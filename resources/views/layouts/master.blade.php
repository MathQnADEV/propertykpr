<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name'))</title>

    {{-- Favicon --}}
    <link rel="icon" type="image/svg+xml" href="{{ asset('assets/images/logos/XPRO-Favicon.svg') }}">
    <link rel="apple-touch-icon" href="{{ asset('assets/images/logos/XPRO-Favicon.svg') }}">
    <meta property="og:site_name" content="{{ config('app.name') }}">
    <meta name="description" content="@yield('meta_description', 'Temukan hunian impian Anda dengan simulasi KPR terbaik di ' . config('app.name') . '.')">

    {{-- Open Graph --}}
    <meta property="og:title"       content="@yield('title', config('app.name'))">
    <meta property="og:description" content="@yield('meta_description', 'Temukan hunian impian Anda.')">
    <meta property="og:type"        content="website">
    <meta property="og:url"         content="{{ url()->current() }}">
    @hasSection('og_image')
        <meta property="og:image" content="@yield('og_image')">
    @endif

    {{-- Font: Poppins --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    {{-- Tailwind + App CSS via Vite --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('after-styles')
</head>

<body class="font-sans antialiased">

    @yield('content')

    @stack('after-scripts')

</body>

</html>
