<?php

namespace app\Services\Usen\Order\Wrappers\SkipDecision;

use App\Exceptions\SkipImportException;

class OrderStatus
{
    private ?string $value;

    /**
     * 以下の場合はスキップ
     * 12:合算会計済, 30:赤伝票, 50:支払取消
     */
    private const SKIP_VALUES = ['12', '30', '50'];

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
        if (in_array($this->value, self::SKIP_VALUES, true)) {
            throw new SkipImportException('スキップ対象の値が含まれています');
        }
    }
}
