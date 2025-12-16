<!DOCTYPE html>
<html>
<head>
    <title>OTP Verification</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <style>
        body { background:#f2f2f2; }
        .otp-box {
            max-width: 450px;
            margin: 80px auto;
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
        }
    </style>
</head>

<body>

<div class="otp-box">
    <h4 class="text-center mb-4">Mobile Number Verification</h4>

    <!-- Send OTP Section -->
    <div id="send_otp_section">
        <div class="mb-3">
            <label>Mobile Number</label>
            <input type="text" id="mobile" class="form-control" placeholder="Enter mobile number">
        </div>

        <button class="btn btn-primary w-100" id="sendOtpBtn">Send OTP</button>
    </div>

    <!-- Verify OTP Section -->
    <div id="verify_otp_section" style="display:none;">
        <div class="mb-3 mt-3">
            <label>Enter OTP</label>
            <input type="text" id="otp" class="form-control" placeholder="Enter received OTP">
        </div>

        <button class="btn btn-success w-100" id="verifyOtpBtn">Verify OTP</button>
    </div>

    <div class="mt-3" id="message"></div>
</div>

<script>
$(document).ready(function () {

    // SEND OTP
    $("#sendOtpBtn").click(function () {
        let mobile = $("#mobile").val();

        $.ajax({
            url: "/send-otp",
            method: "POST",
            data: {
                mobile: mobile,
                _token: "{{ csrf_token() }}"
            },
            success: function (res) {
                $("#message").html(`<div class='alert alert-success'>${res.message}</div>`);
                $("#send_otp_section").hide();
                $("#verify_otp_section").show();
            },
            error: function (xhr) {
                $("#message").html(`<div class='alert alert-danger'>${xhr.responseJSON.message}</div>`);
            }
        });
    });

    // VERIFY OTP
    $("#verifyOtpBtn").click(function () {
        let mobile = $("#mobile").val();
        let otp = $("#otp").val();

        $.ajax({
            url: "/verify-otp",
            method: "POST",
            data: {
                mobile: mobile,
                otp: otp,
                _token: "{{ csrf_token() }}"
            },
            success: function (res) {
                $("#message").html(`<div class='alert alert-success'>${res.message}</div>`);
            },
            error: function (xhr) {
                $("#message").html(`<div class='alert alert-danger'>${xhr.responseJSON.message}</div>`);
            }
        });
    });

});
</script>

</body>
</html>
