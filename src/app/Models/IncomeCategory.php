<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class IncomeCategory extends BaseModel
{
    use HasFactory;

    protected $table = 'income_category';

    protected $fillable = [
        'company_id',
        'parent_income_category_id',
        'cat_cd',
        'cat_name',
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
     * parent_income_categoryテーブル リレーション設定
     * @return BelongsTo
     */
    public function parent_income_category(): BelongsTo
    {
        return $this->belongsTo(ParentIncomeCategory::class);
    }

    /**
     * income_typeテーブル リレーション設定
     * @return BelongsTo
     */
    public function income_type(): BelongsTo
    {
        return $this->belongsTo(IncomeType::class);
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
