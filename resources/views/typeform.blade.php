<!DOCTYPE html>
<html>
<head>
    <title>Typeform Laravel</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
    <h2>Fetch Typeform Responses</h2>
    <button id="fetchBtn">Get Responses</button>
    <pre id="response"></pre>

    <h2>Submit Response</h2>
    <button id="submitBtn">Submit Example Response</button>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $('#fetchBtn').click(function() {
            $.get('/typeform/responses', function(data) {
                $('#response').text(JSON.stringify(data, null, 4));
            });
        });

        $('#submitBtn').click(function() {
            $.ajax({
                url: '/typeform/submit',
                type: 'POST',
                data: JSON.stringify({
                    responses: [
                        {
                            "field": { "id": "CCYUp2cx" },
                            "type": "fsdf",
                            "text": "Test Answer"
                        }
                    ]
                }),
                contentType: 'application/json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(data) {
                    $('#response').text(JSON.stringify(data, null, 4));
                }
            });
        });
    </script>
</body>
</html>
