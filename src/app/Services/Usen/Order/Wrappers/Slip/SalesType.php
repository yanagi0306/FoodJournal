<?php

namespace App\Services\Usen\Order\Wrappers\Slip;

use App\Services\Usen\Order\Wrappers\BaseWrapper;

/**
 * SalesType(販売形態)
 * example inputValue:「01:店内」
 * example value :「01」
 */
class SalesType extends BaseWrapper
{
    protected string $permittedValueType = 'string';
    protected bool $isExtractLeft = true;
    protected array $invalidValues = [];
}
