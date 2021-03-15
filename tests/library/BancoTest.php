<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;

final class BancoTest extends TestCase
{
    public function testBancoRetornaNomeSantander(): void
    {
        $this->assertEquals('santander', \Cnab\Banco::getNomeBanco('033'));
    }

    public function testBancoNomeNaoImplementado(): void
    {
        $this->expectExceptionMessage('Banco não implementado');

        \Cnab\Banco::getNomeBanco('999');
    }
}


