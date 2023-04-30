<?php

namespace app\Services\Usen\Order\Wrappers\Slip;

use app\Services\Usen\Order\Wrappers\BaseWrapper;

/**
 * StoreCd(店舗コード)
 * example inputValue:「001:ＢＥＮＣＩＡ」
 * example value :「001」
 */
class StoreCd extends BaseWrapper
{
    protected string $permittedValueType = 'string';
    protected bool $isExtractLeft = true;
}
