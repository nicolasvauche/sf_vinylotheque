<?php

namespace App\Controller;

use App\Repository\AlbumRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(AlbumRepository $albumRepository): Response
    {
        $albums = [];
        foreach ($albumRepository->findAll() as $album) {
            $albums[] = [
                'id' => $album->getId(),
                'title' => $album->getTitle(),
                'artist' => $album->getArtist()->getName(),
                'cover' => $album->getCover(),
            ];
        }

        return $this->render('default/index.html.twig', [
            'album' => $albums ? $albums[array_rand($albums, 1)] : null,
        ]);
    }

    #[Route('/ecouter/{id}', name: 'app_home_listen')]
    public function listen(AlbumRepository $albumRepository, $id = null): Response
    {
        $album = $albumRepository->find($id);
        if ($album) {
            $this->addFlash('success', 'On écoute ' . $album->getTitle() . ' !');
        }

        return $this->render('default/index.html.twig', [
            'album' => [
                'id' => $album->getId(),
                'title' => $album->getTitle(),
                'artist' => $album->getArtist()->getName(),
                'cover' => $album->getCover(),
            ],
            'id' => $id,
        ]);
    }
}
