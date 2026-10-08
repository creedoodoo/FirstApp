<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>BSIT 3rd Year Attendance System — PUP Santa Rosa Campus</title>

    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,200..800;1,200..800&display=swap" rel="stylesheet">

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
            --font-family: 'Plus Jakarta Sans', system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            --radius-md: 8px;
            --radius-lg: 12px;
            --radius-xl: 16px;
            --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.08), 0 2px 4px -1px rgba(0, 0, 0, 0.04);
            --shadow-lg: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1);
            --transition: all 0.2s ease-in-out;
        }

        button, .btn-hero-primary, .btn-hero-secondary, .btn-signout-red, .btn-modal-neutral, .btn-modal-red, .btn-action, .btn-map-link, .nav-link {
            white-space: nowrap !important;
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
            background-image: 
                linear-gradient(to right, rgba(140, 13, 71, 0.035) 1px, transparent 1px),
                linear-gradient(to bottom, rgba(140, 13, 71, 0.035) 1px, transparent 1px);
            background-size: 36px 36px;
            color: var(--color-dark);
            line-height: 1.6;
            -webkit-font-smoothing: antialiased;
            position: relative;
        }

        /* Idle Floating Organic Background Shapes Container */
        .dash-art-container {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            pointer-events: none;
            overflow: hidden;
            z-index: 0;
        }

        .dash-shape {
            position: absolute;
            pointer-events: none;
            will-change: transform;
        }

        /* Soft Blurred Circle 1 (Top Left) */
        .dash-blob-1 {
            top: 6%;
            left: 2%;
            width: 220px;
            height: 220px;
            background: radial-gradient(circle, rgba(140, 13, 71, 0.12) 0%, rgba(140, 13, 71, 0.03) 70%, transparent 100%);
            border-radius: 50%;
            filter: blur(14px);
            animation: dashFloat1 14s ease-in-out infinite alternate;
        }

        /* Floating Glass Square (Top Right) */
        .dash-square-1 {
            top: 12%;
            right: 4%;
            width: 86px;
            height: 86px;
            background: linear-gradient(135deg, rgba(140, 13, 71, 0.12), rgba(140, 13, 71, 0.04));
            border: 1px solid rgba(140, 13, 71, 0.18);
            border-radius: 20px;
            box-shadow: 0 10px 25px rgba(140, 13, 71, 0.06);
            backdrop-filter: blur(6px);
            -webkit-backdrop-filter: blur(6px);
            transform: rotate(14deg);
            animation: dashFloat2 10s ease-in-out infinite alternate;
        }

        /* Capsule / Pill Shape 1 (Middle Left) */
        .dash-capsule-1 {
            top: 45%;
            left: 2%;
            width: 90px;
            height: 34px;
            background: linear-gradient(135deg, rgba(140, 13, 71, 0.14), rgba(140, 13, 71, 0.04));
            border: 1px solid rgba(140, 13, 71, 0.16);
            border-radius: 9999px;
            transform: rotate(-20deg);
            animation: dashFloat3 12s ease-in-out infinite alternate;
        }

        /* Sparkle / Splat Accent (Middle Right) */
        .dash-splat-1 {
            top: 55%;
            right: 3%;
            width: 42px;
            height: 42px;
            animation: dashFloat4 8s ease-in-out infinite alternate;
        }

        /* Soft Blurred Circle 2 (Bottom Right) */
        .dash-blob-2 {
            bottom: 8%;
            right: 5%;
            width: 260px;
            height: 260px;
            background: radial-gradient(circle, rgba(140, 13, 71, 0.1) 0%, rgba(140, 13, 71, 0.02) 70%, transparent 100%);
            border-radius: 50%;
            filter: blur(18px);
            animation: dashFloat1 18s ease-in-out infinite alternate-reverse;
        }

        /* Capsule / Pill Shape 2 (Bottom Left) */
        .dash-capsule-2 {
            bottom: 12%;
            left: 5%;
            width: 65px;
            height: 26px;
            background: rgba(140, 13, 71, 0.1);
            border: 1px solid rgba(140, 13, 71, 0.14);
            border-radius: 9999px;
            transform: rotate(30deg);
            animation: dashFloat3 15s ease-in-out infinite alternate-reverse;
        }

        /* Idle Floating Keyframes */
        @keyframes dashFloat1 {
            0% { transform: translate3d(0, 0, 0) scale(1); }
            100% { transform: translate3d(18px, -22px, 0) scale(1.05); }
        }

        @keyframes dashFloat2 {
            0% { transform: translate3d(0, 0, 0) rotate(14deg); }
            100% { transform: translate3d(-14px, -18px, 0) rotate(22deg); }
        }

        @keyframes dashFloat3 {
            0% { transform: translate3d(0, 0, 0) rotate(-20deg); }
            100% { transform: translate3d(12px, -16px, 0) rotate(-10deg); }
        }

        @keyframes dashFloat4 {
            0% { transform: translate3d(0, 0, 0) rotate(0deg); }
            100% { transform: translate3d(-10px, -12px, 0) rotate(36deg); }
        }

        /* Reduced Motion Accessibility */
        @media (prefers-reduced-motion: reduce) {
            .dash-shape {
                animation: none !important;
            }
        }

        /* Glassmorphism Floating Pill Header Navigation Bar */
        .navbar {
            position: sticky;
            top: 1.25rem;
            z-index: 1000;
            width: calc(100% - 3rem);
            max-width: 1140px;
            margin: 1.25rem auto 0;
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(229, 231, 235, 0.9);
            border-radius: 9999px;
            padding: 0.65rem 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 10px 30px -5px rgba(140, 13, 71, 0.08), 0 4px 12px rgba(0, 0, 0, 0.03);
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
            font-size: 1.35rem;
            font-weight: 800;
            color: var(--color-jade);
            text-decoration: none;
            letter-spacing: -0.015em;
        }

        .brand-icon {
            display: none;
        }

        .nav-menu {
            display: flex;
            align-items: center;
            gap: 0.75rem;
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
            justify-content: center;
            align-items: center;
        }

        .btn-modal-neutral {
            padding: 0.65rem 1.25rem;
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
            white-space: nowrap;
        }

        .btn-modal-neutral:hover {
            background-color: #E5E7EB;
            color: var(--color-dark);
        }

        .btn-modal-red {
            padding: 0.65rem 1.25rem;
            height: 42px;
            background-color: var(--color-red);
            color: var(--color-white);
            border: none;
            border-radius: var(--radius-md);
            font-family: inherit;
            font-size: 0.875rem;
            font-weight: 600;
            cursor: pointer;
            white-space: nowrap;
        }
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
            padding: 6rem 1.5rem 4rem;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: calc(100vh - 120px);
        }

        .hero-badge {
            display: inline-block;
            background-color: var(--color-jade-light);
            color: var(--color-jade);
            font-size: 0.875rem;
            font-weight: 600;
            padding: 0.4rem 1.25rem;
            border-radius: 9999px;
            margin-bottom: 1.5rem;
        }

        .hero-title {
            font-size: clamp(2rem, 3.8vw, 3.75rem);
            font-weight: 800;
            color: var(--color-jade);
            line-height: 1.15;
            letter-spacing: -0.025em;
            white-space: nowrap;
            width: 100%;
            max-width: 100%;
            margin: 0 auto 1.5rem;
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
        }        /* Real Image & Location Card Styling */
        .hero-banner-wrap {
            width: 100%;
            height: 400px;
            max-width: 960px;
            margin: 0 auto;
            border-radius: var(--radius-xl);
            overflow: hidden;
            box-shadow: 0 20px 40px -15px rgba(0, 0, 0, 0.12);
            border: 1px solid var(--color-border);
            background-color: #F3F4F6;
        }

        .hero-banner-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center;
        }

        .about-img-wrap {
            width: 100%;
            height: 380px;
            border-radius: var(--radius-xl);
            overflow: hidden;
            box-shadow: 0 15px 35px -10px rgba(0, 0, 0, 0.1);
            border: 1px solid var(--color-border);
            background-color: #F3F4F6;
        }

        .about-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center;
        }

        .solution-img-wrap {
            width: 100%;
            height: 220px;
            margin-bottom: 1.25rem;
            border-radius: var(--radius-lg);
            overflow: hidden;
            border: 1px solid var(--color-border);
            background-color: #F3F4F6;
        }

        .solution-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center;
            transition: transform 0.5s ease;
        }

        .solution-card:hover .solution-img {
            transform: scale(1.05);
        }

        /* Map Location Card */
        .map-card {
            height: 100%;
            min-height: 340px;
            background: linear-gradient(135deg, #FBF0F5 0%, #FFFFFF 100%);
            border: 1px solid var(--color-border);
            border-radius: var(--radius-xl);
            padding: 1.75rem;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            box-shadow: var(--shadow-sm);
            position: relative;
            overflow: hidden;
        }

        .map-card-header {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 0.75rem;
        }

        .map-card-icon {
            width: 42px;
            height: 42px;
            background-color: var(--color-jade-light);
            border-radius: var(--radius-md);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--color-jade);
            flex-shrink: 0;
        }

        .map-card-title {
            font-size: 1.0625rem;
            font-weight: 700;
            color: var(--color-dark);
        }

        .map-card-subtitle {
            font-size: 0.8125rem;
            color: var(--color-muted);
        }

        .map-img-link {
            display: block;
            text-decoration: none;
        }

        .map-preview-wrap {
            width: 100%;
            height: 200px;
            border-radius: var(--radius-lg);
            overflow: hidden;
            margin: 1rem 0;
            border: 1px solid var(--color-border);
            position: relative;
            background: #E5E7EB;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.06);
        }

        .map-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center;
            transition: transform 0.4s ease;
        }

        .map-img-link:hover .map-img {
            transform: scale(1.04);
        }

        .map-overlay-badge {
            position: absolute;
            bottom: 10px;
            right: 10px;
            background: rgba(140, 13, 71, 0.9);
            color: #FFFFFF;
            font-size: 0.75rem;
            font-weight: 600;
            padding: 0.35rem 0.75rem;
            border-radius: 20px;
            backdrop-filter: blur(4px);
            display: flex;
            align-items: center;
            gap: 0.35rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
        }

        .btn-map-link {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            width: 100%;
            padding: 0.875rem 1.25rem;
            background-color: var(--color-jade);
            color: var(--color-white);
            font-weight: 600;
            font-size: 0.9375rem;
            border-radius: var(--radius-md);
            text-decoration: none;
            transition: var(--transition);
            box-shadow: 0 4px 14px rgba(140, 13, 71, 0.2);
        }

        .btn-map-link:hover {
            background-color: var(--color-jade-hover);
            transform: translateY(-2px);
            color: var(--color-white);
        }      color: #64748B;
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

        /* Meet the Team Section */
        .team-header {
            text-align: center;
            max-width: 620px;
            margin: 0 auto 3rem;
        }

        .team-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1.5rem;
            align-items: stretch;
        }

        .team-card {
            background-color: var(--color-white);
            border: 1px solid var(--color-border);
            border-radius: var(--radius-xl);
            padding: 1.25rem 1rem;
            box-shadow: var(--shadow-sm);
            text-align: center;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            transition: var(--transition);
        }

        .team-card:hover {
            box-shadow: var(--shadow-md);
            transform: translateY(-4px);
        }

        .team-img-wrap {
            width: 100%;
            height: 240px;
            border-radius: var(--radius-lg);
            overflow: hidden;
            margin-bottom: 1.125rem;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.12), 0 8px 10px -6px rgba(0, 0, 0, 0.08);
            border: 1px solid var(--color-border);
            background-color: #F3F4F6;
        }

        .team-img-wrap img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center;
            transition: transform 0.5s ease;
        }

        .team-card:hover .team-img-wrap img {
            transform: scale(1.05);
        }

        .team-info-wrap {
            display: flex;
            flex-direction: column;
            flex: 1;
            justify-content: flex-start;
        }

        .team-name {
            font-size: 1.0625rem;
            font-weight: 700;
            color: var(--color-dark);
            margin-bottom: 0.25rem;
            height: 2.8rem;
            min-height: 2.8rem;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            line-height: 1.3;
        }

        .team-role {
            font-size: 0.8125rem;
            color: var(--color-jade);
            font-weight: 600;
            margin-bottom: 0.25rem;
            min-height: 1.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
        }

        @media (max-width: 992px) {
            .team-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 576px) {
            .team-grid {
                grid-template-columns: 1fr;
            }
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
    <!-- Idle Floating Organic Background Shapes Container -->
    <div class="dash-art-container" aria-hidden="true">
        <!-- Soft Blurred Circle 1 (Top Left) -->
        <div class="dash-shape dash-blob-1"></div>

        <!-- Floating Glass Square (Top Right) -->
        <div class="dash-shape dash-square-1"></div>

        <!-- Capsule / Pill Shape 1 (Middle Left) -->
        <div class="dash-shape dash-capsule-1"></div>

        <!-- Sparkle / Splat Accent (Middle Right) -->
        <svg class="dash-shape dash-splat-1" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
            <path fill="rgba(140, 13, 71, 0.18)" d="M50 0 C53 35 65 47 100 50 C65 53 53 65 50 100 C47 65 35 53 0 50 C35 47 47 35 50 0 Z" />
        </svg>

        <!-- Soft Blurred Circle 2 (Bottom Right) -->
        <div class="dash-shape dash-blob-2"></div>

        <!-- Capsule / Pill Shape 2 (Bottom Left) -->
        <div class="dash-shape dash-capsule-2"></div>
    </div>
    <!-- Glassmorphism Fixed Top Navigation Header Bar -->
    <header class="navbar">
        <div class="nav-left">
            <a href="#home" class="nav-brand">
                <span>Attendance System</span>
            </a>

            <ul class="nav-menu">
                <li><a href="#home" class="nav-link active">Home</a></li>
                <li><a href="#about" class="nav-link">About</a></li>
                <li><a href="#solutions" class="nav-link">Solutions</a></li>
                <li><a href="#team" class="nav-link">Team</a></li>
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
                <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
                    @csrf
                    <button type="submit" class="btn-modal-red">Sign Out</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Hero Section -->
    <section id="home" class="section hero-section">
        <span class="hero-badge">PUP Santa Rosa Campus BSIT 3rd Year</span>
        <h1 class="hero-title">BSIT 3rd Year Attendance System</h1>
        <p class="hero-paragraph">
            Official Attendance System for BSIT 3rd Year students at the Polytechnic University of the Philippines — Santa Rosa Campus.
        </p>

        <div class="hero-cta">
            <a href="#team" class="btn-hero-primary">Meet the Team</a>
            <a href="#about" class="btn-hero-secondary">Learn More</a>
        </div>
        <!-- Hero Banner Image -->
        <div class="hero-banner-wrap">
            <img src="{{ asset('images/hero-banner.png') }}" alt="BSIT 3rd Year Attendance System Hero Banner - PUP Santa Rosa Campus" class="hero-banner-img">
        </div>
    </section>

    <!-- About Section -->
    <section id="about" class="section">
        <div class="about-grid">
            <div class="about-text">
                <span class="section-tag">About Attendance System</span>
                <h2 class="section-title">Polytechnic University of the Philippines — Santa Rosa Campus</h2>
                <p>
                    Developed for the Polytechnic University of the Philippines — Santa Rosa Campus, the BSIT 3rd Year Attendance System streamlines class attendance tracking, real-time logging, and student records management.
                </p>
                <p>
                    Our system fosters efficiency, accuracy, and modern digital attendance solutions engineered to empower teachers and students.
                </p>
            </div>

            <!-- About Us Image -->
            <div class="about-img-wrap">
                <img src="{{ asset('images/about-us.jpg') }}" alt="About Attendance System - PUP Santa Rosa Campus" class="about-img">
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
                <!-- Solution Image 1 -->
                <div class="solution-img-wrap">
                    <img src="{{ asset('images/solution1.jpg') }}" alt="Cloud Infrastructure" class="solution-img">
                </div>
                <h3 class="card-title">Cloud Infrastructure</h3>
                <p class="card-desc">Scalable, high-availability server architecture engineered to handle workload demands with minimal latency.</p>
            </div>

            <div class="solution-card">
                <!-- Solution Image 2 -->
                <div class="solution-img-wrap">
                    <img src="{{ asset('images/solution2.jpg') }}" alt="Automated Workflows" class="solution-img">
                </div>
                <h3 class="card-title">Automated Workflows</h3>
                <p class="card-desc">Eliminate manual processes with intelligent automation tools designed to optimize productivity across teams.</p>
            </div>

            <div class="solution-card">
                <!-- Solution Image 3 -->
                <div class="solution-img-wrap">
                    <img src="{{ asset('images/solution3.jpg') }}" alt="Data Intelligence" class="solution-img">
                </div>
                <h3 class="card-title">Data Intelligence</h3>
                <p class="card-desc">Gain actionable insights with real-time analytics dashboards tailored to your organization's key metrics.</p>
            </div>
        </div>
    </section>

    <!-- Meet the Team Section -->
    <section id="team" class="section">
        <div class="team-header">
            <span class="section-tag">Leadership & Vision</span>
            <h2 class="section-title">Meet the Team</h2>
            <p style="color: var(--color-muted);">The passionate team driving the BSIT 3rd Year Attendance System forward at PUP Santa Rosa Campus.</p>
        </div>

        <div class="team-grid">
            <div class="team-card">
                <div class="team-img-wrap">
                    <img src="{{ asset('images/team/member1.png') }}" alt="Angelo Castroverde - Project Manager">
                </div>
                <div class="team-info-wrap">
                    <h3 class="team-name">Angelo<br>Castroverde</h3>
                    <div class="team-role">Project Manager</div>
                </div>
            </div>

            <div class="team-card">
                <div class="team-img-wrap">
                    <img src="{{ asset('images/team/member2.png') }}" alt="Chazlene Bacay - UI/UX Designer">
                </div>
                <div class="team-info-wrap">
                    <h3 class="team-name">Chazlene<br>Bacay</h3>
                    <div class="team-role">UI/UX Designer</div>
                </div>
            </div>

            <div class="team-card">
                <div class="team-img-wrap">
                    <img src="{{ asset('images/team/member3.png') }}" alt="Johnrey Aborot - Frontend Developer">
                </div>
                <div class="team-info-wrap">
                    <h3 class="team-name">Johnrey<br>Aborot</h3>
                    <div class="team-role">Frontend Developer</div>
                </div>
            </div>

            <div class="team-card">
                <div class="team-img-wrap">
                    <img src="{{ asset('images/team/member4.png') }}" alt="Alexia Eunice Patulot - Documentation Specialist">
                </div>
                <div class="team-info-wrap">
                    <h3 class="team-name">Alexia Eunice<br>Patulot</h3>
                    <div class="team-role">Documentation Specialist</div>
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
                <p style="color: var(--color-muted); margin-bottom: 2rem;">Have questions about the Attendance System? Reach out to our system administrators.</p>

                <div class="contact-detail">
                    <div class="contact-detail-label">Headquarters</div>
                    <div class="contact-detail-val">Polytechnic University of the Philippines — Santa Rosa Campus</div>
                </div>

                <div class="contact-detail">
                    <div class="contact-detail-label">Email Inquiries</div>
                    <div class="contact-detail-val">attendancesystem@pupsantarosa.edu.ph</div>
                </div>

                <div class="contact-detail">
                    <div class="contact-detail-label">Campus Location</div>
                    <div class="contact-detail-val">Santa Rosa, Laguna, Philippines</div>
                </div>
            </div>

            <!-- Interactive PUP Santa Rosa Campus Location & Map Card -->
            <div class="map-card">
                <div>
                    <div class="map-card-header">
                        <div class="map-card-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
                        </div>
                        <div>
                            <div class="map-card-title">PUP Santa Rosa Campus</div>
                            <div class="map-card-subtitle">Official Campus Location & Directions</div>
                        </div>
                    </div>

                    <!-- Map Image with Overlay Badge -->
                    <a href="https://share.google/x8I0JVgaJzuySv7sf" target="_blank" rel="noopener noreferrer" class="map-img-link" title="Open PUP Santa Rosa Location in Google Maps">
                        <div class="map-preview-wrap">
                            <img src="{{ asset('images/pupsrc-map.png') }}" alt="Polytechnic University of the Philippines Santa Rosa Campus Map" class="map-img">
                            <div class="map-overlay-badge">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="3 11 22 2 13 21 11 13 3 11"/></svg>
                                <span>Click to Navigate</span>
                            </div>
                        </div>
                    </a>
                </div>

                <a href="https://share.google/x8I0JVgaJzuySv7sf" target="_blank" rel="noopener noreferrer" class="btn-map-link">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="3 11 22 2 13 21 11 13 3 11"/></svg>
                    Open PUP Santa Rosa Location in Google Maps
                </a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <p>&copy; {{ date('Y') }} BSIT 3rd Year Attendance System — PUP Santa Rosa Campus. All rights reserved.</p>
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
