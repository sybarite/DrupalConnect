<?php
namespace DrupalConnect\Document\Field;

/**
 * Text Field
 *
 * This field stores varchar text in the database.
 */
class Text implements \DrupalConnect\Document\Field
{
    /**
     * @var string
     */
    protected $_value;

    /**
     * @var string
     */
    protected $_format;

    /**
     * @var string
     */
    protected $_safeValue;

    /**
     * Sample field data
     *     array(
     *          'value': "text value",
     *          'format': null, // optional
     *          'safe_value': "dark knight" // optional
     *     )
     *
     *
     * @param array|null $data Field Data
     */
    public function __construct(array $data = null)
    {
        // if data set
        if ($data)
        {
            if (isset($data['value']))
            {
                $this->setValue($data['value']);
            }
            if (isset($data['format']))
            {
                $this->setFormat($data['format']);
            }
            if (isset($data['safe_value']))
            {
                $this->setSafeValue($data['safe_value']);
            }
        }
    }


    /**
     * @param string $value
     * @return Text
     */
    public function setValue($value)
    {
        $this->_value = $value;
        return $this;
    }

    /**
     * @return string
     */
    public function getValue()
    {
        return $this->_value;
    }

    /**
     * @param string $format
     * @return Text
     */
    public function setFormat($format)
    {
        $this->_format = $format;
        return $this;
    }

    /**
     * @return string
     */
    public function getFormat()
    {
        return $this->_format;
    }

    /**
     * @param string $safeValue
     * @return Text
     */
    public function setSafeValue($safeValue)
    {
        $this->_safeValue = $safeValue;
        return $this;
    }

    /**
     * @return string
     */
    public function getSafeValue()
    {
        return $this->_safeValue;
    }
}
