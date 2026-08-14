@extends('home.layout.master')

@section('content')
<div class="login-wrapper-split">
    <!-- Image Section -->
    <div class="login-hero-section" style="background-image: url('{{ asset('login_hero.jpg') }}');">
        <div class="hero-overlay">
            <div class="hero-content">
                <h1>HANN PRINT</h1>
                <p>Solusi Cetak Sablon B2B & B2C Berkualitas Tinggi. Cepat, Presisi, dan Profesional.</p>
            </div>
        </div>
    </div>

    <!-- Form Section -->
    <div class="login-form-section">
        <div class="login-container-inner">
            <div class="login-header">
                <h2>Selamat Datang</h2>
                <p>Silakan masuk ke akun Anda untuk melanjutkan</p>
            </div>

            @if (session('error'))
                <div class="login-alert login-alert-error">
                    <i class="fas fa-exclamation-circle" style="margin-right: 8px;"></i> {{ session('error') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="login-form">
                @csrf
                <div class="form-group">
                    <label for="email">Alamat Email</label>
                    <input type="email"
                           class="form-control-custom @error('email') is-invalid @enderror"
                           id="email"
                           name="email"
                           placeholder="Masukkan email Anda"
                           value="{{ old('email') }}"
                           required>
                    @error('email')
                        <span class="invalid-feedback-custom">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <div class="password-input-wrapper">
                        <input type="password"
                               class="form-control-custom @error('password') is-invalid @enderror"
                               id="password"
                               name="password"
                               placeholder="Masukkan password Anda"
                               required>
                        <button type="button" class="btn-toggle-password" onclick="togglePassword()" title="Tampilkan Password">
                            Show
                        </button>
                    </div>
                    @error('password')
                        <span class="invalid-feedback-custom">{{ $message }}</span>
                    @enderror
                </div>

                <button type="submit" class="btn-login">Masuk ke Dashboard</button>

                <!-- Helpful Demo Login Instructions for Showcase -->
                <div class="demo-login-info mt-4">
                    <p style="font-size: 13px; color: var(--brand-gray); margin-bottom: 8px; font-weight: bold; text-transform: uppercase;">
                        Akun Demo Tersedia:
                    </p>
                    <div class="demo-accounts-grid">
                        <div class="demo-account-card" onclick="fillLogin('admin@admin.com', 'admin123')">
                            <i class="fas fa-user-shield"></i>
                            <div>
                                <strong>Admin</strong>
                                <span>admin123</span>
                            </div>
                        </div>
                        <div class="demo-account-card" onclick="fillLogin('b2b@pembeli.com', 'reseller123')">
                            <i class="fas fa-store"></i>
                            <div>
                                <strong>B2B Reseller</strong>
                                <span>reseller123</span>
                            </div>
                        </div>
                        <div class="demo-account-card" onclick="fillLogin('b2c@pembeli.com', 'pembeli123')">
                            <i class="fas fa-user"></i>
                            <div>
                                <strong>B2C Retail</strong>
                                <span>pembeli123</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="login-footer">
                    <p>Belum memiliki akun? <a href="/register">Daftar sekarang</a></p>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function togglePassword() {
        const input = document.getElementById('password');
        const btn = document.querySelector('.btn-toggle-password');
        if (input.type === 'password') {
            input.type = 'text';
            btn.textContent = 'Hide';
        } else {
            input.type = 'password';
            btn.textContent = 'Show';
        }
    }

    function fillLogin(email, password) {
        document.getElementById('email').value = email;
        document.getElementById('password').value = password;
    }
</script>

<style>
    /* 
     * Modern Split-Screen Login Layout
     * No "AI Slop" - Clean, premium SaaS feel with industrial print elements
     */
    :root {
        --brand-charcoal: #0f172a;
        --brand-gray: #64748b;
        --brand-light: #f8fafc;
        --brand-accent: #2563eb; 
        --brand-accent-hover: #1d4ed8;
        --brand-error: #ef4444;
        --brand-error-bg: #fef2f2;
    }

    .login-wrapper-split {
        display: flex;
        min-height: calc(100vh - 120px); /* Adjust based on navbar/footer height */
        background-color: #ffffff;
        font-family: 'Inter', 'Lato', sans-serif;
    }

    /* Left Side: Hero Image */
    .login-hero-section {
        flex: 1;
        display: none;
        background-size: cover;
        background-position: center;
        position: relative;
    }
    
    @media (min-width: 992px) {
        .login-hero-section {
            display: flex;
            align-items: flex-end;
            justify-content: flex-start;
        }
    }

    .hero-overlay {
        background: linear-gradient(to top, rgba(15, 23, 42, 0.95) 0%, rgba(15, 23, 42, 0.4) 50%, rgba(15, 23, 42, 0.1) 100%);
        width: 100%;
        height: 100%;
        display: flex;
        align-items: flex-end;
        padding: 60px;
    }

    .hero-content {
        color: #ffffff;
        max-width: 500px;
    }

    .hero-content h1 {
        font-size: 48px;
        font-weight: 900;
        margin-bottom: 16px;
        letter-spacing: -1px;
    }

    .hero-content p {
        font-size: 18px;
        line-height: 1.6;
        color: #cbd5e1;
        font-weight: 300;
    }

    /* Right Side: Form */
    .login-form-section {
        flex: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 40px 20px;
    }

    .login-container-inner {
        width: 100%;
        max-width: 420px;
    }

    .login-header {
        margin-bottom: 32px;
    }

    .login-header h2 {
        font-size: 32px;
        font-weight: 800;
        color: var(--brand-charcoal);
        margin: 0 0 8px 0;
        letter-spacing: -0.5px;
    }

    .login-header p {
        color: var(--brand-gray);
        font-size: 16px;
        margin: 0;
    }

    .login-alert {
        padding: 16px;
        margin-bottom: 24px;
        border-radius: 8px;
        font-weight: 500;
        font-size: 14px;
        display: flex;
        align-items: center;
    }

    .login-alert-error {
        background-color: var(--brand-error-bg);
        color: var(--brand-error);
        border: 1px solid #fca5a5;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        display: block;
        font-weight: 600;
        color: var(--brand-charcoal);
        margin-bottom: 8px;
        font-size: 14px;
    }

    .form-control-custom {
        width: 100%;
        padding: 12px 16px;
        font-size: 16px;
        color: var(--brand-charcoal);
        background-color: #ffffff;
        border: 1px solid #cbd5e1;
        border-radius: 8px;
        transition: all 200ms ease;
    }

    .form-control-custom:focus {
        outline: none;
        border-color: var(--brand-accent);
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15);
    }

    .password-input-wrapper {
        position: relative;
        display: flex;
    }

    .btn-toggle-password {
        position: absolute;
        right: 4px;
        top: 4px;
        bottom: 4px;
        background: transparent;
        border: none;
        padding: 0 12px;
        font-weight: 600;
        font-size: 13px;
        color: var(--brand-gray);
        cursor: pointer;
        transition: color 150ms ease;
    }

    .btn-toggle-password:hover {
        color: var(--brand-charcoal);
    }

    .invalid-feedback-custom {
        display: block;
        margin-top: 6px;
        color: var(--brand-error);
        font-size: 13px;
        font-weight: 500;
    }

    .btn-login {
        display: block;
        width: 100%;
        padding: 14px;
        background-color: var(--brand-accent);
        color: #ffffff;
        border: none;
        border-radius: 8px;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        transition: all 200ms ease;
        margin-top: 32px;
        box-shadow: 0 4px 6px -1px rgba(37, 99, 235, 0.2);
    }

    .btn-login:hover {
        background-color: var(--brand-accent-hover);
        box-shadow: 0 10px 15px -3px rgba(37, 99, 235, 0.25);
        transform: translateY(-1px);
    }

    .demo-accounts-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 10px;
    }

    .demo-account-card {
        background: var(--brand-light);
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 10px;
        cursor: pointer;
        transition: all 0.2s ease;
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
    }

    .demo-account-card:hover {
        border-color: var(--brand-accent);
        background: #eff6ff;
    }

    .demo-account-card i {
        font-size: 18px;
        color: var(--brand-accent);
        margin-bottom: 6px;
    }

    .demo-account-card strong {
        display: block;
        font-size: 12px;
        color: var(--brand-charcoal);
    }

    .demo-account-card span {
        display: block;
        font-size: 11px;
        color: var(--brand-gray);
        margin-top: 2px;
    }

    .login-footer {
        margin-top: 40px;
        text-align: center;
    }

    .login-footer p {
        color: var(--brand-gray);
        font-size: 15px;
        margin: 0;
    }

    .login-footer a {
        color: var(--brand-accent);
        font-weight: 600;
        text-decoration: none;
        margin-left: 4px;
    }

    .login-footer a:hover {
        text-decoration: underline;
    }
</style>
@endsection
