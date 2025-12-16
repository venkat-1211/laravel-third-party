<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class TypeformController extends Controller
{
    private $apiKey;
    private $formId;
    private $baseUrl = 'https://api.typeform.com';

    public function __construct()
    {
        $this->apiKey = env('TYPEFORM_API_KEY');
        $this->formId = env('TYPEFORM_FORM_ID');
    }

    public function index()
    {
        return view('typeform');
    }

    // Fetch Typeform Responses
    public function getResponses()
    {
        $url = $this->baseUrl . "/forms/{$this->formId}/responses";

        $response = Http::withHeaders([
            'Authorization' => "Bearer {$this->apiKey}"
        ])->get($url);

        if ($response->successful()) {
            $data = $response->json();
            return response()->json($data);
        } else {
            return response()->json([
                'error' => $response->json()
            ], $response->status());
        }
    }

    // Submit a response to Typeform (optional)
    public function submitResponse(Request $request)
    {
        $url = $this->baseUrl . "/forms/{$this->formId}/responses";

        $response = Http::withHeaders([
            'Authorization' => "Bearer {$this->apiKey}",
            'Content-Type' => 'application/json'
        ])->post($url, [
            'responses' => $request->input('responses')
        ]);

        if ($response->successful()) {
            return response()->json([
                'message' => 'Response submitted successfully!',
                'data' => $response->json()
            ]);
        } else {
            return response()->json([
                'error' => $response->json()
            ], $response->status());
        }
    }
}
