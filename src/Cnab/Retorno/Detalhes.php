<?php
declare(strict_types = 1);

namespace Cnab\Retorno;

use \Cnab\Retorno\Detalhe;

class Detalhes implements \Iterator
{
    private \SplFileObject $file;

    public array $layout;

    private int $key;

    /** Quantidade de linhas (segmentos) do detalhe atual */
    private int $qtdLinhasDetalheAtual;

    /** Todas as linhas do detalhe atual */
    private array $linhasDetalheAtual;

    /** Numero do banco com 3 caracteres (ex.: 001, 033, 237) */
    public string $codigoBanco;

    private ?bool $isValid = null;

    public function __construct(\SplFileObject $file, string $codigoBanco, array $layout)
    {
        $this->file = $file;
        $this->codigoBanco = $codigoBanco;
        $this->layout = $layout;

        $this->pularHeaderTrailler();
    }

    public function getLinhaAtual(): int
    {
        // Lembrando que a primeira linha é o indice 0
        return $this->key + 1;
    }

    private function pularHeaderTrailler(): void
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

    private function validarDetalheAtual(): void
    {
        $linhas = Detalhe\Factory::getLinhasSegmentos($this->file, $this->layout['retorno']['segmentos']);

        if (!$linhas) {
            $this->isValid = false;
            $this->qtdLinhasDetalheAtual = 0;
            $this->linhasDetalheAtual = [];

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

    public function next(): void
    {
        if (!$this->valid()) {
            return;
        }

        // Mover o ponteiro para o proximo detalhe
        // Usar o next, o seek é absurdamente mais lento
        for ($i = 1; $i <= $this->qtdLinhasDetalheAtual; $i++) {
            $this->file->next();
        }

        // Atualizar a linha atual
        $this->key += $this->qtdLinhasDetalheAtual;

        // limpar a quantidade de linhas do detalhe atual
        $this->qtdLinhasDetalheAtual = 0;

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

    public function rewind(): void
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
