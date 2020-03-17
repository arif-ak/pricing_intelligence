<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\BrandsRepository")
 */
class Brands
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $brand_name;

    /**
     * @ORM\Column(type="string", length=500, nullable=true)
     */
    private $brand_url;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Website", inversedBy="brands")
     */
    private $website;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBrandName(): ?string
    {
        return $this->brand_name;
    }

    public function setBrandName(string $brand_name): self
    {
        $this->brand_name = $brand_name;

        return $this;
    }

    public function getBrandUrl(): ?string
    {
        return $this->brand_url;
    }

    public function setBrandUrl(?string $brand_url): self
    {
        $this->brand_url = $brand_url;

        return $this;
    }

    public function getWebsite(): ?Website
    {
        return $this->website;
    }

    public function setWebsite(?Website $website): self
    {
        $this->website = $website;

        return $this;
    }

    public function __toString()
    {
        return $this->brand_name;
    }
}
