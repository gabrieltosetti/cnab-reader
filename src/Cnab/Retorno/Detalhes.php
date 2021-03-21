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
    private $key;

    /**
     * Quantidade de linhas (segmentos) do detalhe atual
     * @var int
     */
    private $qtdLinhasDetalheAtual;

    /**
     * Todas as linhas do detalhe atual
     * @var array
     */
    private $linhasDetalheAtual;

    /**
     * Numero do banco com 3 caracteres (ex.: 001, 033, 237)
     *
     * @var string
     */
    public $codigoBanco;

    /**
     * 
     * @var bool
     */
    private $isValid = null;

    public function __construct(\SplFileObject $file, string $codigoBanco, array $layout)
    {
        $this->file = $file;
        $this->codigoBanco = $codigoBanco;
        $this->layout = $layout;

        $this->pularHeaderTrailler();
    }

    public function getLinhaAtual()
    {
        // Lembrando que a primeira linha é o indice 0
        return $this->key + 1;
    }

    public function setLinhaAtual($linha)
    {
        // Lembrando que a primeira linha é o indice 0
        $this->key = $linha - 1;
        return;
    }

    private function pularHeaderTrailler()
    {
        $this->file->seek(2);
        $this->key = 2;
    }

    private function getLinhasDetalheAtual(): array
    {
        if (!$this->linhasDetalheAtual) {
            $this->validarDetalheAtual();
        }

        return $this->linhasDetalheAtual;
    }

    private function validarDetalheAtual()
    {
        $linhas = Detalhe\Factory::getLinhasSegmentos($this->file, $this->layout['retorno']['segmentos']);

        if (!$linhas) {
            $this->isValid = false;
            $this->qtdLinhasDetalheAtual = null;
            $this->linhasDetalheAtual = null;

            return;
        }

        $this->isValid = true;
        $this->qtdLinhasDetalheAtual = count($linhas);
        $this->linhasDetalheAtual = $linhas;
    }

    public function current(): Detalhe\Detalhe
    {
        if (!$this->valid()) {
            throw new \Exception('Não existe detalhe no ponteiro atual.');
        }

        $linhas = $this->getLinhasDetalheAtual();

        return Detalhe\Factory::createDetalhe($linhas, $this->layout);
    }

    public function next()
    {
        if (!$this->valid()) {
            return;
        }

        $linhaProximoDetalhe = $this->qtdLinhasDetalheAtual + 1;

        // Mover o ponteiro para o proximo detalhe
        // Usar o next, o seek é absurdamente mais lento
        for ($i = 1; $i <= $linhaProximoDetalhe; $i++) {
            $this->file->next();
        }

        // Atualizar a linha atual
        $this->key += $this->qtdLinhasDetalheAtual;

        // limpar a quantidade de linhas do detalhe atual
        $this->qtdLinhasDetalheAtual = null;

        // Apos andar o ponteiro, não sabemos se o detalhe atual é valido
        $this->isValid = null;
        return;
    }

    public function getDetalhe(): Detalhe\Detalhe
    {
        return $this->current();
    }

    public function key(): int
    {
        return $this->key;
    }

    public function rewind()
    {
        $this->pularHeaderTrailler();
    }

    public function valid(): bool
    {
        // Caso o detalhe da posição atual do ponteiro não foi validado ainda
        if ($this->isValid === null) {
            $this->validarDetalheAtual();
        }

        // Caso o detalhe atual já foi validado, retornar valor
        return $this->isValid;
    }
}
