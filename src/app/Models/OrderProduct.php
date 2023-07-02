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
        'order_product_master_id',
        'sell_price',
        'quantity',
    ];

    /**
     * orderテーブル リレーション設定
     * @return BelongsTo
     */
    public function orderInfo(): BelongsTo
    {
        return $this->belongsTo(OrderInfo::class);
    }

    /**
     * order_product_masterテーブル リレーション設定
     * @return BelongsTo
     */
    public function orderProductMaster(): BelongsTo
    {
        return $this->belongsTo(OrderProductMaster::class);
    }
}
