<?php
namespace DrupalConnect\Document;

/**
 * Representation of a Node document (or entity) in Drupal
 *
 * The following references were used while building this document:
 *     - Node (core) table: http://drupal.org/node/70591
 *     - Comments from the 'node' mysql table for each column
 *     - http://drupanium.org/api/55 for example response data (in full node form)
 */
class Node extends AbstractDocument
{

    /**
     * Language used when no language defined
     */
    const LANGUAGE_NONE = 'und';

    /**
     * Base URLS for the various file streams like public, private, etc
     *
     * @var array
     */
    protected $_fileBaseUrls;

    /**
     * The numeric ID for a node. This should always be a unique identifier
     *
     * @var int
     */
    protected $_nodeId;

    /**
     * The numeric ID for the version of this node. This maps directly to the node_revisions table.
     * This should always be a unique identifier.
     *
     * @var int
     */
    protected $_versionId;

    /**
     * The machine-readable form for the type for this node.
     * For example, in Drupal a default type is "Page", but the machine readable form of that type is page
     * (note how it is all lowercase, this is common practice).
     *
     * @var string
     */
    protected $_type;

    /**
     * The language used in the content of this node.
     *
     * @var string
     */
    protected $_language;

    /**
     * The title for this node.
     *
     * @var string
     */
    protected $_title;

    /**
     * The ID of the user who created this node.
     * Drupal does not change this at all after it is created.
     * The change in possession occurs in node_revisions.
     *
     * @var int
     */
    protected $_userId;

    /**
     * Indicates the publicity status of this node. 1 is published. 0 is unpublished.
     *
     * @var boolean
     */
    protected $_status;

    /**
     * When the node was created
     *
     * @var \DateTime
     */
    protected $_created;

    /**
     * When the node was last modified
     *
     * @var \DateTime
     */
    protected $_changed;

    /**
     * Whether comment is allowed on this node
     * 0 = no, 1 = closed (read only), 2 = open (read/write).
     *
     * @var int
     */
    protected $_comment;

    /**
     * Indicates whether or not this node has been promoted to the front page.
     *
     * @var boolean
     */
    protected $_promote;

    /**
     * Indicates whether the node should be displayed at the top of lists in which it appears.
     *
     * @var boolean
     */
    protected $_sticky;

//    /**
//     * The translation set id for this node, which equals the node id of the source post in each set.
//     *
//     * @var int
//     */
//    protected $_translationSetId;
//
//    /**
//     * A boolean indicating whether this translation page needs to be updated.
//     *
//     * @var boolean
//     */
//    protected $_translate;


    /**
     * Stores the 'body' field defined by the node module.
     * Note: any custom field with a name 'body' is not the same as this field. This is a special field!
     *
     * @var array
     */
    protected $_body = array();


    // -------- custom fields and feature related variables ---

    /**
     * Stores all custom fields
     *
     * @var array
     */
    protected $_fields = array();

    /**
     * @param int $versionId
     * @return Node
     */
    public function setVersionId($versionId)
    {
        $this->_versionId = $versionId;
        return $this;
    }

    /**
     * @return int
     */
    public function getVersionId()
    {
        return $this->_versionId;
    }

