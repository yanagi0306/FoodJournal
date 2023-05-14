<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run(): void
    {
        $this->call([
            CompanySeeder::class,
            StoreSeeder::class,
            UsersSeeder::class,
            PaymentMethodSeeder::class,
            CustomerTypeSeeder::class,
            ExpenseTypeSeeder::class,
            IncomeTypeSeeder::class,
            ExpenseCategorySeeder::class,
            IncomeCategorySeeder::class,
        ]);

    }
}
