<?php

namespace Rbtecnet\Phpboleto\Boleto\Render;

use Rbtecnet\Phpboleto\Contracts\Boletos\Boleto as BoletoContract;
use Rbtecnet\Phpboleto\Contracts\Boletos\Render\Pdf as PdfContract;
use Rbtecnet\Phpboleto\Util;
use Illuminate\Support\Str;

class Pdf extends AbstractPdf implements PdfContract
{
    const OUTPUT_STANDARD = 'I';
    const OUTPUT_DOWNLOAD = 'D';
    const OUTPUT_SAVE = 'F';
    const OUTPUT_STRING = 'S';

    protected $PadraoFont = 'Arial';
    /**
     * @var BoletoContract[]
     */
    protected $boleto = [];

    /**
     * @var bool
     */
    protected $print = false;

    /**
     * @var bool
     */
    protected $showInstrucoes = true;

    protected $desc = 3; // tamanho célula descrição
    protected $cell = 4; // tamanho célula dado
    protected $fdes = 6; // tamanho fonte descrição
    protected $fcel = 8; // tamanho fonte célula
    protected $small = 0.2; // tamanho barra fina
    protected $totalBoletos = 0;

    public function __construct()
    {
        parent::__construct('P', 'mm', 'A4');
        $this->SetAutoPageBreak(false);
        $this->SetLeftMargin(5);
        $this->SetTopMargin(5);
        $this->SetRightMargin(5);
        $this->SetLineWidth($this->small);
    }

    /**
     * @param integer $i
     *
     * @return $this
     */
    protected function instrucoes($i)
    {
        return $this;
    }

    /**
     * @param integer $i
     *
     * @return $this
     */
    protected function logoEmpresa($i)
    {

        $this->SetY("10");
        $this->SetX("10");
        $this->Image($this->boleto[$i]->getLogo(), 10, 10, 10, 10, 'PNG', '');
        $this->traco('Recibo do Pagador', 7);
        return $this;
    }

