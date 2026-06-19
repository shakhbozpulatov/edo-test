<!DOCTYPE html>
<html lang="uz">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kirish — EDO Elektron Hujjat Aylanishi</title>
    <!-- Google Fonts Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/auth/login.css') }}">
</head>
<body>
    <div class="left-panel">
        <div class="left-content">
            <div class="logo-emblem">
                <!-- Golden Coat of Arms of Uzbekistan -->
                <svg viewBox="0 0 100 100" fill="none" stroke="#D4AF37" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" style="width:76px;height:76px;display:block;">
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
            <h2>O'zbekiston Respublikasi</h2>
            <h1>ELEKTRON HUJJAT AYLANISHI TIZIMI</h1>
            <p>Tashkilotlararo tezkor, xavfsiz va shaffof elektron hujjat almashinuvi davlat platformasi</p>

            <div class="features">
                <div class="feature">
                    <svg class="feature-icon" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    <span>Rasmiy hujjatlarni shablon asosida yaratish va elektron raqamli imzo bilan tasdiqlash</span>
                </div>
                <div class="feature">
                    <svg class="feature-icon" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
                    <span>Unikal QR kod orqali hujjat haqiqiyligini real vaqt rejimida tekshirish</span>
                </div>
                <div class="feature">
                    <svg class="feature-icon" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    <span>Vazirlik, idora va hokimliklar o'rtasida hujjatlarni tezkor jo'natish va qabul qilish</span>
                </div>
            </div>
        </div>
    </div>

    <div class="right-panel">
        <div class="auth-box">
            <h2>Tizimga kirish</h2>
            <p class="subtitle">Foydalanuvchi hisob ma'lumotlarini kiriting</p>

            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="form-group">
                    <label for="email">E-mail Manzil</label>
                    <div class="input-wrap">
                        <span class="input-icon">
                            <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/></svg>
                        </span>
                        <input type="email" id="email" name="email" value="{{ old('email') }}"
                               placeholder="admin@edo.uz" class="{{ $errors->has('email') ? 'is-invalid' : '' }}"
                               required autofocus>
                    </div>
                    @error('email')
                        <div class="error-msg">
                            <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><path stroke-linecap="round" d="M12 8v4m0 4h.01"/></svg>
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password">Maxfiy Parol</label>
                    <div class="input-wrap">
                        <span class="input-icon">
                            <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                        </span>
                        <input type="password" id="password" name="password"
                               placeholder="••••••••" required>
                    </div>
                    @error('password')
                        <div class="error-msg">
                            <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><path stroke-linecap="round" d="M12 8v4m0 4h.01"/></svg>
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="row">
                    <label class="remember">
                        <input type="checkbox" name="remember">
                        Meni tizimda eslab qol
                    </label>
                </div>

                <button type="submit" class="btn-submit">
                    <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/></svg>
                    Kirish
                </button>
            </form>

            <div class="auth-link">
                Tizimda yangimisiz? <a href="{{ route('register') }}">Ro'yxatdan o'tish</a>
            </div>

            <div class="demo-hint">
                <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" style="flex-shrink:0;margin-top:2px;"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <div>
                    <strong>Demo foydalanuvchi:</strong>
                    Login: admin@edo.uz <br> Parol: password
                </div>
            </div>
        </div>
    </div>
</body>
</html>
