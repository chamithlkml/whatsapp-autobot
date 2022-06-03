<?php


class Logger
{
    private static $log_path = './api.log';

    /**
     * Log info message
     * @param $message
     */
    public static function info($message){
        self::log($message, 'INFO');
    }

    /**
     * Log error message
     * @param $message
     */
    public static function error($message){
        self::log($message, 'ERROR');
    }

    private static function log($message, $type){
        $timely_message = date('Y-m-d H:i:s') . ' ' . $type . ' ' . $message . "\n";
        file_put_contents(self::$log_path, $timely_message, FILE_APPEND);
    }
}