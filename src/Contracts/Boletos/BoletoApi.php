<?php

namespace Rbtecnet\Phpboleto\Contracts\Boletos;

use Rbtecnet\Phpboleto\Boleto\AbstractBoleto;
Interface BoletoApi extends Boleto
{
    /**
     * Return boleto as a Array.
     *
     * @return array
     */
    public function toAPI();

    /**
     * @param $boleto
     * @param $appends
     *
     * @return AbstractBoleto
     */
    public static function fromAPI($boleto, $appends);

}