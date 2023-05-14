<?php

namespace Database\Seeders;

use App\Models\ExpenseType;
use Illuminate\Database\Seeder;

class ExpenseTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * @return void
     */
    public function run(): void
    {
        $expenseTypes = [
            1 => '固定支出',
            2 => '月次支出',
            3 => '日次支出',
            4 => '入力不要',
        ];

        foreach ($expenseTypes as $typeCd => $typeName) {
            ExpenseType::create([
                'type_cd'   => $typeCd,
                'type_name' => $typeName,
            ]);
        }
    }
}
