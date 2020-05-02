<?php

namespace Cnab\Retorno;

use \SplFileObject;
use \Cnab\Retorno\Arquivo;
use \Cnab\Banco;

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
        // Posição genérica do código do banco: 1, 3
        return substr($file->current(), 0, 3);
    }

    private static function getTamanhoArquivo(\SplFileObject $file): int
    {
        return strlen(preg_replace("/[\n\r]/", '', $file->current()));
    }

    private static function getVersaoArquivo(\SplFileObject $file): string
    {
        // Posição genérica da versão do arquivo: 164, 166
        return substr($file->current(), 163, 3);
    }

    private static function getLayoutArquivo(string $codigoBanco, $tamanhoArquivo, $versaoArquivo): array
    {
        $nomeBanco = ucfirst(Banco::getNomeBanco($codigoBanco));
        $json = file_get_contents(__DIR__. "/../Layouts/{$nomeBanco}/{$tamanhoArquivo}/{$versaoArquivo}.json");
        $layout = json_decode($json, true);

        return $layout;
    }

    private function openFile($filePath): \SplFileObject
    {
        return new SplFileObject($filePath, 'r');
    }
}
