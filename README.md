# DeepSeek PHP API Client

A lightweight PHP wrapper for the DeepSeek Chat API.

## Requirements

- PHP 8.1+
- Guzzle HTTP Client
- Valid DeepSeek API key

## Installation

```bash
composer require jpcaparas/deepseek-php-client
```

## Usage

### Basic Example

*(You can also use PsySH for interactive testing)*

```php
require 'vendor/autoload.php';

use DeepSeek\DeepSeekClient;

$client = new DeepSeekClient('your-api-key');

$response = $client
    ->setMessage('user', 'What is 2+2?')
    ->send();

print_r($response);
```

### Available Methods

```php
// Initialize client
$client = new DeepSeekClient('your-api-key');

// Add a message to the conversation
$client->setMessage('system', 'You are a helpful assistant');
$client->setMessage('user', 'Hello'); // Returns self for chaining

// Clear conversation history
$client->clearMessages(); // Returns self for chaining

// Send conversation to API (returns array)
$response = $client->send();
```

### Response Format

A successful response will have this structure:

Abridged:

```php
[
    'choices' => [
        ['message' => ['content' => 'Response text here']]
    ]
]
```

Full:

```php
[
    "id" => "d7b42b8b-5007-42c6-b607-7b3b7b7b7b7b",
    "object" => "chat.completion",
    "created" => 1735810490,
    "model" => "deepseek-chat",
    "choices" => [
      [
        "index" => 0,
        "message" => [
          "role" => "assistant",
          "content" => "Hello! How can I assist you today? 😊",
        ],
        "logprobs" => null,
        "finish_reason" => "stop",
      ],
    ],
    "usage" => [
      "prompt_tokens" => 9,
      "completion_tokens" => 11,
      "total_tokens" => 20,
      "prompt_cache_hit_tokens" => 0,
      "prompt_cache_miss_tokens" => 9,
    ],
    "system_fingerprint" => "d7b42b8b-5007-42c6-b607-7b3b7b7b7b7b",
  ]
  ```

### Error Handling

```php
try {
    $response = $client
        ->setMessage('user', 'Hello')
        ->send();
} catch (\Exception $e) {
    echo $e->getMessage();
}
```

The client will return the error response from the API if available, otherwise throw an exception.