<?php
namespace DrupalConnect\Document\Field;
/**
 * Boolean Field
 *
 * This field stores simple on/off or yes/no options.
 */
class Boolean implements \DrupalConnect\Document\Field
{
    /**
     * @var boolean
     */
    protected $_value;

    /**
     * Sample field data
     *      array(
     *          'value' => "1"
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
                $this->_value = (boolean)$data['value'];
            }
        }
    }

    /**
     * @param float $value
     * @return boolean
     */
    public function setValue($value)
    {
        $this->_value = (boolean)$value;
        return $this;
    }

    /**
     * @return boolean
     */
    public function getValue()
    {
        return $this->_value;
    }

}
