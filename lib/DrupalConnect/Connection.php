<?php
namespace DrupalConnect;

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

    /**
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->_config = $config;
        $this->_endpoint = $config['endpoint'];
    }

    /**
     * Returns the endpoint URI configured
     *
     * @return string
     */
    public function getEndpoint()
    {
        return $this->_endpoint;
    }

}