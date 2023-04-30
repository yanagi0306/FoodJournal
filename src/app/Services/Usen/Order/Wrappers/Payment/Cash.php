<?php

namespace app\Services\Usen\Order\Wrappers\Payment;

use App\Exceptions\SkipImportException;

class Cash
{
    private ?string $value;

    /**
     * @param string|null $value 値
     * @throws SkipImportException
     */
    public function __construct(?string $value)
    {
        $intValue = intval($value);

        if ($intValue == 0) {
            $this->value = null;

        } elseif ($intValue > 0) {
            $this->value = $intValue;

        } else {
            throw new SkipImportException('負の整数は許可されません');
        }
    }
}
