<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title')</title>

    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        :root{
            --primary: #1d4ed8;
            --primary-dark: #1e3a8a;
            --accent: #0ea5e9;
            --bg: #f1f5f9;
            --text: #0f172a;
            --muted: #64748b;
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
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .custom-navbar{
            background: linear-gradient(135deg, var(--primary-dark), var(--primary), var(--accent));
            box-shadow: 0 8px 24px rgba(15, 23, 42, 0.16);
        }

        .navbar-brand{
            font-size: 1.5rem;
            font-weight: 700;
            color: #fff !important;
        }

        .navbar-brand span{
            font-weight: 400;
            opacity: 0.95;
        }

        .navbar-nav .nav-link{
            color: #fff !important;
            font-weight: 600;
            padding: 10px 18px !important;
            border-radius: 999px;
            transition: 0.25s ease;
        }

        .navbar-nav .nav-link:hover{
            background: rgba(255,255,255,0.16);
        }

        .navbar-nav .nav-link.active{
            background: #fff;
            color: var(--primary-dark) !important;
            box-shadow: 0 6px 16px rgba(255,255,255,0.25);
        }

        .logout-btn{
            border: 1px solid rgba(255,255,255,0.55);
            color: #fff;
            border-radius: 999px;
            padding: 8px 16px;
            font-weight: 600;
            background: rgba(255,255,255,0.08);
            transition: 0.25s ease;
        }

        .logout-btn:hover{
            background: #fff;
            color: var(--primary-dark);
        }

        .session-user{
            color: #e2e8f0;
            font-size: 0.9rem;
            margin-right: 6px;
        }

        .profile-nav-button{
            width: 42px;
            height: 42px;
            padding: 0;
            border: 2px solid rgba(255,255,255,0.7);
            border-radius: 50%;
            background: rgba(255,255,255,0.14);
            overflow: hidden;
            box-shadow: 0 8px 18px rgba(15, 23, 42, 0.18);
        }

        .profile-nav-button:focus{
            outline: 3px solid rgba(255,255,255,0.4);
            outline-offset: 3px;
        }

        .profile-nav-logo,
        .profile-nav-placeholder{
            width: 100%;
            height: 100%;
            border-radius: 50%;
        }

        .profile-nav-logo{
            display: block;
            object-fit: cover;
            background: #fff;
        }

        .profile-nav-placeholder{
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary-dark);
            background: #dbeafe;
            font-weight: 800;
            font-size: 1.05rem;
        }

        .profile-modal-avatar,
        .profile-modal-avatar-placeholder{
            width: 92px;
            height: 92px;
            border-radius: 50%;
            border: 4px solid #fff;
            box-shadow: 0 10px 24px rgba(15, 23, 42, 0.16);
        }

        .profile-modal-avatar{
            object-fit: cover;
            background: #fff;
        }

        .profile-modal-avatar-placeholder{
            display: flex;
            align-items: center;
            justify-content: center;
            background: #dbeafe;
            color: var(--primary);
            font-size: 2rem;
            font-weight: 800;
        }

        .student-detail-grid{
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 12px;
        }

        .student-detail-item{
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            padding: 12px 14px;
            background: #f8fafc;
        }

        .student-detail-label{
            display: block;
            color: var(--muted);
            font-size: 0.78rem;
            margin-bottom: 4px;
        }

        .student-detail-value{
            color: var(--text);
            font-weight: 700;
            overflow-wrap: anywhere;
        }

        .content-wrapper{
            flex: 1;
        }

        .page-shell{
            background: rgba(255,255,255,0.80);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255,255,255,0.7);
            border-radius: 24px;
            padding: 32px;
            box-shadow: 0 14px 40px rgba(15, 23, 42, 0.10);
        }

        .page-title{
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 8px;
        }

        .page-subtitle{
            font-size: 0.95rem;
            color: var(--muted);
            margin-bottom: 26px;
            line-height: 1.7;
        }

        .site-footer{
            margin-top: auto;
            background: var(--footer);
            color: #e2e8f0;
            text-align: center;
            padding: 16px 12px;
        }

        .site-footer h5{
            margin: 0;
            font-size: 14px;
            font-weight: 500;
        }

        @media (max-width: 768px){
            .page-shell{
                padding: 20px;
                border-radius: 18px;
            }

            .page-title{
                font-size: 1.5rem;
            }

            .student-detail-grid{
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    @php
        $currentRole = strtolower((string) session('role', ''));
        $brandLink = route('loginRoute');
        $studentForNavbar = null;
        $teacherForNavbar = null;
        $studentInitial = strtoupper(substr((string) session('username', 'S'), 0, 1));
        $teacherInitial = strtoupper(substr((string) session('username', 'T'), 0, 1));
        if ($currentRole === 'admin') {
            $brandLink = route('admin.dashboard');
        } elseif ($currentRole === 'student') {
            $brandLink = route('studentPortalRoute');
            $studentForNavbar = \App\Models\Student::with(['degree', 'userAccount'])
                ->where('user_account_id', session('user_id'))
                ->first();
        } elseif ($currentRole === 'teacher') {
            $brandLink = route('teacherPortalRoute');
            $teacherForNavbar = \App\Models\Teacher::with('userAccount')
                ->where('user_account_id', session('user_id'))
                ->first();
        }
        $studentFullName = $studentForNavbar
            ? trim($studentForNavbar->fname . ' ' . $studentForNavbar->mname . ' ' . $studentForNavbar->lname)
            : session('username');
        $teacherFullName = $teacherForNavbar
            ? trim($teacherForNavbar->fname . ' ' . $teacherForNavbar->mname . ' ' . $teacherForNavbar->lname)
            : session('username');
    @endphp

    <!-- Header -->
    @section('Header')
    <nav class="navbar navbar-expand-lg custom-navbar">
        <div class="container">
            <a class="navbar-brand" href="{{ $brandLink }}">Astig<span>Website</span></a>

            <button class="navbar-toggler bg-light" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse justify-content-end" id="mainNavbar">
                <ul class="navbar-nav gap-lg-2 text-center">
                    @if($currentRole === 'admin')
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                                Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.students.*') ? 'active' : '' }}" href="{{ route('admin.students.index') }}">
                                Students
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.teachers.*') ? 'active' : '' }}" href="{{ route('admin.teachers.index') }}">
                                Teachers
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.degrees.*') ? 'active' : '' }}" href="{{ route('admin.degrees.index') }}">
                                Degrees
                            </a>
                        </li>
                    @elseif($currentRole === 'student')
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('studentPortalRoute') ? 'active' : '' }}" href="{{ route('studentPortalRoute') }}">
                                Student Portal
                            </a>
                        </li>
                    @elseif($currentRole === 'teacher')
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('teacherPortalRoute') ? 'active' : '' }}" href="{{ route('teacherPortalRoute') }}">
                                Teacher Portal
                            </a>
                        </li>
                    @endif
                    @if(session()->has('user_id'))
                        <li class="nav-item d-flex align-items-center justify-content-center px-2">
                            @if($currentRole === 'student')
                                <button
                                    type="button"
                                    class="profile-nav-button me-2"
                                    data-bs-toggle="modal"
                                    data-bs-target="#studentProfileModal"
                                    aria-label="Open student profile"
                                >
                                    @if($studentForNavbar?->image_path)
                                        <img src="{{ asset($studentForNavbar->image_path) }}" alt="Student profile image" class="profile-nav-logo">
                                    @else
                                        <span class="profile-nav-placeholder">{{ $studentInitial }}</span>
                                    @endif
                                </button>
                            @elseif($currentRole === 'teacher')
                                <button
                                    type="button"
                                    class="profile-nav-button me-2"
                                    data-bs-toggle="modal"
                                    data-bs-target="#teacherProfileModal"
                                    aria-label="Open teacher profile"
                                >
                                    @if($teacherForNavbar?->image_path)
                                        <img src="{{ asset($teacherForNavbar->image_path) }}" alt="Teacher profile image" class="profile-nav-logo">
                                    @else
                                        <span class="profile-nav-placeholder">{{ $teacherInitial }}</span>
                                    @endif
                                </button>
                            @endif
                            <span class="session-user">Hi, {{ session('username') }}</span>
                        </li>
                        <li class="nav-item d-flex align-items-center justify-content-center">
                            <button
                                type="button"
                                class="logout-btn js-ajax-action"
                                data-url="{{ route('logoutRoute') }}"
                                data-method="POST"
                                data-redirect="{{ route('loginRoute') }}"
                            >
                                Logout
                            </button>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </nav>
    @show

    @if($currentRole === 'student')
        <div class="modal fade" id="studentProfileModal" tabindex="-1" aria-labelledby="studentProfileModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content border-0 rounded-4 overflow-hidden">
                    <div class="modal-header text-white border-0"
                         style="background: linear-gradient(135deg, #1e3a8a, #2563eb, #0ea5e9);">
                        <div class="d-flex align-items-center gap-3">
                            @if($studentForNavbar?->image_path)
                                <img src="{{ asset($studentForNavbar->image_path) }}" alt="Student profile image" class="profile-modal-avatar">
                            @else
                                <span class="profile-modal-avatar-placeholder">{{ $studentInitial }}</span>
                            @endif
                            <div>
                                <h2 class="modal-title fs-4 fw-bold" id="studentProfileModalLabel">Student Profile</h2>
                                <p class="mb-0 opacity-75">{{ $studentFullName }}</p>
                            </div>
                        </div>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body p-4">
                        @if($studentForNavbar)
                            <div class="student-detail-grid mb-4">
                                <div class="student-detail-item">
                                    <span class="student-detail-label">Student ID</span>
                                    <div class="student-detail-value">{{ $studentForNavbar->id }}</div>
                                </div>
                                <div class="student-detail-item">
                                    <span class="student-detail-label">Username</span>
                                    <div class="student-detail-value">{{ $studentForNavbar->userAccount->username ?? session('username') }}</div>
                                </div>
                                <div class="student-detail-item">
                                    <span class="student-detail-label">Email</span>
                                    <div class="student-detail-value">{{ $studentForNavbar->email ?? 'N/A' }}</div>
                                </div>
                                <div class="student-detail-item">
                                    <span class="student-detail-label">Contact Number</span>
                                    <div class="student-detail-value">{{ $studentForNavbar->contact_no ?? 'N/A' }}</div>
                                </div>
                                <div class="student-detail-item">
                                    <span class="student-detail-label">Degree</span>
                                    <div class="student-detail-value">{{ $studentForNavbar->degree->degree ?? 'N/A' }}</div>
                                </div>
                                <div class="student-detail-item">
                                    <span class="student-detail-label">Full Name</span>
                                    <div class="student-detail-value">{{ $studentFullName }}</div>
                                </div>
                            </div>

                            <div class="js-ajax-fields">
                                <label for="student_profile_image" class="form-label fw-semibold">Upload Profile Image</label>
                                <input
                                    type="file"
                                    name="image"
                                    id="student_profile_image"
                                    accept="image/*"
                                    class="form-control rounded-3 @error('image') is-invalid @enderror"
                                >
                                <small class="text-muted d-block mt-1">Accepted: JPG, PNG, GIF, or WEBP up to 2MB.</small>
                                @error('image')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror

                                <div class="d-flex justify-content-end gap-2 mt-4">
                                    <button type="button" class="btn btn-light border rounded-pill px-4" data-bs-dismiss="modal">
                                        Cancel
                                    </button>
                                    <button
                                        type="button"
                                        class="btn btn-primary rounded-pill px-4 shadow-sm js-ajax-save"
                                        data-url="{{ route('studentImageUpdateRoute') }}"
                                        data-method="POST"
                                        data-redirect="{{ route('studentPortalRoute') }}"
                                    >
                                        Upload Image
                                    </button>
                                </div>
                            </div>
                        @else
                            <div class="alert alert-warning mb-0">
                                No student record is connected to this account yet.
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if($currentRole === 'teacher')
        <div class="modal fade" id="teacherProfileModal" tabindex="-1" aria-labelledby="teacherProfileModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content border-0 rounded-4 overflow-hidden">
                    <div class="modal-header text-white border-0"
                         style="background: linear-gradient(135deg, #115e59, #0f766e, #0ea5e9);">
                        <div class="d-flex align-items-center gap-3">
                            @if($teacherForNavbar?->image_path)
                                <img src="{{ asset($teacherForNavbar->image_path) }}" alt="Teacher profile image" class="profile-modal-avatar">
                            @else
                                <span class="profile-modal-avatar-placeholder">{{ $teacherInitial }}</span>
                            @endif
                            <div>
                                <h2 class="modal-title fs-4 fw-bold" id="teacherProfileModalLabel">Teacher Profile</h2>
                                <p class="mb-0 opacity-75">{{ $teacherFullName }}</p>
                            </div>
                        </div>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body p-4">
                        @if($teacherForNavbar)
                            <div class="student-detail-grid mb-4">
                                <div class="student-detail-item">
                                    <span class="student-detail-label">Teacher ID</span>
                                    <div class="student-detail-value">{{ $teacherForNavbar->id }}</div>
                                </div>
                                <div class="student-detail-item">
                                    <span class="student-detail-label">Username</span>
                                    <div class="student-detail-value">{{ $teacherForNavbar->userAccount->username ?? session('username') }}</div>
                                </div>
                                <div class="student-detail-item">
                                    <span class="student-detail-label">Email</span>
                                    <div class="student-detail-value">{{ $teacherForNavbar->email ?? 'N/A' }}</div>
                                </div>
                                <div class="student-detail-item">
                                    <span class="student-detail-label">Contact Number</span>
                                    <div class="student-detail-value">{{ $teacherForNavbar->contact_no ?? 'N/A' }}</div>
                                </div>
                                <div class="student-detail-item">
                                    <span class="student-detail-label">Full Name</span>
                                    <div class="student-detail-value">{{ $teacherFullName }}</div>
                                </div>
                            </div>

                            <div class="js-ajax-fields">
                                <label for="teacher_profile_image" class="form-label fw-semibold">Upload Profile Image</label>
                                <input
                                    type="file"
                                    name="image"
                                    id="teacher_profile_image"
                                    accept="image/*"
                                    class="form-control rounded-3 @error('image') is-invalid @enderror"
                                >
                                <small class="text-muted d-block mt-1">Accepted: JPG, PNG, GIF, or WEBP up to 2MB.</small>
                                @error('image')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror

                                <div class="d-flex justify-content-end gap-2 mt-4">
                                    <button type="button" class="btn btn-light border rounded-pill px-4" data-bs-dismiss="modal">
                                        Cancel
                                    </button>
                                    <button
                                        type="button"
                                        class="btn btn-primary rounded-pill px-4 shadow-sm js-ajax-save"
                                        data-url="{{ route('teacherImageUpdateRoute') }}"
                                        data-method="POST"
                                        data-redirect="{{ route('teacherPortalRoute') }}"
                                    >
                                        Upload Image
                                    </button>
                                </div>
                            </div>
                        @else
                            <div class="alert alert-warning mb-0">
                                No teacher record is connected to this account yet.
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Main Content -->
    <main class="content-wrapper py-4">
        <div class="container">
            <div class="page-shell">
                @yield('Content')
            </div>
        </div>
    </main>

    <!-- Footer -->
    @section('Footer')
    <footer class="site-footer">
        <h5>Copyright © 2026 | AstigWebsite | Contact: okayokay@gmail.com</h5>
    </footer>
    @show

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    @vite(['resources/js/app.js'])

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
