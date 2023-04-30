<?php

namespace app\Services\Usen\Order\Wrappers\Product;

class ProductOption
{
    private ?string $value;

    public function __construct(?string $value)
    {
        $this->value = $value;
    }
}
