<?php
namespace DrupalConnect;

class Query
{
    const TYPE_FIND = 1;

    const DOCUMENT_TYPE_NODE = 'DrupalConnect\Document\Node';
    const DOCUMENT_TYPE_FILE = 'DrupalConnect\Document\File';
    const DOCUMENT_TYPE_FILE_IMAGE = 'DrupalConnect\Document\File\Image';
    const DOCUMENT_TYPE_TAXANOMY_VOCABULARY = 'DrupalConnect\Document\Taxanomy\Vocabulary';
    const DOCUMENT_TYPE_TAXANOMY_TERM = 'DrupalConnect\Document\Taxanomy\Term';

    /**
     * Array containing the query data.
     *
     * @var array
     */
    protected $_query = array();

    /**
     * @var DocumentManager
     */
    protected $_dm;

    /**
     * @var \DrupalConnect\Connection
     */
    protected $_connection;

    /**
     * @var string
     */
    protected $_documentName;

    /**
     * Whether to hydrate or not?
     *
     * @var bool
     */
    protected $_hydrate = true;

    /**
     * @var \DrupalConnect\Connection\Request
     */
    protected $_httpClient;

    public function __construct(\DrupalConnect\DocumentManager $dm, $documentName, array $query)
    {
        $this->_dm = $dm;
        $this->_connection = $dm->getConnection();

        $this->_query = $query;
        $this->_documentName = $documentName;
    }

    public function execute()
    {
        if (!$this->_httpClient)
        {
            $this->_httpClient = new \DrupalConnect\Connection\Request();
        }

        switch ($this->_query['type'])
        {
            case self::TYPE_FIND:

                if ($this->_documentName === self::DOCUMENT_TYPE_NODE ||
                    is_subclass_of($this->_documentName, self::DOCUMENT_TYPE_NODE))
                {
                    return $this->_executeNodeFind();
                }
                elseif ($this->_documentName === self::DOCUMENT_TYPE_FILE ||
                    is_subclass_of($this->_documentName, self::DOCUMENT_TYPE_FILE))
                {
                    return $this->_executeFileFind();
                }
                elseif ($this->_documentName === self::DOCUMENT_TYPE_TAXANOMY_VOCABULARY ||
                    is_subclass_of($this->_documentName, self::DOCUMENT_TYPE_TAXANOMY_VOCABULARY))
                {
                    return $this->_executeVocabularyFind();
                }
                if ($this->_documentName === self::DOCUMENT_TYPE_TAXANOMY_TERM ||
                    is_subclass_of($this->_documentName, self::DOCUMENT_TYPE_TAXANOMY_TERM))
                {
                    return $this->_executeTermFind();
                }
                else
                {
                    throw new \DrupalConnect\Query\Exception("Could not identify document type: {$this->_documentName}");
                }

                break;
        }

        throw new \DrupalConnect\Query\Exception("Could not execute query type: " . $this->_query['type']);
    }

