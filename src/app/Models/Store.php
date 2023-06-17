<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Store extends BaseModel
{
    use HasFactory;

    protected $table = 'store';

    protected $fillable = [
        'company_id',
        'order_store_cd',
        'purchase_store_cd',
        'store_name',
        'mail',
        'is_closed',
    ];

    /**
     * companyテーブル リレーション設定
     * @return BelongsTo
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Usersテーブル リレーション設定
     * @return HasMany
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    /**
     * product_categoryテーブル リレーション設定
     * @return HasMany
     */
    public function productCategories(): HasMany
    {
        return $this->hasMany(ProductCategory::class);
    }

    /**
     * order_infoテーブル リレーション設定
     * @return HasMany
     */
    public function orderInfos(): HasMany
    {
        return $this->hasMany(OrderInfo::class);
    }

    /**
     * purchase_infoテーブル リレーション設定
     * @return HasMany
     */
    public function purchaseInfos(): HasMany
    {
        return $this->hasMany(PurchaseInfo::class);
    }

    /**
     * purchase_productテーブル リレーション設定
     * @return HasMany
     */
    public function purchaseProducts(): HasMany
    {
        return $this->hasMany(PurchaseProduct::class);
    }

    /**
     * expense_infoテーブル リレーション設定
     * @return HasMany
     */
    public function expenseInfos(): HasMany
    {
        return $this->hasMany(ExpenseInfo::class);
    }

    /**
     * expense_budgetテーブル リレーション設定
     * @return HasMany
     */
    public function expenseBudgets(): HasMany
    {
        return $this->hasMany(ExpenseBudget::class);
    }

    /**
     * income_infoテーブル リレーション設定
     * @return HasMany
     */
    public function incomeInfos(): HasMany
    {
        return $this->hasMany(IncomeInfo::class);
    }

    /**
     * income_budgetテーブル リレーション設定
     * @return HasMany
     */
    public function incomeBudgets(): HasMany
    {
        return $this->hasMany(IncomeBudget::class);
    }


}
