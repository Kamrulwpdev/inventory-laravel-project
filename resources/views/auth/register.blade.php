<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<div class="container d-flex justify-content-center align-items-center vh-100">
    <div class="card shadow-lg border-0" style="width: 450px; border-radius: 15px;">
        <div class="card-body p-5">
            <div class="text-center mb-4">
                <h3 class="fw-bold text-success">Create Account</h3>
                <p class="text-muted">Join the QuickSpace network.</p>
            </div>

            <form method="POST" action="/register">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Full Name</label>
                    <input type="text" name="name" class="form-control shadow-sm" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control shadow-sm" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control shadow-sm" required>
                </div>
                <button type="submit" class="btn btn-success w-100 py-2 shadow-sm fw-bold">Sign Up</button>
            </form>

            <div class="text-center mt-4">
                <p class="small">Already have an account? <a href="{{ url('/') }}" class="text-decoration-none">Login here</a></p>
            </div>
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