<?php

declare(strict_types=1);

use Random\RandomException;

$uri = $_SERVER['REQUEST_URI'] ?? '';
$method = $_SERVER['REQUEST_METHOD'] ?? '';
$route = sprintf('%s:%s', $method, $uri);

try {
    match (true) {
        preg_match('/^GET:\/api\/v\d+\/authorize/', $route) === 1 => authorize(),
        preg_match('/^POST:\/api\/v\d+\/notify/', $route) === 1 => notify(),
        default => notFound($route),
    };
} catch (RandomException $e) {
    exception($e);
}


/**
 * @throws RandomException
 */
function authorize(): void
{
    $response_code = 200;
    $body = '{ "status" : "success", "data" : { "authorization" : true } }';
    if (random_int(0, 1)) {
        $response_code = 403;
        $body = '{ "status" : "fail", "data" : { "authorization" : false } }';
    }

    http_response_code($response_code);
    header('Content-Type: application/json; charset=utf-8');
    header(sprintf('X-HTTP-Status-Cat: https://http.cat/status/%s', $response_code));
    echo $body;
}

/**
 * @throws RandomException
 */
function notify(): void
{
    $response_code = 204;
    $body = '';
    if (random_int(0, 1)) {
        $response_code = 504;
        $body = '{ "status" : "error", "message": "The service is not available, try again later" }';
    }

    http_response_code($response_code);
    header('Content-Type: application/json; charset=utf-8');
    header(sprintf('X-HTTP-Status-Cat: https://http.cat/status/%s', $response_code));
    echo $body;
}

function notFound(string $route): void
{
    http_response_code(404);
    header('Content-Type: application/json; charset=utf-8');
    header('X-HTTP-Status-Cat: https://http.cat/status/404');
    echo sprintf('{ "status" : "fail", "data" : { "message": "Route \'%s\' not found" } }', $route);
}

/**
 * @param Exception|RandomException $e
 * @return void
 */
function exception(Exception|RandomException $e): void
{
    http_response_code(500);
    header('Content-Type: application/json; charset=utf-8');
    header('X-HTTP-Status-Cat: https://http.cat/status/500');
    echo sprintf(
        '{ "status" : "error", "message" : "\'%s\' on \'%s\' at %s" }',
        $e->getMessage(),
        $e->getFile(),
        $e->getLine()
    );
}
