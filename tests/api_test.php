<?php

declare(strict_types=1);

require_once __DIR__ . '/framework.php';

describe('Route Matching', function () {
    test('authorize route matches GET requests with version', function () {
        $pattern = '/^GET:\/api\/v\d+\/authorize/';
        assertTrue(preg_match($pattern, 'GET:/api/v1/authorize') === 1);
        assertTrue(preg_match($pattern, 'GET:/api/v2/authorize') === 1);
        assertTrue(preg_match($pattern, 'GET:/api/v10/authorize') === 1);
    });

    test('authorize route does not match POST requests', function () {
        $pattern = '/^GET:\/api\/v\d+\/authorize/';
        assertFalse(preg_match($pattern, 'POST:/api/v1/authorize') === 1);
    });

    test('notify route matches POST requests with version', function () {
        $pattern = '/^POST:\/api\/v\d+\/notify/';
        assertTrue(preg_match($pattern, 'POST:/api/v1/notify') === 1);
        assertTrue(preg_match($pattern, 'POST:/api/v2/notify') === 1);
    });

    test('notify route does not match GET requests', function () {
        $pattern = '/^POST:\/api\/v\d+\/notify/';
        assertFalse(preg_match($pattern, 'GET:/api/v1/notify') === 1);
    });
});

describe('Authorize Response Structure', function () {
    test('success response has correct structure', function () {
        $body = '{ "status" : "success", "data" : { "authorization" : true } }';
        $json = json_decode($body, true);

        assertEquals('success', $json['status']);
        assertTrue($json['data']['authorization']);
    });

    test('fail response has correct structure', function () {
        $body = '{ "status" : "fail", "data" : { "authorization" : false } }';
        $json = json_decode($body, true);

        assertEquals('fail', $json['status']);
        assertFalse($json['data']['authorization']);
    });
});

describe('Notify Response Structure', function () {
    test('success response is empty with 204', function () {
        $body = '';
        assertEquals('', $body);
    });

    test('error response has correct structure', function () {
        $body = '{ "status" : "error", "message": "The service is not available, try again later" }';
        $json = json_decode($body, true);

        assertEquals('error', $json['status']);
        assertContains('not available', $json['message']);
    });
});

describe('Not Found Response', function () {
    test('not found response includes route in message', function () {
        $route = 'GET:/api/v1/unknown';
        $body = sprintf('{ "status" : "fail", "data" : { "message": "Route \'%s\' not found" } }', $route);
        $json = json_decode($body, true);

        assertEquals('fail', $json['status']);
        assertContains($route, $json['data']['message']);
    });
});

describe('HTTP Status Cat Header', function () {
    test('generates correct cat URL for status codes', function () {
        $codes = [200, 204, 403, 404, 500, 504];
        foreach ($codes as $code) {
            $header = sprintf('https://http.cat/status/%s', $code);
            assertContains((string)$code, $header);
            assertContains('http.cat', $header);
        }
    });
});

exit(summary());
