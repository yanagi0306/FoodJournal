<?php
namespace App\Services\Purchase;

/**
 * Interface PurchaseUploaderInterface
 * 仕入アップローダーインターフェース
 * @package App\Services\Purchase
 */
interface PurchaseUploaderInterface
{
    /**
     * CSVファイルの処理を実行
     */
    public function processCsv(): string;
}
