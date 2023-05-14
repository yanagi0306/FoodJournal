<?php

namespace App\Services\Files;

use App\Constants\Common;
use Illuminate\Support\Facades\Log;

class UploadHistory
{

    private string $uploadDir;

    public function __construct(string $type, int $companyId)
    {
        $this->uploadDir = Common::UPLOAD_DIR ."/{$type}/{$companyId}";
    }

    /**
     * アップロード履歴から最新の10ファイルを取得する。
     * @return array
     */
    public function getUploadHistory(): array
    {
        // ディレクトリが存在しない場合
        if (!file_exists($this->uploadDir)) {
            return array();
        }

        // `$uploadDir` ディレクトリ内の全ファイル/ディレクトリのリストを取得('.'と'..'を除く)
        $historyFiles = array_diff(scandir($this->uploadDir), array('.', '..'));

        // ファイル名と作成日を持つ配列を作成
        $filesWithCreationDates = [];
        foreach ($historyFiles as $file) {
            $filePath = $this->uploadDir . '/' . $file;
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
