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
            <div class="row">
                <div class="col-md-12">
                    <h4 class="text-4xl font-bold mb-8 text-center">{{ $client_settings['name'] }}</h4>
                </div>
                <div class="col-md-12">
                    <p class="text-lg mb-8 text-center">Password reset link has been sent to your email address {{ $email }}.</p>
                </div>
                <div class="col-md-12">
                    <button class="back_to_login_btn"><a href="{{ route('index') }}" class="back_to_login">Back to Login</a></button>
                </div>
            </div>
        </div>
    </body>
</html>