    /**
     * @param integer $i
     *
     * @return $this
     */
    protected function Topo($i)
    {

//LINHA SUPERIOR
        $this->Line(5, 23, 205, 23);
//LINHA INFERIOR DAS PRIMEIRAS CELULAS
        $this->Line(5, 38, 205, 38);
//LINHA INFERIOR DAS SEGUNDAS CELULAS
        $this->Line(5, 48, 205, 48);
//LINHA INFERIOR DAS TERCEIRAS CELULAS
        $this->Line(5, 58, 205, 58);
//LINHA INFERIOR DAS QUARTAS CELULAS
        $this->Line(5, 68, 205, 68);
//LINHA INFERIOR DAS QUARTAS CELULAS
        $this->Line(65, 58, 65, 68);
//LINHA LATERAL ESQUEDA DO PRIMEIRO CONJUNTO DE CELULAS
        $this->Line(120, 68, 120, 23);
//LINHA LATERAL ESQUEDA DO PRIMEIRO CONJUNTO DE CELULAS
        $this->Line(165, 68, 165, 23);
//LINHA LATERAL DIREITA DA PRIMEIRA CELULA DA TERCEIRA LINHA
        $this->Line(33, 58, 33, 48);
//LINHA LATERAL DIREITA DA SEGUNDA CELULA DA TERCEIRA LINHA
        $this->Line(73, 58, 73, 48);
//LINHA LATERAL ESQUEDA
        $this->Line(5, 143, 5, 23);
//LINHA LATERAL DIREITA
        $this->Line(205, 143, 205, 23);
//LINHA INFERIOR
        $this->Line(5, 143, 205, 143);
        $this->SetFont($this->PadraoFont, '', $this->fdes);
        $this->SetY("10");
        $this->SetX("0");
        $this->Cell(0, 22, utf8_decode(''), 0, 0, "R");
        $this->Ln();
        $this->SetFont('Times', '', 8);
        $this->SetY("25");
        $this->SetX("5");
        $this->Cell(0, 0, utf8_decode('Cedente'), 0, 0, "L");
        $this->SetY("25");
        $this->SetX("120");
        $this->Cell(0, 0, utf8_decode('Agencia / Cód Cedente'), 0, 0, "");
        $this->SetY("25");
        $this->SetX("165");
        $this->Cell(0, 0, utf8_decode('Vencimento'), 0, 0, "");
        $this->SetY("40");
        $this->SetX("5");
        $this->Cell(0, 0, utf8_decode('Sacado'), 0, 0, "");
        $this->SetY("40");
        $this->SetX("120");
        $this->Cell(0, 0, utf8_decode('Numero Documento'), 0, 0, "");
        $this->SetY("40");
        $this->SetX("165");
        $this->Cell(0, 0, utf8_decode('Nosso Número'), 0, 0, "");
        $this->SetY("50");
        $this->SetX("5");
        $this->Cell(0, 0, utf8_decode('Espécie/Moéda'), 0, 0, "");
        $this->SetY("50");
        $this->SetX("33");
        $this->Cell(0, 0, utf8_decode('Quantidade'), 0, 0, "");
        $this->SetY("50");
        $this->SetX("73");
        $this->Cell(0, 0, utf8_decode('(X)Valor'), 0, 0, "");
        $this->SetY("50");
        $this->SetX("120");
        $this->Cell(0, 0, utf8_decode('(=)Valor do Documento'), 0, 0, "");
        $this->SetY("50");
        $this->SetX("165");
        $this->Cell(0, 0, utf8_decode('(-)Desconto'), 0, 0, "");
        $this->SetY("60");
        $this->SetX("5");
        $this->Cell(0, 0, utf8_decode('CPF/CNPJ Sacado'), 0, 0, "");
        $this->SetY("60");
        $this->SetX("65");
        $this->Cell(0, 0, utf8_decode('N/F Vinculada ao Boleto'), 0, 0, "");
        $this->SetY("60");
        $this->SetX("120");
        $this->Cell(0, 0, utf8_decode('(+)Outros Acréscimos'), 0, 0, "");
        $this->SetY("60");
        $this->SetX("165");
        $this->Cell(0, 0, utf8_decode('(=)Valor Cobrado'), 0, 0, "");
        $this->SetY("70");
        $this->SetX("5");
        $this->Cell(0, 0, utf8_decode('Demonstrativo:'), 0, 0, "");
        $this->SetY("142");
        $this->SetX("5");
        $this->MultiCell(205, 5, utf8_decode('Autenticação Mecânica'), 0, 'C', false);
        $this->SetFont('Times', '', 5);
        $this->SetY("147");
        $this->SetX("0");
        $this->MultiCell(210, 5, utf8_decode('-------------------------------------------------------------------------------------------------------------------------------------------------------------                                ------------------------------------------------------------------------------------------------------------------------------------------------------------'), 0, 'C', false);
        $this->SetFont('Times', '', 8);
        $this->SetY("147");
        $this->SetX("0");
        $this->MultiCell(210, 5, utf8_decode('Corte Aqui'), 0, 'C', false);

        $this->SetFont('Times', 'B', 10);
        $this->SetY("27");
        $this->SetX("5");
        $this->MultiCell(110, 5, utf8_decode(substr($this->boleto[$i]->getBeneficiario()->getNome(), 0, 110).' - CNPJ:'.$this->boleto[$i]->getBeneficiario()->getDocumento()), 0, 'L', false);
        $this->SetY("29");
        $this->SetX("130");
        $this->MultiCell(110, 5, utf8_decode(substr($this->_($this->boleto[$i]->getAgenciaCodigoBeneficiario()), 0, 24)), 0, 'L', false);
        $this->SetY("29");
        $this->SetX("185");
        $this->MultiCell(110, 5, utf8_decode(substr($this->_($this->boleto[$i]->getDataVencimento()->format('d/m/Y'), 'R'), 0, 10)), 0, 'L', false);
        $this->SetY("42");
        $this->SetX("5");
        $this->MultiCell(110, 5, utf8_decode(substr($this->boleto[$i]->getPagador()->getNome(), 0, 60)), 0, 'L', false);
        $this->SetY("42");
        $this->SetX("135");
        $this->MultiCell(110, 5, utf8_decode(substr($this->boleto[$i]->getNumeroDocumento(), 0, 24)), 0, 'L', false);
        $this->SetY("42");
        $this->SetX("175");
        $this->MultiCell(110, 5, utf8_decode(substr($this->boleto[$i]->getCarteira().'/'.$this->boleto[$i]->getNossoNumero(), 0, 17)), 0, 'L', false);
        $this->SetY("52");
        $this->SetX("5");
        $this->MultiCell(110, 5, utf8_decode(substr($this->boleto[$i]->getMoedaExtenso(), 0, 60)), 0, 'L', false);
        $this->SetY("52");
        $this->SetX("33");
        $this->MultiCell(110, 5, utf8_decode(substr($this->boleto[$i]->getParcela(), 0, 24)), 0, 'L', false);
        $this->SetY("52");
        $this->SetX("83");
        $this->MultiCell(110, 5, utf8_decode('R$ ' . substr(number_format($this->boleto[$i]->getValor(), 2, ',', ''), 0, 19)), 0, 'L', false);
        $this->SetY("52");
        $this->SetX("133");
        $this->MultiCell(110, 5, utf8_decode('R$ ' . substr(number_format($this->boleto[$i]->getValor(), 2, ',', ''), 0, 19)), 0, 'L', false);
        $this->SetY("52");
        $this->SetX("179");
        $this->MultiCell(110, 5, utf8_decode('R$ ' . substr(number_format($this->boleto[$i]->getDesconto(), 2, ',', ''), 0, 19)), 0, 'L', false);
        $this->SetY("62");
        $this->SetX("15");
        $this->MultiCell(110, 5, utf8_decode(substr($this->boleto[$i]->getPagador()->getDocumento(), 0, 19)), 0, 'L', false);
        $this->SetY("62");
        $this->SetX("75");
        $this->MultiCell(110, 5, utf8_decode(substr($this->boleto[$i]->getNotaFiscal(), 0, 19)), 0, 'L', false);
        $this->SetY("62");
        $this->SetX("133");
        $this->MultiCell(110, 5, utf8_decode('R$ ' . substr(number_format($this->boleto[$i]->getAcrescimo(), 2, ',', ''), 0, 19)), 0, 'L', false);
        $this->SetY("62");
        $this->SetX("179");
        $this->MultiCell(110, 5, utf8_decode('R$ ' . substr(number_format($this->boleto[$i]->getTotal(), 2, ',', ''), 0, 19)), 0, 'L', false);
        $this->SetY("72");
        $this->SetX("7");
        $this->MultiCell(195, 5, utf8_decode(substr($this->boleto[$i]->getDescricaoDemonstrativo()[0], 0, 1960)), 0, 'L', false);



//        $this->SetFont('Times', 'B', 18);
//        $this->SetY("161");
//        $this->SetX("46");
//        $this->MultiCell(20, 5, utf8_decode(substr($cogigo_banco . '-' . $codigo_banco_digito, 0, 6)), 0, 'L', false);

//        $this->Image($this->boleto[$i]->getLogoBanco(), 3, ($this->GetY()), 28);
//        $this->Cell(29, 8, '', 'B');
//        $this->SetFont('', 'B', 13);
//        $this->Cell(15, 8, $this->boleto[$i]->getCodigoBancoComDv(), 'LBR', 0, 'C');
//        $this->SetFont('', 'B', 10);
//        $this->Cell(0, 8, $this->boleto[$i]->getLinhaDigitavel(), 'B', 1, 'R');
//
//        $this->SetFont($this->PadraoFont, '', $this->fdes);
//        $this->Cell(75, $this->desc, $this->_('Beneficiário'), 'TLR');
//        $this->Cell(35, $this->desc, $this->_('Agência/Código do beneficiário'), 'TR');
//        $this->Cell(10, $this->desc, $this->_('Espécie'), 'TR');
//        $this->Cell(15, $this->desc, $this->_('Quantidade'), 'TR');
//        $this->Cell(35, $this->desc, $this->_('Nosso Número'), 'TR', 1);
//
//        $this->SetFont($this->PadraoFont, 'B', $this->fcel);
//
//        $this->textFitCell(75, $this->cell, $this->_($this->boleto[$i]->getBeneficiario()->getNome()), 'LR', 0, 'L');
//
//        $this->Cell(35, $this->cell, $this->_($this->boleto[$i]->getAgenciaCodigoBeneficiario()), 'R');
//        $this->Cell(10, $this->cell, $this->_('R$'), 'R');
//        $this->Cell(15, $this->cell, $this->_(''), 'R');
//        $this->Cell(35, $this->cell, $this->_($this->boleto[$i]->getNossoNumeroBoleto()), 'R', 1, 'R');
//
//        $this->SetFont($this->PadraoFont, '', $this->fdes);
//        $this->Cell(50, $this->desc, $this->_('Número do Documento'), 'TLR');
//        $this->Cell(40, $this->desc, $this->_('CPF/CNPJ'), 'TR');
//        $this->Cell(30, $this->desc, $this->_('Vencimento'), 'TR');
//        $this->Cell(50, $this->desc, $this->_('Valor do Documento'), 'TR', 1);
//
//        $this->SetFont($this->PadraoFont, 'B', $this->fcel);
//        $this->Cell(50, $this->cell, $this->_($this->boleto[$i]->getNumeroDocumento()), 'LR');
//        $this->Cell(40, $this->cell, $this->_($this->boleto[$i]->getBeneficiario()->getDocumento(), '##.###.###/####-##'), 'R');
//        $this->Cell(30, $this->cell, $this->_($this->boleto[$i]->getDataVencimento()->format('d/m/Y')), 'R');
//        $this->Cell(50, $this->cell, $this->_(Util::nReal($this->boleto[$i]->getValor())), 'R', 1, 'R');
//
//        $this->SetFont($this->PadraoFont, '', $this->fdes);
//        $this->Cell(30, $this->desc, $this->_('(-) Descontos/Abatimentos'), 'TLR');
//        $this->Cell(30, $this->desc, $this->_('(-) Outras Deduções'), 'TR');
//        $this->Cell(30, $this->desc, $this->_('(+) Mora Multa'), 'TR');
//        $this->Cell(30, $this->desc, $this->_('(+) Acréscimos'), 'TR');
//        $this->Cell(50, $this->desc, $this->_('(=) Valor Cobrado'), 'TR', 1);
//
//        $this->SetFont($this->PadraoFont, 'B', $this->fcel);
//        $this->Cell(30, $this->cell, $this->_(''), 'LR');
//        $this->Cell(30, $this->cell, $this->_(''), 'R');
//        $this->Cell(30, $this->cell, $this->_(''), 'R');
//        $this->Cell(30, $this->cell, $this->_(''), 'R');
//        $this->Cell(50, $this->cell, $this->_(''), 'R', 1, 'R');
//
//        $this->SetFont($this->PadraoFont, '', $this->fdes);
//        $this->Cell(0, $this->desc, $this->_('Pagador'), 'TLR', 1);
//
//        $this->SetFont($this->PadraoFont, 'B', $this->fcel);
//        $this->Cell(0, $this->cell, $this->_($this->boleto[$i]->getPagador()->getNomeDocumento()), 'BLR', 1);
//
//        $this->SetFont($this->PadraoFont, '', $this->fdes);
//        $this->Cell(100, $this->desc, $this->_('Demonstrativo'), 0, 0, 'L');
//        $this->Cell(0, $this->desc, $this->_('Autenticação mecânica'), 0, 1, 'R');
//        $this->Ln(2);
//
//        $pulaLinha = 26;
//
//        $this->SetFont($this->PadraoFont, 'B', $this->fcel);
//        if (count($this->boleto[$i]->getDescricaoDemonstrativo()) > 0) {
//            $pulaLinha = $this->listaLinhas($this->boleto[$i]->getDescricaoDemonstrativo(), $pulaLinha);
//        }
//
//        $this->traco('Corte na linha pontilhada', $pulaLinha, 10);

        return $this;
    }

