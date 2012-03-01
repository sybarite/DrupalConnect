<?php
namespace DrupalConnect\Repository;

/**
 * Repository for Node Documents
 */
class Node extends AbstractRepository
{
    public function find($nid)
    {
        $qb = $this->_dm->createQueryBuilder($this->_documentName);

        $result = $qb->find()
                     ->field('nid')->equals($nid)
                     ->getQuery()
                     ->execute();

        if (!$result)
            return null;

        return $result->getSingleResult();
    }
}