<?php
namespace DrupalConnect\Mapping\Type;

/**
 * The String type
 */
class StringType extends AbstractType
{
    /**
     * Converts a value from its PHP representation to Drupal's data representation
     * of this type.
     *
     * @param mixed $value Value to convert
     * @return mixed The database representation of the value
     */
    public function convertToDrupalValue($value)
    {
        return $value !== null ? (string) $value : null;
    }

    /**
     * Converts a value from its Drupal representation to its PHP representation
     * of this type.
     *
     * @param mixed $value The value to convert.
     * @return mixed The PHP representation of the value.
     */
    public function convertToPHPValue($value)
    {
        return $value !== null ? (string) $value : null;
    }

}
