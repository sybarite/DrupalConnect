<?php
namespace DrupalConnect\Connection;

class Request extends \Zend_Http_Client
{
    const ENDPOINT_NODE_RESOURCE_RETRIEVE = 'node/'; // example node/1.json
    const ENDPOINT_NODE_RESOURCE_INDEX = 'node';

    const ENDPOINT_NODE_VIEW_RETRIEVE = 'views/'; // example views/article_recent.json

    public function __construct()
    {
        parent::__construct(null, array(
            'adapter' => 'Zend_Http_Client_Adapter_Curl')
        );
    }
}
