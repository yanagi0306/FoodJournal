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
        'type_cd',
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
    public function parentIncomeCategory(): BelongsTo
    {
        return $this->belongsTo(ParentIncomeCategory::class);
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
