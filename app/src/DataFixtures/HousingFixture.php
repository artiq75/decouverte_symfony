<?php

namespace App\DataFixtures;

use App\Entity\Housing;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class HousingFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        $housing = new Housing();

        for ($i = 0; $i < 10; $i++) {

            $housing
                ->setTitle($faker->sentence())
                ->setDescription($faker->paragraphs(4, true))
                ->setPrice($faker->numberBetween(99, 999999))
                ->setRooms(12)
                ->setBeds(12)
                ->setAvailabilityStart(new \DateTime($faker->date()))
                ->setAvailabilityEnd((new \DateTime())->modify('+10 day'))
                ->setCity('Perpignan')
                ->setRegion('Occitanie')
                ->setCountry('France')
                ->setIsPublished(true);

            $manager->persist($housing);

        }

        $manager->flush();
    }
}