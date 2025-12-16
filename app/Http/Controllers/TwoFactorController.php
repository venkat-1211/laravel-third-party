<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\TwoFactorService;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class TwoFactorController extends Controller
{
    protected $twoFactor;

    public function __construct(TwoFactorService $twoFactor)
    {
        $this->twoFactor = $twoFactor;
    }

    public function index()
    {
        return view('2factor.otp');
    }

    /**
     * Send OTP to phone.
     * Request: { phone: "919876543210" }  (country code +91 or full E.164)
     */
    public function sendOtp(Request $request)
    {
        $data = $request->validate([
            'phone' => ['required', 'string'],
        ]);

        // Normalize phone if you want, ensure full international format
        $phone = $data['phone'];

        $response = $this->twoFactor->sendOtp($phone);

        // 2Factor typically returns { "Status":"Success", "Details":"<session_id>" }
        if (isset($response['Status']) && $response['Status'] === 'Success') {
            // Option: persist session_id with phone number in DB for later validation
            // session()->put("2fa.{$phone}", $response['Details']); // not recommended for production

            return response()->json([
                'status' => 'ok',
                'message' => 'OTP sent',
                'session_id' => $response['Details'], // send to frontend (or store server-side)
                'raw' => $response,
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => $response['Details'] ?? 'Failed to send OTP',
            'raw' => $response,
        ], 400);
    }

    /**
     * Verify OTP.
     * Request: { session_id: "...", otp: "123456", phone: "919876543210" (optional) }
     */
    public function verifyOtp(Request $request)
    {
        $data = $request->validate([
            'session_id' => ['required', 'string'],
            'otp' => ['required', 'string'],
        ]);

        $sessionId = $data['session_id'];
        $otp = $data['otp'];

        $response = $this->twoFactor->verifyOtp($sessionId, $otp);

        // Example response: { "Status":"Success", "Details":"OTP Matched" }
        if (isset($response['Status']) && $response['Status'] === 'Success') {
            // mark phone verified / login user / create account etc.
            return response()->json([
                'status' => 'ok',
                'message' => 'OTP verified',
                'raw' => $response,
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => $response['Details'] ?? 'OTP verification failed',
            'raw' => $response,
        ], 400);
    }
}
