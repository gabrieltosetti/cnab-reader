<?php
function getMemoryPeak() {
    $bytes = memory_get_peak_usage();
    $precision = 2;

    $units = array("b", "kb", "mb", "gb", "tb");

    $bytes = max($bytes, 0);
    $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
    $pow = min($pow, count($units) - 1);

    $bytes /= (1 << (10 * $pow));

    return round($bytes, $precision) . " " . $units[$pow];
}

print "\nMemory: " . getMemoryPeak() . "\n";

/**
 * carregar o arquivo inteiro
 */

$filePath = 'cnab-examples/santander240_28-04-2020.TXT';

$file = file_get_contents($filePath);


/**
 * Ler linha por linha
 */

// $file = new \SplFileObject($filePath, 'r');

// $count = 0;

// foreach ($file as $line) {
//     // print $line;
//     ++$count;
// }

print "\nMemory: " . getMemoryPeak() . "\n";
