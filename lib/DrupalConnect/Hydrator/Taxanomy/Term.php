<?php
namespace DrupalConnect\Hydrator\Taxanomy;

use \DrupalConnect\Mapping\TypeManager as TypeManager;

/**
 * Hydrator for Taxanomy Term Documents
 */
class Term extends \DrupalConnect\Hydrator\AbstractHydrator
{
    const CUSTOM_FIELD_PREFIX = 'field_';

    /**
     * Get the hydrated version of the document.
     *
     * @param array $data
     * @return \DrupalConnect\Document\Taxanomy\Term
     */
    public function hydrate(array $data)
    {
        /**
             * @var \DrupalConnect\Document\Taxanomy\Term $term
             */
        $term = new $this->_documentName();

        // tid is (and must be) always returned
        $term->setTermId(TypeManager::getType('integer')->convertToPHPValue($data['tid']));

        if (isset($data['vid']))
        {
            $term->setVocabularyId(TypeManager::getType('integer')->convertToPHPValue($data['vid']));
        }
        if (isset($data['name']))
        {
            $term->setName(TypeManager::getType('string')->convertToPHPValue($data['name']));
        }
        if (isset($data['description']))
        {
            $term->setDescription(TypeManager::getType('string')->convertToPHPValue($data['description']));
        }
        if (isset($data['weight']))
        {
            $term->setWeight(TypeManager::getType('integer')->convertToPHPValue($data['weight']));
        }
        if (isset($data['vocabulary_machine_name']))
        {
            $term->setVocabularyMachineName(TypeManager::getType('string')->convertToPHPValue($data['vocabulary_machine_name']));
        }

        $prefixLength = strlen(self::CUSTOM_FIELD_PREFIX);

        /**
             * loop through all keys and extract custom fields and assign them to node
             */
        foreach ($data as $key => $fieldData)
        {
            if (strpos($key, self::CUSTOM_FIELD_PREFIX) === 0)
            {
                $fieldName = substr($key, $prefixLength);

                // loop through every language for this fieldset
                foreach ($data[$key] as $language => $fieldSet)
                {
                    if ($fieldSet) // if fieldSet is not null or [] (empty array)
                    {
                        foreach ($fieldSet as $value)
                        {
                            $term->addToField($fieldName, $value, array(
                                'language' => $language
                            ));
                        }
                    }
                }
            }
        }

        // set the base urls so the File document can generate URLs for the image styles requested
        $term->setFileBaseUrls($this->_dm->getConfig('file_base_url'));

        return $term;
    }
}
