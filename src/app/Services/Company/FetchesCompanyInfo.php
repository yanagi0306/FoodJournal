<?php

namespace App\Services\Company;

use App\Models\Company;
use App\Models\CustomerType;
use App\Models\ExpenseCategory;
use App\Models\IncomeCategory;
use App\Models\PaymentMethod;
use App\Models\PurchaseSupplier;
use App\Models\Store;

class FetchesCompanyInfo
{
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
}
