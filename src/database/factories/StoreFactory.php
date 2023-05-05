<?php

namespace Database\Factories;

use App\Models\Company;
use App\Models\Store;
use App\Traits\Counter;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Store>
 */
class StoreFactory extends Factory
{
    protected $model = Store::class;

    use Counter;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'company_id' => Company::factory(),
            'order_store_cd' => $this->getNextCounter('company_cd', '1001'),
            'purchase_store_cd' => $this->getNextCounter('purchase_store_cd', '1001'),
            'store_name' => $this->faker->name,
            'mail' => $this->faker->unique()->safeEmail,
            'is_closed' => null,
        ];
    }
}
