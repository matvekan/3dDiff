<?php

declare(strict_types=1);

namespace App\Infrastructure\Controller\Web;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class AppController extends AbstractController
{
    #[Route('/', name: 'web.index', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('app/index.html.twig');
    }

    #[Route('/app/user', name: 'web.user', methods: ['GET'])]
    public function user(): Response
    {
        return $this->render('app/user.html.twig');
    }

    #[Route('/app/admin', name: 'web.admin', methods: ['GET'])]
    public function admin(): Response
    {
        return $this->render('app/admin.html.twig');
    }

    #[Route('/app/reset-password', name: 'web.reset_password', methods: ['GET'])]
    public function resetPassword(): Response
    {
        return $this->render('app/reset_password.html.twig');
    }
}
