<?php
namespace DrupalConnect\Document;

/**
 * Representation of a File document (or entity) in Drupal
 */
class File
{
    /**
     * Numeric id for a file. This should always be the unique idenitifier
     *
     * @var int
     */
    protected $_fileId;

    /**
     * uid of the user who is associated with the file.
     *
     * @var int
     */
    protected $_userId;

    /**
     * Name of the file with no path components. This may differ from the basename of the URI if the file is renamed to avoid overwriting an existing file.
     *
     * @var string
     */
    protected $_name;

    /**
     * The URI to access the file (either local or remote).
     *
     * @var string
     */
    protected $_uri;

    /**
     * The file's MIME type.
     *
     * @var string
     */
    protected $_mime;

    /**
     * The size of the file in bytes.
     *
     * @var int
     */
    protected $_size;

    /**
     * A field indicating the status of the file.
     * Two status are defined in core: temporary (0) and permanent (1).
     * Temporary files older than DRUPAL_MAXIMUM_TEMP_FILE_AGE will be removed during a cron run.
     *
     * @var int
     */
    protected $_status;

    /**
     * Time when the file was added
     *
     * @var \DateTime
     */
    protected $_time;


    /**
     * @param int $fileId
     * @return \DrupalConnect\Document\File
     */
    public function setFileId($fileId)
    {
        $this->_fileId = $fileId;
        return $this;
    }

    /**
     * @return int
     */
    public function getFileId()
    {
        return $this->_fileId;
    }

    /**
     * @param string $name
     * @return \DrupalConnect\Document\File
     */
    public function setName($name)
    {
        $this->_name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->_name;
    }

    /**
     * @param int $size
     * @return \DrupalConnect\Document\File
     */
    public function setSize($size)
    {
        $this->_size = $size;
        return $this;
    }

    /**
     * @return int
     */
    public function getSize()
    {
        return $this->_size;
    }

    /**
     * @param int $status
     * @return \DrupalConnect\Document\File
     */
    public function setStatus($status)
    {
        $this->_status = $status;
        return $this;
    }

    /**
     * @return int
     */
    public function getStatus()
    {
        return $this->_status;
    }

    /**
     * @param \DateTime $time
     * @return \DrupalConnect\Document\File
     */
    public function setTime(\DateTime $time)
    {
        $this->_time = $time;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getTime()
    {
        return $this->_time;
    }

    /**
     * @param string $uri
     * @return \DrupalConnect\Document\File
     */
    public function setUri($uri)
    {
        $this->_uri = $uri;
        return $this;
    }

    /**
     * @return string
     */
    public function getUri()
    {
        return $this->_uri;
    }

    /**
     * @param int $userId
     * @return \DrupalConnect\Document\File
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
     * @param string $mime
     * @return \DrupalConnect\Document\File
     */
    public function setMime($mime)
    {
        $this->_mime = $mime;
        return $this;
    }

    /**
     * @return string
     */
    public function getMime()
    {
        return $this->_mime;
    }

    // ----------- Additional Getters + Setters + Functionality ----------------------

    /**
     * Alias for getNodeId(..)
     *
     * @return int
     */
    public function getId()
    {
        return $this->getFileId();
    }

    /**
     * Alias for setNodeId(..)
     *
     * @param $id
     */
    public function setId($id)
    {
        $this->setFileId($id);
    }

}
