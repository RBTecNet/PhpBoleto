<?php

require 'vendor/autoload.php';
$Beneficiario = new \Rbtecnet\Phpboleto\Pessoa([
    'nome' => 'Brasinorte Servicos De Telefonia Limitada - Epp',
    'endereco' => 'Praça Tenente Gil Guilherme - 05',
    'cep' => '22291190',
    'uf' => 'RJ',
    'cidade' => 'Rio de Janeiro',
    'documento' => '03287545000119',
]);


$pagador = new Rbtecnet\PhpBoleto\Pessoa([
    'nome' => 'COND. ED. A RUA TENENTE GIL GUILHERME',
    'endereco' => 'Praça Tenente Gil Guilherme - 05',
    'bairro' => 'Urca',
    'cep' => '22291190',
    'uf' => 'RJ',
    'cidade' => 'Rio de Janeiro',
    'documento' => '07431714700',
]);

$boleto = new \Rbtecnet\Phpboleto\Boleto\Banco\Bradesco([
    'logo' => 'logos/empresa.png',
    'dataVencimento' => new Carbon\Carbon('2018-11-12', 'America/Sao_Paulo'),
    'dataDocumento' => new Carbon\Carbon('2020-09-29', 'America/Sao_Paulo'),
    'valor' => 68.85,
    'desconto' => 0.00,
    'acrescimo' => 0.00,
    'multa' => false,
    'juros' => false,
    'parcela' => '1 de 1',
    'notafiscal' => '12345678900',
    'moedaextenso' => 'R$',
    'numero' => '229571',
    'numeroDocumento' => '229571',
    'pagador' => $pagador,
    'beneficiario' => $Beneficiario,
    'carteira' => '09',
    'agencia' => '0541',
    'conta' => 36912,
    'codigoCliente' => 07431714700,
    'operacao' => '1234567',
    'descricaoDemonstrativo' => ['Itens Manutenção Preventiva - Mês Referência 10/2018
INTERFONES - 68,85'],
    'instrucoes' => ['PAGAVÉL EM QUALQUER BANCO ATÉ O VENCIMENTO
APÓS O VENCIMENTO COBRAR MULTA DE R$ 0,01
APÓS O VENCIMENTO COBRAR MORA DE R$ 0,00 AO DIA'],
    'aceite' => 'N',
    'especieDoc' => 'DS',
]);
$remessa = new \Rbtecnet\Phpboleto\Cnab\Remessa\Cnab400\Banco\Bradesco([
    'agencia'      => 0541,
    'conta'        => 36912,
    'contaDv'      => 0,
    'carteira'     => $boleto->getCarteira(),
    'codigoCliente' => 07431714700,// $boleto->getCodigoCliente(),
    'beneficiario' => $Beneficiario,
    'idremessa' => $boleto->getNumeroDocumento(),
]);


$remessa->addBoleto($boleto);
$remessa->addBoleto($boleto);
$remessa->addBoleto($boleto);
$remessa->addBoleto($boleto);
$remessa->addBoleto($boleto);
$remessa->addBoleto($boleto);


$remessa->save('output' . DIRECTORY_SEPARATOR . date('Ymdhis') . '.txt');
echo "gerado com sucesso";