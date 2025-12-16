<!DOCTYPE html>
<html>
<head>
    <title>Send OTP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5" style="max-width: 450px;">
    <div class="card shadow p-4">
        <h3 class="text-center mb-3">Send OTP</h3>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <form method="POST" action="{{ route('otp.send') }}">
            @csrf

            <div class="mb-3">
                <label>Mobile Number</label>
                <input type="text" name="mobile" class="form-control" placeholder="Enter 10-digit mobile" required>
                @error('mobile') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <button class="btn btn-primary w-100">Send OTP</button>
        </form>
    </div>
</div>

</body>
</html>