    /**
     * Deals with processing a Node Find
     *
     * @return Cursor|null
     */
    protected function _executeNodeFind()
    {
        // if no VIEW selected
        if (!$this->_query['useView'])
        {
            // if querying by nid, then use the $endpoint/node/1.json where only ONE result is returned
            if (isset($this->_query['parameters']['nid']) && $this->_query['parameters']['nid']['type'] === 'equals')
            {
                $requestUrl = $this->_connection->getEndpoint() . \DrupalConnect\Connection\Request::ENDPOINT_NODE_RESOURCE_RETRIEVE . $this->_query['parameters']['nid']['value'] . '.json';

                $response = $this->_httpClient->resetParameters(true)
                                              ->setUri($requestUrl)
                                              ->request('GET');
                $this->_validateServerResponse($response);

                $singleNode = json_decode($response->getBody(), true);

                if (!$singleNode) // if false or null
                {
                    return null;
                }

                return $this->_wrapCursor(array($singleNode)); // return an array with 1 item node
            }
            else // multiple results possible, so use the node index $endpoint/node.json?...
            {
                $requestUrl = $this->_connection->getEndpoint() . \DrupalConnect\Connection\Request::ENDPOINT_NODE_RESOURCE_INDEX . '.json';

                $request = $this->_httpClient->resetParameters(true)
                                             ->setUri($requestUrl);

                foreach ($this->_query['parameters'] as $field => $param)
                {
                    if ($param['type'] === 'equals')
                    {
                        $request->setParameterGet("parameters[$field]", $this->_convertToDrupalValue($param['value']) );
                    }
                    else if ($param['type'] === 'in')
                    {
                        foreach ($param['value'] as $key => $val) // convert the IN values to drupal equivalent values first
                        {
                            $param['value'][$key] = $this->_convertToDrupalValue($val);
                        }

                        $request->setParameterGet("parameters[$field]", implode(',', $param['value']) );
                    }
                }

                // if fields to be selected is explicitly defined
                if (count($this->_query['select']) > 0)
                {
                    /**
                                     * Note:
                                     * 1 > Even if the fields are explicitly selected, the 'nid' must always be returned.
                                     *       This is not just important because it's the primary identifier but also because for some reason it makes
                                     *       the time taken for drupal to return results faster.
                                     *
                                     * 2 > Whether you like it or not, drupal will for some reason always return the 'uri' field.
                                     */
                    $this->_query['select']['nid'] = 1;
                    $request->setParameterGet('fields', implode(',', array_keys($this->_query['select'])) );
                }

                // set the page size
                if ($this->_query['pageSize'])
                {
                    $request->setParameterGet('pagesize', $this->_query['pageSize']);
                }

                // set the page number
                if ($this->_query['page'])
                {
                    $request->setParameterGet('page', $this->_query['page']);
                }


                $response = $request->request('GET');

                $this->_validateServerResponse($response);

                $nodeSetData = (json_decode($response->getBody(), true));

                if (!$nodeSetData || !is_array($nodeSetData))
                {
                    return null;
                }

                return $this->_wrapCursor($nodeSetData);

            }
        }
        else
        { // a VIEW is to be used for the find

            $requestUrl = $this->_connection->getEndpoint() . \DrupalConnect\Connection\Request::ENDPOINT_VIEWS_RESOURCE_RETRIEVE . $this->_query['useView'] . '.json';

            $request = $this->_httpClient->resetParameters(true)
                                         ->setUri($requestUrl);

            // set the limit
            if ($this->_query['limit'])
            {
                $request->setParameterGet('limit', $this->_query['limit']);
            }

            // set the offset
            if ($this->_query['skip'])
            {
                $request->setParameterGet('offset', $this->_query['skip']);
            }

            // set the contextual filters
            if ($this->_query['contextualFilters'])
            {
                foreach ($this->_query['contextualFilters'] as $index => $filter)
                {
                    $request->setParameterGet("args[$index]", $filter);
                }
            }

            // set the exposed filters
            foreach ($this->_query['filters'] as $field => $param)
            {
                // if simple value passed
                if (!is_array($param))
                {
                    $request->setParameterGet("filters[$field]", $this->_convertToDrupalExposedFilterValue($param['value']) );
                }
                else
                {
                    if (isset($param['operator'])) // if operator is set
                    {
                        $request->setParameterGet("filters[{$field}_op]", $this->_convertToDrupalExposedFilterValue($param['operator']));
                        unset($param['operator']);
                    }

                    // if filter value a range, etc
                    foreach ($param as $key => $val)
                    {
                        $request->setParameterGet("filters[$field][$key]", $this->_convertToDrupalExposedFilterValue($val) );
                    }
                }
            }

            $response = $request->request('GET');

            $this->_validateServerResponse($response);

            $nodeSetData = (json_decode($response->getBody(), true));

            if (!$nodeSetData || !is_array($nodeSetData))
            {
                return null;
            }

            // wrap with cursor, BUT USE a CUSTOM HYDRATOR since Node data from views has a different representation
            return $this->_wrapCursor($nodeSetData, 'DrupalConnect\Hydrator\Views\Node');
        }
    }

