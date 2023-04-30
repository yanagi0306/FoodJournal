<?php

namespace app\Services\Usen\Order\Wrappers\Slip;

use app\Services\Usen\Order\Wrappers\BaseWrapper;

/**
 * CustomerSegment(客層)
 * example inputValue:「客層:夫婦」
 * example value :「夫婦」
 */
class CustomerSegment extends BaseWrapper
{
    protected bool $isExtractRight = false;
    protected string $permittedValueType = 'string';
    protected array $invalidValues = [];
}
