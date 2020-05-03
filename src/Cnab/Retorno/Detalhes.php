<?php

namespace Cnab\Retorno;

use \Cnab\Retorno\Detalhe;

class Detalhes implements \Iterator
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
     * 
     * @var int
     */
    private $currentLine;

    /**
     * Quantas linhas (segmentos) sao necessarias pular para o começo do proximo Detalhe
     * @var int
     */
    private $qtdLinhasParaProximoDetalhe;

    /**
     * Numero do banco com 3 caracteres (ex.: 001, 033, 237)
     *
     * @var string
     */
    public $codigoBanco;

    public function __construct(\SplFileObject $file, string $codigoBanco, array $layout)
    {
        $this->file = $file;
        $this->codigoBanco = $codigoBanco;
        $this->layout = $layout;

        $this->pularHeaderTrailler();
    }

    public function getCurrentLine()
    {
        // Lembrando que a primeira linha é o indice 0
        return $this->currentLine + 1;
    }

    private function pularHeaderTrailler()
    {
        $this->file->seek(2);
        $this->currentLine = 2;
    }

    public function current(): Detalhe\Detalhe
    {
        $linhas = Detalhe\Factory::getLinhasSegmentos($this->file, $this->layout['retorno']['segmentos']);
        $this->qtdLinhasParaProximoDetalhe = count($linhas);

        return Detalhe\Factory::createDetalhe($linhas, $this->layout);
    }

    public function next()
    {
        if ($this->qtdLinhasParaProximoDetalhe) {
            $this->file->seek($this->file->key() + $this->qtdLinhasParaProximoDetalhe);
            $this->qtdLinhasParaProximoDetalhe = null;
        } else {
            $linhas = Detalhe\Factory::getLinhasSegmentos($this->file, $this->layout['retorno']['segmentos']);
            $this->file->seek($this->file->key() + count($linhas));
        }

        $this->currentLine = $this->file->key();
        return;
    }

    public function getDetalhe()
    {
        return new Detalhe($this->file, $this->layout);
    }

    public function key(): int
    {
        return $this->currentLine;
    }
    public function rewind()
    {
        $this->pularHeaderTrailler();
    }
    public function valid(): bool
    {
        return true;
    }
}
