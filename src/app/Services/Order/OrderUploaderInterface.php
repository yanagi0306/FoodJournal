<?php
namespace App\Services\Order;

/**
 * Interface OrderUploaderInterface
 * 売上アップローダーインターフェース
 * @package App\Services\Order
 */
interface OrderUploaderInterface
{
    /**
     * CSVファイルの処理を実行
     */
    public function processCsv(): string;
}
