<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ModificationRepository")
 */
class Modification
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\HistoriqueStock", mappedBy="modification")
     */
    private $historiqueStocks;

    public function __construct()
    {
        $this->historiqueStocks = new ArrayCollection();
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
     * @return Collection|HistoriqueStock[]
     */
    public function getHistoriqueStocks(): Collection
    {
        return $this->historiqueStocks;
    }

    public function addHistoriqueStock(HistoriqueStock $historiqueStock): self
    {
        if (!$this->historiqueStocks->contains($historiqueStock)) {
            $this->historiqueStocks[] = $historiqueStock;
            $historiqueStock->setModification($this);
        }

        return $this;
    }

    public function removeHistoriqueStock(HistoriqueStock $historiqueStock): self
    {
        if ($this->historiqueStocks->contains($historiqueStock)) {
            $this->historiqueStocks->removeElement($historiqueStock);
            // set the owning side to null (unless already changed)
            if ($historiqueStock->getModification() === $this) {
                $historiqueStock->setModification(null);
            }
        }

        return $this;
    }

    public function __toString(){
        return $this->name;
    }
}
