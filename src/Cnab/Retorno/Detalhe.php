<?php

namespace Cnab\Retorno;

class Detalhe
{
    /**
     * 
     * @var int
     */
    public $nossoNumero;

    private $data = [];

    function __construct(\SplFileObject $file, array $layout)
    {
        $linha = preg_replace("/[\n\r]/", '', $file->current());
        $segmentosEncontrados = [];

        foreach ($layout['registro']['segmentos'] as $seg) {
            $segCodigo = $this->getPosition($linha, $seg['codigo_pos']);

            // Ja foi lido este segmento
            if (in_array($segCodigo, $segmentosEncontrados)) {
                break;
            }

            foreach ($seg['campos'] as $campo => $campoInfo) {
                $this->{$campo} = $this->getPosition($linha, $campoInfo['pos']);
            }
        }
    }

    private function getPosition(string $linha, array $positions)
    {
        $tamanho = ($positions[1] - $positions[0]) + 1;

        return substr($linha, ($positions[0] - 1), $tamanho);
    }

    function __set($campo, $valor)
    {
        $this->data[$campo] = $valor;

        $campoCamel = str_replace('_', '', ucwords($campo, '_'));
        $setCampo = "set{$campoCamel}";

        if (method_exists($this, $setCampo)) {
            $this->$setCampo($valor);
        }
    }

    function __get($campo)
    {
        if (!isset($this->data[$campo])) {
            return $this->$campo;
        }

        $campoCamel = str_replace('_', '', ucwords($campo, '_'));
        $getCampo = "get{$campoCamel}";

        if (method_exists($this, $getCampo)) {
            return $this->$getCampo();
        }

        return $this->data[$campo];
    }
}
