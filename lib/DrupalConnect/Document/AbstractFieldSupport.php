<?php
namespace DrupalConnect\Document;

/**
 * Provides accessesors and functionality which allows accessing/setting custom field data for Documents
 * which support custom fields (like Nodes, Terms, User, Comments, etc)
 */
class AbstractFieldSupport extends AbstractDocument
{
    /**
     * Language used when no language defined
     */
    const LANGUAGE_NONE = 'und';

    /**
     * Stores all custom field values
     *
     * @var array
     */
    protected $_fields = array();

    /**
     * Base URLS for the various file streams like public, private, etc
     *
     * @var array
     */
    protected $_fileBaseUrls;

    /**
     * Add a field-value to a field
     * e.g
     *      array(
     *              value => 'body_text',
     *              summary => 'summary_text',
     *              format => 'filtered_html'
     *      )
     *
     * @param string $fieldName
     * @param array $fieldData
     * @param array|null $options
     * @return Node
     */
    public function addToField($fieldName, array $fieldData, array $options = null)
    {
        $lang = (is_array($options) && isset($options['language'])) ? $options['language'] : self::LANGUAGE_NONE;

        $this->_fields[$fieldName][$lang][] = $fieldData;
    }

    /**
     * Get the value or value-set for a custom field
     *
     *  $options = array(
     *                  'language' => 'fr'
     *                  'type' => 'DrupalConnect\Document\Field\Text', // or any class that implements \DrupalConnect\Document\Field interface
     *              )
     *
     * @param string $fieldName Name of the custom field
     * @param int|null $index Index of the field value to fetch
     * @param array|null $options Options like language, etc
     * @return null|array|Field|Field[]
     */
    public function getField($fieldName, $index = null, array $options = null)
    {
        // if field not set
        if (!isset($this->_fields[$fieldName]) )
        {
            return null;
        }

        $lang = (is_array($options) && isset($options['language'])) ? $options['language'] : self::LANGUAGE_NONE;
        $fieldType = (is_array($options) && isset($options['type'])) ? $options['type'] : null;

        // if language not set for this field
        if (!isset($this->_fields[$fieldName][$lang]))
        {
            return null;
        }

        // if index not set, then return all results
        if ($index === null)
        {
            if ($fieldType)
            {
                $results = array();

                foreach ($this->_fields[$fieldName][$lang] as $fieldValue)
                {
                    $results[] = new $fieldType($fieldValue);
                }

                return $results;
            }
            else // return array representation of all fields
            {
                return $this->_fields[$fieldName][$lang];
            }
        }

        if (!isset($this->_fields[$fieldName][$lang][$index]))
        {
            return null;
        }

        // if field(sub-docoument) type set, then convert to an object of that type
        if ($fieldType)
        {
            return new $fieldType($this->_fields[$fieldName][$lang][$index]);
        }
        else // return array representation
        {
            return $this->_fields[$fieldName][$lang][$index];
        }
    }

    /**
     * Set the file base URLS
     * DO NOT CALL THIS FUNCTION DIRECTLY... the hydrator will call it!
     *
     * @param array $fileBaseUrls
     * @return \DrupalConnect\Document\File\Image
     */
    public function setFileBaseUrls($fileBaseUrls)
    {
        $this->_fileBaseUrls = $fileBaseUrls;
        return $this;
    }

    // ----------- Functionality for getting fields of Particular Type ----------------------

    /**
     * Get a custom Text field
     *
     * @param string $fieldName
     * @param null|int $index
     * @param array $options Field options like language
     * @return Field\Text[]|Field\Text|null
     */
    public function getTextField($fieldName, $index = null, array $options = array())
    {
        $options['type'] = 'DrupalConnect\Document\Field\Text';
        return $this->getField($fieldName, $index, $options);
    }

    /**
     * Get a custom TextWithSummary field
     *
     * @param string $fieldName
     * @param null|int $index
     * @param array $options Field options like language
     * @return Field\TextWithSummary[]|Field\TextWithSummary|null
     */
    public function getTextWithSummaryField($fieldName, $index = null, array $options = array())
    {
        $options['type'] = 'DrupalConnect\Document\Field\TextWithSummary';
        return $this->getField($fieldName, $index, $options);
    }

    /**
     * Get a custom Float field
     *
     * @param string $fieldName
     * @param null|int $index
     * @param array $options Field options like language
     * @return Field\Number\Float[]|Field\Number\Float|null
     */
    public function getFloatField($fieldName, $index = null, array $options = array())
    {
        $options['type'] = 'DrupalConnect\Document\Field\Number\Float';
        return $this->getField($fieldName, $index, $options);
    }

    /**
     * Get a custom Integer field
     *
     * @param string $fieldName
     * @param null|int $index
     * @param array $options Field options like language
     * @return Field\Number\Integer[]|Field\Number\Integer|null
     */
    public function getIntegerField($fieldName, $index = null, array $options = array())
    {
        $options['type'] = 'DrupalConnect\Document\Field\Number\Integer';
        return $this->getField($fieldName, $index, $options);
    }

    /**
     * Get a custom Boolean field
     *
     * @param string $fieldName
     * @param null|int $index
     * @param array $options Field options like language
     * @return Field\Boolean[]|Field\Boolean|null
     */
    public function getBooleanField($fieldName, $index = null, array $options = array())
    {
        $options['type'] = 'DrupalConnect\Document\Field\Boolean';
        return $this->getField($fieldName, $index, $options);
    }

