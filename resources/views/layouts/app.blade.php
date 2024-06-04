<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8" />
        <meta
            name="viewport"
            content="width=device-width, initial-scale=1"
        />
        <meta
            name="csrf-token"
            content="{{ csrf_token() }}"
        />

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Web App Manifest -->
        <link
            rel="manifest"
            href="manifest.json"
        />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-gray-100 font-sans text-gray-900 antialiased dark:bg-gray-900 dark:text-gray-100">
        {{ $slot }}
    </body>
</html>
