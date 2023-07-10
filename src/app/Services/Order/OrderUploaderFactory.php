<?php

namespace App\Services\Order;

use App\Constants\UsenConstants;
use app\Services\Company\FetchesCompanyInfo;
use app\Services\Order\Usen\Usen\CsvOrderUploader;
use Exception;
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
     * @param UploadedFile       $uploadedFile アップロードされたファイル
     * @param FetchesCompanyInfo $companyInfo
     * @return OrderUploaderInterface
     * @throws Exception
     */
    public static function createUploader(UploadedFile $uploadedFile, FetchesCompanyInfo $companyInfo): OrderUploaderInterface
    {
        return match ($companyInfo->getCompanyValue('order_system')) {
            UsenConstants::USEN_SYSTEM_NAME => new CsvOrderUploader($uploadedFile, $companyInfo),
            default => throw new InvalidArgumentException("無効なシステムが入力されました システム名:({$companyInfo->getCompanyValue('order_system')})"),
        };
    }
}
