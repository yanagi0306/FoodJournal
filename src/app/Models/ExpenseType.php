<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;


class ExpenseType extends Model
{
    use HasFactory;

    protected $table = 'expense_type';

    protected $fillable = [
        'type_cd',
        'type_name',
        'description',
    ];

    /**
     * expense_categoryテーブル リレーション設定
     * @return HasMany
     */
    public function expenseCategories(): HasMany
    {
        return $this->hasMany(ExpenseCategory::class);
    }
}
