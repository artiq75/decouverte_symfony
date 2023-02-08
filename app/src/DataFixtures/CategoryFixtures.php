<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;

class CategoryFixtures extends Fixture
{
    private Generator $faker;

    private array $categories;

    public function __construct()
    {
        $this->faker = Factory::create('fr_FR');
        $this->categories = [
            'Borde de mer',
            'Piscines',
            'Patrimoine',
            'Lacs',
            'Au pied des pistes',
            'Luxe',
            'Grandes demeures',
            'Campagne'
        ];
    }

    public function load(ObjectManager $manager): void
    {
        foreach ($this->categories as $categoryName) {
            $category = new Category();
            $category->setName($categoryName);
            $manager->persist($category);
        }

        $manager->flush();
    }
}
