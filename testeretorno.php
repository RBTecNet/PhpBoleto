<?php
require 'vendor/autoload.php';
$retorno = \Rbtecnet\Phpboleto\Cnab\Retorno\Factory::make('retorno' . DIRECTORY_SEPARATOR . 'CB040100.RET');
$retorno->processar();
echo $retorno->getBancoNome();

$detalhes = $retorno->getDetalhes();
dd($detalhes);
$i=0;
$t=0;
foreach ($detalhes as $detalhe){
    if($detalhe->ocorrenciaDescricao=="Entrada Confirmada"){
        //echo $detalhe->nossoNumero.'-'.$detalhe->valor.'-'.$detalhe->ocorrenciaDescricao.'-'.$detalhe->dataOcorrencia."<br>";
        $pgtos[$t]=$detalhe;
        $t++;
    }else{
        //echo $detalhe->nossoNumero.'-'.$detalhe->valor.'-'.$detalhe->ocorrenciaDescricao.'-'.$detalhe->dataOcorrencia."<br>";
        $dados[$i]=$detalhe;
        $i++;
    }

    if ($detalhe->codigoLiquidacao!=null){
        echo $detalhe->codigoLiquidacao.'-'.$detalhe->valor.'-'.$detalhe->ocorrenciaDescricao.'-'.$detalhe->dataOcorrencia."<br>";
    }
}
//dd($pgtos);


