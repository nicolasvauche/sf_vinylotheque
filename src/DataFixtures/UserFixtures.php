<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture implements OrderedFixtureInterface
{
    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager): void
    {
        $admin = new User();
        $admin->setPseudo('Admin')
            ->setEmail('admin@admin.com')
            ->setPassword($this->hasher->hashPassword($admin, 'admin'))
            ->setRoles(['ROLE_ADMIN'])
            ->setActive(true);
        $manager->persist($admin);

        $user = new User();
        $user->setPseudo('Nicolas')
            ->setEmail('nicolas@user.com')
            ->setPassword($this->hasher->hashPassword($user, 'nicolas'))
            ->setActive(true)
        ->setCurrentMood('fa-face-angry');
        $manager->persist($user);

        $user = new User();
        $user->setPseudo('Sophie')
            ->setEmail('sophie@user.com')
            ->setPassword($this->hasher->hashPassword($user, 'sophie'))
            ->setActive(true)
            ->setCurrentMood('fa-face-grin-stars');
        $manager->persist($user);

        $user = new User();
        $user->setPseudo('Cédric')
            ->setEmail('cedric@user.com')
            ->setPassword($this->hasher->hashPassword($user, 'cedric'))
            ->setActive(true)
            ->setCurrentMood('fa-face-smile');
        $manager->persist($user);

        $manager->flush();
    }

    public function getOrder()
    {
        return 1;
    }
}
