<?php
namespace DrupalConnect;

/**
 * Wraps the results of all queries
 */
class Cursor implements Iterator
{
    /**
     * Current position in the cursor
     *
     * @var int
     */
    protected $_position;

    /**
     * The documents data being wrapped by this cursor
     *
     * @var array
     */
    protected $_documentSetData;

    /**
     * @var string
     */
    protected $_documentType;

    /**
     * @var DocumentManager
     */
    protected $_dm;

    /**
     * Whether to hydrate the cursor documents retreived or not?
     *
     * @var bool
     */
    protected $_hydrate = true;

    /**
     * @var \DrupalConnect\Hydrator
     */
    protected $_hydrator;

    /**
     * @param DocumentManager $dm
     * @param $documentType
     * @param array $documentSetData
     */
    public function __construct(DocumentManager $dm, $documentType, array $documentSetData)
    {
        $this->_dm = $dm;
        $this->_documentType = $documentType;
        $this->_documentSetData = $documentSetData;

        $this->_position = 0;

    }

    /**
     * Get the data which the cursor wraps.
     *
     * @return array
     */
    public function toArray()
    {
        return $this->_documentSetData;
    }

    /**
     * @param $hydrate
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
     * Set the hydrator to be used
     *
     * @param Hydrator $hydrator
     */
    public function setHydrator(Hydrator $hydrator)
    {
        $this->_hydrator = $hydrator;
    }

    /**
     * Initialize once and return the hydrator to be used
     *
     * @return Hydrator
     */
    protected function _getHydrator()
    {
        if (!$this->_hydrator)
        {
            $this->_hydrator = $this->_dm->getHydrator($this->_documentType);
        }

        return $this->_hydrator;
    }

    public function rewind()
    {
        $this->_position = 0;
    }

    public function current()
    {
        $current = $this->_documentSetData[$this->_position];

        if ($current && $this->_hydrate)
        {
            $hydrator = $this->_getHydrator();

            return $hydrator->hydrate($current);
        }

        return $current;
    }

    public function key()
    {
        return $this->_position;
    }

    public function next()
    {
        ++$this->_position;
    }

    public function valid()
    {
        return isset($this->_documentSetData[$this->_position]);
    }

    public function count()
    {
        return count($this->_documentSetData);
    }

//    /**
//     * Get the next element
//     *
//     * @return Document
//     */
//    public function getNext()
//    {
//        $this->next();
//        return $this->current();
//    }

    /**
     * Get the first single result from the cursor.
     *
     * @return array $document  The single document.
     */
    public function getSingleResult()
    {
        $result = null;
        $this->valid() ?: $this->next();
        if ($this->valid()) {
            $result = $this->current();
        }
        $this->rewind();
        return $result ? $result : null;
    }



}
