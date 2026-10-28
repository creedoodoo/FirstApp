<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'BSIT 3rd Year Attendance System')</title>

    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --color-brand: #8C0D47;
            --color-brand-header: #ffe9f1; /* Lighter version of the brand color for table headers */
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
            --color-active-teachers: #18ad63;
            --color-total-students: #f08c00;
            --color-active-subjects: #87127f;
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
            font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
        }

        body {
            background-color: var(--color-bg);
            color: var(--color-dark);
            line-height: 1.5;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* Ensure all buttons do not wrap content into multiple lines */
        button, .btn-primary, .btn-neutral, .btn-red, .btn-signout-red, a.nav-link {
            white-space: nowrap !important;
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
            padding: 0.875rem 2.5rem;
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
            white-space: nowrap;
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
            flex-shrink: 0;
        }

        .nav-menu {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            list-style: none;
        }

        .nav-link {
            text-decoration: none;
            color: #64748B;
            font-size: 0.9375rem;
            font-weight: 500;
            padding: 0.5rem 1.15rem;
            border-radius: 50px;
            transition: var(--transition);
            white-space: nowrap;
            background: transparent;
        }

        .nav-link:hover {
            color: #6F0A38;
            background-color: #FBF0F5;
        }

        .nav-link.active {
            color: #FFFFFF !important;
            background-color: #6F0A38 !important;
            font-weight: 700 !important;
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
            white-space: nowrap;
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
            white-space: nowrap;
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
            white-space: nowrap;
        }

        .btn-signout-red:hover {
            background-color: #EF4444;
            color: #FFFFFF;
            border-color: #EF4444;
        }

        /* Container */
        .container {
            width: 100%;
            max-width: 100%;
            margin: 0 auto;
            padding: 2rem 2.5rem;
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

        /* Universal Table Styles */
        table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
            font-size: 0.875rem;
        }

        /* Card Header Bar above Tables (Brand Header Color) */
        .table-card-header {
            padding: 1.25rem 1.5rem;
            background-color: var(--color-brand-header) !important;
            border-bottom: 1px solid rgba(0, 0, 0, 0.1);
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 0.75rem;
        }

        .table-card-header h3,
        .table-card-header h2,
        .table-card-header h1 {
            color: var(--color-dark, #1E293B) !important;
            margin: 0;
            font-size: 1.0625rem;
            font-weight: 700;
        }

        .table-card-header p {
            color: var(--color-muted, #64748B);
        }

        /* Elements inside card header bar (pills, badges, buttons) remain on white pill */
        .table-card-header .badge,
        .table-card-header .pill,
        .table-card-header span[style*="border-radius"],
        .table-card-header a[style*="border-radius"],
        .table-card-header button[style*="border-radius"] {
            background-color: #FFFFFF !important;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.12);
        }

        /* Category Rows (Table thead) */
        table thead tr {
            background-color: #F8FAFC !important;
            color: var(--color-dark, #1E293B) !important;
            border-bottom: 1px solid var(--color-border) !important;
        }

        table thead th {
            background-color: #F8FAFC !important;
            color: var(--color-dark, #1E293B) !important;
            font-size: 0.75rem !important;
            font-weight: 700 !important;
            letter-spacing: 0.05em !important;
            text-transform: uppercase !important;
            vertical-align: middle !important;
            border: none !important;
            border-bottom: 1px solid var(--color-border) !important;
            padding: 0.875rem 1.25rem !important;
        }

        #btn-submit-confirm {
            color: #FFFFFF !important;
        }

        table tbody tr {
            border-bottom: 1px solid var(--color-border);
            transition: var(--transition);
        }

        table tbody td {
            vertical-align: middle !important;
            padding: 0.875rem 1.25rem;
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
            justify-content: center;
            align-items: center;
        }

        .btn-neutral {
            padding: 0.65rem 1.25rem;
            background-color: #F1F5F9;
            color: #475569;
            border: 1px solid #CBD5E1;
            border-radius: var(--radius-md);
            font-weight: 600;
            cursor: pointer;
            white-space: nowrap;
        }

        .btn-red {
            padding: 0.65rem 1.25rem;
            background-color: #EF4444;
            color: #FFFFFF;
            border: none;
            border-radius: var(--radius-md);
            font-weight: 600;
            cursor: pointer;
            white-space: nowrap;
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

        /* Pagination SVG icon size fix & Clean Pill Styling */
        nav svg, .pagination svg, [role="navigation"] svg {
            width: 1.25rem !important;
            height: 1.25rem !important;
            max-width: 1.25rem !important;
            max-height: 1.25rem !important;
            display: inline-block !important;
            vertical-align: middle;
        }

        nav[role="navigation"] {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 1rem;
            font-size: 0.875rem;
            color: var(--color-muted);
        }

        nav[role="navigation"] a {
            text-decoration: none !important;
            color: var(--color-dark) !important;
            padding: 0.4rem 0.85rem;
            border: 1px solid var(--color-border);
            border-radius: 50px;
            background: #FFFFFF;
            font-weight: 600;
            transition: var(--transition);
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        nav[role="navigation"] a:hover {
            background-color: var(--color-brand-light) !important;
            color: var(--color-brand) !important;
            border-color: var(--color-brand) !important;
        }

        nav[role="navigation"] span[aria-current="page"] span {
            background-color: var(--color-brand) !important;
            color: #FFFFFF !important;
            border-color: var(--color-brand) !important;
            padding: 0.4rem 0.85rem;
            border-radius: 50px;
            font-weight: 700;
            display: inline-flex;
        }

        nav[role="navigation"] span[aria-disabled="true"] span {
            color: #94A3B8 !important;
            background-color: #F8FAFC !important;
            border: 1px solid #E2E8F0 !important;
            padding: 0.4rem 0.85rem;
            border-radius: 50px;
            cursor: not-allowed;
            display: inline-flex;
        }

        nav[role="navigation"] p {
            margin: 0;
            font-size: 0.875rem;
            color: var(--color-muted);
        }

        /* Hide duplicate unstyled mobile Previous/Next text links */
        nav[role="navigation"] > div:first-child:not(:only-child) {
            display: none !important;
        }

        nav[role="navigation"] > div:last-child {
            display: flex !important;
            align-items: center !important;
            justify-content: space-between !important;
            width: 100% !important;
            flex-wrap: wrap !important;
            gap: 1rem !important;
        }
    </style>
    @yield('styles')
</head>
<body>

    <!-- Header Navigation Bar -->
    <header class="navbar">
        <div class="nav-left">
            <a href="{{ auth()->user() && auth()->user()->isAdmin() ? route('admin.dashboard') : (auth()->user() && auth()->user()->isTeacher() ? route('teacher.dashboard') : route('login')) }}" class="nav-brand">
                <span>Attendance System</span>
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
            <div class="alert alert-success" style="display: flex; align-items: center; justify-content: space-between; gap: 0.85rem; background-color: #ECFDF5; color: #065F46; border: 1.5px solid #10B981; border-radius: var(--radius-md); padding: 0.875rem 1.25rem; margin-bottom: 1.5rem; box-shadow: 0 4px 12px rgba(16, 185, 129, 0.12);">
                <div style="display: flex; align-items: center; gap: 0.75rem;">
                    <div style="width: 28px; height: 28px; border-radius: 50%; background: #10B981; color: #FFFFFF; display: flex; align-items: center; justify-content: center; flex-shrink: 0; box-shadow: 0 2px 4px rgba(16, 185, 129, 0.3);">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                    </div>
                    <div>
                        <strong style="color: #065F46; font-size: 0.9375rem; display: block;">Action Completed</strong>
                        <span style="font-size: 0.875rem; color: #047857; font-weight: 500;">{{ session('success') }}</span>
                    </div>
                </div>
                <button type="button" onclick="this.parentElement.style.display='none';" style="background: none; border: none; color: #065F46; font-size: 1.35rem; cursor: pointer; line-height: 1; padding: 0.25rem 0.5rem; opacity: 0.7;" title="Dismiss">&times;</button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-error" style="display: flex; align-items: center; justify-content: space-between; gap: 0.85rem; background-color: #FEF2F2; color: #991B1B; border: 1.5px solid #EF4444; border-radius: var(--radius-md); padding: 0.875rem 1.25rem; margin-bottom: 1.5rem; box-shadow: 0 4px 12px rgba(239, 68, 68, 0.12);">
                <div style="display: flex; align-items: center; gap: 0.75rem;">
                    <div style="width: 28px; height: 28px; border-radius: 50%; background: #EF4444; color: #FFFFFF; display: flex; align-items: center; justify-content: center; flex-shrink: 0; box-shadow: 0 2px 4px rgba(239, 68, 68, 0.3);">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                    </div>
                    <div>
                        <strong style="color: #991B1B; font-size: 0.9375rem; display: block;">Notice</strong>
                        <span style="font-size: 0.875rem; color: #B91C1C; font-weight: 500;">{{ session('error') }}</span>
                    </div>
                </div>
                <button type="button" onclick="this.parentElement.style.display='none';" style="background: none; border: none; color: #991B1B; font-size: 1.35rem; cursor: pointer; line-height: 1; padding: 0.25rem 0.5rem; opacity: 0.7;" title="Dismiss">&times;</button>
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
                <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
                    @csrf
                    <button type="submit" class="btn-red">Sign Out</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Universal Action Confirmation Modal -->
    <div id="universal-confirm-modal" class="modal-overlay" style="display: none;">
        <div class="modal-card" style="text-align: center; max-width: 420px; border-radius: 24px; padding: 2rem;">
            <div id="confirm-modal-icon-wrap" style="width: 52px; height: 52px; border-radius: 50%; background: #FEE2E2; color: #DC2626; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.25rem;">
                <svg id="confirm-modal-icon" width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"></circle>
                    <line x1="12" y1="8" x2="12" y2="12"></line>
                    <line x1="12" y1="16" x2="12.01" y2="16"></line>
                </svg>
            </div>
            <h3 id="confirm-modal-title" style="margin-bottom: 0.5rem; font-size: 1.25rem; font-weight: 700; color: var(--color-dark);">Confirm Action</h3>
            <p id="confirm-modal-message" style="color: var(--color-muted); font-size: 0.9375rem; margin-bottom: 1.5rem; line-height: 1.5;">Are you sure you want to proceed?</p>
            <div class="modal-actions" style="margin-top: 0; justify-content: center; gap: 0.75rem;">
                <button type="button" id="btn-cancel-confirm" class="btn-neutral" style="border-radius: 50px; padding: 0.6rem 1.25rem; font-weight: 600;">Cancel</button>
                <form id="confirm-modal-form" action="" method="POST" style="margin: 0;">
                    @csrf
                    <button type="submit" id="btn-submit-confirm" class="btn-primary" style="padding: 0.65rem 1.5rem; border: none; border-radius: 50px; font-weight: 700; cursor: pointer; color: #FFFFFF !important; font-size: 0.875rem;">
                        Proceed
                    </button>
                </form>
            </div>
        </div>
    </div>

    <footer class="footer">
        <p>&copy; {{ date('Y') }} BSIT 3rd Year Attendance System — PUP Santa Rosa Campus. All rights reserved.</p>
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

            // Universal Confirmation Modal Logic
            const confirmModal = document.getElementById('universal-confirm-modal');
            const confirmTitle = document.getElementById('confirm-modal-title');
            const confirmMsg = document.getElementById('confirm-modal-message');
            const confirmForm = document.getElementById('confirm-modal-form');
            const confirmSubmit = document.getElementById('btn-submit-confirm');
            const confirmCancel = document.getElementById('btn-cancel-confirm');
            const iconWrap = document.getElementById('confirm-modal-icon-wrap');

            window.openConfirmModal = function({ title, message, actionUrl, buttonText, buttonColor, iconType }) {
                if (!confirmModal) return;

                confirmTitle.textContent = title || 'Confirm Action';
                confirmMsg.textContent = message || 'Are you sure you want to proceed?';
                confirmForm.action = actionUrl || '';
                confirmSubmit.textContent = buttonText || 'Proceed';
                confirmSubmit.style.background = buttonColor || 'var(--color-brand)';
                confirmSubmit.style.color = '#FFFFFF';

                if (buttonColor === '#10B981' || buttonColor === '#059669') {
                    iconWrap.style.background = '#ECFDF5';
                    iconWrap.style.color = '#059669';
                } else if (buttonColor === '#EF4444' || buttonColor === '#DC2626') {
                    iconWrap.style.background = '#FEF2F2';
                    iconWrap.style.color = '#DC2626';
                } else {
                    iconWrap.style.background = 'var(--color-brand-light)';
                    iconWrap.style.color = 'var(--color-brand)';
                }

                confirmModal.style.display = 'flex';
            };

            if (confirmCancel && confirmModal) {
                confirmCancel.addEventListener('click', () => {
                    confirmModal.style.display = 'none';
                });
                confirmModal.addEventListener('click', (e) => {
                    if (e.target === confirmModal) {
                        confirmModal.style.display = 'none';
                    }
                });
            }
        });
    </script>
    @yield('scripts')
</body>
</html>
