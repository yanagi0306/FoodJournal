<?php

namespace app\Services\Order\Usen\Usen\Wrappers\SkipDecision;

use App\Services\Base\CsvWrappers\ColumnBase;

/**
 * AggregateFlag(集計フラグ)
 * 「*」の時のみ取り込み許可
 * 「空」「null」の時は取り込みスキップ
 */
class AggregateFlag extends ColumnBase
{
    protected string $permittedValueType = 'string';
    protected ?array $permittedValues    = ['*'];
    protected ?array $invalidValues      = null;
}
