<?php

namespace App\DataFixtures;

use App\Entity\Users;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\String\Slugger\SluggerInterface;

class UsersFixtures extends Fixture
{
    private $encoder;
    private $sluger;

    public function __construct(UserPasswordHasherInterface $encoder, SluggerInterface $sluggerInterface)
    {
        $this->encoder = $encoder;
        $this->sluger = $sluggerInterface;
    }

    public function load(ObjectManager $manager)
    {
        for($i = 0; $i <= 10; $i++) {
            $user = new Users();
            $user->setEmail('test@test.fr ' .$i);
            $user->setFirstName('name ' .$i);
            $user->setLastName('lastName ' .$i);
            $user->setIsVerified(true);
            $user->setIsBanned(false);
            $user->setIsDeleted(false);
            $user->setDescription("test_$i");
            $user->setPicture("picture_$i");
            $user->setRgpd(true);
            $user->setPassword($this->encoder->hashPassword($user, "password"));
            $user->setSlug($this->sluger->slug($user->getFirstName())->lower());
            $manager->persist($user);
        }

        $manager->flush();
    }
}