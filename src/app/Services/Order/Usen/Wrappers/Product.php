<?php

namespace app\Services\Order\Usen\Usen\Wrappers;

use App\Exceptions\SkipImportException;
use App\Services\Base\CsvWrappers\ColumnGroupBase;
use app\Services\Order\Usen\Usen\Wrappers\Product\ProductCd;
use app\Services\Order\Usen\Usen\Wrappers\Product\Quantity;
use app\Services\Order\Usen\Usen\Wrappers\Product\UnitPrice;
use Exception;

/**
 * 注文商品クラス
 */
class Product extends ColumnGroupBase
{
    protected ProductCd $productCd;
    protected unitPrice $unitPrice;
    protected Quantity  $quantity;

    /**
     * @param array $row
     * @throws SkipImportException|Exception
     */
    public function __construct(array $row)
    {
        $this->productCd = new ProductCd($row['product'], '商品コード');
        $this->unitPrice = new unitPrice($row['unitPrice'], '販売価格');
        $this->quantity  = new Quantity($row['quantity'], '数量');
    }
}


