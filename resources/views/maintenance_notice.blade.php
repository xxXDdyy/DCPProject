<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Maintenance Notice</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root{
            --ink: #0f172a;
            --muted: #475569;
            --surface: rgba(255, 255, 255, 0.84);
            --line: rgba(148, 163, 184, 0.26);
            --accent: #0369a1;
            --accent-dark: #075985;
            --glow: #7dd3fc;
        }

        body{
            min-height: 100vh;
            margin: 0;
            display: grid;
            place-items: center;
            padding: 24px;
            font-family: 'Trebuchet MS', 'Segoe UI', sans-serif;
            color: var(--ink);
            background:
                radial-gradient(circle at 18% 20%, rgba(125, 211, 252, 0.45), transparent 38%),
                radial-gradient(circle at 82% 78%, rgba(14, 165, 233, 0.32), transparent 32%),
                linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
        }

        .notice-card{
            width: 100%;
            max-width: 760px;
            background: var(--surface);
            border: 1px solid var(--line);
            border-radius: 24px;
            padding: 40px 34px;
            box-shadow: 0 18px 45px rgba(2, 132, 199, 0.18);
            backdrop-filter: blur(12px);
        }

        .badge-soft{
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(14, 165, 233, 0.10);
            color: var(--accent-dark);
            border: 1px solid rgba(14, 165, 233, 0.20);
            border-radius: 999px;
            padding: 8px 14px;
            font-weight: 700;
            letter-spacing: 0.02em;
            font-size: 0.82rem;
            text-transform: uppercase;
        }

        .title{
            margin: 18px 0 10px;
            font-size: 2rem;
            line-height: 1.2;
            font-weight: 800;
        }

        .copy{
            color: var(--muted);
            font-size: 1.05rem;
            line-height: 1.75;
            margin-bottom: 0;
        }

        .cta-group{
            margin-top: 28px;
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
        }

        .btn-accent{
            border: 0;
            background: linear-gradient(135deg, var(--accent), var(--accent-dark));
            color: #fff;
            border-radius: 999px;
            padding: 11px 20px;
            font-weight: 700;
            box-shadow: 0 10px 26px rgba(3, 105, 161, 0.28);
        }

        .btn-accent:hover{
            color: #fff;
            filter: brightness(1.05);
        }

        .pulse-dot{
            width: 9px;
            height: 9px;
            border-radius: 50%;
            background: var(--glow);
            box-shadow: 0 0 0 0 rgba(125, 211, 252, 0.75);
            animation: pulse 1.6s infinite;
        }

        @keyframes pulse{
            0% { box-shadow: 0 0 0 0 rgba(125, 211, 252, 0.75); }
            70% { box-shadow: 0 0 0 12px rgba(125, 211, 252, 0); }
            100% { box-shadow: 0 0 0 0 rgba(125, 211, 252, 0); }
        }

        @media (max-width: 576px){
            .notice-card{
                padding: 28px 22px;
                border-radius: 18px;
            }

            .title{
                font-size: 1.55rem;
            }
        }
    </style>
</head>
<body>
    <section class="notice-card">
        <span class="badge-soft">
            <span class="pulse-dot"></span>
            Service Status
        </span>
        <h1 class="title">Sorry, the website is currently down for maintenance.</h1>
        <p class="copy">
            Please check back later. We are applying updates to improve the platform experience and
            stability. Thank you for your patience.
        </p>
        <div class="cta-group">
            <a href="{{ url('/login') }}" class="btn btn-accent">Back to Login</a>
        </div>
    </section>
</body>
</html>
