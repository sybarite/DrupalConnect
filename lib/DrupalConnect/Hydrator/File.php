<?php
namespace DrupalConnect\Hydrator;

use \DrupalConnect\Mapping\TypeManager as TypeManager;

/**
 * Hydrates File data that is received from the default endpoints provided by the Services v3 module
 */
class File extends AbstractHydrator
{
    /**
     * Get the hydrated version of the document.
     *
     * @param array $data
     * @return \DrupalConnect\Document\File
     */
    public function hydrate(array $data)
    {
        /**
         * @var \DrupalConnect\Document\File $file
         */
        $file = new $this->_documentName();

        // node id is (and must be) always returned
        $file->setFileId(TypeManager::getType('integer')->convertToPHPValue($data['fid']));

        if (isset($data['uid']))
        {
            $file->setUserId(TypeManager::getType('integer')->convertToPHPValue($data['uid']));
        }

        if (isset($data['filename']))
        {
            $file->setName(TypeManager::getType('string')->convertToPHPValue($data['filename']));
        }

        if (isset($data['uri']))
        {
            $file->setUri(TypeManager::getType('string')->convertToPHPValue($data['uri']));
            $file->setUrl(TypeManager::getType('string')->convertToPHPValue($this->_convertUriStreamToFullUrl($data['uri'])));
        }

        if (isset($data['filemime']))
        {
            $file->setMime(TypeManager::getType('string')->convertToPHPValue($data['filemime']));
        }

        if (isset($data['filesize']))
        {
            $file->setSize(TypeManager::getType('integer')->convertToPHPValue($data['filesize']));
        }

        if (isset($data['status']))
        {
            $file->setStatus(TypeManager::getType('integer')->convertToPHPValue($data['status']));
        }

        if (isset($data['timestamp']))
        {
            $file->setTime(TypeManager::getType('date')->convertToPHPValue($data['timestamp']));
        }

        return $file;
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
        $target = urlencode(substr($uri, $index + 3));

        $basePaths = $this->_dm->getConfig('file_base_url');

        return $basePaths[$scheme] . $target;
    }
}
