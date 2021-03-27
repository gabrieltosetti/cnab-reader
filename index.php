<?php
include_once 'vendor/autoload.php';

use \Cnab\Retorno\Factory;

error_reporting(E_ALL);
set_time_limit(1000);

$filePath = 'cnab-files/santander240_28-04-2020.TXT';

$arquivo = Factory::criarArquivo($filePath);

$detalhes = $arquivo->getDetalhes();

$count = 1;

$debug = microtime(true);
foreach ($detalhes as $detalhe) {
    var_dump($detalhe->nosso_numero);
}
