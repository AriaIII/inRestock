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
     * @ORM\OneToOne(targetEntity="App\Entity\Product", inversedBy="stock", cascade={"persist"})
     */
    private $product;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $packaging;

    public function __construct()
    {
        
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

    public function __toString(){
        return $this->product->getName();
    }

    public function getPackaging(): ?string
    {
        return $this->packaging;
    }

    public function setPackaging(?string $packaging): self
    {
        $this->packaging = $packaging;

        return $this;
    }
}
