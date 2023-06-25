<?php

namespace App\Services\Company;

use App\Models\Company;
use App\Models\CustomerType;
use App\Models\ExpenseCategory;
use App\Models\IncomeCategory;
use App\Models\PaymentMethod;
use App\Models\PurchaseSupplier;
use App\Models\Store;
use Exception;

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

    public Company $company;

    /** @var iterable|Store[] $stores */
    public array $stores;

    /** @var iterable|CustomerType[] $customerTypes */
    public array $customerTypes;

    /** @var iterable|PaymentMethod[] $paymentMethods */
    public array $paymentMethods;

    /** @var iterable|PurchaseSupplier[] $purchaseSuppliers */
    public array $purchaseSuppliers;

    /** @var iterable|IncomeCategory[] $incomeCategories */
    public array $incomeCategories;

    /** @var iterable|ExpenseCategory[] $expenseCategories */
    public array $expenseCategories;

    /**
     * 会社に関連テーブル情報一覧を取得する
     * @param int $companyId
     */
    public function __construct(int $companyId)
    {
        $this->findCompanyById($companyId);
        $this->findCompanyWithStoresById();
        $this->findCustomersWithStoresById();
        $this->findPaymentMethodsWithStoresById();
        $this->findPurchaseSuppliersWithStoresById();
        $this->findIncomeCategoriesWithStoresById();
        $this->findExpenseCategoriesWithStoresById();
    }

    /**
     * モデルを参照して会社テーブル情報を取得する
     * @param int $companyId
     * @return void
     */
    private function findCompanyById(int $companyId): void
    {
        $this->company = Company::find($companyId)->first();
    }

    /**
     * モデルを参照して関連する店舗テーブル情報を取得する
     * @return void
     */
    private function findCompanyWithStoresById(): void
    {
        $this->stores = $this->company->stores->keyBy('id')->toArray();
    }

    /**
     * モデルを参照して関連する顧客テーブル情報を取得する
     * @return void
     */
    private function findCustomersWithStoresById(): void
    {
        $this->customerTypes = $this->company->customerTypes->keyBy('id')->toArray();
    }

    /**
     * モデルを参照して関連する顧客テーブル情報を取得する
     * @return void
     */
    private function findPaymentMethodsWithStoresById(): void
    {
        $this->paymentMethods = $this->company->paymentMethods->keyBy('id')->toArray();
    }

    /**
     * モデルを参照して関連する顧客テーブル情報を取得する
     * @return void
     */
    private function findPurchaseSuppliersWithStoresById(): void
    {
        $this->purchaseSuppliers = $this->company->purchaseSuppliers->keyBy('id')->toArray();
    }

    /**
     * モデルを参照して関連する収入カテゴリテーブル情報を取得する
     * @return void
     */
    private function findIncomeCategoriesWithStoresById(): void
    {
        $this->incomeCategories = $this->company->incomeCategories->keyBy('id')->toArray();
    }

    /**
     * モデルを参照して関連する支払カテゴリテーブル情報を取得する
     * @return void
     */
    private function findExpenseCategoriesWithStoresById(): void
    {
        $this->expenseCategories = $this->company->expenseCategories->keyBy('id')->toArray();
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
        if (!isset($this->{$table})) {
            throw new Exception("無効なテーブル名: {$table}");
        }

        return array_column($this->{$table}, $column);
    }

    /**
     * 指定したテーブルとカラムから特定の値を持つレコードのIDを取得する
     * @param string $table  テーブル名
     * @param string $column カラム名
     * @param mixed  $value  カラム値
     * @return int
     * @throws Exception
     */
    public function getIdFromColumnValue(string $table, string $column, mixed $value): int
    {
        // テーブルの存在確認
        if (!isset($this->{$table})) {
            throw new Exception("無効なテーブル名: {$table}");
        }

        $matchedIds = [];

        // 対象テーブルの各レコードについてループ
        foreach ($this->{$table} as $id => $record) {
            // カラムの存在確認
            if (!isset($record[$column])) {
                throw new Exception("テーブル {$table} に無効なカラム名: {$column}");
            }

            // 指定した値と一致するか確認
            if ($record[$column] === $value) {
                $matchedIds[] = $id;
            }
        }

        // 指定した値が見つからない場合
        if (empty($matchedIds)) {
            throw new Exception("テーブル {$table} のカラム {$column} で値 {$value} を見つけられませんでした");
        }

        // 指定した値が複数存在する場合
        if (count($matchedIds) > 1) {
            throw new Exception("テーブル {$table} のカラム {$column} で値 {$value} が複数存在します");
        }

        return $matchedIds[0];
    }



}
