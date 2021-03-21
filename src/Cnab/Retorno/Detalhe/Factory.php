<?php

namespace Cnab\Retorno\Detalhe;

class Factory
{
    public static function createDetalhe(array $linhas, array $layout): Detalhe
    {
        $layoutSegmentos = $layout['retorno']['segmentos'];
        $codigos = array_column($layoutSegmentos, 'codigo');
        $codigoPos = $layoutSegmentos[0]['codigo_pos'];

        $detalhe = new Detalhe($linhas, $layout);

        foreach ($linhas as $linha) {
            $segCodigo = self::getPosition($linha, $codigoPos);

            $key = array_search($segCodigo, $codigos);
            $segmento = $layoutSegmentos[$key];

            foreach ($segmento['campos'] as $campo => $campoInfo) {
                $valor = self::getPosition($linha, $campoInfo['pos']);
                $valor = self::formatarValor($valor, $campoInfo);

                $detalhe->{$campo} = $valor;
            }
        }

        return $detalhe;
    }

    /**
     * Bem parecido com o substr mas usando array
     * @param string $linha 
     * @param array  $positions Array com apenas inicio e fim em integer
     *
     * @return string 
     */
    private static function getPosition(string $linha, array $positions): string
    {
        $tamanho = ($positions[1] - $positions[0]) + 1;

        return substr($linha, ($positions[0] - 1), $tamanho);
    }

    public static function getLinhasSegmentos(\SplFileObject $file, array $layoutSegmentos): array
    {
        // Todos os possiveis segmentos do layout
        $codigoSegmentos = array_column($layoutSegmentos, 'codigo');
        $posicaoSegmentosPadrao = $layoutSegmentos[0]['codigo_pos'];
        $linhaAtual = $file->ftell();

        $segmentosJaEncontrados = [];
        $linhas = [];

        do {
            $linha = preg_replace("/[\n\r]/", '', $file->current());
            $file->next();

            $segCodigo = self::getPosition($linha, $posicaoSegmentosPadrao);

            if (in_array($segCodigo, $segmentosJaEncontrados)) {
                break;
            }

            if (in_array($segCodigo, $codigoSegmentos)) {
                $segmentosJaEncontrados[] = $segCodigo;
                $linhas[] = $linha;
                continue;
            }

            break;
        } while (true);

        // Resetar o arquivo para a linha antes de chegar aqui
        $file->fseek($linhaAtual);

        return $linhas;
    }

    private static function formatarValor(string $valor, array $info)
    {
        switch ($info['tipo']) {
            case 'int':
                return (int) trim($valor);

            case 'string':
                return $valor;

            case 'decimal':
                if (!$info['casas_decimais']) {
                    throw new \Exception('Casas decimais não informadas.');
                }

                $valor = (int) trim($valor);

                // Para andar a vírgula para a esquerda, basta dividir o número por 10, 100, 1000...
                return (float) ($valor / pow(10, $info['casas_decimais']));
            default:
                throw new \Exception("Tipo do campo não encontrado: {$info['tipo']}");
        }
    }
}
