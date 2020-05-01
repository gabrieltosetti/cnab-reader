<?php

namespace Cnab\Arquivo;

class Arquivo
{
    /**
     * Caminho e nome do arquivo
     * @var string
     */
    public $filePath;

    /**
     * 
     * @var resource
     */
    private $fileHandler;

    /**
     * 
     * @var string
     */
    public $layout;

    /**
     * Numero do banco com 3 caracteres (ex.: 001, 033, 237)
     *
     * @var string
     */
    public $banco;

    public function __construct(string $filePath)
    {
        $this->filePath = $filePath;
        $this->fileHandler = fopen($this->filePath, 'r');

        $this->getBancoArquivo();
    }

    private function getBancoArquivo()
    {
        
    }
}
