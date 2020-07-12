<?php


namespace app;


class ConverterView
{
    public static function view(string $name, array $args = [])
    {

        $file = CPC_PLUGIN_DIR . 'views/' . $name . '.php';

        if (count($args)) {
            extract($args);
        }
        include($file);
    }
}