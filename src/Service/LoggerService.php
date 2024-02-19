<?php

namespace Service;

class LoggerService
{
    const string filepath = './../Storage/Logs/';
    public static function error(\Throwable $exception): void
    {
        $date = date('Y-m-d H:i:s');
        $file = $exception->getFile();
        $line = $exception->getLine();
        $message = $exception->getMessage();

        $filename = self::filepath . 'error.txt';
        $content = "Date: {$date}\nFile: {$file}\nLine: {$line}\nMessage: {$message}\n\n";

        file_put_contents($filename, $content, FILE_APPEND);
    }

    public static function info()
    {
        $filename = self::filepath . 'info.txt';
    }

}