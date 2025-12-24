<?php

declare(strict_types=1);

echo "Running tests...\n";

$testFiles = glob(__DIR__ . '/*_test.php');
$exitCode = 0;

foreach ($testFiles as $file) {
    $result = 0;
    passthru("php {$file}", $result);
    if ($result !== 0) {
        $exitCode = 1;
    }
}

exit($exitCode);
