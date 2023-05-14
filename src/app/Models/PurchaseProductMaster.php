<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PurchaseProductMaster extends BaseModel
{
    use HasFactory;

    protected $table = 'purchase_product_master';

    protected $fillable = [
        'company_id',
        'category1_id',
        'category2_id',
        'category3_id',
        'category4_id',
        'cat_cd',
        'purchase_cd',
        'purchase_name',
        'tax_type',
        'original_unit_price',
        'unit_cost',
        'unit_price',
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
     * product_categoryテーブル リレーション設定
     * @return BelongsTo
     */
    public function category1(): BelongsTo
    {
        return $this->belongsTo(ProductCategory::class, 'category1_id');
    }

    /**
     * product_categoryテーブル リレーション設定
     * @return BelongsTo
     */
    public function category2(): BelongsTo
    {
        return $this->belongsTo(ProductCategory::class, 'category2_id');
    }

    /**
     * product_categoryテーブル リレーション設定
     * @return BelongsTo
     */
    public function category3(): BelongsTo
    {
        return $this->belongsTo(ProductCategory::class, 'category3_id');
    }

    /**
     * product_categoryテーブル リレーション設定
     * @return BelongsTo
     */
    public function category4(): BelongsTo
    {
        return $this->belongsTo(ProductCategory::class, 'category4_id');
    }

}
