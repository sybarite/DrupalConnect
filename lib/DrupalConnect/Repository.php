<?php
namespace DrupalConnect;

/**
 * Serves as a repository for documents with generic as well as business specific methods for retrieving documents.
 * Future Plans: This class is designed for inheritance and users can subclass this class to write their own repositories with business-specific methods to locate documents.
 */
abstract class Repository
{
    protected $_dm;

    /**
     * Document Class this Repository Handles
     *
     * @var string
     */
    protected $_documentName;

    /**
     * @param DocumentManager $dm
     * @param string $documentName
     */
    public function __construct(\DrupalConnect\DocumentManager $dm, $documentName)
    {
        $this->_dm = $dm;
        $this->_documentName = $documentName;
    }

    /**
     * DO NOT USE THIS FUNCTION, it's for internal usage only!
     *
     * Get the hydrated version of the document.
     *
     * @param array $data
     * @return Document
     */
    public function getHydratedDocument(array $data)
    {
        return $this->_hydrate($data);
    }

    /**
     * @param array $data
     * @return Document
     */
    protected function _hydrate(array $data)
    {
        /**
             * @var Document $node
             */
        $node = new $this->_documentName();

        $node->setDocumentArray($data);

        foreach ($data as $key => $val)
        {
            $setterFunction = $this->_getSetterFunctionName($key);

            if (method_exists($node, $setterFunction))
            {
                $node->{$setterFunction}($val);
            }
        }

        return $node;
    }

    /**
     * Returns the setter function which must be defined to set a field name
     *
     * @param string $fieldName
     * @return string
     */
    protected function _getSetterFunctionName($fieldName)
    {
        $fieldName = ucfirst($fieldName);

        if (strpos($fieldName, '_') !== false) // if under score exists
        {
            $length = strlen($fieldName);
            for ($i=0; $i<$length; $i++)
            {
                if ($fieldName[$i] === '_' && ($i + 1) < $length)
                {
                    $fieldName[$i + 1] = strtoupper($fieldName[$i + 1]);
                }
            }

            $fieldName = str_replace('_', '', $fieldName);
        }

        return "set$fieldName";
    }
}
