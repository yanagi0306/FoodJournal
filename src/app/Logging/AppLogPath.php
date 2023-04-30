<?php

namespace App\Logging;

use Monolog\Formatter\LineFormatter;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Monolog\Processor\IntrospectionProcessor;

class AppLogPath
{
    public function __invoke(array $config): Logger
    {
        $date = date('ymd');
        $logPath      = $config['logPath'] ?? "/logs/app_{$date}.log";
        $userId       = $config['userId'] ?? null;
        $progName     = $config['progName'] ?? null;
        $userIdFormat = ($userId !== null) ? '[userId:' . $userId . '] ' : '';
        $format       = "[%datetime%{$userIdFormat}%level_name%%message%{$progName}:%extra.line%" . PHP_EOL;
        $dateFormat   = 'Y-m-d H:i:s';

        $lineFormatter = new LineFormatter($format, $dateFormat, true, true);

        $handler       = new StreamHandler($logPath, Logger::INFO);
        $handler->setFormatter($lineFormatter);

        $logger = new Logger('app');
        $logger->pushHandler($handler);
        $logger->pushProcessor(new IntrospectionProcessor(Logger::DEBUG, ['App\Logging\AppLogPath', 'Monolog\Logger', 'Illuminate\Log\Writer', 'Illuminate\Log\Logger']));

        return $logger;
    }
}


