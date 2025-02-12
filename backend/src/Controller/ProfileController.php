<?php

namespace App\Controller;

use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class ProfileController extends AbstractController
{

    private UserService $userService;

    public function __construct(
        UserService $userService
    )
    {
        $this->userService = $userService;
    }

    /**
     * Method to display the profile of a user
     *
     * @return Response
     */
    #[Route('/profile', name: 'app_profile')]
    #[IsGranted('ROLE_USER')]
    public function index(): Response
    {
        $user = $this->userService->getUserByEmail($this->getUser()->getUserIdentifier());
        $articles = $user->getArticles();

        return $this->render('profile/index.html.twig', [
            'user' => $user,
            'articles' => $articles,
            'articlesCount' => count($articles)
        ]);
    }
}