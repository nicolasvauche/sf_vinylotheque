<?php

namespace App\Controller;

use App\Form\UserType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    #[Route('/mon-profil', name: 'app_user_profile')]
    public function profile(Request $request, UserRepository $userRepository): Response
    {
        $form = $this->createForm(UserType::class, $this->getUser());
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();
            if (!empty($form->get('password')->getData())) {
                $user->setPassword($this->hasher->hashPassword($user, $form->get('password')->getData()));
            }
            $userRepository->save($user, true);

            $this->addFlash('success', 'Ton profil a été modifié');
        }

        return $this->renderForm('user/profile.html.twig', [
            'form' => $form,
        ]);
    }
}
