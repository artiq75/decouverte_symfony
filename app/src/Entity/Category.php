<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CategoryRepository::class)]
class Category
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\OneToMany(mappedBy: 'category', targetEntity: Housing::class, orphanRemoval: true)]
    private Collection $housing;

    public function __construct()
    {
        $this->housing = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, Housing>
     */
    public function getHousing(): Collection
    {
        return $this->housing;
    }

    public function addHousing(Housing $housing): self
    {
        if (!$this->housing->contains($housing)) {
            $this->housing->add($housing);
            $housing->setCategory($this);
        }

        return $this;
    }

    public function removeHousing(Housing $housing): self
    {
        if ($this->housing->removeElement($housing)) {
            // set the owning side to null (unless already changed)
            if ($housing->getCategory() === $this) {
                $housing->setCategory(null);
            }
        }

        return $this;
    }
}
