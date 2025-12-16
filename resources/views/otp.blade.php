<form id="sendForm" method="POST" action="/otp/send">
  @csrf
  <input name="phone" placeholder="+911234567890" />
  <button type="submit">Send OTP</button>
</form>

<form id="verifyForm" method="POST" action="/otp/verify">
  @csrf
  <input name="phone" placeholder="+911234567890" />
  <input name="code" placeholder="123456" />
  <button type="submit">Verify</button>
</form>
