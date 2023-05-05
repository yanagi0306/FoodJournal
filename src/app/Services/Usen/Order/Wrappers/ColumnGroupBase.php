<?php

namespace App\Services\Usen\Order\Wrappers;

/**
 * 各カラムの一部をまとめたクラスの継承元クラス
 */
abstract class ColumnGroupBase
{

    /**
     * 値の取得
     * @return mixed value
     */
    public function getValues(): array
    {
        $values     = [];
        $properties = get_object_vars($this);

        foreach ($properties as $property => $value) {
            if ($value instanceof ColumnBase) {
                $values[$property] = $value->getValue();
            }
        }

        return $values;
    }
}

