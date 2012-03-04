<?php
namespace DrupalConnect;

class Query
{
    const TYPE_FIND = 1;

    const DOCUMENT_TYPE_NODE = 'DrupalConnect\Document\Node';

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

                            $singleNode = json_decode($response->getBody(), true);

                            if (!$singleNode) // if false or null
                            {
                                return null;
                            }

                            return $this->_wrapCursor(array($singleNode)); // return an array with 1 item node
                        }
                        else // multiple results possible, else use the node index $endpoint/node.json?...
                        {
                            $requestUrl = $this->_connection->getEndpoint() . \DrupalConnect\Connection\Request::ENDPOINT_NODE_RESOURCE_INDEX . '.json';

                            $request = $this->_httpClient->resetParameters(true)
                                                         ->setUri($requestUrl);

                            foreach ($this->_query['parameters'] as $field => $param)
                            {
                                if ($param['type'] === 'in')
                                {
                                    $request->setParameterGet("parameters[$field]", implode(',', $param['value']) );
                                }
                            }

                            // if fields to be selected is explicitly defined
                            if (count($this->_query['select']) > 1)
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

                        $requestUrl = $this->_connection->getEndpoint() . \DrupalConnect\Connection\Request::ENDPOINT_NODE_VIEW_RETRIEVE . $this->_query['useView'] . '.json';

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

                        $response = $request->request('GET');

                        $nodeSetData = (json_decode($response->getBody(), true));

                        if (!$nodeSetData || !is_array($nodeSetData))
                        {
                            return null;
                        }

                        // wrap with cursor, BUT USE a CUSTOM HYDRATOR since Node data from views has a different representation
                        return $this->_wrapCursor($nodeSetData, 'DrupalConnect\Hydrator\Views\Node');
                    }
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
            $cursor->setHydrator(new $hydrator($this->_documentName));
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

}
