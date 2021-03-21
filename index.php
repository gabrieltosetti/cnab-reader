<?php
include_once 'vendor/autoload.php';

use \Cnab\Retorno\Factory;

error_reporting(E_ALL);
set_time_limit(1000);

print "\nMemory: " . getMemoryPeak() . "\n\n";

$filePath = 'cnab-files/santander240_28-04-2020.TXT';

$arquivo = Factory::criarArquivo($filePath);

$detalhes = $arquivo->getDetalhes();

$count = 1;

$debug = microtime(true);
foreach ($detalhes as $detalhe) {
    // var_dump($detalhe->nosso_numero);
    ++$count;

    if ($count == 30000) {
        break;
    }
}
echo round(microtime(true) - $debug, 3);