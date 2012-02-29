<?php
namespace DrupalConnect\Document;

/**
 * Representation of a Node document (or entity) in Drupal
 */
class Node
{

    // Refer http://drupanium.org/api/55 for example response data (in full node form)

    protected $nid;

    protected $vid;

    protected $uid;

    protected $title;

    protected $log;

    protected $status;

    protected $comment;

    protected $promote;

    protected $sticky;

    protected $type;

    protected $language;

    protected $created;

    protected $changed;

    protected $tnid;

    protected $translate;

    protected $revision_timestmap;

    protected $revision_uid;

    protected $body;

    protected $field_tags;

    protected $field_image;

    protected $rdf_mapping;

    protected $cid;

    protected $last_comment_timestamp;

    protected $last_comment_name;

    protected $last_comment_uid;

    protected $comment_count;

    protected $name;

    protected $picture;

    protected $data;

    protected $path;


//    /**
//     *  Stores the data in the raw form as retreived from server
//     * @var mixed
//     */
//    protected $_data;
//
//    /**
//     * Set the data in the raw form as retreived from server
//     *
//     * @param $data
//     */
//    public function setData($data)
//    {
//        $this->_data = $data;
//    }
//
//    public function getData()
//    {
//        return $this->_data;
//    }


    public function setVid($vid)
    {
        $this->vid = $vid;
        return $this;
    }

    public function getVid()
    {
        return $this->vid;
    }

    public function setUid($uid)
    {
        $this->uid = $uid;
        return $this;
    }

    public function getUid()
    {
        return $this->uid;
    }

    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    public function getType()
    {
        return $this->type;
    }

    public function setTranslate($translate)
    {
        $this->translate = $translate;
        return $this;
    }

    public function getTranslate()
    {
        return $this->translate;
    }

    public function setTnid($tnid)
    {
        $this->tnid = $tnid;
        return $this;
    }

    public function getTnid()
    {
        return $this->tnid;
    }

    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setNid($nid)
    {
        $this->nid = $nid;
        return $this;
    }

    public function getNid()
    {
        return $this->nid;
    }

    public function setBody($body)
    {
        $this->body = $body;
        return $this;
    }

    public function getBody()
    {
        return $this->body;
    }

    public function setChanged($changed)
    {
        $this->changed = $changed;
        return $this;
    }

    public function getChanged()
    {
        return $this->changed;
    }

    public function setComment($comment)
    {
        $this->comment = $comment;
        return $this;
    }

    public function getComment()
    {
        return $this->comment;
    }

    public function setCreated($created)
    {
        $this->created = $created;
        return $this;
    }

    public function getCreated()
    {
        return $this->created;
    }

    public function setLanguage($language)
    {
        $this->language = $language;
        return $this;
    }

    public function getLanguage()
    {
        return $this->language;
    }

    public function setLog($log)
    {
        $this->log = $log;
        return $this;
    }

    public function getLog()
    {
        return $this->log;
    }

    public function setPromote($promote)
    {
        $this->promote = $promote;
        return $this;
    }

    public function getPromote()
    {
        return $this->promote;
    }

    public function setRevisionTimestamp($revisionTimestmap)
    {
        $this->revision_timestmap = $revisionTimestmap;
        return $this;
    }

    public function getRevisionTimestmap()
    {
        return $this->revision_timestmap;
    }

    public function setRevisionUid($revisionUid)
    {
        $this->revision_uid = $revisionUid;
        return $this;
    }

    public function getRevisionUid()
    {
        return $this->revision_uid;
    }

    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setSticky($sticky)
    {
        $this->sticky = $sticky;
        return $this;
    }

    public function getSticky()
    {
        return $this->sticky;
    }

    public function setCid($cid)
    {
        $this->cid = $cid;
        return $this;
    }

    public function getCid()
    {
        return $this->cid;
    }

    public function setCommentCount($comment_count)
    {
        $this->comment_count = $comment_count;
        return $this;
    }

    public function getCommentCount()
    {
        return $this->comment_count;
    }

    public function setData($data)
    {
        $this->data = $data;
        return $this;
    }

    public function getData()
    {
        return $this->data;
    }

    public function setFieldImage($field_image)
    {
        $this->field_image = $field_image;
        return $this;
    }

    public function getFieldImage()
    {
        return $this->field_image;
    }

    public function setFieldTags($field_tags)
    {
        $this->field_tags = $field_tags;
        return $this;
    }

    public function getFieldTags()
    {
        return $this->field_tags;
    }

    public function setLastCommentName($last_comment_name)
    {
        $this->last_comment_name = $last_comment_name;
        return $this;
    }

    public function getLastCommentName()
    {
        return $this->last_comment_name;
    }

    public function setLastCommentTimestamp($last_comment_timestamp)
    {
        $this->last_comment_timestamp = $last_comment_timestamp;
        return $this;
    }

    public function getLastCommentTimestamp()
    {
        return $this->last_comment_timestamp;
    }

    public function setLastCommentUid($last_comment_uid)
    {
        $this->last_comment_uid = $last_comment_uid;
        return $this;
    }

    public function getLastCommentUid()
    {
        return $this->last_comment_uid;
    }

    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setPath($path)
    {
        $this->path = $path;
        return $this;
    }

    public function getPath()
    {
        return $this->path;
    }

    public function setPicture($picture)
    {
        $this->picture = $picture;
        return $this;
    }

    public function getPicture()
    {
        return $this->picture;
    }

    public function setRdfMapping($rdf_mapping)
    {
        $this->rdf_mapping = $rdf_mapping;
        return $this;
    }

    public function getRdfMapping()
    {
        return $this->rdf_mapping;
    }


}
