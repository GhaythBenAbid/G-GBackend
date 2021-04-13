<?php

namespace App\Entity;

use App\Repository\ValidationRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ValidationRepository::class)
 */
class Validation
{
    

    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity=CommercialAgent::class, inversedBy="validations")
     * @ORM\JoinColumn(nullable=false)
     */
    private $CommercialAgent;

    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity=Product::class, inversedBy="validations")
     * @ORM\JoinColumn(nullable=false)
     */
    private $Product;


    public function getCommercialAgent(): ?CommercialAgent
    {
        return $this->CommercialAgent;
    }

    public function setCommercialAgent(?CommercialAgent $CommercialAgent): self
    {
        $this->CommercialAgent = $CommercialAgent;

        return $this;
    }

    public function getProduct(): ?Product
    {
        return $this->Product;
    }

    public function setProduct(?Product $Product): self
    {
        $this->Product = $Product;

        return $this;
    }
}
