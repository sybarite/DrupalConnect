<?php
namespace DrupalConnect\Query;

/**
 *
 */
class Builder
{
    /**
     * Array containing the query data.
     *
     * @var array
     */
    protected $_query = array(
        'type' => \DrupalConnect\Query::TYPE_FIND,
        'parameters' => array(), // the where (filters) for the query
    );

    /**
     * @var \DrupalConnect\DocumentManager
     */
    protected $_dm;

    /**
     * @var string
     */
    protected $_documentType;

    /**
     * The current field we are operating on.
     *
     * @var string
     */
    protected $_currentField;


    /**
     * @param \DrupalConnect\DocumentManager $dm
     * @param string $documentType
     */
    public function __construct(\DrupalConnect\DocumentManager $dm, $documentType)
    {
        $this->_dm = $dm;
        $this->_documentType = $documentType;
    }

    /**
     * Get the type of this query.
     *
     * @return string $type
     */
    public function getType()
    {
        return $this->_query['type'];
    }

    /**
     * @param $documentType
     * @return Builder
     */
    public function setDocumentType($documentType)
    {
        $this->_documentType = $documentType;
        return $this;
    }

    public function getDocumentType()
    {
        return $this->_documentType;
    }

    /**
     * Change the query type to find and optionally set and change the class being queried.
     *
     * @return Builder
     */
    public function find()
    {
        $this->_query['type'] = \DrupalConnect\Query::TYPE_FIND;
        return $this;
    }

    public function field($field)
    {
        $this->_currentField = $field;
        return $this;
    }

    public function equals($value)
    {
        $this->_query['parameters'][$this->_currentField] = array(
            'type' => 'equals',
            'value' => $value
        );
        return $this;
    }

    public function in(array $values)
    {
        $this->_query['parameters'][$this->_currentField] = array(
            'type' => 'in',
            'value' => $values
        );
        return $this;
    }

    public function getQuery()
    {
        return new \DrupalConnect\Query($this->_dm, $this->_documentType, $this->_query);
    }

}
