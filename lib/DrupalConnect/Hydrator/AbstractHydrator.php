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

    /**
     * Get the hydrated version of the document.
     *
     * @param array $data
     * @return \DrupalConnect\Document
     */
    public function hydrate(array $data)
    {
        /**
                 * @var \DrupalConnect\Document $node
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
