<?php

namespace App\Services\Usen\Order\Wrappers;

use App\Exceptions\SkipImportException;
use App\Helpers\ConvertHelper;
use App\Helpers\ValidationHelper;
use Exception;
use Illuminate\Support\Facades\Log;

class BaseWrapper
{

    /**
     * 取り込まれる値
     * @var mixed
     */
    public mixed $value = null;

    /**
     * 取り込まれた値の名称
     * @var string
     */
    protected string $valueName = '';

    /**
     * 例外発生時のメッセージ
     * @var string
     */
    protected string $errorMessage = '';

    /**
     * 例外発生時のメッセージ(内部処理)
     * @var string
     */
    protected string $internalErrorMessage = '';

    /**
     * 許可された型
     * 取り込み型に変更がある場合は継承先で変更する
     * integer:整数 string:文字列 boolean:真偽 double:浮動小数点数 date:日付型 timestamp:タイムスタンプ
     * @var string
     */
    protected string $permittedValueType = 'integer';

    /**
     * $this->valueの先頭末尾の空白削除を許可
     * @var bool
     */
    protected bool $isTrimSpaces = true;

    /**
     * 空からnullへの置換を許可
     * @var bool
     */
    protected bool $isReplaceEmptyWithNull = true;

    /**
     * 禁止文字を設定
     * ※必要があれば' 'を追加する
     * 削除する場合は$isRemoveForbiddenChars = trueに設定
     * @var array 配列で禁止文字を指定
     */
    protected array $forbiddenChars = [
        "\n",
        "\r",
        "\t",
        "\0",
        "\x1F",
        '@',
        '#',
        '%',
        '!',
        '^',
        '&',
    ];

    /**
     * @var bool 禁止文字の削除を許可
     */
    protected bool $isRemoveForbiddenChars = false;

    /**
     * 「:」区切りで左側の分離の許可
     * @var mixed|string|null
     */
    protected bool $isExtractLeft = false;

    /**
     * 「:」区切りで右側の分離の許可
     * @var mixed|string|null
     */
    protected bool $isExtractRight = false;

    /**
     * 許可された値がある場合継承先で定義する
     * 配列に定義されいる場合は値がチェックされる
     * 許可された値
     */
    protected array $permittedValues = [];

    /**
     * 無効な値がある場合継承先で定義する
     * 配列に定義されいる場合は値がチェックされる
     */
    protected array $invalidValues = [null];

    /**
     * $this->valueの負の整数を許可
     * @var bool
     */
    protected bool $isAllowNegativeInteger = false;

    /**
     * @para string|null $value
     * @para string $valueName
     * @throws SkipImportException|Exception
     */
    public function __construct(?string $value, string $valueName)
    {
        $this->value     = $value;
        $this->valueName = $valueName;

        // 値の変換処理
        $this->convertValues();

        // 値の検証処理
        $this->validateValues();

        // バリデーションパートでエラー発生時はスルー
        if ($this->errorMessage) {
            throw new SkipImportException($this->errorMessage);
        }

        if ($this->internalErrorMessage) {
            throw new Exception($this->internalErrorMessage);
        }

    }

    /**
     * 値の変換処理
     * @throws Exception
     */
    private function convertValues(): void
    {
        // 許可されている場合は先頭末尾の空白を削除
        if ($this->isTrimSpaces) {
            $this->value = ConvertHelper::trimSpaces($this->value);
        }

        // 許可されている場合は空をnullに置換
        if ($this->isReplaceEmptyWithNull) {
            $this->value = ConvertHelper::replaceEmptyWithNull($this->value);
        }

        // 許可されている場合「:」区切りで左側を分離
        if ($this->isExtractLeft) {
            $this->value = ConvertHelper::extractLeft($this->value, $this->valueName);
        }

        // 許可されている場合「:」区切りで右側を分離
        if ($this->isExtractRight) {
            $this->value = ConvertHelper::extractRight($this->value, $this->valueName);
        }

        // 許可されている場合は禁止文字を削除
        if ($this->isRemoveForbiddenChars) {
            $this->value = ConvertHelper::removeForbiddenChars($this->value, $this->valueName, $this->forbiddenChars);
        }

        // 定義されている場合は型の変換
        if ($this->permittedValueType) {
            $this->value = ConvertHelper::convertToType($this->value, $this->valueName, $this->permittedValueType);
        }
    }

    /**
     * 値の検証処理
     * @throws Exception
     */
    private function validateValues(): void
    {
        // 型のチェック
        if ($this->permittedValueType && ValidationHelper::validateValueType($this->value, $this->permittedValueType) === false) {
            $this->addErrorMessage("取り込まれた型に誤りがあります。permittedValueType:{$this->permittedValueType}");
        }

        // 許可された値を検証
        if ($this->permittedValues && ValidationHelper::validatePermittedValue($this->value, $this->permittedValues) === false) {
            $this->addErrorMessage("許可された値以外が含まれています value={$this->value}");
        }

        // 許可されていない値か検証
        if ($this->invalidValues && ValidationHelper::validateInvalidValues($this->value, $this->invalidValues) === false) {
            $this->addErrorMessage("許可されていない値が含まれています value={$this->value}]");
        }

        // 負の値か検証
        if (!$this->isAllowNegativeInteger && ValidationHelper::validatePositiveValue($this->value, $this->permittedValueType) === false) {
            $this->addErrorMessage("負の値が検出されました。value:{$this->value}");
        }
    }

    /**
     * エラーメッセージの追加
     * @param string $message
     */
    private function addErrorMessage(string $message): void
    {
        $this->errorMessage .= "項目名[{$this->valueName}] 値:[{$this->value}] {$message}\n";
    }
}
