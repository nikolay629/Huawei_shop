<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Comment
 *
 * @ORM\Table(name="comment")
 * @ORM\Entity
 */
class Comment
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
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity="Article", inversedBy="comment")
     */
    private $article;

    /**
     * @ORM\ManyToOne(targetEntity="MainArticle", inversedBy="comment")
     */
    private $main;

    /**
     * @var int|null
     *
     * @ORM\Column(name="rating", type="integer", nullable=true)
     */
    private $rating;

    /**
     * @ORM\OneToMany(targetEntity="CommentTranslation", mappedBy="comment")
     */
    private $commentTrans;

    public function __construct()
    {
        $this->commentTrans = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

//    public function getArticle(): ?int
//    {
//        return $this->article;
//    }
//
//    public function setArticle(int $article): self
//    {
//        $this->article = $article;
//
//        return $this;
//    }

    public function getRating(): ?int
    {
        return $this->rating;
    }

    public function setRating(?int $rating): self
    {
        $this->rating = $rating;

        return $this;
    }

//    public function getCommentTrans(): ?CommentTranslation
//    {
//        return $this->commentTrans;
//    }
//
//    public function setCommentTrans(?CommentTranslation $commentTrans): self
//    {
//        $this->commentTrans = $commentTrans;
//
//        return $this;
//    }
//
//    public function addCommentTran(CommentTranslation $commentTran): self
//    {
//        if (!$this->commentTrans->contains($commentTran)) {
//            $this->commentTrans[] = $commentTran;
//            $commentTran->setComment($this);
//        }
//
//        return $this;
//    }
//
//    public function removeCommentTran(CommentTranslation $commentTran): self
//    {
//        if ($this->commentTrans->removeElement($commentTran)) {
//            // set the owning side to null (unless already changed)
//            if ($commentTran->getComment() === $this) {
//                $commentTran->setComment(null);
//            }
//        }
//
//        return $this;
//    }

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
     * @return Collection|CommentTranslation[]
     */
    public function getCommentTrans(): Collection
    {
        return $this->commentTrans;
    }

    public function addCommentTran(CommentTranslation $commentTran): self
    {
        if (!$this->commentTrans->contains($commentTran)) {
            $this->commentTrans[] = $commentTran;
            $commentTran->setComment($this);
        }

        return $this;
    }

    public function removeCommentTran(CommentTranslation $commentTran): self
    {
        if ($this->commentTrans->removeElement($commentTran)) {
            // set the owning side to null (unless already changed)
            if ($commentTran->getComment() === $this) {
                $commentTran->setComment(null);
            }
        }

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


}
