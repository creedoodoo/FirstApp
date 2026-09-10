<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Verdant Technologies — Official Portal</title>

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
            --color-red: #EF4444;
            --color-red-hover: #DC2626;
            --color-warning-bg: #FFFBEB;
            --font-family: 'Inter', system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            --radius-md: 8px;
            --radius-lg: 12px;
            --radius-xl: 16px;
            --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.08), 0 2px 4px -1px rgba(0, 0, 0, 0.04);
            --shadow-lg: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1);
            --transition: all 0.2s ease-in-out;
        }

        *, *::before, *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: var(--font-family);
            background-color: var(--color-ghost-white);
            color: var(--color-dark);
            line-height: 1.6;
            -webkit-font-smoothing: antialiased;
        }

        /* Glassmorphism Fixed Header Navigation Bar */
        .navbar {
            position: sticky;
            top: 0;
            z-index: 1000;
            background: rgba(255, 255, 255, 0.78);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(229, 231, 235, 0.8);
            padding: 0.875rem 2.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
            transition: var(--transition);
        }

        .nav-left {
            display: flex;
            align-items: center;
            gap: 2.5rem;
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
            width: 34px;
            height: 34px;
            background-color: var(--color-jade-light);
            border-radius: var(--radius-md);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .brand-icon svg {
            width: 20px;
            height: 20px;
            stroke: var(--color-jade);
            stroke-width: 2.2;
            fill: none;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        .nav-menu {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            list-style: none;
        }

        .nav-link {
            text-decoration: none;
            color: var(--color-muted);
            font-size: 0.9375rem;
            font-weight: 500;
            padding: 0.4rem 0.875rem;
            border-radius: var(--radius-md);
            transition: var(--transition);
        }

        /* Green Highlight for Current Active Section */
        .nav-link:hover,
        .nav-link.active {
            color: var(--color-jade);
            background-color: var(--color-jade-light);
            font-weight: 600;
        }

        .nav-right {
            display: flex;
            align-items: center;
            gap: 1.25rem;
        }

        .user-name-badge {
            font-size: 0.9375rem;
            font-weight: 600;
            color: var(--color-dark);
            background-color: #F3F4F6;
            padding: 0.35rem 0.875rem;
            border-radius: 9999px;
            border: 1px solid var(--color-border);
        }

        /* Red Sign Out Button Icon */
        .btn-signout-red {
            background-color: var(--color-red);
            color: var(--color-white);
            border: none;
            width: 38px;
            height: 38px;
            border-radius: var(--radius-md);
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: var(--transition);
            box-shadow: var(--shadow-sm);
        }

        .btn-signout-red:hover {
            background-color: var(--color-red-hover);
            transform: scale(1.05);
        }

        /* Modal Overlay & Dialog */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background: rgba(15, 23, 42, 0.45);
            backdrop-filter: blur(4px);
            z-index: 2000;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
            animation: fadeIn 0.2s ease-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        .modal-card {
            background: var(--color-white);
            border-radius: var(--radius-xl);
            max-width: 400px;
            width: 100%;
            padding: 2rem;
            box-shadow: var(--shadow-lg);
            border: 1px solid var(--color-border);
            text-align: center;
        }

        .modal-icon-wrap {
            width: 52px;
            height: 52px;
            background-color: #FEF2F2;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.25rem;
        }

        .modal-title {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--color-dark);
            margin-bottom: 0.5rem;
        }

        .modal-subtitle {
            font-size: 0.875rem;
            color: var(--color-muted);
            margin-bottom: 1.75rem;
            line-height: 1.5;
        }

        .modal-actions {
            display: flex;
            gap: 0.875rem;
        }

        .btn-modal-neutral {
            flex: 1;
            height: 42px;
            background-color: #F3F4F6;
            color: #374151;
            border: 1px solid var(--color-border);
            border-radius: var(--radius-md);
            font-family: inherit;
            font-size: 0.875rem;
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
        }

        .btn-modal-neutral:hover {
            background-color: #E5E7EB;
            color: var(--color-dark);
        }

        .btn-modal-red {
            width: 100%;
            height: 42px;
            background-color: var(--color-red);
            color: var(--color-white);
            border: none;
            border-radius: var(--radius-md);
            font-family: inherit;
            font-size: 0.875rem;
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
        }

        .btn-modal-red:hover {
            background-color: var(--color-red-hover);
        }

        /* Sections & Layout */
        .section {
            padding: 4.5rem 2rem;
            max-width: 1140px;
            margin: 0 auto;
        }

        /* Hero Section */
        .hero-section {
            text-align: center;
            padding: 5rem 1.5rem 4rem;
        }

        .hero-badge {
            display: inline-block;
            background-color: var(--color-jade-light);
            color: var(--color-jade);
            font-size: 0.8125rem;
            font-weight: 600;
            padding: 0.35rem 1rem;
            border-radius: 9999px;
            margin-bottom: 1.25rem;
        }

        .hero-title {
            font-size: 2.75rem;
            font-weight: 800;
            color: var(--color-dark);
            line-height: 1.2;
            letter-spacing: -0.025em;
            max-width: 820px;
            margin: 0 auto 1.25rem;
        }

        .hero-paragraph {
            font-size: 1.125rem;
            color: var(--color-muted);
            max-width: 680px;
            margin: 0 auto 2.5rem;
            line-height: 1.7;
        }

        .hero-cta {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 1rem;
            margin-bottom: 3.5rem;
        }

        .btn-hero-primary {
            background-color: var(--color-jade);
            color: var(--color-white);
            padding: 0.875rem 2rem;
            border-radius: var(--radius-md);
            font-weight: 600;
            text-decoration: none;
            transition: var(--transition);
        }

        .btn-hero-primary:hover {
            background-color: var(--color-jade-hover);
        }

        .btn-hero-secondary {
            background-color: var(--color-white);
            color: var(--color-dark);
            border: 1px solid var(--color-border);
            padding: 0.875rem 2rem;
            border-radius: var(--radius-md);
            font-weight: 600;
            text-decoration: none;
            transition: var(--transition);
        }

        .btn-hero-secondary:hover {
            background-color: #F9FAFB;
        }

        /* Blank Image Template Component */
        .image-template {
            background-color: #E5E7EB;
            border: 2px dashed #CBD5E1;
            border-radius: var(--radius-xl);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: #64748B;
            padding: 3rem 1.5rem;
            position: relative;
            overflow: hidden;
        }

        .image-template-hero {
            width: 100%;
            height: 400px;
            max-width: 960px;
            margin: 0 auto;
        }

        .image-template-card {
            width: 100%;
            height: 220px;
            margin-bottom: 1.25rem;
            border-radius: var(--radius-lg);
        }

        .image-template-about {
            width: 100%;
            height: 380px;
        }

        .template-icon {
            width: 48px;
            height: 48px;
            stroke: #94A3B8;
            stroke-width: 1.5;
            fill: none;
            margin-bottom: 0.75rem;
        }

        .template-text {
            font-size: 0.875rem;
            font-weight: 600;
            color: #64748B;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        /* About Section */
        .about-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 3.5rem;
            align-items: center;
        }

        .section-tag {
            color: var(--color-jade);
            font-size: 0.875rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 0.5rem;
        }

        .section-title {
            font-size: 2.125rem;
            font-weight: 700;
            color: var(--color-dark);
            margin-bottom: 1.25rem;
            letter-spacing: -0.015em;
        }

        .about-text p {
            color: var(--color-muted);
            font-size: 1rem;
            margin-bottom: 1.25rem;
            line-height: 1.7;
        }

        /* Solutions Cards */
        .solutions-header {
            text-align: center;
            max-width: 600px;
            margin: 0 auto 3rem;
        }

        .solutions-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 2rem;
        }

        .solution-card {
            background-color: var(--color-white);
            border: 1px solid var(--color-border);
            border-radius: var(--radius-xl);
            padding: 1.75rem;
            box-shadow: var(--shadow-sm);
            transition: var(--transition);
        }

        .solution-card:hover {
            box-shadow: var(--shadow-md);
            transform: translateY(-2px);
        }

        .card-title {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--color-dark);
            margin-bottom: 0.5rem;
        }

        .card-desc {
            font-size: 0.9375rem;
            color: var(--color-muted);
            line-height: 1.6;
        }

        /* Interactive Trial Limiter Section */
        .trial-section {
            background-color: var(--color-white);
            border: 1px solid var(--color-border);
            border-radius: var(--radius-xl);
            padding: 3rem 2.5rem;
            box-shadow: var(--shadow-sm);
            margin: 4rem auto;
            max-width: 900px;
        }

        .trial-grid {
            display: grid;
            grid-template-columns: 1.2fr 1fr;
            gap: 2.5rem;
            align-items: center;
        }

        .trial-display {
            display: flex;
            align-items: baseline;
            gap: 0.5rem;
            margin-bottom: 0.875rem;
        }

        .trial-number {
            font-size: 3rem;
            font-weight: 800;
            color: var(--color-jade);
            line-height: 1;
        }

        .trial-label {
            font-size: 1rem;
            color: var(--color-muted);
            font-weight: 500;
        }

        .progress-bar-container {
            width: 100%;
            height: 12px;
            background-color: #E5E7EB;
            border-radius: 9999px;
            overflow: hidden;
            margin-bottom: 1.5rem;
        }

        .progress-bar-fill {
            height: 100%;
            background-color: var(--color-jade);
            width: {{ ($user->trial_uses_left / 5) * 100 }}%;
            transition: width 0.3s ease-in-out;
        }

        .btn-action {
            width: 100%;
            height: 46px;
            background-color: var(--color-jade);
            color: var(--color-white);
            border: none;
            border-radius: var(--radius-md);
            font-family: inherit;
            font-size: 0.9375rem;
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
        }

        .btn-action:hover {
            background-color: var(--color-jade-hover);
        }

        .btn-action:disabled {
            background-color: #9CA3AF;
            cursor: not-allowed;
        }

        .trial-warning {
            margin-top: 1rem;
            padding: 0.875rem;
            background-color: var(--color-warning-bg);
            border: 1px solid #FCD34D;
            border-radius: var(--radius-md);
            color: #B45309;
            font-size: 0.875rem;
            display: {{ $user->trial_uses_left <= 0 ? 'block' : 'none' }};
        }

        /* Contact Section */
        .contact-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 3rem;
        }

        .contact-info-card {
            background-color: var(--color-white);
            border: 1px solid var(--color-border);
            border-radius: var(--radius-xl);
            padding: 2.5rem;
        }

        .contact-detail {
            margin-bottom: 1.5rem;
        }

        .contact-detail-label {
            font-size: 0.8125rem;
            font-weight: 700;
            text-transform: uppercase;
            color: var(--color-muted);
            margin-bottom: 0.25rem;
        }

        .contact-detail-val {
            font-size: 1.0625rem;
            font-weight: 600;
            color: var(--color-dark);
        }

        /* Footer */
        .footer {
            background-color: var(--color-white);
            border-top: 1px solid var(--color-border);
            padding: 2.5rem 2rem;
            text-align: center;
            font-size: 0.875rem;
            color: var(--color-muted);
            margin-top: 5rem;
        }

        @media (max-width: 868px) {
            .navbar {
                padding: 0.875rem 1.25rem;
            }
            .nav-menu {
                display: none;
            }
            .about-grid, .contact-grid, .trial-grid {
                grid-template-columns: 1fr;
                gap: 2rem;
            }
            .hero-title {
                font-size: 2rem;
            }
            .image-template-hero {
                height: 260px;
            }
        }
    </style>
