<?php
namespace DrupalConnect\Repository;

/**
 * Repository for File Documents.
 *
 * Fetching documents using the repository will always fetch all associated content with a file.
 *
 */
class File extends AbstractRepository
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

    /**
     * Query for a single file based on several conditions that form a logical conjunction
     *
     * @param array $criteria
     * @return \DrupalConnect\Document\File|null
     */
    public function findOneBy(array $criteria)
    {
        $qb = $this->createQueryBuilder()
                   ->find()
                   ->hydrate(false)
                   ->select('fid')
                   ->limit(1);

        if ($criteria)
        {
            foreach ($criteria as $field => $val)
            {
                $qb->field($field)->equals($val);
            }
        }

        $partialFile = $qb->getQuery()->getSingleResult();

        if (!$partialFile)
            return null;

        return $this->find($partialFile['fid']);
    }

    /**
     * Query for one or more files based on several conditions that form a logical conjunction
     *
     * @param array $criteria
     * @return \DrupalConnect\Document\File[]|null
     */
    public function findBy(array $criteria)
    {
        // if only the fid criteria is set, then use the ->find($fid) for faster results (since only 1 request needed and 1 result expected)
        if ($criteria && count($criteria) === 1 && isset($criteria['fid']))
        {
            $result = $this->find($criteria['fid']);
            return ($result) ? array($result) : null;
        }

        $qb = $this->createQueryBuilder()
                   ->find()
                   ->hydrate(false)
                   ->select('fid');

        if ($criteria)
        {
            foreach ($criteria as $field => $val)
            {
                $qb->field($field)->equals($val);
            }
        }

        $partialFiles = $qb->getQuery()->execute();

        if (!$partialFiles)
            return null;

        $fullNodes = array();

        foreach ($partialFiles as $pFile)
        {
            $fullNodes[] = $this->find($pFile['fid']);
        }

        return $fullNodes;
    }

    /**
     * Find a File by its title
     *
     * @param string $name
     * @return null|\DrupalConnect\Document\File
     */
    public function findOneByName($name)
    {
        return $this->findOneBy(array(
            'filename' => $name
        ));
    }

    /**
     * Find nodes by its title
     *
     * @param string $name
     * @return null|\DrupalConnect\Document\File[]
     */
    public function findByName($name)
    {
        return $this->findBy(array(
            'filename' => $name
        ));
    }
}