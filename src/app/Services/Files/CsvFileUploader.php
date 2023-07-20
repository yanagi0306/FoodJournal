<?php

namespace App\Services\Files;

use App\Constants\Common;
use Exception;
use Illuminate\Http\UploadedFile;


/**
 * CSVファイルアップロードを処理するクラス
 */
class CsvFileUploader
{

    /**
     * @var string $targetDirectory
     * アップロード対象ディレクトリ
     */
    private string $targetDirectory;

    /**
     * @var array $allowedExtensions
     * 許可された拡張子のリスト
     */
    private array $allowedExtensions = ['csv'];

    /**
     *
     * @param string $userDirectory
     */
    public function __construct(string $userDirectory)
    {
        $this->targetDirectory = Common::UPLOAD_DIR . $userDirectory;
    }

    /**
     * ファイルアップロード処理
     *
     * @param UploadedFile $file
     * @return string
     * @throws Exception
     */
    public function upload(UploadedFile $file): string
    {
        $this->validateExtension($file->getClientOriginalExtension());
        $fileName = $this->generateUniqueFileName();
        $file->move($this->targetDirectory, $fileName);
        return $fileName;
    }

    /**
     * 拡張子の検証
     *
     * @param string $extension
     * @return void
     * @throws Exception
     */
    private function validateExtension(string $extension): void
    {
        if (!in_array($extension, $this->allowedExtensions)) {
            throw new Exception("ファイル拡張子が無効です: {$extension}");
        }
    }

    /**
     * 一意のファイル名を生成
     *
     * @return string
     */
    private function generateUniqueFileName(): string
    {
        $timestamp = date('YmdHis', microtime(true)) . substr(sprintf('%0.6f', microtime(true)), -6);
        return "{$timestamp}.csv";
    }
}
