<?php

namespace Cnab;

use Exception;

/**
 * Classe com todos os bancos implementados
 * @package Cnab
 */
class Banco {
    /**
     * Codigo do banco SANTANDER
     * @var string
     */
    const SANTANDER = '033';

    /**
     * Nome do banco informado
     * @param string $codigoBanco 
     *
     * @return string
     */
    public static function getNomeBanco(string $codigoBanco): string
    {
        switch ($codigoBanco) {
            case self::SANTANDER:
                return 'santander';
            default:
                throw new \Exception('Banco não implementado');
        }
    }
}