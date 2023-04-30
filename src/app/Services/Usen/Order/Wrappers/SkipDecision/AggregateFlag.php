<?php

namespace app\Services\Usen\Order\Wrappers\SkipDecision;

use App\Exceptions\SkipImportException;
use app\Services\Usen\Order\Wrappers\BaseWrapper;

class AggregateFlag extends BaseWrapper
{
    private ?string $value;

    /**
     * @param ?string $value
     * @throws SkipImportException
     */
    public function __construct(?string $value)
    {
        Parent::__construct($value);
        $this->invalidValues = [null, ''];
    }

}
