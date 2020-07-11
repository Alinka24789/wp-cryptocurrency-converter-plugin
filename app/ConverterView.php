<?php


namespace app;


class ConverterView
{
    public static function view($name)
    {

        $file = CONVERTER__PLUGIN_DIR . 'views/' . $name . '.php';

        include($file);
    }
}