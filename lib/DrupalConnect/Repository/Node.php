<?php
namespace DrupalConnect\Repository;

class Node
{
    const DOC_TYPE = 'node';

    protected $_connection;

    public function __construct(\DrupalConnect\Service\Connection $connection)
    {
        $this->_connection = $connection;
    }

    public function find($nid)
    {
        $qb = $this->_connection->createQueryBuilder(self::DOC_TYPE);

        $result = $qb->find()
                     ->field('nid')->equals($nid)
                     ->getQuery()
                     ->execute();

        if (!$result || !is_array($result))
            return null;

        return $this->_hydrate($result[0]);
    }

    protected function _hydrate($data)
    {
        $node = new \DrupalConnect\Document\Node();

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