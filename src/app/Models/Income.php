<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Income extends BaseModel
{
    use HasFactory;

    protected $table = 'income';

    protected $fillable = [
        'store_id',
        'income_category_id',
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
     * income_categoryテーブル リレーション設定
     * @return BelongsTo
     */
    public function income_category(): BelongsTo
    {
        return $this->belongsTo(IncomeCategory::class);
    }
}
