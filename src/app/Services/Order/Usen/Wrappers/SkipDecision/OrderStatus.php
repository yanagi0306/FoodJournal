<?php

namespace App\Services\Order\Usen\Wrappers\SkipDecision;

use App\Services\Base\CsvWrappers\ColumnBase;

/**
 * OrderStatus(オーダーステータス)
 * 「*」の時のみ取り込み許可
 * 「空」「null」の時は取り込みスキップ
 */
class OrderStatus extends ColumnBase
{
    protected string $permittedValueType     = 'string';
    protected ?bool  $isExtractLeft          = true;
    protected ?array $permittedValues        = ['20'];
    protected ?array $invalidValues          = null;
}
