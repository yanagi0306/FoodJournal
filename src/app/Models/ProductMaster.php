<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductMaster extends Model
{
    use HasFactory;

    protected $table = 'product_master';

    protected $fillable = [
        'store_id',
        'category1_id',
        'category2_id',
        'category3_id',
        'category4_id',
        'product_cd',
        'product_name',
        'unit_price',
    ];

    /**
     * companyテーブル リレーション設定
     * @return BelongsTo
     */
    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }

    /**
     * product_categoryテーブル リレーション設定
     * @return BelongsTo
     */
    public function category1(): BelongsTo
    {
        return $this->belongsTo(ProductCategory::class, 'category1_id', 'id');
    }

    /**
     * product_categoryテーブル リレーション設定
     * @return BelongsTo
     */
    public function category2(): BelongsTo
    {
        return $this->belongsTo(ProductCategory::class, 'category2_id', 'id');
    }

    /**
     * product_categoryテーブル リレーション設定
     * @return BelongsTo
     */
    public function category3(): BelongsTo
    {
        return $this->belongsTo(ProductCategory::class, 'category3_id', 'id');
    }

    /**
     * product_categoryテーブル リレーション設定
     * @return BelongsTo
     */
    public function category4(): BelongsTo
    {
        return $this->belongsTo(ProductCategory::class, 'category4_id', 'id');
    }

    /**
     * order_productテーブル リレーション設定
     * @return HasMany
     */
    public function orderProducts(): HasMany
    {
        return $this->hasMany(OrderProduct::class);
    }

}
