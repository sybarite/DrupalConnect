<?php
namespace DrupalConnect;

/**
 * Base class inherited by all Documents (like Nodes, Comments, Users)
 */
interface Document
{
    /**
     * Returns the array representation of this node with all fields as retreived from drupal
     *
     * @return mixed
     */
    public function toArray();

    /**
     *  Set the array representation of this node with all fields as retreived from drupal
     *
     * @param $nodeArray
     *
     */
    public function setDocumentArray($nodeArray);
}