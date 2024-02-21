<?php

namespace Service;

class LoggerService
{
    private const string STORAGE_PATH = './../Storage/Logs/';

    public static function error(\Throwable $exception): void
    {
        $date = date('Y-m-d H:i:s');
        $file = $exception->getFile();
        $line = $exception->getLine();
        $message = $exception->getMessage();

        $filename = self::STORAGE_PATH . 'error.txt';
        $content = "Date: {$date}\nFile: {$file}\nLine: {$line}\nMessage: {$message}\n\n";

        file_put_contents($filename, $content, FILE_APPEND);
    }

    public static function info(string $info) : void
    {
        $date = date('Y-m-d H:i:s');

        $filename = self::STORAGE_PATH . 'info.txt';
        $content = "Date: {$date}\nInfo: {$info}\n\n";

        file_put_contents($filename, $content, FILE_APPEND);
    }

}