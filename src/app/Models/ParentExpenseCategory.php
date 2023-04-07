<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ParentExpenseCategory extends BaseModel
{
    use HasFactory;

    protected $table = 'parent_expense_category';

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
     * expense_categoryテーブル リレーション設定
     * @return HasMany
     */
    public function expense_categories(): HasMany
    {
        return $this->hasMany(ExpenseCategory::class);
    }

}
