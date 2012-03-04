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
        'select' => array(), // fields to select (if empty, selects all fields)
        'pageSize' => null, // Number of records to get per page
        'page' => null, // zero-based index of the page

        'useView' => null, // name of the view to use in case a view has to be used
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
     * Whether to hydrate or not?
     *
     * @var bool
     */
    protected $_hydrate = true;

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
     * Get the built-query which can be executed
     *
     * @return \DrupalConnect\Query
     */
    public function getQuery()
    {
        $query = new \DrupalConnect\Query($this->_dm, $this->_documentType, $this->_query);
        $query->setHydrate($this->_hydrate);

        return $query;
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

    /**
     * Whether to hydrate this query or not
     *
     * @param bool $bool default true
     * @return \DrupalConnect\Query\Builder
     */
    public function hydrate($bool)
    {
        $this->_hydrate = $bool;
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

    /**
     * The fields to select.
     *
     * @param string $fieldName
     * @return Builder
     */
    public function select($fieldName = null)
    {
        $select = func_get_args();
        foreach ($select as $fieldName) {
            $this->_query['select'][$fieldName] = 1;
        }
        return $this;
    }

    /**
     * Set the zero-based index of the page
     *
     * @param int $page
     * @return Builder
     */
    public function page($page)
    {
        $this->_query['page'] = $page;
        return $this;
    }

    /**
     * Set the number of records to get per page
     *
     * @param int $pageSize
     * @return Builder
     */
    public function pageSize($pageSize)
    {
        $this->_query['pageSize'] = $pageSize;
        return $this;
    }

    /**
     * @param $viewName
     * @return Builder
     */
    public function useView($viewName)
    {
        $this->_query['useView'] = $viewName;
        return $this;
    }
}
