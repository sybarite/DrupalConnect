<?php
namespace DrupalConnect\Document\Taxanomy;

/**
 * Representation of a Taxanomy Term (or entity) in Drupal
 */
class Term extends \DrupalConnect\Document\AbstractFieldSupport
{
    /**
     * Primary Key: Unique term ID.
     *
     * @var int
     */
    protected $_termId;

    /**
     * The drupal_taxonomy_vocabulary.vid of the vocabulary to which the term is assigned.
     *
     * @var int
     */
    protected $_vocabularyId;

    /**
     * The term name.
     *
     * @var string
     */
    protected $_name;

    /**
     * A description of the term.
     *
     * @var string
     */
    protected $_description;

    /**
     * The weight of this term in relation to other terms.
     *
     * @var int
     */
    protected $_weight;

    /**
     * Machine name of the vocabulary to which the term is assigned
     *
     * @var string
     */
    protected $_vocabularyMachineName;


    /**
     * @param string $description
     * @return Term
     */
    public function setDescription($description)
    {
        $this->_description = $description;
        return $this;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->_description;
    }

    /**
     * @param string $name
     * @return Term
     */
    public function setName($name)
    {
        $this->_name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->_name;
    }

    /**
     * @param int $vid
     * @return Term
     */
    public function setTermId($vid)
    {
        $this->_termId = $vid;
        return $this;
    }

    /**
     * @return int
     */
    public function getTermId()
    {
        return $this->_termId;
    }

    /**
     * @param int $weight
     * @return Term
     */
    public function setWeight($weight)
    {
        $this->_weight = $weight;
        return $this;
    }

    /**
     * @return int
     */
    public function getWeight()
    {
        return $this->_weight;
    }

    /**
     * @param int $vocabularyId
     * @return Term
     */
    public function setVocabularyId($vocabularyId)
    {
        $this->_vocabularyId = $vocabularyId;
        return $this;
    }

    /**
     * @return int
     */
    public function getVocabularyId()
    {
        return $this->_vocabularyId;
    }

    /**
     * @param string $vocabularyMachineName
     * @return Term
     */
    public function setVocabularyMachineName($vocabularyMachineName)
    {
        $this->_vocabularyMachineName = $vocabularyMachineName;
        return $this;
    }

    /**
     * @return string
     */
    public function getVocabularyMachineName()
    {
        return $this->_vocabularyMachineName;
    }


}
