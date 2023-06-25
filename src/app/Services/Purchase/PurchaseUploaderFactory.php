<?php

namespace App\Services\Purchase;

use App\Constants\Common;
use App\Services\Company\FetchesCompanyInfo;
use App\Services\Purchase\Aspit\CsvPurchaseUploader;
use Illuminate\Http\UploadedFile;
use InvalidArgumentException;

/**
 * 仕入アップローダーファクトリークラス
 * @package App\Services\PurchaseUploaders
 */
class PurchaseUploaderFactory
{
    /**
     * 指定されたシステムに応じたアップローダーインスタンスを生成
     * @param UploadedFile       $uploadedFile アップロードされたファイル
     * @param FetchesCompanyInfo $companyInfo
     * @return CsvPurchaseUploader
     */
    public static function createUploader(UploadedFile $uploadedFile, FetchesCompanyInfo $companyInfo): CsvPurchaseUploader
    {
        return match ($companyInfo->company->purchase_system) {
            Common::ASPIT_SYSTEM_NAME => new CsvPurchaseUploader($uploadedFile, $companyInfo),
            default => throw new InvalidArgumentException("無効なシステムが入力されました システム名:({$companyInfo->company->purchase_system})"),
        };
    }
}
