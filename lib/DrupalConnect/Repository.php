<?php
namespace DrupalConnect;

/**
 * Serves as a repository for documents with generic as well as business specific methods for retrieving documents.
 * Future Plans: This class is designed for inheritance and users can subclass this class to write their own repositories with business-specific methods to locate documents.
 */
interface Repository
{
    /**
     * @param DocumentManager $dm
     * @param string $documentName
     */
    public function __construct(\DrupalConnect\DocumentManager $dm, $documentName);
}