    /**
     * @param integer $i
     *
     * @return $this
     */
    protected function Bottom($i)
    {
        //LOGO DO BANCO
        $this->Image($this->boleto[$i]->getLogoBanco(), 5, 157, 40, 10, 'png', '');
//LINHA SUPERIOR
        $this->Line(5, 167, 205, 167);
//LINHA DO LOGO
        $this->Line(45, 160, 45, 167);
//LINHA DA LINHA DIGITAVEL
        $this->Line(63, 160, 63, 167);
//LINHA LATERAL ESQUERDA
        $this->Line(5, 270, 5, 167);
//LINHA LATERAL DIREITA
        $this->Line(205, 270, 205, 167);
//LINHA PRIMEIRO CONJUNTO DE CELULAS
        $this->Line(5, 177, 205, 177);
//LINHA SEGUNDO CONJUNTO DE CELULAS
        $this->Line(5, 187, 205, 187);
//LINHA TERCEIRO CONJUNTO DE CELULAS
        $this->Line(5, 197, 205, 197);
//LINHA QUARTO CONJUNTO DE CELULAS
        $this->Line(5, 207, 205, 207);
//LINHA SUPERIOR SACADO
        $this->Line(5, 247, 205, 247);
//LINHA FINAL
        $this->Line(5, 270, 205, 270);
//LINHA QUE SEPARA A DESCRIÇÃO DOS DADOS DE COBRANCA
        $this->Line(165, 167, 165, 247);
//LINHA INFERIOR DESCONTO
        $this->Line(165, 217, 205, 217);
//LINHA INFERIOR MULTA
        $this->Line(165, 227, 205, 227);
//LINHA INFERIOR OUTROS ACRESCIMOS
        $this->Line(165, 237, 205, 237);
//LINHA DIREITA DATA DOCUMENTO
        $this->Line(40, 187, 40, 207);
//LINHA DIREITA NUMERO DOCUMENTO
        $this->Line(80, 187, 80, 207);
//LINHA DIREITA ACEITE
        $this->Line(120, 187, 120, 207);
//LINHA ESQUERDA ACEITE
        $this->Line(105, 187, 105, 197);
//LINHA ESQUERDA CARTEIRA
        $this->Line(68, 197, 68, 207);
//LINHA DIREITA COD BAIXA
        $this->Line(165, 267, 165, 270);
        $this->SetY("166");
        $this->SetX("5");
        $this->SetFont($this->PadraoFont, '', $this->fdes);
        $this->MultiCell(205, 5, utf8_decode('Local de Pagamento'), 0, 'L', false);
        $this->SetY("166");
        $this->SetX("165");
        $this->MultiCell(205, 5, utf8_decode('Vencimento'), 0, 'L', false);
        $this->SetY("179");
        $this->SetX("5");
        $this->Cell(0, 0, utf8_decode('Cedente'), 0, 0, "L");
        $this->SetY("179");
        $this->SetX("165");
        $this->Cell(0, 0, utf8_decode('Agência/Código Cedente'), 0, 0, "L");
        $this->SetY("189");
        $this->SetX("5");
        $this->Cell(0, 0, utf8_decode('Data Documento'), 0, 0, "L");
        $this->SetY("189");
        $this->SetX("40");
        $this->Cell(0, 0, utf8_decode('Número Documento'), 0, 0, "L");
        $this->SetY("189");
        $this->SetX("80");
        $this->Cell(0, 0, utf8_decode('Espécie Doc'), 0, 0, "L");
        $this->SetY("189");
        $this->SetX("105");
        $this->Cell(0, 0, utf8_decode('Aceite'), 0, 0, "L");
        $this->SetY("189");
        $this->SetX("120");
        $this->Cell(0, 0, utf8_decode('Data do Processamento'), 0, 0, "L");
        $this->SetY("189");
        $this->SetX("165");
        $this->Cell(0, 0, utf8_decode('Nosso Número'), 0, 0, "L");
        $this->SetY("199");
        $this->SetX("5");
        $this->Cell(0, 0, utf8_decode('Uso do Banco'), 0, 0, "L");
        $this->SetY("199");
        $this->SetX("40");
        $this->Cell(0, 0, utf8_decode('Carteira'), 0, 0, "L");
        $this->SetY("199");
        $this->SetX("68");
        $this->Cell(0, 0, utf8_decode('Espécie'), 0, 0, "L");
        $this->SetY("199");
        $this->SetX("80");
        $this->Cell(0, 0, utf8_decode('Quantidade'), 0, 0, "L");
        $this->SetY("199");
        $this->SetX("120");
        $this->Cell(0, 0, utf8_decode('(x)Valor'), 0, 0, "L");
        $this->SetY("199");
        $this->SetX("165");
        $this->Cell(0, 0, utf8_decode('(=)Valor Documento'), 0, 0, "L");
        $this->SetY("209");
        $this->SetX("5");
        $this->Cell(0, 0, utf8_decode('Instruções (texto de responsabilidade do cedente)'), 0, 0, "L");
        $this->SetY("209");
        $this->SetX("165");
        $this->Cell(0, 0, utf8_decode('(-)Desconto'), 0, 0, "L");
        $this->SetY("219");
        $this->SetX("165");
        $this->Cell(0, 0, utf8_decode('(+)Mora/Multa'), 0, 0, "L");
        $this->SetY("229");
        $this->SetX("165");
        $this->Cell(0, 0, utf8_decode('(+)Outros Acréscimos'), 0, 0, "L");
        $this->SetY("239");
        $this->SetX("165");
        $this->Cell(0, 0, utf8_decode('(=)Valor Cobrado'), 0, 0, "L");
        $this->SetY("249");
        $this->SetX("5");
        $this->Cell(0, 0, utf8_decode('Sacado'), 0, 0, "L");
        $this->SetY("268");
        $this->SetX("165");
        $this->Cell(0, 0, utf8_decode('Cód Baixa'), 0, 0, "L");
        $this->SetY("272");
        $this->SetX("5");
        $this->Cell(0, 0, utf8_decode('Sacador/Avalista'), 0, 0, "L");
        $this->SetY("272");
        $this->SetX("148");
        $this->Cell(0, 0, utf8_decode('Autenticação Mecânica - Ficha de Compensação'), 0, 0, "L");
        $this->SetFont('Times', 'B', 18);
        $this->SetY("161");
        $this->SetX("46");
        $this->MultiCell(20, 5, utf8_decode(substr($this->boleto[$i]->getCodigoBancoComDv(), 0, 6)), 0, 'L', false);
        $this->SetFont('Times', 'B', 15);
        $this->SetY("161");
        $this->SetX("0");
        $this->MultiCell(205, 5, utf8_decode(substr($this->boleto[$i]->getLinhaDigitavel(), 0, 58)), 0, 'R', false);
        $this->SetFont('Times', 'B', 11);
        $this->SetY("170");
        $this->SetX("5");
        $this->MultiCell(205, 5, utf8_decode($this->boleto[$i]->getLocalPagamento()), 0, 'L', false);
        $this->SetFont('Times', 'B', 10);
        $this->SetY("170");
        $this->SetX("185");
        $this->MultiCell(110, 5, utf8_decode(substr($this->boleto[$i]->getDataVencimento()->format('d/m/Y'), 0, 10)), 0, 'L', false);
        $this->SetY("181");
        $this->SetX("5");
        $this->MultiCell(162, 5, utf8_decode(substr($this->boleto[$i]->getBeneficiario()->getNome()." - CNPJ:". $this->boleto[$i]->getBeneficiario()->getDocumento(), 0, 80)), 0, 'L', false);
        $this->SetY("181");
        $this->SetX("177");
        $this->MultiCell(110, 5, utf8_decode(substr($this->boleto[$i]->getAgenciaCodigoBeneficiario(), 0, 24)), 0, 'L', false);
        $this->SetY("191");
        $this->SetX("12");
        $this->MultiCell(162, 5, utf8_decode(substr($this->boleto[$i]->getDataDocumento()->format('d/m/Y'), 0, 80)), 0, 'L', false);
        $this->SetY("191");
        $this->SetX("52");
        $this->MultiCell(162, 5, utf8_decode(substr($this->boleto[$i]->getNumeroDocumento(), 0, 80)), 0, 'L', false);
        $this->SetY("191");
        $this->SetX("88");
        $this->MultiCell(162, 5, utf8_decode(substr($this->boleto[$i]->getEspecieDoc(), 0, 80)), 0, 'L', false);
        $this->SetY("191");
        $this->SetX("110");
        $this->MultiCell(162, 5, utf8_decode(substr($this->boleto[$i]->getAceite(), 0, 80)), 0, 'L', false);
        $this->SetY("191");
        $this->SetX("130");
        $this->MultiCell(162, 5, utf8_decode(substr($this->boleto[$i]->getDataDocumento()->format('d/m/Y'), 0, 80)), 0, 'L', false);
        $this->SetY("191");
        $this->SetX("175");
        $this->MultiCell(110, 5, utf8_decode(substr($this->boleto[$i]->getNumeroDocumento(), 0, 17)), 0, 'L', false);
        $this->SetY("200");
        $this->SetX("52");
        $this->MultiCell(162, 5, utf8_decode(substr($this->boleto[$i]->getCarteira(), 0, 80)), 0, 'L', false);
        $this->SetY("200");
        $this->SetX("71");
        $this->MultiCell(162, 5, utf8_decode(substr($this->boleto[$i]->getMoedaExtenso(), 0, 80)), 0, 'L', false);
        $this->SetY("200");
        $this->SetX("96");
        $this->MultiCell(162, 5, utf8_decode(substr($this->boleto[$i]->getParcela(), 0, 80)), 0, 'L', false);
        $this->SetY("200");
        $this->SetX("130");
        $this->MultiCell(162, 5, utf8_decode('R$ ' . substr(number_format($this->boleto[$i]->getValor(), 2, ',', ''), 0, 19)), 0, 'L', false);
        $this->SetY("200");
        $this->SetX("175");
        $this->MultiCell(110, 5, utf8_decode('R$ ' . substr(number_format($this->boleto[$i]->getTotal(), 2, ',', ''), 0, 19)), 0, 'L', false);
        $this->SetY("220");
        $this->SetX("5");
        $this->MultiCell(110, 5, utf8_decode(substr($this->boleto[$i]->getInstrucoes()[0], 0, 300)), 0, 'L', false);
        $this->SetY("252");
        $this->SetX("5");
        $this->MultiCell(205, 5, utf8_decode(substr($this->boleto[$i]->getPagador()->getNome() . ' CPF/CNPJ: ' . $this->boleto[$i]->getPagador()->getDocumento(), 0, 300)), 0, 'L', false);
        $this->SetY("256");
        $this->SetX("5");
        $this->MultiCell(300, 5, utf8_decode(substr($this->boleto[$i]->getPagador()->getEndereco().' - '.$this->boleto[$i]->getPagador()->getBairro(), 0, 300)), 0, 'L', false);
        $this->SetY("260");
        $this->SetX("5");
        $this->MultiCell(300, 5, utf8_decode(substr($this->boleto[$i]->getPagador()->getCidade().' - ' .$this->boleto[$i]->getPagador()->getUf().' - ' .$this->boleto[$i]->getPagador()->getCep() , 0, 300)), 0, 'L', false);
        return $this;
    }

