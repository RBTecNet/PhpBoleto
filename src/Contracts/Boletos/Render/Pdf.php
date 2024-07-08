<?php


namespace Rbtecnet\Phpboleto\Contracts\Boletos\Render;


Interface Pdf
{
    public function gerarBoleto($dest = self::OUTPUT_STANDARD, $save_path = null);
}