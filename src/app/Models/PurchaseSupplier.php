<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PurchaseSupplier extends BaseModel
{
    use HasFactory;

    protected $table = 'purchase_supplier';

    protected $fillable = [
        'company_id',
        'supplier_cd',
        'supplier_name',
        'is_no_slip_num',
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
     * purchase_infoテーブル リレーション設定
     * @return HasMany
     */
    public function purchaseInfos(): HasMany
    {
        return $this->hasMany(PurchaseInfo::class);
    }

}
