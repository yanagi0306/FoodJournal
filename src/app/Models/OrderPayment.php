<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderPayment extends BaseModel
{
    use HasFactory;

    protected $table = 'order_payment';

    protected $fillable = [
        'order_info_id',
        'payment_method_id',
        'amount',
        'payment_fee',
    ];

    /**
     * order_infoテーブル リレーション設定
     * @return BelongsTo
     */
    public function orderInfo(): BelongsTo
    {
        return $this->belongsTo(OrderInfo::class);
    }

    /**
     * payment_methodテーブル リレーション設定
     * @return BelongsTo
     */
    public function paymentMethod(): BelongsTo
    {
        return $this->belongsTo(PaymentMethod::class);
    }
}
