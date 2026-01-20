<x-guest-layout>
    <div class="flex items-center justify-center h-screen bg-gradient-to-r from-indigo-900 via-blue-800 to-sky-700">
        <div class="flex flex-col bg-white/20 backdrop-blur-lg border border-white/30 p-10 rounded-lg">
            <div class="flex flex-col gap-2 items-center mb-10 text-gray-200">
                <x-application-logo/>
                <span>CoreHelp</span>
            </div>

            <div class="mb-4 text-sm text-gray-200">
                {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
            </div>

            <!-- Session Status -->
            <x-auth-session-status class="mb-4" :status="session('status')" />

            <form method="POST" action="{{ route('password.email') }}">
                @csrf

                <!-- Email Address -->
                <div>
                    <x-input-label class="text-gray-200" for="email" :value="__('Email')" />
                    <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <div class="flex items-center justify-between mt-4">
                    <a href="{{ route('login') }}" class="flex items-center gap-1 text-gray-200 hover:text-white hover:underline">
                        <x-heroicon-o-arrow-uturn-left class="w-4 h-4" />
                        <span>Back</span>
                    </a>
                    <x-primary-button>
                        {{ __('Email Password Reset Link') }}
                    </x-primary-button>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>
