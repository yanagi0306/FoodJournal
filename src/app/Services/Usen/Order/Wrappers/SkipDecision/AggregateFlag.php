<?php

namespace app\Services\Usen\Order\Wrappers\SkipDecision;

use app\Services\Usen\Order\Wrappers\BaseWrapper;

/**
 * AggregateFlag(集計フラグ)
 * 「*」の時のみ取り込み許可
 * 「空」「null」の時は取り込みスキップ
 */
class AggregateFlag extends BaseWrapper
{
    protected string $permittedValueType = 'string';
    protected array $permittedValues = ['*'];
    protected array $invalidValues = ['', null];
}
