<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\IssueRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=IssueRepository::class)
 */
class Issue
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"maintenance"})
     * @Groups({"issue"})
     * 
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"product"})
     * @Groups({"maintenance"})
     * @Groups({"issue"})
     * 
     */
    private $issue;

    /**
     * @ORM\OneToMany(targetEntity=Maintenance::class, mappedBy="Issue")
     */
    private $maintenances;

    public function __construct()
    {
        $this->maintenances = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIssue(): ?string
    {
        return $this->issue;
    }

    public function setIssue(string $issue): self
    {
        $this->issue = $issue;

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
            $maintenance->setIssue($this);
        }

        return $this;
    }

    public function removeMaintenance(Maintenance $maintenance): self
    {
        if ($this->maintenances->removeElement($maintenance)) {
            // set the owning side to null (unless already changed)
            if ($maintenance->getIssue() === $this) {
                $maintenance->setIssue(null);
            }
        }

        return $this;
    }
}
