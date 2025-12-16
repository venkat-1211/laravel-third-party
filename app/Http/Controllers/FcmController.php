<?php

namespace App\Http\Controllers;

use App\Models\DeviceToken;
use App\Services\FcmService;
use Illuminate\Http\Request;

class FcmController extends Controller
{
    // Show form
    public function index()
    {
        return view('send-notification');
    }

    // Handle form submit
    public function send(Request $request, FcmService $fcm)
    {
        $request->validate([
            'title' => 'required',
            'body' => 'required',
        ]);

        // Get all tokens
        $tokens = DeviceToken::pluck('token')->toArray();

        if (empty($tokens)) {
            return back()->with('error', 'No device tokens found!');
        }

        // Send push
        $response = $fcm->sendToMultiple(
            $tokens,
            $request->title,
            $request->body,
            ["screen" => "HomePage"]
        );

        return back()->with('success', 'Notification sent successfully!');
    }
}
