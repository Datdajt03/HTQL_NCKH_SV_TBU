<?php
declare(strict_types=1);

$dir = dirname(__DIR__);
$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));
$phpFiles = [];

foreach ($iterator as $file) {
    if ($file->isFile() && $file->getExtension() === 'php') {
        $path = $file->getRealPath();
        // Skip vendor if any
        if (strpos($path, 'vendor') === false) {
            $phpFiles[] = $path;
        }
    }
}

$errors = 0;
foreach ($phpFiles as $f) {
    $cmd = 'a:\\xampp\\php\\php.exe -l ' . escapeshellarg($f);
    $out = [];
    $ret = 0;
    exec($cmd, $out, $ret);
    if ($ret !== 0) {
        echo "LINT ERROR: $f\n" . implode("\n", $out) . "\n";
        $errors++;
    }
}

echo "Linted " . count($phpFiles) . " PHP files. Errors: $errors\n";
if ($errors > 0) exit(1);
