<?php

namespace App\Helpers;

class UploadHistory
{

    /**
     * アップロード履歴から最新の5つのファイルを取得する。
     * @param $type
     * @param $userInfo
     * @return array
     */
    public static function getOrderHistory($type, $userInfo): array
    {
        $companyId = $userInfo['company_id'];
        $uploadDir = "/data/upload_BK/{$type}/{$companyId}";

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

        // 最新の5つのファイル/ディレクトリを取得
        return array_slice($filesWithCreationDates, 0, 5);
    }

}
