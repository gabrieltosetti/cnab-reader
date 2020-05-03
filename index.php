<?php
include_once 'vendor/autoload.php';

use \Cnab\Retorno\Factory;

$filePath = 'cnab-examples/santander240_28-04-2020.TXT';

$arquivo = Factory::criarArquivo($filePath);

$detalhes = $arquivo->getDetalhes();

foreach ($detalhes as $detalhe) {
    var_dump($detalhe->nosso_numero);
    die();
}
