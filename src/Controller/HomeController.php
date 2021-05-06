<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\ArticleTranslation;
use App\Entity\Bulletin;
use App\Entity\Cart;
use App\Entity\CartItem;
use App\Entity\Comment;
use App\Entity\CommentTranslation;
use App\Entity\Language;
use App\Entity\MainArticle;
use App\Entity\Page;
use App\Entity\PageList;
use App\Entity\PageTitleTrans;
use App\Entity\PageTrans;
use App\Entity\Photo;
use App\Form\BulletinFormType;
use PhpParser\Node\Expr\Cast\Object_;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\DateTime;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(Request $request): Response
    {
        if($request->get('locale')){
            $locale = $request->get('locale');
        }
        else {
            $locale = 'en';
        }

        $cartId = $request->get('cartId');
        if($cartId) {
            $cartItem = $this->getDoctrine()
                ->getRepository(CartItem::class)
                ->findBy([
                    'cart' => $cartId
                ]);
        }
        else
            $cartItem = '';

        $language = $this->getDoctrine()
            ->getRepository(Language::class)
            ->findOneBy([
               'locale' => $locale
            ]);

        $mainArticle = $this->getDoctrine()
            ->getRepository(MainArticle::class)
            ->find(1);

        $languageId = $language->getId();

        $homeTitle = HomeController::getPageTitle($languageId, 'Home');
        $homePhotos = HomeController::getMainPhoto($languageId, 'Home-Image');

        $reviewTitle = HomeController::getPageTitle($languageId, 'Review');
        $designTitle = HomeController::getPageTitle($languageId, 'Design');
        $design = HomeController::getPage($languageId, 'Design');
        $designPhotos = HomeController::getMainPhoto($languageId, 'Design');

        $photographyTittle = HomeController::getPageTitle($languageId, 'Photography');
        $photography = HomeController::getPage($languageId, 'Photography');
        $allCameras = HomeController::getPage($languageId, 'All Cameras');
        $photographyDescription = HomeController::getPage($languageId, 'Photography_Description');
        $photographyDescriptionPhotos = HomeController::getMainPhoto($languageId, 'Photography_Description');

        $videographyTitle = HomeController::getPageTitle($languageId, 'Videography');
        $videography = HomeController::getPage($languageId, 'Videography');

        $batteryTitle = HomeController::getPageTitle($languageId, 'Battery and Charging');
        $battery = HomeController::getPage($languageId, 'Battery');
        $batteryPhoto = HomeController::getMainPhoto($languageId, 'Battery');
        $charging = HomeController::getPage($languageId, 'Charging');
        $chargingPhoto = HomeController::getMainPhoto($languageId, 'Charging');

        $galleryTitle = HomeController::getPageTitle($languageId, 'Gallery');
        $gallery = HomeController::getPage($languageId, 'Gallery');
        $galleryPhotos = HomeController::getMainPhoto($languageId, 'Gallery');

        $contactTitle = HomeController::getPageTitle($languageId, 'Contact');
        $contact = HomeController::getPage($languageId, 'Contact');

        $productTitle = HomeController::getPageTitle($languageId, 'Products');
        $articles = $this->getDoctrine()
            ->getRepository(ArticleTranslation::class)
            ->findBy([
                'language' => $languageId
            ]);
        $articlePhotos = HomeController::getArticlePhoto();

        $cartTitle = HomeController::getPageTitle($languageId, 'Cart');
        $commentTitle = HomeController::getPageTitle($languageId, 'Comments');
        $subscribeTitle = HomeController::getPageTitle($languageId, 'Subscribe to news');

        $commentTrans = $this->getDoctrine()
            ->getRepository(CommentTranslation::class)
            ->findBy([
               'language' => $languageId
            ]);

        $languageTitle = HomeController::getPageTitle($languageId, 'Languages');
        $languages = $this->getDoctrine()
            ->getRepository(Language::class)
            ->findAll();

        $em = $this->getDoctrine()->getManager();

        $bulletin = new Bulletin();
        $bulletinForm = $this->createForm(BulletinFormType::class, $bulletin);
        $bulletinForm->handleRequest($request);
        if($bulletinForm->isSubmitted() && $bulletinForm->isValid()){
            $em->persist($bulletin);
            $em->flush();

            return $this->redirectToRoute("home");
        }


        return $this->render('home/index.html.twig', [
            'cartId' => $cartId,
            'language' => $language,
            'homeTitle' => $homeTitle,
            'reviewTitle' => $reviewTitle,
            'designTitle' => $designTitle,
            'photographyTitle' => $photographyTittle,
            'videographyTitle' => $videographyTitle,
            'batteryTitle' => $batteryTitle,
            'galleryTitle' => $galleryTitle,
            'productTitle' => $productTitle,
            'commentTitle' => $commentTitle,
            'commentTrans' => $commentTrans,
            'contactTitle' => $contactTitle,
            'cartTitle' => $cartTitle,
            'cartItem' => $cartItem,
            'languageTitle' => $languageTitle,
            'subscribeTitle' => $subscribeTitle,
            'languages' => $languages,
            'mainArticle' => $mainArticle,
            'homePhotos' => $homePhotos,
            'design' => $design,
            'designPhotos' => $designPhotos,
            'photography' => $photography,
            'allCameras' => $allCameras,
            'photographyDescription' => $photographyDescription,
            'photographyDescriptionPhotos' => $photographyDescriptionPhotos,
            'videography' => $videography,
            'battery' => $battery,
            'batteryPhotos' => $batteryPhoto,
            'charging' => $charging,
            'chargingPhotos' => $chargingPhoto,
            'gallery' => $gallery,
            'galleryPhotos' => $galleryPhotos,
            'articles' => $articles,
            'articlePhotos' => $articlePhotos,
            'contact' => $contact,
            'bulletinForm' => $bulletinForm->createView()
        ]);

    }

    /**
     * @Route("add_comment", name="add_comment")
     */
    public function addComment(Request $request)
    {
        $name = $request->get('name');
        $articleId = $request->get('articleVote');
        $rating = $request->get('rating');
        $comment = $request->get('comment');

        $comm = new Comment();

        $comm->setName($name);
        $comm->setRating($rating);

        if($articleId == 0){
            $mainArticle = $this->getDoctrine()
                ->getRepository(MainArticle::class)
                ->find(1);
            $comm->setMain($mainArticle);
        }
        else {
            $article = $this->getDoctrine()
                ->getRepository(Article::class)
                ->find($articleId);
            $comm->setArticle($article);
        }


        $em = $this->getDoctrine()->getManager();
        $em->persist($comm);
        $em->flush();

        $commentDB = $this->getDoctrine()
            ->getRepository(Comment::class)
            ->findOneBy([
                'name' => $name,
                'rating' => $rating,
            ]);

        $commentTrans = new CommentTranslation();

        $commentTrans->setComment($commentDB);
        $commentTrans->setCommentTrans($comment);
        $commentTrans->setLanguage($this->getDoctrine()->getRepository(Language::class)->find(1));
        $em->persist($commentTrans);
        $em->flush();


        return $this->redirectToRoute('home');
    }

    /**
     * @Route("/add_cart", name="add_cart")
     */
    public function addCart(Request $request)
    {
        if($request->request->get('articleId')) {
            $articleTrans = $this->getDoctrine()
                ->getRepository(ArticleTranslation::class)
                ->findOneBy([
                    'article' => $request->request->get('articleId')
                ]);
            $main = null;
        }
        else {
            $main = $this->getDoctrine()
                ->getRepository(MainArticle::class)
                ->find($request->request->get('mainId'));
            $articleTrans = null;
        }
        $quantity = $request->request->get('quantity');

        $cartId = $request->request->get('cartId');
        if($cartId){
            $currentCart = $this->getDoctrine()
                ->getRepository(Cart::class)
                ->find($cartId);

        }
        else {
            $cart = new Cart();
            $date = new \DateTime();

            $cart->setDateCreated($date);
            $cart->setCheckedOut(false);

            $em = $this->getDoctrine()->getManager();
            $em->persist($cart);
            $em->flush();

            $currentCart = $this->getDoctrine()
                ->getRepository(Cart::class)
                ->findOneBy([
                    'dateCreated' => $date
                ]);
        }

        if($articleTrans != null){
            $cartItem = $this->getDoctrine()
                ->getRepository(CartItem::class)
                ->findOneBy([
                    'cart' => $cartId,
                    'articleTrans' => $articleTrans->getId()
                ]);
        }
        else {
            $cartItem = $this->getDoctrine()
                ->getRepository(CartItem::class)
                ->findOneBy([
                    'cart' => $cartId,
                    'main' => $main->getId()
                ]);
        }


        if($cartItem){
            $cartItemQuantity = $cartItem->getQuantity();
            $cartItem->setQuantity($cartItemQuantity + $quantity);
        }
        else {
            $cartItem = new CartItem();
            $cartItem->setMain($main);
            $cartItem->setQuantity($quantity);
            $cartItem->setArticleTrans($articleTrans);
            $cartItem->setCart($currentCart);
        }
        $em = $this->getDoctrine()->getManager();
        $em->persist($cartItem);
        $em->flush();

        return new JsonResponse(array(
            'locale' => $request->get('locale'),
            'cartId' => $currentCart->getId()
        ));

    }

    /**
     * @Route("/getCartItem",name="getCartItem")
     */
    public function getCartItem(Request $request)
    {
        $mainId = $request->request->get('mainId');
        if($mainId != 0){
            $items = $this->getDoctrine()
                ->getRepository(CartItem::class)
                ->findOneBy([
                    'cart' => $request->request->get('cartId'),
                    'main' => $mainId
                ]);
            $price = $items->getMain()->getPrice();
            $image = $items->getMain()->getPhoto();
        }
        else {
            $articleId = $request->request->get('articleId');

            $articleTrans = $this->getDoctrine()
                ->getRepository(ArticleTranslation::class)
                ->findOneBy([
                    'article' => $request->request->get('articleId')
                ]);

            $items = $this->getDoctrine()
                ->getRepository(CartItem::class)
                ->findOneBy([
                    'cart' => $request->request->get('cartId'),
                    'articleTrans' => $articleTrans->getId()
                ]);
            $price = $articleTrans->getArticle()->getPrice();

            $photo = $this->getDoctrine()
                ->getRepository(Photo::class)
                ->findOneBy([
                   'article' => $articleId
                ]);
            $image = $photo->getFile();
        }

        $quantity = $items->getQuantity();


        return new JsonResponse(array(
            'image' => $image,
            'quantity' => $quantity,
            'price' => $price
        ));
    }

    public function getPage(int $languageId,string $subpage)
    {
        $pageList = $this->getDoctrine()
            ->getRepository(PageList::class)
            ->findOneBy([
                'subpage' => $subpage
            ]);


        return $this->getDoctrine()
            ->getRepository(PageTrans::class)
            ->findOneBy([
                'locale' => $languageId,
                'pageList' => $pageList->getId()
            ]);

    }

    public function getArticlePhoto()
    {
        return $this->getDoctrine()
            ->getRepository(Photo::class)
            ->findBy([
               'pageList' => null
            ]);
    }

    public function getMainPhoto(int $languageId,string $title)
    {
        $pageList = HomeController::getPage($languageId, $title);


        return $this->getDoctrine()
            ->getRepository(Photo::class)
            ->findBy([
                'pageList' => $pageList->getPageList()
            ]);

    }

    public function getPageTitle(int $languageId,string $title)
    {
        $page = $this->getDoctrine()
            ->getRepository(Page::class)
            ->findOneBy([
               'title' => $title
            ]);

        $pageTitleTrans = $this->getDoctrine()
            ->getRepository(PageTitleTrans::class)
            ->findOneBy([
               'page' => $page->getId(),
               'language' => $languageId
            ]);

        return $pageTitleTrans->getTitleTrans();
    }


}
