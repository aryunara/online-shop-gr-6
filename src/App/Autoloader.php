<?php


class Autoloader
{
    public static function registrate()
    {
        $autoloader = function (string $class): bool {
            $filename = './../' . str_replace('\\', '/', $class) . '.php';
            if (file_exists($filename)) {
                require_once $filename;
                return true;
            }
            return false;
        };
        spl_autoload_register($autoloader);
    }
}