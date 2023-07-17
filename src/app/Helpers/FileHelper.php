<?php

namespace App\Helpers;

use Exception;
use Illuminate\Http\UploadedFile;

class FileHelper
{
    /**
     * アップロードされたファイルを指定された宛先パスに移動して保存する
     * @param UploadedFile $uploadedFile
     * @param string       $destinationPath
     * @param string       $fileName
     * @return bool
     */
    public static function moveAndStoreUploadedFile(UploadedFile $uploadedFile, string $destinationPath, string $fileName): bool
    {
        // ディレクトリが存在しない場合は作成
        if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 0755, true);
        }

        // ファイルを移動
        $uploadedFile->move($destinationPath, $fileName);

        return true;
    }

    /**
     * 一意のファイル名を生成
     * @param $typeName
     * @return string
     */
    public static function generateUniqueFileName($typeName): string
    {
        $timestamp = date('YmdHis', microtime(true)) . substr(sprintf('%0.6f', microtime(true)), -6);
        return "{$typeName}_{$timestamp}.csv";
    }
}