    /**
     * Deals with processing a File or File\Image FIND
     *
     * @return Cursor|null
     */
    protected function _executeFileFind()
    {
        $isImage = ($this->_documentName === self::DOCUMENT_TYPE_FILE_IMAGE || is_subclass_of($this->_documentName, self::DOCUMENT_TYPE_FILE_IMAGE));

        // if no VIEW selected
        if (!$this->_query['useView'])
        {
            // if querying by nid, then use the $endpoint/node/1.json where only ONE result is returned
            if (isset($this->_query['parameters']['fid']) && $this->_query['parameters']['fid']['type'] === 'equals')
            {
                $requestUrl = $this->_connection->getEndpoint() . \DrupalConnect\Connection\Request::ENDPOINT_FILE_RESOURCE_RETRIEVE . $this->_query['parameters']['fid']['value'] . '.json';

                $request = $this->_httpClient->resetParameters(true)
                                              ->setUri($requestUrl);


                $request->setParameterGet('file_contents', 0);

                if ($isImage)
                {
                    // do not get the image styles for now
                    $request->setParameterGet('image_styles', 0);
                }

                $response = $request->request('GET');

                $this->_validateServerResponse($response);

                $singleFile = json_decode($response->getBody(), true);

                if (!$singleFile) // if false or null
                {
                    return null;
                }

                return $this->_wrapCursor(array($singleFile)); // return an array with 1 item file
            }
            else // multiple results possible, so use the file index $endpoint/file.json?...
            {
                $requestUrl = $this->_connection->getEndpoint() . \DrupalConnect\Connection\Request::ENDPOINT_FILE_RESOURCE_INDEX . '.json';

                $request = $this->_httpClient->resetParameters(true)
                                             ->setUri($requestUrl);

                foreach ($this->_query['parameters'] as $field => $param)
                {
                    if ($param['type'] === 'equals')
                    {
                        $request->setParameterGet("parameters[$field]", $this->_convertToDrupalValue($param['value']) );
                    }
                    else if ($param['type'] === 'in')
                    {
                        foreach ($param['value'] as $key => $val) // convert the IN values to drupal equivalent values first
                        {
                            $param['value'][$key] = $this->_convertToDrupalValue($val);
                        }

                        $request->setParameterGet("parameters[$field]", implode(',', $param['value']) );
                    }
                }

                // if fields to be selected is explicitly defined
                if (count($this->_query['select']) > 0)
                {
                    /**
                                 * Note:
                                 * 1 > Even if the fields are explicitly selected, the 'nid' must always be returned.
                                 *       This is not just important because it's the primary identifier but also because for some reason it makes
                                 *       the time taken for drupal to return results faster.
                                 *
                                 * 2 > Whether you like it or not, drupal will for some reason always return the 'uri' field.
                                 */
                    $this->_query['select']['fid'] = 1;
                    $request->setParameterGet('fields', implode(',', array_keys($this->_query['select'])) );
                }

                // set the page size
                if ($this->_query['pageSize'])
                {
                    $request->setParameterGet('pagesize', $this->_query['pageSize']);
                }

                // set the page number
                if ($this->_query['page'])
                {
                    $request->setParameterGet('page', $this->_query['page']);
                }


                $response = $request->request('GET');

                $this->_validateServerResponse($response);

                $nodeSetData = (json_decode($response->getBody(), true));

                if (!$nodeSetData || !is_array($nodeSetData))
                {
                    return null;
                }

                // remove the uri since the uri received from the index resource is not what we want
                foreach ($nodeSetData as $index => $f)
                {
                    if (isset($f['uri']))
                    {
                        unset($nodeSetData[$index]['uri']);
                    }
                }

                return $this->_wrapCursor($nodeSetData);

            }
        }
        else
        { // a VIEW is to be used for the find

            $requestUrl = $this->_connection->getEndpoint() . \DrupalConnect\Connection\Request::ENDPOINT_VIEWS_RESOURCE_RETRIEVE . $this->_query['useView'] . '.json';

            $request = $this->_httpClient->resetParameters(true)
                                         ->setUri($requestUrl);

            // set the limit
            if ($this->_query['limit'])
            {
                $request->setParameterGet('limit', $this->_query['limit']);
            }

            // set the offset
            if ($this->_query['skip'])
            {
                $request->setParameterGet('offset', $this->_query['skip']);
            }

            // set the contextual filters
            if ($this->_query['contextualFilters'])
            {
                foreach ($this->_query['contextualFilters'] as $index => $filter)
                {
                    $request->setParameterGet("args[$index]", $filter);
                }
            }

            // set the exposed filters
            foreach ($this->_query['filters'] as $field => $param)
            {
                // if simple value passed
                if (!is_array($param))
                {
                    $request->setParameterGet("filters[$field]", $this->_convertToDrupalExposedFilterValue($param['value']) );
                }
                else
                {
                    if (isset($param['operator'])) // if operator is set
                    {
                        $request->setParameterGet("filters[{$field}_op]", $this->_convertToDrupalExposedFilterValue($param['operator']));
                        unset($param['operator']);
                    }

                    // if filter value a range, etc
                    foreach ($param as $key => $val)
                    {
                        $request->setParameterGet("filters[$field][$key]", $this->_convertToDrupalExposedFilterValue($val) );
                    }
                }
            }

            $response = $request->request('GET');

            $this->_validateServerResponse($response);

            $nodeSetData = (json_decode($response->getBody(), true));

            if (!$nodeSetData || !is_array($nodeSetData))
            {
                return null;
            }

            // wrap with cursor, BUT USE a CUSTOM HYDRATOR since Node data from views has a different representation
            if ($isImage)
            {
                return $this->_wrapCursor($nodeSetData, 'DrupalConnect\Hydrator\Views\File\Image');
            }
            else
            {
                return $this->_wrapCursor($nodeSetData, 'DrupalConnect\Hydrator\Views\File');
            }
        }
    }

