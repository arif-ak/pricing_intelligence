<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProductRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Product
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=500)
     */
    private $productName;

    /**
     * @ORM\Column(type="string", length=500, nullable=true)
     */
    private $productUrl;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $productPrice;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $productMrp;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $productDiscount;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $packingQuantity;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $moq;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $brand;

    /**
     * @ORM\Column(type="string", length=500, nullable=true)
     */
    private $productImageUrl;

    /**
     * @ORM\Column(type="string", length=500, nullable=true)
     */
    private $similarProductsLink;

    /**
     * @ORM\Column(type="string", length=500, nullable=true)
     */
    private $dataSourceUrl;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $createdAt;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProductName(): ?string
    {
        return $this->productName;
    }

    public function setProductName(string $productName): self
    {
        $this->productName = $productName;

        return $this;
    }

    public function getProductUrl(): ?string
    {
        return $this->productUrl;
    }

    public function setProductUrl(?string $productUrl): self
    {
        $this->productUrl = $productUrl;

        return $this;
    }

    public function getProductPrice(): ?float
    {
        return $this->productPrice;
    }

    public function setProductPrice(?float $productPrice): self
    {
        $this->productPrice = $productPrice;

        return $this;
    }

    public function getProductMrp(): ?float
    {
        return $this->productMrp;
    }

    public function setProductMrp(float $productMrp): self
    {
        $this->productMrp = $productMrp;

        return $this;
    }

    public function getProductDiscount(): ?float
    {
        return $this->productDiscount;
    }

    public function setProductDiscount(?float $productDiscount): self
    {
        $this->productDiscount = $productDiscount;

        return $this;
    }

    public function getPackingQuantity(): ?int
    {
        return $this->packingQuantity;
    }

    public function setPackingQuantity(?int $packingQuantity): self
    {
        $this->packingQuantity = $packingQuantity;

        return $this;
    }

    public function getMoq(): ?string
    {
        return $this->moq;
    }

    public function setMoq(?string $moq): self
    {
        $this->moq = $moq;

        return $this;
    }

    public function getBrand(): ?string
    {
        return $this->brand;
    }

    public function setBrand(?string $brand): self
    {
        $this->brand = $brand;

        return $this;
    }

    public function getProductImageUrl(): ?string
    {
        return $this->productImageUrl;
    }

    public function setProductImageUrl(?string $productImageUrl): self
    {
        $this->productImageUrl = $productImageUrl;

        return $this;
    }

    public function getSimilarProductsLink(): ?string
    {
        return $this->similarProductsLink;
    }

    public function setSimilarProductsLink(?string $similarProductsLink): self
    {
        $this->similarProductsLink = $similarProductsLink;

        return $this;
    }

    public function getDataSourceUrl(): ?string
    {
        return $this->dataSourceUrl;
    }

    public function setDataSourceUrl(?string $dataSourceUrl): self
    {
        $this->dataSourceUrl = $dataSourceUrl;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @ORM\PrePersist
     */
    public function setCreatedAtValue()
    {
        $this->createdAt = new \DateTime();
    }
}
