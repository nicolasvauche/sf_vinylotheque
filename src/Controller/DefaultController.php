<?php

namespace App\Controller;

use App\Entity\Album;
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
                'year' => $userAlbum->getAlbum()->getYear(),
                'favorite' => $userAlbum->isFavorite(),
            ];
        }

        if ($albums) {
            $album = $albums[array_rand($albums, 1)];

            return $this->render('default/index.html.twig', [
                'album' => $album,
                'userAlbums' => $this->getUser()->getUserAlbums(),
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
                'year' => $album->getYear(),
                'favorite' => $userAlbum->isFavorite(),
            ],
            'id' => $id,
            'userAlbums' => $this->getUser()->getUserAlbums(),
        ]);
    }

    #[Route('/like/{id}', name: 'app_home_like')]
    public function like(UserAlbumRepository $userAlbumRepository, Album $album)
    {
        $userAlbum = $userAlbumRepository->findOneBy(['user' => $this->getUser(), 'album' => $album]);
        if ($userAlbum) {
            if ($userAlbum->isFavorite()) {
                $userAlbum->setFavorite(false);
                $userAlbumRepository->save($userAlbum, true);

                return $this->json('unlike');
            } else {
                $userAlbum->setFavorite(true);
                $userAlbumRepository->save($userAlbum, true);

                return $this->json('like');
            }
        }
    }
}
