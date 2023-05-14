<?php

namespace App\Traits;

use Exception;
use Illuminate\Http\UploadedFile;
use InvalidArgumentException;
use RuntimeException;

trait CsvTrait
{
    /**
     * 配列からCSVデータ用への変換処理
     * @param array  $array         CSVデータに変換する配列
     * @param string $toEncoding    出力するCSVデータのエンコーディング
     * @param string $fromEncoding  変換する配列のエンコーディング
     * @param string $lineSeparator 行区切り文字
     * @return string CSVデータ
     * @throws Exception
     */
    public function convertArrayToCsv(
        array  $array,
        string $toEncoding = 'SJIS',
        string $fromEncoding = 'UTF-8',
        string $lineSeparator = "\n",
    ): string
    {
        $escapedArray = array_map(function($value) use ($toEncoding, $fromEncoding) {
            $value = str_replace('"', '""', $value);
            $value = '"' . $value . '"';
            return mb_convert_encoding($value, $toEncoding, $fromEncoding);
        }, $array);
        return implode(',', $escapedArray) . $lineSeparator;
    }

    /**
     * CSVファイルを配列に変換する処理
     * @param UploadedFile $uploadedFile アップロードされたCSVファイル
     * @param int|null     $skipRows     スキップする行数
     * @param string       $delimiter    カンマ区切り以外の場合の区切り文字
     * @param string       $enclosure    エンクロージャ
     * @param string       $escapeChar   エスケープ文字
     * @return array CSVファイルの内容を格納した配列
     * @throws Exception
     */
    public function convertCsvToArray(
        UploadedFile $uploadedFile,
        ?int         $skipRows = null,
        string       $delimiter = ',',
        string       $enclosure = '"',
        string       $escapeChar = '\\',
    ): array
    {
        $path = $uploadedFile->getPathname();

        if (!file_exists($path)) {
            throw new Exception('CSVから配列への変換に失敗しました。ファイルが存在しません。');
        }

        $handle = fopen($path, 'r');
        if (!$handle) {
            throw new Exception('CSVから配列への変換に失敗しました。ファイルが開けません。');
        }

        $rows      = [];
        $skipCount = 0;
        while (($data = fgetcsv($handle, 0, $delimiter, $enclosure, $escapeChar)) !== false) {
            if (is_int($skipRows) && $skipCount < $skipRows) {
                $skipCount++;
                continue;
            }
            $rows[] = $data;
        }

        fclose($handle);
        return $rows;
    }


    /**
     * CSVデータを一時的に生成し、ダウンロードする処理
     * @param string $fileName   ダウンロードファイル名
     * @param array  $outputData 出力するデータ
     */
    public function downloadOutputCsvFile(string $fileName, array $outputData): void
    {
        $this->setResponseHeaders($fileName);

        $fp = fopen('php://output', 'w');
        foreach ($outputData as $data) {
            fwrite($fp, $this->convertArrayToCsv($data));
        }
        fclose($fp);
    }

    /**
     * 作成済みのCSVデータをダウンロードする処理
     * @param string $fileName       ダウンロードファイル名
     * @param string $serverFilePath サーバーにあるファイルパス
     */
    public function downloadCsvFile(string $fileName, string $serverFilePath): void
    {
        $this->setResponseHeaders($fileName);

        while (ob_get_level()) {
            ob_end_clean();
        }

        readfile($serverFilePath);
    }

    /**
     * CSVダウンロード時のレスポンスヘッダー設定処理
     * @param string $fileName ダウンロードファイル名
     */
    private function setResponseHeaders(string $fileName): void
    {
        foreach ($this->getResponseHeaderTemplate($fileName) as $key => $val) {
            header("$key: $val");
        }
    }

    /**
     * CSVダウンロード時のレスポンスヘッダーテンプレートを取得する処理
     * @return string[] レスポンスヘッダーテンプレート
     */
    private function getResponseHeaderTemplate(string $fileName): array
    {
        return [
            'Content-type'           => 'application/octet-stream; charset=Shift_JIS',
            'Content-Disposition'    => "attachment; filename=$fileName",
            'X-Content-Type-Options' => 'nosniff',
            'Pragma'                 => 'no-cache',
            'Cache-Control'          => 'must-revalidate, post-check=0, pre-check=0',
            'Expires'                => '0',
            'Connection'             => 'close',
        ];
    }
}


