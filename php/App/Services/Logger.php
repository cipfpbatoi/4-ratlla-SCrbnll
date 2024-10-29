<?php

namespace Joc4enRatlla\Services;

use Monolog\Logger as MonologLogger;
use Monolog\Handler\StreamHandler;

class Logger {
    private static ?MonologLogger $instance = null;

    private function __construct() {}

    public static function getInstance(): MonologLogger {
        if (self::$instance === null) {
            self::$instance = new MonologLogger('app');
            self::$instance->pushHandler(new StreamHandler(__DIR__ . '/../../logs/game.log', MonologLogger::INFO));
            self::$instance->pushHandler(new StreamHandler(__DIR__ . '/../../logs/error.log', MonologLogger::ERROR));
        }
        
        return self::$instance;
    }
}
