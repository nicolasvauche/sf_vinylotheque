<?php

namespace App\Controller;

use App\Entity\Album;
use App\Entity\Artist;
use App\Form\DiscogsApiSearchType;
use App\Repository\AlbumRepository;
use App\Repository\ArtistRepository;
use App\Service\DiscogsApiService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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

    #[Route('/rechercher', name: 'search')]
    public function search(Request $request, DiscogsApiService $discogsApiService): Response
    {
        $form = $this->createForm(DiscogsApiSearchType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $results = $discogsApiService->search(
                $form->get('artistName')->getData(),
                $form->get('albumName')->getData()
            );
        }

        return $this->renderForm('album/add.html.twig', [
            'form' => $form,
            'results' => $results ?? null,
        ]);
    }

    #[Route('/ajouter/{type}/{id}', name: 'add')]
    public function add(DiscogsApiService $discogsApiService, ArtistRepository $artistRepository, AlbumRepository $albumRepository, $type, $id): Response
    {
        if ($type === 'master') {
            $result = $discogsApiService->searchMaster($id);
        } else {
            $result = $discogsApiService->searchRelease($id);
        }

        if ($result) {
            $artist = $artistRepository->findOneBy(['discogsId' => $result['artist']['discogsId']]);
            if (!$artist) {
                $artist = new Artist();
                $artist->setDiscogsId($result['artist']['discogsId'])
                    ->setName($result['artist']['name'])
                    ->setCover($result['artist']['cover']);
                $artistRepository->save($artist, true);
            }

            $album = $albumRepository->findOneBy(['discogsId' => $result['discogsId']]);
            if (!$album) {
                $album = new Album();
                $album->setDiscogsId($result['discogsId'])
                    ->setTitle($result['title'])
                    ->setYear($result['year'])
                    ->setCover($result['cover'])
                    ->setArtist($artist);
                $albumRepository->save($album, true);

                $this->addFlash('success', "L'album {$album->getTitle()} a été ajouté à ta vinylothèque !");
            }
        }

        return $this->redirectToRoute('app_home');
    }
}
