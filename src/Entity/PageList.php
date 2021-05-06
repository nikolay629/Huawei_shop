<?php

namespace App\Entity;

use App\Repository\PageListRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PageListRepository::class)
 */
class PageList
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
    private $subpage;

    /**
     * @ORM\ManyToOne(targetEntity="Page", inversedBy="pageList")
     */
    private $page;

    /**
     * @ORM\OneToMany(targetEntity="PageTrans", mappedBy="pageList")
     */
    private $pageTrans;

    /**
     * @ORM\OneToMany(targetEntity="Photo", mappedBy="pageList")
     */
    private $photo;

    public function __construct()
    {
        $this->pageTrans = new ArrayCollection();
        $this->photo = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSubpage(): ?string
    {
        return $this->subpage;
    }

    public function setSubpage(string $subpage): self
    {
        $this->subpage = $subpage;

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

    /**
     * @return Collection|PageTrans[]
     */
    public function getPageTrans(): Collection
    {
        return $this->pageTrans;
    }

    public function addPageTran(PageTrans $pageTran): self
    {
        if (!$this->pageTrans->contains($pageTran)) {
            $this->pageTrans[] = $pageTran;
            $pageTran->setPageList($this);
        }

        return $this;
    }

    public function removePageTran(PageTrans $pageTran): self
    {
        if ($this->pageTrans->removeElement($pageTran)) {
            // set the owning side to null (unless already changed)
            if ($pageTran->getPageList() === $this) {
                $pageTran->setPageList(null);
            }
        }

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
            $photo->setPageList($this);
        }

        return $this;
    }

    public function removePhoto(Photo $photo): self
    {
        if ($this->photo->removeElement($photo)) {
            // set the owning side to null (unless already changed)
            if ($photo->getPageList() === $this) {
                $photo->setPageList(null);
            }
        }

        return $this;
    }
}
