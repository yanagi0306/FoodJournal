<?php

namespace app\Services\Order\Usen\Usen\Wrappers\SkipDecision;

use App\Services\Base\CsvWrappers\ColumnBase;

/**
 * PaymentStatus(支払いステータス)
 * 「12」「30」「50」の時は取り込みスキップ
 */
class PaymentStatus extends ColumnBase
{
    protected string $permittedValueType     = 'string';
    protected ?bool  $isExtractLeft          = true;
    protected ?array $invalidValues          = ['12', '30', '50'];
//    protected ?array $permittedValues    = ['10', '11'];

}
