<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\BaseModel
 *
 * @mixin Builder
 * @method static Model|null find(mixed $id, array $columns = ['*'])
 * ここに追加したメソッドはModel共通処理として追加できる。
 */
class BaseModel extends Model
{

}
