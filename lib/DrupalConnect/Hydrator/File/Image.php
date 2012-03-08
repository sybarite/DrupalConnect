<?php
namespace DrupalConnect\Hydrator\File;

/**
 * Hydrator Image File Documents
 */
class Image extends \DrupalConnect\Hydrator\File
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
             * @var \DrupalConnect\Document\File\Image $image
             */
        $image = parent::hydrate($data);

        // set the base urls so the File document can generate URLs for the image styles requested
        $image->setFileBaseUrls($this->_dm->getConfig('file_base_url'));

        return $image;
    }
}
