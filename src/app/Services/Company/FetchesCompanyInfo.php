<?php

namespace App\Services\Company;

use App\Models\Company;
use App\Models\ParentExpenseCategory;
use App\Models\ParentIncomeCategory;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Str;

class FetchesCompanyInfo
{
    // クラス定数を追加
    const TABLE_COMPANY                 = 'company';
    const TABLE_STORE                   = 'stores';
    const TABLE_CUSTOMER_TYPE           = 'customerTypes';
    const TABLE_PAYMENT_METHOD          = 'paymentMethods';
    const TABLE_PURCHASE_SUPPLIER       = 'purchaseSuppliers';
    const TABLE_PARENT_INCOME_CATEGORY  = 'parentIncomeCategories';
    const TABLE_INCOME_CATEGORY         = 'incomeCategories';
    const TABLE_PARENT_EXPENSE_CATEGORY = 'parentExpenseCategories';
    const TABLE_EXPENSE_CATEGORY        = 'expenseCategories';

    private ?Company   $company;
    private Collection $stores;
    private Collection $customerTypes;
    private Collection $paymentMethods;
    private Collection $purchaseSuppliers;
    private Collection $incomeCategories;
    private Collection $parentIncomeCategories;
    private Collection $expenseCategories;
    private Collection $parentExpenseCategories;

    /**
     * 会社に関連テーブル情報一覧を取得する
     * @param int $companyId
     * @throws Exception
     */
    public function __construct(int $companyId)
    {
        $this->company = $this->findCompanyById($companyId);
    }

    /**
     * モデルを参照して会社テーブル情報を取得する
     * @param int $companyId
     * @return Company
     * @throws Exception
     */
    private function findCompanyById(int $companyId): Company
    {
        try {
            return Company::findOrFail($companyId);
        } catch (ModelNotFoundException $e) {
            $msg = 'システムエラー：会社情報の取得に失敗しました。';
            Log::error($msg . $e->getMessage() . $e->getFile() . $e->getLine());
            throw new Exception($msg);
        }
    }

    /**
     * モデルを参照して関連する店舗テーブル情報を定義する
     */
    public function setStoresWithCompany(): void
    {
        $this->stores = $this->company ? collect($this->company->stores->toArray()) : collect();
    }

    /**
     * モデルを参照して関連する顧客テーブル情報を定義する
     */
    public function setCustomersWithCompany(): void
    {
        $this->customerTypes = $this->company ? collect($this->company->customerTypes->toArray()) : collect();
    }

    /**
     * モデルを参照して関連する支払方法テーブル情報を定義する
     */
    public function setPaymentMethodsWithCompany(): void
    {
        $this->paymentMethods = $this->company ? collect($this->company->paymentMethods->toArray()) : collect();
    }

    /**
     * モデルを参照して関連する顧客テーブル情報を定義する
     */
    public function setPurchaseSuppliersWithCompany(): void
    {
        $this->purchaseSuppliers = $this->company ? collect($this->company->purchaseSuppliers->toArray()) : collect();
    }

    /**
     * モデルを参照して会社に紐づく収入カテゴリテーブル情報を定義する
     */
    public function setIncomeCategoriesWithCompany(): void
    {
        $this->incomeCategories = $this->company ? collect($this->company->incomeCategories->toArray()) : collect();
    }

    /**
     * モデルを参照して会社に紐づく親収入カテゴリテーブル情報を定義する(子収入カテゴリも配列で定義)
     */
    public function setParentIncomeCategoriesWithCompany(): void
    {
        if (!$this->company) {
            $this->parentIncomeCategories = collect();
            return;
        }

        $this->parentIncomeCategories = collect(ParentIncomeCategory::with('incomeCategories')->where('company_id', $this->company->id)->get()->toArray());
    }

    /**
     * モデルを参照して会社に紐づく支出カテゴリテーブル情報を定義
     */
    public function setExpenseCategoriesWithCompany(): void
    {
        $this->expenseCategories = $this->company ? collect($this->company->expenseCategories->toArray()) : collect();
    }

    /**
     * モデルを参照して会社に紐づく親支出カテゴリテーブル情報を定義(子支出カテゴリも配列で定義)
     */
    public function setParentExpenseCategoriesWithCompany(): void
    {
        if (!$this->company) {
            $this->parentExpenseCategories = collect();
            return;
        }
        $this->parentExpenseCategories = collect(ParentExpenseCategory::with('expenseCategories')->where('company_id', $this->company->id)->get()->toArray());

    }

