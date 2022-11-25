<?php

namespace App\Entity;

use App\Repository\CmoVatRateRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CmoVatRateRepository::class)
 */
class CmoVatRate
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $cmoVatRateId;

    /**
     * @ORM\Column(type="integer")
     */
    private $rate;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\CmoCalculation", mappedBy="cmoVatRate")
     */
    private $cmoCalculations;


    public function __construct()
    {
        $this->cmoCalculations = new ArrayCollection();
    }


    public function getCmoVatRateId(): ?int
    {
        return $this->cmoVatRateId;
    }

    public function getRate(): ?int
    {
        return $this->rate;
    }

    public function setRate(int $rate): self
    {
        $this->rate = $rate;

        return $this;
    }

    
    public function getCmoCalculations()
    {
        return $this->cmoCalculations;
    }

    public function addCmoCalculation(?CmoCalculation $calc = null) 
    {
        if (is_null($calc) || $this->cmoCalculations->contains($calc)) {
            return;
        }

        $this->cmoCalculations->add($calc);
        return $this;
    }

    public function removeCmoCalculation(CmoCalculation $calc)
    {
        $this->cmoCalculations->removeElement($calc);
    }
}
