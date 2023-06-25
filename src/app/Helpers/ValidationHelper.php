<?php

namespace App\Helpers;

use DateTime;

/**
 * ValidationHelper クラス
 * 値の検証を行うメソッド
 */
class ValidationHelper
{
    /**
     * 許可された値以外が含まれているか検証
     * @param mixed $value           検証する値
     * @param array $permittedValues 許可された値の配列
     * @return bool
     */
    public static function validatePermittedValue(mixed $value, array $permittedValues): bool
    {
        return in_array($value, $permittedValues, true);
    }

    /**
     * 許可されていない値が含まれているか検証
     * @param mixed $value         検証する値
     * @param array $invalidValues 許可されていない値の配列
     * @return bool
     */
    public static function validateInvalidValues(mixed $value, array $invalidValues): bool
    {
        return !in_array($value, $invalidValues, true);
    }

    /**
     * 値が正の値か検証する
     * @param mixed  $value              検証する値
     * @param string $permittedValueType 許可された値の型
     * @return bool
     */
    public static function validatePositiveValue(mixed $value, string $permittedValueType): bool
    {
        if ($permittedValueType !== 'integer' && $permittedValueType !== 'double') {
            return true;
        }

        return $value >= 0;
    }

    /**
     * 値の型が指定された型と一致するか検証
     * @param mixed  $value              検証する値
     * @param string $permittedValueType 許可された値の型名
     * @return bool
     */
    public static function validateValueType(mixed $value, string $permittedValueType): bool
    {
        $inputValueType = gettype($value);

        if ($inputValueType === 'NULL') {
            return true;
        }

        if (($permittedValueType === 'timestamp' || $permittedValueType === 'date') && $value instanceof DateTime) {
            $inputValueType = $permittedValueType;
        }

        if ($permittedValueType === 'date' && $inputValueType === 'string') {
            $date = DateTime::createFromFormat('Y-m-d', $value);
            if ($date && $date->format('Y-m-d') === $value) {
                $inputValueType = 'date';
            }
        }

        return $inputValueType === $permittedValueType;
    }
}