</head>
<body>
    <!-- Glassmorphism Fixed Top Navigation Header Bar -->
    <header class="navbar">
        <div class="nav-left">
            <a href="#home" class="nav-brand">
                <div class="brand-icon">
                    <svg viewBox="0 0 24 24">
                        <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"></path>
                    </svg>
                </div>
                <span>Verdant</span>
            </a>

            <ul class="nav-menu">
                <li><a href="#home" class="nav-link active">Home</a></li>
                <li><a href="#about" class="nav-link">About</a></li>
                <li><a href="#solutions" class="nav-link">Solutions</a></li>
                <li><a href="#contact" class="nav-link">Contact</a></li>
            </ul>
        </div>

        <div class="nav-right">
            <!-- User Name Badge -->
            <div class="user-name-badge">
                {{ $user->name }}
            </div>

            <!-- Red Sign Out Button with Lucide Logout SVG Icon -->
            <button type="button" id="open-logout-modal-btn" class="btn-signout-red" title="Sign Out" aria-label="Sign Out">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-log-out"><path d="m16 17 5-5-5-5"/><path d="M21 12H9"/><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/></svg>
            </button>
        </div>
    </header>

    <!-- Sign Out Confirmation Modal Overlay -->
    <div id="logout-modal" class="modal-overlay" style="display: none;">
        <div class="modal-card">
            <div class="modal-icon-wrap">
                <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="#EF4444" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-log-out"><path d="m16 17 5-5-5-5"/><path d="M21 12H9"/><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/></svg>
            </div>
            <h3 class="modal-title">Confirm Sign Out</h3>
            <p class="modal-subtitle">Are you sure you want to sign out of your account?</p>
            
            <div class="modal-actions">
                <button type="button" id="cancel-logout-btn" class="btn-modal-neutral">Cancel</button>
                <form action="{{ route('logout') }}" method="POST" style="margin: 0; flex: 1;">
                    @csrf
                    <button type="submit" class="btn-modal-red">Sign Out</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Hero Section -->
    <section id="home" class="section hero-section">
        <span class="hero-badge">Next-Generation Enterprise Platform</span>
        <h1 class="hero-title">Empowering Modern Teams with Intelligent Automation</h1>
        <p class="hero-paragraph">
            Verdant delivers high-performance infrastructure designed to streamline digital workflows, enhance team collaboration, and accelerate scalable corporate growth.
        </p>

        <div class="hero-cta">
            <a href="#solutions" class="btn-hero-primary">Explore Solutions</a>
            <a href="#about" class="btn-hero-secondary">Learn More</a>
        </div>

        <!-- Blank Image Template: Hero Banner -->
        <div class="image-template image-template-hero">
            <svg class="template-icon" viewBox="0 0 24 24">
                <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                <circle cx="8.5" cy="8.5" r="1.5"></circle>
                <polyline points="21 15 16 10 5 21"></polyline>
            </svg>
            <span class="template-text">[ Company Hero Banner Placeholder — 960x400 ]</span>
        </div>
    </section>

    <!-- About Section -->
    <section id="about" class="section">
        <div class="about-grid">
            <div class="about-text">
                <span class="section-tag">About Verdant</span>
                <h2 class="section-title">Built on Innovation and Absolute Reliability</h2>
                <p>
                    Founded with a vision to redefine enterprise technology, Verdant equips organizations with intuitive solutions engineered to solve complex operational challenges.
                </p>
                <p>
                    Our platform integrates modern architecture with industry-leading reliability, providing your teams with the agility needed to succeed in an evolving marketplace.
                </p>
            </div>

            <!-- Blank Image Template: About Us -->
            <div class="image-template image-template-about">
                <svg class="template-icon" viewBox="0 0 24 24">
                    <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                    <circle cx="8.5" cy="8.5" r="1.5"></circle>
                    <polyline points="21 15 16 10 5 21"></polyline>
                </svg>
                <span class="template-text">[ About Us Image Template — 500x380 ]</span>
            </div>
        </div>
    </section>

    <!-- Solutions Section -->
    <section id="solutions" class="section">
        <div class="solutions-header">
            <span class="section-tag">Our Expertise</span>
            <h2 class="section-title">Comprehensive Enterprise Solutions</h2>
            <p style="color: var(--color-muted);">Tailored technologies built to drive growth and efficiency for your business.</p>
        </div>

        <div class="solutions-grid">
            <div class="solution-card">
                <!-- Blank Image Template: Feature 1 -->
                <div class="image-template image-template-card">
                    <svg class="template-icon" viewBox="0 0 24 24">
                        <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                        <circle cx="8.5" cy="8.5" r="1.5"></circle>
                        <polyline points="21 15 16 10 5 21"></polyline>
                    </svg>
                    <span class="template-text">[ Feature Template — 360x220 ]</span>
                </div>
                <h3 class="card-title">Cloud Infrastructure</h3>
                <p class="card-desc">Scalable, high-availability server architecture engineered to handle workload demands with minimal latency.</p>
            </div>

            <div class="solution-card">
                <!-- Blank Image Template: Feature 2 -->
                <div class="image-template image-template-card">
                    <svg class="template-icon" viewBox="0 0 24 24">
                        <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                        <circle cx="8.5" cy="8.5" r="1.5"></circle>
                        <polyline points="21 15 16 10 5 21"></polyline>
                    </svg>
                    <span class="template-text">[ Feature Template — 360x220 ]</span>
                </div>
                <h3 class="card-title">Automated Workflows</h3>
                <p class="card-desc">Eliminate manual processes with intelligent automation tools designed to optimize productivity across teams.</p>
            </div>

            <div class="solution-card">
                <!-- Blank Image Template: Feature 3 -->
                <div class="image-template image-template-card">
                    <svg class="template-icon" viewBox="0 0 24 24">
                        <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                        <circle cx="8.5" cy="8.5" r="1.5"></circle>
                        <polyline points="21 15 16 10 5 21"></polyline>
                    </svg>
                    <span class="template-text">[ Feature Template — 360x220 ]</span>
                </div>
                <h3 class="card-title">Data Intelligence</h3>
                <p class="card-desc">Gain actionable insights with real-time analytics dashboards tailored to your organization's key metrics.</p>
            </div>
        </div>
    </section>

    <!-- Interactive Trial Limiter Section -->
    <section class="section">
        <div class="trial-section">
            <div class="trial-grid">
                <div>
                    <span class="section-tag">Interactive Feature Trial</span>
                    <h2 class="section-title" style="font-size: 1.75rem; margin-bottom: 0.75rem;">Account Trial Limiter</h2>
                    <p style="color: var(--color-muted); font-size: 0.9375rem; margin-bottom: 1.25rem;">
                        Test our live action execution feature. Every newly registered account starts with 5 complimentary trial action credits.
                    </p>

                    <div id="trial-warning" class="trial-warning">
                        ⚠️ <strong>Trial Limit Reached!</strong> You have consumed all 5 free action credits for this account.
                    </div>
                </div>

                <div>
                    <div class="trial-display">
                        <span id="trial-count" class="trial-number">{{ $user->trial_uses_left }}</span>
                        <span class="trial-label">/ 5 Action Credits Left</span>
                    </div>

                    <div class="progress-bar-container">
                        <div id="progress-bar" class="progress-bar-fill"></div>
                    </div>

                    <button type="button" id="use-trial-btn" class="btn-action" {{ $user->trial_uses_left <= 0 ? 'disabled' : '' }}>
                        Execute Action (Use 1 Credit)
                    </button>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="section">
        <div class="contact-grid">
            <div class="contact-info-card">
                <span class="section-tag">Get In Touch</span>
                <h2 class="section-title">Contact Our Team</h2>
                <p style="color: var(--color-muted); margin-bottom: 2rem;">Have questions about our solutions? Reach out to our dedicated support representatives.</p>

                <div class="contact-detail">
                    <div class="contact-detail-label">Headquarters</div>
                    <div class="contact-detail-val">100 Tech Plaza, Suite 400, Innovation District</div>
                </div>

                <div class="contact-detail">
                    <div class="contact-detail-label">Email Inquiries</div>
                    <div class="contact-detail-val">contact@verdant-tech.com</div>
                </div>

                <div class="contact-detail">
                    <div class="contact-detail-label">Customer Support</div>
                    <div class="contact-detail-val">+1 (800) 555-0199</div>
                </div>
            </div>

            <!-- Blank Image Template: Map / Contact Placeholder -->
            <div class="image-template" style="height: 100%; min-height: 320px;">
                <svg class="template-icon" viewBox="0 0 24 24">
                    <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                    <circle cx="8.5" cy="8.5" r="1.5"></circle>
                    <polyline points="21 15 16 10 5 21"></polyline>
                </svg>
                <span class="template-text">[ Office Location & Map Image Template ]</span>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <p>&copy; {{ date('Y') }} Verdant Technologies Inc. All rights reserved. Empowering modern workflows.</p>
    </footer>

    <!-- Interactive Scripts -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            // 1. Scroll Highlighting for Active Navbar Section
            const sections = document.querySelectorAll('section[id]');
            const navLinks = document.querySelectorAll('.nav-link');

            function highlightNavOnScroll() {
                const scrollY = window.pageYOffset;

                sections.forEach(current => {
                    const sectionHeight = current.offsetHeight;
                    const sectionTop = current.offsetTop - 120;
                    const sectionId = current.getAttribute('id');

                    if (scrollY > sectionTop && scrollY <= sectionTop + sectionHeight) {
                        navLinks.forEach(link => {
                            if (link.getAttribute('href') === `#${sectionId}`) {
                                link.classList.add('active');
                            } else {
                                link.classList.remove('active');
                            }
                        });
                    }
                });
            }

            window.addEventListener('scroll', highlightNavOnScroll);

            // 2. Sign Out Confirmation Modal Handlers
            const openLogoutBtn = document.getElementById('open-logout-modal-btn');
            const cancelLogoutBtn = document.getElementById('cancel-logout-btn');
            const logoutModal = document.getElementById('logout-modal');

            openLogoutBtn.addEventListener('click', () => {
                logoutModal.style.display = 'flex';
            });

            cancelLogoutBtn.addEventListener('click', () => {
                logoutModal.style.display = 'none';
            });

            logoutModal.addEventListener('click', (e) => {
                if (e.target === logoutModal) {
                    logoutModal.style.display = 'none';
                }
            });

            // 3. Trial Limiter Action Handler
            const useTrialBtn = document.getElementById('use-trial-btn');
            const trialCountElem = document.getElementById('trial-count');
            const progressBar = document.getElementById('progress-bar');
            const trialWarning = document.getElementById('trial-warning');

            useTrialBtn.addEventListener('click', async () => {
                useTrialBtn.disabled = true;
                useTrialBtn.textContent = 'Processing...';

                try {
                    const response = await fetch('/api/trial/use', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json'
                        }
                    });

                    const data = await response.json();

                    if (response.ok && data.success) {
                        const newCount = data.trial_uses_left;
                        trialCountElem.textContent = newCount;

                        const percentage = (newCount / 5) * 100;
                        progressBar.style.width = percentage + '%';

                        if (newCount <= 0) {
                            useTrialBtn.disabled = true;
                            useTrialBtn.textContent = 'Trial Expired';
                            trialWarning.style.display = 'block';
                        } else {
                            useTrialBtn.disabled = false;
                            useTrialBtn.textContent = 'Execute Action (Use 1 Credit)';
                        }
                    } else {
                        alert(data.message || 'Unable to consume trial credit.');
                        useTrialBtn.disabled = true;
                        useTrialBtn.textContent = 'Trial Expired';
                        trialWarning.style.display = 'block';
                    }
                } catch (err) {
                    alert('Network error. Please try again.');
                    useTrialBtn.disabled = false;
                    useTrialBtn.textContent = 'Execute Action (Use 1 Credit)';
                }
            });
        });
    </script>
</body>
</html>
