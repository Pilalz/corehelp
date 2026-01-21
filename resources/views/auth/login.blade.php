<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div class="flex flex-row h-screen">
        <div class="hidden sm:flex bg-linear-to-r from-indigo-900 via-blue-800 to-sky-700 w-[40%]">
            <div class="flex flex-col justify-between w-screen p-10 items-center">
                <div class="text-gray-200">Your central hub for guides, support, and answers.</div>
                <div class="">
                    <p class="text-white text-center font-bold text-4xl mb-10">Resolve Issues Faster</p>
                    <img src="{{ asset('img/Dashboard.svg') }}" alt="Logo" class="h-auto w-auto" />
                </div>
            </div>
        </div>

        <div class="flex bg-sky-700 w-screen sm:w-[60%]">
            <div class="rounded-none sm:rounded-l-[50px] bg-white flex flex-col justify-between w-screen p-10">
                <div class="flex flex-row justify-between">
                    <a href="{{ route('article.index') }}" class="flex gap-2 items-center text-gray-800">
                        <x-application-logo class="w-20 h-20" />
                        CoreHelp
                    </a>
                    
                    <a href="{{ route('register') }}" class="flex gap-2 items-center text-gray-800 hover:text-blue-700">
                        <x-heroicon-o-user-circle class="w-5 h-5" />
                        {{ __('Sign up') }}
                    </a>
                </div>
                <div class="p-14">
                    <h1 class="pb-4 text-4xl">Sign In</h1>
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <!-- Email Address -->
                        <div>
                            <x-input-label for="email" :value="__('Email')" />
                            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>

                        <!-- Password -->
                        <div class="mt-4">
                            <x-input-label for="password" :value="__('Password')" />

                            <x-text-input id="password" class="block mt-1 w-full"
                                            type="password"
                                            name="password"
                                            required autocomplete="current-password" />

                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        </div>

                        <div class="flex justify-between items-center mt-4">
                            <!-- Remember Me -->
                            <label for="remember_me" class="inline-flex items-center">
                                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                                <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
                            </label>

                            @if (Route::has('password.request'))
                                <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('password.request') }}">
                                    {{ __('Forgot your password?') }}
                                </a>
                            @endif
                        </div>
                        
                        <div class="block mt-8">
                            <x-primary-button class="w-full justify-center">
                                {{ __('Log in') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
                <div class="text-gray-400 text-sm">
                    2026
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
