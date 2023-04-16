<?php

namespace App\Traits;

use JetBrains\PhpStorm\NoReturn;

trait CsvTrait
{
    /**
     * CSVデータを一時的に生成し、ダウンロードする処理
     *
     * @param string $fileName ダウンロードファイル名
     * @param array $outputData 出力するデータ
     */
    public function downloadOutputCsvFile(string $fileName, array $outputData): void
    {
        $this->setResponseHeaders($fileName);

        $fp = fopen('php://output', 'w');
        foreach ($outputData as $data) {
            fwrite($fp, $this->convertCsv($data));
        }
        fclose($fp);
    }

    /**
     * 作成済みのCSVデータをダウンロードする処理
     *
     * @param string $fileName ダウンロードファイル名
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
     * @param string $fileName
     */
    private function setResponseHeaders(string $fileName): void
    {
        foreach ($this->getResponseHeaderTemplate() as $key => $val) {
            $setVal = $val;
            if ($key === 'Content-Disposition') {
                $setVal = str_replace('%file_name%', $fileName, $setVal);
            }
            header("$key: $setVal");
        }
    }

    /**
     * @return string[]
     */
    private function getResponseHeaderTemplate(): array
    {
        return [
            'Content-type' => 'application/octet-stream; charset=Shift_JIS',
            'Content-Disposition' => 'attachment; filename=%file_name%',
            'X-Content-Type-Options' => 'nosniff',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
            'Connection' => 'close'
        ];
    }

    /**
     * 配列からcsvデータ用への変換
     * @param array $array
     * @param string $toEncoding
     * @param string $fromEncoding
     * @param string $lineSeparator
     * @return string
     */
    public function convertCsv(
        array  $array,
        string $toEncoding = 'SJIS',
        string $fromEncoding = 'UTF-8',
        string $lineSeparator = "\n"
    ): string
    {
        $escArray = [];
        foreach ($array as $value) {
            $value = str_replace('"', '""', $value);
            $value = '"' . $value . '"';
            $escArray[] = mb_convert_encoding($value, $toEncoding, $fromEncoding);
        }
        return implode(',', $escArray) . $lineSeparator;
    }

    /**
     * CSVデータから配列への変換
     * @param string $csvData
     * @param bool $hasHeader
     * @param string $toEncoding
     * @param string $fromEncoding
     * @return array
     */
    public function convertArray(
        string $csvData,
        bool   $hasHeader = true,
        string $toEncoding = 'UTF-8',
        string $fromEncoding = 'SJIS'
    ): array
    {
        $array = [];
        $csvData = mb_convert_encoding($csvData, $toEncoding, $fromEncoding);
        $stream = fopen('data://text/plain;base64,' . base64_encode($csvData), 'r');

        // ヘッダーがある場合は読み飛ばす
        if ($hasHeader) {
            fgetcsv($stream);
        }

        while (($row = fgetcsv($stream)) !== false) {
            $array[] = $row;
        }
        fclose($stream);
        return $array;
    }

}
