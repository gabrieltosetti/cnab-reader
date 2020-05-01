<?php

namespace Cnab\Retorno;

use \SplFileObject;
use \Cnab\Retorno\Arquivo;

class Factory
{
    public static function criarArquivo(string $filePath)
    {
        $file = self::openFile($filePath);

        $codigoBanco = self::getNumeroBancoArquivo($file);
        $tamanhoArquivo = self::getTamanhoArquivo($file);
        $versaoArquivo = self::getVersaoArquivo($file);
        $layout = self::getLayoutArquivo($codigoBanco, $tamanhoArquivo, $versaoArquivo);

        return new Arquivo($file, $layout);

    }

    private static function getNumeroBancoArquivo(\SplFileObject $file): string
    {
        return '033';
    }

    private static function getTamanhoArquivo(\SplFileObject $file): int
    {
        return 240;
    }

    private static function getVersaoArquivo(\SplFileObject $file): string
    {
        return '030';
    }

    private static function getLayoutArquivo(string $codigoBanco, $tamanhoArquivo, $versaoArquivo): array
    {
        $nomeBanco = 'Santander';
        $json = file_get_contents(__DIR__. "/../Layouts/{$nomeBanco}/{$tamanhoArquivo}/{$versaoArquivo}.json");
        $layout = json_decode($json, true);

        return $layout;
    }

    private function openFile($filePath): \SplFileObject
    {
        return new SplFileObject($filePath, 'r');
    }
}
