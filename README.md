# AI Text Classification REST API

An AI-powered REST API built using Core PHP and Ollama (Llama 3.2) to classify text into four categories: Complaint, Query, Feedback, and Other.

## Features

- AI-powered text classification.
- Four predefined classification categories.
- Confidence score between 0 and 1.
- REST API using POST method.
- JSON request and response.
- Input validation.
- AI response validation.
- Exception handling.
- Environment configuration using dotenv.
- Local AI processing using Ollama.

## Technologies Used

- PHP 8.5
- Composer
- Guzzle HTTP Client
- vlucas/phpdotenv
- Ollama
- Llama 3.2 (3B)

## Project Structure

    ai-text-classifier/
    ├── public/
    │   ├── index.php
    │   └── .htaccess
    ├── src/
    │   ├── Controllers/
    │   │   └── ClassificationController.php
    │   ├── Helpers/
    │   │   └── Response.php
    │   └── Services/
    │       └── AIClassificationService.php
    ├── routes/
    │   └── api.php
    ├── .env.example
    ├── .gitignore
    ├── composer.json
    ├── composer.lock
    └── README.md

## Requirements

- PHP 8.1 or higher
- Composer
- Apache Server (XAMPP)
- Ollama
- Llama 3.2 model

## Installation

1. Clone the repository:

   git clone YOUR_GITHUB_REPOSITORY_URL

2. Navigate to the project directory:

   cd ai-text-classifier

3. Install dependencies:

   composer install

4. Copy `.env.example` to `.env`.

5. Configure the environment variables:

   OLLAMA_BASE_URL=http://localhost:11434
   OLLAMA_MODEL=llama3.2:3b

6. Download the AI model:

   ollama pull llama3.2:3b

7. Start Ollama.

8. Run the project using XAMPP Apache server.

## API Endpoint

**POST**

http://localhost/ai-text-classifier/public/api/classify

## Request Headers

Content-Type: application/json

## Request Body

    {
        "text": "I am very unhappy with your service."
    }

## Successful Response

    {
        "success": true,
        "message": "Text classified successfully!",
        "data": {
            "category": "Complaint",
            "confidence": 0.95
        }
    }

Note: Confidence is an AI-estimated score, not a calibrated probability.

## Available Categories

| Category  | Description                                          |
| --------- | ---------------------------------------------------- |
| Complaint | User expresses dissatisfaction or reports a problem. |
| Query     | User asks a question or requests information.        |
| Feedback  | User provides an opinion, suggestion, or review.     |
| Other     | Text does not fit the other three categories.        |

## Error Responses

| HTTP Status | Description                                |
| ----------- | ------------------------------------------ |
| 400         | Invalid JSON format                        |
| 404         | Route not found                            |
| 422         | Validation error                           |
| 503         | AI service unavailable or processing error |

## Environment Variables

| Variable        | Description    |
| --------------- | -------------- |
| OLLAMA_BASE_URL | Ollama API URL |
| OLLAMA_MODEL    | AI model name  |

## AI Model

This project uses the locally running Llama 3.2 3B model through Ollama.

The model performs zero-shot text classification using prompt instructions. No model training or fine-tuning is required.

## License

This project was developed as part of a technical assessment.
