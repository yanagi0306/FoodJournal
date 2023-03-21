<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PurchaseProduct extends Model
{
    use HasFactory;

    protected $table = 'purchase_product';

    protected $fillable = [
        'purchase_id',
        'purchase_product_master_id',
        'product_name',
        'quantity',
        'unit_price',
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
     * purchase_product_masterテーブル リレーション設定
     * @return BelongsTo
     */
    public function purchaseProductMaster(): BelongsTo
    {
        return $this->belongsTo(PurchaseProductMaster::class);
    }
}
