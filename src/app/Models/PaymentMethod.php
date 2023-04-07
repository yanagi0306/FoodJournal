<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PaymentMethod extends BaseModel
{
    use HasFactory;

    protected $table = 'payment_method';

    protected $fillable = [
        'company_id',
        'payment_cd',
        'payment_name',
        'commission_rate',
        'is_hidden',
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
     * order_paymentテーブル リレーション設定
     * @return HasMany
     */
    public function order_payments(): HasMany
    {
        return $this->hasMany(OrderPayment::class);
    }

}
