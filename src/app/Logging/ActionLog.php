<?php

namespace App\Logging;

use Monolog\Formatter\LineFormatter;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Monolog\Processor\IntrospectionProcessor;

class ActionLog
{
    public function __invoke(array $config): Logger
    {
        $date = date('ymd');
        $logPath = $config['logPath'] ?? "/logs/app_{$date}.log";
        $userId = $config['userId'] ?? null;
        $userName = $config['userName'] ?? null;
        $progName = $config['progName'] ?? null;
        $userIdFormat = ($userId !== null) ? "[id:{$userId} name:{$userName}]"  : '';
        $format = "[%datetime%{$userIdFormat}%level_name% %message%{$progName}:%extra.line%\n";
        $dateFormat = 'Y-m-d H:i:s';

        $lineFormatter = new LineFormatter($format, $dateFormat, true, true);

        $handler = new StreamHandler($logPath, Logger::DEBUG);
        $handler->setFormatter($lineFormatter);

        $handler->pushProcessor(new IntrospectionProcessor(Logger::DEBUG, [
            'App\Logging\ActionLog',
            'Monolog\Logger',
            'Illuminate\Log\Writer',
            'Illuminate\Log\Logger',
        ]));

        $logger = new Logger('action');
        $logger->pushHandler($handler);

        return $logger;
    }
}