    /**
     * Get a custom file field
     *
     * @param $fieldName
     * @param null|int $index
     * @param array $options options like language
     * @return Field\File|Field\File[]|null
     */
    public function getFileField($fieldName, $index = null, array $options = array())
    {
        $options['type'] = 'DrupalConnect\Document\Field\File';
        /**
             * @var \DrupalConnect\Document\Field\File|\DrupalConnect\Document\Field\File[] $fileField
             */
        $fileField = $this->getField($fieldName, $index, $options);

        if ($fileField)
        {
            // set the base URLS so file fields can create the FULL URL
            if (is_array($fileField))
            {
                foreach ($fileField as $f)
                {
                    $f->setFileBaseUrls($this->_fileBaseUrls);
                }
            }
            else
            {
                $fileField->setFileBaseUrls($this->_fileBaseUrls);
            }
        }

        return $fileField;
    }

    /**
     * Get a custom image field
     *
     * @param $fieldName
     * @param null|int $index
     * @param array $options options like language
     * @return Field\File\Image|Field\File\Image[]|null
     */
    public function getImageField($fieldName, $index = null, array $options = array())
    {
        $options['type'] = 'DrupalConnect\Document\Field\File\Image';
        /**
             * @var \DrupalConnect\Document\Field\File\Image|\DrupalConnect\Document\Field\File\Image[] $fileField
             */
        $fileField = $this->getField($fieldName, $index, $options);

        if ($fileField)
        {
            // set the base URLS so file fields can create the FULL URL
            if (is_array($fileField))
            {
                foreach ($fileField as $f)
                {
                    $f->setFileBaseUrls($this->_fileBaseUrls);
                }
            }
            else
            {
                $fileField->setFileBaseUrls($this->_fileBaseUrls);
            }
        }

        return $fileField;
    }

    // ----------- Functionality for getting VALUES of a Particular Field Type ----------------------

     /**
      * Get the value or value-set of a float field as a PHP float value
      *
      * @param string $fieldName
      * @param null|int $index
      * @param array $options Field options like language
      * @return float|float[]|null
      */
    public function getFloatFieldValue($fieldName, $index = null, array $options = array())
    {
        return $this->_getNumberFieldValue($this->getFloatField($fieldName, $index, $options));
    }

    /**
     * Get the value or value-set of an integer field as a PHP integer value
     *
     * @param string $fieldName
     * @param null|int $index
     * @param array $options Field options like language
     * @return int|int[]|null
     */
   public function getIntegerFieldValue($fieldName, $index = null, array $options = array())
   {
       return $this->_getNumberFieldValue($this->getIntegerField($fieldName, $index, $options));
   }

     /**
      * Get the value of a number field or fieldset as a PHP value
      *
      * @param $fieldSet
      * @return float|float[]|int|int[]|null
      */
    public function _getNumberFieldValue($fieldSet)
    {
        if (!$fieldSet)
            return null;

        // if multiple values returned
        if (is_array($fieldSet))
        {
            $results = array();

            foreach ($fieldSet as $fieldValue)
            {
                /**
                            * @var Field\Number $fieldValue
                            */
                $results[] = $fieldValue->getValue();
            }

            return $results;
        }

        // if only one result
        /**
                * @var Field\Number $fieldSet
                */
        return $fieldSet->getValue();
    }

    /**
     * Get the value or value-set of a string field as a PHP string value
     *
     * @param string $fieldName
     * @param null|int $index
     * @param array $options Field options like language
     * @return string|string[]|null
     */
   public function getTextFieldValue($fieldName, $index = null, array $options = array())
   {
       return $this->_getTextFieldValue($this->getTextField($fieldName, $index, $options));
   }

    /**
     * Get the value of a number field or fieldset as a PHP value
     *
     * @param $fieldSet
     * @return string|string[]|null
     */
   public function _getTextFieldValue($fieldSet)
   {
       if (!$fieldSet)
           return null;

       // if multiple values returned
       if (is_array($fieldSet))
       {
           $results = array();

           foreach ($fieldSet as $fieldValue)
           {
               /**
                           * @var Field\Text $fieldValue
                           */
               $results[] = $fieldValue->getValue();
           }

           return $results;
       }

       // if only one result
       /**
               * @var Field\Text $fieldSet
               */
       return $fieldSet->getValue();
   }

    /**
     * Get the value or value-set of a boolean field as a PHP boolean value
     *
     * @param string $fieldName
     * @param null|int $index
     * @param array $options Field options like language
     * @return boolean|boolean[]|null
     */
   public function getBooleanFieldValue($fieldName, $index = null, array $options = array())
   {
        $fieldSet = $this->getBooleanField($fieldName, $index, $options);

        if (!$fieldSet)
            return null;

        // if multiple values returned
        if (is_array($fieldSet))
        {
            $results = array();

            foreach ($fieldSet as $fieldValue)
            {
                 /**
                              * @var Field\Boolean $fieldValue
                              */
                 $results[] = $fieldValue->getValue();
            }

            return $results;
        }

        // if only one result
        /**
              * @var Field\Boolean $fieldSet
              */
        return $fieldSet->getValue();
   }

}
