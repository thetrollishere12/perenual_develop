<x-guest-layout>
    <x-jet-authentication-card>
        <x-slot name="logo">
            <x-jet-authentication-card-logo />
        </x-slot>

        <x-jet-validation-errors class="mb-4" />

        @if (session('status'))
            <div class="mb-4 font-medium text-sm text-green-600">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div>
                <x-jet-label for="email" value="{{ __('Email') }}" />
                <x-jet-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus />
            </div>

            <div class="mt-4">
                <x-jet-label for="password" value="{{ __('Password') }}" />
                <x-jet-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="current-password" />
            </div>

            <div class="block mt-4">
                <label for="remember_me" class="flex items-center">
                    <x-jet-checkbox id="remember_me" name="remember" />
                    <span class="ml-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
                </label>
            </div>

            <div class="flex items-center justify-end mt-4">
                @if (Route::has('password.request'))
                    <a class="underline text-sm text-gray-600 hover:text-gray-900" href="{{ route('password.request') }}">
                        {{ __('Forgot your password?') }}
                    </a>
                @endif

                <x-jet-button type="submit" class="ml-4 main-bg-c text-white">
                    {{ __('Log in') }}
                </x-jet-button>
            </div>

            <x-google-login-button>
                @if(isset($redirect))
                <x-slot name="link">{{ $redirect }}</x-slot>
                @endif
            </x-google-login-button>

            <x-facebook-login-button>
                @if(isset($redirect))
                <x-slot name="link">{{ $redirect }}</x-slot>
                @endif
            </x-facebook-login-button>

            <br>
            <div class="border-b border-gray-200"></div>
            <div class="text-center text-sm py-5 text-gray-600">Don't have an account?</div>
            <div class="text-center pb-2">
                <a href="{{ url('register?') }}@if(isset($redirect))link={{ $redirect }}@endif">
                    <button type="button" class="border rounded-full px-10 py-1 main-t-c text-sm border-purple-600 duration-100 focus:outline-none">Sign Up</button>
                </a>
            </div>

            @if(isset($redirect))
            <input type="hidden" name="link" value="{{ $redirect }}">
            @endif
            
        </form>
    </x-jet-authentication-card>

</x-guest-layout>