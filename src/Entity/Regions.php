<?php

namespace App\Entity;

use App\Repository\RegionsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RegionsRepository::class)]
class Regions
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $name = null;

    #[ORM\Column(length: 2)]
    private ?string $code = null;

    /**
     * @var Collection<int, Departments>
     */
    #[ORM\OneToMany(targetEntity: Departments::class, mappedBy: 'region', orphanRemoval: true)]
    private Collection $departments;

    public function __construct()
    {
        $this->departments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): static
    {
        $this->code = $code;

        return $this;
    }

    /**
     * @return Collection<int, Departments>
     */
    public function getDepartments(): Collection
    {
        return $this->departments;
    }

    public function addDepartment(Departments $department): static
    {
        if (!$this->departments->contains($department)) {
            $this->departments->add($department);
            $department->setRegion($this);
        }

        return $this;
    }

    public function removeDepartment(Departments $department): static
    {
        if ($this->departments->removeElement($department)) {
            if ($department->getRegion() === $this) {
                $department->setRegion(null);
            }
        }

        return $this;
    }
}
