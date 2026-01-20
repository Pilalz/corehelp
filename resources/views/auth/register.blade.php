<x-guest-layout>

    <div class="flex flex-row h-screen">
        <div class="hidden sm:flex bg-gradient-to-r from-indigo-900 via-blue-800 to-sky-700 w-[40%]">
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
                    <a href="{{ route('register') }}" class="flex gap-2 items-center text-gray-800">
                        <x-application-logo class="w-20 h-20" />
                        CoreHelp
                    </a>
                </div>
                <div class="p-14">
                    <h1 class="pb-4 text-4xl">Create an Account</h1>
                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <!-- Name -->
                        <div>
                            <x-input-label for="name" :value="__('Name')" />
                            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <!-- Email Address -->
                        <div class="mt-4">
                            <x-input-label for="email" :value="__('Email')" />
                            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>

                        <!-- Password -->
                        <div class="mt-4">
                            <x-input-label for="password" :value="__('Password')" />

                            <x-text-input id="password" class="block mt-1 w-full"
                                            type="password"
                                            name="password"
                                            required autocomplete="new-password" />

                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        </div>

                        <!-- Confirm Password -->
                        <div class="mt-4">
                            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

                            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                                            type="password"
                                            name="password_confirmation" required autocomplete="new-password" />

                            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                        </div>

                        <div class="mt-6">
                            <x-primary-button class="w-full justify-center">
                                {{ __('Register') }}
                            </x-primary-button>
                        </div>

                        <div class="mt-6 justify-center items-center flex gap-3">
                            <p class="text-gray-600">Already have an account?</p>
                            <a class="flex gap-1 text-gray-800 hover:underline text-sm rounded-md focus:outline-none focus:ring-0" href="{{ route('login') }}">
                                <x-heroicon-o-arrow-right-start-on-rectangle class="w-5 h-5" />
                                <span>
                                    {{ __('Log in') }}
                                </span>
                            </a>
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