    /**
     * Companyテーブルの指定したカラムの値を取得
     * @throws Exception
     */
    public function getCompanyValue(string $column): mixed
    {
        return $this->company->$column;
    }

    /**
     * 指定したテーブルの情報をを取得
     * @throws Exception
     */
    public function getTableInfo(string $table): mixed
    {
        $collection = $this->{$table};

        // テーブルの存在確認
        $this->validateTable($collection, $table);

        return $this->company->{$collection};
    }

    /**
     * 指定したテーブル、カラムの値を配列で取得
     * @throws Exception
     */
    public function getValueArrayFromColumn(string $table, string $column): array
    {
        $collection = $this->{$table};

        // テーブルとカラムの存在確認
        $this->validateTable($collection, $table, $column);

        return $collection->pluck($column)->toArray();
    }


    /**
     * 指定したテーブルとカラムから特定の値を持つレコードを取得
     * @param string $table  テーブル名
     * @param string $column カラム名
     * @param mixed  $value  カラム値
     * @return array
     * @throws Exception
     */
    public function getRecordFromColumnValue(string $table, string $column, mixed $value): array
    {
        $collection = $this->{$table};

        // テーブルとカラムの存在確認
        $this->validateTable($collection, $table, $column);

        // カラム値に一致するレコードを取得
        return $this->getRecordsMatchingColumnValue($collection, $column, $value);
    }

    /**
     * 親テーブルと子テーブルの特定の値を持つカラムに一致するレコードを取得
     * @param string $parentTable  親テーブルの名前
     * @param string $childTable   子テーブルの名前
     * @param string $parentColumn 親テーブルでマッチさせるカラムの名前
     * @param mixed  $parentValue  親テーブルのカラムでマッチさせる値
     * @param string $childColumn  子テーブルでマッチさせるカラムの名前
     * @param mixed  $childValue   子テーブルのカラムでマッチさせる値
     * @return array
     * @throws Exception
     */
    public function getChildRecordFromParentColumnValue(string $parentTable, string $childTable, string $parentColumn, mixed $parentValue, string $childColumn, mixed $childValue): array
    {
        // 親レコードの子レコードのkeyはスネークケースに変換されている
        $snakeChildTable = Str::snake($childTable);

        $collection = $this->{$parentTable};

        // 指定されたテーブル、カラムの存在確認
        $this->validateTable($collection, $parentTable, $parentColumn);
        $this->validateTable(collect($collection->first()[$snakeChildTable]), $childTable, $childColumn);

        // 指定されたレコードの取得
        $parentRecords = $this->getRecordsMatchingColumnValue($collection, $parentColumn, $parentValue);
        return $this->getRecordsMatchingColumnValue(collect($parentRecords[$snakeChildTable]), $childColumn, $childValue);
    }

    /**
     * コレクションに指定したテーブル,カラムが存在するかを検証
     * @param Collection  $collection 検証対象のコレクション
     * @param string      $table      検証対象のテーブル名
     * @param string|null $column     検証対象のカラム名(指定がある場合は検索)
     * @return void
     * @throws Exception
     */
    private function validateTable(Collection $collection, string $table, string $column = null): void
    {
        if (!property_exists($this, $table)) {
            throw new Exception("無効なテーブル名: {$table}");
        }

        if ($column === null) {
            return;
        }

        if (!$collection->first() || !array_key_exists($column, $collection->first())) {
            throw new Exception("無効なカラム名: {$column}");
        }
    }

    /**
     * コレクション内で特定の値を持つカラムを持つレコードを取得
     * @param Collection $collection 検索対象のコレクション
     * @param string     $column     値を比較対象のカラム名
     * @param mixed      $value      比較対象の値
     * @return array
     * @throws Exception
     */
    private function getRecordsMatchingColumnValue(Collection $collection, string $column, mixed $value): array
    {
        $records = $collection->filter(function($item) use ($column, $value) {
            return isset($item[$column]) && $item[$column] == $value;
        });

        if ($records->isEmpty()) {
            throw new Exception("カラム {$column} で値 {$value} を見つけられませんでした\n" . __FILE__ . __LINE__);
        }

        if ($records->count() > 1) {
            throw new Exception("カラム {$column} で値 {$value} を持つレコードが複数見つかりました\n" . __FILE__ . __LINE__);
        }

        return $records->first();
    }
}
