<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\MaintenanceRepository;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=MaintenanceRepository::class)
 */
class Maintenance
{

    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity=Product::class, inversedBy="maintenances")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"maintenance"})
     */
    private $Product;

    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity=Technician::class, inversedBy="maintenances")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"maintenance"})
     */
    private $Technician;

    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity=Issue::class, inversedBy="maintenances")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"maintenance"})
     * 
     */
    private $Issue;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"maintenance"})
     * @Groups({"maint"})
     */
    private $RepairDate;

    /**
     * @ORM\Column(type="float")
     * @Groups({"maintenance"})
     * @Groups({"maint"})
     */
    private $ExpectedMaintenanceCost;


    public function getProduct(): ?Product
    {
        return $this->Product;
    }

    public function setProduct(?Product $Product): self
    {
        $this->Product = $Product;

        return $this;
    }

    public function getTechnician(): ?Technician
    {
        return $this->Technician;
    }

    public function setTechnician(?Technician $Technician): self
    {
        $this->Technician = $Technician;

        return $this;
    }

    public function getIssue(): ?Issue
    {
        return $this->Issue;
    }

    public function setIssue(?Issue $Issue): self
    {
        $this->Issue = $Issue;

        return $this;
    }

    public function getRepairDate()
    {
        return $this->RepairDate->format('Y-n-d');
    }

    public function setRepairDate(\DateTimeInterface $RepairDate): self
    {
        $this->RepairDate = $RepairDate;

        return $this;
    }

    public function getExpectedMaintenanceCost(): ?float
    {
        return $this->ExpectedMaintenanceCost;
    }

    public function setExpectedMaintenanceCost(float $ExpectedMaintenanceCost): self
    {
        $this->ExpectedMaintenanceCost = $ExpectedMaintenanceCost;

        return $this;
    }
}
