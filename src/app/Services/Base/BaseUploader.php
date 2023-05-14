<?php

namespace App\Services;

use App\Constants\Common;
use App\Helpers\FileHelper;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;

abstract class BaseUploader
{
    protected UploadedFile $uploadFile;
    protected int $companyId;
    protected string $type;

    public function __construct(UploadedFile $uploadFile, int $companyId)
    {
        $this->companyId  = $companyId;
        $this->uploadFile = $uploadFile;
    }

    abstract public function processCsv(): void;

    protected function moveUploadedFile(): void
    {
        try {
            // 保存先ディレクトリ定義
            $destinationPath = Common::UPLOAD_DIR . "/{$this->type}/{$this->companyId}";

            // 一意のファイル名を生成
            $fileName = FileHelper::generateUniqueFileName($this->type);

            // アップロードされたファイルを移動して保存
            FileHelper::moveAndStoreUploadedFile($this->uploadFile, $destinationPath, $fileName);

        } catch (\Exception $e) {
            Log::error('アップロードファイルの保存に失敗しました msg=' . $e->getMessage());
        }
    }
}
