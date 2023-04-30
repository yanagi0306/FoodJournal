<?php

namespace app\Services\Usen\Order\Wrappers\Payment;

use App\Exceptions\SkipImportException;

class GiftCertNoChange
{
    /**
     * 支払い金額
     * @var string|null
     */
    private ?string $giftCertAmount;

    /**
     * 支払い金額差額
     * @var string|null
     */
    private ?string $unusedAmount;

    private ?string $value;

    /**
     * @param string|null $giftCertAmount
     * @param string|null $unusedAmount
     * @throws SkipImportException
     */
    public function __construct(?string $giftCertAmount, ?string $unusedAmount)
    {
        $this->giftCertAmount = $giftCertAmount !== null ? $giftCertAmount : 0;
        $this->unusedAmount = $unusedAmount !== null ? $unusedAmount : 0;
        $this->value = $this->getUsedAmount();
    }

    /**
     * @return string|null
     * @throws SkipImportException 使用額が負の場合にスローされる例外。
     */
    public function getUsedAmount(): ?string
    {
        $usedAmount = (string)($this->giftCertAmount - $this->unusedAmount);
        if ($usedAmount == 0) {
            return null;

        } elseif ($usedAmount > 0) {
            return $usedAmount;

        } else {
            throw new SkipImportException('使用金額が負の整数');
        }
    }
}

