<?php
namespace DrupalConnect\Document\Field;

/**
 * For all Number fields
 */
interface Number extends \DrupalConnect\Document\Field
{
    /**
     * @abstract
     * @return int|float|null
     */
    public function getValue();
}
