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
class Node extends AbstractFieldSupport
{

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

}
