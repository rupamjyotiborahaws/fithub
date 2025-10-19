<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

        <!-- Styles / Scripts -->
        @vite(['resources/css/app.css'])
    </head>
    <body>
        <div class="container-fluid flex items-center justify-center min-h-screen bg-gray-100">
            <div class="row flex bg-grey-200 p-8 rounded-lg shadow-lg w-full max-w-md">
                <div class="col-md-12">
                    <h4 class="text-4xl font-bold mb-8 text-center">{{ $client_settings['name'] }}</h4>
                </div>
                <div class="col-md-12">
                    @if (session('error'))
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                            {{ session('error') }}
                        </div>
                    @endif
                </div>
                <div class="col-md-12">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        <div class="mb-4">
                            <input type="email" id="email" name="email" required class="login_input" placeholder="Email">
                        </div>
                        <div class="mb-4">
                            <input type="password" id="password" name="password" required class="login_input" placeholder="Password">
                        </div>
                        <button type="submit" class="login_btn">Login</button>
                    </form>
                </div>
            </div>
        </div>
    </body>
</html>
