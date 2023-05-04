<?php

namespace App\Services\Usen\Order\Wrappers\Product;

use App\Services\Usen\Order\Wrappers\BaseWrapper;

/**
 * Quantity(数量)
 * example inputValue:「00000001967:金の鯛茶漬け」
 * example value :「金の鯛茶漬け」
 */
class Quantity extends BaseWrapper
{
    protected array $invalidValues = [null, '', 0];
}
