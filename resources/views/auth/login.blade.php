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
            --gray-900:   #0B132B;
        }

        body {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            min-height: 100vh;
            display: flex;
            background: var(--gray-50);
            position: relative;
        }



        /* Left decorative panel */
        .left-panel {
            flex: 0 0 500px;
            background: linear-gradient(135deg, var(--primary) 0%, var(--indigo) 50%, var(--gray-900) 100%);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 48px;
            position: relative;
            overflow: hidden;
            border-right: 1px solid rgba(255,255,255,.1);
        }

        .left-panel::before {
            content: '';
            position: absolute;
            width: 600px;
            height: 600px;
            background: radial-gradient(circle, rgba(212,175,55,0.05) 0%, rgba(255,255,255,0) 70%);
            top: -150px;
            left: -150px;
        }

        .left-content { position: relative; z-index: 1; text-align: center; color: #fff; width: 100%; }

        .logo-emblem {
            width: 110px;
            height: 110px;
            background: rgba(255,255,255,.05);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 28px;
            backdrop-filter: blur(12px);
            border: 1.5px solid rgba(212,175,55,.4);
            box-shadow: 0 10px 25px rgba(0,0,0,.2);
        }

        .left-content h2 { font-size: 13px; font-weight: 700; color: var(--gold); letter-spacing: 3px; text-transform: uppercase; margin-bottom: 6px; }
        .left-content h1 { font-size: 24px; font-weight: 800; color: #fff; letter-spacing: 1px; line-height: 1.3; margin-bottom: 12px; }
        .left-content p  { font-size: 13.5px; color: rgba(255,255,255,.7); line-height: 1.6; max-width: 320px; margin: 0 auto 40px; }

        .features { display: flex; flex-direction: column; gap: 16px; text-align: left; max-width: 340px; margin: 0 auto; background: rgba(255,255,255,0.03); padding: 20px; border-radius: 12px; border: 1px solid rgba(255,255,255,0.07); }
        .feature { display: flex; align-items: flex-start; gap: 12px; font-size: 13.5px; color: rgba(255,255,255,.85); line-height: 1.4; }
        .feature-icon {
            color: var(--gold);
            flex-shrink: 0;
            margin-top: 1px;
        }

        /* Right form panel */
        .right-panel {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 48px 40px;
        }

        .auth-box {
            width: 100%;
            max-width: 400px;
            background: #fff;
            padding: 36px;
            border-radius: 16px;
            border: 1px solid var(--gray-200);
            box-shadow: 0 10px 30px rgba(15,44,89,.04);
        }

        .auth-box h2 { font-size: 24px; font-weight: 800; color: var(--primary); margin-bottom: 6px; }
        .auth-box .subtitle { font-size: 13px; color: var(--gray-500); margin-bottom: 28px; }

        .form-group { margin-bottom: 18px; }

        label {
            display: block;
            font-size: 12px;
            font-weight: 700;
            color: var(--gray-700);
            text-transform: uppercase;
            letter-spacing: .05em;
            margin-bottom: 6px;
        }

        .input-wrap {
            position: relative;
        }

        .input-icon {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--gray-500);
            pointer-events: none;
            display: flex;
            align-items: center;
        }

        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 12px 14px 12px 42px;
            border: 1.5px solid var(--gray-200);
            border-radius: 8px;
            font-size: 13.5px;
            color: var(--gray-800);
            background: #fff;
            outline: none;
            transition: all .2s;
        }

        input:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(15,44,89,.1);
        }

        input.is-invalid { border-color: var(--red); }
        input.is-invalid:focus { box-shadow: 0 0 0 3px rgba(239,68,68,.1); }

        .error-msg { font-size: 12px; color: var(--red); margin-top: 5px; display: flex; align-items: center; gap: 4px; }

        .row { display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px; }
        .remember { display: flex; align-items: center; gap: 8px; font-size: 13px; color: var(--gray-700); cursor: pointer; user-select: none; }

        input[type="checkbox"] {
            width: 16px;
            height: 16px;
            accent-color: var(--primary);
            cursor: pointer;
        }

        .btn-submit {
            width: 100%;
            padding: 13px;
            background: linear-gradient(135deg, var(--primary) 0%, var(--indigo) 100%);
            color: #fff;
            border: none;
            border-radius: 8px;
            font-size: 14.5px;
            font-weight: 700;
            cursor: pointer;
            transition: all .2s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            letter-spacing: .02em;
            box-shadow: 0 4px 12px rgba(15,44,89,.2);
        }

        .btn-submit:hover  { opacity: .95; transform: translateY(-1px); box-shadow: 0 6px 16px rgba(15,44,89,.25); }
        .btn-submit:active { transform: translateY(0); }

        .auth-link { text-align: center; margin-top: 24px; font-size: 13px; color: var(--gray-500); }
        .auth-link a { color: var(--indigo); text-decoration: none; font-weight: 700; }
        .auth-link a:hover { text-decoration: underline; }

        .demo-hint {
            margin-top: 28px;
            padding: 12px 16px;
            background: var(--gold-lt);
            border: 1px solid rgba(212,175,55,.3);
            border-radius: 8px;
            font-size: 12px;
            color: #856404;
            display: flex;
            align-items: flex-start;
            gap: 8px;
        }
        .demo-hint strong { font-weight: 700; display: block; margin-bottom: 2px; }

        @media (max-width: 900px) {
            .left-panel { display: none; }
        }
    </style>
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
