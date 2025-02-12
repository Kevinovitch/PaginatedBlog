<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Http\Event\LoginSuccessEvent;
use Symfony\Component\Security\Http\Event\LogoutEvent;

class SecurityController extends AbstractController implements EventSubscriberInterface
{


    private AuthenticationUtils $authenticationUtils;


    public function __construct(
        AuthenticationUtils $authenticationUtils,
    )
    {
        $this->authenticationUtils = $authenticationUtils;
    }

    /**
     * Method to log a user in
     *
     * @return Response
     */
    #[Route('/login', name: 'app_login')]
    public function login(): Response
    {
        // If there is a user already logged in
        // it is redirected to the index
        if ($this->getUser()) {
            return $this->redirectToRoute('article_index');
        }

        // If the last connexion failed, we get the error message
        $error = $this->authenticationUtils->getLastAuthenticationError();

        // The e-mail address entered during the last connection attempt
        // is entered as the default value.
        $lastUsername = $this->authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }

    #[Route('/logout', name: 'app_logout')]
    public function logout(): void
    {
        // The controller will be intercepted by the firewall
    }

    public static function getSubscribedEvents()
    {
        return [
            LoginSuccessEvent::class => 'onLoginSuccess',
            LogoutEvent::class => 'onLogout',
        ];
    }

    /**
     *  Method to send a flash message when the user is
     *  successfully log in
     *
     * @param LoginSuccessEvent $event
     * @return void
     */
    public function onLoginSuccess(LoginSuccessEvent $event): void
    {
        $this->addFlash('success', 'Login success');
    }

    /**
     *  Method to send a flash message when the user is
     *  successfully log out
     *
     * @param LogoutEvent $event
     * @return void
     */
    public function onLogout(LogoutEvent $event): void
    {
        $this->addFlash('success', 'Logout success');
    }
}