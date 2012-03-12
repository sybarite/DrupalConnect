<?php
namespace DrupalConnect\Repository\Taxanomy;

/**
 * Repository for Vocabulary Documents.
 *
 * Fetching documents using the repository will always fetch all associated content with a vocabulary.
 *
 */
class Vocabulary extends \DrupalConnect\Repository\AbstractRepository
{
    /**
     * @param int $vid
     * @return null|\DrupalConnect\Document\Taxanomy\Vocabulary)
     */
    public function find($vid)
    {
        $result = $this->createQueryBuilder()
                       ->find()
                       ->field('vid')->equals($vid)
                       ->limit(1)
                       ->getQuery()
                       ->getSingleResult();

        return $result;
    }

    /**
     * Query for a single vocabulary based on several conditions that form a logical conjunction
     *
     * @param array $criteria
     * @return \DrupalConnect\Document\Taxanomy\Vocabulary|null
     */
    public function findOneBy(array $criteria)
    {
        // We don't need to do two http requests for vocabularies as the index location results return all the required fields
        $qb = $this->createQueryBuilder()
                   ->find()
                   ->limit(1);

        if ($criteria)
        {
            foreach ($criteria as $field => $val)
            {
                $qb->field($field)->equals($val);
            }
        }

        return $qb->getQuery()->getSingleResult();
    }

    /**
     * Query for one or more vocabularies based on several conditions that form a logical conjunction
     *
     * @param array $criteria
     * @return \DrupalConnect\Document\Taxanomy\Vocabulary[]|null
     */
    public function findBy(array $criteria)
    {
        // if only the nid criteria is set, then use the ->find($nid) for faster results (since only 1 request needed and 1 result expected)
        if ($criteria && count($criteria) === 1 && isset($criteria['vid']))
        {
            $result = $this->find($criteria['vid']);
            return ($result) ? array($result) : null;
        }

        $qb = $this->createQueryBuilder()
                   ->find();

        if ($criteria)
        {
            foreach ($criteria as $field => $val)
            {
                $qb->field($field)->equals($val);
            }
        }

        $partialVocabularies = $qb->getQuery()->execute();

        if (!$partialVocabularies)
            return null;

        $fullVocabularies = array();

        foreach ($partialVocabularies as $pNode)
        {
            $fullVocabularies[] = $pNode;
        }

        return $fullVocabularies;
    }

    /**
     * Find a Vocabulary by its name
     *
     * @param string $name
     * @return null|\DrupalConnect\Document\Taxanomy\Vocabulary
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
     * @return null|\DrupalConnect\Document\Taxanomy\Vocabulary[]
     */
    public function findByName($name)
    {
        return $this->findBy(array(
            'name' => $name
        ));
    }

    /**
     * Find a Vocabulary by its Machine name
     *
     * @param string $name
     * @return null|\DrupalConnect\Document\Taxanomy\Vocabulary
     */
    public function findOneByMachineName($name)
    {
        return $this->findOneBy(array(
            'machine_name' => $name
        ));
    }

    /**
     * Find vocabularies by its Machine name
     *
     * @param string $name
     * @return null|\DrupalConnect\Document\Taxanomy\Vocabulary[]
     */
    public function findByMachineName($name)
    {
        return $this->findBy(array(
            'machine_name' => $name
        ));
    }
}
