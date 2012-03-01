<?php
namespace DrupalConnect;

/**
 * Iterator interface
 */
interface Iterator extends \Iterator, \Countable
{
    function toArray();
    function getSingleResult();
}
