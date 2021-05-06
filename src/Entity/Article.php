<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Article
 *
 * @ORM\Table(name="article")
 * @ORM\Entity
 */
class Article
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
     * @ORM\Column(name="slug", type="string", length=255, nullable=false)
     */
    private $slug;

    /**
     * @var int
     *
     * @ORM\Column(name="quantity", type="integer", nullable=false)
     */
    private $quantity;

    /**
     * @var float
     *
     * @ORM\Column(name="price", type="float", precision=10, scale=0, nullable=false)
     */
    private $price;

    /**
     * @ORM\OneToMany(targetEntity="ArticleTranslation", mappedBy="article")
     */
    private $articleTrans;

    /**
     * @ORM\OneToMany (targetEntity="Photo", mappedBy="article")
     */
    private $photo;

    /**
     * @ORM\OneToMany(targetEntity="Comment", mappedBy="main")
     */
    private $comment;

    public function __construct()
    {
        $this->articleTrans = new ArrayCollection();
        $this->photo = new ArrayCollection();
        $this->comment = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    /**
     * @return Collection|ArticleTranslation[]
     */
    public function getArticleTrans(): Collection
    {
        return $this->articleTrans;
    }

    public function addArticleTran(ArticleTranslation $articleTran): self
    {
        if (!$this->articleTrans->contains($articleTran)) {
            $this->articleTrans[] = $articleTran;
            $articleTran->setArticle($this);
        }

        return $this;
    }

    public function removeArticleTran(ArticleTranslation $articleTran): self
    {
        if ($this->articleTrans->removeElement($articleTran)) {
            // set the owning side to null (unless already changed)
            if ($articleTran->getArticle() === $this) {
                $articleTran->setArticle(null);
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
            $photo->setArticle($this);
        }

        return $this;
    }

    public function removePhoto(Photo $photo): self
    {
        if ($this->photo->removeElement($photo)) {
            // set the owning side to null (unless already changed)
            if ($photo->getArticle() === $this) {
                $photo->setArticle(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Comment[]
     */
    public function getComment(): Collection
    {
        return $this->comment;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comment->contains($comment)) {
            $this->comment[] = $comment;
            $comment->setMain($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comment->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getMain() === $this) {
                $comment->setMain(null);
            }
        }

        return $this;
    }


}
