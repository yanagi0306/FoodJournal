<?php

namespace app\Services\Usen\Order\Wrappers;

use App\Exceptions\SkipImportException;
use App\Traits\LogTrait;

class BaseWrapper
{

    /**
     * 継承先で取り込み時の型定義をすること
     * @var ?int
     */
    private ?int $value;

    /**
     * 入力された値
     * @var mixed
     */
    private mixed $inputValue;

    /**
     * 禁止文字を設定
     * ※必要があれば' 'を追加する
     * 削除する場合は$isRemoveForbiddenChars = trueに設定
     * @var array 配列で禁止文字を指定
     */
    private array $forbiddenChars = [
        "\n", "\r", "\t", "\0", "\x1F", '@', '#', '%', '!', '^', '&', '*',
    ];

    /**
     * @var bool 禁止文字の削除を許可
     */
    private bool $isRemoveForbiddenChars = false;

    /**
     * コードと名前の分離を許可
     * @var mixed|string|null
     */
    private bool $isExtractCodeAndName = false;

    /**
     * 許可された値がある場合継承先で定義する
     * 配列に定義されいる場合は値がチェックされる
     * 許可された値
     */
    private array $permittedValues = [];

    /**
     * 無効な値がある場合継承先で定義する
     * 配列に定義されいる場合は値がチェックされる
     * 無効な値
     */
    private array $invalidValues = [];

    /**
     * @param string|null $value
     * @throws SkipImportException
     */
    public function __construct(mixed $value)
    {
        $this->inputValue = $value;

        // 許可されている場合はコードと名前を分離
        $this->extractCodeAndName();

        // 許可されている場合は禁止文字を削除
        $this->removeForbiddenChars();

        // 定義されている場合は許可された値を検証
        $this->validatePermittedValue();

        // 定義されている場合は許可されていない値か検証
        $this->validateInvalidValues();

    }

    /**
     * コードと名前の分離してコードを定義
     * ステータスコード:ステータス名となっている為
     * @return void
     */
    private function extractCodeAndName(): void
    {
        if ($this->isExtractCodeAndName === true) {
            $split = explode(':', $this->inputValue);
            $this->value = intval($split[0]);
        }
    }

    /**
     * 禁止文字を削除
     * @return void
     */
    private function removeForbiddenChars(): void
    {
        if ($this->isRemoveForbiddenChars === true) {
            $originalValue = $this->value;
            $this->value = str_replace($this->forbiddenChars, '', $this->value);
            self::setLog();
        }
    }

    /**
     * 許可された値以外が含まれているか検証
     * $this->permittedValuesに値が定義されている場合のみ
     * @throws SkipImportException
     */
    private function validatePermittedValue(): void
    {
        if ($this->permittedValues && in_array($this->value, $this->permittedValues, true) === true) {
            throw new SkipImportException("許可された値以外が含まれています value={$this->value}");
        }
    }

    /**
     * 許可されていない値が含まれているか検証
     * $this->invalidValuesに値が定義されている場合のみ
     * @throws SkipImportException
     */
    private function validateInvalidValues(): void
    {
        if ($this->invalidValues && in_array($this->value, $this->invalidValues, true) === false) {
            throw new SkipImportException("許可されていない値が含まれています value={$this->value}");
        }
    }


}
