<?php

namespace App\Entity;

use App\Repository\CmoCalculationRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CmoCalculationRepository::class)
 */
class CmoCalculation
{
   /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $cmoCalculationId;

   /**
    * @ORM\JoinColumn(name="cmo_price_id",referencedColumnName="cmo_price_id")
     * @ORM\ManyToOne(targetEntity="App\Entity\CmoPrice", inversedBy="cmoCalculations")
     */
    private $cmoPrice;

    /**
    * @ORM\JoinColumn(name="cmo_vat_rate_id",referencedColumnName="cmo_vat_rate_id")
         * @ORM\ManyToOne(targetEntity="App\Entity\CmoVatRate", inversedBy="cmoCalculations")

     */
    private $cmoVatRate;

    /**
     * @ORM\Column(type="float")
     */
    private $priceExcVat;

    /**
     * @ORM\Column(type="float")
     */
    private $priceIncVat;

   

    public function getCmoCalculationId(): ?int
    {
        return $this->cmoCalculationId;
    }

    public function getPriceExcVat(): ?float
    {
        return $this->priceExcVat;
    }

    public function setPriceExcVat(float $priceExcVat): self
    {
        $this->priceExcVat = $priceExcVat;

        return $this;
    }

    public function getPriceIncVat(): ?float
    {
        return $this->priceIncVat;
    }

    public function setPriceIncVat(float $priceIncVat): self
    {
        $this->priceIncVat = $priceIncVat;

        return $this;
    }

    public function getCmoPrice()
    {
        return $this->cmoPrice;
    }

    public function setCmoPrice($cmoPrice)
    {
        $this->cmoPrice = $cmoPrice;
    }

    public function getCmoVatRate()
    {
        return $this->cmoVatRate;
    }

    public function setCmoVatRate($cmoVatRate)
    {
        $this->cmoVatRate = $cmoVatRate;
    }
}
