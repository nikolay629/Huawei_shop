<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CartItem
 *
 * @ORM\Table(name="cart_item")
 * @ORM\Entity
 */
class CartItem
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
     * @var int
     *
     * @ORM\Column(name="quantity", type="integer", nullable=false)
     */
    private $quantity;

    /**
     * @var \Cart
     *
     * @ORM\ManyToOne(targetEntity="Cart")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="cart_id", referencedColumnName="id")
     * })
     */
    private $cart;

    /**
     * @ORM\ManyToOne(targetEntity="MainArticle", inversedBy="cartItem")
     */
    private $main;

    /**
     * @ORM\ManyToOne(targetEntity="ArticleTranslation", inversedBy="cartItem")
     */
    private $articleTrans;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getCart(): ?Cart
    {
        return $this->cart;
    }

    public function setCart(?Cart $cart): self
    {
        $this->cart = $cart;

        return $this;
    }


    public function getArticleTrans(): ?ArticleTranslation
    {
        return $this->articleTrans;
    }

    public function setArticleTrans(?ArticleTranslation $articleTrans): self
    {
        $this->articleTrans = $articleTrans;

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



}
