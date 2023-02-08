<?php

namespace App\DataFixtures;

use App\Entity\Housing;
use App\Repository\CategoryRepository;
use App\Repository\UserRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;

class HousingFixtures extends Fixture implements DependentFixtureInterface
{
    private Generator $faker;

    public function __construct(
        private CategoryRepository $categoryRepository,
        private UserRepository $userRepository
    )
    {
        $this->faker = Factory::create('fr_FR');
    }

    public function getDependencies(): array
    {
        return [
            CategoryFixtures::class,
            UserFixtures::class
        ];
    }

    public function load(ObjectManager $manager): void
    {
        for ($i = 1; $i < 50; $i++) {
            
            $housing = new Housing();

            $housing
                ->setTitle($this->faker->sentence())
                ->setDescription($this->faker->paragraphs(4, true))
                ->setPrice($this->faker->numberBetween(99, 999999))
                ->setRooms($this->faker->numberBetween(2, 12))
                ->setBeds($this->faker->numberBetween(2, 12))
                ->setAvailabilityStart(new \DateTime($this->faker->date()))
                ->setAvailabilityEnd((new \DateTime())->modify('+10 day'))
                ->setCity($this->faker->city)
                ->setRegion($this->faker->region())
                ->setCountry('France')
                ->setIsPublished($this->faker->boolean())
                ->setCategory($this->faker->randomElement($this->categoryRepository->findAll()))
                ->setUser($this->faker->randomElement($this->userRepository->findAll()));

            $manager->persist($housing);
        }

        $manager->flush();
    }
}