<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Expense extends BaseModel
{
    use HasFactory;

    protected $table = 'expense';

    protected $fillable = [
        'store_id',
        'expense_category_id',
        'amount',
        'date',
        'comment',
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
     * expense_categoryテーブル リレーション設定
     * @return BelongsTo
     */
    public function expense_category(): BelongsTo
    {
        return $this->belongsTo(ExpenseCategory::class);
    }
}
