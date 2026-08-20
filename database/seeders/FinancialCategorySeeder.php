<?php

namespace Database\Seeders;

use App\Enums\FinancialType;
use App\Models\FinancialCategory;
use App\Models\User;
use Illuminate\Database\Seeder;

class FinancialCategorySeeder extends Seeder
{
    public function run(): void
    {
        $user = User::role('super-admin')->first();

        if (!$user) {
            return;
        }

        $categories = [
            ['name' => 'Moradia', 'type' => FinancialType::EXPENSE->value, 'icon' => 'fas fa-home'],
            ['name' => 'Alimentação', 'type' => FinancialType::EXPENSE->value, 'icon' => 'fas fa-utensils'],
            ['name' => 'Transporte', 'type' => FinancialType::EXPENSE->value, 'icon' => 'fas fa-car'],
            ['name' => 'Saúde', 'type' => FinancialType::EXPENSE->value, 'icon' => 'fas fa-heartbeat'],
            ['name' => 'Educação', 'type' => FinancialType::EXPENSE->value, 'icon' => 'fas fa-graduation-cap'],
            ['name' => 'Lazer', 'type' => FinancialType::EXPENSE->value, 'icon' => 'fas fa-film'],
            ['name' => 'Assinaturas', 'type' => FinancialType::EXPENSE->value, 'icon' => 'fas fa-sync-alt'],
            ['name' => 'Contas', 'type' => FinancialType::EXPENSE->value, 'icon' => 'fas fa-file-invoice'],
            ['name' => 'Outros', 'type' => FinancialType::EXPENSE->value, 'icon' => 'fas fa-ellipsis-h'],
            ['name' => 'Salário', 'type' => FinancialType::INCOME->value, 'icon' => 'fas fa-money-bill-wave'],
            ['name' => 'Freelance', 'type' => FinancialType::INCOME->value, 'icon' => 'fas fa-laptop-code'],
            ['name' => 'Investimentos', 'type' => FinancialType::INCOME->value, 'icon' => 'fas fa-chart-line'],
            ['name' => 'Outros', 'type' => FinancialType::INCOME->value, 'icon' => 'fas fa-ellipsis-h'],
        ];

        foreach ($categories as $category) {
            FinancialCategory::withoutGlobalScope('user')->firstOrCreate(
                [
                    'user_id' => $user->id,
                    'name' => $category['name'],
                    'type' => $category['type'],
                ],
                $category
            );
        }
    }
}