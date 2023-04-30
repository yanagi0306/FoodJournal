<?php

namespace app\Services\Usen\Order\Wrappers\SkipDecision;

use App\Exceptions\SkipImportException;

class PaymentStatus
{
    private ?string $value;

    /**
     * 20:配膳済み以外はスキップ
     */
    private const ALLOWED_VALUES = ['20'];

    /**
     * @param string|null $value
     * @throws SkipImportException
     */
    public function __construct(?string $value)
    {
        $this->value = $this->getStatusCd($value);
        $this->validateValue();
    }

    /**
     * ステータスコードの取得
     * ステータスコード:ステータス名となっているためステータスコードのみ抽出
     * @param string $otherStatus
     * @return string
     */
    private function getStatusCd(string $otherStatus): string
    {
        $split = explode(':', $otherStatus);
        return $split[0];
    }

    /**
     * @throws SkipImportException
     */
    private function validateValue(): void
    {
        if (in_array($this->value, self::ALLOWED_VALUES)) {
            throw new SkipImportException('スキップ対象の値が含まれています');
        }
    }
}
