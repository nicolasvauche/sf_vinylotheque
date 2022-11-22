<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index($album = null): Response
    {
        $albums = [
            0 => [
                'id' => 1,
                'title' => 'Killers',
                'artist' => 'Iron Maiden',
                'cover' => 'album_killers.jpg',
            ],
            1 => [
                'id' => 2,
                'title' => 'Seventh Son Of A Seventh Son',
                'artist' => 'Iron Maiden',
                'cover' => 'album_seventh.jpg',
            ],
            2 => [
                'id' => 3,
                'title' => 'The Kick Inside',
                'artist' => 'Kate Bush',
                'cover' => 'album_kick.jpg',
            ],
            3 => [
                'id' => 4,
                'title' => 'Animals',
                'artist' => 'Pink Floyd',
                'cover' => 'album_animals.jpg',
            ],
            4 => [
                'id' => 5,
                'title' => 'The Wall',
                'artist' => 'Pink Floyd',
                'cover' => 'album_wall.jpg',
            ],
        ];

        return $this->render('default/index.html.twig', [
            'album' => $albums[array_rand($albums, 1)],
        ]);
    }

    #[Route('/ecouter/{id}', name: 'app_home_listen')]
    public function listen($id = null): Response
    {
        $albums = [
            0 => [
                'id' => 1,
                'title' => 'Killers',
                'artist' => 'Iron Maiden',
                'cover' => 'album_killers.jpg',
            ],
            1 => [
                'id' => 2,
                'title' => 'Seventh Son Of A Seventh Son',
                'artist' => 'Iron Maiden',
                'cover' => 'album_seventh.jpg',
            ],
            2 => [
                'id' => 3,
                'title' => 'The Kick Inside',
                'artist' => 'Kate Bush',
                'cover' => 'album_kick.jpg',
            ],
            3 => [
                'id' => 4,
                'title' => 'Animals',
                'artist' => 'Pink Floyd',
                'cover' => 'album_animals.jpg',
            ],
            4 => [
                'id' => 5,
                'title' => 'The Wall',
                'artist' => 'Pink Floyd',
                'cover' => 'album_wall.jpg',
            ],
        ];

        $this->addFlash('success', 'On écoute ' . $albums[$id - 1]['title'] . ' !');

        return $this->render('default/index.html.twig', [
            'album' => $albums[$id - 1],
            'id' => $id,
        ]);
    }
}
