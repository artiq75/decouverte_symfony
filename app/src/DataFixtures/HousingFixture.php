<?php

namespace App\DataFixtures;

use App\Entity\Housing;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;

class HousingFixture extends Fixture
{
    private Generator $faker;

    public function __construct()
    {
        $this->faker = Factory::create('fr_FR');
    }

    public function load(ObjectManager $manager): void
    {
        for ($i = 1; $i < 50; $i++) {
            
            $housing = new Housing();

            $housing
                ->setTitle($this->faker->sentence())
                ->setDescription($this->faker->paragraphs(4, true))
                ->setPrice($this->faker->numberBetween(99, 999999))
                ->setRooms(12)
                ->setBeds(12)
                ->setAvailabilityStart(new \DateTime($this->faker->date()))
                ->setAvailabilityEnd((new \DateTime())->modify('+10 day'))
                ->setCity($this->faker->city)
                ->setRegion($this->faker->region())
                ->setCountry('France')
                ->setIsPublished($this->faker->numberBetween(0, 1));

            $manager->persist($housing);

        }

        $manager->flush();
    }
}