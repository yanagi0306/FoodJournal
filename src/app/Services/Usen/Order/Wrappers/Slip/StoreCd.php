<?php

namespace App\Services\Usen\Order\Wrappers\Slip;

use App\Exceptions\SkipImportException;
use App\Services\Base\CsvWrappers\ColumnBase;

/**
 * StoreCd(店舗コード)
 * example inputValue:「001:ＢＥＮＣＩＡ」
 * example value :「001」
 */
class StoreCd extends ColumnBase
{
    protected string $permittedValueType = 'string';
    protected ?bool  $isExtractLeft      = true;

    /**
     * @param       $storeCd
     * @param array $storeCds
     * @throws SkipImportException
     */
    public function __construct($storeCd, array $storeCds)
    {
        // 会社に紐づく店舗コード以外はスキップ
        $this->permittedValues = $storeCds;
        Parent::__construct($storeCd, '店舗コード');
    }
}
