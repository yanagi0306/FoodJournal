<?php

namespace App\Services\Usen\Order\Wrappers\Slip;

use App\Services\Usen\Order\Wrappers\ColumnBase;

/**
 * StoreCd(店舗コード)
 * example inputValue:「001:ＢＥＮＣＩＡ」
 * example value :「001」
 */
class StoreCd extends ColumnBase
{
    protected string $permittedValueType = 'string';
    protected bool $isExtractLeft = true;
}
