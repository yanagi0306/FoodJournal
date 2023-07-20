<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ParentIncomeCategory extends BaseModel
{
    use HasFactory;

    protected $table = 'parent_income_category';

    protected $fillable = [
        'company_id',
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
     * income_category リレーション設定
     * @return HasMany
     */
    public function incomeCategories(): HasMany
    {
        return $this->hasMany(IncomeCategory::class);
    }
}
