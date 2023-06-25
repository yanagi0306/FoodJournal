<?php

namespace App\Services\Order;

use App\Constants\Common;
use App\Constants\UsenConstants;
use App\Services\Company\FetchesCompanyInfo;
use App\Services\Order\Usen\CsvOrderUploader;
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
     */
    public static function createUploader(UploadedFile $uploadedFile, FetchesCompanyInfo $companyInfo): OrderUploaderInterface
    {
        return match ($companyInfo->company->order_system) {
            UsenConstants::USEN_SYSTEM_NAME => new CsvOrderUploader($uploadedFile, $companyInfo),
            default => throw new InvalidArgumentException("無効なシステムが入力されました システム名:({$companyInfo->company->order_system})"),
        };
    }
}
