<?php

declare(strict_types=1);

use AmountToWords\AmountToWords;
use AmountToWords\ConversionOptions;

require dirname(__DIR__) . '/vendor/autoload.php';

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Content-Type, Accept');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

$method = $_SERVER['REQUEST_METHOD'];
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?: '/';
$converter = new AmountToWords();

/** @param mixed $data */
function respond(mixed $data, int $status = 200): never
{
    http_response_code($status);
    echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR);
    exit;
}

/** @return array<string, mixed> */
function jsonBody(): array
{
    $raw = file_get_contents('php://input');
    if ($raw === false || trim($raw) === '') {
        throw new InvalidArgumentException('A JSON request body is required.');
    }

    $data = json_decode($raw, true, flags: JSON_THROW_ON_ERROR);
    if (!is_array($data)) {
        throw new InvalidArgumentException('The JSON body must be an object.');
    }

    return $data;
}

/** @param array<string, mixed> $data */
function requiredString(array $data, string $key): string
{
    if (!isset($data[$key]) || !is_string($data[$key]) || trim($data[$key]) === '') {
        throw new InvalidArgumentException(sprintf('Field "%s" is required and must be a string.', $key));
    }
    return $data[$key];
}

try {
    if ($method === 'GET' && $path === '/health') {
        respond(['status' => 'ok', 'service' => 'WorldMoneyWords', 'version' => '1.0.0']);
    }

    if ($method === 'GET' && $path === '/v1/countries') {
        respond(['data' => $converter->supportedCountries()]);
    }

    if ($method === 'GET' && $path === '/v1/currencies') {
        respond(['data' => $converter->supportedCurrencies()]);
    }

    if ($method === 'POST' && $path === '/v1/convert') {
        $body = jsonBody();
        if (!array_key_exists('amount', $body) || !is_int($body['amount']) && !is_float($body['amount']) && !is_string($body['amount'])) {
            throw new InvalidArgumentException('Field "amount" is required and must be a number or numeric string.');
        }

        $optionsData = isset($body['options']) && is_array($body['options']) ? $body['options'] : [];
        $options = new ConversionOptions(
            includeOnly: (bool) ($optionsData['includeOnly'] ?? true),
            includeZeroMinor: (bool) ($optionsData['includeZeroMinor'] ?? false),
            conjunction: (string) ($optionsData['conjunction'] ?? 'and'),
        );

        respond($converter->convertDetailed(
            $body['amount'],
            requiredString($body, 'countryCode'),
            requiredString($body, 'currencyCode'),
            $options,
        ));
    }

    if ($method === 'POST' && $path === '/v1/convert/batch') {
        $body = jsonBody();
        if (!isset($body['items']) || !is_array($body['items'])) {
            throw new InvalidArgumentException('Field "items" is required and must be an array.');
        }
        if (count($body['items']) > 100) {
            throw new InvalidArgumentException('A batch may contain at most 100 items.');
        }

        $items = array_map(static function (mixed $item): array {
            if (!is_array($item) || !array_key_exists('amount', $item)) {
                throw new InvalidArgumentException('Every batch item requires amount, countryCode, and currencyCode.');
            }
            return [
                'amount' => $item['amount'],
                'country' => requiredString($item, 'countryCode'),
                'currency' => requiredString($item, 'currencyCode'),
            ];
        }, $body['items']);

        respond(['data' => $converter->convertMany($items)]);
    }

    respond(['error' => ['code' => 'not_found', 'message' => 'Endpoint not found.']], 404);
} catch (JsonException $error) {
    respond(['error' => ['code' => 'invalid_json', 'message' => $error->getMessage()]], 400);
} catch (InvalidArgumentException $error) {
    respond(['error' => ['code' => 'validation_error', 'message' => $error->getMessage()]], 422);
} catch (Throwable $error) {
    respond(['error' => ['code' => 'internal_error', 'message' => 'Unexpected server error.']], 500);
}
