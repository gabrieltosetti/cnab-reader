<?php
include_once 'vendor/autoload.php';

use \Cnab\Retorno\Factory;

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

$filePath = 'cnab-examples/santander240_28-04-2020.TXT';

$arquivo = Factory::criarArquivo($filePath);

$detalhes = $arquivo->getDetalhes();

foreach ($detalhes as $detalhe) {
    // var_dump($detalhe->nosso_numero);
    // die();
}
print "\nMemory: " . getMemoryPeak() . "\n";
