<?php

namespace App\Services\Usen\Order\Wrappers\Payment;

use App\Exceptions\SkipImportException;
use App\Services\Base\CsvWrappers\ColumnBase;
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
     * 許可された型
     * 取り込み型に変更がある場合は継承先で変更する
     * integer:整数 string:文字列 boolean:真偽 double:浮動小数点数 date:日付型 timestamp:タイムスタンプ
     * @var string
     */
    protected string $permittedValueType = 'integer';

    /**
     * 支払金額
     * @var int|null
     */
    private ?int $giftCertAmount;

    /**
     * 支払金額差額
     * @var int|null
     */
    private ?int $unusedAmount;

    /**
     * @param int|null $giftCertAmount
     * @param int|null $unusedAmount
     * @throws SkipImportException|Exception
     */
    public function __construct(?int $giftCertAmount, ?int $unusedAmount)
    {
        $this->giftCertAmount = $giftCertAmount !== null ? $giftCertAmount : 0;
        $this->unusedAmount   = $unusedAmount !== null ? $unusedAmount : 0;
        $value                = $this->getUsedAmount();
        Parent::__construct($value, '商品券釣無');
    }

    /**
     * 使用金額を取得
     * @return int|null
     */
    public function getUsedAmount(): int|null
    {
        return ($this->giftCertAmount - $this->unusedAmount);
    }
}

