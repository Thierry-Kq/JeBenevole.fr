<?php

namespace App\DataFixtures;

use App\Entity\Offers;
use DateTime;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\String\Slugger\SluggerInterface;

class OffersFixtures extends Fixture implements DependentFixtureInterface
{
    private $slugger;

    public function __construct(SluggerInterface $sluggerInterface)
    {

        $this->slugger = $sluggerInterface;
    }

    public function load(ObjectManager $manager)
    {
        // Offers for Associations
        for ($i = 0; $i < 10; $i++) {
            $offers = new Offers();
            $offers->setTitle('titre ' . $i);
            $offers->setAddress('adress ' . $i);
            $offers->setZip(1);
            $offers->setCity('city ' . $i);
            $offers->setLongitude(0.1);
            $offers->setLatitude(0.2);
            $offers->setIsPublished(true);
            $offers->setIsActived(true);
            $offers->setDateStart(new DateTimeImmutable());
            $offers->setDateEnd(new DateTime());
            $offers->setFile('file ' . $i);
            $offers->setRecommended(true);
            $offers->setContactExternalName('contactExternal ' . $i);
            $offers->setContactExternalEmail('externalEmail ' . $i);
            $offers->setContactExternalTel('externalContactTel' . $i);
            $offers->setSlug($this->slugger->slug($offers->getTitle())->lower());
            $offers->setDescription('description ' . $i);
            $offers->setAssociations($this->getReference('association' . $i));
            $offers->setCategories($this->getReference('category' . random_int(0, 6)));

            $manager->persist($offers);
        }

        // Offers for Users
        for ($i = 10; $i < 20; $i++) {
            $offers = new Offers();
            $offers->setTitle('titre ' . $i);
            $offers->setAddress('adress ' . $i);
            $offers->setZip(1);
            $offers->setCity('city ' . $i);
            $offers->setLongitude(0.1);
            $offers->setLatitude(0.2);
            $offers->setIsPublished(true);
            $offers->setIsActived(true);
            $offers->setDateStart(new DateTimeImmutable());
            $offers->setDateEnd(new DateTime());
            $offers->setFile('file ' . $i);
            $offers->setRecommended(true);
            $offers->setContactExternalName('contactExternal ' . $i);
            $offers->setContactExternalEmail('externalEmail ' . $i);
            $offers->setContactExternalTel('externalContactTel' . $i);
            $offers->setSlug($this->slugger->slug($offers->getTitle())->lower());
            $offers->setDescription('description ' . $i);
            $offers->setUsers($this->getReference('user' . $i));
            $offers->setCategories($this->getReference('category' . random_int(0, 6)));

            $manager->persist($offers);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return
            [
                UsersFixtures::class,
                AssociationsFixtures::class,
                CategoriesFixtures::class,
            ];
    }
}
