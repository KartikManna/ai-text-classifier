<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\TransferException;

class AIClassificationService
{
    private $client;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => $_ENV['OLLAMA_BASE_URL'],
            'timeout' => 90,
            'connect_timeout' => 10
        ]);
    }

    public function classifyText($text)
    {
        try {

            $prompt = <<<PROMPT
            You are a professional text classification system.

            Your task is to classify the given text into exactly ONE of these four categories:

            1. Complaint:
            Use this when the user expresses dissatisfaction,
            frustration, a problem, or reports a bad experience.

            Examples:
            - Your service is very bad.
            - I am unhappy with your product.
            - My order has not arrived.

            2. Query:
            Use this when the user asks a question,
            requests information, or wants help.

            Examples:
            - How can I reset my password?
            - What are your business hours?
            - Can you help me with my account?

            3. Feedback:
            Use this when the user provides an opinion,
            review, suggestion, praise, or criticism about
            a specific product, service, or experience.

            Examples:
            - Your application is very easy to use.
            - I love your customer support.
            - You should improve the dashboard.

            4. Other:
            Use this when the text does not clearly belong
            to Complaint, Query, or Feedback.

            Examples:
            - Today the weather is very nice.
            - My name is Kartik.
            - Hello.
            - The sky is blue.
            - Today is Monday.

            IMPORTANT RULES:

            - Classify based on the actual meaning of the text.
            - Do not assume every positive sentence is Feedback.
            - General statements, greetings, names, and unrelated topics
            should be classified as Other.
            - Do not invent meaning that is not present in the text.
            - Select exactly one category.

            Return valid JSON only, with this exact structure:

            {
                "category": "Other",
                "confidence": 0.95
            }

            Confidence must be a numeric value between 0 and 1.
            It is your estimated confidence, not a calibrated probability.

            Text to classify:
            {$text}
            PROMPT;

            $response = $this->client->post('/api/generate', [
                'json' => [
                    'model' => $_ENV['OLLAMA_MODEL'],
                    'prompt' => $prompt,
                    'stream' => false,
                    'format' => 'json'
                ]
            ]);

            $result = json_decode($response->getBody(), true);

            if (
                !is_array($result) ||
                !isset($result['response']) ||
                !is_string($result['response'])
            ) {
                throw new \Exception('Invalid Ollama response format!');
            }

            $classification = json_decode($result['response'], true);

            // Validate AI response
            if (
                !is_array($classification) ||
                !isset($classification['category']) ||
                !isset($classification['confidence'])
            ) {
                throw new \Exception('Invalid AI response format!');
            }

            // Allowed categories
            $allowedCategories = [
                'Complaint',
                'Query',
                'Feedback',
                'Other'
            ];

            // Validate category
            if (
                !is_string($classification['category']) ||
                !in_array($classification['category'], $allowedCategories, true)
            ) {
                throw new \Exception('Invalid category returned by AI!');
            }

            // Validate confidence
            if (
                !is_numeric($classification['confidence']) ||
                $classification['confidence'] < 0 ||
                $classification['confidence'] > 1
            ) {
                throw new \Exception('Invalid confidence score returned by AI!');
            }

            return [
                'category' => $classification['category'],
                'confidence' => (float) $classification['confidence']
            ];
        } catch (ConnectException $e) {

            throw new \Exception(
                'Unable to connect with AI service. Please try again later.'
            );
        } catch (TransferException $e) {

            throw new \Exception(
                'AI service request failed or timed out. Please try again later.'
            );
        } catch (\Exception $e) {

            throw $e;
        }
    }
}
