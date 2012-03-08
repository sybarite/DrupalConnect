<?php
namespace DrupalConnect\Hydrator\Views;

use \DrupalConnect\Mapping\TypeManager as TypeManager;

/**
 * Hydrates File Document data that is received from VIEWS (via the Services Views module)
 */
class File extends \DrupalConnect\Hydrator\File
{

    /**
     * Get the hydrated version of the document.
     *
     * @param array $data
     * @return \DrupalConnect\Document\Node
     */
    public function hydrate(array $data)
    {

        /**
         * @var \DrupalConnect\Document\File $file
         */
        $file = new $this->_documentName();

        // node id is (and must be) always returned
        $file->setFileId(TypeManager::getType('integer')->convertToPHPValue($data['fid']));

        if (isset($data['file_managed_uri']))
        {
            $file->setUrl(TypeManager::getType('string')->convertToPHPValue($this->_convertUriStreamToFullUrl($data['file_managed_uri'])));
        }

        if (isset($data['file_managed_filemime']))
        {
            $file->setMime(TypeManager::getType('string')->convertToPHPValue($data['file_managed_filemime']));
        }

        if (isset($data['file_managed_filesize']))
        {
            $file->setSize(TypeManager::getType('integer')->convertToPHPValue($data['file_managed_filesize']));
        }

        if (isset($data['file_managed_status']))
        {
            $file->setStatus(TypeManager::getType('integer')->convertToPHPValue($data['file_managed_status']));
        }

        if (isset($data['file_managed_timestamp']))
        {
            $file->setTime(TypeManager::getType('date')->convertToPHPValue($data['file_managed_timestamp']));
        }

        return $file;
    }
}
