<?php

namespace App\Controllers;

use App\Helpers\Response;
use App\Services\AIClassificationService;

class ClassificationController
{

    public function classify()
    {
        // Read incoming JSON data
        $rawInput = file_get_contents('php://input');

        // Decode JSON
        $input = json_decode($rawInput, true);

        // Check invalid JSON
        if (json_last_error() !== JSON_ERROR_NONE) {
            Response::json([
                'success' => false,
                'message' => 'Invalid JSON format!'
            ], 400);
        }

        // Check text field exists
        if (!isset($input['text'])) {
            Response::json([
                'success' => false,
                'message' => 'Text field is required!'
            ], 422);
        }

        // Check text must be a string
        if (!is_string($input['text'])) {
            Response::json([
                'success' => false,
                'message' => 'Text must be a string!'
            ], 422);
        }

        // Check empty text
        $text = trim($input['text']);

        if ($text === '') {
            Response::json([
                'success' => false,
                'message' => 'Text cannot be empty!'
            ], 422);
        }

        // AI Classification
        try {

            $aiService = new AIClassificationService();

            $result = $aiService->classifyText($text);

            Response::json([
                'success' => true,
                'message' => 'Text classified successfully!',
                'data' => $result
            ]);
        } catch (\Exception $e) {

            Response::json([
                'success' => false,
                'message' => $e->getMessage()
            ], 503);
        }
    }
}