    /**
     * @param string $texto
     * @param integer $ln
     * @param integer $ln2
     * @param $posicaoTexto
     * @param $alinhamentoTexto
     * @param $tamanho
     */
    protected function traco($texto, $ln = null, $ln2 = null, $posicaoTexto = 1, $alinhamentoTexto = 'R', $tamanho = 226)
    {
        if ($ln == 1 || $ln) {
            $this->Ln($ln);
        }
        $this->SetFont($this->PadraoFont, '', $this->fdes);
        if ($texto && $posicaoTexto !== -1) {
            $this->Cell(0, 2, $this->_($texto), 0, 1, $alinhamentoTexto);
        }
        $this->Cell(0,  2, str_pad('', $tamanho, ' ', STR_PAD_RIGHT), 0, 1);
        if ($texto && $posicaoTexto === -1) {
            $this->Cell(0, 2, $this->_($texto), 0, 1, $alinhamentoTexto);
        }
        if ($ln2 == 1 || $ln2) {
            $this->Ln($ln2);
        }
    }

    /**
     * @param integer $i
     */
    protected function codigoBarras($i)
    {
        $this->Ln(9);
        $this->Cell(0, 15, '', 0, 1, 'L');
        $this->i25($this->GetX(), $this->GetY() - 15, $this->boleto[$i]->getCodigoBarras(), 1, 17);
    }

