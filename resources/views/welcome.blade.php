<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="darkMode()" :class="{ 'dark': isDark }" x-init="init()">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>CRTS</title>

    <link rel="icon" href="{{ asset('images/IcoTranscoLogo.ico')}}" type="image/x-icon">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Styles / Scripts -->
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else

    @endif
</head>

<body class="font-sans antialiased dark:bg-black dark:text-white/50">
    <div class="min-h-screen grid grid-cols-1 lg:grid-cols-2 bg-gray-50 text-gray-800 dark:bg-stone-800 dark:text-white">
        {{-- Left Side - Login Form --}}
        <div class="flex flex-col justify-center px-8 lg:px-24 py-12">
            <div class="mb-6">
                <img src="{{ asset('images/TranscoLogo.png') }}" alt="Logo" class="w-32">
            </div>

            <h2 class="text-2xl font-semibold mb-6">
                Welcome to
                <span class="bg-gradient-to-r from-green-400 to-blue-500 bg-clip-text text-transparent font-extrabold">
                    Trans<span class="uppercase">c</span>o
                </span>, Log in to your account
            </h2>

            <x-auth-session-status class="mb-4" :status="session('status')" />

            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf
                <!-- Email Address -->
                <div>
                    <label for="email" class="block text-sm font-medium">Email</label>
                    <x-text-input placeholder="Ex. jpdelacruz@transco.ph" id="email" class="mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <!-- Password -->
                <div class="relative">
                    <label for="password" class="block text-sm font-medium">Password</label>
                    <x-text-input id="password" placeholder="Password" class="mt-1 w-full pr-12" type="password" name="password" required autocomplete="current-password" />
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />

                    <div class="flex items-center mt-2">
                        <input type="checkbox" id="show-password" onclick="togglePassword()" class="mr-2">
                        <label for="show-password" class="text-sm">Show Password</label>
                    </div>
                </div>

                <x-green-button id="submitButton" class="w-full flex items-center justify-center">
                    {{ __('Log in') }}
                </x-green-button>
            </form>
        </div>


        <div class="hidden lg:flex flex-col justify-center items-center text-white px-12 bg-cover bg-center"
            style="background-image: url('{{ asset('images/background.svg') }}')">

            <div class="text-center w-full">
                <h3 class="text-xl font-bold mb-4">Welcome to TransCo Computerized Records Turn-Over and Inventory System</h3>
                <p class="text-sm">Efficiently track and manage your inventory with ease. Secure, simple, and fast.</p>
            </div>

            <div class="mt-10">
                <img src="{{ asset('images/undraw_transfer-files_anat.svg') }}" alt="Analytics" class="w-72"> {{-- keeps spacing/layout --}}
            </div>
        </div>

    </div>
</body>

<script>
    function togglePassword() {
        let passwordInput = document.getElementById('password');
        let checkbox = document.getElementById('show-password');

        passwordInput.type = checkbox.checked ? "text" : "password";
    }
</script>
<script src="{{ asset('js/disabledbutton.js') }}"></script>

</html>