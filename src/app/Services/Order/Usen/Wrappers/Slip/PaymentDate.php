<?php

namespace App\Services\Order\Usen\Wrappers\Slip;

use App\Services\Base\CsvWrappers\ColumnBase;

/**
 * PaymentDate(伝票処理日)
 * example inputValue:「2022/12/25  19:12:00」
 * example value :「2022/12/25  19:12:00」
 */
class PaymentDate extends ColumnBase
{
    protected string $permittedValueType = 'timestamp';
}
