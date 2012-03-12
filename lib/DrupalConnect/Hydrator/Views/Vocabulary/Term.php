<?php
namespace DrupalConnect\Hydrator\Views\Vocabulary;

use \DrupalConnect\Mapping\TypeManager as TypeManager;

/**
 *
 */
class Term extends \DrupalConnect\Hydrator\AbstractHydrator
{
    const CUSTOM_FIELD_PREFIX = 'field_field_';

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

        if (isset($data['taxonomy_term_data_vid']))
        {
            $term->setVocabularyId(TypeManager::getType('integer')->convertToPHPValue($data['taxonomy_term_data_vid']));
        }
        if (isset($data['taxonomy_term_data_name']))
        {
            $term->setName(TypeManager::getType('string')->convertToPHPValue($data['taxonomy_term_data_name']));
        }
        if (isset($data['taxonomy_term_data_description']))
        {
            $term->setDescription(TypeManager::getType('string')->convertToPHPValue($data['taxonomy_term_data_description']));
        }
        if (isset($data['taxonomy_term_data_weight']))
        {
            $term->setWeight(TypeManager::getType('integer')->convertToPHPValue($data['taxonomy_term_data_weight']));
        }
        if (isset($data['taxonomy_vocabulary_machine_name']))
        {
            $term->setVocabularyMachineName(TypeManager::getType('string')->convertToPHPValue($data['taxonomy_vocabulary_machine_name']));
        }

        $prefixLength = strlen(self::CUSTOM_FIELD_PREFIX);

        /**
             * loop through all keys and extract custom fields and assign them to node
             */
        foreach ($data as $key => $fieldSet)
        {
            if (strpos($key, self::CUSTOM_FIELD_PREFIX) === 0)
            {
                $fieldName = substr($key, $prefixLength);

                if ($fieldSet) // if fieldSet is not null or [] (empty array)
                {
                    foreach ($fieldSet as $value)
                    {
                        $term->addToField($fieldName, $value['raw']);
                    }
                }
            }
        }

        // set the base urls so that file/image fields can generate URLs required
        $term->setFileBaseUrls($this->_dm->getConfig('file_base_url'));


        return $term;
    }
}
