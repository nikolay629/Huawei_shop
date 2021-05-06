<?php

namespace App\Controller;

use App\Entity\Cart;
use App\Entity\CartItem;
use App\Entity\Language;
use App\Entity\Photo;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends HomeController
{
    /**
     * @Route("/cart", name="cart")
     */
    public function index(Request $request): Response
    {
        $cartId = $request->get("cartId");
        $locale = $request->get('locale');

        if($cartId == null){
            return $this->redirectToRoute('home',[
               'locale' => $locale
            ]);
        }

        $language = $this->getDoctrine()
            ->getRepository(Language::class)
            ->findOneBy([
                'locale' => $locale
            ]);
        $languageId = $language->getId();
        $cart = $this->getDoctrine()
            ->getRepository(Cart::class)
            ->find($cartId);

        $cartItems = $this->getDoctrine()
            ->getRepository(CartItem::class)
            ->findBy([
               'cart' => $cartId
            ]);

        $languages = $this->getDoctrine()
            ->getRepository(Language::class)
            ->findAll();

        $photos = $this->getDoctrine()
            ->getRepository(Photo::class)
            ->findBy([
                'pageList' => null
            ]);

        $homeTitle = HomeController::getPageTitle($languageId, 'Home');
        $reviewTitle = HomeController::getPageTitle($languageId, 'Review');
        $designTitle = HomeController::getPageTitle($languageId, 'Design');
        $photographyTitle = HomeController::getPageTitle($languageId, 'Photography');
        $videographyTitle = HomeController::getPageTitle($languageId, 'Videography');
        $batteryTitle = HomeController::getPageTitle($languageId, 'Battery and Charging');
        $galleryTitle = HomeController::getPageTitle($languageId, 'Gallery');
        $productTitle = HomeController::getPageTitle($languageId, 'Products');
        $commentTitle = HomeController::getPageTitle($languageId, 'Comments');
        $contactTitle = HomeController::getPageTitle($languageId, 'Contact');
        $languageTitle = HomeController::getPageTitle($languageId, 'Languages');

        $allPrice = 0;
        foreach ($cartItems as $item){
            if($item->getMain()){
                $quantity = $item->getQuantity();
                $articlePrice = $item->getMain()->getPrice();
                $allPrice += ($quantity * $articlePrice);
            }
            else if($item){
                $quantity = $item->getQuantity();
                $articlePrice = $item->getArticleTrans()->getArticle()->getPrice();
                $allPrice += ($quantity * $articlePrice);
            }
        }

        return $this->render('cart/index.html.twig',[
            'homeTitle' => $homeTitle,
            'reviewTitle' => $reviewTitle,
            'designTitle' => $designTitle,
            'photographyTitle' => $photographyTitle,
            'videographyTitle' => $videographyTitle,
            'batteryTitle' => $batteryTitle,
            'galleryTitle' => $galleryTitle,
            'productTitle' => $productTitle,
            'commentTitle' => $commentTitle,
            'contactTitle' => $contactTitle,
            'languageTitle' => $languageTitle,
            'locale' => $locale,
            'languages' => $languages,
            'cartItems' => $cartItems,
            'cart' => $cart,
            'photos' => $photos,
            'allPrice' => $allPrice
        ]);
    }

    /**
     * @Route("/cart/edit_quantity", name="cart_edit_quantity")
     */
    public function editQuantity(Request $request)
    {
        $quantity = $request->get('quantity');

        $cartItem = $this->getDoctrine()
            ->getRepository(CartItem::class)
            ->find($request->get('cartItemId'));

        $cartItem->setQuantity($quantity);
        $em = $this->getDoctrine()->getManager();
        $em->persist($cartItem);
        $em->flush();

        return $this->redirectToRoute('cart',[
            'cartId' => $request->get('cartId'),
            'locale' => $request->get('locale')
        ]);
    }

    /**
     * @Route("/cart/{cartId}/{cartItemId}", name="cartItem_delete")
     */
    public function deleteCartItem(Request $request)
    {
        $cartItem = $this->getDoctrine()
            ->getRepository(CartItem::class)
            ->find($request->get('cartItemId'));

        $em = $this->getDoctrine()->getManager();
        $em->remove($cartItem);
        $em->flush();

        return $this->redirectToRoute('cart', [
            'cartId' => $request->get('cartId'),
            'locale' => $request->get('locale')
        ]);
    }

    /**
     * @Route("/send_cart/{cartId}", name="send_cart")
     */
    public function sendOrder(Request $request)
    {
        $name = $request->get('name');
        $address = $request->get('address');

        $cart = $this->getDoctrine()
            ->getRepository(Cart::class)
            ->find($request->get('cartId'));

        $cart->setName($name);
        $cart->setAddress($address);

        $em = $this->getDoctrine()->getManager();
        $em->persist($cart);
        $em->flush();

        return $this->redirectToRoute('home', [
            'locale' => $request->get('locale')
        ]);
    }
}
