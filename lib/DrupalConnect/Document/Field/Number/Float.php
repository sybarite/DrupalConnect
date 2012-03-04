<?php
namespace DrupalConnect\Document\Field\Number;

/**
 * Float Field
 *
 * This field stores a number in the database in a floating point format.
 */
class Float implements \DrupalConnect\Document\Field\Number
{
    /**
     * @var float
     */
    protected $_value;

    /**
     * Sample field data
     *      array(
     *          'value' => "2.55"
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
                $this->_value = floatval($data['value']);
            }
        }
    }

    /**
     * @param float $value
     * @return Float
     */
    public function setValue($value)
    {
        $this->_value = floatval($value);
        return $this;
    }

    /**
     * @return float
     */
    public function getValue()
    {
        return $this->_value;
    }

}