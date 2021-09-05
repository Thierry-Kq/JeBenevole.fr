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
        $users = [
            'Re' => 'Naissance',
            'Kass' => 'Kq',
            'Anass' => 'Anass',
            'Nox' => 'Nox',
        ];

        foreach ($users as $key => $value) {
            $user = new Users();
            $user->setEmail($value . '@gmail.com');
            $user->setFirstName($key);
            $user->setLastName($value);
            $user->setIsVerified(true);
            $description = ($key === 'Kass') ? 'Si vous avez des questions, c\'est à lui qu\'il faut demander' : 'Description de l\'utilisateur ' . $key . ' ' . $value;
            $user->setDescription($description);
            $user->setPicture('image.com');
            $user->setRgpd(true);
            $user->setPassword($this->encoder->hashPassword($user, "azerty"));
            $user->setSlug($this->sluger->slug($user->getFirstName())->lower());
            $user->setRoles(['ROLE_ADMIN']);

            $manager->persist($user);
        }

        // users for Associations creation
        for ($i = 0; $i < 10; $i++) {
            $user = new Users();
            $user->setEmail('user' . $i . '@gmail.com');
            $user->setFirstName('User' . $i);
            $user->setLastName('lastName' . $i);
            $user->setIsVerified(true);
            $user->setDescription('Description de l\'utilisateur ' . $user->getFirstName());
            $user->setPicture('image.com');
            $user->setRgpd(true);
            $user->setPassword($this->encoder->hashPassword($user, "azerty"));
            $user->setSlug($this->sluger->slug($user->getFirstName())->lower());

            $manager->persist($user);

            $this->addReference('user' . $i, $user);
        }

        // users for Offers creation
        for ($i = 10; $i < 20; $i++) {
            $user = new Users();
            $user->setEmail('user' . $i . '@gmail.com');
            $user->setFirstName('User' . $i);
            $user->setLastName('lastName' . $i);
            $user->setIsVerified(true);
            $user->setDescription('Description de l\'utilisateur ' . $user->getFirstName());
            $user->setPicture('image.com');
            $user->setRgpd(true);
            $user->setPassword($this->encoder->hashPassword($user, "azerty"));
            $user->setSlug($this->sluger->slug($user->getFirstName())->lower());

            $manager->persist($user);

            $this->addReference('user' . $i, $user);
        }

        $manager->flush();
    }
}
