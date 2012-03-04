<?php
namespace DrupalConnect\Hydrator;

/**
 * Base class inherited by all document hydrators
 */
abstract class AbstractHydrator implements \DrupalConnect\Hydrator
{
    /**
     * Document Class this Repository Handles
     *
     * @var string
     */
    protected $_documentName;

    /**
     * @param string $documentName
     */
    public function __construct($documentName)
    {
        $this->_documentName = $documentName;
    }
}
