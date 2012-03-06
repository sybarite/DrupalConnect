<?php
namespace DrupalConnect\Repository;

/**
 * Repository for Node Documents.
 *
 * Fetching documents using the repository will always fetch all associated content with a node.
 * This includes custom fields and fields like body.
 *
 */
class Node extends AbstractRepository
{
    /**
     * @param $nid
     * @return null|\DrupalConnect\Document\Node
     */
    public function find($nid)
    {
        $result = $this->createQueryBuilder()
                       ->find()
                       ->field('nid')->equals($nid)
                       ->limit(1)
                       ->getQuery()
                       ->getSingleResult();

        return $result;
    }

    /**
     * Query for a single node based on several conditions that form a logical conjunction
     *
     * @param array $criteria
     * @return array|null
     */
    public function findOneBy(array $criteria)
    {
        $qb = $this->createQueryBuilder()
                   ->find()
                   ->hydrate(false)
                   ->select('nid')
                   ->limit(1);

        if ($criteria)
        {
            foreach ($criteria as $field => $val)
            {
                $qb->field($field)->equals($val);
            }
        }

        $partialNode = $qb->getQuery()->getSingleResult();

        if (!$partialNode)
            return null;

        return $this->find($partialNode['nid']);
    }

    /**
     * Query for one or more nodes based on several conditions that form a logical conjunction
     *
     * @param array $criteria
     * @return array|null
     */
    public function findBy(array $criteria)
    {
        // if only the nid criteria is set, then use the ->find($nid) for faster results (since only 1 request needed and 1 result expected)
        if ($criteria && count($criteria) === 1 && isset($criteria['nid']))
        {
            $result = $this->find($criteria['nid']);
            return ($result) ? array($result) : null;
        }

        $qb = $this->createQueryBuilder()
                   ->find()
                   ->hydrate(false)
                   ->select('nid');

        if ($criteria)
        {
            foreach ($criteria as $field => $val)
            {
                $qb->field($field)->equals($val);
            }
        }

        $partialNodes = $qb->getQuery()->execute();

        if (!$partialNodes)
            return null;

        $fullNodes = array();

        foreach ($partialNodes as $pNode)
        {
            $fullNodes[] = $this->find($pNode['nid']);
        }

        return $fullNodes;

    }

    /**
     * Find a Node by its title
     *
     * @param string $title
     * @return null|\DrupalConnect\Document\Node
     */
    public function findOneByTitle($title)
    {
        return $this->findOneBy(array(
            'title' => $title
        ));
    }

    /**
     * Find nodes by its title
     *
     * @param string $title
     * @return null|\DrupalConnect\Document\Node[]
     */
    public function findByTitle($title)
    {
        return $this->findBy(array(
            'title' => $title
        ));
    }
}