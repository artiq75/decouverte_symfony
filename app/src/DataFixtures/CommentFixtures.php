<?php

namespace App\DataFixtures;

use App\Entity\Comment;
use App\Repository\HousingRepository;
use App\Repository\UserRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;

class CommentFixtures extends Fixture implements DependentFixtureInterface
{
    private Generator $faker;

    public function __construct(
        private UserRepository $userRepository,
        private HousingRepository $housingRepository
    )
    {
        $this->faker = Factory::create();
    }
    
    public function getDependencies(): array
    {
        return [
            HousingFixtures::class,
            UserFixtures::class
        ];
    }

    public function load(ObjectManager $manager): void
    {
        for ($i=0; $i < 100; $i++) { 
            $comment = new Comment();
            
            $comment
                ->setMessage($this->faker->paragraph())
                ->setUser($this->faker->randomElement($this->userRepository->findAll()))
                ->setHousing($this->faker->randomElement($this->housingRepository->findAll()));

            $manager->persist($comment);
        }

        $manager->flush();
    }
}
