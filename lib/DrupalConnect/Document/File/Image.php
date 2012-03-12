<?php
namespace DrupalConnect\Document\File;

/**
 * Representation of an Image File document (or entity) in Drupal
 */
class Image extends \DrupalConnect\Document\File
{
    /**
     * Base URLS for the various file streams like public, private, etc
     *
     * @var array
     */
    protected $_fileBaseUrls;

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


}
