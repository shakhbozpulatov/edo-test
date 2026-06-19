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
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --primary:    #0F2C59; /* Navy */
            --primary-dk: #071B38;
            --primary-lt: #F0F4F8;
            --indigo:     #1E3A8A; /* Dark blue */
            --gold:       #D4AF37; /* Gold */
            --gold-lt:    #FDF6E2;
            --sidebar-w:  270px;
            --topbar-h:   64px;
            --gray-50:    #F4F6F9;
            --gray-100:   #EAEFF4;
            --gray-200:   #DDE3EA;
            --gray-300:   #CBD5E1;
            --gray-400:   #94A3B8;
            --gray-500:   #64748B;
            --gray-600:   #475569;
            --gray-700:   #334155;
            --gray-800:   #1E293B;
            --gray-900:   #0B132B; /* Dark navy for sidebar */
            --green:      #10B981;
            --green-lt:   #D1FAE5;
            --red:        #EF4444;
            --red-lt:     #FEE2E2;
            --yellow:     #F59E0B;
            --yellow-lt:  #FEF3C7;
            --shadow-sm: 0 1px 3px rgba(15, 44, 89, .05), 0 1px 2px rgba(15, 44, 89, .03);
            --shadow-md: 0 4px 14px rgba(15, 44, 89, .06), 0 2px 4px rgba(15, 44, 89, .03);
            --shadow-lg: 0 12px 30px rgba(15, 44, 89, .08), 0 4px 12px rgba(15, 44, 89, .04);
            --radius-sm: 8px;
            --radius-md: 12px;
            --radius-lg: 16px;
            --base-font-size: 13.5px;
        }

        body {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            background: var(--gray-50);
            color: var(--gray-800);
            display: flex;
            min-height: 100vh;
            line-height: 1.5;
            font-size: var(--base-font-size);
            transition: background 0.3s, color 0.3s;
        }

        /* ─── SIDEBAR ─── */
        .sidebar {
            width: var(--sidebar-w);
            background: var(--gray-900);
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0; left: 0; bottom: 0;
            z-index: 200;
            overflow: hidden;
        }

        .sidebar-logo {
            padding: 20px 20px 16px;
            display: flex;
            align-items: center;
            gap: 12px;
            border-bottom: 1px solid rgba(255,255,255,.07);
        }

        .logo-mark {
            width: 38px;
            height: 38px;
            background: linear-gradient(135deg, var(--primary), var(--indigo));
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .logo-mark svg { width: 22px; height: 22px; }
        .logo-text h1 { font-size: 18px; font-weight: 800; color: #fff; letter-spacing: 2px; }
        .logo-text span { font-size: 10px; color: rgba(255,255,255,.4); letter-spacing: .02em; }

        .sidebar-new-btn {
            margin: 14px 14px 8px;
            background: linear-gradient(135deg, var(--primary) 0%, var(--indigo) 100%);
            color: #fff;
            border: none;
            border-radius: var(--radius-sm);
            padding: 11px 14px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
            transition: opacity .15s, transform .1s;
            box-shadow: 0 4px 12px rgba(37,99,235,.35);
        }

        .sidebar-new-btn:hover  { opacity: .92; color: #fff; }
        .sidebar-new-btn:active { transform: scale(.98); }
        .sidebar-new-btn svg { width: 16px; height: 16px; }

        .nav-section {
            padding: 20px 14px 6px;
        }

        .nav-label {
            font-size: 10px;
            font-weight: 700;
            color: rgba(255,255,255,.25);
            text-transform: uppercase;
            letter-spacing: .1em;
            padding: 0 6px;
            margin-bottom: 6px;
        }

        .nav-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 12px;
            border-radius: var(--radius-sm);
            color: rgba(255,255,255,.55);
            text-decoration: none;
            font-size: 13.5px;
            font-weight: 500;
            margin-bottom: 2px;
            transition: background .15s, color .15s;
            position: relative;
        }

        .nav-item svg { width: 17px; height: 17px; flex-shrink: 0; }
        .nav-item:hover  { background: rgba(255,255,255,.07); color: rgba(255,255,255,.9); }

        .nav-item.active {
            background: rgba(37,99,235,.25);
            color: #fff;
        }

        .nav-item.active::before {
            content: '';
            position: absolute;
            left: 0; top: 6px; bottom: 6px;
            width: 3px;
            background: var(--primary);
            border-radius: 0 3px 3px 0;
        }

        .nav-badge {
            margin-left: auto;
            background: var(--primary);
            color: #fff;
            font-size: 10px;
            font-weight: 700;
            padding: 2px 7px;
            border-radius: 10px;
            min-width: 20px;
            text-align: center;
        }

        .sidebar-bottom {
            margin-top: auto;
            padding: 12px 14px;
            border-top: 1px solid rgba(255,255,255,.07);
        }

        .user-card {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px;
            border-radius: var(--radius-sm);
            transition: background .15s;
        }

        .user-card:hover { background: rgba(255,255,255,.07); }

        .user-avatar {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            background: linear-gradient(135deg, var(--primary), var(--indigo));
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 13px;
            font-weight: 700;
            flex-shrink: 0;
        }

        .user-info { min-width: 0; flex: 1; }
        .user-name { font-size: 13px; font-weight: 600; color: #fff; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .user-role { font-size: 11px; color: rgba(255,255,255,.35); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }

        .logout-btn {
            background: none;
            border: none;
            cursor: pointer;
            color: rgba(255,255,255,.3);
            padding: 6px;
            border-radius: 6px;
            transition: color .15s, background .15s;
            flex-shrink: 0;
        }

        .logout-btn:hover { color: var(--red); background: rgba(239,68,68,.1); }
        .logout-btn svg { width: 16px; height: 16px; }

        /* ─── MAIN ─── */
        .main {
            margin-left: var(--sidebar-w);
            flex: 1;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .topbar {
            background: #fff;
            border-bottom: 1px solid var(--gray-200);
            padding: 0 24px;
            height: var(--topbar-h);
            display: flex;
            align-items: center;
            gap: 12px;
            position: sticky;
            top: 0;
            z-index: 100;
            box-shadow: var(--shadow-sm);
        }

        .topbar-left { flex: 1; min-width: 0; }
        .topbar-breadcrumb { font-size: 12px; color: var(--gray-400); margin-bottom: 1px; }
        .topbar-breadcrumb a { color: var(--primary); text-decoration: none; font-weight: 500; }
        .topbar-breadcrumb a:hover { text-decoration: underline; }
        .topbar-title { font-size: 17px; font-weight: 700; color: var(--gray-900); }
        .topbar-actions { display: flex; align-items: center; gap: 8px; }

        .page-content {
            padding: 24px;
            flex: 1;
        }

        /* ─── ALERTS ─── */
        .alert {
            padding: 13px 16px;
            border-radius: var(--radius-sm);
            font-size: 13.5px;
            margin-bottom: 16px;
            display: flex;
            align-items: flex-start;
            gap: 10px;
            animation: slideDown .25s ease;
        }

        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-8px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .alert-success { background: var(--green-lt); border: 1px solid #a7f3d0; color: #065f46; }
        .alert-error   { background: var(--red-lt);   border: 1px solid #fca5a5; color: #991b1b; }
        .alert-warning { background: var(--yellow-lt); border: 1px solid #fde68a; color: #92400e; }
        .alert svg { flex-shrink: 0; }

        /* ─── BUTTONS ─── */
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 9px 16px;
            border-radius: var(--radius-sm);
            font-size: 13.5px;
            font-weight: 600;
            cursor: pointer;
            border: none;
            text-decoration: none;
            transition: all .15s;
            white-space: nowrap;
            line-height: 1;
        }

        .btn svg { width: 15px; height: 15px; }
        .btn-sm  { padding: 6px 12px; font-size: 12px; }
        .btn-sm svg { width: 13px; height: 13px; }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary) 0%, var(--indigo) 100%);
            color: #fff;
            box-shadow: 0 2px 8px rgba(37,99,235,.3);
        }
        .btn-primary:hover  { opacity: .9; color: #fff; box-shadow: 0 4px 14px rgba(37,99,235,.4); }
        .btn-primary:active { transform: scale(.98); }

        .btn-secondary {
            background: #fff;
            color: var(--gray-700);
            border: 1.5px solid var(--gray-200);
            box-shadow: var(--shadow-sm);
        }
        .btn-secondary:hover { background: var(--gray-50); border-color: var(--gray-300); color: var(--gray-800); }

        .btn-danger  { background: var(--red); color: #fff; }
        .btn-danger:hover  { background: #dc2626; color: #fff; box-shadow: 0 2px 8px rgba(239,68,68,.3); }

        .btn-success { background: var(--green); color: #fff; }
        .btn-success:hover { background: #059669; color: #fff; box-shadow: 0 2px 8px rgba(16,185,129,.3); }

        .btn-ghost { background: none; border: none; color: var(--gray-500); padding: 7px; border-radius: var(--radius-sm); }
        .btn-ghost:hover { background: var(--gray-100); color: var(--gray-700); }

        /* ─── BADGES ─── */
        .badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 3px 9px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: .04em;
        }

        .badge::before {
            content: '';
            width: 5px;
            height: 5px;
            border-radius: 50%;
            background: currentColor;
        }

        .badge-warning { background: var(--yellow-lt); color: #92400e; }
        .badge-success { background: var(--green-lt);  color: #065f46; }
        .badge-danger  { background: var(--red-lt);    color: #991b1b; }
        .badge-gray    { background: var(--gray-100);  color: var(--gray-600); }

        /* ─── CARDS ─── */
        .card {
            background: #fff;
            border-radius: var(--radius-md);
            border: 1px solid var(--gray-200);
            overflow: hidden;
            box-shadow: var(--shadow-sm);
        }

        .card-header {
            padding: 16px 20px;
            border-bottom: 1px solid var(--gray-100);
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: var(--gray-50);
        }

        .card-header h2 { font-size: 14px; font-weight: 700; color: var(--gray-800); }
        .card-body { padding: 20px; }

        /* ─── FORMS ─── */
        .form-group { margin-bottom: 16px; }

        .form-label {
            display: block;
            font-size: 12px;
            font-weight: 700;
            color: var(--gray-600);
            text-transform: uppercase;
            letter-spacing: .06em;
            margin-bottom: 6px;
        }

        .form-label .req { color: var(--red); margin-left: 2px; }

        .form-control {
            width: 100%;
            padding: 10px 12px;
            border: 1.5px solid var(--gray-200);
            border-radius: var(--radius-sm);
            font-size: 13.5px;
            color: var(--gray-800);
            background: #fff;
            transition: border-color .2s, box-shadow .2s;
            outline: none;
        }

        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(37,99,235,.1);
        }

        .form-control.is-invalid { border-color: var(--red); }
        textarea.form-control { resize: vertical; min-height: 80px; }

        select.form-control {
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='14' height='14' viewBox='0 0 24 24' fill='none' stroke='%2394a3b8' stroke-width='2'%3E%3Cpath d='M6 9l6 6 6-6'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 10px center;
            padding-right: 34px;
            cursor: pointer;
        }

        .invalid-feedback { font-size: 12px; color: var(--red); margin-top: 5px; display: flex; align-items: center; gap: 4px; }

        /* ─── FILE CARD ─── */
        .file-card {
            border: 1.5px solid var(--gray-200);
            border-radius: var(--radius-sm);
            padding: 12px 14px;
            display: flex;
            align-items: center;
            gap: 12px;
            background: var(--gray-50);
            transition: border-color .15s;
        }

        .file-card:hover { border-color: var(--gray-300); }

        .file-icon {
            width: 38px;
            height: 38px;
            background: #dbeafe;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .file-icon svg { width: 20px; height: 20px; color: var(--primary); }
        .file-info { flex: 1; min-width: 0; }
        .file-name { font-size: 13px; font-weight: 600; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; color: var(--gray-800); }
        .file-meta { font-size: 11px; color: var(--gray-400); margin-top: 2px; }
        .file-actions { display: flex; gap: 6px; flex-shrink: 0; }

        /* ─── MODALS ─── */
        .modal-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(15,23,42,.55);
            z-index: 1000;
            align-items: center;
            justify-content: center;
            backdrop-filter: blur(2px);
            animation: fadeIn .2s ease;
        }

        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }

        .modal-overlay.open {
            display: flex;
        }

        .modal {
            background: #fff;
            border-radius: var(--radius-lg);
            width: 90%;
            max-width: 560px;
            max-height: 88vh;
            display: flex;
            flex-direction: column;
            box-shadow: var(--shadow-lg);
            animation: slideUp .25s cubic-bezier(.34,1.56,.64,1);
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(24px) scale(.97); }
            to   { opacity: 1; transform: translateY(0) scale(1); }
        }

        .modal-lg { max-width: 820px; }

        .modal-header {
            padding: 20px 24px 16px;
            border-bottom: 1px solid var(--gray-100);
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-shrink: 0;
        }

        .modal-header h3 { font-size: 16px; font-weight: 700; color: var(--gray-900); }

        .modal-close {
            background: var(--gray-100);
            border: none;
            cursor: pointer;
            color: var(--gray-500);
            border-radius: 8px;
            padding: 6px;
            transition: all .15s;
            display: flex;
        }
        .modal-close:hover { background: var(--gray-200); color: var(--gray-800); }
        .modal-close svg { width: 18px; height: 18px; }

        .modal-body   { padding: 20px 24px; overflow-y: auto; flex: 1; }
        .modal-footer { padding: 14px 24px; border-top: 1px solid var(--gray-100); display: flex; justify-content: flex-end; gap: 8px; flex-shrink: 0; background: var(--gray-50); border-radius: 0 0 var(--radius-lg) var(--radius-lg); }

        /* ─── TREE ─── */
        .tree-node { user-select: none; }
        .tree-node-row {
            display: flex;
            align-items: center;
            gap: 6px;
            padding: 6px 8px;
            border-radius: var(--radius-sm);
            cursor: pointer;
            font-size: 13px;
            transition: background .12s;
        }
        .tree-node-row:hover { background: var(--gray-50); }
        .tree-toggle {
            width: 18px; height: 18px;
            display: flex; align-items: center; justify-content: center;
            color: var(--gray-400);
            flex-shrink: 0;
            transition: transform .15s;
            border-radius: 4px;
        }
        .tree-toggle:hover { background: var(--gray-100); color: var(--gray-600); }
        .tree-toggle.open  { transform: rotate(90deg); }
        .tree-toggle svg   { width: 12px; height: 12px; }
        .tree-toggle-placeholder { width: 18px; flex-shrink: 0; }
        .tree-children { padding-left: 26px; display: none; }
        .tree-children.open { display: block; }
        .tree-category { font-weight: 700; color: var(--gray-700); }

        /* ─── QR ─── */
        .qr-img {
            cursor: grab;
            max-width: 120px;
            border: 1px solid var(--gray-200);
            border-radius: 8px;
            user-select: none;
            transition: transform .15s, box-shadow .15s;
        }
        .qr-img:hover  { transform: scale(1.04); box-shadow: 0 4px 12px rgba(0,0,0,.12); }
        .qr-img:active { cursor: grabbing; }

        /* ─── UTILITIES ─── */
        .flex { display: flex; }
        .items-center { align-items: center; }
        .justify-between { justify-content: space-between; }
        .gap-2 { gap: 8px; }
        .gap-3 { gap: 12px; }
        .mt-2 { margin-top: 8px; }
        .mt-3 { margin-top: 12px; }
        .mt-4 { margin-top: 16px; }
        .mb-4 { margin-bottom: 16px; }
        .text-sm  { font-size: 13px; }
        .text-xs  { font-size: 11px; }
        .text-gray { color: var(--gray-500); }
        .text-primary { color: var(--primary); }
        .font-semibold { font-weight: 600; }
        .font-bold { font-weight: 700; }
        .w-full { width: 100%; }

        .grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }

        .divider { border: none; border-top: 1px solid var(--gray-200); margin: 16px 0; }

        /* ─── TOOLTIP ─── */
        [title] { position: relative; }

        /* ─── SCROLLBAR ─── */
        ::-webkit-scrollbar { width: 5px; height: 5px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: var(--gray-300); border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: var(--gray-400); }

        /* ─── RESPONSIVE ─── */
        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); transition: transform .25s; }
            .sidebar.open { transform: translateX(0); }
            .main { margin-left: 0; }
            .grid-2 { grid-template-columns: 1fr; }
        }
    </style>
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

            <a href="{{ route('documents.create') }}" class="nav-item {{ request()->routeIs('documents.create') ? 'active' : '' }}">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Yangi hujjat
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
