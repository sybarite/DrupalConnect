<?php
namespace DrupalConnect\Repository;

/**
 * Repository for File Documents.
 *
 * Fetching documents using the repository will always fetch all associated content with a file.
 *
 */
class Node extends AbstractRepository
{
    /**
     *
     *
     * @param int $fid
     * @return null|\DrupalConnect\Document\File
     */
    public function find($fid)
    {
        $result = $this->createQueryBuilder()
                       ->find()
                       ->field('fid')->equals($fid)
                       ->limit(1)
                       ->getQuery()
                       ->getSingleResult();

        return $result;
    }
}