<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\ProductRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity(repositoryClass=ProductRepository::class)
 */
class Product
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"product"})
     * @Groups({"maintenance"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"product"})
     * @Groups({"maintenance"})
     * @Assert\NotBlank
     */
    private $ProductName;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"product"})
     * @Groups({"maintenance"})
     * @Assert\NotBlank
     */
    private $ProductImage;

    /**
     * @ORM\Column(type="text", length=255)
     * @Groups({"product"})
     * @Groups({"maintenance"})
     * 
     * @Assert\NotBlank
     */
    private $Description;

    /**
     * @ORM\Column(type="float")
     * @Groups({"product"})
     * @Groups({"maintenance"})
     * 
     * @Assert\NotBlank
     */
    private $initialPrice;

    /**
     * @ORM\Column(type="float" , nullable=true)
     * @Groups({"product"})
     * @Groups({"maintenance"})
     * 
     */
    private $SellingPrice;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"product"})
     * @Groups({"maintenance"})
     * 
     * @Assert\NotBlank
     */
    private $State;

    /**
     * @ORM\ManyToOne(targetEntity=Owner::class, inversedBy="products" )
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"product"})
     * @Groups({"maintenance"})
     * 
     * 
     */
    private $Owner;

    /**
     * @ORM\OneToOne(targetEntity=Client::class, inversedBy="product", cascade={"persist", "remove"})
     * 
     */
    private $Client;

    /**
     * @ORM\OneToMany(targetEntity=Validation::class, mappedBy="Product")
     * 
     */
    private $validations;

    /**
     * @ORM\OneToMany(targetEntity=Maintenance::class, mappedBy="Product")
     * @Groups({"product"})
     */
    private $maintenances;

    /**
     * @ORM\ManyToOne(targetEntity=Category::class, inversedBy="products")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"product"})
     */
    private $Category;

    public function __construct()
    {
        $this->validations = new ArrayCollection();
        $this->maintenances = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProductName(): ?string
    {
        return $this->ProductName;
    }

    public function setProductName(string $ProductName): self
    {
        $this->ProductName = $ProductName;

        return $this;
    }

    public function getProductImage(): ?string
    {
        return $this->ProductImage;
    }

    public function setProductImage(string $ProductImage): self
    {
        $this->ProductImage = $ProductImage;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->Description;
    }

    public function setDescription(string $Description): self
    {
        $this->Description = $Description;

        return $this;
    }

    public function getInitialPrice(): ?float
    {
        return $this->initialPrice;
    }

    public function setInitialPrice(float $initialPrice): self
    {
        $this->initialPrice = $initialPrice;

        return $this;
    }

    public function getSellingPrice(): ?float
    {
        return $this->SellingPrice;
    }

    public function setSellingPrice(float $SellingPrice): self
    {
        $this->SellingPrice = $SellingPrice;

        return $this;
    }

    public function getState(): ?string
    {
        return $this->State;
    }

    public function setState(string $State): self
    {
        $this->State = $State;

        return $this;
    }

    public function getOwner(): ?Owner
    {
        return $this->Owner;
    }

    public function setOwner(?Owner $Owner): self
    {
        $this->Owner = $Owner;

        return $this;
    }

    public function getClient(): ?Client
    {
        return $this->Client;
    }

    public function setClient(?Client $Client): self
    {
        $this->Client = $Client;

        return $this;
    }

    /**
     * @return Collection|Validation[]
     */
    public function getValidations(): Collection
    {
        return $this->validations;
    }

    public function addValidation(Validation $validation): self
    {
        if (!$this->validations->contains($validation)) {
            $this->validations[] = $validation;
            $validation->setProduct($this);
        }

        return $this;
    }

    public function removeValidation(Validation $validation): self
    {
        if ($this->validations->removeElement($validation)) {
            // set the owning side to null (unless already changed)
            if ($validation->getProduct() === $this) {
                $validation->setProduct(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Maintenance[]
     */
    public function getMaintenances(): Collection
    {
        return $this->maintenances;
    }

    public function addMaintenance(Maintenance $maintenance): self
    {
        if (!$this->maintenances->contains($maintenance)) {
            $this->maintenances[] = $maintenance;
            $maintenance->setProduct($this);
        }

        return $this;
    }

    public function removeMaintenance(Maintenance $maintenance): self
    {
        if ($this->maintenances->removeElement($maintenance)) {
            // set the owning side to null (unless already changed)
            if ($maintenance->getProduct() === $this) {
                $maintenance->setProduct(null);
            }
        }

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->Category;
    }

    public function setCategory(?Category $Category): self
    {
        $this->Category = $Category;

        return $this;
    }
}
