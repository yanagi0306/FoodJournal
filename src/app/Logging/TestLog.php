<?php

namespace App\Logging;

use App\Constants\Common;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Monolog\Processor\IntrospectionProcessor;

class TestLog
{
    public function __invoke(): Logger
    {
        $date = date('ymd');
        $logPath    = Common::LOGS_DIR . "/test_{$date}.log";
        $dateFormat = 'Y-m-d H:i:s';
        $format     = '[%datetime%][%level_name%] %message% [%extra.class%:%extra.line%]' . PHP_EOL;

        $lineFormatter = new LineFormatter($format, $dateFormat, true, true);

        $handler = new StreamHandler($logPath, Logger::DEBUG);
        $handler->setFormatter($lineFormatter);

        $handler->pushProcessor(new IntrospectionProcessor(Logger::DEBUG, [
            'App\Logging\TestLog',
            'Monolog\Logger',
            'Monolog\Handler\StreamHandler',
            'Illuminate\Log\Writer',
            'Illuminate\Log\Logger',
            'Illuminate\Log\LogManager',
            'Illuminate\Support\Facades\Facade',
        ]));

        $logger = new Logger('test');
        $logger->pushHandler($handler);

        return $logger;
    }
}
