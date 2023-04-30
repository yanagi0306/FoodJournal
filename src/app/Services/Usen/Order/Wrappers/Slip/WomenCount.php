<?php

namespace app\Services\Usen\Order\Wrappers\Slip;

class WomenCount
{
    private ?string $value;

    public function __construct(?string $value)
    {
        $this->value = $value;
    }
}
