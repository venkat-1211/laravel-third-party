<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class SetuDigilockerController extends Controller
{
    // Step 1: Initiate DigiLocker login
    public function initiateDigiLocker(Request $request)
    {
        $response = Http::withHeaders([
            'x-client-id' => env('SETU_CLIENT_ID'),
            'x-client-secret' => env('SETU_CLIENT_SECRET'),
            'x-product-instance-id' => env('SETU_PRODUCT_INSTANCE_ID'),
        ])->post('https://dg-sandbox.setu.co/api/digilocker', [
            'redirectUrl' => env('SETU_REDIRECT_URI'),
        ]);

        $data = $response->json();

        if (!isset($data['url'])) {
            return response()->json([
                'error' => 'Failed to get DigiLocker URL',
                'details' => $data
            ], 400);
        }

        return redirect($data['url']);
    }

    // Step 2: Handle DigiLocker callback
    public function handleCallback(Request $request)
    {
        $requestId = $request->query('requestId');

        if (!$requestId) {
            return response()->json([
                'error' => 'Request ID not found in callback'
            ], 400);
        }

        $documentResponse = Http::withHeaders([
            'x-client-id' => env('SETU_CLIENT_ID'),
            'x-client-secret' => env('SETU_CLIENT_SECRET'),
            'x-product-instance-id' => env('SETU_PRODUCT_INSTANCE_ID'),
        ])->get("https://dg-sandbox.setu.co/api/digilocker/$requestId");

        $data = $documentResponse->json();

        // Pass data to verification view
        return view('aadhaar-verify', ['documents' => $data]);
    }

    // Step 3: Verify Aadhaar upload
    public function verifyAadhaar(Request $request)
    {
        $request->validate([
            'aadhaar_file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120', // max 5MB
        ]);

        // Store file securely
        $path = $request->file('aadhaar_file')->store('aadhaar_docs');

        // For demonstration: return success
        return response()->json([
            'message' => 'Aadhaar uploaded successfully',
            'file_path' => $path
        ]);
    }
}
