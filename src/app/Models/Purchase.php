<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Purchase extends BaseModel
{
    use HasFactory;

    protected $table = 'purchase';

    protected $fillable = [
        'store_id',
        'purchase_supplier_id',
        'slip_number',
        'date',
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
     * purchase_supplierテーブル リレーション設定
     * @return BelongsTo
     */
    public function purchase_supplier(): BelongsTo
    {
        return $this->belongsTo(PurchaseSupplier::class);
    }

}
