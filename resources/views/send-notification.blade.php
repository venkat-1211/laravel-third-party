<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Send Push Notification</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">

            <div class="card shadow-lg p-4 rounded-4">
                <h3 class="text-center mb-4">Send Push Notification</h3>

                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                <form action="/send-notification" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Notification Title</label>
                        <input type="text" name="title" class="form-control" placeholder="Enter title" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Notification Message</label>
                        <textarea name="body" class="form-control" rows="4" placeholder="Enter message" required></textarea>
                    </div>

                    <button class="btn btn-primary w-100 py-2 fw-bold">Send Notification</button>
                </form>
            </div>

        </div>
    </div>
</div>

</body>
</html>
