<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class IncomeType extends Model
{
    use HasFactory;

    protected $table = 'income_type';

    protected $fillable = [
        'type_cd',
        'type_name',
        'description',
    ];

    /**
     * income_categoryテーブル リレーション設定
     * @return HasMany
     */
    public function IncomeCategories(): HasMany
    {
        return $this->hasMany(IncomeCategory::class);
    }

}
