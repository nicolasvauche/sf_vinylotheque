<?php

namespace App\Controller;

use App\Form\DiscogsApiSearchType;
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
    public function add(DiscogsApiService $discogsApiService, $type, $id): Response
    {
        if($type === 'master'){
            $result = $discogsApiService->searchMaster($id);
        }else{
            $result = $discogsApiService->searchRelease($id);
        }

        dd($result);

        return $this->renderForm('album/add.html.twig', [
            'result' => $result ?? null,
        ]);
    }
}
