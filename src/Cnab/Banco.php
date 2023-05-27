<?php

declare(strict_types = 1);

namespace Cnab;

use Cnab\Enums\BanksEnum;

/**
 * Classe com todos os bancos implementados
 */
class Banco {

    /** Nome do banco informado */
    public static function getNomeBanco(string $codigoBanco): string
    {
        switch ($codigoBanco) {
            case BanksEnum::SANTANDER:
                return 'santander';
            default:
                throw new \Exception('Banco não implementado');
        }
    }
}