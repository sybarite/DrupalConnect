<?php
namespace DrupalConnect;

/**
 *
 */
abstract class Document
{
    /**
     *  Stores the node/comment/file/document information in the raw array form as retreived from server.
     *  This allows us to access fields which are not common to all nodes/comment/file in drupal
     *
     * @var mixed
     */
    protected $_documentArray;

    /**
     * Returns the array representation of this node with all fields as retreived from drupal
     *
     * @return mixed
     */
    public function toArray()
    {
        return $this->_documentArray;
    }

    /**
     *  Set the array representation of this node with all fields as retreived from drupal
     *
     * @param $nodeArray
     *
     */
    public function setDocumentArray($nodeArray)
    {
        $this->_documentArray = $nodeArray;
    }


}
