<?php

namespace App\Services\Usen\Order\Wrappers;

use App\Exceptions\SkipImportException;
use App\Services\Base\CsvWrappers\ColumnGroupBase;
use App\Services\Usen\Order\Wrappers\Product\Category1;
use App\Services\Usen\Order\Wrappers\Product\Category2;
use App\Services\Usen\Order\Wrappers\Product\Category3;
use App\Services\Usen\Order\Wrappers\Product\Category4;
use App\Services\Usen\Order\Wrappers\Product\Category5;
use App\Services\Usen\Order\Wrappers\Product\ProductCd;
use App\Services\Usen\Order\Wrappers\Product\ProductName;
use App\Services\Usen\Order\Wrappers\Product\Quantity;
use App\Services\Usen\Order\Wrappers\Product\UnitCost;
use App\Services\Usen\Order\Wrappers\Product\UnitPrice;
use App\Services\Usen\Order\Wrappers\Slip\StoreCd;
use Exception;

/**
 * 注文商品マスタクラス
 */
class OrderProductMaster extends ColumnGroupBase
{
    protected StoreCd     $storeCd;
    protected ProductCd   $productCd;
    protected ProductName $productName;
    protected Category1   $category1;
    protected Category2   $category2;
    protected Category3   $category3;
    protected Category4   $category4;
    protected Category5   $category5;
    protected UnitCost    $unitCost;
    protected UnitPrice   $unitPrice;
    protected Quantity    $quantity;

    /**
     * @param array $row
     * @throws SkipImportException|Exception
     */
    public function __construct(array $row)
    {
        $this->productCd   = new ProductCd($row['product'], '商品コード');
        $this->productName = new ProductName($row['product'], '商品名');
        $this->unitCost    = new UnitCost($row['unitCost'], '理論原価');
        $this->unitPrice   = new UnitPrice($row['unitPrice'], '販売価格');
        $this->category1   = new Category1($row['category1'], 'カテゴリ1');
        $this->category2   = new Category2($row['category2'], 'カテゴリ2');
        $this->category3   = new Category3($row['category3'], 'カテゴリ3');
        $this->category4   = new Category4($row['category4'], 'カテゴリ4');
        $this->category5   = new Category5($row['category5'], 'カテゴリ5');
        $this->quantity    = new Quantity($row['quantity'], '数量');

    }
}


