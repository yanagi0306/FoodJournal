<?php

namespace app\Services\Usen\Order\Wrappers;

use App\Exceptions\SkipImportException;
use Exception;
use app\Services\Usen\Order\Wrappers\Product\Category1;
use app\Services\Usen\Order\Wrappers\Product\Category2;
use app\Services\Usen\Order\Wrappers\Product\Category3;
use app\Services\Usen\Order\Wrappers\Product\Category4;
use app\Services\Usen\Order\Wrappers\Product\Category5;
use app\Services\Usen\Order\Wrappers\Product\ProductCd;
use app\Services\Usen\Order\Wrappers\Product\ProductName;
use app\Services\Usen\Order\Wrappers\Product\ProductOption;
use app\Services\Usen\Order\Wrappers\Product\Quantity;
use app\Services\Usen\Order\Wrappers\Product\UnitCost;
use app\Services\Usen\Order\Wrappers\Product\UnitPrice;


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
        $this->productCd     = new ProductCd($row['product']);
        $this->productName   = new ProductName($row['product']);
        $this->quantity      = new Quantity($row['product_']);
        $this->unitCost      = new UnitCost($row['unitCost']);
        $this->unitPrice     = new UnitPrice($row['unitPrice']);
        $this->category1     = new Category1($row['category1']);
        $this->category2     = new Category2($row['category2']);
        $this->category3     = new Category3($row['category3']);
        $this->category4     = new Category4($row['category4']);
        $this->category5     = new Category5($row['category5']);
        $this->productOption = new ProductOption($row['productOption']);
    }
}


