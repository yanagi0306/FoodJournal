<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CustomerType extends BaseModel
{
    use HasFactory;

    protected $table = 'customer_type';

    protected $fillable = [
        'company_id',
        'type_cd',
        'type_name',
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
     * order_infoテーブル リレーション設定
     * @return HasMany
     */
    public function orderInfos(): HasMany
    {
        return $this->hasMany(OrderInfo::class);
    }


}
