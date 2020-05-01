<?php

namespace Cnab\Arquivo;

class Factory
{

    private $fileHandler;

    public static function getArquivo(string $filePath)
    {
        $this->fileHandler = fopen($filePath, 'r');

    }

    private function getNumeroBanco()
    {
        $PrimeiraLinha = $this->fileHandler;
    }

    private function getLayoutBanco()
    {

    }

    private function openFile($filePath)
    {
        $this->fileHandler = fopen($filePath, 'r');

        if (!$this->fileHandler) {
            throw new \Exception('Erro ao abrir arquivo');
        }
    }
}
