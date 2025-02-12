<?php

namespace App\Controller;

use App\Entity\Article;
use App\Exception\UnauthorizedArticleDeletionException;
use App\Exception\UnauthorizedArticleEditionException;
use App\Form\ArticleType;
use App\Service\ArticleService;
use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{

    private ArticleService $articleService;
    private UserService $userService;

    public function __construct(
        ArticleService $articleService,
        UserService $userService
    )
    {
        $this->articleService = $articleService;
        $this->userService = $userService;
    }

    /**
     *  Home
     *
     * @param Request $request
     * @return Response
     */
    #[Route('/', name: 'article_index')]
    public function index(Request $request): Response
    {
        $page = $request->query->getInt('page', 1);
        $articles = $this->articleService->getPaginatedArticles($page);

        return $this->render('article/index.html.twig', [
            'articles' => $articles
        ]);
    }

    /**
     * Method to create a new article
     *
     * @param Request $request
     * @return Response
     */
    #[Route('/article/new', name: 'article_new')]
    public function new(Request $request): Response
    {
        // We check that the user is logged in
        $currentUser = $this->getUser();
        if (!$currentUser) {
            throw $this->createAccessDeniedException('You must be logged in to edit this article.');
        }

        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->userService->getUserByEmail($this->getUser()->getUserIdentifier());
            $this->articleService->registerArticle($form->getData(), $user);

            $this->addFlash('success', 'Article created successfully!');
            return $this->redirectToRoute('article_index');
        }

        return $this->render('article/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * Method to get and show an article
     *
     * @param Request $request
     * @return Response
     */
    #[Route('/article/{slug}', name: 'article_show', methods: ['GET'])]
    public function show(Request $request): Response
    {
        $slug = $request->get('slug');

        // We fetch the article corresponding to this slug
        $article = $this->articleService->getArticleBySlug($slug);

        return $this->render('article/show.html.twig', [
            'article' => $article,
        ]);
    }

    /**
     * Method to update an article
     *
     * @throws UnauthorizedArticleEditionException
     */
    #[Route('/article/{id<\d+>}/edit', name: 'article_edit', methods: ['GET', 'POST'])]
    public function edit(Article $article, Request $request): Response
    {
        // We check that the user is logged in
        $currentUser = $this->getUser();
        if (!$currentUser) {
            throw $this->createAccessDeniedException('You must be logged in to edit this article.');
        }

        // We check that the user is the author of the article
        if ($article->getAuthor() !== $currentUser) {
            throw new UnauthorizedArticleEditionException();
        }

        // We get the value of the current title of the article
        $title = $this->articleService->getArticleById($request->get('id'))->getTitle();

        // We create the form
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // If the title changed , we will set a new slug for the article
            // If the title was unchanged, the slug will remain the same
            // And we save the other updates in the database
            $article->getTitle() !== $title ?
                $this->articleService->registerArticleEdited($form->getData(), true) :
                $this->articleService->registerArticleEdited($form->getData(), false) ;


            $this->addFlash('success', 'Article successfully edited !');
            return $this->redirectToRoute('article_index');
        }

        return $this->render('article/edit.html.twig', [
            'form' => $form->createView(),
            'article' => $article
        ]);
    }

    /**
     * Method to delete an article
     *
     * @throws UnauthorizedArticleDeletionException
     */
    #[Route('/article/{id<\d+>}/delete', name: 'article_delete', methods: ['POST'])]
    public function delete(Article $article): Response
    {
        // We check that the user is logged in
        $currentUser = $this->getUser();
        if (!$currentUser) {
            throw $this->createAccessDeniedException('You must be logged in to edit this article.');
        }

        // We check that the user is the author of the article
        if ($article->getAuthor() !== $currentUser) {
            throw new UnauthorizedArticleDeletionException();
        }

        // We delete the article
        $this->articleService->deleteArticle($article);

        $this->addFlash('success', 'Article successfully deleted !');
        
        return $this->redirectToRoute('article_index');
    }


}