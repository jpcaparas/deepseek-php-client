<?php

return [
    'defaultIncludes' => [
        __DIR__ . '/vendor/autoload.php',
    ],
];

// Usage:
// $client = new DeepSeek\DeepSeekClient('actual API key');
// $client->setMessage('system', 'You are a helpful assistant');
// $client->setMessage('user', 'Hello');