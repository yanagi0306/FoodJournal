<?php

namespace App\Traits;

use Monolog\Formatter\LineFormatter;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

trait LogTrait
{
    protected static string $logPath;

    /**
     * ログの出力先を設定
     * コンストラクタで以下の設定が必要
     *
     * @param ?string $path
     */
    public static function setLogPath(?string $path): void
    {
        self::$logPath = $path;
    }

    /**
     * ログを出力する
     *
     * @param string $message ログメッセージ
     * @param string $logLevel ログレベル
     */
    public static function setLog(string $message, string $logLevel = 'info'): void
    {
        // ディレクトリが存在しない場合は作成する
        $logDir = dirname(self::$logPath);
        if (!file_exists($logDir)) {
            mkdir($logDir, 0755, true);
        }

        $monolog = new Logger(self::$logPath);
        $handler = new StreamHandler(self::$logPath, Logger::toMonologLevel($logLevel));
        $handler->setFormatter(
            new LineFormatter("[%datetime%][%level_name%]%message%\n", 'Y/m/d H:i:s')
        );
        $monolog->pushHandler($handler);

        // ログ出力
        $monolog->$logLevel($message);
    }

}

