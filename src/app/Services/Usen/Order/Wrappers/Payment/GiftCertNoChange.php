<?php

namespace App\Services\Usen\Order\Wrappers\Payment;

use App\Exceptions\SkipImportException;
use App\Services\Usen\Order\Wrappers\ColumnBase;
use Exception;

/**
 * GiftCertNoChange(商品券釣無)
 * example giftCertAmount:支払額:「1000」
 * example unusedAmount:支払金額差額:「100」
 * example inputValue:「900」
 * example value :「900」
 */
class GiftCertNoChange extends ColumnBase
{
    /**
     * 支払金額
     * @var string|null
     */
    private ?string $giftCertAmount;

    /**
     * 支払金額差額
     * @var string|null
     */
    private ?string $unusedAmount;

    /**
     * @param string|null $giftCertAmount
     * @param string|null $unusedAmount
     * @throws SkipImportException|Exception
     */
    public function __construct(?string $giftCertAmount, ?string $unusedAmount)
    {
        $this->giftCertAmount = $giftCertAmount !== null ? $giftCertAmount : 0;
        $this->unusedAmount = $unusedAmount !== null ? $unusedAmount : 0;
        $value = $this->getUsedAmount();
        Parent::__construct($value, '商品券釣無');
    }

    /**
     * 使用金額を取得
     * @return string|int|null
     */
    public function getUsedAmount(): string|int|null
    {
        return ($this->giftCertAmount - $this->unusedAmount);
    }
}

