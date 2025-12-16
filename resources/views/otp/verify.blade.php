<!DOCTYPE html>
<html>
<head>
    <title>Verify OTP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5" style="max-width: 450px;">
    <div class="card shadow p-4">
        <h3 class="text-center mb-3">Verify OTP</h3>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <form method="POST" action="{{ route('otp.verify') }}">
            @csrf

            <div class="mb-3">
                <label>Enter OTP</label>
                <input type="text" name="otp" class="form-control" placeholder="6-digit OTP" required>
                @error('otp') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <button class="btn btn-success w-100">Verify OTP</button>
        </form>
    </div>
</div>

</body>
</html>
