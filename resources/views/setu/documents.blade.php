<!DOCTYPE html>
<html>
<head>
    <title>DigiLocker Files</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">

    <h3 class="mb-3">Your DigiLocker Documents</h3>

    <table class="table table-bordered">
        <thead>
        <tr>
            <th>Name</th>
            <th>Type</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody>

        @if(!empty($files))
            @foreach($files as $doc)
                <tr>
                    <td>{{ $doc['name'] ?? '' }}</td>
                    <td>{{ $doc['type'] ?? '' }}</td>
                    <td>
                        @if(isset($doc['uri']))
                            <a href="{{ route('setu.download') }}?uri={{ urlencode($doc['uri']) }}"
                               class="btn btn-sm btn-primary">
                                Download
                            </a>
                        @endif
                    </td>
                </tr>
            @endforeach
        @endif

        </tbody>
    </table>

</div>
</body>
</html>
