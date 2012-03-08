<?php
namespace DrupalConnect\Hydrator\Views\File;

use \DrupalConnect\Mapping\TypeManager as TypeManager;

/**
 * Hydrates File Document data that is received from VIEWS (via the Services Views module)
 */
class Image extends \DrupalConnect\Hydrator\Views\File
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

        return $image;
    }
}