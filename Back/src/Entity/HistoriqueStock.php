<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\HistoriqueStockRepository")
 */
class HistoriqueStock
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $variation;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Stock", inversedBy="historiqueStocks")
     */
    private $stock;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Modification", inversedBy="historiqueStocks")
     */
    private $modification;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="historiqueStocks")
     */
    private $user;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getVariation(): ?int
    {
        return $this->variation;
    }

    public function setVariation(int $variation): self
    {
        $this->variation = $variation;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getStock(): ?stock
    {
        return $this->stock;
    }

    public function setStock(?stock $stock): self
    {
        $this->stock = $stock;

        return $this;
    }

    public function getModification(): ?Modification
    {
        return $this->modification;
    }

    public function setModification(?Modification $modification): self
    {
        $this->modification = $modification;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }
}
