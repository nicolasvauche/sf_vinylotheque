<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    #[Route('/{album}', name: 'app_home')]
    public function index($album = null): Response
    {
        return $this->render('default/index.html.twig', [
            'album' => $album ?? null,
        ]);
    }
}
