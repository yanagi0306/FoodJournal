<?php

namespace App\Services\Base;

use App\Constants\Common;
use App\Helpers\FileHelper;
use App\Services\Company\FetchesCompanyInfo;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;

abstract class BaseUploader
{

    protected UploadedFile       $uploadFile;
    protected FetchesCompanyInfo $companyInfo;
    protected string             $type;

    public function __construct(UploadedFile $uploadFile, FetchesCompanyInfo $companyInfo)
    {
        $this->uploadFile  = $uploadFile;
        $this->companyInfo = $companyInfo;
    }

    abstract public function processCsv(): string;

    protected function moveUploadedFile(): void
    {
        try {
            // 保存先ディレクトリ定義
            $destinationPath = Common::UPLOAD_DIR . "/{$this->type}/{$this->companyInfo->company->id}";

            // 一意のファイル名を生成
            $fileName = FileHelper::generateUniqueFileName($this->type);

            // アップロードされたファイルを移動して保存
            FileHelper::moveAndStoreUploadedFile($this->uploadFile, $destinationPath, $fileName);

        } catch (\Exception $e) {
            Log::error('アップロードファイルの取得に失敗しました。' . $e->getMessage());
        }
    }
}
