<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\CustomerType;
use Illuminate\Database\Seeder;

class CustomerTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * @return void
     */
    public function run(): void
    {
        $customerTypes = [
            1 => '会社員・OL',
            2 => '家族',
            3 => '学生',
            4 => '夫婦',
            5 => 'ママ、主婦',
            6 => '先輩',
            7 => 'T.O,デリバリー',
        ];

        /** @var Company $company */
        $company = Company::inRandomOrder()->first();

        foreach ($customerTypes as $typeCd => $typeName) {
            CustomerType::create([
                                     'company_id' => $company->id,
                                     'type_cd'    => $typeCd,
                                     'type_name'  => $typeName,
                                 ]);
        }
    }
}
