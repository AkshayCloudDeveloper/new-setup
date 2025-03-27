<?php

namespace App\Http\Controllers\Monday;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class MondayController extends Controller
{

    public function getBoardId(Request $request)
    {
        $apiUrl = "https://api.monday.com/v2";
        $apiKey = env('MONDAY_API_KEY'); // Store API key in .env file
        $boardId = 1990107409;
        $query = <<<GQL
        query {
            boards(ids: $boardId) {
                id
                name
                items_page {
                    items {
                        id
                        name
                    }
                }
            }
        }
        GQL;

        $response = Http::withHeaders([
            'Authorization' => $apiKey,
            'Content-Type'  => 'application/json'
        ])->post($apiUrl, ['query' => $query]);

        if ($response->failed()) {
            return ['error' => 'Failed to fetch data', 'details' => $response->json()];
        }

        return $response->json();
    }

    public function getQuestionsList($jobId = '')
    {
        $apiUrl = "https://app.hirenowx.com/api/get_question_paper";

        $apiKey = env('HIRENOWX_API_KEY'); // Store API key in .env file

        $response = Http::withHeaders([
            'Authorization' => "Bearer {$apiKey}"
        ])->asForm()->post($apiUrl, [
            'job_id' => "cy2-0065", // Sending job_id in the request body as form-data
        ]);

        if ($response->failed()) {
            return ['error' => 'Failed to fetch data', 'details' => $response->json()];
        }

        return $response->json();
    }
}
