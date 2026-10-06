<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Authentication — {{ config('app.name', 'Laravel') }}</title>

    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --color-jade: #8C0D47;
            --color-jade-hover: #6F0A38;
            --color-jade-light: #FBF0F5;
            --color-ghost-white: #F8F8FF;
            --color-white: #FFFFFF;
            --color-dark: #1F2937;
            --color-muted: #6B7280;
            --color-border: #E5E7EB;
            --color-error: #EF4444;
            --color-error-bg: #FEF2F2;
            --color-error-border: #FCA5A5;
            --color-success: #10B981;
            --color-success-bg: #ECFDF5;
            --color-success-border: #6EE7B7;
            --font-family: 'Inter', system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            --radius-md: 8px;
            --radius-lg: 12px;
            --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.08), 0 2px 4px -1px rgba(0, 0, 0, 0.04);
            --transition: all 0.2s ease-in-out;
        }

        *, *::before, *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        html, body {
            height: 100%;
            overflow: hidden;
        }

        body {
            font-family: var(--font-family);
            background-color: var(--color-ghost-white);
            color: var(--color-dark);
            -webkit-font-smoothing: antialiased;
        }

        .auth-wrapper {
            display: flex;
            width: 100vw;
            height: 100vh;
        }

        /* Left Panel - Branding & Organic Art Canvas */
        .branding-panel {
            flex: 1;
            background-color: var(--color-jade);
            color: var(--color-white);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 2rem;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        /* Abstract Flowy Organic Shapes Container */
        .bg-art-container {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            overflow: hidden;
            z-index: 1;
        }

        .branding-content {
            position: relative;
            z-index: 2;
        }

        .organic-shape {
            position: absolute;
            pointer-events: none;
        }

        /* Large Blob 1 */
        .blob-1 {
            top: -15%;
            left: -15%;
            width: 480px;
            height: 480px;
            filter: blur(12px);
            animation: floatBlob1 18s ease-in-out infinite alternate;
        }

        /* Large Blob 2 */
        .blob-2 {
            bottom: -20%;
            right: -15%;
            width: 520px;
            height: 520px;
            filter: blur(16px);
            animation: floatBlob2 22s ease-in-out infinite alternate;
        }

        /* Floating Soft Rounded Square with Drop Shadow */
        .floating-square {
            top: 18%;
            right: 10%;
            width: 140px;
            height: 140px;
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.22), rgba(255, 255, 255, 0.05));
            border: 1px solid rgba(255, 255, 255, 0.35);
            border-radius: 28px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.12);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            transform: rotate(14deg);
            animation: floatSquare 14s ease-in-out infinite alternate;
        }

        /* Capsule / Pill Shapes */
        .capsule-shape {
            bottom: 22%;
            left: 10%;
            width: 130px;
            height: 46px;
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.24), rgba(255, 255, 255, 0.08));
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 9999px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
            transform: rotate(-22deg);
            animation: floatCapsule 12s ease-in-out infinite alternate;
        }

        .capsule-shape-2 {
            top: 14%;
            left: 32%;
            width: 76px;
            height: 30px;
            background: rgba(255, 255, 255, 0.14);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 9999px;
            transform: rotate(35deg);
            animation: floatCapsule 16s ease-in-out infinite alternate-reverse;
        }

        /* Star / Splat Accent Shape */
        .star-splat {
            top: 66%;
            right: 22%;
            width: 54px;
            height: 54px;
            animation: floatStar 10s ease-in-out infinite alternate;
            filter: drop-shadow(0 6px 12px rgba(0, 0, 0, 0.1));
        }

        @keyframes floatBlob1 {
            0% { transform: translate(0, 0) rotate(0deg) scale(1); }
            100% { transform: translate(30px, 25px) rotate(15deg) scale(1.08); }
        }

        @keyframes floatBlob2 {
            0% { transform: translate(0, 0) rotate(0deg) scale(1); }
            100% { transform: translate(-35px, -20px) rotate(-18deg) scale(1.05); }
        }

        @keyframes floatSquare {
            0% { transform: translateY(0) rotate(14deg); }
            100% { transform: translateY(-18px) rotate(22deg); }
        }

        @keyframes floatCapsule {
            0% { transform: translateY(0) rotate(-22deg); }
            100% { transform: translateY(-14px) rotate(-14deg); }
        }

        @keyframes floatStar {
            0% { transform: translateY(0) rotate(0deg) scale(1); }
            100% { transform: translateY(-10px) rotate(45deg) scale(1.15); }
        }

        .brand-logo-large {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-size: 1.875rem;
            font-weight: 700;
            letter-spacing: -0.02em;
            margin-bottom: 1rem;
        }

        .brand-icon-large {
            width: 44px;
            height: 44px;
            background: rgba(255, 255, 255, 0.2);
            border: 2px solid rgba(255, 255, 255, 0.4);
            border-radius: var(--radius-lg);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .brand-icon-large svg {
            width: 24px;
            height: 24px;
            fill: none;
            stroke: var(--color-white);
            stroke-width: 2.2;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        .branding-tagline {
            font-size: 1rem;
            font-weight: 400;
            color: rgba(255, 255, 255, 0.9);
            max-width: 360px;
            line-height: 1.5;
        }

        /* Right Panel - Auth Forms */
        .form-panel {
            flex: 1;
            background-color: var(--color-ghost-white);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 1.5rem;
            height: 100%;
        }

        .form-container {
            width: 100%;
            max-width: 380px;
            background: transparent;
            padding: 0;
            border-radius: 0;
            box-shadow: none;
            border: none;
            transition: var(--transition);
        }

        .brand-logo-small {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 1.125rem;
            font-weight: 700;
            color: var(--color-dark);
            margin-bottom: 1rem;
        }

        .brand-icon-small {
            width: 28px;
            height: 28px;
            background-color: var(--color-jade-light);
            border-radius: var(--radius-md);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .brand-icon-small svg {
            width: 16px;
            height: 16px;
            stroke: var(--color-jade);
            stroke-width: 2.2;
            fill: none;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        .form-header {
            margin-bottom: 1.25rem;
        }

        .form-title {
            font-size: 1.375rem;
            font-weight: 700;
            color: var(--color-dark);
            letter-spacing: -0.015em;
            margin-bottom: 0.25rem;
        }

        .form-subtitle {
            font-size: 0.8125rem;
            color: var(--color-muted);
            line-height: 1.35;
        }

        /* Form Controls */
        .form-group {
            margin-bottom: 0.875rem;
        }

        .form-label {
            display: block;
            font-size: 0.8125rem;
            font-weight: 500;
            color: var(--color-dark);
            margin-bottom: 0.375rem;
        }

        .form-input {
            width: 100%;
            height: 38px;
            padding: 0 0.75rem;
            font-size: 0.875rem;
            font-family: inherit;
            color: var(--color-dark);
            background-color: var(--color-white);
            border: 1px solid var(--color-border);
            border-radius: var(--radius-md);
            outline: none;
            transition: var(--transition);
        }

        .form-input::placeholder {
            color: #9CA3AF;
        }

        .form-input:focus {
            border-color: var(--color-jade);
            box-shadow: 0 0 0 3px rgba(140, 13, 71, 0.15);
        }

        /* Checkbox & Forgot Link Row */
        .form-options {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1.25rem;
            margin-top: 0.25rem;
            margin-bottom: 1.125rem;
            font-size: 0.8125rem;
        }

        .checkbox-label {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: var(--color-dark);
            cursor: pointer;
            user-select: none;
            white-space: nowrap;
        }

        .checkbox-input {
            width: 15px;
            height: 15px;
            accent-color: var(--color-jade);
            cursor: pointer;
            border-radius: 4px;
        }

        .forgot-link {
            color: var(--color-jade);
            text-decoration: none;
            font-weight: 500;
            white-space: nowrap;
            transition: var(--transition);
        }

        .forgot-link:hover {
            color: var(--color-jade-hover);
            text-decoration: underline;
        }

        /* Buttons */
        .btn-primary {
            width: 100%;
            height: 40px;
            background-color: var(--color-jade);
            color: var(--color-white);
            font-family: inherit;
            font-size: 0.875rem;
            font-weight: 600;
            border: none;
            border-radius: var(--radius-md);
            cursor: pointer;
            transition: var(--transition);
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: var(--shadow-sm);
        }

        .btn-primary:hover {
            background-color: var(--color-jade-hover);
        }

        .btn-primary:focus-visible {
            outline: 2px solid var(--color-jade);
            outline-offset: 2px;
        }

        .btn-primary:disabled {
            opacity: 0.7;
            cursor: not-allowed;
        }

        /* Form Footer Link */
        .form-footer {
            margin-top: 1.125rem;
            text-align: center;
            font-size: 0.8125rem;
            color: var(--color-muted);
        }

        .toggle-btn {
            background: none;
            border: none;
            color: var(--color-jade);
            font-family: inherit;
            font-size: inherit;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            padding: 0;
            margin-left: 0.25rem;
            transition: var(--transition);
        }

        .toggle-btn:hover {
            color: var(--color-jade-hover);
            text-decoration: underline;
        }

        /* Alert Banners */
        .alert {
            padding: 0.625rem 0.875rem;
            border-radius: var(--radius-md);
            font-size: 0.8125rem;
            line-height: 1.35;
            margin-bottom: 1rem;
            display: none;
        }

        .alert-error {
            background-color: var(--color-error-bg);
            color: var(--color-error);
            border: 1px solid var(--color-error-border);
        }

        .alert-success {
            background-color: var(--color-success-bg);
            color: var(--color-success);
            border: 1px solid var(--color-success-border);
        }

        .auth-form {
            display: block;
        }

        .auth-form.hidden {
            display: none;
        }

        @media (max-width: 768px) {
            html, body {
                overflow-y: auto;
            }
            .auth-wrapper {
                flex-direction: column;
                height: auto;
                min-height: 100vh;
            }

            .branding-panel {
                flex: none;
                padding: 2.5rem 1.25rem;
            }

            .form-panel {
                flex: 1;
                padding: 1.25rem 1rem;
            }

            .form-container {
                padding: 0;
                box-shadow: none;
                border: none;
            }
        }
    </style>
</head>
<body>
    <div class="auth-wrapper">
        <!-- Left Panel: Branding & Abstract Organic Art Canvas -->
        <section class="branding-panel">
            <!-- Organic Flowy Background Art Shapes -->
            <div class="bg-art-container" aria-hidden="true">
                <!-- Large Blob 1 -->
                <svg class="organic-shape blob-1" viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
                    <path fill="rgba(255, 255, 255, 0.18)" d="M44.7,-59.4C57.2,-49.8,66.1,-35.1,70.3,-19.1C74.6,-3.1,74.2,14.2,67.3,28.8C60.4,43.4,47,55.3,31.7,62.8C16.4,70.3,-0.8,73.4,-18.2,70.4C-35.6,67.4,-53.2,58.3,-64.1,43.8C-75,29.3,-79.2,9.4,-76.3,-9.1C-73.4,-27.6,-63.4,-44.7,-49.2,-54.6C-35,-64.5,-17.5,-67.2,-0.1,-67.1C17.3,-67,32.2,-69,44.7,-59.4Z" transform="translate(100 100)" />
                </svg>

                <!-- Large Blob 2 -->
                <svg class="organic-shape blob-2" viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
                    <path fill="rgba(255, 255, 255, 0.12)" d="M39.9,-54.2C51.9,-44.6,61.9,-32.2,66.3,-17.9C70.7,-3.6,69.5,12.6,63.1,26.8C56.7,41,45.1,53.2,31.2,60.8C17.3,68.4,1.1,71.4,-15.8,69.2C-32.7,67,-50.3,59.6,-61.2,46.2C-72.1,32.8,-76.3,13.4,-74.6,-5C-72.9,-23.4,-65.3,-40.8,-52.3,-50.8C-39.3,-60.8,-20.9,-63.4,-3.6,-58.4C13.7,-53.4,27.9,-63.8,39.9,-54.2Z" transform="translate(100 100)" />
                </svg>

                <!-- Floating Soft Rounded Square with Drop Shadow -->
                <div class="organic-shape floating-square"></div>

                <!-- Capsule / Pill Shapes -->
                <div class="organic-shape capsule-shape"></div>
                <div class="organic-shape capsule-shape-2"></div>

                <!-- Star / Splat Accent SVG Shape -->
                <svg class="organic-shape star-splat" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
                    <path fill="rgba(255, 255, 255, 0.28)" d="M50 0 C53 35 65 47 100 50 C65 53 53 65 50 100 C47 65 35 53 0 50 C35 47 47 35 50 0 Z" />
                </svg>
            </div>

            <!-- Branding Content -->
            <div class="branding-content">
                <div class="brand-logo-large">
                    <div class="brand-icon-large">
                        <svg viewBox="0 0 24 24">
                            <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"></path>
                        </svg>
                    </div>
                    <span>Cuatro Marias</span>
                </div>
                <p class="branding-tagline">
                    Official Student Organization at Polytechnic University of the Philippines — Santa Rosa Campus.
                </p>
            </div>
        </section>

        <!-- Right Panel: Auth Forms -->
        <main class="form-panel">
            <div class="form-container">
                <div class="brand-logo-small">
                    <div class="brand-icon-small">
                        <svg viewBox="0 0 24 24">
                            <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"></path>
                        </svg>
                    </div>
                    <span>Cuatro Marias</span>
                </div>

                <!-- Alert Banners -->
                <div id="alert-error" class="alert alert-error" role="alert"></div>
                <div id="alert-success" class="alert alert-success" role="alert"></div>

                <!-- LOGIN FORM -->
                <form id="login-form" class="auth-form" novalidate>
                    <div class="form-header">
                        <h1 class="form-title">Welcome back</h1>
                        <p class="form-subtitle">Please enter your details to sign in to your account.</p>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="login-email">Email address</label>
                        <input 
                            type="email" 
                            id="login-email" 
                            class="form-input" 
                            placeholder="name@example.com" 
                            autocomplete="email" 
                            required
                        >
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="login-password">Password</label>
                        <input 
                            type="password" 
                            id="login-password" 
                            class="form-input" 
                            placeholder="••••••••" 
                            autocomplete="current-password" 
                            required
                        >
                    </div>

                    <div class="form-options">
                        <label class="checkbox-label">
                            <input type="checkbox" id="login-remember" class="checkbox-input">
                            <span>Remember me</span>
                        </label>
                        <a href="#" id="forgot-password-link" class="forgot-link">Forgot password?</a>
                    </div>

                    <button type="submit" id="login-btn" class="btn-primary">Log In</button>

                    <div class="form-footer">
                        <span>Don't have an account?</span>
                        <button type="button" class="toggle-btn" id="go-to-signup">Sign up</button>
                    </div>
                </form>

                <!-- SIGNUP FORM -->
                <form id="signup-form" class="auth-form hidden" novalidate>
                    <div class="form-header">
                        <h1 class="form-title">Teacher Registration</h1>
                        <p class="form-subtitle">Register a Teacher account for BSIT 3rd Year Attendance (pending admin approval).</p>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="signup-name">Full name</label>
                        <input 
                            type="text" 
                            id="signup-name" 
                            class="form-input" 
                            placeholder="Alex Morgan" 
                            autocomplete="name" 
                            required
                        >
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="signup-email">Email address</label>
                        <input 
                            type="email" 
                            id="signup-email" 
                            class="form-input" 
                            placeholder="name@example.com" 
                            autocomplete="email" 
                            required
                        >
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="signup-password">Password</label>
                        <input 
                            type="password" 
                            id="signup-password" 
                            class="form-input" 
                            placeholder="At least 6 characters" 
                            autocomplete="new-password" 
                            required
                        >
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="signup-confirm-password">Confirm password</label>
                        <input 
                            type="password" 
                            id="signup-confirm-password" 
                            class="form-input" 
                            placeholder="Re-enter password" 
                            autocomplete="new-password" 
                            required
                        >
                    </div>

                    <button type="submit" id="signup-btn" class="btn-primary">Create Account</button>

                    <div class="form-footer">
                        <span>Already have an account?</span>
                        <button type="button" class="toggle-btn" id="go-to-login">Log in</button>
                    </div>
                </form>
            </div>
        </main>
    </div>

    <!-- Frontend Interactive Script -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            const loginForm = document.getElementById('login-form');
            const signupForm = document.getElementById('signup-form');
            const loginBtn = document.getElementById('login-btn');
            const signupBtn = document.getElementById('signup-btn');

            const goToSignupBtn = document.getElementById('go-to-signup');
            const goToLoginBtn = document.getElementById('go-to-login');
            const alertError = document.getElementById('alert-error');
            const alertSuccess = document.getElementById('alert-success');
            const forgotPasswordLink = document.getElementById('forgot-password-link');

            function hideAlerts() {
                alertError.style.display = 'none';
                alertError.textContent = '';
                alertSuccess.style.display = 'none';
                alertSuccess.textContent = '';
            }

            function showError(message) {
                hideAlerts();
                alertError.textContent = message;
                alertError.style.display = 'block';
            }

            function showSuccess(message) {
                hideAlerts();
                alertSuccess.textContent = message;
                alertSuccess.style.display = 'block';
            }

            function validateEmail(email) {
                const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                return re.test(String(email).toLowerCase());
            }

            // Form Switching
            goToSignupBtn.addEventListener('click', () => {
                hideAlerts();
                loginForm.classList.add('hidden');
                signupForm.classList.remove('hidden');
            });

            goToLoginBtn.addEventListener('click', () => {
                hideAlerts();
                signupForm.classList.add('hidden');
                loginForm.classList.remove('hidden');
            });

            // Login Submit Handler
            loginForm.addEventListener('submit', async (e) => {
                e.preventDefault();
                hideAlerts();

                const email = document.getElementById('login-email').value.trim();
                const password = document.getElementById('login-password').value;

                if (!email) {
                    showError('Please enter your email address.');
                    return;
                }

                if (!validateEmail(email)) {
                    showError('Please enter a valid email address.');
                    return;
                }

                if (!password) {
                    showError('Please enter your password.');
                    return;
                }

                loginBtn.disabled = true;
                loginBtn.textContent = 'Logging in...';

                try {
                    const response = await fetch('/api/login', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ email, password })
                    });

                    const data = await response.json();

                    if (response.ok && data.success) {
                        window.location.href = data.redirect || '/landing';
                    } else {
                        showError(data.message || 'Invalid email or password.');
                    }
                } catch (err) {
                    showError('Connection error. Please try again.');
                } finally {
                    loginBtn.disabled = false;
                    loginBtn.textContent = 'Log In';
                }
            });

            // Signup Submit Handler -> Brings user back to login page
            signupForm.addEventListener('submit', async (e) => {
                e.preventDefault();
                hideAlerts();

                const name = document.getElementById('signup-name').value.trim();
                const email = document.getElementById('signup-email').value.trim();
                const password = document.getElementById('signup-password').value;
                const confirmPassword = document.getElementById('signup-confirm-password').value;

                if (!name) {
                    showError('Please enter your full name.');
                    return;
                }

                if (!email) {
                    showError('Please enter your email address.');
                    return;
                }

                if (!validateEmail(email)) {
                    showError('Please enter a valid email address.');
                    return;
                }

                if (!password) {
                    showError('Please enter a password.');
                    return;
                }

                if (password.length < 6) {
                    showError('Password must be at least 6 characters long.');
                    return;
                }

                if (password !== confirmPassword) {
                    showError('Passwords do not match. Please check and try again.');
                    return;
                }

                signupBtn.disabled = true;
                signupBtn.textContent = 'Creating account...';

                try {
                    const response = await fetch('/api/signup', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ name, email, password })
                    });

                    const data = await response.json();

                    if (response.ok && data.success) {
                        document.getElementById('signup-form').reset();
                        document.getElementById('login-email').value = email;
                        document.getElementById('login-password').value = '';
                        
                        signupForm.classList.add('hidden');
                        loginForm.classList.remove('hidden');
                        
                        showSuccess(data.message || 'Account created successfully! Please log in below.');
                    } else {
                        showError(data.message || 'Registration failed.');
                    }
                } catch (err) {
                    showError('Connection error. Please try again.');
                } finally {
                    signupBtn.disabled = false;
                    signupBtn.textContent = 'Create Account';
                }
            });

            forgotPasswordLink.addEventListener('click', (e) => {
                e.preventDefault();
                showSuccess('Password reset notification sent.');
            });
        });
    </script>
</body>
</html>
