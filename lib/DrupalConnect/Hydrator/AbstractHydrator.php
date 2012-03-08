<?php
namespace DrupalConnect\Hydrator;

/**
 * Base class inherited by all document hydrators
 */
abstract class AbstractHydrator implements \DrupalConnect\Hydrator
{
    /**
     * @var \DrupalConnect\DocumentManager
     */
    protected $_dm;

    /**
     * Document Class this Repository Handles
     *
     * @var string
     */
    protected $_documentName;


    /**
     * @param \DrupalConnect\DocumentManager $dm
     * @param string $documentName
     */
    public function __construct(\DrupalConnect\DocumentManager $dm, $documentName)
    {
        $this->_dm = $dm;
        $this->_documentName = $documentName;
    }
}
