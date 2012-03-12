<?php
namespace DrupalConnect\Document\Field\File;

/**
 * Custom Image File Field
 */
class Image extends \DrupalConnect\Document\Field\File
{

    /**
     * @var int
     */
    protected $_width;

    /**
     * @var int
     */
    protected $_height;

    /**
     * Alt text
     *
     * @var string
     */
    protected $_alt;

    /**
     * Title text
     *
     * @var string
     */
    protected $_title;

    /**
     * @param array|null $data
     */
    public function __construct(array $data = null)
    {
        parent::__construct($data);

        if (isset($data['width']))
        {
            $this->_width = (int)$data['width'];
        }
        if (isset($data['height']))
        {
            $this->_height = (int)$data['height'];
        }
        if (isset($data['alt']))
        {
            $this->_alt = $data['alt'];
        }
        if (isset($data['title']))
        {
            $this->_title = $data['title'];
        }
    }

    /**
     * Get the URL to the Original image or one of the styles
     *
     * @param string|null $styleName Style for the image (optional)
     * @return string
     */
    public function getUrl($styleName = null)
    {
        if ($styleName)
        {
            return $this->_getStyleUrl($styleName);
        }
        return parent::getUrl();
    }

    /**
     * Convert the Image URI to a style URL using the public base path
     *
     * @see http://api.drupal.org/api/drupal/modules%21image%21image.module/function/image_style_url/7
     * @param string $styleName
     * @return string
     */
    protected function _getStyleUrl($styleName)
    {
        $uri = $this->_uri;

        $index = strpos($uri, '://');
        $scheme = substr($uri, 0, $index);
        $target =  urlencode(substr($uri, $index + 3));

        $styleName = urlencode($styleName);

        if ($scheme === 'public')
        {
            return "{$this->_fileBaseUrls[$scheme]}styles/$styleName/$scheme/$target";
        }
        else if ($scheme === 'private')
        {
            return "{$this->_fileBaseUrls[$scheme]}styles/$styleName/$scheme/$target";
        }

        return null;
    }


    /**
     * @return string
     */
    public function getAlt()
    {
        return $this->_alt;
    }

    /**
     * @return int
     */
    public function getHeight()
    {
        return $this->_height;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->_title;
    }


    /**
     * @return int
     */
    public function getWidth()
    {
        return $this->_width;
    }


}
