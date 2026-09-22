<?php

namespace App\DataFixtures;

use App\Factory\ProjectFactory;
use App\Factory\TaskFactory;
use App\Factory\UserFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
         $users = UserFactory::createMany(9);
         $projects =ProjectFactory::createMany(3);
         
        foreach ($users as $key => $user) {
            $projects[$key % count($projects)]->addUser($user);
        }

        $tasks =TaskFactory::new()
            ->many(12)
            ->create(fn () => [
                'project' => ProjectFactory::random(),
            ]);
    }
}
