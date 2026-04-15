<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run()
    {
        // Criar usuário admin
        User::updateOrCreate(
            ['email' => 'admin@technews.com'],
            [
                'name' => 'Administrador',
                'email' => 'admin@technews.com',
                'password' => Hash::make('12345678'),
                'is_admin' => true,
                'email_verified_at' => now(),
            ]
        );

        // Criar um usuário comum para teste
        User::updateOrCreate(
            ['email' => 'usuario@teste.com'],
            [
                'name' => 'Usuário Teste',
                'email' => 'usuario@teste.com',
                'password' => Hash::make('12345678'),
                'is_admin' => false,
                'email_verified_at' => now(),
            ]
        );

        $this->command->info('Usuários criados com sucesso!');
        $this->command->info('Admin: admin@technews.com / 12345678');
        $this->command->info('Usuário: usuario@teste.com / 12345678');
    }
}