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
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --primary:    #0F2C59; /* Navy */
            --primary-dk: #071B38;
            --primary-lt: #F0F4F8;
            --indigo:     #1E3A8A; /* Dark blue */
            --gold:       #D4AF37; /* Gold */
            --red:        #EF4444;
            --gray-50:    #F4F6F9;
            --gray-100:   #EAEFF4;
            --gray-200:   #DDE3EA;
            --gray-300:   #CBD5E1;
            --gray-500:   #64748B;
            --gray-600:   #475569;
            --gray-700:   #334155;
            --gray-800:   #1E293B;
        }

        body {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--gray-50);
            padding: 24px;
            position: relative;
        }



        .card {
            background: #fff;
            border-radius: 16px;
            padding: 40px;
            width: 100%;
            max-width: 520px;
            border: 1px solid var(--gray-200);
            box-shadow: 0 12px 40px rgba(15,44,89,.06);
        }

        .card-top { text-align: center; margin-bottom: 24px; }
        
        .logo-emblem {
            width: 80px;
            height: 80px;
            background: var(--primary-lt);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 16px;
            border: 1.5px solid rgba(212,175,55,.4);
        }

        .card-top h1 { font-size: 22px; font-weight: 800; color: var(--primary); letter-spacing: 1px; margin-bottom: 6px; }
        .card-top p  { font-size: 13px; color: var(--gray-500); }

        h2 { font-size: 15px; font-weight: 700; color: var(--primary); margin-bottom: 20px; padding-bottom: 10px; border-bottom: 1.5px solid var(--gray-100); text-transform: uppercase; letter-spacing: 0.05em; }

        .grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }
        .form-group { margin-bottom: 16px; }
        label { display: block; font-size: 11.5px; font-weight: 700; color: var(--gray-700); text-transform: uppercase; letter-spacing: .05em; margin-bottom: 6px; }

        .input-wrap { position: relative; }
        .input-icon { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: var(--gray-500); pointer-events: none; display: flex; align-items: center; }
        .input-icon svg { width: 16px; height: 16px; }

        input {
            width: 100%;
            padding: 10px 12px 10px 38px;
            border: 1.5px solid var(--gray-200);
            border-radius: 8px;
            font-size: 13.5px;
            color: var(--gray-800);
            background: #fff;
            outline: none;
            transition: all .2s;
        }
        input:focus { border-color: var(--primary); box-shadow: 0 0 0 3px rgba(15,44,89,.1); }
        input.is-invalid { border-color: var(--red); }
        .optional-badge { font-size: 10px; font-weight: 500; color: var(--gray-500); text-transform: none; letter-spacing: 0; margin-left: 4px; }
        .error-msg { font-size: 11px; color: var(--red); margin-top: 4px; display: flex; align-items: center; gap: 4px; }

        .btn-submit {
            width: 100%;
            padding: 12px;
            background: linear-gradient(135deg, var(--primary) 0%, var(--indigo) 100%);
            color: #fff;
            border: none;
            border-radius: 8px;
            font-size: 14.5px;
            font-weight: 700;
            cursor: pointer;
            margin-top: 8px;
            transition: all .2s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            box-shadow: 0 4px 12px rgba(15,44,89,.15);
        }
        .btn-submit:hover { opacity: .95; transform: translateY(-1px); box-shadow: 0 6px 16px rgba(15,44,89,.2); }
        .btn-submit:active { transform: translateY(0); }

        .auth-link { text-align: center; margin-top: 20px; font-size: 13px; color: var(--gray-500); }
        .auth-link a { color: var(--indigo); text-decoration: none; font-weight: 700; }
        .auth-link a:hover { text-decoration: underline; }

        @media (max-width: 580px) { .grid-2 { grid-template-columns: 1fr; } }
    </style>
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
