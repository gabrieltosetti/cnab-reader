<?php

namespace Cnab\Retorno\Detalhe;

class Detalhe
{
    /**
     * 
     * @var int
     */
    public $nossoNumero;

    /**
     * 
     * @var array
     */
    public $linhas;

    /**
     * 
     * @var array
     */
    public $layuot;

    private $data = [];

    function __construct(array $linhas, array $layout)
    {
        $this->linhas = $linhas;
        $this->layuot = $layout;
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
