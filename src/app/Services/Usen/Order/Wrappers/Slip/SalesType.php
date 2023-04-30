<?php

namespace app\Services\Aspit\Order\Wrappers\Slip;

class SalesType
{
    private ?string $value;

    public function __construct(?string $value)
    {
        $this->value = $value;
    }
}
