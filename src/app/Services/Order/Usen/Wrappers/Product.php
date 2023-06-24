<?php

namespace App\Services\Order\Usen\Wrappers;

use App\Exceptions\SkipImportException;
use App\Services\Base\CsvWrappers\ColumnGroupBase;
use App\Services\Order\Usen\Wrappers\Product\ProductCd;
use App\Services\Order\Usen\Wrappers\Product\Quantity;
use Exception;

/**
 * 注文商品クラス
 */
class Product extends ColumnGroupBase
{
    protected ProductCd    $productCd;
    protected Quantity     $quantity;

    /**
     * @param array $row
     * @throws SkipImportException|Exception
     */
    public function __construct(array $row)
    {
        $this->productCd    = new ProductCd($row['product'], '商品コード');
        $this->quantity     = new Quantity($row['quantity'], '数量');
    }
}


