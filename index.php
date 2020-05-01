<?php
include_once 'vendor/autoload.php';

use \Cnab\Retorno\Factory;

$filePath = 'cnab-examples/santander240_28-04-2020.TXT';

$arquivo = Factory::criarArquivo($filePath);

var_dump($arquivo->getDetalhes()->current());