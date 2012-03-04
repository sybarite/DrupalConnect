<?php
namespace DrupalConnect\Mapping\Type;

/**
 * The Date type
 */
class DateType extends AbstractType
{

    public function convertToDrupalValue($value)
    {
        if ($value === null) {
            return null;
        }

        if ($value instanceof \DateTime) {
            /**
                     * @var \DateTime $value
                     */
            $timestamp = $value->getTimestamp();
            return $timestamp;
        }
        else
        {
            throw new Exception('Value not of this type');
        }
    }

    /**
     * Converts a value from its Drupal representation to its PHP representation
     * of this type.
     *
     * @param int $value The value to convert.
     * @return mixed The PHP representation of the value.
     */
    public function convertToPHPValue($value)
    {
        if ($value === null) {
            return null;
        }

        $date = new \DateTime();

        $date->setTimestamp($value);
        return $date;
    }

}
