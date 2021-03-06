<?php
namespace DrupalConnect\Document\Field;

use \DrupalConnect\Mapping\TypeManager as TypeManager;

/**
 * File field
 *
 * Custom fields which are of type File are represented by this class
 */
class File implements \DrupalConnect\Document\Field
{
    /**
     * @var int
     */
    protected $_id;

    /**
     * @var string
     */
    protected $_description;

    /**
     * @var string
     */
    protected $_name;

    /**
     * @var string
     */
    protected $_uri;

    /**
     * @var string
     */
    protected $_mime;

    /**
     * @var int
     */
    protected $_size;

    /**
     * @var \DateTime
     */
    protected $_time;

    /**
     * Base URLS for the various file streams like public, private, etc
     *
     * @var array
     */
    protected $_fileBaseUrls;

    /**
     * @param array|null $data
     */
    public function __construct(array $data = null)
    {
        if (isset($data['fid']))
        {
            $this->_id = (int)$data['fid'];
        }
        if (isset($data['description']))
        {
            $this->_description = $data['description'];
        }
        if (isset($data['filename']))
        {
            $this->_name = $data['filename'];
        }
        if (isset($data['uri']))
        {
            $this->_uri = $data['uri'];
        }
        if (isset($data['filemime']))
        {
            $this->_mime = $data['filemime'];
        }
        if (isset($data['filesize']))
        {
            $this->_size = (int)$data['filesize'];
        }
        if (isset($data['timestamp']))
        {
            $this->_time = TypeManager::getType('date')->convertToPHPValue($data['timestamp']);
        }
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->_id;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->_description;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->_name;
    }

    /**
     * @return string
     */
    public function getUri()
    {
        return $this->_uri;
    }

    /**
     * @return string
     */
    public function getMime()
    {
        return $this->_mime;
    }

    /**
     * @return int
     */
    public function getSize()
    {
        return $this->_size;
    }

    /**
     * @return \DateTime|mixed
     */
    public function getTime()
    {
        return $this->_time;
    }

    // ----------- Additional Getters + Setters + Functionality ----------------------

    /**
     * Get the full URL of this file
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->_convertUriStreamToFullUrl($this->_uri);
    }

    /**
     * Set the file base URLS
     * DO NOT CALL THIS FUNCTION DIRECTLY... the hydrator will call it!
     *
     *
     * @param array $fileBaseUrls
     * @return \DrupalConnect\Document\File\Image
     */
    public function setFileBaseUrls($fileBaseUrls)
    {
        $this->_fileBaseUrls = $fileBaseUrls;
        return $this;
    }

    /**
     * Convert a scheme:://target URI like public://avatar.jpg to http://drupal.com/sites/default/files/avatar.jpg
     *
     * @see http://api.drupal.org/api/drupal/includes%21file.inc/function/file_uri_scheme/7
     * @param string $uri
     * @return string
     */
    protected function _convertUriStreamToFullUrl($uri)
    {
        $index = strpos($uri, '://');
        $scheme = substr($uri, 0, $index);
        $target =  urlencode(substr($uri, $index + 3));

        return $this->_fileBaseUrls[$scheme] . $target;
    }
}
