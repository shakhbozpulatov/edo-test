<!DOCTYPE html>
<html lang="uz">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'EDO') — Elektron hujjat aylanishi</title>
    <!-- Google Fonts Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @stack('styles')
</head>
<body>

<!-- ═══ SIDEBAR ═══ -->
<aside class="sidebar" id="sidebar">
    <div class="sidebar-logo">
        <div class="logo-mark" style="background: rgba(255,255,255,0.06); border: 1px solid rgba(212, 175, 55, 0.3);">
            <!-- Golden Coat of Arms of Uzbekistan -->
            <svg viewBox="0 0 100 100" fill="none" stroke="#D4AF37" stroke-width="3.5" stroke-linecap="round" stroke-linejoin="round" style="width:28px;height:28px;">
                <!-- Crescent & Star -->
                <path d="M50,12 L51.5,16 L55,16 L52,18 L53,21.5 L50,19.5 L47,21.5 L48,18 L45,16 L48.5,16 Z" fill="#D4AF37" stroke-width="0.5"/>
                <!-- Wreaths -->
                <path d="M35,68 C22,50 25,32 40,22 M65,68 C78,50 75,32 60,22" />
                <path d="M38,58 C28,45 32,35 42,28 M62,58 C72,45 68,35 58,28" />
                <!-- Humo Bird (Spread wings) -->
                <path d="M50,36 C42,42 32,54 28,64 C40,58 46,54 50,48 C54,54 60,58 72,64 C68,54 58,42 50,36 Z" fill="none" stroke="#D4AF37" />
                <path d="M50,48 L50,66" />
                <path d="M45,43 C48,46 52,46 55,43" />
                <!-- Ribbon at bottom -->
                <path d="M30,68 C40,73 60,73 70,68 C62,65 38,65 30,68 Z" fill="#009A49" stroke="#D4AF37" stroke-width="1.5"/>
            </svg>
        </div>
        <div class="logo-text">
            <h1 style="letter-spacing: 1px; font-size: 16px; font-weight: 800; color: #fff;">DAVLAT EDO</h1>
            <span style="font-size: 9.5px; color: rgba(255,255,255,.45); letter-spacing: 0;">Elektron Hujjat Aylanishi</span>
        </div>
    </div>

    <a href="{{ route('documents.create') }}" class="sidebar-new-btn">
        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
        </svg>
        Yangi hujjat
    </a>

    <nav>
        <div class="nav-section">
            <div class="nav-label">Hujjatlar</div>

            <a href="{{ route('documents.index') }}" class="nav-item {{ request()->routeIs('documents.index') ? 'active' : '' }}">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Mening hujjatlarim
            </a>

            <a href="{{ route('documents.signed') }}" class="nav-item {{ request()->routeIs('documents.signed') ? 'active' : '' }}">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                </svg>
                Imzolangan hujjatlar
            </a>
        </div>
    </nav>

    <div class="sidebar-bottom">
        <div class="user-card">
            <div class="user-avatar">{{ strtoupper(substr(Auth::user()->name, 0, 2)) }}</div>
            <div class="user-info">
                <div class="user-name">{{ Auth::user()->name }}</div>
                <div class="user-role">{{ Auth::user()->position ?? Auth::user()->email }}</div>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="logout-btn" title="Chiqish">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                </button>
            </form>
        </div>
    </div>
</aside>

<!-- ═══ MAIN ═══ -->
<div class="main">
    <!-- TOPBAR -->
    <header class="topbar">
        <button class="btn-ghost" id="menuToggle" style="display:none;" onclick="document.getElementById('sidebar').classList.toggle('open')">
            <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/></svg>
        </button>

        <div class="topbar-left">
            @hasSection('breadcrumb')
                <div class="topbar-breadcrumb">{!! $__env->yieldContent('breadcrumb') !!}</div>
            @endif
            <div class="topbar-title">@yield('page-title')</div>
        </div>

        <div class="topbar-actions" style="margin-left: auto;">
            @yield('topbar-actions')
        </div>
    </header>

    <!-- PAGE CONTENT -->
    <div class="page-content">
        @if(session('success'))
            <div class="alert alert-success" role="alert">
                <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                {{ session('success') }}
            </div>
        @endif

        @if(session('warning'))
            <div class="alert alert-warning" role="alert">
                <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="flex-shrink:0;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
                {{ session('warning') }}
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-error" role="alert">
                <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="flex-shrink:0;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
                <ul style="list-style:none;padding:0;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')
    </div>
</div>

<script>
window.CSRF_TOKEN = '{{ csrf_token() }}';

function openModal(id) {
    const el = document.getElementById(id);
    if (el) { el.classList.add('open'); document.body.style.overflow = 'hidden'; }
}

function closeModal(id) {
    const el = document.getElementById(id);
    if (el) { el.classList.remove('open'); document.body.style.overflow = ''; }
}

document.addEventListener('click', function(e) {
    if (e.target.classList.contains('modal-overlay')) {
        e.target.classList.remove('open');
        document.body.style.overflow = '';
    }
});

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        document.querySelectorAll('.modal-overlay.open').forEach(m => {
            m.classList.remove('open');
        });
        document.body.style.overflow = '';
    }
});

// Mobile menu
if (window.innerWidth <= 768) {
    document.getElementById('menuToggle').style.display = 'flex';
}
</script>

@stack('scripts')
</body>
</html>
