<?php
namespace DrupalConnect\Hydrator\Taxanomy;

use \DrupalConnect\Mapping\TypeManager as TypeManager;

/**
 * Hydrator for Taxanomy Vocabulary Documents
 */
class Vocabulary extends \DrupalConnect\Hydrator\AbstractHydrator
{
    /**
     * Get the hydrated version of the document.
     *
     * @param array $data
     * @return \DrupalConnect\Document\Taxanomy\Vocabulary
     */
    public function hydrate(array $data)
    {
        /**
             * @var \DrupalConnect\Document\Taxanomy\Vocabulary $vocabulary
             */
        $vocabulary = new $this->_documentName();

        // vid is (and must be) always returned
        $vocabulary->setVocabularyId(TypeManager::getType('integer')->convertToPHPValue($data['vid']));

        if (isset($data['name']))
        {
            $vocabulary->setName(TypeManager::getType('string')->convertToPHPValue($data['name']));
        }
        if (isset($data['machine_name']))
        {
            $vocabulary->setMachineName(TypeManager::getType('string')->convertToPHPValue($data['machine_name']));
        }
        if (isset($data['description']))
        {
            $vocabulary->setDescription(TypeManager::getType('string')->convertToPHPValue($data['description']));
        }
        if (isset($data['hierarchy']))
        {
            $vocabulary->setHierarchy(TypeManager::getType('integer')->convertToPHPValue($data['hierarchy']));
        }
        if (isset($data['module']))
        {
            $vocabulary->setModule(TypeManager::getType('string')->convertToPHPValue($data['module']));
        }
        if (isset($data['weight']))
        {
            $vocabulary->setWeight(TypeManager::getType('integer')->convertToPHPValue($data['weight']));
        }

        return $vocabulary;
    }
}
