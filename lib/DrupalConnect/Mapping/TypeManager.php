<?php
namespace DrupalConnect\Mapping;

class TypeManager
{
    /**
     * Array of string types mapped to their type class.
     */
    private static $typesMap = array(
        'boolean' => 'DrupalConnect\Mapping\Type\BooleanType',
        'integer' => 'DrupalConnect\Mapping\Type\IntegerType',
        'string'  => 'DrupalConnect\Mapping\Type\StringType',
        'date'    => 'DrupalConnect\Mapping\Type\DateType',
    );


    /**
     * Array of instantiated type classes.
     */
    private static $types = array();

    /**
     * Get a Type instance.
     *
     * @param string $type The type name.
     * @return \DrupalConnect\Mapping\Type $type
     * @throws \InvalidArgumentException
     */
    public static function getType($type)
    {
        if ( ! isset(self::$typesMap[$type])) {
            throw new \InvalidArgumentException(sprintf('Invalid type specified "%s".', $type));
        }
        if ( ! isset(self::$types[$type])) {
            $className = self::$typesMap[$type];
            self::$types[$type] = new $className;
        }
        return self::$types[$type];
    }

    /**
     * Get the types array map which holds all registered types and the corresponding
     * type class
     *
     * @return array $typesMap
     */
    public static function getTypesMap()
    {
        return self::$typesMap;
    }
}
