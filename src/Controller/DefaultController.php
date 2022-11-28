<?php

namespace App\Controller;

use App\Repository\AlbumRepository;
use App\Repository\UserAlbumRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(UserAlbumRepository $userAlbumRepository): Response
    {
        $albums = [];

        $userAlbums = $userAlbumRepository->findBy(['user' => $this->getUser()]);
        foreach ($userAlbums as $userAlbum) {
            $albums[] = [
                'id' => $userAlbum->getAlbum()->getId(),
                'title' => $userAlbum->getAlbum()->getTitle(),
                'artist' => $userAlbum->getAlbum()->getArtist()->getName(),
                'cover' => $userAlbum->getAlbum()->getCover(),
            ];
        }

        if ($albums) {
            return $this->render('default/index.html.twig', [
                'album' => $albums ? $albums[array_rand($albums, 1)] : null,
            ]);
        } else {
            return $this->redirectToRoute('app_album_search');
        }
    }

    #[Route('/ecouter/{id}', name: 'app_home_listen')]
    public function listen(AlbumRepository $albumRepository, UserAlbumRepository $userAlbumRepository, $id = null): Response
    {
        $album = $albumRepository->find($id);
        if ($album) {
            $userAlbum = $userAlbumRepository->findOneBy(['user' => $this->getUser(), 'album' => $album]);
            if ($userAlbum) {
                $userAlbum->setPlayed($userAlbum->getPlayed() + 1)
                    ->setPlayedAt(new \DateTimeImmutable());
                $userAlbumRepository->save($userAlbum, true);

                $this->addFlash('success', 'On écoute ' . $album->getTitle() . ' !');
            } else {
                $this->addFlash('error', "L'album " . $album->getTitle() . " n'est pas dans ta vinylothèque");
            }
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
