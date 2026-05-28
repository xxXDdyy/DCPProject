<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Change Password</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root{
            --ink: #0f172a;
            --muted: #475569;
            --brand: #0f766e;
            --brand-dark: #115e59;
            --surface: rgba(255, 255, 255, 0.92);
            --line: rgba(148, 163, 184, 0.3);
            --ok-bg: #dcfce7;
            --ok-text: #166534;
            --err-bg: #fee2e2;
            --err-text: #991b1b;
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
                radial-gradient(circle at 15% 18%, rgba(16, 185, 129, 0.28), transparent 34%),
                radial-gradient(circle at 86% 82%, rgba(45, 212, 191, 0.28), transparent 38%),
                linear-gradient(135deg, #ecfeff 0%, #f0fdfa 55%, #f8fafc 100%);
        }

        .shell{
            width: 100%;
            max-width: 520px;
            border-radius: 26px;
            border: 1px solid var(--line);
            background: var(--surface);
            box-shadow: 0 24px 58px rgba(15, 23, 42, 0.16);
            padding: 34px 30px;
        }

        .title{
            margin: 0;
            font-size: 1.8rem;
            font-weight: 800;
            color: #0f172a;
        }

        .subtitle{
            margin: 10px 0 22px;
            font-size: 0.95rem;
            line-height: 1.65;
            color: var(--muted);
        }

        .notice{
            border-radius: 12px;
            padding: 12px 14px;
            font-size: 0.9rem;
            font-weight: 600;
            margin-bottom: 18px;
            border: 1px solid transparent;
        }

        .notice-info{
            background: var(--ok-bg);
            color: var(--ok-text);
            border-color: rgba(22, 163, 74, 0.28);
        }

        .notice-error{
            background: var(--err-bg);
            color: var(--err-text);
            border-color: rgba(220, 38, 38, 0.3);
        }

        .form-label{
            font-weight: 700;
            margin-bottom: 8px;
            color: #0f172a;
        }

        .custom-input{
            border: 1px solid #cbd5e1;
            border-radius: 12px;
            padding: 12px 13px;
            font-size: 0.98rem;
        }

        .custom-input:focus{
            border-color: #0d9488;
            box-shadow: 0 0 0 0.22rem rgba(13, 148, 136, 0.16);
        }

        .btn-submit{
            border: 0;
            border-radius: 12px;
            padding: 12px 15px;
            width: 100%;
            margin-top: 10px;
            font-weight: 700;
            color: #fff;
            background: linear-gradient(135deg, var(--brand), var(--brand-dark));
            box-shadow: 0 10px 22px rgba(15, 118, 110, 0.25);
        }

        .btn-submit:hover{
            filter: brightness(1.06);
            color: #fff;
        }

        .hint{
            margin-top: 12px;
            font-size: 0.84rem;
            color: var(--muted);
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="shell">
        <h1 class="title">Set New Password</h1>
        <p class="subtitle">
            This account is logging in for the first time. Please create a new password, confirm it,
            then continue by logging in again.
        </p>

        @if (!empty($msg))
            @php
                $lowerMsg = strtolower($msg);
                $isError = str_contains($lowerMsg, 'failed') || str_contains($lowerMsg, 'please');
            @endphp
            <div class="notice {{ $isError ? 'notice-error' : 'notice-info' }}">
                {{ $msg }}
            </div>
        @endif

        @if ($errors->any())
            <div class="notice notice-error">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <form method="POST" action="/password/change-first-login" class="js-password-fields" data-url="/password/change-first-login">
            @csrf

            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input
                    type="text"
                    id="username"
                    class="form-control custom-input"
                    value="{{ $username }}"
                    readonly
                >
            </div>

            <div class="mb-3">
                <label for="old_password" class="form-label">Old Password</label>
                <input
                    type="password"
                    name="old_password"
                    id="old_password"
                    class="form-control custom-input @error('old_password') is-invalid @enderror"
                    placeholder="Enter your old password"
                    required
                >
            </div>

            <div class="mb-3">
                <label for="new_password" class="form-label">New Password</label>
                <input
                    type="password"
                    name="new_password"
                    id="new_password"
                    class="form-control custom-input @error('new_password') is-invalid @enderror"
                    placeholder="Enter your new password"
                    required
                >
            </div>

            <div class="mb-3">
                <label for="new_password_confirmation" class="form-label">Retype New Password</label>
                <input
                    type="password"
                    name="new_password_confirmation"
                    id="new_password_confirmation"
                    class="form-control custom-input"
                    placeholder="Retype your new password"
                    required
                >
            </div>

            <button type="submit" class="btn-submit js-password-submit">Change Password</button>
        </form>

        <p class="hint">Minimum 8 characters. Use a strong password for your account security.</p>
    </div>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @vite(['resources/js/app.js'])
</body>
</html>
