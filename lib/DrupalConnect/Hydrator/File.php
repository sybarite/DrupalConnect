<?php
namespace DrupalConnect\Hydrator;

use \DrupalConnect\Mapping\TypeManager as TypeManager;

/**
 * Hydrates File data that is received from the default endpoints provided by the Services v3 module
 */
class File extends AbstractHydrator
{
    const CUSTOM_FIELD_PREFIX = 'field_';

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
}
