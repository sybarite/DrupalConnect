<?php
namespace DrupalConnect\Service;

class Connection
{
    /**
     * HTTP Client used to query all web services
     *
     * @var \Zend_Http_Client
     */
    protected $_httpClient;

    /**
     * @var string
     */
    protected $_endpoint;

    /**
     * Configuration
     *
     * @var array
     */
    protected $_config;

    public function __construct(array $config)
    {
        $this->_config = $config;

        $this->_endpoint = $config['endpoint'];
    }

    public function createQueryBuilder($documentType)
    {
        return new \DrupalConnect\Query\Builder($this, $documentType);
    }

    public function getEndpoint()
    {
        return $this->_endpoint;
    }



}