    /**
     * Deals with processing a Vocabulary Find
     *
     * @return Cursor|null
     */
    protected function _executeVocabularyFind()
    {
        // if querying by vid, then use the $endpoint/taxonomy_vocabulary/vid.json where only ONE result is returned
        if (isset($this->_query['parameters']['vid']) && $this->_query['parameters']['vid']['type'] === 'equals')
        {
            $requestUrl = $this->_connection->getEndpoint() . \DrupalConnect\Connection\Request::ENDPOINT_FILE_TAXAONOMY_VOCABULARY_RETRIEVE . $this->_query['parameters']['vid']['value'] . '.json';

            $response = $this->_httpClient->resetParameters(true)
                                          ->setUri($requestUrl)
                                          ->request('GET');

            $this->_validateServerResponse($response);

            $singleVocabulary = json_decode($response->getBody(), true);

            if (!$singleVocabulary) // if false or null
            {
                return null;
            }

            return $this->_wrapCursor(array($singleVocabulary)); // return an array with 1 item node
        }
        else // multiple results possible, so use the node index $endpoint/taxaonomy_vocabulary.json?...
        {
            $requestUrl = $this->_connection->getEndpoint() . \DrupalConnect\Connection\Request::ENDPOINT_FILE_TAXAONOMY_VOCABULARY_INDEX . '.json';

            $request = $this->_httpClient->resetParameters(true)
                                         ->setUri($requestUrl);

            foreach ($this->_query['parameters'] as $field => $param)
            {
                if ($param['type'] === 'equals')
                {
                    $request->setParameterGet("parameters[$field]", $this->_convertToDrupalValue($param['value']) );
                }
                else if ($param['type'] === 'in')
                {
                    foreach ($param['value'] as $key => $val) // convert the IN values to drupal equivalent values first
                    {
                        $param['value'][$key] = $this->_convertToDrupalValue($val);
                    }

                    $request->setParameterGet("parameters[$field]", implode(',', $param['value']) );
                }
            }

            // if fields to be selected is explicitly defined
            if (count($this->_query['select']) > 0)
            {
                /**
                                 * Note:
                                 * 1 > Even if the fields are explicitly selected, the 'nid' must always be returned.
                                 *       This is not just important because it's the primary identifier but also because for some reason it makes
                                 *       the time taken for drupal to return results faster.
                                 *
                                 * 2 > Whether you like it or not, drupal will for some reason always return the 'uri' field.
                                 */
                $this->_query['select']['vid'] = 1;
                $request->setParameterGet('fields', implode(',', array_keys($this->_query['select'])) );
            }

            // set the page size
            if ($this->_query['pageSize'])
            {
                $request->setParameterGet('pagesize', $this->_query['pageSize']);
            }

            // set the page number
            if ($this->_query['page'])
            {
                $request->setParameterGet('page', $this->_query['page']);
            }


            $response = $request->request('GET');

            $this->_validateServerResponse($response);

            $vocabularySetData = (json_decode($response->getBody(), true));

            if (!$vocabularySetData || !is_array($vocabularySetData))
            {
                return null;
            }

            return $this->_wrapCursor($vocabularySetData);

        }
    }

