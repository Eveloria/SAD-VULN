<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') · EventPass</title>
    <link rel="stylesheet" href="{{ asset('eventpass.css') }}">
</head>
<body>
<header class="topbar">
    <div class="topbar-inner">
        <a class="brand" href="{{ route('dashboard') }}"><span class="brand-mark">E</span><span>EventPass<small>Ticket & Exhibition Desk</small></span></a>
        <nav class="nav-links" aria-label="Navigasi utama">
            <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">Dashboard</a>
            <a href="{{ route('tickets.index') }}" class="{{ request()->routeIs('tickets.*') ? 'active' : '' }}">Tiket</a>
            <a href="{{ route('validation.form') }}" class="{{ request()->routeIs('validation.*') ? 'active' : '' }}">Validasi</a>
            <a href="{{ route('gateway.index') }}" class="{{ request()->routeIs('gateway.*') ? 'active' : '' }}">Gateway</a>
            @if (app()->environment('local'))<a href="{{ route('lab.index') }}" class="{{ request()->routeIs('lab.*') ? 'active' : '' }}">Security Lab</a>@endif
        </nav>
    </div>
</header>
<main class="container">
    @if (session('success')) <div class="alert success" role="status">{{ session('success') }}</div> @endif
    @if (session('error')) <div class="alert error" role="alert">{{ session('error') }}</div> @endif
    @if ($errors->any())
        <div class="alert error"><strong>Periksa kembali input:</strong><ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
    @endif
    @yield('content')
</main>
<footer class="footer"><div class="container footer-inner"><span>EventPass · Local learning project</span><span>Payment provider and security lab are simulated</span></div></footer>
</body>
</html>
