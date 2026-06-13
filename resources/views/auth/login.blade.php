<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">

<style>
    body {
        font-family: 'Inter', sans-serif;
        background-color: #f8f9fc;
        margin: 0;
        height: 100vh;
    }

    .login-wrapper {
        display: flex;
        height: 100vh;
        width: 100%;
    }

    /* Left Side: Purple Branding */
    .brand-side {
        background: #6366f1; /* Deep Indigo/Purple */
        color: white;
        display: flex;
        flex-direction: column;
        justify-content: center;
        padding: 0 10%;
        flex: 1;
    }

    .brand-side h1 {
        font-size: 3.5rem;
        font-weight: 700;
        margin-bottom: 20px;
    }

    .brand-side p {
        font-size: 1.1rem;
        opacity: 0.9;
        line-height: 1.6;
        max-width: 400px;
    }

    /* Right Side: Login Form */
    .form-side {
        background: white;
        flex: 1;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        padding: 40px;
    }

    .login-box {
        width: 100%;
        max-width: 420px;
        padding: 40px;
        border-radius: 24px;
        background: #ffffff;
    }

    .login-box h2 {
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 8px;
    }

    .login-box .subtitle {
        color: #64748b;
        margin-bottom: 32px;
    }

    .form-label {
        font-weight: 600;
        color: #334155;
        font-size: 0.9rem;
    }

    .form-control {
        padding: 12px 16px;
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        background-color: #f8fafc;
    }

    .form-control:focus {
        border-color: #6366f1;
        box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
    }

    .btn-primary {
        background-color: #6366f1;
        border: none;
        padding: 12px;
        border-radius: 12px;
        font-weight: 600;
        margin-top: 20px;
    }

    .btn-primary:hover {
        background-color: #4f46e5;
    }

    .btn-outline-light-blue {
        background-color: #eff6ff;
        color: #1e40af;
        border: none;
        padding: 12px;
        border-radius: 12px;
        font-weight: 600;
        width: 100%;
        margin-top: 15px;
    }

    .forgot-password {
        display: block;
        text-align: right;
        font-size: 0.85rem;
        color: #6366f1;
        text-decoration: none;
        margin-top: 8px;
    }

    .footer-text {
        position: absolute;
        bottom: 20px;
        color: #94a3b8;
        font-size: 0.8rem;
    }

    /* Responsive */
    @media (max-width: 992px) {
        .brand-side { display: none; }
    }
</style>

<div class="login-wrapper">
    <div class="brand-side">
        <h1>Welcome back!</h1>
        <p>Sign in to access your account and keep your operations in sync.</p>
    </div>

    <div class="form-side">
        <div class="login-box">
            <h2>Sign In</h2>
            <p class="subtitle">Access your dashboard and manage everything from one place.</p>

            <form method="POST" action="{{ url('/login') }}">
                @csrf 
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <div class="input-group">
                        <input type="email" name="email" class="form-control" placeholder="admin@example.com" required autofocus>
                    </div>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" placeholder="••••••" required>
                    <a href="#" class="forgot-password">Forgot password?</a>
                </div>
            
                <button type="submit" class="btn btn-primary w-100 shadow-sm">Sign In</button>
            </form>

            <div class="text-center mt-4">
                <p class="text-muted small mb-2">NEED TO BROWSE FIRST?</p>
                <a href="{{ route('online.store') }}" class="btn btn-outline-light-blue">
    <i class="bi bi-shop me-2"></i> Visit Online Store
</a>
            </div>
        </div>

        <div class="footer-text">
            <strong>QuickSpace POS</strong> &copy; {{ date('Y') }} - Developed by Kamrul Hasan
        </div>
    </div>
</div>

<script>
    window.addEventListener('livewire:init', () => {
        Livewire.on('session-expired', () => {
            alert('Session expired. Page will refresh.');
            window.location.reload();
        });
    });
</script>