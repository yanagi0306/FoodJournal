<?php

namespace app\Services\Usen\Order\Wrappers\Product;

class Quantity
{
    private ?string $value;

    public function __construct(?string $value)
    {
        $this->value = $value;
    }
}
