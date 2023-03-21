<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IncomeBudget extends Model
{
    use HasFactory;

    protected $table = 'income_budget';

    protected $fillable = [
        'store_id',
        'income_category_id',
        'budget_month',
        'budget_amount',
        'confirmed_date',
        'confirmed_amount',
    ];

    /**
     * storeテーブル リレーション設定
     * @return BelongsTo
     */
    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }

    /**
     * income_categoryテーブル リレーション設定
     * @return BelongsTo
     */
    public function incomeCategory(): BelongsTo
    {
        return $this->belongsTo(IncomeCategory::class);
    }
}
