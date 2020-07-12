<?php


namespace app;


class ConverterView
{
    public static function view(string $name)
    {

        $file = CPC_PLUGIN_DIR . 'views/' . $name . '.php';

        include($file);
    }
}