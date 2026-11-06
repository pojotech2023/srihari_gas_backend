<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - BookMyLPG</title>
    <link rel="stylesheet" href="{{ asset('css/admin-dashboard.css') }}">
</head>
<body class="auth-wrapper">
    <div class="auth-card">
        <div class="auth-header">
            <img src="{{ asset('images/logo.png') }}" alt="BookMyLPG Logo" style="width: 80px; height: 80px; object-fit: contain; margin-bottom: 0.5rem; border-radius: 0.5rem;">
            <h2>Admin Sign In</h2>
            <p>Enter your credentials to access the console</p>
        </div>

        @if($errors->any())
            <div class="alert alert-danger">
                @foreach($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('admin.login.submit') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="mobile_number">Mobile Number</label>
                <input type="text" name="mobile_number" id="mobile_number" class="form-control" placeholder="e.g. 9876543210" value="{{ old('mobile_number') }}" required autocomplete="username">
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" class="form-control" placeholder="••••••••" required autocomplete="current-password">
            </div>

            <div class="form-group" style="flex-direction: row; gap: 0.5rem; align-items: center;">
                <input type="checkbox" name="remember" id="remember" style="accent-color: #6366f1; width: 16px; height: 16px;">
                <label for="remember" style="margin-bottom: 0; cursor: pointer; user-select: none;">Remember me</label>
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%; justify-content: center; margin-top: 0.5rem;">
                Sign In
            </button>
        </form>
    </div>
</body>
</html>
