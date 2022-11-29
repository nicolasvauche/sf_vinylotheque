<?php

namespace App\Controller;

use App\Entity\Album;
use App\Entity\Artist;
use App\Entity\UserAlbum;
use App\Form\AlbumType;
use App\Form\DiscogsApiSearchType;
use App\Repository\AlbumRepository;
use App\Repository\ArtistRepository;
use App\Repository\UserAlbumRepository;
use App\Service\DiscogsApiService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Validator\ValidatorInterface;

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
    public function add(DiscogsApiService $discogsApiService, ArtistRepository $artistRepository, AlbumRepository $albumRepository, UserAlbumRepository $userAlbumRepository, SluggerInterface $slugger, $type, $id): Response
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
                // Make slug for image
                $info = getimagesize($result['cover']);
                $extension = image_type_to_extension($info[2]);
                $filename = $slugger->slug(strtolower($artist->getName()) . '-' . strtolower($result['title'])) . $extension;

                // Save image file
                if ($result['cover']) {
                    $content = file_get_contents($result['cover']);
                    $fp = fopen($this->getParameter('kernel.project_dir') . "/public/img/album/$filename", "w");
                    fwrite($fp, $content);
                    fclose($fp);
                }

                $album = new Album();
                $album->setDiscogsId($result['discogsId'])
                    ->setTitle($result['title'])
                    ->setYear($result['year'])
                    ->setCover($filename)
                    ->setArtist($artist);
                $albumRepository->save($album, true);

                $userAlbum = new UserAlbum();
                $userAlbum->setUser($this->getUser())
                    ->setAlbum($album)
                    ->setPlayed(0)
                    ->setFavorite(false);
                $userAlbumRepository->save($userAlbum, true);

                $this->addFlash('success', "L'album {$album->getTitle()} a été ajouté à ta vinylothèque !");
            }
        }

        return $this->redirectToRoute('app_album_search');
    }

    #[Route('/modifier/{id}', name: 'edit')]
    public function edit(Request $request, Album $album, AlbumRepository $albumRepository, ValidatorInterface $validator): Response
    {
        $form = $this->createForm(AlbumType::class, $album);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $coverFile */
            $coverFile = $form->get('cover')->getData();

            if ($coverFile) {
                $safeFilename = $album->getSlug();
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $coverFile->guessExtension();

                try {
                    $coverFile->move(
                        $this->getParameter('album_cover_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    dd($e);
                }

                $album->setCover($newFilename);
            }

            $albumRepository->save($album, true);

            $this->addFlash('success', "L'album {$album->getTitle()} a été modifié");
        } else if ($form->isSubmitted() && !$form->isValid()) {
            $coverFile = $form->get('cover')->getData();

            if ($coverFile) {
                $violations = $validator->validate(
                    $coverFile,
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                            'image/webp',
                        ],
                        'mimeTypesMessage' => 'Choisis une image valide stp',
                    ])
                );
            }
        }

        return $this->renderForm('album/edit.html.twig', [
            'form' => $form,
            'album' => $album,
        ]);
    }
}
