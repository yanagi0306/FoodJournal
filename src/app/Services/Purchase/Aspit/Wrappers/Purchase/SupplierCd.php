<?php

namespace App\Services\Purchase\Aspit\Wrappers\Purchase;

use App\Exceptions\SkipImportException;
use App\Services\Base\CsvWrappers\ColumnBase;

/**
 * SupplierCd(仕入業者コード)
 * example inputValue:「1001」
 * example value :「1001」
 */
class SupplierCd extends ColumnBase
{
    protected string $permittedValueType = 'string';

    /**
     * @param       $supplierCd
     * @param array $supplierCds
     * @param       $valueName
     * @throws SkipImportException
     */
    public function __construct($supplierCd, array $supplierCds, $valueName)
    {
        // 会社に紐づく仕入業者以外はスキップ
        $this->permittedValues = $supplierCds;
        Parent::__construct($supplierCd, $valueName);
    }
}
