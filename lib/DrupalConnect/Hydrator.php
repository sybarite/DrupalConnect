<?php
namespace DrupalConnect;

/**
 * Interface for all Hydrators
 */
interface Hydrator
{
    /**
     * @param \DrupalConnect\DocumentManager $dm
     * @param string $documentName
     */
    public function __construct(\DrupalConnect\DocumentManager $dm, $documentName);

    /**
     * Get a hydrated version of the document.
     *
     * @param array $data
     * @return Document
     */
    public function hydrate(array $data);
}