    /**
     * @param \DateTime $changed
     * @return Node
     */
    public function setChanged(\DateTime $changed)
    {
        $this->_changed = $changed;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getChanged()
    {
        return $this->_changed;
    }

    /**
     * @param int $comment
     * @return Node
     */
    public function setComment($comment)
    {
        $this->_comment = $comment;
        return $this;
    }

    /**
     * @return int
     */
    public function getComment()
    {
        return $this->_comment;
    }

    /**
     * @param \DateTime $created
     * @return Node
     */
    public function setCreated(\DateTime $created)
    {
        $this->_created = $created;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->_created;
    }

    /**
     * @param string $language
     * @return Node
     */
    public function setLanguage($language)
    {
        $this->_language = $language;
        return $this;
    }

    /**
     * @return string
     */
    public function getLanguage()
    {
        return $this->_language;
    }

    /**
     * @param int $nodeId
     * @return Node
     */
    public function setNodeId($nodeId)
    {
        $this->_nodeId = $nodeId;
        return $this;
    }

    /**
     * @return int
     */
    public function getNodeId()
    {
        return $this->_nodeId;
    }

    /**
     * @param boolean $promote
     * @return Node
     */
    public function setPromote($promote)
    {
        $this->_promote = $promote;
        return $this;
    }

    /**
     * @return boolean
     */
    public function getPromote()
    {
        return $this->_promote;
    }

    /**
     * @param boolean $status
     * @return Node
     */
    public function setStatus($status)
    {
        $this->_status = $status;
        return $this;
    }

    /**
     * @return boolean
     */
    public function getStatus()
    {
        return $this->_status;
    }

    /**
     * @param boolean $sticky
     * @return Node
     */
    public function setSticky($sticky)
    {
        $this->_sticky = $sticky;
        return $this;
    }

    /**
     * @return boolean
     */
    public function getSticky()
    {
        return $this->_sticky;
    }

    /**
     * @param string $title
     * @return Node
     */
    public function setTitle($title)
    {
        $this->_title = $title;
        return $this;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->_title;
    }

//    /**
//     * @param int $tnid
//     * @return Node
//     */
//    public function setTranslationSetId($tnid)
//    {
//        $this->_translationSetId = $tnid;
//        return $this;
//    }
//
//    /**
//     * @return int
//     */
//    public function getTranslationSetId()
//    {
//        return $this->_translationSetId;
//    }
//
//    /**
//     * @param boolean $translate
//     * @return Node
//     */
//    public function setTranslate($translate)
//    {
//        $this->_translate = $translate;
//        return $this;
//    }
//
//    /**
//     * @return boolean
//     */
//    public function getTranslate()
//    {
//        return $this->_translate;
//    }

    /**
     * @param string $type
     * @return Node
     */
    public function setType($type)
    {
        $this->_type = $type;
        return $this;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->_type;
    }

    /**
     * @param int $userId
     * @return Node
     */
    public function setUserId($userId)
    {
        $this->_userId = $userId;
        return $this;
    }

    /**
     * @return int
     */
    public function getUserId()
    {
        return $this->_userId;
    }

    /**
     * Add a field-value to body
     * e.g
     *      array(
     *              value => 'body_text',
     *              summary => 'summary_text',
     *              format => 'filtered_html'
     *      )
     *
     * @param array $fieldData
     * @param array|null $options language options, etc
     * @return Node
     */
    public function addToBody(array $fieldData, array $options = null)
    {
        $lang = (is_array($options) && isset($options['language'])) ? $options['language'] : self::LANGUAGE_NONE;

        $this->_body[$lang][] = $fieldData;
    }

    /**
     * @param int|null $index
     * @param array|null $options language options, etc
     * @return Field\TextWithSummary|null
     */
    public function getBody($index = null, array $options = null)
    {
        $lang = (is_array($options) && isset($options['language'])) ? $options['language'] : self::LANGUAGE_NONE;

        // if language not set for this field
        if (!isset($this->_body[$lang]))
        {
            return null;
        }

        // if all values requested
        if ($index === null)
        {
            $results = array();

            foreach ($this->_body[$lang] as $fieldSet)
            {
                $results[] = new Field\TextWithSummary($fieldSet);
            }

            return $results;
        }

        // if only one index requested
        if (!isset($this->_body[$lang][$index]))
        {
            return null;
        }

        return new Field\TextWithSummary($this->_body[$lang][$index]);
    }


    // ----------- Additional Getters + Setters + Functionality ----------------------

    /**
     * Alias for getNodeId(..)
     *
     * @return int
     */
    public function getId()
    {
        return $this->getNodeId();
    }

    /**
     * Alias for setNodeId(..)
     *
     * @param $id
     */
    public function setId($id)
    {
        $this->setNodeId($id);
    }

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
     * @return Field\Text[]|Field\Text|null
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
