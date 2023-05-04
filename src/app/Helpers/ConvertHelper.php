<?php

namespace App\Helpers;

use DateTime;
use DateTimeZone;
use Exception;
use Illuminate\Support\Facades\Log;

/**
 * TransformHelper クラス
 * 文字列や配列の操作を補助するメソッド
 */
class ConvertHelper
{
    /**
     * 空白をnullに置換
     * @param string $value
     * @return string|null
     */
    public static function replaceEmptyWithNull(string $value): ?string
    {
        return $value === '' ? null : $value;
    }

    /**
     * 「:」区切りで左側を分離
     * ステータスコード:ステータス名となっている為
     * @param string $value
     * @param string $valueName
     * @return string
     * @throws Exception
     */
    public static function extractLeft(string $value, string $valueName): string
    {
        $split = explode(':', $value);

        if (count($split) !== 2) {
            throw new Exception("項目名[{$valueName}] 値:{$value} 「:」区切りでの値の分離に失敗しました");
        }

        if (!$split[0]) {
            throw new Exception("項目名[{$valueName}] 値:{$value} 「:」区切りでの分離後の値が空です");
        }

        return $split[0];
    }

    /**
     * 「:」区切りで右側を分離
     * ステータスコード:ステータス名となっている為
     * @param string $value
     * @param string $valueName
     * @return string
     * @throws Exception
     */
    public static function extractRight(string $value, string $valueName): string
    {
        $split = explode(':', $value);

        if (count($split) !== 2) {
            throw new Exception("項目名[{$valueName}] 値:{$value} 「:」区切りでの値の分離に失敗しました");
        }

        if (!$split[1]) {
            throw new Exception("項目名[{$valueName}] 値:{$value} 「:」区切りでの分離後の値が空です");
        }

        return $split[1];
    }

    /**
     * 禁止文字を削除
     * @param string $inputValue
     * @param string $valueName
     * @param array  $forbiddenChars
     * @return string
     */
    public static function removeForbiddenChars(string $inputValue, string $valueName, array $forbiddenChars): string
    {
        $value = str_replace($forbiddenChars, '', $inputValue, $count);
        if ($count > 0) {
            Log::info("項目名[{$valueName}] 値:{$value} 禁止文字を削除します 禁止文字:" . self::convertArrayToString($forbiddenChars) . " 削除後:{$value}");
        }

        return $value;
    }

    /**
     * 先頭末尾空白を削除
     * @param string $value
     * @return string
     */
    public static function trimSpaces(string $value): string
    {
        return trim($value);
    }

    /**
     * $valueを指定された型に変換する
     * @throws Exception
     */
    public static function convertToType($value, $valueName, $permittedValueType): float|DateTime|bool|int|string|null
    {
        if (!isset($permittedValueType)) {
            throw new Exception("項目名[{$valueName}] 値:{$value} 指定の型が定義されていません。型:{$permittedValueType}");
        }

        if ($value === null || $value === '') {
            return null;
        }

        if ($permittedValueType === 'integer') {
            $convertedValue = self::convertToInteger($value, $valueName);
        } elseif ($permittedValueType === 'string') {
            $convertedValue = self::convertToString($value);
        } elseif ($permittedValueType === 'boolean') {
            $convertedValue = self::convertToBoolean($value, $valueName);
        } elseif ($permittedValueType === 'double') {
            $convertedValue = self::convertToDouble($value, $valueName);
        } elseif ($permittedValueType === 'date') {
            $convertedValue = self::convertToDate($value, $valueName);
        } elseif ($permittedValueType === 'timestamp') {
            $convertedValue = self::convertToTimestamp($value, $valueName);
        } else {
            throw new Exception("未対応の型が指定されています 型:[{$permittedValueType}]");
        }

        return $convertedValue;
    }

    /**
     * 整数に変換
     * @param string|null $value 変換前の値
     * @return int|null 変換後の整数値
     * @throws Exception 変換に失敗した場合
     */
    public static function convertToInteger(?string $value, $valueName): ?int
    {
        if ($value === null) {
            return null;
        }

        $intValue = filter_var($value, FILTER_VALIDATE_INT);
        if ($intValue === false) {
            throw new Exception("項目名[{$valueName}] 値:[{$value}] int型への変換ができません");
        }

        return $intValue;
    }

    /**
     * 文字列に変換
     * @param string|null $value 変換前の値
     * @return string|null 変換後の文字列値
     */
    public static function convertToString(?string $value): ?string
    {
        return $value;
    }

    /**
     * 真偽値に変換
     * @param string|null $value 変換前の値
     * @return ?bool 変換後の真偽値
     * @throws Exception 変換に失敗した場合
     */
    public static function convertToBoolean(?string $value, string $valueName): ?bool
    {
        if ($value === null) {
            return null;
        }

        $boolValue = filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
        if ($boolValue === null) {
            throw new Exception("項目名[{$valueName}] 値:[{$value}] boolean型に変換できません");
        }
        return $boolValue;
    }

    /**
     * 浮動小数点数に変換
     * @param string|null $value 変換前の値
     * @return float|null 変換後の浮動小数点数
     * @throws Exception 変換に失敗した場合
     */
    public static function convertToDouble(?string $value, string $valueName): ?float
    {
        if ($value === null) {
            return null;
        }

        $doubleValue = filter_var($value, FILTER_VALIDATE_FLOAT);
        if ($doubleValue === false) {
            throw new Exception("項目名[{$valueName}] 値:[{$value}] double型に変換できません");
        }
        return $doubleValue;
    }

    /**
     * 日付に変換
     * @param string|null $value 変換前の値
     * @return DateTime|null 変換後の日付オブジェクト
     * @throws Exception 変換に失敗した場合
     */
    public static function convertToDate(?string $value, string $valueName): ?DateTime
    {
        if ($value === null) {
            return null;
        }

        $dateValue = DateTime::createFromFormat('Y-m-d', $value);
        if ($dateValue === false) {
            throw new Exception("項目名[{$valueName}] 値:[{$value}] date型に変換できません");
        }
        return $dateValue;
    }

    /**
     * タイムスタンプに変換
     * @param string|null $value 変換前の値
     * @return DateTime|null 変換後のタイムスタンプ
     * @throws Exception 変換に失敗した場合
     */
    public static function convertToTimestamp(?string $value, string $valueName): ?DateTime
    {
        if ($value === null) {
            return null;
        }

        // 秒の記載がない場合は追記(例:2022/12/25 19:08)
        if (strlen($value) === 16){
            $value .= ':00';
        }

        $timestampValue = DateTime::createFromFormat('Y/m/d H:i:s', $value);
        if ($timestampValue === false) {
            throw new Exception("項目名[{$valueName}] 値:[{$value}] timestamp型に変換できません");
        }

        $timestampValue->setTimezone(new DateTimeZone(env('APP_TIMEZONE', 'Asia/Tokyo')));
        return $timestampValue;
    }

    /**
     * 配列を文字列に変換して、前後に [ ] を追加
     * @param array $array 変換する配列
     * @return string 変換された文字列
     */
    private static function convertArrayToString(array $array): string
    {
        return '[' . implode('', $array) . ']';
    }
}
