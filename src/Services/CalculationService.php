<?php

namespace App\Services;

use App\Entity\CmoPrice;
use App\Entity\CmoVatRate;

class CalculationService 
{

    public function calculate(CmoPrice $cmoPrice, CmoVatRate $cmoVatRate)
    {
      $excVatRate = $cmoPrice->getPrice() + ($cmoPrice->getPrice() * ($cmoVatRate->getRate()/ 100));
      $incVatRate = $cmoPrice->getPrice() - ($cmoPrice->getPrice() * ($cmoVatRate->getRate()/ 100));

      return [
        'excVatRate' => $excVatRate,
        'incVatRate' => $incVatRate,
      ];
    }
}
