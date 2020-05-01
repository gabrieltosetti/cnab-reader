<?php

namespace Cnab\Retorno;

class Detalhes
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

    public function __construct(\SplFileObject $file, string $codigoBanco, array $layout)
    {
        $file->rewind();

        $this->file = $file;
        $this->codigoBanco = $codigoBanco;
        $this->layout = $layout;

        $this->pularHeaderTrailler();
    }

    private function pularHeaderTrailler()
    {
        $this->file->seek(2);
    }

    public function current(): string
    {
        return $this->file->current();
    }

    public function getDetalhe()
    {
        return new Detalhe($this->file, $this->layout);
    }
}