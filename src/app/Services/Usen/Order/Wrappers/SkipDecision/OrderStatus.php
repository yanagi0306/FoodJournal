<?php

namespace app\Services\Usen\Order\Wrappers\SkipDecision;

use app\Services\Usen\Order\Wrappers\BaseWrapper;

/**
 * OrderStatus(オーダーステータス)
 * 「*」の時のみ取り込み許可
 * 「空」「null」の時は取り込みスキップ
 */
class OrderStatus extends BaseWrapper
{
    protected bool $isExtractLeft = true;
    protected array $permittedValues = [20];
}
