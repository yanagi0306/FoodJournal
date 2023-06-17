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
     * カテゴリ1に関連するPurchaseProductMasterのリレーション定義
     * @return HasMany
     */
    public function purchaseProductMastersCategory1(): HasMany
    {
        return $this->hasMany(PurchaseProductMaster::class, 'category1_id', 'id');
    }

    /**
     * カテゴリ2に関連するPurchaseProductMasterのリレーション定義
     * @return HasMany
     */
    public function purchaseProductMastersCategory2(): HasMany
    {
        return $this->hasMany(PurchaseProductMaster::class, 'category2_id', 'id');
    }

    /**
     * カテゴリ3に関連するPurchaseProductMasterのリレーション定義
     * @return HasMany
     */
    public function purchaseProductMastersCategory3(): HasMany
    {
        return $this->hasMany(PurchaseProductMaster::class, 'category3_id', 'id');
    }

    /**
     * カテゴリ4に関連するPurchaseProductMasterのリレーション定義
     * @return HasMany
     */
    public function purchaseProductMastersCategory4(): HasMany
    {
        return $this->hasMany(PurchaseProductMaster::class, 'category4_id', 'id');
    }


}
