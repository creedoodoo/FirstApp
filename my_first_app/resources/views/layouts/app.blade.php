<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Cuatro Marias Portal — BSIT 3rd Year Attendance System')</title>

    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --color-brand: #8C0D47;
            --color-brand-hover: #6F0A38;
            --color-brand-light: #FBF0F5;
            --color-bg: #F8F9FA;
            --color-card: #FFFFFF;
            --color-dark: #1E293B;
            --color-muted: #64748B;
            --color-border: #E2E8F0;
            --color-success: #10B981;
            --color-warning: #F59E0B;
            --color-danger: #EF4444;
            --color-info: #3B82F6;
            --radius-sm: 6px;
            --radius-md: 10px;
            --radius-lg: 16px;
            --radius-xl: 24px;
            --shadow-sm: 0 1px 3px rgba(0,0,0,0.05);
            --shadow-md: 0 4px 12px rgba(0,0,0,0.08);
            --shadow-lg: 0 10px 25px rgba(0,0,0,0.1);
            --transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
        }

        body {
            background-color: var(--color-bg);
            color: var(--color-dark);
            line-height: 1.5;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* Glassmorphism Navbar */
        .navbar {
            position: sticky;
            top: 0;
            z-index: 1000;
            background: rgba(255, 255, 255, 0.88);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--color-border);
            padding: 0.875rem 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
        }

        .nav-left {
            display: flex;
            align-items: center;
            gap: 2rem;
        }

        .nav-brand {
            display: flex;
            align-items: center;
            gap: 0.625rem;
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--color-dark);
            text-decoration: none;
        }

        .brand-icon {
            width: 36px;
            height: 36px;
            background-color: var(--color-brand-light);
            border-radius: var(--radius-md);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--color-brand);
        }

        .nav-menu {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            list-style: none;
        }

        .nav-link {
            text-decoration: none;
            color: var(--color-muted);
            font-size: 0.9375rem;
            font-weight: 500;
            padding: 0.5rem 1rem;
            border-radius: var(--radius-md);
            transition: var(--transition);
        }

        .nav-link:hover, .nav-link.active {
            color: var(--color-brand);
            background-color: var(--color-brand-light);
            font-weight: 600;
        }

        .nav-right {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .role-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            padding: 0.35rem 0.75rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .role-badge-admin {
            background-color: #FEF2F2;
            color: #DC2626;
            border: 1px solid #FCA5A5;
        }

        .role-badge-teacher {
            background-color: var(--color-brand-light);
            color: var(--color-brand);
            border: 1px solid #F472B6;
        }

        .user-name {
            font-size: 0.9375rem;
            font-weight: 600;
            color: var(--color-dark);
        }

        .btn-signout-red {
            background-color: #FEE2E2;
            color: #EF4444;
            border: 1px solid #FCA5A5;
            padding: 0.5rem;
            border-radius: var(--radius-md);
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: var(--transition);
        }

        .btn-signout-red:hover {
            background-color: #EF4444;
            color: #FFFFFF;
            border-color: #EF4444;
        }

        /* Container */
        .container {
            width: 100%;
            max-width: 1240px;
            margin: 0 auto;
            padding: 2rem 1.5rem;
            flex: 1;
        }

        /* Alerts */
        .alert {
            padding: 1rem 1.25rem;
            border-radius: var(--radius-md);
            margin-bottom: 1.5rem;
            font-size: 0.9375rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .alert-success {
            background-color: #ECFDF5;
            color: #065F46;
            border: 1px solid #A7F3D0;
        }

        .alert-error {
            background-color: #FEF2F2;
            color: #991B1B;
            border: 1px solid #FCA5A5;
        }

        /* UI Cards */
        .card {
            background: var(--color-card);
            border: 1px solid var(--color-border);
            border-radius: var(--radius-lg);
            padding: 1.5rem;
            box-shadow: var(--shadow-sm);
            margin-bottom: 1.5rem;
        }

        /* Sign Out Modal */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(15, 23, 42, 0.6);
            backdrop-filter: blur(4px);
            z-index: 2000;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .modal-card {
            background: #FFFFFF;
            border-radius: var(--radius-xl);
            padding: 2rem;
            max-width: 400px;
            width: 90%;
            text-align: center;
            box-shadow: var(--shadow-lg);
        }

        .modal-actions {
            display: flex;
            gap: 1rem;
            margin-top: 1.5rem;
        }

        .btn-neutral {
            flex: 1;
            padding: 0.75rem 1rem;
            background-color: #F1F5F9;
            color: #475569;
            border: 1px solid #CBD5E1;
            border-radius: var(--radius-md);
            font-weight: 600;
            cursor: pointer;
        }

        .btn-red {
            flex: 1;
            padding: 0.75rem 1rem;
            background-color: #EF4444;
            color: #FFFFFF;
            border: none;
            border-radius: var(--radius-md);
            font-weight: 600;
            cursor: pointer;
        }

        .footer {
            text-align: center;
            padding: 1.5rem;
            color: var(--color-muted);
            font-size: 0.875rem;
            border-top: 1px solid var(--color-border);
            margin-top: auto;
            background: #FFFFFF;
        }
    </style>
    @yield('styles')
</head>
<body>

    <!-- Header Navigation Bar -->
    <header class="navbar">
        <div class="nav-left">
            <a href="{{ auth()->user() && auth()->user()->isAdmin() ? route('admin.dashboard') : (auth()->user() && auth()->user()->isTeacher() ? route('teacher.dashboard') : route('login')) }}" class="nav-brand">
                <div class="brand-icon">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"></path>
                    </svg>
                </div>
                <span>Cuatro Marias Portal</span>
            </a>

            @if(session('user_id'))
                @php $navUser = \App\Models\User::find(session('user_id')); @endphp
                @if($navUser)
                    <ul class="nav-menu">
                        @if($navUser->isAdmin())
                            <li><a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">Dashboard</a></li>
                            <li><a href="{{ route('admin.approvals.index') }}" class="nav-link {{ request()->routeIs('admin.approvals.*') ? 'active' : '' }}">Teacher Approvals</a></li>
                            <li><a href="{{ route('admin.subjects.index') }}" class="nav-link {{ request()->routeIs('admin.subjects.*') ? 'active' : '' }}">Subjects</a></li>
                        @elseif($navUser->isTeacher() && $navUser->isApproved())
                            <li><a href="{{ route('teacher.dashboard') }}" class="nav-link {{ request()->routeIs('teacher.dashboard') ? 'active' : '' }}">Dashboard</a></li>
                            <li><a href="{{ route('teacher.classes.index') }}" class="nav-link {{ request()->routeIs('teacher.classes.*') ? 'active' : '' }}">My Classes</a></li>
                            <li><a href="{{ route('teacher.sessions.index') }}" class="nav-link {{ request()->routeIs('teacher.sessions.*') ? 'active' : '' }}">Sessions History</a></li>
                            <li><a href="{{ route('teacher.students.search') }}" class="nav-link {{ request()->routeIs('teacher.students.*') ? 'active' : '' }}">Students</a></li>
                        @endif
                    </ul>
                @endif
            @endif
        </div>

        <div class="nav-right">
            @if(session('user_id'))
                @php $navUser = \App\Models\User::find(session('user_id')); @endphp
                @if($navUser)
                    <span class="role-badge {{ $navUser->isAdmin() ? 'role-badge-admin' : 'role-badge-teacher' }}">
                        {{ $navUser->role }}
                    </span>
                    <span class="user-name">{{ $navUser->name }}</span>
                    <button type="button" id="btn-open-logout-modal" class="btn-signout-red" title="Sign Out">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m16 17 5-5-5-5"/><path d="M21 12H9"/><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/></svg>
                    </button>
                @endif
            @else
                <a href="{{ route('login') }}" class="nav-link active">Sign In</a>
            @endif
        </div>
    </header>

    <!-- Main Container -->
    <main class="container">
        @if(session('success'))
            <div class="alert alert-success">
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-error">
                <span>{{ session('error') }}</span>
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Logout Confirmation Modal -->
    <div id="logout-modal" class="modal-overlay" style="display: none;">
        <div class="modal-card">
            <h3 style="margin-bottom: 0.5rem;">Confirm Sign Out</h3>
            <p style="color: var(--color-muted); font-size: 0.9375rem;">Are you sure you want to sign out of your account?</p>
            <div class="modal-actions">
                <button type="button" id="btn-cancel-logout" class="btn-neutral">Cancel</button>
                <form action="{{ route('logout') }}" method="POST" style="margin: 0; flex: 1;">
                    @csrf
                    <button type="submit" class="btn-red">Sign Out</button>
                </form>
            </div>
        </div>
    </div>

    <footer class="footer">
        <p>&copy; {{ date('Y') }} Cuatro Marias Portal — BSIT 3rd Year Attendance System (PUP Santa Rosa Campus). All rights reserved.</p>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const openModalBtn = document.getElementById('btn-open-logout-modal');
            const cancelModalBtn = document.getElementById('btn-cancel-logout');
            const logoutModal = document.getElementById('logout-modal');

            if (openModalBtn && logoutModal) {
                openModalBtn.addEventListener('click', () => {
                    logoutModal.style.display = 'flex';
                });
            }

            if (cancelModalBtn && logoutModal) {
                cancelModalBtn.addEventListener('click', () => {
                    logoutModal.style.display = 'none';
                });
            }
        });
    </script>
    @yield('scripts')
</body>
</html>
