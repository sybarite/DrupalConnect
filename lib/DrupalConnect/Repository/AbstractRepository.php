<?php
namespace DrupalConnect\Repository;

/**
 * Base class for all repositories
 */
class AbstractRepository implements \DrupalConnect\Repository
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

    /**
     * Initialize and get a query builder for the document name this repository handles
     *
     * @return \DrupalConnect\Query\Builder
     */
    public function createQueryBuilder()
    {
        return $this->_dm->createQueryBuilder($this->_documentName);
    }
}
