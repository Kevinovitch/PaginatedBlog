<?php

namespace App\Service;

use App\Entity\Article;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

class ArticleService
{

    private EntityManagerInterface $entityManager;
    private PaginatorInterface $paginator;
    private SluggerInterface $slugger;


    public function __construct(
        EntityManagerInterface $entityManager,
        PaginatorInterface $paginator,
        SluggerInterface $slugger
    )
    {
        $this->entityManager = $entityManager;
        $this->paginator = $paginator;
        $this->slugger = $slugger;
    }

    /**
     * Method to get an article by its id
     *
     * @param $id
     * @return Article|mixed|object|null
     */
    public function getArticleById($id)
    {
        return $this->entityManager->getRepository(Article::class)->find($id);
    }

    /**
     * Method to get an article by its slug
     *
     * @param $slug
     * @return Article|mixed|object|null
     */
    public function getArticleBySlug($slug)
    {
        return $this->entityManager->getRepository(Article::class)->findOneBy(["slug" => $slug]);
    }

    /**
     * Method to return the query to fetch all the articles
     * descending order in order to apply pagination
     *
     * @return Query
     */
    public function getArticlesSortedByOrderDesc()
    {
        return $this->entityManager->getRepository(Article::class)->getArticlesSortedByOrderDesc();
    }

    /**
     * Method to paginate the articles
     *
     * @param int $page
     * @param int $limit
     * @return PaginationInterface
     */
    public function getPaginatedArticles(int $page = 1, int $limit = 10)
    {
        return $this->paginator->paginate($this->getArticlesSortedByOrderDesc(), $page, $limit);
    }

    /**
     * Method to handle the registration of a new article in
     * the database
     *
     * @param Article $article
     * @param User $user
     * @return void
     */
    public function registerArticle(Article $article, User $user)
    {
        $article->setAuthor($user);
        $article->setSlug($this->slugger->slug($article->getTitle())->lower());

        $this->entityManager->persist($article);
        $this->entityManager->flush();
    }

    /**
     * Method to handle the registration of the updates
     * in an article in the database
     *
     * @param Article $article
     * @param bool $titleEdited
     * @return void
     */
    public function registerArticleEdited(Article $article, bool $titleEdited)
    {
        if($titleEdited) {
            $article->setSlug($this->slugger->slug($article->getTitle())->lower());
        }
        $this->entityManager->persist($article);
        $this->entityManager->flush();
    }

    /**
     * Method to handle the deletion of
     * an article in the database
     *
     * @param Article $article
     * @return void
     */
    public function deleteArticle(Article $article)
    {
        $this->entityManager->remove($article);
        $this->entityManager->flush();
    }

}