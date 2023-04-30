<?php

namespace app\Services\Usen\Order\Wrappers\Payment;

class Other
{
    private ?string $value;

    public function __construct(?string $value)
    {
        $this->value = $value;
    }
}
