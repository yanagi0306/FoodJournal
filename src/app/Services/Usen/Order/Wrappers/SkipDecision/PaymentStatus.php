<?php

namespace app\Services\Usen\Order\Wrappers\SkipDecision;

use app\Services\Usen\Order\Wrappers\BaseWrapper;

/**
 * PaymentStatus(支払いステータス)
 * 「12」「30」「50」の時は取り込みスキップ
 */
class PaymentStatus extends BaseWrapper
{
    protected bool $isExtractLeft = true;
    protected array $invalidValues = [12, 30, 50];
}