    /**
     * Deals with processing a Term Find
     *
     * @return Cursor|null
     */
    protected function _executeTermFind()
    {
        // if no VIEW selected
        if (!$this->_query['useView'])
        {
            // if querying by nid, then use the $endpoint/node/1.json where only ONE result is returned
            if (isset($this->_query['parameters']['tid']) && $this->_query['parameters']['tid']['type'] === 'equals')
            {
                $requestUrl = $this->_connection->getEndpoint() . \DrupalConnect\Connection\Request::ENDPOINT_FILE_TAXAONOMY_TERM_RESOURCE_RETRIEVE . $this->_query['parameters']['tid']['value'] . '.json';

                $response = $this->_httpClient->resetParameters(true)
                                              ->setUri($requestUrl)
                                              ->request('GET');

                $this->_validateServerResponse($response);

                $singleTerm = json_decode($response->getBody(), true);

                if (!$singleTerm) // if false or null
                {
                    return null;
                }

                return $this->_wrapCursor(array($singleTerm)); // return an array with 1 item node
            }
            else // multiple results possible, so use the node index $endpoint/taxonomy_term.json?...
            {
                $requestUrl = $this->_connection->getEndpoint() . \DrupalConnect\Connection\Request::ENDPOINT_FILE_TAXAONOMY_TERM_RESOURCE_INDEX . '.json';

                $request = $this->_httpClient->resetParameters(true)
                                             ->setUri($requestUrl);

                foreach ($this->_query['parameters'] as $field => $param)
                {
                    if ($param['type'] === 'equals')
                    {
                        $request->setParameterGet("parameters[$field]", $this->_convertToDrupalValue($param['value']) );
                    }
                    else if ($param['type'] === 'in')
                    {
                        foreach ($param['value'] as $key => $val) // convert the IN values to drupal equivalent values first
                        {
                            $param['value'][$key] = $this->_convertToDrupalValue($val);
                        }

                        $request->setParameterGet("parameters[$field]", implode(',', $param['value']) );
                    }
                }

                // if fields to be selected is explicitly defined
                if (count($this->_query['select']) > 0)
                {
                    /**
                                     * Note:
                                     * 1 > Even if the fields are explicitly selected, the 'tid' must always be returned.
                                     *       This is not just important because it's the primary identifier but also because for some reason it makes
                                     *       the time taken for drupal to return results faster.
                                     *
                                     * 2 > Whether you like it or not, drupal will for some reason always return the 'uri' field.
                                     */
                    $this->_query['select']['tid'] = 1;
                    $request->setParameterGet('fields', implode(',', array_keys($this->_query['select'])) );
                }

                // set the page size
                if ($this->_query['pageSize'])
                {
                    $request->setParameterGet('pagesize', $this->_query['pageSize']);
                }

                // set the page number
                if ($this->_query['page'])
                {
                    $request->setParameterGet('page', $this->_query['page']);
                }


                $response = $request->request('GET');

                $this->_validateServerResponse($response);

                $termSetData = (json_decode($response->getBody(), true));

                if (!$termSetData || !is_array($termSetData))
                {
                    return null;
                }

                return $this->_wrapCursor($termSetData);

            }
        }
        else
        { // a VIEW is to be used for the find

            $requestUrl = $this->_connection->getEndpoint() . \DrupalConnect\Connection\Request::ENDPOINT_VIEWS_RESOURCE_RETRIEVE . $this->_query['useView'] . '.json';

            $request = $this->_httpClient->resetParameters(true)
                                         ->setUri($requestUrl);

            // set the limit
            if ($this->_query['limit'])
            {
                $request->setParameterGet('limit', $this->_query['limit']);
            }

            // set the offset
            if ($this->_query['skip'])
            {
                $request->setParameterGet('offset', $this->_query['skip']);
            }

            // set the contextual filters
            if ($this->_query['contextualFilters'])
            {
                foreach ($this->_query['contextualFilters'] as $index => $filter)
                {
                    $request->setParameterGet("args[$index]", $filter);
                }
            }

            // set the exposed filters
            foreach ($this->_query['filters'] as $field => $param)
            {
                // if simple value passed
                if (!is_array($param))
                {
                    $request->setParameterGet("filters[$field]", $this->_convertToDrupalExposedFilterValue($param['value']) );
                }
                else
                {
                    if (isset($param['operator'])) // if operator is set
                    {
                        $request->setParameterGet("filters[{$field}_op]", $this->_convertToDrupalExposedFilterValue($param['operator']));
                        unset($param['operator']);
                    }

                    // if filter value a range, etc
                    foreach ($param as $key => $val)
                    {
                        $request->setParameterGet("filters[$field][$key]", $this->_convertToDrupalExposedFilterValue($val) );
                    }
                }
            }

            $response = $request->request('GET');

            $this->_validateServerResponse($response);
            

            $termSetData = (json_decode($response->getBody(), true));

            if (!$termSetData || !is_array($termSetData))
            {
                return null;
            }

            // wrap with cursor, BUT USE a CUSTOM HYDRATOR since Node data from views has a different representation
            return $this->_wrapCursor($termSetData, 'DrupalConnect\Hydrator\Views\Vocabulary\Term');
        }
    }