    /**
     * Addiciona o boletos
     *
     * @param array $boletos
     * @param bool $withGroup
     *
     * @return $this
     */
    public function addBoletos(array $boletos, $withGroup = true)
    {
        if ($withGroup) {
            $this->StartPageGroup();
        }

        foreach ($boletos as $boleto) {
            $this->addBoleto($boleto);
        }

        return $this;
    }

    /**
     * Addiciona o boleto
     *
     * @param BoletoContract $boleto
     *
     * @return $this
     */
    public function addBoleto(BoletoContract $boleto)
    {
        $this->totalBoletos += 1;
        $this->boleto[] = $boleto;
        return $this;
    }

    /**
     * @return $this
     */
    public function hideInstrucoes()
    {
        $this->showInstrucoes = false;
        return $this;
    }

    /**
     * @return $this
     */
    public function showPrint()
    {
        $this->print = true;
        return $this;
    }

    /**
     * função para gerar o boleto
     *
     * @param string $dest tipo de destino const BOLETOPDF_DEST_STANDARD | BOLETOPDF_DEST_DOWNLOAD | BOLETOPDF_DEST_SAVE | BOLETOPDF_DEST_STRING
     * @param null $save_path
     *
     * @return string
     * @throws \Exception
     */
    public function gerarBoleto($dest = self::OUTPUT_STANDARD, $save_path = null, $nameFile = null)
    {
        if ($this->totalBoletos == 0) {
            throw new \Exception('Nenhum Boleto adicionado');
        }

        for ($i = 0; $i < $this->totalBoletos; $i++) {
            $this->SetDrawColor('0', '0', '0');
            $this->AddPage();
            $this->instrucoes($i)->logoEmpresa($i)->Topo($i)->Bottom($i)->codigoBarras($i);
        }
        if ($dest == self::OUTPUT_SAVE) {
            $this->Output($save_path, $dest, $this->print);
            return $save_path;
        }
        if ($nameFile == null) {
            $nameFile = Str::random(32);
        }
        
        return $this->Output($nameFile . '.pdf', $dest, $this->print);
    }

    /**
     * @param $lista
     * @param integer $pulaLinha
     *
     * @return int
     */
    protected function listaLinhas($lista, $pulaLinha)
    {
        foreach ($lista as $d) {
            $pulaLinha -= 2;
            $this->MultiCell(0, $this->cell - 0.2, $this->_(preg_replace('/(%)/', '%$1', $d ?? '')), 0, 1);
        }

        return $pulaLinha;
    }
}
