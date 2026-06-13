<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
        <title>{{ $title ?? config('app.name') }}</title>

        @livewireStyles
        <script src="https://cdn.tailwindcss.com"></script>
        
        @livewireScripts 
    </head>
    <body>
        {{ $slot }}


        <footer style="
        text-align: center; 
        padding: 20px; 
        margin-top: 50px; 
        border-top: 1px solid #eee; 
        color: #666; 
        font-size: 14px;
    ">
        <p><strong>QuickSpace POS</strong> - Developed By <strong>Kamrul Hasan</strong></p>
        <p style="font-size: 12px; opacity: 0.8;">&copy; {{ date('Y') }} All Rights Reserved</p>
    </footer>

    @livewireScripts
    @stack('scripts')
    </body>
</html>

