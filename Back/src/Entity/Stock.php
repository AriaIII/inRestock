<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\StockRepository")
 */
class Stock
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
    private $stock;

    /**
     * @ORM\Column(type="integer")
     */
    private $stock_alert;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Product", inversedBy="stock", cascade={"persist", "remove"})
     */
    private $product;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\HistoriqueStock", mappedBy="stock")
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

    public function getStock(): ?int
    {
        return $this->stock;
    }

    public function setStock(int $stock): self
    {
        $this->stock = $stock;

        return $this;
    }

    public function getStockAlert(): ?int
    {
        return $this->stock_alert;
    }

    public function setStockAlert(int $stock_alert): self
    {
        $this->stock_alert = $stock_alert;

        return $this;
    }

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): self
    {
        $this->product = $product;

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
            $historiqueStock->setStock($this);
        }

        return $this;
    }

    public function removeHistoriqueStock(HistoriqueStock $historiqueStock): self
    {
        if ($this->historiqueStocks->contains($historiqueStock)) {
            $this->historiqueStocks->removeElement($historiqueStock);
            // set the owning side to null (unless already changed)
            if ($historiqueStock->getStock() === $this) {
                $historiqueStock->setStock(null);
            }
        }

        return $this;
    }

    public function __toString(){
        return $this->product->getName();
    }
}
