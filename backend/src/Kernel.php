<?php

namespace App;

use App\Exception\UnauthorizedArticleDeletionException;
use App\Exception\UnauthorizedArticleEditionException;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class Kernel extends BaseKernel
{
    use MicroKernelTrait;

    private ?Environment $twig;

    public function __construct(string $environment, bool $debug, Environment $twig = null)
    {
        parent::__construct($environment, $debug);
        $this->twig = $twig;
    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     */
    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        if ($exception instanceof UnauthorizedArticleEditionException || $exception instanceof UnauthorizedArticleDeletionException) {
            $content = $this->twig->render('error/unauthorized.html.twig', [
                'message' => $exception->getMessage()
            ]);

            $response = new Response($content, Response::HTTP_FORBIDDEN);
            $event->setResponse($response);
        }
    }
}
