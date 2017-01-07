<?php

/**
 * Class Stub
 */
abstract class Stub
{
    /**
     * @param $module
     * @param $action
     * @return string
     */
    public static function getStub($module, $action)
    {
        return file_get_contents(__DIR__ . '/' . 'stubs' . '/' . $module . '/' . $action . '.json');
    }
}