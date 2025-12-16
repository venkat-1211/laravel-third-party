<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class Fast2SmsController extends Controller
{
    public function showSendForm()
    {
        return view('otp.send');
    }

    public function sendOtp(Request $request)
    {
        $request->validate([
            'mobile' => 'required|digits:10'
        ]);

        $otp = rand(100000, 999999);

        // Store OTP in session
        Session::put('otp', $otp);
        Session::put('mobile', $request->mobile);

        // Fast2SMS API integration
        $response = Http::get('https://www.fast2sms.com/dev/bulkV2', [
            'authorization'    => env('FAST2SMS_API_KEY'),
            'route'            => 'otp',
            'variables_values' => $otp,
            'numbers'          => $request->mobile,
        ]);

        dd($response->json());

        if ($response->successful()) {
            return redirect()->route('otp.verify.form')->with('success', 'OTP sent successfully!');
        }

        return back()->with('error', 'Failed to send OTP, try again.');
    }

    public function showVerifyForm()
    {
        return view('otp.verify');
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|digits:6'
        ]);

        if (Session::get('otp') == $request->otp) {
            return back()->with('success', 'OTP verified successfully!');
        }

        return back()->with('error', 'Invalid OTP, please try again.');
    }
}
