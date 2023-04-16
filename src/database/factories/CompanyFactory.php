<?php

namespace Database\Factories;

use App\Models\Company;
use App\Traits\Counter;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Company>
 */
class CompanyFactory extends Factory
{
    protected $model = Company::class;

    use Counter;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

//        return [
//            'company_name' => $this->faker->name,
//            'company_cd' => $this->getNextCounter('company_cd', '1001'),
//            'purchase_company_cd' => $this->faker->numberBetween(10000001, 99999999),
//            'mail' => $this->faker->unique()->safeEmail,
//        ];

        return [
            'company_name' => '合同会社PlusH-table',
            'company_cd' => $this->getNextCounter('company_cd', '1001'),
            'purchase_company_cd' => '78411381',
            'mail' => $this->faker->unique()->safeEmail,
        ];
    }
}
