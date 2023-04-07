<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductCategory extends BaseModel
{
    use HasFactory;

    protected $table = 'product_category';

    protected $fillable = [
        'company_id',
        'cat_cd',
        'cat_name',
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
     * @return HasMany
     */
    public function purchase_product_masters(): HasMany
    {
        return $this->hasMany(PurchaseProductMaster::class, 'category1_id', 'id')
            ->orWhere('category2_id', $this->id)
            ->orWhere('category3_id', $this->id)
            ->orWhere('category4_id', $this->id);
    }

}
