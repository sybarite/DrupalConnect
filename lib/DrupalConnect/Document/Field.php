<?php
namespace DrupalConnect\Document;

interface Field
{
    /**
     * @param array|null $data Field Data
     */
    public function __construct(array $data = null);
}