<?php

namespace app\Services\Usen\Order\Wrappers;

use app\Services\Usen\Order\Wrappers\Product\Category1;
use app\Services\Usen\Order\Wrappers\Product\Category2;
use app\Services\Usen\Order\Wrappers\Product\Category3;
use app\Services\Usen\Order\Wrappers\Product\Category4;
use app\Services\Usen\Order\Wrappers\Product\Category5;
use app\Services\Usen\Order\Wrappers\Product\ProductCd;
use app\Services\Usen\Order\Wrappers\Product\ProductName;
use app\Services\Usen\Order\Wrappers\Product\ProductOption;

class Product
{
    private ProductCd     $productCd;
    private ProductName   $productName;
    private Category1     $Category1;
    private Category2     $Category2;
    private Category3     $Category3;
    private Category4     $Category4;
    private Category5     $Category5;
    private ProductOption $productOption;

    public function __construct(array $row)
    {
        [$productCd, $productName] = $this->splitProductCodeAndName($row['product']);

        $this->productCd     = new ProductCd($productCd);
        $this->productName   = new ProductName($productName);
        $this->Category1     = new Category1($row['productCategory1']);
        $this->Category2     = new Category2($row['productCategory2']);
        $this->Category3     = new Category3($row['productCategory3']);
        $this->Category4     = new Category4($row['productCategory4']);
        $this->Category5     = new Category5($row['productCategory5']);
        $this->productOption = new ProductOption($row['product_option']);
    }

    private function splitProductCodeAndName(string $productCodeAndName): array
    {
        // ProductCd:ProductNameとなっているため分離
        $split = explode(':', $productCodeAndName);
        $productCd = $split[0];
        $productName = $split[1];

        return [$productCd, $productName];
    }
}


