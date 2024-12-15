<?php

namespace Tests\Feature;

use App\Enums\UserTypeEnum;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\TestCase;

/**
 * Teste unitário de criação de usuário
 *
 * @author Kaic Valadares <valadares19@gmail.com>
 * @since 14/12/2024
 * @version 1.0.0
 */
class UserRegistrationTest extends TestCase {

    use RefreshDatabase;

    /**
     * Testa se criação de usuário está funcionando corretamente
     *
     * @return void
     */
    public function test_user_can_register_valid() {

        $response = $this->postJson('/api/register', [
            'name' => 'kaic valadares',
            'email' => 'valadares19@gmail.com',
            'password' => '123456',
            'password_confirmation' => '123456',
            'is_active' => true,
            'type_id' => UserTypeEnum::WEB,
        ]);


        // $response->dump();

        // Verifica se foi inserido
        $response->assertStatus(Response::HTTP_OK);

        // Verifica se está no banco
        $this->assertDatabaseHas('users', [
            'email' => 'valadares19@gmail.com',
        ]);
    }
}
