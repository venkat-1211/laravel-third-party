<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Aadhaar Verification</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h3>Aadhaar Verification</h3>

    <h5 class="mt-3">Fetched Documents from DigiLocker:</h5>
    <pre>{{ json_encode($documents, JSON_PRETTY_PRINT) }}</pre>

    <form action="{{ route('aadhaar.verify') }}" method="POST" enctype="multipart/form-data" class="mt-4">
        @csrf
        <div class="mb-3">
            <label for="aadhaar_file" class="form-label">Upload Aadhaar File</label>
            <input type="file" name="aadhaar_file" id="aadhaar_file" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-success">Verify Aadhaar</button>
    </form>
</div>
</body>
</html>
