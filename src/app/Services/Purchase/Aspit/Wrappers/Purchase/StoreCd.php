<?php

namespace App\Services\Purchase\Aspit\Wrappers\Purchase;

use App\Exceptions\SkipImportException;
use App\Services\Base\CsvWrappers\ColumnBase;

/**
 * StoreCd(店舗コード)
 * example inputValue:「1001」
 * example value :「1001」
 */
class StoreCd extends ColumnBase
{
    protected string $permittedValueType = 'string';

    /**
     * @param       $storeCd
     * @param array $storeCds
     * @param       $valueName
     * @throws SkipImportException
     */
    public function __construct($storeCd, array $storeCds, $valueName)
    {
        // 会社に紐づく店舗以外はスキップ
        $this->permittedValues = $storeCds;
        Parent::__construct($storeCd, $valueName);
    }
}
