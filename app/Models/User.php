<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/**
 * Gerência tabela e entendidades relacionanos a usuários no banco
 *
 * @author Kaic Valadares <valadares19@gmail.com>
 * @since 14/12/2024
 * @version 1.0.0
 */
class User extends Authenticatable {

    use HasApiTokens, HasFactory, Notifiable;
    protected $table='users';

    /**
     * Atributos que permitem inserção em massa
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'is_active',
        'type_id',
    ];

    /**
     * Atributos que nao pode ser serializados
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Tipa retornos (cast) como solicitado
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Obtém usuário apartir do e-mail
     *
     * @param object $query
     * @param string $email
     * @return object
     */
    public function scopeGetUserByEmail(Object $query, string $email) {

        $query->where('email', $email)
        ->where('is_active', true);

        return $query;

    }
}
