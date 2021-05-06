<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\ArticleTranslation;
use App\Entity\Cart;
use App\Entity\CartItem;
use App\Entity\Comment;
use App\Entity\CommentTranslation;
use App\Entity\Language;
use App\Entity\MainArticle;
use App\Entity\Page;
use App\Entity\PageList;
use App\Entity\PageTrans;
use App\Entity\Photo;
use App\Entity\User;
use App\Form\ArticleFormType;
use App\Form\CommentFormType;
use App\Form\CommentTransFormType;
use App\Form\MainFormType;
use App\Form\PageListFormType;
use App\Form\PageTransType;
use App\Form\PhotoFormType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    /**
     * @Route("/redirect", name="redirect")
     */
    public function redirecting()
    {
        return $this->redirectToRoute('home');
    }

    /**
     * @Route("/administration", name="administration")
     * @IsGranted("ROLE_ADMIN")
     */
    public function index(): Response
    {
        return $this->render('admin/base.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }

    /**
     * @Route("/administration_user/list", name="user_list")
     * @IsGranted("ROLE_ADMIN")
     * @return Response
     */
    public function listUser()
    {
        $users = $this->getDoctrine()->getRepository(User::class)->findAll();

        return $this->render('admin/user/index.html.twig', [
            'users'=>$users,
        ]);

    }

    /**
     * @Route("/administration_user/edit/{id}", name="user_edit_form")
     * @IsGranted("ROLE_ADMIN")
     */
    public function editUserForm(Request $request)
    {
        $userId = $request->get("id");
        $user = null;

        if($userId != null && $userId != 0){
            $user = $this->getDoctrine()
                    ->getRepository(User::class)
                    ->findOneBy(["id"=>$userId]);
        }

        return $this->render("admin/user/edit.html.twig",[
            "user" => $user,
        ]);


    }

    /**
     * @Route("administration_user/edit/", name="user_edit")
     * @IsGranted("ROLE_ADMIN")
     */
    public function editUser(Request $request)
    {
        $userId = $request->get("id");
        //$user = new User();
        if($userId != null && $userId != 0){
            $user = $this->getDoctrine()
                ->getRepository(User::class)
                ->findOneBy(["id"=>$userId]);

            $email = $request->get("email");
            $name = $request->get("name");
            $firstName = $request->get("firstName");
            $lastName = $request->get("lastName");

            $user->setEmail($email);
            $user->setName($name);
            $user->setFirstName($firstName);
            $user->setLastName($lastName);

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
        }

        return $this->redirectToRoute("user_list");

    }

    /**
     * @Route ("/administration_user/delete/{id}", name="user_delete")
     * @IsGranted("ROLE_ADMIN")
     */
    public function deleteUser(Request $request)
    {
        $userId = $request->get("id");
        $user = $this->getDoctrine()->getRepository(User::class)->findOneBy(["id"=>$userId]);

        $em = $this->getDoctrine()->getManager();
        $em->remove($user);
        $em->flush();

        return $this->redirectToRoute("user_list");
    }

    /**
     * @Route("/administration_article/list", name="article_list")
     * @IsGranted("ROLE_ADMIN")
     * @return Response
     */
    public function listArticle()
    {
        $articles = $this->getDoctrine()
            ->getRepository(Article::class)
            ->findAll();

        return $this->render("admin/article/index.html.twig", [
           "articles" => $articles,
        ]);
    }

    /**
     * @Route("/administration_article/create", name="article_create")
     * @IsGranted("ROLE_ADMIN")
     */
    public function createArticle(Request $request)
    {
        $article = new Article();
        $article_trans = new ArticleTranslation();
        $form = $this->createForm(ArticleFormType::class,$article_trans);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $slug = $request->get("slug");
            $quantity = $request->get("quantity");
            $price = $request->get("price");

            $article->setSlug($slug);
            $article->setQuantity($quantity);
            $article->setPrice($price);

            $em = $this->getDoctrine()->getManager();
            $em->persist($article);
            $em->flush();

            $article = $this->getDoctrine()
                ->getRepository(Article::class)
                ->findOneBy([
                    'slug' => $slug,
                ]);

            $article_trans->setArticle($article);

            $em->persist($article_trans);
            $em->flush();

            return $this->redirectToRoute('article_list');
        }
        return $this->render("admin/article/create.html.twig", [
            'form'=>$form->createView(),
        ]);
    }

    /**
     * @Route("/administration_article/edit/{id}", name="article_edit")
     * @IsGranted("ROLE_ADMIN")
     */
    public function editArticle(Request $request)
    {
        $articleId = $request->get('id');

        $article_trans = $this->getDoctrine()
            ->getRepository(ArticleTranslation::class)
            ->findOneBy([
                'article' => $articleId
            ]);
        $form = $this->createForm(ArticleFormType::class,$article_trans);
        $form->handleRequest($request);



        $article = $this->getDoctrine()
            ->getRepository(Article::class)
            ->findOneBy([
                'id'=> $articleId
            ]);

        if($form->isSubmitted() && $form->isValid()){

            $slug = $request->get("slug");
            $quantity = $request->get("quantity");
            $price = $request->get("price");

            $article->setSlug($slug);
            $article->setQuantity($quantity);
            $article->setPrice($price);


            $em = $this->getDoctrine()->getManager();
            $em->persist($article);
            $em->persist($article_trans);
            $em->flush();
            return $this->redirectToRoute('article_list');
        }

        return $this->render('admin/article/edit.html.twig',[
            'form' => $form->createView(),
            'name' => $article_trans->getName(),
            'slug' => $article->getSlug(),
            'quantity' => $article->getQuantity(),
            'price' => $article->getPrice()
        ]);
    }

    /**
     * @Route("/administration_article/photos/{id}", name="article_photo")
     * @IsGranted("ROLE_ADMIN")
     */
    public function photoArticle(Request $request)
    {
        $articleId = $request->get("id");

        $newPhoto = new Photo();
        $form = $this->createForm(PhotoFormType::class, $newPhoto);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $file = $request->files->get('photo_form')['file'];
            $upload_directory = $this->getParameter('uploads_directory_article');

            $fileName = md5(uniqid() . '.' . $file->guessExtension());

            $file->move(
                $upload_directory,
                $fileName
            );

            $article = $this->getDoctrine()
                ->getRepository(Article::class)
                ->find($request->get('id'));


            $newPhoto->setFile($fileName);
            $newPhoto->setArticle($article);
            $em = $this->getDoctrine()->getManager();
            $em->persist($newPhoto);
            $em->flush();

            return $this->redirectToRoute('article_photo', [
                'id' => $request->get('id')
            ]);
        }

        $article_trans = $this->getDoctrine()
            ->getRepository(ArticleTranslation::class)
            ->findOneBy([
                'article' => $articleId
            ]);

        $photos = $this->getDoctrine()
            ->getRepository(Photo::class)
            ->findBy([
                'article' => $articleId
            ]);

        return $this->render('admin/article/view_photo.html.twig',[
            'form' => $form->createView(),
           'photos' => $photos,
           'article_trans' => $article_trans
        ]);
    }

    /**
     * @Route("/administration_article/photo/delete/{articleId}/{id}", name="article_photo_delete")
     */
    public function deleteArticlePhoto(Request $request)
    {
        AdminController::removePhoto($request->get('id'));
        return $this->redirectToRoute('article_photo', [
            'id' => $request->get('articleId')
        ]);
    }

    /**
     * @Route("/administration_article/delete/{id}", name="article_delete")
     * @IsGranted("ROLE_ADMIN")
     */
    public function deleteArticle(Request $request)
    {
        $articleId = $request->get('id');

        $article = $this->getDoctrine()
            ->getRepository(Article::class)
            ->find($articleId);

        $articles_trans = $this->getDoctrine()
            ->getRepository(ArticleTranslation::class)
            ->findBy([
                'article' => $articleId
            ]);

        $photos = $this->getDoctrine()
            ->getRepository(Photo::class)
            ->findBy([
                'article' => $articleId
            ]);

        $em = $this->getDoctrine()->getManager();
        foreach ($photos as $photo) {
            if ($photo) {
                $em->remove($photo);
                $em->flush();
            }
        }
        foreach ($articles_trans as $article_trans){
            if($article_trans) {
                $em->remove($article_trans);
                $em->flush();
            }
        }
        $em->remove($article);
        $em->flush();

        return $this->redirectToRoute('article_list');
    }

    /**
     * @Route("/administration_article/translations/{id}", name="article_translation")
     */
    public function listTranslation(Request $request)
    {
        $articleId = $request->get("id");

        $articleTrans = $this->getDoctrine()
            ->getRepository(ArticleTranslation::class)
            ->findBy([
                'article' => $articleId
            ]);

        $article = $this->getDoctrine()
            ->getRepository(Article::class)
            ->find($articleId);

        return $this->render('admin/article/translation/index.html.twig', [
           'articles' => $articleTrans,
           'article' => $article,

        ]);
    }

    /**
     * @Route("/administration_article/translation_createEdit/{articleId}/{articleTransId?}", name="article_translation_createEdit")
     */
    public function createTranslation(Request $request)
    {
        $articleId = $request->get('articleId');

        if($request->get('articleTransId')){
            $articleTrans = $this->getDoctrine()
                ->getRepository(ArticleTranslation::class)
                ->find($request->get('articleTransId'));

            $check = false;
        }
        else{
            $articleTrans = new ArticleTranslation();
            $check = true;
        }

        $form = $this->createForm(ArticleFormType::class, $articleTrans);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            if($check){
               $article = $this->getDoctrine()
               ->getRepository(Article::class)
               ->find($articleId);
               $articleTrans->setArticle($article);
            }
            $em = $this->getDoctrine()->getManager();
            $em->persist($articleTrans);
            $em->flush();

            return $this->redirectToRoute('article_translation', [
                'id' => $articleId
            ]);
        }
        return $this->render('admin/article/translation/create_Edit.html.twig',[
            'form' => $form->createView(),
            'check' =>$check
        ]);
    }

    /**
     * @Route("/adminstration_article/translation_delete/{articleId)/{articleTransId}", name="article_translation_delete")
     */
    public function deleteTranslation(Request $request)
    {
        $articleTrans = $this->getDoctrine()
            ->getRepository(ArticleTranslation::class)
            ->find($request->get('articleTransId'));

        $em = $this->getDoctrine()->getManager();
        $em->remove($articleTrans);
        $em->flush();

        return $this->redirectToRoute('article_translation', [
            'id' => $request->get('articleId')
        ]);
    }


    /**
     * @Route("/administration_article/comment_list/{id}", name="article_comment_list")
     */
    public function listArticleComment(Request $request)
    {
        $articleId = $request->get('id');

        $article = $this->getDoctrine()
            ->getRepository(Article::class)
            ->find($articleId);

        $comments = $this->getDoctrine()
            ->getRepository(Comment::class)
            ->findBy([
               'article' => $articleId
            ]);

        return $this->render('admin/article/comments/index.html.twig', [
            'comments' => $comments,
            'article' => $article
        ]);

    }

    /**
     * @Route("/administration_artilce/comment_edit/{articleId}/{commentId}" , name="article_comment_edit")
     */
    public function editArticleComment(Request $request)
    {
        $commentId = $request->get('commentId');

        $comment = $this->getDoctrine()
            ->getRepository(Comment::class)
            ->find($commentId);

        $form = $this->createForm(CommentFormType::class, $comment);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($comment);
            $em->flush();

            return $this->redirectToRoute('article_comment_list', [
                'id' => $request->get('articleId')
            ]);
        }

        return $this->render('admin/article/comments/edit.html.twig', [
            'form' => $form->createView()
        ]);

    }

    /**
     * @Route("/administration_article/comment_delete/{articleId}/{commentId}", name="article_comment_delete")
     */
    public function deleteArticleComment(Request $request)
    {
        $commentId = $request->get('commentId');

        $commentTrans = $this->getDoctrine()
            ->getRepository(CommentTranslation::class)
            ->findBy([
               'comment' => $commentId
            ]);

        $comment = $this->getDoctrine()
            ->getRepository(Comment::class)
            ->find($commentId);

        $em = $this->getDoctrine()->getManager();

        foreach ($commentTrans as $comm){
            if($comm){
                $em->remove($comm);
                $em->flush();
            }
        }

        $em->remove($comment);
        $em->flush();

        return $this->redirectToRoute('article_comment_list', [
            'id' => $request->get('articleId')
        ]);
    }

    /**
     * @Route("/administration_article/comment/translation_list/{commentId}", name="article_comment_translation_list")
     */
    public function listArticleCommentTrans(Request $request)
    {
        $commentId = $request->get('commentId');
        $comment = $this->getDoctrine()
            ->getRepository(Comment::class)
            ->find(
                $commentId
            );

        $commentTrans = $this->getDoctrine()
            ->getRepository(CommentTranslation::class)
            ->findBy([
                'comment' => $commentId
            ]);

        return $this->render('admin/article/comments/translation/index.html.twig', [
            'commentTrans' => $commentTrans,
            'comment' => $comment
        ]);
    }

    /**
     * @Route("/administration_article/comment/translation_createEdit/{commentId}/{commentTransId?}", name="article_comment_translation_createEdit")
     */
    public function createEditArticleCommentTrans(Request $request)
    {
        $commentId = $request->get('commentId');
        $commentTransId = $request->get('commentTransId');

        $comment = $this->getDoctrine()
            ->getRepository(Comment::class)
            ->find($commentId);

        if($commentTransId){
            $commentTrans = $this->getDoctrine()
                ->getRepository(CommentTranslation::class)
                ->find($commentTransId);
        }
        else {
            $commentTrans = new CommentTranslation();
        }

        $form = $this->createForm(CommentTransFormType::class, $commentTrans);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $commentTrans->setComment($comment);
            $em = $this->getDoctrine()->getManager();
            $em->persist($commentTrans);
            $em->flush();

            return $this->redirectToRoute('article_comment_translation_list', [
                'commentId' => $commentId
            ]);
        }



        return $this->render('admin/article/comments/translation/addEdit.html.twig',[
           'form' => $form->createView(),
           'comment' => $comment
        ]);


    }

    /**
     * @Route("/administrationo_article/comment/translation_delete/{commentId}/{commentTransId}", name="article_comment_translation_delete")
     */
    public function deleteArticleCommentTrans(Request $request)
    {
        $commentTrans = $this->getDoctrine()
            ->getRepository(CommentTranslation::class)
            ->find($request->get('commentTransId'));

        $em = $this->getDoctrine()->getManager();
        $em->remove($commentTrans);
        $em->flush();

        return $this->redirectToRoute('article_comment_translation_list', [
            'commentId' => $request->get('commentId'),
        ]);
    }
    
    /**
     * @Route("/administration_main/list", name="main_list")
     */
    public function listMain(Request $request)
    {
        if($request->get('page')){
            $pageId = $request->get('page');
        }
        else {
            $pageId = 1;
        }
        $pageList = $this->getDoctrine()
            ->getRepository(PageList::class)
            ->findBy([
                'page' => $pageId
            ]);

        $pages = $this->getDoctrine()
            ->getRepository(Page::class)
            ->findAll();

        return $this->render('admin/mainArticle/index.html.twig', [
            'pageList' => $pageList,
            'pages' => $pages,
            'pageId' => $pageId
        ]);
    }

    /**
     * @Route("/administration_main/create/{pageId}/{pageListId?}", name="main_create_edit")
     */
    public function createPageList(Request $request)
    {
        $pageId = $request->get('pageId');
        $page = $this->getDoctrine()
            ->getRepository(Page::class)
            ->find($pageId);

        if($request->get('pageListId')){
            $pageList = $this->getDoctrine()
                ->getRepository(PageList::class)
                ->find($request->get('pageListId'));
        }
        else {
            $pageList = new PageList();
        }

        $form = $this->createForm(PageListFormType::class, $pageList);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $pageList->setPage($page);

            $em = $this->getDoctrine()->getManager();
            $em->persist($pageList);
            $em->flush();

            return $this->redirectToRoute('main_list');
        }

        return $this->render('admin/mainArticle/create_edit.html.twig',[
            'form' => $form->createView(),
            'page' => $page
        ]);
    }

    /**
     * @Route("/administration_main/photos/{id}", name="main_subpage_photo")
     * @IsGranted("ROLE_ADMIN")
     */
    public function photoMain(Request $request)
    {
        $newPhoto = new Photo();
        $form = $this->createForm(PhotoFormType::class, $newPhoto);
        $form->handleRequest($request);

        $pageListId = $request->get("id");
        $pageList = $this->getDoctrine()
            ->getRepository(PageList::class)
            ->findOneBy([
                'id' => $pageListId
            ]);

        if($form->isSubmitted() && $form->isValid()){
            $file = $request->files->get('photo_form')['file'];
            $upload_directory = $this->getParameter('uploads_directory_main');

            $fileName = md5(uniqid() . '.' . $file->guessExtension());

            $file->move(
                $upload_directory,
                $fileName
            );

            $newPhoto->setFile($fileName);
            $newPhoto->setPageList($pageList);
            $em = $this->getDoctrine()->getManager();
            $em->persist($newPhoto);
            $em->flush();
            return  $this->redirectToRoute('main_subpage_photo', [
                'id'=> $pageListId
            ]);
        }

        $photos = $this->getDoctrine()
            ->getRepository(Photo::class)
            ->findBy([
                'pageList' => $pageListId
            ]);


        return $this->render('admin/mainArticle/view_photo.html.twig',[
            'form' => $form->createView(),
            'photos' => $photos,
            'pageList' => $pageList
        ]);
    }

    /**
     * @Route("/administration_main/photo/{pageListId}/{id}", name="main_subpage_photo_delete")
     */
    public function deleteMainPhoto(Request $request)
    {
        AdminController::removePhoto($request->get('id'));

        return $this->redirectToRoute('main_subpage_photo', [
            'id' => $request->get('pageListId')
        ]);
    }


    /**
     * @Route("/administration_main/delete/{id}", name="main_subpage_delete")
     */
    public function deleteSubPage(Request $request)
    {
        $pageListId = $request->get('id');

        $pageTrans = $this->getDoctrine()
            ->getRepository(PageTrans::class)
            ->findBy([
                'pageList' => $pageListId
            ]);

        $pageList = $this->getDoctrine()
            ->getRepository(PageList::class)
            ->find($pageListId);

        $em = $this->getDoctrine()->getManager();
        foreach ($pageTrans as $page) {
            if(page){
                $em->remove($page);
                $em->flush();
            }
        }

        $em->remove($pageList);
        $em->flush();

        return $this->redirectToRoute('main_list');

    }


    /**
     * @Route("/administration_main/translation_list/{pageListId}", name="main_translation_list")
     */
    public function listMainTranslation(Request $request)
    {
        $pageListId = $request->get('pageListId');

        $pageList = $this->getDoctrine()
            ->getRepository(PageList::class)
            ->find($pageListId);

        $pageTrans = $this->getDoctrine()
            ->getRepository(PageTrans::class)
            ->findBy([
               'pageList' => $pageListId
            ]);

        return $this->render('admin/mainArticle/translation/index.html.twig', [
            'pageTrans' => $pageTrans,
            'pageList' => $pageList
        ]);



    }

    /**
     * @Route("/administration_main/translation_create/{id}", name="main_translation_create")
     */
    public function createPageTrans(Request $request)
    {
        $pageListId = $request->get('id');
        $pageList = $this->getDoctrine()
            ->getRepository(PageList::class)
            ->find($pageListId);

        $pageTrans = new PageTrans();
        $form = $this->createForm(PageTransType::class, $pageTrans);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $pageTrans->setPageList($pageList);
            $em = $this->getDoctrine()->getManager();
            $em->persist($pageTrans);
            $em->flush();

            return $this->redirectToRoute('main_translation_list', [
                'pageListId' => $pageListId
            ]);
        }

        return $this->render('admin/mainArticle/translation/create.html.twig', [
            'pageList' => $pageList,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/administration_main/translation_edit/{pageListId}/{id}", name="main_translation_edit")
     * @IsGranted("ROLE_ADMIN")
     */
    public function editMain(Request $request)
    {
        $pageTransId = $request->get('id');

        $pageTrans = $this->getDoctrine()
            ->getRepository(PageTrans::class)
            ->find($pageTransId);

        $form = $this->createForm(PageTransType::class, $pageTrans);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $em = $this->getDoctrine()->getManager();
            $em->persist($pageTrans);
            $em->flush();
            return $this->redirectToRoute('main_translation_list', [
                'pageListId' => $request->get('pageListId')
            ]);
        }

        return $this->render('admin/mainArticle/translation/edit.html.twig',[
            'form' => $form->createView(),
            'pageList' => $pageTrans->getPageList()
        ]);
    }

    /**
     * @Route("/administration_main/translation_delete/{pageListId}/{id}", name="main_translation_delete")
     */
    public function deleteMain(Request $request)
    {
        $pageListId = $request->get('pageListId');
        $pageTransId = $request->get('id');

        $pageTrans = $this->getDoctrine()
            ->getRepository(PageTrans::class)
            ->find($pageTransId);

        $em = $this->getDoctrine()->getManager();
        $em->remove($pageTrans);
        $em->flush();

        return $this->redirectToRoute('main_translation_list', [
            'pageListId' => $pageListId
        ]);
    }

    /**
     * @Route("/administration_main/comment_list/{mainId}", name="main_comment_list")
     */
    public function listMainComment(Request $request)
    {
        $mainId = $request->get('mainId');

        $comments = $this->getDoctrine()
            ->getRepository(Comment::class)
            ->findBy([
                'main' => $mainId
            ]);

        $main = $this->getDoctrine()
            ->getRepository(MainArticle::class)
            ->find($mainId);

        return $this->render('admin/mainArticle/comments/index.html.twig', [
           'comments' => $comments,
            'main' => $main
        ]);

    }

    /**
     * @Route("/administration_main/comment_edit/{mainId}/{commentId}" , name="main_comment_edit")
     */
    public function editMainComment(Request $request)
    {
        $commentId = $request->get('commentId');

        $comment = $this->getDoctrine()
            ->getRepository(Comment::class)
            ->find($commentId);

        $form = $this->createForm(CommentFormType::class, $comment);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($comment);
            $em->flush();

            return $this->redirectToRoute('main_comment_list', [
                'mainId' => $request->get('mainId')
            ]);
        }

        return $this->render('admin/mainArticle/comments/edit.html.twig', [
            'form' => $form->createView()
        ]);

    }

    /**
     * @Route("/administration_main/comment_delete/{mainId}/{commentId}", name="main_comment_delete")
     */
    public function deleteMainComment(Request $request)
    {
        $commentId = $request->get('commentId');

        $commentTrans = $this->getDoctrine()
            ->getRepository(CommentTranslation::class)
            ->findBy([
                'comment' => $commentId
            ]);

        $comment = $this->getDoctrine()
            ->getRepository(Comment::class)
            ->find($commentId);

        $em = $this->getDoctrine()->getManager();

        foreach ($commentTrans as $comm){
            if($comm){
                $em->remove($comm);
                $em->flush();
            }
        }

        $em->remove($comment);
        $em->flush();

        return $this->redirectToRoute('main_comment_list', [
            'mainId' => $request->get('mainId')
        ]);
    }

    /**
     * @Route("/administration_main/comment/translation_list/{commentId}", name="main_comment_translation_list")
     */
    public function listMainCommentTrans(Request $request)
    {
        $commentId = $request->get('commentId');
        $comment = $this->getDoctrine()
            ->getRepository(Comment::class)
            ->find(
                $commentId
            );

        $commentTrans = $this->getDoctrine()
            ->getRepository(CommentTranslation::class)
            ->findBy([
                'comment' => $commentId
            ]);

        return $this->render('admin/mainArticle/comments/translation/index.html.twig', [
            'commentTrans' => $commentTrans,
            'comment' => $comment
        ]);
    }

    /**
     * @Route("/administration_main/comment/translation_createEdit/{commentId}/{commentTransId?}", name="main_comment_translation_createEdit")
     */
    public function createEditMainCommentTrans(Request $request)
    {
        $commentId = $request->get('commentId');
        $commentTransId = $request->get('commentTransId');

        $comment = $this->getDoctrine()
            ->getRepository(Comment::class)
            ->find($commentId);

        if($commentTransId){
            $commentTrans = $this->getDoctrine()
                ->getRepository(CommentTranslation::class)
                ->find($commentTransId);
        }
        else {
            $commentTrans = new CommentTranslation();
        }

        $form = $this->createForm(CommentTransFormType::class, $commentTrans);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $commentTrans->setComment($comment);
            $em = $this->getDoctrine()->getManager();
            $em->persist($commentTrans);
            $em->flush();

            return $this->redirectToRoute('main_comment_translation_list', [
                'commentId' => $commentId
            ]);
        }



        return $this->render('admin/mainArticle/comments/translation/createEdit.html.twig',[
            'form' => $form->createView(),
            'comment' => $comment
        ]);


    }

    /**
     * @Route("/administrationo_main/comment/translation_delete/{commentId}/{commentTransId}", name="main_comment_translation_delete")
     */
    public function deleteMainCommentTrans(Request $request)
    {
        $commentTrans = $this->getDoctrine()
            ->getRepository(CommentTranslation::class)
            ->find($request->get('commentTransId'));

        $em = $this->getDoctrine()->getManager();
        $em->remove($commentTrans);
        $em->flush();

        return $this->redirectToRoute('main_comment_translation_list', [
            'commentId' => $request->get('commentId'),
        ]);
    }

    /**
     * @Route("/administration_order/list", name="order_list")
     */
    public function listCart()
    {
        $carts = $this->getDoctrine()
            ->getRepository(Cart::class)
            ->findAll();

        return $this->render('admin/cart/index.html.twig', [
           'carts' => $carts
        ]);
    }

    /**
     * @Route("/administration_order/confirm/{id}", name="order_confirm")
     */
    public function confirmOrder(Request $request)
    {
        $cartId = $request->get('id');

        $cart = $this->getDoctrine()
            ->getRepository(Cart::class)
            ->find($cartId);

        if($cart->getCheckedOut() == false){
            $cart->setCheckedOut(true);
        }
        else {
            $cart->setCheckedOut(false);
        }
        $em = $this->getDoctrine()->getManager();
        $em->persist($cart);
        $em->flush();

        return $this->redirectToRoute('order_list');
    }

    /**
     * @Route("/administration_order/review/{id}", name="order_review")
     */
    public function reviewOrder(Request $request)
    {
        $cartId = $request->get('id');

        $cart = $this->getDoctrine()
            ->getRepository(Cart::class)
            ->find($cartId);

        $cartItems = $this->getDoctrine()
            ->getRepository(CartItem::class)
            ->findBy([
           'cart' => $cartId
        ]);

        $allPrice = 0;

        foreach ($cartItems as $cartItem){
            if($cartItem){
                $price = 0;
                $main = $cartItem->getMain();
                $articleTrans = $cartItem->getArticleTrans();
                if($main){
                    $price = $main->getPrice();
                }
                else{
                    $article = $articleTrans->getArticle();
                    $price = $article->getPrice();
                }
                $allPrice += ($price * $cartItem->getQuantity());
            }
        }

        return $this->render('admin/cart/review.html.twig', [
           'cart' => $cart,
           'cartItems' => $cartItems,
           'allPrice' => $allPrice
        ]);

    }

    /**
     * @Route("/administration_order/delete/{id}", name="order_delete")
     */
    public function deleteOrder(Request $request)
    {
        $cartId = $request->get('id');

        $cart = $this->getDoctrine()
            ->getRepository(Cart::class)
            ->find($cartId);

        $cartItem = $this->getDoctrine()
            ->getRepository(CartItem::class)
            ->findBy([
                'cart' => $cartId
            ]);

        $em = $this->getDoctrine()->getManager();

        foreach ($cartItem as $item){
            if($item){
                $em->remove($item);
                $em->flush();
            }
        }

        $em->remove($cart);
        $em->flush();

        return $this->redirectToRoute('order_list');
    }

    /**
     * @Route("/administration_oreder/delete_article/{cartId}/{id}", name="order_article_delete")
     */
    public function deleteOrderArticleItem(Request $request)
    {
        $cartItem =  $this->getDoctrine()
            ->getRepository(CartItem::class)
            ->findOneBy([
                "articleTrans" => $request->get('id')
            ]);
        AdminController::removeOrderItem($cartItem);

        return $this->redirectToRoute('order_review', [
            'id' => $request->get('cartId')
        ]);
    }


    /**
     * @Route("/administration_oreder/delete_main/{cartId}/{id}", name="order_main_delete")
     */
    public function deleteOrderMainItem(Request $request)
    {
        $cartItem =  $this->getDoctrine()
            ->getRepository(CartItem::class)
            ->findOneBy([
                "main" => $request->get('id')
            ]);
        AdminController::removeOrderItem($cartItem);

        return $this->redirectToRoute('order_review', [
            'id' => $request->get('cartId')
        ]);
    }

    public function removeOrderItem(CartItem $cartItem)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($cartItem);
        $em->flush();
    }

    public function removePhoto(int $id)
    {
        $photo = $this->getDoctrine()
            ->getRepository(Photo::class)
            ->find($id);

        $em = $this->getDoctrine()->getManager();
        $em->remove($photo);
        $em->flush();
    }
}
