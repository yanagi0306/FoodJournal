<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OrderProductMaster extends BaseModel
{
    use HasFactory;

    protected $table = 'order_product_master';

    protected $fillable = [
        'company_id',
        'product_cd',
        'product_name',
        'unit_price',
        'unit_cost',
        'category1',
        'category2',
        'category3',
        'category4',
        'category5',
    ];

    /**
     * companyテーブル リレーション設定
     * @return BelongsTo
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }

    /**
     * order_productテーブル リレーション設定
     * @return HasMany
     */
    public function OrderProducts(): HasMany
    {
        return $this->hasMany(OrderProduct::class);
    }

}
