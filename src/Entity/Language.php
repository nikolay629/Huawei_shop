<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Language
 *
 * @ORM\Table(name="language")
 * @ORM\Entity
 */
class Language
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="locale", type="string", length=255, nullable=false)
     */
    private $locale;

    /**
     * @var string
     *
     * @ORM\Column(name="language", type="string", length=255, nullable=false)
     */
    private $language;

    /**
     * @ORM\OneToMany (targetEntity="PageTitleTrans", mappedBy="language")
     */
    private $titleTrans;


    /**
     * @ORM\OneToMany (targetEntity="PageTrans", mappedBy="locale")
     */
    private $pageTrans;

    /**
     * @var string
     *
     * @ORM\Column(name="photo", type="string", length=255, nullable=false)
     */
    private $photo;

    public function __construct()
    {
        $this->pageTrans = new ArrayCollection();
        $this->titleTrans = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLocale(): ?string
    {
        return $this->locale;
    }

    public function setLocale(string $locale): self
    {
        $this->locale = $locale;

        return $this;
    }

    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    public function setPhoto(string $photo): self
    {
        $this->photo = $photo;

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
            $pageTran->setLocale($this);
        }

        return $this;
    }

    public function removePageTran(PageTrans $pageTran): self
    {
        if ($this->pageTrans->removeElement($pageTran)) {
            // set the owning side to null (unless already changed)
            if ($pageTran->getLocale() === $this) {
                $pageTran->setLocale(null);
            }
        }

        return $this;
    }

    public function getLanguage(): ?string
    {
        return $this->language;
    }

    public function setLanguage(string $language): self
    {
        $this->language = $language;

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
            $titleTran->setLanguage($this);
        }

        return $this;
    }

    public function removeTitleTran(PageTitleTrans $titleTran): self
    {
        if ($this->titleTrans->removeElement($titleTran)) {
            // set the owning side to null (unless already changed)
            if ($titleTran->getLanguage() === $this) {
                $titleTran->setLanguage(null);
            }
        }

        return $this;
    }


}
