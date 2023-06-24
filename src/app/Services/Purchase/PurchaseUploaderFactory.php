<?php
namespace App\Services\Purchase;

use App\Services\Purchase\Aspit\CsvPurchaseUploader;
use Illuminate\Http\UploadedFile;
use InvalidArgumentException;

/**
 * 売上アップローダーファクトリークラス
 * @package App\Services\PurchaseUploaders
 */
class PurchaseUploaderFactory
{
    /**
     * 指定されたシステムに応じたアップローダーインスタンスを生成
     * @param string       $system       システム名（例: 'Aspit'）
     * @param UploadedFile $uploadedFile アップロードされたファイル
     * @param int          $companyId    会社ID
     * @return CsvPurchaseUploader
     */
    public static function createUploader(string $system, UploadedFile $uploadedFile, int $companyId): CsvPurchaseUploader
    {
        return match ($system) {
            'aspit' => new CsvPurchaseUploader($uploadedFile, $companyId),
            default => throw new InvalidArgumentException("無効なシステムが入力されました システム名:({$system})"),
        };
    }
}
