<?php

namespace App\Services\Files;

use App\Constants\Common;
use Illuminate\Support\Facades\Log;

class UploadHistory
{

    private string $uploadDir;

    public function __construct(string $type, int $companyId)
    {
        $this->uploadDir = Common::UPLOAD_DIR . "/{$type}/{$companyId}";
    }

    /**
     * アップロード履歴から最新の10ファイルを取得する。
     * @return array
     */
    public function getUploadHistory(): array
    {
        // ディレクトリが存在しない場合
        if (!file_exists($this->uploadDir)) {
            return [];
        }

        // `$uploadDir` ディレクトリ内の全ファイル/ディレクトリのリストを取得('.'と'..'を除く)
        $historyFiles = array_diff(scandir($this->uploadDir), ['.',
                                                               '..']);

        // ファイル名と更新日を持つ配列を作成
        $filesWithModificationDates = [];
        foreach ($historyFiles as $file) {
            $filePath                     = $this->uploadDir . '/' . $file;
            $modificationTimestamp        = filemtime($filePath);
            $modificationDateTime         = date('Y/m/d H:i', $modificationTimestamp);
            $filesWithModificationDates[] = [
                'name'  => $file,
                'path'  => $filePath,
                'updated_at' => $modificationDateTime,
            ];
        }

        // 更新日をもとにソート
        usort($filesWithModificationDates, function($a, $b) {
            $timestampA = strtotime($a['updated_at']);
            $timestampB = strtotime($b['updated_at']);

            if ($timestampA === false || $timestampB === false) {
                return 0; // 比較できない場合は順序を変更しない（同じ順序とする）
            }

            return $timestampB - $timestampA;
        });

        // 最新の10ファイル/ディレクトリを取得
        return array_slice($filesWithModificationDates, 0, 10);
    }

}
