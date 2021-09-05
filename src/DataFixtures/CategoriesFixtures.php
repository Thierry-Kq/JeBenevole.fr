<?php

namespace App\DataFixtures;

use App\Entity\Categories;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\String\Slugger\SluggerInterface;

class CategoriesFixtures extends Fixture
{
    private $slugger;

    public function __construct(SluggerInterface $sluggerInterface)
    {

        $this->slugger = $sluggerInterface;
    }

    public function load(ObjectManager $manager)
    {

        $colors = ['red', 'blue', 'green'];
        $categoriesLabel = [
            'Travail physique' => ['Déménagement'],
            'Social' => ['Récolte de dons'],
            'Divers' => ['Distribution de flyers', 'Catégorie désactivée'],
        ];

        $count = 0;
        foreach ($categoriesLabel as $parent => $children) {
            $categoriesParent = new Categories();
            $categoriesParent->setName($parent);
            $categoriesParent->setIsActived(true);
            $categoriesParent->setColor($colors[array_rand($colors, 1)]);
            $categoriesParent->setSlug($this->slugger->slug($categoriesParent->getName())->lower());
            $categoriesParent->setDescription('Categorie parent : ' . $parent);
            $categoriesParent->setPicture('picture.com');

            $this->addReference('category' . $count, $categoriesParent);
            $count++;

            $manager->persist($categoriesParent);

            foreach ($children as $child) {
                $categories = new Categories();
                $categories->setName($child);
                $categories->setIsActived(true);
                $categories->setColor($colors[array_rand($colors, 1)]);
                $categories->setParent($categoriesParent);
                $categories->setSlug($this->slugger->slug($categories->getName())->lower());
                $categories->setDescription('Categorie enfant :' . $child);
                $categories->setPicture('picture.com');

                $this->addReference('category' . $count, $categories);
                $count++;

                $manager->persist($categories);
            }
        }

        $manager->flush();
    }
}
