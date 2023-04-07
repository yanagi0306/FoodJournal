<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderProduct extends BaseModel
{
    use HasFactory;

    protected $table = 'order_product';

    protected $fillable = [
        'order_id',
        'product_master_id',
        'quantity',
        'unit_price',
        'order_options',
    ];

    /**
     * orderテーブル リレーション設定
     * @return BelongsTo
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * product_masterテーブル リレーション設定
     * @return BelongsTo
     */
    public function product_master(): BelongsTo
    {
        return $this->belongsTo(ProductMaster::class);
    }
}
