<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CommentTranslation
 *
 * @ORM\Table(name="comment_translation", indexes={@ORM\Index(name="IDX_8F85842D82F1BAF4", columns={"language_id"})})
 * @ORM\Entity
 */
class CommentTranslation
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
     * @ORM\Column(name="comment", type="string", length=255, nullable=false)
     */
    private $commentTrans;

    /**
     * @ORM\ManyToOne(targetEntity="Comment", inversedBy="commentTrans")
     */
    private $comment;

    /**
     * @var \Language
     *
     * @ORM\ManyToOne(targetEntity="Language")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="language_id", referencedColumnName="id")
     * })
     */
    private $language;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCommentTrans(): ?string
    {
        return $this->commentTrans;
    }

    public function setCommentTrans(string $commentTrans): self
    {
        $this->commentTrans = $commentTrans;

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

    public function getComment(): ?Comment
    {
        return $this->comment;
    }

    public function setComment(?Comment $comment): self
    {
        $this->comment = $comment;

        return $this;
    }


}
