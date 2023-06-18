<?php
namespace App\Services\Order;

use App\Services\Usen\Order\CsvOrderUploader;
use Illuminate\Http\UploadedFile;
use InvalidArgumentException;

/**
 * 売上アップローダーファクトリークラス
 * @package App\Services\OrderUploaders
 */
class OrderUploaderFactory
{
    /**
     * 指定されたシステムに応じたアップローダーインスタンスを生成
     * @param string $system システム名（例: 'usen'）
     * @param UploadedFile $uploadedFile アップロードされたファイル
     * @param int $companyId 会社ID
     * @return OrderUploaderInterface
     * @throws InvalidArgumentException
     */
    public static function createUploader(string $system, UploadedFile $uploadedFile, int $companyId, array $storeIds): OrderUploaderInterface
    {
        return match ($system) {
            'usen' => new CsvOrderUploader($uploadedFile, $companyId, $storeIds),
            default => throw new InvalidArgumentException("無効なシステムが入力されました システム名:({$system})"),
        };
    }
}
