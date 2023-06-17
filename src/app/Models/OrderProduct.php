<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderProduct extends BaseModel
{
    use HasFactory;

    protected $table = 'order_product';

    protected $fillable = [
        'order_info_id',
        'product_cd',
        'product_name',
        'quantity',
        'unit_price',
        'unit_cost',
        'order_options',
        'category1',
        'category2',
        'category3',
        'category4',
        'category5',
    ];

    /**
     * orderテーブル リレーション設定
     * @return BelongsTo
     */
    public function orderInfo(): BelongsTo
    {
        return $this->belongsTo(OrderInfo::class);
    }
}
