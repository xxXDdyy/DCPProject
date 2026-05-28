<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>

    <style>
        *{
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        :root{
            --primary: #1d4ed8;
            --primary-dark: #1e3a8a;
            --accent: #0ea5e9;
            --bg: #f1f5f9;
            --card: #ffffff;
            --text: #0f172a;
            --muted: #64748b;
            --border: #e2e8f0;
            --footer: #0f172a;
        }

        html, body{
            height: 100%;
        }

        body{
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            background:
                radial-gradient(circle at top left, #dbeafe 0%, transparent 30%),
                radial-gradient(circle at bottom right, #bfdbfe 0%, transparent 25%),
                var(--bg);
            color: var(--text);
        }

        /* HEADER */
        .topbar{
            background: linear-gradient(135deg, var(--primary-dark), var(--primary), var(--accent));
            box-shadow: 0 8px 24px rgba(15, 23, 42, 0.16);
        }

        .nav-container{
            width: 92%;
            max-width: 1200px;
            margin: 0 auto;
            min-height: 82px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 20px;
        }

        .brand{
            color: #fff;
            font-size: 24px;
            font-weight: 700;
            letter-spacing: 0.5px;
        }

        .brand span{
            font-weight: 400;
            opacity: 0.95;
        }

        .navbar{
            list-style: none;
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .navbar li a{
            text-decoration: none;
            color: #ffffff;
            font-size: 15px;
            font-weight: 600;
            padding: 12px 18px;
            border-radius: 999px;
            transition: all 0.25s ease;
            display: inline-block;
        }

        .navbar li a:hover{
            background: rgba(255,255,255,0.16);
            transform: translateY(-1px);
        }

        .navbar li a.active{
            background: #ffffff;
            color: var(--primary-dark);
            box-shadow: 0 6px 16px rgba(255,255,255,0.25);
        }

        /* MAIN CONTENT */
        .content-wrapper{
            flex: 1;
            width: 92%;
            max-width: 1200px;
            margin: 35px auto;
        }

        .page-shell{
            background: rgba(255,255,255,0.75);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255,255,255,0.7);
            border-radius: 24px;
            padding: 32px;
            box-shadow: 0 14px 40px rgba(15, 23, 42, 0.10);
        }

        /* COMMON TYPOGRAPHY */
        .page-title{
            font-size: 32px;
            font-weight: 700;
            color: var(--text);
            margin-bottom: 8px;
        }

        .page-subtitle{
            font-size: 15px;
            color: var(--muted);
            margin-bottom: 26px;
            line-height: 1.7;
        }

        /* CARD */
        .card{
            background: var(--card);
            border-radius: 22px;
            border: 1px solid var(--border);
            box-shadow: 0 10px 25px rgba(15, 23, 42, 0.07);
            overflow: hidden;
        }

        .card-header{
            padding: 22px 24px 14px;
            border-bottom: 1px solid var(--border);
            background: linear-gradient(to right, #f8fbff, #eef6ff);
        }

        .card-title{
            font-size: 20px;
            font-weight: 700;
            color: var(--text);
        }

        .card-subtitle{
            margin-top: 6px;
            color: var(--muted);
            font-size: 14px;
        }

        .card-body{
            padding: 0;
        }

        /* TABLE */
        .table-wrapper{
            overflow-x: auto;
        }

        table{
            width: 100%;
            border-collapse: collapse;
        }

        thead{
            background: #eff6ff;
        }

        th{
            padding: 18px 20px;
            text-align: left;
            font-size: 14px;
            font-weight: 700;
            color: var(--primary-dark);
            text-transform: uppercase;
            letter-spacing: 0.6px;
            border-bottom: 1px solid var(--border);
        }

        td{
            padding: 18px 20px;
            font-size: 15px;
            color: #334155;
            border-bottom: 1px solid var(--border);
        }

        tbody tr{
            transition: background 0.2s ease;
        }

        tbody tr:hover{
            background: #f8fbff;
        }

        tbody tr:last-child td{
            border-bottom: none;
        }

        .text-center{
            text-align: center;
        }

        .badge{
            display: inline-block;
            padding: 7px 14px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 0.3px;
        }

        .badge-blue{
            background: #dbeafe;
            color: #1d4ed8;
        }

        .badge-green{
            background: #dcfce7;
            color: #15803d;
        }

        .badge-yellow{
            background: #fef3c7;
            color: #b45309;
        }

        .badge-purple{
            background: #ede9fe;
            color: #7c3aed;
        }

        .badge-red{
            background: #fee2e2;
            color: #dc2626;
        }

        .empty-state{
            padding: 28px;
            text-align: center;
            color: var(--muted);
            font-size: 15px;
        }

        /* FOOTER */
        .site-footer{
            margin-top: auto;
            background: var(--footer);
            color: #e2e8f0;
            text-align: center;
            padding: 18px 12px;
        }

        .site-footer h5{
            margin: 0;
            font-size: 14px;
            font-weight: 500;
            letter-spacing: 0.3px;
        }

        @media (max-width: 768px){
            .nav-container{
                flex-direction: column;
                justify-content: center;
                padding: 18px 0;
            }

            .navbar{
                flex-wrap: wrap;
                justify-content: center;
            }

            .page-shell{
                padding: 20px;
            }

            .page-title{
                font-size: 24px;
            }

            th, td{
                padding: 14px 12px;
                font-size: 14px;
            }
        }
    </style>
</head>
<body>
    @section('Header')
        <header class="topbar">
            <div class="nav-container">
                <div class="brand">Astig<span>Website</span></div>

                <ul class="navbar">
                    <li>
                        <a href="/homePage" class="{{ request()->is('homePage') ? 'active' : '' }}">
                            Home
                        </a>
                    </li>
                    <li>
                        <a href="/studentsPage" class="{{ request()->is('studentsPage') ? 'active' : '' }}">
                            Students
                        </a>
                    </li>
                    <li>
                        <a href="/aboutPage" class="{{ request()->is('aboutPage') ? 'active' : '' }}">
                            About
                        </a>
                    </li>
                </ul>
            </div>
        </header>
    @show

    <main class="content-wrapper">
        <div class="page-shell">
            @yield('Content')
        </div>
    </main>

    @section('Footer')
        <footer class="site-footer">
            <h5>Copyright © 2026 | AstigWebsite | Contact: okayokay@gmail.com</h5>
        </footer>
    @show
</body>
</html>