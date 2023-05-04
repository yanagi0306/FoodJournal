<?php

namespace App\Services\Usen\Order\Wrappers;

use App\Exceptions\SkipImportException;
use App\Services\Usen\Order\Wrappers\Product\Category1;
use App\Services\Usen\Order\Wrappers\Product\Category2;
use App\Services\Usen\Order\Wrappers\Product\Category3;
use App\Services\Usen\Order\Wrappers\Product\Category4;
use App\Services\Usen\Order\Wrappers\Product\Category5;
use App\Services\Usen\Order\Wrappers\Product\ProductCd;
use App\Services\Usen\Order\Wrappers\Product\ProductName;
use App\Services\Usen\Order\Wrappers\Product\ProductOption;
use App\Services\Usen\Order\Wrappers\Product\Quantity;
use App\Services\Usen\Order\Wrappers\Product\UnitCost;
use App\Services\Usen\Order\Wrappers\Product\UnitPrice;
use Exception;


/**
 * 注文商品クラス
 */
class Product
{
    private ProductCd     $productCd;
    private ProductName   $productName;
    private Category1     $category1;
    private Category2     $category2;
    private Category3     $category3;
    private Category4     $category4;
    private Category5     $category5;
    private ProductOption $productOption;
    private Quantity      $quantity;
    private UnitCost      $unitCost;
    private UnitPrice     $unitPrice;

    /**
     * @param array $row
     * @throws SkipImportException|Exception
     */
    public function __construct(array $row)
    {
        $this->productCd     = new ProductCd($row['product'], '商品コード');
        $this->productName   = new ProductName($row['product'], '商品名');
        $this->quantity      = new Quantity($row['quantity'], '数量');
        $this->unitCost      = new UnitCost($row['unitCost'], '理論原価');
        $this->unitPrice     = new UnitPrice($row['unitPrice'], '販売価格');
        $this->category1     = new Category1($row['category1'], 'カテゴリ1');
        $this->category2     = new Category2($row['category2'], 'カテゴリ2');
        $this->category3     = new Category3($row['category3'], 'カテゴリ3');
        $this->category4     = new Category4($row['category4'], 'カテゴリ4');
        $this->category5     = new Category5($row['category5'], 'カテゴリ5');
        $this->productOption = new ProductOption($row['productOption'], '商品オプション');
    }
}


