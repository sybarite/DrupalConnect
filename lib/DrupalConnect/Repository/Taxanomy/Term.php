<?php
namespace DrupalConnect\Repository\Taxanomy;

/**
 * Repository for Term Documents.
 *
 * Fetching documents using the repository will always fetch all associated content with a Term.
 *
 */
class Term extends \DrupalConnect\Repository\AbstractRepository
{
    /**
     * @param int $vid
     * @return null|\DrupalConnect\Document\Taxanomy\Term)
     */
    public function find($vid)
    {
        $result = $this->createQueryBuilder()
                       ->find()
                       ->field('tid')->equals($vid)
                       ->limit(1)
                       ->getQuery()
                       ->getSingleResult();

        return $result;
    }

    /**
     * Query for a single vocabulary based on several conditions that form a logical conjunction
     *
     * @param array $criteria
     * @return \DrupalConnect\Document\Taxanomy\Term|null
     */
    public function findOneBy(array $criteria)
    {
        $qb = $this->createQueryBuilder()
                   ->find()
                   ->hydrate(false)
                   ->select('tid')
                   ->limit(1);

        if ($criteria)
        {
            foreach ($criteria as $field => $val)
            {
                $qb->field($field)->equals($val);
            }
        }


        $partialTerm = $qb->getQuery()->getSingleResult();

        if (!$partialTerm)
            return null;

        return $this->find($partialTerm['tid']);
    }

    /**
     * Query for one or more vocabularies based on several conditions that form a logical conjunction
     *
     * @param array $criteria
     * @return \DrupalConnect\Document\Taxanomy\Term[]|null
     */
    public function findBy(array $criteria)
    {
        // if only the tid criteria is set, then use the ->find($nid) for faster results (since only 1 request needed and 1 result expected)
        if ($criteria && count($criteria) === 1 && isset($criteria['tid']))
        {
            $result = $this->find($criteria['tid']);
            return ($result) ? array($result) : null;
        }

        $qb = $this->createQueryBuilder()
                   ->find()
                   ->hydrate(false)
                   ->select('tid');

        if ($criteria)
        {
            foreach ($criteria as $field => $val)
            {
                $qb->field($field)->equals($val);
            }
        }

        $partialTerms = $qb->getQuery()->execute();

        if (!$partialTerms)
            return null;

        $fullTerms = array();

        foreach ($partialTerms as $pNode)
        {
            $fullTerms[] = $this->find($pNode['tid']);
        }

        return $fullTerms;
    }

    /**
     * Find a Vocabulary by its name
     *
     * @param string $name
     * @return null|\DrupalConnect\Document\Taxanomy\Term
     */
    public function findOneByName($name)
    {
        return $this->findOneBy(array(
            'name' => $name
        ));
    }

    /**
     * Find vocabularies by its name
     *
     * @param string $name
     * @return null|\DrupalConnect\Document\Taxanomy\Term[]
     */
    public function findByName($name)
    {
        return $this->findBy(array(
            'name' => $name
        ));
    }
}
