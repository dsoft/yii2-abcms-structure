<?php

namespace abcms\structure\classes;

interface FieldTypesInterface
{
    /**
     * Return an array containing the field type classname as key and the field type name as value.
     * @return Array
     */
    public static function getTypes();
}