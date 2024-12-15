<?php

namespace App\Http\Controllers\Auth;

use App\Enums\UserTypeEnum;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Exception;

/**
 * Gerencia registro de usuários
 *
 * @author Kaic Valadares <valadares19@gmail.com>
 * @since 14/12/2024
 * @version 1.0.0
 */
class RegisteredUserController extends Controller {

    /**
     * Redireciona para tela de registros
     *
     * @return View
     */
    public function create(): View {
        return view('auth.register');
    }

    /**
     * Adiciona novo usuário
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse {

        try {

            $validator = Validator::make($request->all(), [
                'name' => ['required', 'string', 'min:3', 'max:50'],
                'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class,'regex:/(.+)@(.+)\.(.+)/i'],
                'password' => ['required', 'min:6', 'max:20', 'confirmed'],
                'password_confirmation' => ['required', 'min:6', 'max:20']
            ], [
                'name.required' => 'Nome é obrigatório',
                'name.min' => 'Nome deve ter no mínimo 3 caracteres',
                'name.max' => 'Nome deve ter no máximo 50 caracteres',
                'email.required' => 'Email é obrigatório',
            ]);

            if (!$validator->fails()) {

                $user = User::create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                    'type_id' => UserTypeEnum::WEB,
                ]);

                event(new Registered($user));

                Auth::login($user);

                // TODO enviar emails der boas vindas

                return redirect(RouteServiceProvider::HOME);
            }

            return back()->withErrors($validator)->withInput();

        } catch (Exception $e) {
            Log::error('[REGISTER-STORE] Falha ao registrar novo usuário: ' . $e->getMessage());
            return back()->withErrors("Não foi possível cadastrar usuário, tente novamente mais tarde")->withInput();
        }

    }

}
