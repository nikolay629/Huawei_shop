<?php

namespace App\Entity;

use App\Repository\PageTransRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PageTransRepository::class)
 */
class PageTrans
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $smallTitle;

    /**
     * @ORM\Column(type="text", length=255)
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity="PageList", inversedBy="pageTrans")
     */
    private $pageList;


    /**
     * @ORM\ManyToOne(targetEntity="Language", inversedBy="pageTrans")
     */
    private $locale;

    public function __construct()
    {
        $this->photo = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPage(): ?Page
    {
        return $this->page;
    }

    public function setPage(?Page $page): self
    {
        $this->page = $page;

        return $this;
    }

    public function getLocale(): ?Language
    {
        return $this->locale;
    }

    public function setLocale(?Language $locale): self
    {
        $this->locale = $locale;

        return $this;
    }

    public function getSmallTitle(): ?string
    {
        return $this->smallTitle;
    }

    public function setSmallTitle(string $smallTitle): self
    {
        $this->smallTitle = $smallTitle;

        return $this;
    }

    /**
     * @return Collection|Photo[]
     */
    public function getPhoto(): Collection
    {
        return $this->photo;
    }

    public function addPhoto(Photo $photo): self
    {
        if (!$this->photo->contains($photo)) {
            $this->photo[] = $photo;
            $photo->setPage($this);
        }

        return $this;
    }

    public function removePhoto(Photo $photo): self
    {
        if ($this->photo->removeElement($photo)) {
            // set the owning side to null (unless already changed)
            if ($photo->getPage() === $this) {
                $photo->setPage(null);
            }
        }

        return $this;
    }

    public function getPageList(): ?PageList
    {
        return $this->pageList;
    }

    public function setPageList(?PageList $pageList): self
    {
        $this->pageList = $pageList;

        return $this;
    }
}
