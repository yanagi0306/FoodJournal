<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderPayment extends BaseModel
{
    use HasFactory;

    protected $table = 'order_payment';

    protected $fillable = [
        'order_id',
        'payment_method_id',
        'amount',
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
     * payment_methodテーブル リレーション設定
     * @return BelongsTo
     */
    public function payment_method(): BelongsTo
    {
        return $this->belongsTo(PaymentMethod::class);
    }
}
