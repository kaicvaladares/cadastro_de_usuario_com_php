<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

/**
 * @author Kaic Valadares <valadares19@gmail.com>
 * @since 14/12/2024
 * @version 1.0.0
 */
class AuthenticatedSessionController extends Controller {

    /**
     * Exige tela de login
     * @return View
     */
    public function create(): View  {
        return view('auth.login');
    }

    /**
     * Recebe solicitação de autenticação
     * @param LoginRequest $request
     * @return RedirectResponse
     */
    public function store(LoginRequest $request): RedirectResponse {

        $request->authenticate();
        $request->session()->regenerate();

        return redirect()->intended(RouteServiceProvider::HOME);

    }

    /**
     * Finaliza a sessão
     */
    public function destroy(Request $request): RedirectResponse {

        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
