<?php

namespace App\Services\Files;

use Illuminate\Support\Facades\Log;

class UploadHistory
{

    protected UploadDirectory $uploadDirectory;

    public function __construct(string $type, int $companyId)
    {
        $uploadDirectory = new UploadDirectory($type, $companyId);
        $this->uploadDirectory = $uploadDirectory;
    }

    /**
     * アップロード履歴から最新の10ファイルを取得する。
     * @return array
     */
    public function getUploadHistory(): array
    {
        $uploadDir = $this->uploadDirectory->getPath();

        // ディレクトリが存在しない場合
        if (!file_exists($uploadDir)) {
            return array();
        }

        // `$uploadDir` ディレクトリ内の全ファイル/ディレクトリのリストを取得('.'と'..'を除く)
        $historyFiles = array_diff(scandir($uploadDir), array('.', '..'));

        // ファイル名と作成日を持つ配列を作成
        $filesWithCreationDates = [];
        foreach ($historyFiles as $file) {
            $filePath = $uploadDir . '/' . $file;
            $filesWithCreationDates[] = [
                'name' => $file,
                'created_at' => filectime($filePath)
            ];
        }

        // 作成日をもとにソート
        usort($filesWithCreationDates, function ($a, $b) {
            return $b['created_at'] - $a['created_at'];
        });

        // 最新の10ファイル/ディレクトリを取得
        return array_slice($filesWithCreationDates, 0, 10);
    }
}
