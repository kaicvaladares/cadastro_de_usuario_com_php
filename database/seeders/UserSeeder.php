<?php

namespace Database\Seeders;

use App\Enums\UserTypeEnum;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder {

    /**
     * Run the database seeds.
     */
    public function run(): void {

        // Adiciona usuário de teste padrão
        User::factory()->create([
            'name' => 'administrador',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('123456'),
            'remember_token' => Str::random(10),
            'is_active' => true,
            'type_id' => UserTypeEnum::WEB
        ]);

        // Adiciona usuários fakes genéricos
        User::factory(10)->create();
    }
}
