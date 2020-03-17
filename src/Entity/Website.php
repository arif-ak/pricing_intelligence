<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\WebsiteRepository")
 */
class Website
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
    private $websiteName;

    /**
     * @ORM\Column(type="string", length=500, nullable=true)
     */
    private $websiteUrl;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Categories", mappedBy="website")
     */
    private $categories;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Brands", mappedBy="website")
     */
    private $brands;

    public function __construct()
    {
        $this->categories = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getWebsiteName(): ?string
    {
        return $this->websiteName;
    }

    public function setWebsiteName(string $websiteName): self
    {
        $this->websiteName = $websiteName;

        return $this;
    }

    public function getWebsiteUrl(): ?string
    {
        return $this->websiteUrl;
    }

    public function setWebsiteUrl(?string $websiteUrl): self
    {
        $this->websiteUrl = $websiteUrl;

        return $this;
    }

    /**
     * @return Collection|Categories[]
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategory(Categories $category): self
    {
        if (!$this->categories->contains($category)) {
            $this->categories[] = $category;
            $category->setWebsite($this);
        }

        return $this;
    }

    public function removeCategory(Categories $category): self
    {
        if ($this->categories->contains($category)) {
            $this->categories->removeElement($category);
            // set the owning side to null (unless already changed)
            if ($category->getWebsite() === $this) {
                $category->setWebsite(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Brands[]
     */
    public function getBrands(): Collection
    {
        return $this->brands;
    }

    public function addBrand(Brands $brands): self
    {
        if (!$this->brands->contains($brands)) {
            $this->brands[] = $brands;
            $brands->setWebsite($this);
        }

        return $this;
    }

    public function removeBrand(Brands $brands): self
    {
        if ($this->brands->contains($brands)) {
            $this->brands->removeElement($brands);
            // set the owning side to null (unless already changed)
            if ($brands->getWebsite() === $this) {
                $brands->setWebsite(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->websiteName;
    }
}
