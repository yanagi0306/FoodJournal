<?php

namespace App\Traits;

use Monolog\Formatter\LineFormatter;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

trait LogTrait
{
    protected string $logPath;

    /**
     * ログの出力先を設定
     * 使用前に設定の必要あり
     *
     * @param string $path
     */
    public function setLogPath(string $path): void
    {
        $this->logPath = $path;
    }

    /**
     * ログを出力する
     *
     * @param string $message ログメッセージ
     * @param string $logLevel ログレベル
     */
    public function setLog(string $message, string $logLevel = 'info'): void
    {
        // ディレクトリが存在しない場合は作成する
        $logDir = dirname($this->logPath);
        if (!file_exists($logDir)) {
            mkdir($logDir, 0755, true);
        }

        // Monologの設定
        $monolog = new Logger($this->logPath);
        $handler = new StreamHandler($this->logPath, Logger::toMonologLevel($logLevel));
        $handler->setFormatter(
            new LineFormatter("[%datetime%][%level_name%]%message%\n", 'Y/m/d H:i:s')
        );
        $monolog->pushHandler($handler);

        // ログ出力
        $monolog->$logLevel($message);
    }


}
