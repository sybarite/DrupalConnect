<?php
namespace DrupalConnect\Hydrator\Views;

use \DrupalConnect\Mapping\TypeManager as TypeManager;

/**
 * Hydrates Node data that is received from VIEWS (via the Services Views module)
 */
class Node extends \DrupalConnect\Hydrator\AbstractHydrator
{
    const CUSTOM_FIELD_PREFIX = 'field_field_';

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

        $node->setNodeId(TypeManager::getType('integer')->convertToPHPValue($data['nid']));

        if (isset($data['node_title']))
        {
            $node->setTitle(TypeManager::getType('string')->convertToPHPValue($data['node_title']));
        }

        if (isset($data['node_created']))
        {
            $node->setCreated(TypeManager::getType('date')->convertToPHPValue($data['node_created']));
        }

        if (isset($data['node_revision_vid']))
        {
            $node->setVersionId(TypeManager::getType('integer')->convertToPHPValue($data['node_revision_vid']));
        }

        if (isset($data['node_type']))
        {
            $node->setType(TypeManager::getType('string')->convertToPHPValue($data['node_type']));
        }

        if (isset($data['node_uid']))
        {
            $node->setUserId(TypeManager::getType('integer')->convertToPHPValue($data['node_uid']));
        }

        if (isset($data['node_created']))
        {
            $node->setCreated(TypeManager::getType('date')->convertToPHPValue($data['node_created']));
        }

        if (isset($data['node_changed']))
        {
            $node->setChanged(TypeManager::getType('date')->convertToPHPValue($data['node_changed']));
        }

        if (isset($data['node_comment']))
        {
            $node->setComment(TypeManager::getType('integer')->convertToPHPValue($data['node_comment']));
        }

        if (isset($data['node_promote']))
        {
            $node->setPromote(TypeManager::getType('boolean')->convertToPHPValue($data['node_promote']));
        }

        if (isset($data['node_sticky']))
        {
            $node->setSticky(TypeManager::getType('boolean')->convertToPHPValue($data['node_sticky']));
        }

        // if the node field 'body' is set (this is not a custom field)
        if (isset($data['field_body']))
        {
            foreach ($data['field_body'] as $bodyData)
            {
                $node->addToBody($bodyData['raw']);
            }
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
                        $node->addToField($fieldName, $value['raw']);
                    }
                }
            }
        }

        // set the base urls so that file/image fields can generate URLs required
        $node->setFileBaseUrls($this->_dm->getConfig('file_base_url'));

        return $node;
    }
}
