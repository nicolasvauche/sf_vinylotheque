<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/album', name: 'app_album_')]
class AlbumController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(): Response
    {
        return $this->render('album/index.html.twig', [
            'controller_name' => 'AlbumController',
        ]);
    }

    #[Route('/ajouter', name: 'add')]
    public function add(): Response
    {
        return $this->render('album/add.html.twig', [
            'controller_name' => 'AlbumController',
        ]);
    }
}
