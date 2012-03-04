<?php
namespace DrupalConnect\Hydrator;

use \DrupalConnect\Mapping\TypeManager as TypeManager;

/**
 * Hydrates Node data that is received from the default endpoints provided by the Services v3 module
 */
class Node extends AbstractHydrator
{
    const CUSTOM_FIELD_PREFIX = 'field_';

    /**
     * Get the hydrated version of the document.
     *
     * @param array $data
     * @return \DrupalConnect\Document\Node
     */
    public function hydrate(array $data)
    {
        /**
         * @var \DrupalConnect\Document\Node $node
         */
        $node = new $this->_documentName();

        $node->setNodeId(TypeManager::getType('integer')->convertToPHPValue($data['nid']))
             ->setVersionId(TypeManager::getType('integer')->convertToPHPValue($data['vid']))
             ->setType(TypeManager::getType('string')->convertToPHPValue($data['type']))
             ->setLanguage(TypeManager::getType('string')->convertToPHPValue($data['language']))
             ->setTitle(TypeManager::getType('string')->convertToPHPValue($data['title']))
             ->setUserId(TypeManager::getType('integer')->convertToPHPValue($data['uid']))
             ->setStatus(TypeManager::getType('boolean')->convertToPHPValue($data['status']))
             ->setCreated(TypeManager::getType('date')->convertToPHPValue($data['created']))
             ->setChanged(TypeManager::getType('date')->convertToPHPValue($data['changed']))
             ->setComment(TypeManager::getType('integer')->convertToPHPValue($data['comment']))
             ->setPromote(TypeManager::getType('boolean')->convertToPHPValue($data['promote']))
             ->setSticky(TypeManager::getType('boolean')->convertToPHPValue($data['sticky']));
             //->setTranslationSetId(TypeManager::getType('integer')->convertToPHPValue($data['tnid']))
             //->setTranslate(TypeManager::getType('boolean')->convertToPHPValue($data['translate']));

        // if the node field 'body' is set (this is not a custom field)
        if (isset($data['body']))
        {
            // loop through every language for body
            foreach ($data['body'] as $language => $fieldSet)
            {
                if ($fieldSet) // if fieldSet is not null or [] (empty array)
                {
                    foreach ($fieldSet as $value)
                    {
                        // set the body data
                        $node->addToBody($value, array(
                            'language' => $language
                        ));
                    }
                }
            }
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
                            $node->addToField($fieldName, $value, array(
                                'language' => $language
                            ));
                        }
                    }
                }
            }
        }


        return $node;
    }
}
