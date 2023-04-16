<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Company extends BaseModel
{
    use HasFactory;

    protected $table = 'company';

    protected $fillable = [
        'company_name',
        'company_cd',
        'purchase_company_cd',
        'mail',
    ];

    /**
     * storeテーブル リレーション設定
     * @return HasMany
     */
    public function stores(): HasMany
    {
        return $this->hasMany(Store::class);
    }

    /**
     * customer_typeテーブル リレーション設定
     * @return HasMany
     */
    public function customer_types(): HasMany
    {
        return $this->hasMany(CustomerType::class);
    }

    /**
     * usersテーブル リレーション設定
     * @return HasMany
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    /**
     * payment_methodテーブル リレーション設定
     * @return HasMany
     */
    public function payment_methods(): HasMany
    {
        return $this->hasMany(PaymentMethod::class);
    }

    /**
     * purchase_supplierテーブル リレーション設定
     * @return HasMany
     */
    public function purchase_suppliers(): HasMany
    {
        return $this->hasMany(PurchaseSupplier::class);
    }

    /**
     * parent_expense_categoryテーブル リレーション設定
     * @return HasMany
     */
    public function parent_expense_categories(): HasMany
    {
        return $this->hasMany(ParentExpenseCategory::class);
    }

    /**
     * expense_categoryテーブル リレーション設定
     * @return HasMany
     */
    public function expense_categories(): HasMany
    {
        return $this->hasMany(ExpenseCategory::class);
    }

    /**
     * parent_income_categoryテーブル リレーション設定
     * @return HasMany
     */
    public function parent_income_categories(): HasMany
    {
        return $this->hasMany(ParentIncomeCategory::class);
    }

    /**
     * income_categoryテーブル リレーション設定
     * @return HasMany
     */
    public function income_categories(): HasMany
    {
        return $this->hasMany(IncomeCategory::class);
    }

    /**
     * parent_purchase_categoryテーブル リレーション設定
     * @return HasMany
     */
    public function parent_purchase_categories(): HasMany
    {
        return $this->hasMany(ParentPurchaseCategory::class);
    }

    /**
     * purchase_categoryテーブル リレーション設定
     * @return HasMany
     */
    public function purchase_categories(): HasMany
    {
        return $this->hasMany(PurchaseCategory::class);
    }

    /**
     * purchase_product_masterテーブル リレーション設定
     * @return HasMany
     */
    public function purchase_product_masters(): HasMany
    {
        return $this->hasMany(PurchaseProductMaster::class);
    }

}
