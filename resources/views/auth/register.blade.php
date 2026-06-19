<!DOCTYPE html>
<html lang="uz">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ro'yxatdan o'tish — EDO Elektron Hujjat Aylanishi</title>
    <!-- Google Fonts Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/auth/register.css') }}">
</head>
<body>
    <div class="card">
        <div class="card-top">
            <div class="logo-emblem">
                <!-- Golden Coat of Arms of Uzbekistan -->
                <svg viewBox="0 0 100 100" fill="none" stroke="#D4AF37" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" style="width:52px;height:52px;display:block;">
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
            <h1>DAVLAT EDO</h1>
            <p>Elektron Hujjat Aylanishi Davlat Platformasi</p>
        </div>

        <h2>Yangi hisob yaratish</h2>

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div class="grid-2">
                <div class="form-group" style="grid-column: 1/-1;">
                    <label>To'liq Ism (F.I.Sh.) *</label>
                    <div class="input-wrap">
                        <span class="input-icon"><svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg></span>
                        <input type="text" name="name" value="{{ old('name') }}"
                               placeholder="Familiya Ism Otasining ismi"
                               class="{{ $errors->has('name') ? 'is-invalid' : '' }}" required autofocus>
                    </div>
                    @error('name')
                        <div class="error-msg">
                            <svg width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path stroke-linecap="round" d="M12 8v4m0 4h.01"/></svg>
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="form-group" style="grid-column: 1/-1;">
                    <label>E-mail Manzil *</label>
                    <div class="input-wrap">
                        <span class="input-icon"><svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg></span>
                        <input type="email" name="email" value="{{ old('email') }}"
                               placeholder="email@misol.uz"
                               class="{{ $errors->has('email') ? 'is-invalid' : '' }}" required>
                    </div>
                    @error('email')
                        <div class="error-msg">
                            <svg width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path stroke-linecap="round" d="M12 8v4m0 4h.01"/></svg>
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="form-group">
                    <label>Lavozim <span class="optional-badge">(ixtiyoriy)</span></label>
                    <div class="input-wrap">
                        <span class="input-icon"><svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg></span>
                        <input type="text" name="position" value="{{ old('position') }}" placeholder="Bosh mutaxassis">
                    </div>
                </div>

                <div class="form-group">
                    <label>Tashkilot <span class="optional-badge">(ixtiyoriy)</span></label>
                    <div class="input-wrap">
                        <span class="input-icon"><svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg></span>
                        <input type="text" name="organization" value="{{ old('organization') }}" placeholder="Tashkilot nomi">
                    </div>
                </div>

                <div class="form-group">
                    <label>Parol *</label>
                    <div class="input-wrap">
                        <span class="input-icon"><svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg></span>
                        <input type="password" name="password" placeholder="Kamida 6 belgi" class="{{ $errors->has('password') ? 'is-invalid' : '' }}" required>
                    </div>
                    @error('password')
                        <div class="error-msg">
                            <svg width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path stroke-linecap="round" d="M12 8v4m0 4h.01"/></svg>
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="form-group">
                    <label>Tasdiqlash *</label>
                    <div class="input-wrap">
                        <span class="input-icon"><svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg></span>
                        <input type="password" name="password_confirmation" placeholder="Qayta kiriting" required>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn-submit">
                <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
                Ro'yxatdan o'tish
            </button>
        </form>

        <div class="auth-link">
            Hisobingiz bormi? <a href="{{ route('login') }}">Kirish</a>
        </div>
    </div>
</body>
</html>
