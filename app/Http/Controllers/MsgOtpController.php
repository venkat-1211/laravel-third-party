<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\Msg91Service;

class MsgOtpController extends Controller
{
    protected $msg91;

    public function __construct(Msg91Service $msg91)
    {
        $this->msg91 = $msg91;
    }

    // SEND OTP
    public function sendOtp(Request $request)
    {
        $request->validate([
            'mobile' => 'required|digits:10'
        ]);

        $response = $this->msg91->sendOtp($request->mobile);

        if (($response['type'] ?? '') === 'success') {
            return response()->json(['message' => 'OTP sent successfully!']);
        }

        return response()->json(['message' => 'Failed to send OTP'], 422);
    }

    // VERIFY OTP
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'mobile' => 'required|digits:10',
            'otp' => 'required|digits:4'
        ]);

        $response = $this->msg91->verifyOtp($request->mobile, $request->otp);

        if (($response['type'] ?? '') === 'success') {
            return response()->json(['message' => 'OTP verified successfully!']);
        }

        return response()->json(['message' => 'Invalid OTP'], 422);
    }
}
