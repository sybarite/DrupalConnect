<?php
namespace DrupalConnect\Document\Taxanomy;

/**
 * Representation of a Taxanomy Vocabulary (or entity) in Drupal
 */
class Vocabulary extends \DrupalConnect\Document\AbstractDocument
{
    /**
     * Primary Key: Unique vocabulary ID.
     *
     * @var int
     */
    protected $_vocabularyId;

    /**
     * Name of the vocabulary.
     *
     * @var string
     */
    protected $_name;

    /**
     * The vocabulary machine name.
     *
     * @var string
     */
    protected $_machineName;

    /**
     * Description of the vocabulary.
     *
     * @var string
     */
    protected $_description;

    /**
     * The type of hierarchy allowed within the vocabulary. (0 = disabled, 1 = single, 2 = multiple)
     *
     * @var int
     */
    protected $_hierarchy;

    /**
     * The module which created the vocabulary.
     *
     * @var string
     */
    protected $_module;

    /**
     * The weight of this vocabulary in relation to other vocabularies.
     *
     * @var int
     */
    protected $_weight;


    /**
     * @param string $description
     * @return Vocabulary
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
     * @param int $hierarchy
     * @return Vocabulary
     */
    public function setHierarchy($hierarchy)
    {
        $this->_hierarchy = $hierarchy;
        return $this;
    }

    /**
     * @return int
     */
    public function getHierarchy()
    {
        return $this->_hierarchy;
    }

    /**
     * @param string $machineName
     * @return Vocabulary
     */
    public function setMachineName($machineName)
    {
        $this->_machineName = $machineName;
        return $this;
    }

    /**
     * @return string
     */
    public function getMachineName()
    {
        return $this->_machineName;
    }

    /**
     * @param string $module
     * @return Vocabulary
     */
    public function setModule($module)
    {
        $this->_module = $module;
        return $this;
    }

    /**
     * @return string
     */
    public function getModule()
    {
        return $this->_module;
    }

    /**
     * @param string $name
     * @return Vocabulary
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
     * @return Vocabulary
     */
    public function setVocabularyId($vid)
    {
        $this->_vocabularyId = $vid;
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
     * @param int $weight
     * @return Vocabulary
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
}
