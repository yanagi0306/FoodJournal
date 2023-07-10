<?php

namespace app\Services\Company;

use App\Models\Company;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class FetchesCompanyInfo
{
    // クラス定数を追加
    const TABLE_COMPANY           = 'company';
    const TABLE_STORE             = 'stores';
    const TABLE_CUSTOMER_TYPE     = 'customerTypes';
    const TABLE_PAYMENT_METHOD    = 'paymentMethods';
    const TABLE_PURCHASE_SUPPLIER = 'purchaseSuppliers';
    const TABLE_INCOME_CATEGORY   = 'incomeCategories';
    const TABLE_EXPENSE_CATEGORY  = 'expenseCategories';

    private ?Company   $company;
    private Collection $stores;
    private Collection $customerTypes;
    private Collection $paymentMethods;
    private Collection $purchaseSuppliers;
    private Collection $incomeCategories;
    private Collection $expenseCategories;

    /**
     * 会社に関連テーブル情報一覧を取得する
     * @param int $companyId
     * @throws Exception
     */
    public function __construct(int $companyId)
    {
        $this->company           = $this->findCompanyById($companyId);
        $this->stores            = $this->findStoresWithCompany();
        $this->customerTypes     = $this->findCustomersWithCompany();
        $this->paymentMethods    = $this->findPaymentMethodsWithCompany();
        $this->purchaseSuppliers = $this->findPurchaseSuppliersWithCompany();
        $this->incomeCategories  = $this->findIncomeCategoriesWithCompany();
        $this->expenseCategories = $this->findExpenseCategoriesWithCompany();
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
     * モデルを参照して関連する店舗テーブル情報を取得する
     * @return Collection
     */
    private function findStoresWithCompany(): Collection
    {
        return $this->company ? collect($this->company->stores->array()) : collect();
    }

    /**
     * モデルを参照して関連する顧客テーブル情報を取得する
     * @return Collection
     */
    private function findCustomersWithCompany(): Collection
    {
        return $this->company ? collect($this->company->customerTypes->array()) : collect();
    }

    /**
     * モデルを参照して関連する支払方法テーブル情報を取得する
     * @return collection
     */
    private function findPaymentMethodsWithCompany(): Collection
    {
        return $this->company ? collect($this->company->paymentMethods->toArray()) : collect();
    }

    /**
     * モデルを参照して関連する顧客テーブル情報を取得する
     * @return Collection
     */
    private function findPurchaseSuppliersWithCompany(): Collection
    {
        return $this->company ? collect($this->company->purchaseSuppliers->toArray()) : collect();
    }

/**
 * モデルを参照して関連する収入カテゴリテーブル情報を取得する
 * @return Collection
 */
private function findIncomeCategoriesWithCompany(): Collection
{
    if (!$this->company) {
        return collect();
    }

    $incomeCategories = $this->company->incomeCategories->load('parentIncomeCategory');

    return $incomeCategories->map(function ($incomeCategory) {
        $parentIncomeCategory = $incomeCategory->parentIncomeCategory;
        // ここで親カテゴリーの情報を子カテゴリーの情報と同じレベルに持ってくる
        return [
            'id' => $incomeCategory->id,
            'cat_cd' => $incomeCategory->cat_cd,
            'cat_name' => $incomeCategory->cat_name,
            'type_cd' => $incomeCategory->type_cd,
            'parent_id' => $parentIncomeCategory->id,
            'parent_name' => $parentIncomeCategory->name,
            'parent_name' => $parentIncomeCategory->name,
            'parent_name' => $parentIncomeCategory->name,
        ];
    });
}

    /**
     * モデルを参照して関連する支出カテゴリテーブル情報を取得する
     * @return Collection
     */
    private function findExpenseCategoriesWithCompany(): Collection
    {
        return $this->company ? collect($this->company->expenseCategories->load('parentExpenseCategory')->toArray()) : collect();
    }

    /**
     * Companyテーブルの指定したカラムの値を取得する
     * @throws Exception
     */
    public function getCompanyValue(string $column): mixed
    {
        return $this->company->$column;
    }

    /**
     * 指定したテーブル、カラムの値を配列で取得する
     * @throws Exception
     */
    public function getValueArrayFromColumn(string $table, string $column): array
    {
        $collection = $this->{$table};

        if (!isset($collection)) {
            throw new Exception("無効なテーブル名: {$table}");
        }

        // 会社に紐づくレコードが存在するか確認
        if ($collection->isEmpty()) {
            throw new Exception("テーブル {$table} に会社と紐づくレコードが存在しません。");
        }

        // カラム名の存在確認
        if ($collection->isEmpty() || !array_key_exists($column, $collection->first())) {
            throw new Exception("無効なカラム名: {$column}");
        }

        return $collection->pluck($column)->toArray();
    }


    /**
     * 指定したテーブルとカラムから特定の値を持つレコードを取得する
     * @param string $table  テーブル名
     * @param string $column カラム名
     * @param mixed  $value  カラム値
     * @return array
     * @throws Exception
     */
    public function getRecordFromColumnValue(string $table, string $column, mixed $value): array
    {
        $collection = $this->{$table};

        // テーブルの存在確認
        if (!isset($collection)) {
            throw new Exception("無効なテーブル名: {$table}");
        }

        // 会社に紐づくレコードが存在するか確認
        if ($collection->isEmpty()) {
            throw new Exception("テーブル {$table} に会社と紐づくレコードが存在しません。");
        }

        // カラムの存在確認
        if (!array_key_exists($column, $collection->first())) {
            throw new Exception("テーブル {$table} に無効なカラム名: {$column}");
        }

        $matchedRecords = $collection->where($column, $value);

        // 指定した値が見つからない場合
        if ($matchedRecords->isEmpty()) {
            throw new Exception("テーブル {$table} のカラム {$column} で値 {$value} を見つけられませんでした\n" . __FILE__ . __LINE__);
        }

        // 指定した値が複数存在する場合
        if ($matchedRecords->count() > 1) {
            throw new Exception("テーブル {$table} のカラム {$column} で値 {$value} が複数存在します\n" . __FILE__ . __LINE__);
        }

        return $matchedRecords->first();
    }
}
