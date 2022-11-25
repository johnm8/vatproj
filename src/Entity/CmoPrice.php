<?php

namespace App\Entity;

use App\Repository\CmoPriceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

use function Symfony\Component\DependencyInjection\Loader\Configurator\ref;

/**
 * @ORM\Entity(repositoryClass=CmoPriceRepository::class)
 */
class CmoPrice
{
    const CURRENCY_GBP = 'gbp';
    const CURRENCY_USD = 'usd';

    const CURRENCY_TYPES = [
        'United States Dollars' => self::CURRENCY_USD,
        'British Pounds' => self::CURRENCY_USD,
    ];

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $cmoPriceId;

    /**
     * @ORM\Column(type="float")
     */
    private $price;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $currency;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\CmoCalculation", mappedBy="cmoPrice")
     */
    private $cmoCalculations;


    public function __construct()
    {
        $this->cmoCalculations = new ArrayCollection();
    }


    public function getCmoPriceId(): ?int
    {
        return $this->cmoPriceId;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getCurrency(): ?string
    {
        return $this->currency;
    }

    public function setCurrency(string $currency): self
    {
        $this->currency = $currency;

        return $this;
    }

    
    public function getCmoCalculations(): Collection
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
