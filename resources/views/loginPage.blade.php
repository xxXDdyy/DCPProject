<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login Page</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root{
            --ink: #0f172a;
            --muted: #475569;
            --brand: #1d4ed8;
            --brand-dark: #1e3a8a;
            --surface: rgba(255, 255, 255, 0.9);
            --line: rgba(148, 163, 184, 0.25);
            --ok-bg: #e0f2fe;
            --ok-text: #0c4a6e;
            --err-bg: #fee2e2;
            --err-text: #991b1b;
        }

        *{
            box-sizing: border-box;
        }

        body{
            min-height: 100vh;
            margin: 0;
            display: grid;
            place-items: center;
            padding: 24px;
            color: var(--ink);
            font-family: 'Plus Jakarta Sans', 'Segoe UI', Tahoma, sans-serif;
            background:
                radial-gradient(circle at 15% 18%, rgba(14, 165, 233, 0.34), transparent 34%),
                radial-gradient(circle at 86% 82%, rgba(37, 99, 235, 0.30), transparent 38%),
                linear-gradient(135deg, #eef2ff 0%, #e0f2fe 50%, #f8fafc 100%);
        }

        .login-shell{
            width: 100%;
            max-width: 980px;
            display: grid;
            grid-template-columns: 1.05fr 0.95fr;
            border-radius: 28px;
            overflow: hidden;
            border: 1px solid var(--line);
            background: var(--surface);
            backdrop-filter: blur(8px);
            box-shadow: 0 28px 60px rgba(15, 23, 42, 0.20);
        }

        .hero-panel{
            position: relative;
            padding: 52px 42px;
            background:
                linear-gradient(165deg, rgba(30, 58, 138, 0.95), rgba(30, 64, 175, 0.92) 45%, rgba(14, 116, 144, 0.9));
            color: #f8fafc;
        }

        .hero-panel::before{
            content: "";
            position: absolute;
            inset: auto -70px -80px auto;
            width: 260px;
            height: 260px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.12);
            filter: blur(2px);
        }

        .hero-badge{
            display: inline-block;
            padding: 8px 14px;
            border-radius: 999px;
            font-size: 0.75rem;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            background: rgba(255, 255, 255, 0.16);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .hero-title{
            margin: 20px 0 10px;
            font-size: 2.1rem;
            line-height: 1.18;
            font-weight: 800;
            max-width: 14ch;
        }

        .hero-copy{
            margin: 0 0 24px;
            font-size: 1rem;
            line-height: 1.75;
            color: rgba(248, 250, 252, 0.95);
            max-width: 40ch;
        }

        .hero-list{
            list-style: none;
            padding: 0;
            margin: 0;
            display: grid;
            gap: 10px;
        }

        .hero-list li{
            position: relative;
            padding-left: 24px;
            font-size: 0.95rem;
            color: rgba(248, 250, 252, 0.94);
        }

        .hero-list li::before{
            content: "";
            position: absolute;
            left: 0;
            top: 8px;
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background: #7dd3fc;
            box-shadow: 0 0 0 3px rgba(125, 211, 252, 0.26);
        }

        .form-panel{
            padding: 46px 40px;
            background: rgba(255, 255, 255, 0.84);
        }

        .form-title{
            margin: 0;
            font-size: 1.9rem;
            font-weight: 800;
            color: #0f172a;
        }

        .form-subtitle{
            margin: 10px 0 26px;
            color: var(--muted);
            line-height: 1.7;
            font-size: 0.95rem;
        }

        .notice{
            border-radius: 14px;
            padding: 14px 16px;
            font-size: 0.92rem;
            font-weight: 600;
            margin-bottom: 20px;
            border: 1px solid transparent;
        }

        .notice-info{
            background: var(--ok-bg);
            color: var(--ok-text);
            border-color: rgba(14, 165, 233, 0.35);
        }

        .notice-error{
            background: var(--err-bg);
            color: var(--err-text);
            border-color: rgba(239, 68, 68, 0.32);
        }

        .form-label{
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 8px;
        }

        .custom-input{
            border: 1px solid #cbd5e1;
            border-radius: 12px;
            padding: 13px 14px;
            font-size: 1rem;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }

        .custom-input:focus{
            border-color: #2563eb;
            box-shadow: 0 0 0 0.22rem rgba(37, 99, 235, 0.15);
        }

        .submit-btn{
            margin-top: 10px;
            border: 0;
            border-radius: 13px;
            padding: 13px 16px;
            font-size: 1.05rem;
            font-weight: 700;
            color: #fff;
            background: linear-gradient(135deg, var(--brand), var(--brand-dark));
            box-shadow: 0 12px 24px rgba(29, 78, 216, 0.28);
            transition: transform 0.2s ease, filter 0.2s ease;
        }

        .submit-btn:hover{
            filter: brightness(1.08);
            transform: translateY(-1px);
        }

        @media (max-width: 992px){
            .login-shell{
                grid-template-columns: 1fr;
                max-width: 540px;
            }

            .hero-panel{
                padding: 32px 30px;
            }

            .hero-title{
                font-size: 1.75rem;
                max-width: none;
            }

            .hero-list{
                display: none;
            }

            .form-panel{
                padding: 32px 30px;
            }
        }

        @media (max-width: 576px){
            body{
                padding: 14px;
            }

            .hero-panel,
            .form-panel{
                padding: 26px 20px;
            }
        }
    </style>
</head>
<body>

    <div class="login-shell">
        <aside class="hero-panel">
            <span class="hero-badge">AstigWebsite Portal</span>
            <h1 class="hero-title">Welcome Back</h1>
            <p class="hero-copy">
                Sign in to manage student records, degree details, and account activities in one secure dashboard.
            </p>
            <ul class="hero-list">
                <li>Fast access to student and degree modules</li>
                <li>Clean and organized table management</li>
                <li>Secure account session for your workflow</li>
            </ul>
        </aside>

        <section class="form-panel">
            <h2 class="form-title">Login Account</h2>
            <p class="form-subtitle">Please enter your username and password to continue.</p>

            @if (!empty($msg))
                @php
                    $lowerMsg = strtolower($msg);
                    $isError = str_contains($lowerMsg, 'failed') || str_contains($lowerMsg, 'please enter');
                @endphp
                <div class="notice {{ $isError ? 'notice-error' : 'notice-info' }}">
                    {{ $msg }}
                </div>
            @endif

            <div class="js-auth-fields" data-url="{{ route('loginSubmitRoute') }}">
                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input
                        type="text"
                        name="username"
                        id="username"
                        class="form-control custom-input"
                        placeholder="Enter your username"
                        value="{{ old('username') }}"
                        required
                    >
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input
                        type="password"
                        name="password"
                        id="password"
                        class="form-control custom-input"
                        placeholder="Enter your password"
                        required
                    >
                </div>

                <button type="button" class="submit-btn w-100 js-login-submit">Login</button>
            </div>
        </section>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @vite(['resources/js/app.js'])
</body>
</html>
