<?php

namespace App\Entity;

use App\Repository\PageRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PageRepository::class)
 */
class Page
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
    private $title;

    /**
     * @ORM\ManyToOne(targetEntity="MainArticle", inversedBy="page")
     */
    private $main;



    /**
     * @ORM\OneToMany(targetEntity="PageTitleTrans", mappedBy="page")
     */
    private $titleTrans;

    /**
     * @ORM\OneToMany(targetEntity="PageList", mappedBy="page")
     */
    private $pageList;


    public function __construct()
    {
        $this->pageTrans = new ArrayCollection();
        $this->titleTrans = new ArrayCollection();
        $this->pageList = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getMain(): ?MainArticle
    {
        return $this->main;
    }

    public function setMain(?MainArticle $main): self
    {
        $this->main = $main;

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
            $pageTran->setPage($this);
        }

        return $this;
    }

    public function removePageTran(PageTrans $pageTran): self
    {
        if ($this->pageTrans->removeElement($pageTran)) {
            // set the owning side to null (unless already changed)
            if ($pageTran->getPage() === $this) {
                $pageTran->setPage(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|PageTitleTrans[]
     */
    public function getTitleTrans(): Collection
    {
        return $this->titleTrans;
    }

    public function addTitleTran(PageTitleTrans $titleTran): self
    {
        if (!$this->titleTrans->contains($titleTran)) {
            $this->titleTrans[] = $titleTran;
            $titleTran->setPage($this);
        }

        return $this;
    }

    public function removeTitleTran(PageTitleTrans $titleTran): self
    {
        if ($this->titleTrans->removeElement($titleTran)) {
            // set the owning side to null (unless already changed)
            if ($titleTran->getPage() === $this) {
                $titleTran->setPage(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|PageList[]
     */
    public function getPageList(): Collection
    {
        return $this->pageList;
    }

    public function addPageList(PageList $pageList): self
    {
        if (!$this->pageList->contains($pageList)) {
            $this->pageList[] = $pageList;
            $pageList->setPage($this);
        }

        return $this;
    }

    public function removePageList(PageList $pageList): self
    {
        if ($this->pageList->removeElement($pageList)) {
            // set the owning side to null (unless already changed)
            if ($pageList->getPage() === $this) {
                $pageList->setPage(null);
            }
        }

        return $this;
    }
}

