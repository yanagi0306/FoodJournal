<?php

namespace App\Services\Purchase\Aspit\Wrappers\Purchase;

use App\Services\Base\CsvWrappers\ColumnBase;

/**
 * AcquisitionCd(科目コード)
 * 「1」の時のみ取り込み許可(科目名:仕入)
 */
class AcquisitionCd extends ColumnBase
{
    protected string $permittedValueType = 'string';
    protected ?array $permittedValues    = ['1'];
    protected ?array $invalidValues      = null;
}
