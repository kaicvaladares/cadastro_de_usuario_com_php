<x-guest-layout class="page-forgot">

    <div class="title">
        <p class="text-sm">
            Esqueceu sua senha? Sem problemas. Basta nos informar o seu endereço de e-mail e enviaremos um link de redefinição de senha para que você possa escolher uma nova.
        </p>
    </div>

    @if (session('status'))
        <div class="alert alert-success mb-4">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <div class="form-group">
            <label for="email">E-mail</label>
            <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus>
            @error('email')
                <span class="form-error">{{ $message }}</span>
            @enderror
        </div>

        <button type="submit" class="btn">Enviar Link de Redefinição de Senha</button>
        
    </form>

    <div class="message register-message">
        <span>Já tem uma conta?</span>
        <a href="{{ route('login') }}" class="btn-enter focus:outline-none text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">Faça login</a>
    </div>

</x-guest-layout>
