<?php

namespace App\Traits;

use Exception;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
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
     * アップロードされたCSVファイルを配列に変換し、指定されたエンコーディングに変換します
     * @param UploadedFile $uploadedFile アップロードされたCSVファイル
     * @param int|null     $skipRows     読み飛ばす行数
     * @param string       $fromEncoding 変換前のエンコーディング（デフォルトは'SHIFT-JIS'）
     * @param string       $toEncoding   変換後のエンコーディング（デフォルトは'UTF-8'）
     * @param string       $delimiter    区切り文字（デフォルトはカンマ）
     * @param string       $enclosure    フィールドを囲む文字（デフォルトは二重引用符）
     * @return array       CSVファイルの内容を格納した配列
     * @throws Exception   ファイルが存在しない、開けない、または無効なエンコーディングの場合に例外を投げます
     */
    public function convertCsvToArray(
        UploadedFile $uploadedFile,
        string       $fromEncoding = 'Shift_JIS',
        string       $toEncoding = 'UTF-8',
        ?int         $skipRows = null,
        string       $delimiter = ',',
        string       $enclosure = '"',
    ): array
    {
        $path = $uploadedFile->getPathname();
        $this->validateFilePath($path);

        $handle = fopen($path, 'r');
        if (!$handle) {
            throw new Exception('ファイルが開けません。');
        }

        $rows      = [];
        $skipCount = 0;
        while (($line = fgets($handle)) !== false) {
            $skipCount++;
            if (is_int($skipRows) && $skipCount <= $skipRows) {
                continue;
            }

            $line = $this->processLine($line, $enclosure);
            $data = str_getcsv($line, $delimiter);

            $rows[] = $this->convertEncoding($data, $fromEncoding, $toEncoding);
        }

        fclose($handle);
        return $rows;
    }

    /**
     * 指定されたパスが有効なファイルかどうかを検証します
     * @param string $path 検証するパス
     * @throws Exception パスが有効なファイルでない場合に例外を投げます
     */
    private function validateFilePath(string $path): void
    {
        if (!file_exists($path)) {
            throw new Exception('指定されたパスのファイルが存在しません。');
        }
    }

    /**
     * ダブルクォートで囲まれた部分内のカンマをスペースに変換し、ダブルクォートを削除します
     * @param string $line      変換する行
     * @param string $enclosure フィールドを囲む文字
     * @return string           変換後の行
     */
    private function processLine(string $line, string $enclosure): string
    {
        // ダブルクォートで囲まれた部分内のカンマをスペースに変換
        $line = preg_replace_callback(
            "/{$enclosure}([^{$enclosure}]*){$enclosure}/",
            function($matches) {
                return str_replace(',', ' ', $matches[0]);
            },
            $line,
        );

        // ダブルクォートを削除
        return str_replace($enclosure, '', $line);
    }

    /**
     * 文字列のエンコーディングを変換します
     * @param array  $data         エンコーディングを変換するデータの配列
     * @param string $fromEncoding 変換前のエンコーディング
     * @param string $toEncoding   変換後のエンコーディング
     * @return array                エンコーディングを変換したデータの配列
     * @throws Exception            無効なエンコーディングの場合に例外を投げます
     */
    private function convertEncoding(array $data, string $fromEncoding, string $toEncoding): array
    {
        return array_map(function($item) use ($fromEncoding, $toEncoding) {
            if (!mb_check_encoding($item, $fromEncoding)) {
                throw new Exception('無効な文字コードが検出されました。変換前の文字コード:' . mb_detect_encoding($item));
            }
            $convertedItem = mb_convert_encoding($item, $toEncoding, $fromEncoding);
            if (!mb_check_encoding($convertedItem, $toEncoding)) {
                throw new Exception('無効な文字コードが検出されました。変換後の文字コード:' . mb_detect_encoding($convertedItem));
            }
            return $convertedItem;
        }, $data);
    }


//    /**
//     * CSVデータを一時的に生成し、ダウンロードする処理
//     * @param string $fileName   ダウンロードファイル名
//     * @param array  $outputData 出力するデータ
//     * @throws Exception
//     */
//    public function downloadOutputCsvFile(string $fileName, array $outputData): void
//    {
//        $this->setResponseHeaders($fileName);
//
//        $fp = fopen('php://output', 'w');
//        foreach ($outputData as $data) {
//            fwrite($fp, $this->convertArrayToCsv($data));
//        }
//        fclose($fp);
//    }

//    /**
//     * 作成済みのCSVデータをダウンロードする処理
//     * @param string $fileName       ダウンロードファイル名
//     * @param string $serverFilePath サーバーにあるファイルパス
//     */
//    public function downloadCsvFile(string $fileName, string $serverFilePath): void
//    {
//        $this->setResponseHeaders($fileName);
//
//        while (ob_get_level()) {
//            ob_end_clean();
//        }
//
//        readfile($serverFilePath);
//    }
//
//    /**
//     * CSVダウンロード時のレスポンスヘッダー設定処理
//     * @param string $fileName ダウンロードファイル名
//     */
//    private function setResponseHeaders(string $fileName): void
//    {
//        foreach ($this->getResponseHeaderTemplate($fileName) as $key => $val) {
//            header("$key: $val");
//        }
//    }
//
//    /**
//     * CSVダウンロード時のレスポンスヘッダーテンプレートを取得する処理
//     * @return string[] レスポンスヘッダーテンプレート
//     */
//    private function getResponseHeaderTemplate(string $fileName): array
//    {
//        return [
//            'Content-type'           => 'application/octet-stream; charset=Shift_JIS',
//            'Content-Disposition'    => "attachment; filename=$fileName",
//            'X-Content-Type-Options' => 'nosniff',
//            'Pragma'                 => 'no-cache',
//            'Cache-Control'          => 'must-revalidate, post-check=0, pre-check=0',
//            'Expires'                => '0',
//            'Connection'             => 'close',
//        ];
//    }
}

