<?php

namespace App\Utils;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class LoggerHelper {
    private static $logger;

    public static function init() {
        // Crie uma instância do logger
        self::$logger = new Logger('app_logger');

        // Crie um manipulador que gravará os logs em um arquivo
        $logFile = __DIR__ . '/../../logs/app.log'; // Altere o caminho conforme necessário
        self::$logger->pushHandler(new StreamHandler($logFile, Logger::DEBUG));
    }

    public static function logInfo($message) {
        self::$logger->info($message);
    }

    public static function logWarning($message) {
        self::$logger->warning($message);
    }

    public static function logError($message) {
        self::$logger->error($message);
    }
}
