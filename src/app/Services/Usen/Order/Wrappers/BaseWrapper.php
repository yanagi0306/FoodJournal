<?php

namespace app\Services\Usen\Order\Wrappers;

use App\Exceptions\SkipImportException;
use DateTime;
use DateTimeZone;
use Exception;
use Illuminate\Support\Facades\Log;

class BaseWrapper
{

    /**
     * 取り込まれる値
     * @var mixed
     */
    protected mixed $value;

    /**
     * 許可された型
     * 取り込み型に変更がある場合は継承先で変更する
     * integer:整数 string:文字列 boolean:真偽 float:浮動小数点数 date:日付型 timestamp:タイムスタンプ
     * @var string
     */
    protected string $permittedValueType = 'integer';

    /**
     * 入力された値
     * @var mixed
     */
    protected mixed $inputValue;

    /**
     * 禁止文字を設定
     * ※必要があれば' 'を追加する
     * 削除する場合は$isRemoveForbiddenChars = trueに設定
     * @var array 配列で禁止文字を指定
     */
    protected array $forbiddenChars = [
        "\n", "\r", "\t", "\0", "\x1F", '@', '#', '%', '!', '^', '&',
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
    protected array $invalidValues = [null, ''];

    /**
     * $this->valueの空白削除を許可
     * @var bool
     */
    protected bool $isTrimSpaces = true;

    /**
     * $this->valueが正の整数かの判定を許可
     * @var bool
     */
    protected bool $isCheckPositiveInteger = false;


    /**
     * @param string|null $value
     * @throws SkipImportException|Exception
     */
    public function __construct(mixed $value)
    {
        $this->inputValue = $value;

        // 許可されている場合「:」区切りで左側を分離
        $this->ExtractLeft();

        // 許可されている場合「:」区切りで右側を分離
        $this->extractRight();

        // 許可されている場合は禁止文字を削除
        $this->removeForbiddenChars();

        // 定義されている場合は許可された値を検証
        $this->validatePermittedValue();

        // 定義されている場合は許可されていない値か検証
        $this->validateInvalidValues();

        // 許可されている場合は空白を削除
        $this->trimSpaces();

        // 定義されている場合は型の変換
        $this->convertToType();

        // 定義されている場合は型のチェック
        $this->validateValueType();

        // 許可されている場合は正の値か検証
        $this->validatePositiveValue();

    }

    /**
     * 「:」区切りで左側を分離
     * ステータスコード:ステータス名となっている為
     * @return void
     * @throws Exception
     */
    private function extractLeft(): void
    {
        if ($this->isExtractLeft === false) {
            return;
        }

        $split = explode(':', $this->inputValue);

        if (count($split) !== 2) {
            throw new Exception("値の分離に失敗しました。 value={$this->inputValue}");
        }

        Log::info("値を分離 before:{$this->value} code:" . $split[0]);
        $this->value = $split[0];
    }

    /**
     * 「:」区切りで右側を分離
     * ステータスコード:ステータス名となっている為
     * @return void
     * @throws Exception
     */
    private function extractRight(): void
    {
        if ($this->isExtractRight === false) {
            return;
        }

        $split = explode(':', $this->inputValue);

        if (count($split) !== 2) {
            throw new Exception("値の分離に失敗しました。 value={$this->inputValue}");
        }

        Log::info("値を分離 before:{$this->value} code:" . $split[1]);
        $this->value = $split[1];
    }

    /**
     * 禁止文字を削除
     * @return void
     */
    private function removeForbiddenChars(): void
    {
        if ($this->isRemoveForbiddenChars === false) {
            return;
        }

        $originalValue = $this->value;
        $this->value = str_replace($this->forbiddenChars, '', $this->value);
        Log::info("禁止文字を削除 before:{$originalValue} after:{$this->value}");
    }

    /**
     * 空白を削除
     * @return void
     * @throws Exception
     */
    private function trimSpaces(): void
    {
        if ($this->isTrimSpaces === false || $this->permittedValueType === 'timestamp') {
            return;
        }

        $originalValue = $this->value;
        $this->value = str_replace(' ', '', $this->value);
        Log::info("空白を削除 before:{$originalValue} after:{$this->value}");
    }

    /**
     * 許可された値以外が含まれているか検証
     * $this->permittedValuesに値が定義されている場合のみ
     * @throws SkipImportException
     */
    private function validatePermittedValue(): void
    {
        if ($this->permittedValues === []) {
            return;
        }

        if (in_array($this->value, $this->permittedValues, true) === false) {
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
        if ($this->invalidValues === []) {
            return;
        }

        if (in_array($this->value, $this->invalidValues, true) === true) {
            throw new SkipImportException("許可されていない値が含まれています value={$this->value}");
        }
    }

    /**
     * $this->valueが負の値か検証する
     *
     * @return bool
     * @throws Exception
     */
    public function validatePositiveValue(): bool
    {
        if ($this->isCheckPositiveInteger === false) {
            return;
        }

        if ($this->permittedValueType = 'integer' || $this->permittedValueType === 'float') {
            throw new Exception("permittedValueTypeにint型,float型以外が定義されています。。permittedValueType:{$this->permittedValueType}");
        }

        if ($this->value < 0) {
            throw new Exception("負の値が検出されました。value:{$this->value}");
        }
    }

    /**
     * $this->valueを指定された型に変換する
     * @throws Exception
     */
    private function convertToType(): void
    {
        if (!isset($this->permittedValueType)) {
            throw new Exception("permittedValueTypeが定義されていません。permittedValueType:{$this->permittedValueType}");
        }

        // $this->valueが「」「null」の場合はnullに変換
        if (trim($this->value) === null) {
            $this->value = null;
            return;
        }

        switch ($this->permittedValueType) {
            case 'integer':
                $value = filter_var($this->value, FILTER_VALIDATE_INT);
                if ($value === false) {
                    throw new Exception("{$this->value}はint型に変換できません");
                }
                $this->value = $value;
                break;

            case 'string':
                $this->value = (string)$this->value;
                break;

            case 'boolean':
                $value = filter_var($this->value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
                if ($value === null) {
                    throw new Exception("{$this->value}はboolean型に変換できません");
                }
                $this->value = $value;
                break;

            case 'float':
                $value = filter_var($this->value, FILTER_VALIDATE_FLOAT);
                if ($value === false) {
                    throw new Exception("{$this->value}はfloat型に変換できません");
                }
                $this->value = $value;
                break;

            case 'date':
                $dateTime = DateTime::createFromFormat('Y-m-d', $this->value);
                if ($dateTime === false) {
                    throw new Exception("{$this->value}はdate型に変換できません");
                }
                $this->value = $dateTime->format('Y-m-d');
                break;

            case 'timestamp':
                $dateTime = DateTime::createFromFormat('Y/m/d H:i:s', $this->value);
                if ($dateTime === false) {
                    throw new Exception("{$this->value}はtimestamp型に変換できません");
                }
                // タイムゾーンを設定（必要に応じて変更）
                $dateTime->setTimezone(new DateTimeZone(env('APP_TIMEZONE', 'Asia/Tokyo')));

                // Postgresのtimestamp型と互換性のある文字列に変換
                $this->value = $dateTime->format('Y-m-d H:i:s');
                break;

            default:
                throw new Exception("未対応の型が指定されています: {$this->permittedValueType}");

        }
    }

    /**
     * 値の型が継承先で指定された型と一致するか検証
     * @throws Exception
     */
    private function validateValueType(): void
    {
        $inputValueType = gettype($this->value);

        if ($inputValueType === 'NULL') {
            return;
        }

        // タイムスタンプはDateTimeオブジェクト型に変換されているので別で検証
        if ($this->permittedValueType === 'timestamp' && $this->value instanceof DateTime) {
            $inputValueType = 'timestamp';
        }

        if ($inputValueType !== $this->permittedValueType) {
            throw new Exception('取り込まれた型に誤りがあります。inputValue:' . $this->value . " permittedValueType:{$this->permittedValueType}");
        }
    }

}
