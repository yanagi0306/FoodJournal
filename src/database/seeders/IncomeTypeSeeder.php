<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\IncomeType;
use Illuminate\Database\Seeder;

class IncomeTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * @return void
     */
    public function run(): void
    {
        $incomeTypes = [
            1 => '固定収入',
            2 => '月次収入',
            3 => '日次収入',
            4 => '入力不要',
        ];

        foreach ($incomeTypes as $typeCd => $typeName) {
            IncomeType::create([
                'type_cd'    => $typeCd,
                'type_name'  => $typeName,
            ]);
        }
    }
}
