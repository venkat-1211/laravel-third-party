<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Otp;
use App\Services\TwilioService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Validation\ValidationException;

class OtpController extends Controller
{
    protected $twilio;
    protected $expireMinutes;

    public function __construct(TwilioService $twilio)
    {
        $this->twilio = $twilio;
        $this->expireMinutes = (int) config('app.otp_expire_minutes', env('OTP_EXPIRE_MINUTES', 5));
    }

    public function index()
    {
        return view('otp');
    }

    // Send OTP
    public function send(Request $request)
    {
        $request->validate([
            'phone' => 'required|string', // you may want regex for E.164
        ]);

        $phone = $request->phone;
        // Rate-limit / anti-abuse checks here (e.g., check resend_count, recent sends)

        // Generate OTP (6-digit)
        $otpPlain = random_int(100000, 999999);

        // Hash OTP for storage
        $otpHash = Hash::make((string) $otpPlain);

        // Expire time
        $expiresAt = Carbon::now()->addMinutes($this->expireMinutes);

        // Save OTP (keep only latest or keep history)
        $otp = Otp::create([
            'phone' => $phone,
            'code_hash' => $otpHash,
            'expires_at' => $expiresAt,
            'attempts' => 0,
            'resend_count' => 0,
            'used' => false,
        ]);

        // Send via Twilio
        $message = "Your verification code is: {$otpPlain}. It will expire in {$this->expireMinutes} minutes.";
        $this->twilio->sendSms($phone, $message);

        return response()->json([
            'message' => 'OTP sent',
            'expires_at' => $expiresAt->toDateTimeString(),
        ], 200);
    }

    // Verify OTP
    public function verify(Request $request)
    {
        $request->validate([
            'phone' => 'required|string',
            'code' => 'required|digits:6',
        ]);

        $phone = $request->phone;
        $code = $request->code;

        // Find latest unused OTP
        $otp = Otp::where('phone', $phone)
                  ->where('used', false)
                  ->orderByDesc('created_at')
                  ->first();

        if (! $otp) {
            throw ValidationException::withMessages(['code' => 'No OTP request found for this number.']);
        }

        if ($otp->isExpired()) {
            throw ValidationException::withMessages(['code' => 'OTP has expired.']);
        }

        // Optional: limit attempts
        if ($otp->attempts >= 5) {
            throw ValidationException::withMessages(['code' => 'Maximum verification attempts exceeded.']);
        }

        // Compare hash
        if (! Hash::check($code, $otp->code_hash)) {
            $otp->increment('attempts');
            throw ValidationException::withMessages(['code' => 'Invalid code.']);
        }

        // Success: mark used and proceed (e.g. login/create user)
        $otp->update([
            'used' => true,
        ]);

        // Return success (attach user token or other flow)
        return response()->json(['message' => 'Phone verified successfully.'], 200);
    }

    // Resend OTP (optional)
    public function resend(Request $request)
    {
        $request->validate(['phone' => 'required|string']);

        $phone = $request->phone;

        // Check how many times we've resent recently (rate limiting)
        $last = Otp::where('phone', $phone)->orderByDesc('created_at')->first();
        if ($last && $last->resend_count >= 3 && $last->created_at->diffInMinutes(now()) < 30) {
            return response()->json(['message' => 'Resend limit reached. Try later.'], 429);
        }

        // Create new OTP similar to send()
        $otpPlain = random_int(100000, 999999);
        $otpHash = Hash::make((string)$otpPlain);
        $expiresAt = Carbon::now()->addMinutes($this->expireMinutes);

        $otp = Otp::create([
            'phone' => $phone,
            'code_hash' => $otpHash,
            'expires_at' => $expiresAt,
            'attempts' => 0,
            'resend_count' => ($last ? $last->resend_count + 1 : 1),
            'used' => false,
        ]);

        $message = "Your verification code is: {$otpPlain}. It will expire in {$this->expireMinutes} minutes.";
        $this->twilio->sendSms($phone, $message);

        return response()->json(['message' => 'OTP resent', 'expires_at' => $expiresAt->toDateTimeString()]);
    }
}
