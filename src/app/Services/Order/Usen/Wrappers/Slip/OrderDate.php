<?php

namespace App\Services\Order\Usen\Wrappers\Slip;

use App\Services\Base\CsvWrappers\ColumnBase;

/**
 * OrderDate(伝票発行日時)
 * example inputValue:「2022/12/25  19:12:00」
 * example value :「2022/12/25  19:12:00」
 */
class OrderDate extends ColumnBase
{
    protected string $permittedValueType = 'timestamp';
}
