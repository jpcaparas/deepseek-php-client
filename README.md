# DeepSeek PHP API Client

This is a PHP API client for DeepSeek, allowing you to interact with the DeepSeek API. This client is designed to be easy to use and integrate into your PHP applications.

## Installation

You can install the DeepSeek PHP API client via Composer. Run the following command:

```bash
composer require githubnext/deepseek-client
```

## Usage

First, you need to create an instance of the `DeepSeekClient` class with your API key:

```php
require 'vendor/autoload.php';

use DeepSeek\DeepSeekClient;

$apiKey = 'your_api_key';
$client = new DeepSeekClient($apiKey);
```

### Get Something

To retrieve a resource by its ID, use the `getSomething` method:

```php
$id = 1;
$response = $client->getSomething($id);
print_r($response);
```

### Create Something

To create a new resource, use the `createSomething` method:

```php
$data = ['name' => 'New Resource'];
$response = $client->createSomething($data);
print_r($response);
```

### Update Something

To update an existing resource, use the `updateSomething` method:

```php
$id = 1;
$data = ['name' => 'Updated Resource'];
$response = $client->updateSomething($id, $data);
print_r($response);
```

### Delete Something

To delete a resource by its ID, use the `deleteSomething` method:

```php
$id = 1;
$response = $client->deleteSomething($id);
print_r($response);
```

## Running Tests

To run the tests, use the following command:

```bash
composer test
```

## License

This project is licensed under the MIT License. See the [LICENSE](LICENSE) file for details.
