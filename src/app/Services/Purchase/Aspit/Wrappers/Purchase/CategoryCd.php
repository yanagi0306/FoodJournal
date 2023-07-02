<?php

namespace App\Services\Purchase\Aspit\Wrappers\Purchase;

use App\Constants\AspitConstants;
use App\Services\Base\CsvWrappers\ColumnBase;

/**
 * CategoryCd(分類コード)
 * example inputValue:「2000」
 * example value :「2000」
 */
class CategoryCd extends ColumnBase
{
    protected string $permittedValueType = 'string';
    protected ?array $permittedValues    = [];
    protected ?array $invalidValues      = null;

    public function __construct($categoryCd,$valueName)
    {
        $this->permittedValues = array_column(AspitConstants::CATEGORY_MAPS_FROM_ASPIT_TO_DB, 'aspit_category_cd');
        Parent::__construct($categoryCd, $valueName);
    }
}
