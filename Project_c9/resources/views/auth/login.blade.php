<x-guest-layout>
<div class="flex justify-center items-center h-screen imagen-fondo">
       <div class="flex justify-center items-center rounded-xl fondo-login"
              style="background-image: url('/assets/img/login_fo.png'); background-repeat: no-repeat; background-size: cover;">
            <div class="flex flex-wrap justify-center items-center w-full backdrop-blur-md p-8 md:p-16 rounded-xl">
                
                {{-- Sección izquierda con branding --}}
                <div class="hidden md:flex flex-col justify-center gap-4 md:w-1/2 p-6">
                    <h1 class="font-black text-white text-4xl md:text-5xl uppercase drop-shadow">Pen Testing Platform</h1>
                    <p class="text-gray-100 text-lg">Secure. Assess. Strengthen. Your infrastructure under fire.</p>
                </div>

                {{-- Sección derecha con formulario --}}
                <div class="relative bg-white px-8 py-10 rounded-lg shadow-2xl border border-gray-200 w-full md:w-1/2 max-w-md">
                    
                    {{-- Logo --}}
                    <div class="flex justify-center mb-6">
                        <x-authentication-card-logo />
                    </div>

                    {{-- Validaciones --}}
                    <x-validation-errors class="mb-4" />
                    @session('status')
                        <div class="mb-4 font-medium text-sm text-green-600">
                            {{ $value }}
                        </div>
                    @endsession

                    {{-- Formulario de login --}}
                    <form method="POST" action="{{ route('login') }}" class="space-y-6">
                        @csrf

                        {{-- Email --}}
                        <div class="relative">
                            <x-label for="email" value="{{ __('Email') }}" />
                            <x-input id="email" name="email" type="email"
                                class="block mt-1 w-full pl-10 py-2 border border-gray-300 rounded-lg focus:ring-blue-400 focus:border-blue-400"
                                :value="old('email')" required autofocus autocomplete="username" />
                            <svg class="absolute left-3 top-9 w-5 h-5 text-blue-400" fill="none" stroke="currentColor" stroke-width="2"
                                 viewBox="0 0 24 24"><path d="M4 4h16v16H4z" stroke="none"/><path d="M22 4L12 13 2 4"></path></svg>
                        </div>

                        {{-- Password --}}
                        <div class="relative">
                            <x-label for="password" value="{{ __('Password') }}" />
                            <x-input id="password" name="password" type="password"
                                class="block mt-1 w-full pl-10 py-2 border border-gray-300 rounded-lg focus:ring-blue-400 focus:border-blue-400"
                                required autocomplete="current-password" />
                            <svg class="absolute left-3 top-9 w-5 h-5 text-blue-400" fill="none" stroke="currentColor" stroke-width="2"
                                 viewBox="0 0 24 24"><path d="M12 17a2 2 0 1 0 0-4 2 2 0 0 0 0 4z"></path><path d="M5 12h14v10H5z"></path><path d="M8 12V8a4 4 0 1 1 8 0v4"></path></svg>
                        </div>

                        {{-- Remember me --}}
                        <div class="flex items-center">
                            <x-checkbox id="remember_me" name="remember" />
                            <label for="remember_me" class="ml-2 text-sm text-gray-600">{{ __('Remember me') }}</label>
                        </div>

                        {{-- Error custom --}}
                        <div class="text-center text-red-500 text-sm">aaaa</div>

                        {{-- Links y botón --}}
                        <div class="flex items-center justify-between mt-6">
                            @if (Route::has('password.request'))
                                <a class="text-sm text-blue-500 hover:underline" href="{{ route('password.request') }}">
                                    {{ __('Forgot your password?') }}
                                </a>
                            @endif

                            <x-button class="bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-6 rounded-lg shadow">
                                {{ __('Log in') }}
                            </x-button>
                        </div>

                        {{-- Divider --}}
                        <div class="flex items-center gap-4 my-6">
                            <hr class="flex-grow border-gray-300">
                            <span class="text-gray-400 text-sm">or</span>
                            <hr class="flex-grow border-gray-300">
                        </div>

                        {{-- Register --}}
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-gray-500">Don't have an account?</span>
                            <a href="{{ route('register') }}" class="font-semibold text-blue-500 hover:underline">Register</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
