<?php

namespace Cnab\Retorno;

class Arquivo
{
    /**
     * 
     * @var \SplFileObject
     */
    private $file;

    /**
     * 
     * @var array
     */
    public $layout;

    /**
     * Numero do banco com 3 caracteres (ex.: 001, 033, 237)
     *
     * @var string
     */
    public $codigoBanco;

    public function __construct(\SplFileObject $file, array $layout)
    {
        $file->rewind();

        $this->file = $file;
        $this->codigoBanco = $layout['codigo_banco'];
        $this->layout = $layout;
    }

    public function getDetalhes(): Detalhes
    {
        return new Detalhes($this->file, $this->codigoBanco, $this->layout);
    }
}
