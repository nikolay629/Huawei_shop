<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Photo
 *
 * @ORM\Table(name="photo")
 * @ORM\Entity
 */
class Photo
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
     * @ORM\Column(name="file", type="string", length=255, nullable=false)
     */
    private $file;

    /**
     * @ORM\ManyToOne(targetEntity="Article", inversedBy="photo")
     */
    private $article;

    /**
     * @ORM\ManyToOne(targetEntity="PageList", inversedBy="photo")
     */
    private $pageList;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFile(): ?string
    {
        return $this->file;
    }

    public function setFile(string $file): self
    {
        $this->file = $file;

        return $this;
    }

    public function getArticle(): ?Article
    {
        return $this->article;
    }

    public function setArticle(?Article $article): self
    {
        $this->article = $article;

        return $this;
    }

    public function getPageTrans(): ?PageTrans
    {
        return $this->pageTrans;
    }

    public function setPageTrans(?PageTrans $pageTrans): self
    {
        $this->pageTrans = $pageTrans;

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
