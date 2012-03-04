<?php
namespace DrupalConnect\Document\Field\Number;

/**
 * Integer Field
 *
 * This field stores a number in the database as an integer.
 */
class Integer implements \DrupalConnect\Document\Field\Number
{
    /**
     * @var int
     */
    protected $_value;

    /**
     * Sample field data
     *      array(
     *          'value' => "4"
     *      )
     *
     * @param array|null $data
     */
    public function __construct(array $data = null)
    {
        // if data set
        if ($data)
        {
            if (isset($data['value']))
            {
                $this->_value = intval($data['value']);
            }
        }
    }

    /**
     * @param float $value
     * @return Integer
     */
    public function setValue($value)
    {
        $this->_value = intval($value);
        return $this;
    }

    /**
     * @return int
     */
    public function getValue()
    {
        return $this->_value;
    }

}