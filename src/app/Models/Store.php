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
        'store_cd',
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
    public function product_categories(): HasMany
    {
        return $this->hasMany(ProductCategory::class);
    }

    /**
     * orderテーブル リレーション設定
     * @return HasMany
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    /**
     * purchaseテーブル リレーション設定
     * @return HasMany
     */
    public function purchases(): HasMany
    {
        return $this->hasMany(Purchase::class);
    }

    /**
     * purchase_productテーブル リレーション設定
     * @return HasMany
     */
    public function purchase_products(): HasMany
    {
        return $this->hasMany(PurchaseProduct::class);
    }

    /**
     * expenseテーブル リレーション設定
     * @return HasMany
     */
    public function expenses(): HasMany
    {
        return $this->hasMany(Expense::class);
    }

    /**
     * expense_budgetテーブル リレーション設定
     * @return HasMany
     */
    public function expense_budgets(): HasMany
    {
        return $this->hasMany(ExpenseBudget::class);
    }

    /**
     * incomeテーブル リレーション設定
     * @return HasMany
     */
    public function incomes(): HasMany
    {
        return $this->hasMany(Income::class);
    }

    /**
     * income_budgetテーブル リレーション設定
     * @return HasMany
     */
    public function income_budgets(): HasMany
    {
        return $this->hasMany(IncomeBudget::class);
    }


}
