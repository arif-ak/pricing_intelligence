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
    private $brandName;

    /**
     * @ORM\Column(type="string", length=500, nullable=true)
     */
    private $brandUrl;

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
        return $this->brandName;
    }

    public function setBrandName(string $brandName): self
    {
        $this->brandName = $brandName;

        return $this;
    }

    public function getBrandUrl(): ?string
    {
        return $this->brandUrl;
    }

    public function setBrandUrl(?string $brandUrl): self
    {
        $this->brandUrl = $brandUrl;

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
        return $this->brandName;
    }
}
