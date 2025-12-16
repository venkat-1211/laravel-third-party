<!-- send.html -->
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>2Factor OTP demo</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">
  <div class="container" style="max-width:480px">
    <h3>Send OTP</h3>
    <div id="alert"></div>

    <div id="send-block">
      <div class="mb-3">
        <label>Phone (E.164, e.g. 919876543210)</label>
        <input id="phone" class="form-control" placeholder="919876543210">
      </div>
      <button id="sendBtn" class="btn btn-primary">Send OTP</button>
    </div>

    <div id="verify-block" style="display:none; margin-top:20px;">
      <div class="mb-3">
        <label>Enter OTP</label>
        <input id="otp" class="form-control" placeholder="123456">
      </div>
      <input type="hidden" id="session_id">
      <button id="verifyBtn" class="btn btn-success">Verify OTP</button>
    </div>
  </div>

  <script>
    const sendBtn = document.getElementById('sendBtn');
    const verifyBtn = document.getElementById('verifyBtn');

    sendBtn.addEventListener('click', async () => {
      const phone = '+' + document.getElementById('phone').value.trim();
      const res = await fetch('/api/otp/send', {
        method: 'POST',
        headers:{ 'Content-Type':'application/json', 'Accept':'application/json'},
        body: JSON.stringify({ phone })
      });
      const json = await res.json();
      if (res.ok && json.session_id) {
        document.getElementById('session_id').value = json.session_id;
        document.getElementById('send-block').style.display='none';
        document.getElementById('verify-block').style.display='block';
        showAlert('OTP sent. Check phone.', 'success');
      } else {
        showAlert(json.message || 'Send failed', 'danger');
      }
    });

    verifyBtn.addEventListener('click', async () => {
      const session_id = document.getElementById('session_id').value;
      const otp = document.getElementById('otp').value.trim();

      const res = await fetch('/api/otp/verify', {
        method:'POST',
        headers:{ 'Content-Type':'application/json', 'Accept':'application/json'},
        body: JSON.stringify({ session_id, otp })
      });

      const json = await res.json();
      if (res.ok && json.status === 'ok') {
        showAlert('OTP verified successfully!', 'success');
        // proceed, e.g., redirect or unlock UI
      } else {
        showAlert(json.message || 'Verification failed', 'danger');
      }
    });

    function showAlert(msg, type='info') {
      const a = document.getElementById('alert');
      a.innerHTML = `<div class="alert alert-${type}">${msg}</div>`;
    }
  </script>
</body>
</html>
