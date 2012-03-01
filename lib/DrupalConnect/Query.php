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
    protected $_documentType;

    /**
     * @var \DrupalConnect\Connection\Request
     */
    protected $_httpClient;

    public function __construct(\DrupalConnect\DocumentManager $dm, $documentType, array $query)
    {
        $this->_dm = $dm;
        $this->_connection = $dm->getConnection();

        $this->_query = $query;
        $this->_documentType = $documentType;
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

                if ($this->_documentType === self::DOCUMENT_TYPE_NODE)
                {
                    // if querying by nid, then use the node/1.json exposed API
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

                        // initialize the repository for hydration
                        $repository = $this->_dm->getRepository($this->_documentType);

                        return array($repository->getHydratedDocument($singleNode)); // return an array with 1 item node
                    }
                    else
                    {
                        $requestUrl = $this->_connection->getEndpoint() . \DrupalConnect\Connection\Request::ENDPOINT_NODE_RESOURCE_INDEX . '.json';

                        $request = $this->_httpClient->resetParameters(true)
                                                      ->setUri($requestUrl);

                        foreach ($this->_query['parameters'] as $field => $param)
                        {
                            if ($param['type'] === 'in')
                            {
                                $request->setParameterGet("parameters[$field]", (implode(',', $param['value'])));
                            }
                        }

                        $response = $request->request('GET');

                        $nodeSetData = (json_decode($response->getBody(), true));

                        if (!$nodeSetData || !is_array($nodeSetData))
                        {
                            return null;
                        }

                        $nodeSet = array();

                        // initialize the repository for hydration
                        $repository = $this->_dm->getRepository($this->_documentType);

                        foreach ($nodeSetData as $n)
                        {
                            $nodeSet[] = $repository->getHydratedDocument($n);
                        }

                        return $nodeSet;
                    }
                }
                else
                {
                    throw new \DrupalConnect\Query\Exception("Could not identify document type: {$this->_documentType}");
                }

                break;
        }

        throw new \DrupalConnect\Query\Exception("Could not execute query type: " . $this->_query['type']);
    }
}
