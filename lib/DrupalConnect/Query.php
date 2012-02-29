<?php
namespace DrupalConnect;

class Query
{
    const TYPE_FIND = 1;

    const DOCUMENT_TYPE_NODE = 'node';

    /**
     * Array containing the query data.
     *
     * @var array
     */
    protected $_query = array();

    /**
     * @var \DrupalConnect\Service\Connection
     */
    protected $_connection;

    /**
     * @var string
     */
    protected $_documentType;

    /**
     * @var \DrupalConnect\Service\Connection\Request
     */
    protected $_httpClient;

    public function __construct(\DrupalConnect\Service\Connection $connection, $documentType, array $query)
    {
        $this->_query = $query;
        $this->_connection = $connection;
        $this->_documentType = $documentType;
    }

    public function execute()
    {
        if (!$this->_httpClient)
        {
            $this->_httpClient = new \DrupalConnect\Service\Connection\Request();
        }

        switch ($this->_query['type'])
        {
            case self::TYPE_FIND:

                if ($this->_documentType === self::DOCUMENT_TYPE_NODE)
                {
                    // if querying by nid, then use the node/1.json exposed API
                    if (isset($this->_query['parameters']['nid']) && $this->_query['parameters']['nid']['type'] === 'equals')
                    {
                        $requestUrl = $this->_connection->getEndpoint() . \DrupalConnect\Service\Connection\Request::ENDPOINT_NODE_RESOURCE_RETRIEVE . $this->_query['parameters']['nid']['value'] . '.json';

                        $response = $this->_httpClient->resetParameters(true)
                                                      ->setUri($requestUrl)
                                                      ->request('GET');

                        $singleNode = json_decode($response->getBody(), true);

                        if ($singleNode) // if not false or null
                        {
                            return array($singleNode); // return an array with 1 item node
                        }

                        return $singleNode; // if false or null
                    }
                    else
                    {
                        $requestUrl = $this->_connection->getEndpoint() . \DrupalConnect\Service\Connection\Request::ENDPOINT_NODE_RESOURCE_INDEX . '.json';

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

                        $nodes = (json_decode($response->getBody(), true));

                        return $nodes;
                    }
                }
                else
                {
                    throw new \DrupalConnect\Query\Exception("Could not identify document type.");
                }

                break;
        }

        throw new \DrupalConnect\Query\Exception("Could not execute this query type.");
    }
}
