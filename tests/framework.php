<?php

declare(strict_types=1);

$GLOBALS['test_results'] = [
    'passed' => 0,
    'failed' => 0,
    'errors' => [],
];

function test(string $description, callable $fn): void
{
    try {
        $fn();
        $GLOBALS['test_results']['passed']++;
        echo "  ✓ {$description}\n";
    } catch (Throwable $e) {
        $GLOBALS['test_results']['failed']++;
        $GLOBALS['test_results']['errors'][] = [
            'test' => $description,
            'message' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
        ];
        echo "  ✗ {$description}\n";
        echo "    → {$e->getMessage()}\n";
    }
}

function assertEquals(mixed $expected, mixed $actual, string $message = ''): void
{
    if ($expected !== $actual) {
        $msg = $message
            ?: sprintf(
                "Expected %s but got %s",
                var_export($expected, true),
                var_export($actual, true)
            );
        throw new Exception($msg);
    }
}

function assertTrue(bool $condition, string $message = ''): void
{
    if (! $condition) {
        throw new Exception(
            $message
                ?: "Expected true but got false"
        );
    }
}

function assertFalse(bool $condition, string $message = ''): void
{
    if ($condition) {
        throw new Exception(
            $message
                ?: "Expected false but got true"
        );
    }
}

function assertContains(string $needle, string $haystack, string $message = ''): void
{
    if (! str_contains($haystack, $needle)) {
        $msg = $message
            ?: sprintf("Expected '%s' to contain '%s'", $haystack, $needle);
        throw new Exception($msg);
    }
}

function assertMatchesRegex(string $pattern, string $subject, string $message = ''): void
{
    if (! preg_match($pattern, $subject)) {
        $msg = $message
            ?: sprintf("Expected '%s' to match pattern '%s'", $subject, $pattern);
        throw new Exception($msg);
    }
}

function assertOneOf(mixed $actual, array $expected, string $message = ''): void
{
    if (! in_array($actual, $expected, true)) {
        $msg = $message
            ?: sprintf(
                "Expected one of %s but got %s",
                var_export($expected, true),
                var_export($actual, true)
            );
        throw new Exception($msg);
    }
}

function describe(string $suite, callable $fn): void
{
    echo "\n{$suite}\n";
    $fn();
}

function summary(): int
{
    $results = $GLOBALS['test_results'];
    $total = $results['passed'] + $results['failed'];

    echo "\n" . str_repeat('-', 40) . "\n";
    echo "Tests: {$total} total, {$results['passed']} passed, {$results['failed']} failed\n";

    return $results['failed'] > 0
        ? 1
        : 0;
}