    /**
     * Wrap an iterable cursor around the data
     *
     * @param array $documentSetData
     * @param string|null $hydrator Hydrator Class to use. If left blank, it will pick from the mapping in DocumentManager
     * @return \DrupalConnect\Cursor
     */
    protected function _wrapCursor(array $documentSetData, $hydrator = null)
    {
        $cursor = new Cursor($this->_dm, $this->_documentName, $documentSetData);

        $cursor->setHydrate($this->_hydrate);

        if ($hydrator)
        {
            /**
                     * @var \DrupalConnect\Hydrator $hydrator
                     */
            $hydrator = new $hydrator($this->_dm, $this->_documentName);
            $cursor->setHydrator($hydrator);
        }

        return $cursor;
    }

    /**
     * @param bool $hydrate
     */
    public function setHydrate($hydrate)
    {
        $this->_hydrate = $hydrate;
    }

    /**
     * @return bool
     */
    public function getHydrate()
    {
        return $this->_hydrate;
    }

    /**
     * Get the first single result from the cursor.
     *
     * @return Cursor|null
     */
    public function getSingleResult()
    {
        $results = $this->execute();

        if ($results)
        {
            return $results->getSingleResult();
        }

        return $results;
    }

    /**
     * Convert a query builder field value to it's equivalent drupal request variable value.
     * For e.g DateTime is converted to a timestamp
     *
     * @param $value
     * @return \DateTime|int|string|null
     */
    protected function _convertToDrupalValue($value)
    {
        switch(gettype($value))
        {
            case 'boolean':
                return (int)$value;

            case 'object':

                switch(get_class($value))
                {
                    case 'DateTime':
                        /**
                         * @var \DateTime $value
                         */
                        return $value->getTimestamp();
                }

                break;
        }

        return $value;
    }

    /**
     * Convert a query builder filter value to it's equivalent drupal exposed filter request variable value.
     * The reason this is a separate function cause DateTime for example are represented differently
     *
     * @param $value
     * @return \DateTime|int|string|null
     */
    protected function _convertToDrupalExposedFilterValue($value)
    {
        switch(gettype($value))
        {
            case 'boolean':
                return (int)$value;

            case 'object':

                switch(get_class($value))
                {
                    case 'DateTime':
                        /**
                         * @var \DateTime $value
                         */
                        return date( 'Y-m-d H:i:s', $value->getTimestamp());
                }

                break;
        }

        return $value;
    }

    /**
     * Checks for any server errors and throws the exception status code if 500 and
     * displays the drupal under maintenance page when 503.
     *
     * @param $response \Zend_Http_Response
     * @throws \DrupalConnect\Query\Exception
     */
    protected function _validateServerResponse($response)
    {
        if ($response->isError())
        {
            throw new \DrupalConnect\Connection\Exception($response->getMessage(), $response->getStatus());
        }
    }
}