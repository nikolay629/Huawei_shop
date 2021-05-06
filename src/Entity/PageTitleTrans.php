<?php

namespace App\Entity;

use App\Repository\PageTitleTransRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PageTitleTransRepository::class)
 */
class PageTitleTrans
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
    private $titleTrans;

    /**
     * @ORM\ManyToOne(targetEntity="Page", inversedBy="titleTrans")
     */
    private $page;

    /**
     * @ORM\ManyToOne(targetEntity="Language", inversedBy="titleTrans")
     */
    private $language;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitleTrans(): ?string
    {
        return $this->titleTrans;
    }

    public function setTitleTrans(string $titleTrans): self
    {
        $this->titleTrans = $titleTrans;

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

    public function getLanguage(): ?Language
    {
        return $this->language;
    }

    public function setLanguage(?Language $language): self
    {
        $this->language = $language;

        return $this;
    }
}
