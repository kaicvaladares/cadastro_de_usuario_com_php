<?php

namespace App\Http\Controllers\Api;

use App\Enums\UserTypeEnum;
use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

/**
 * Gerencia autenticação do usuário na API
 *
 * @author Kaic Valadares <valadares19@gmail.com,>
 * @since 14/12/2024
 * @version 1.0.0
 */
class AuthController extends Controller {


    /**
     * Realiza cadastro de novo usuário
     *
     * @param Request $request
     * @return Response
     */
    public function register(Request $request) {

        try {

            $validator = Validator::make($request->all(), [
                'name' => ['required', 'string', 'min:3', 'max:50'],
                'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users','regex:/(.+)@(.+)\.(.+)/i'],
                'password_confirmation' => ['required', 'min:6', 'max:20'],
                'password' => ['required', 'min:6', 'max:20', 'confirmed']
            ], [
                'name.required' => 'Name é obrigatório',
                'name.min' => 'Name deve ter no mínimo 3 caracteres',
                'name.max' => 'Name deve ter no máximo 50 caracteres',
                'email.required' => 'email é obrigatório',
                'password_confirmation.required' => 'password_confirmation é obrigatório',
                'password.confirmed' => 'password_confirmation não é igual a password',
            ]);

            if (!$validator->fails()) {

                $user = User::create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'password' => bcrypt($request->password),
                    'is_active' => true,
                    'type_id' => UserTypeEnum::API,
                ]);

                $token = $user->createToken('regisSystem')->plainTextToken ?? [];

                // TODO enviar emails der boas vindas

                Log::info("[AUTH-REGISTER] Novo usuário adicionado" . $user->name . 'às ' . now()->format('d/mY H:i:s'));

                return response()->json([
                    'user' => $user,
                    'token' => $token,
                    'subject' => 'Bem-vindo(a) a api RegisSystem',
                ],  Response::HTTP_OK);

            }

            return response(['message' => 'Não foi possível criar usuário. ' . $validator->errors()->first()], Response::HTTP_BAD_REQUEST);

        } catch (Exception $e) {

            Log::error('[AUTH-REGISTER] Falha ao inserir novo usuário. ' . $e->getMessage());
            return response(['error' => "Não foi possível criar usuário. Tente novamente mais tarde"], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Loga usuário
     *
     * @param Request $request
     * @return Reponse
     */
    public function login(Request $request) {

        try {

            $validator = Validator::make($request->all(), [
                'email' => 'required|string|email',
                'password' => 'required|string',
            ],[
                'email.required' => 'E-mail é obrigatório',
                'password.required' => 'Senha é obrigatória',
            ]);

            if (!$validator->fails()) {

                $user = User::getUserByEmail($request->email)->first();

                if (!$user || !Hash::check($request->password, $user->password)) {
                    return response()->json(['message' => 'Credenciais inválidas'], Response::HTTP_UNAUTHORIZED);
                }

                $token = $user->createToken('regisSystem')->plainTextToken;

                return Response()->json(['user' => $user,  'token' => $token], Response::HTTP_OK);

            }

            return response()->json(['error' => $validator->errors()->first()], Response::HTTP_BAD_REQUEST);

        } catch (Exception $e) {

            Log::error('[AUTH-LOGIN] Falha ao fazer login. ' . $e->getMessage());
            return response("Não foi possível fazer login. Tente novamente mais tarde", Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Desconecta usuário
     *
     * @return void
     */
    public function logout(Request $request) {

        try {

            $request->user()->currentAccessToken()->delete();

            return response()->json(['message' => 'Usuário desconectado com sucesso'], Response::HTTP_OK);

        } catch (Exception $e) {

            Log::error('[AUTH-LOGOUT] Falha ao deslogar usuário. ' . $e->getMessage());
            return response("Não foi deslogar. Tente novamente mais tarde", Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
