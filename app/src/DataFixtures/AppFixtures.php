<?php

namespace App\DataFixtures;

use App\Entity\Task;
use App\Entity\User;
use App\Enum\Task\TaskStatus;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for ($u = 1; $u <= 3; ++$u) {
            $user = new User();
            $user->setUsername('test'.$u);
            $user->setRoles(['ROLE_USER']);
            $manager->persist($user);

            for ($t = 1; $t <= 3; ++$t) {
                $task = new Task();
                $task->setStatus(0 == $t % 2 ? TaskStatus::DONE : TaskStatus::TODO);
                $task->setUser($user);
                $task->setDescription("Test task description $t for user $u");
                $task->setTitle("Test task $t for user $u");
                $task->setPriority(random_int(1, 5));
                $manager->persist($task);

                $this->createChildrenTasks($manager, $task, 3);
                $manager->flush();
            }

            $manager->flush();
        }
    }

    private function createChildrenTasks(ObjectManager $manager, Task $parentTask, int $depth): void
    {
        if ($depth <= 0) {
            return;
        }

        $numChildren = random_int(3, 5);
        for ($i = 1; $i <= $numChildren; ++$i) {
            $childTask = new Task();
            $childTask->setStatus(0 == $i % 2 ? TaskStatus::DONE : TaskStatus::TODO);
            $childTask->setUser($parentTask->getUser());
            $childTask->setDescription("Child task description $i for parent {$parentTask->getTitle()}");
            $childTask->setTitle("Child task $i for parent {$parentTask->getTitle()}");
            $childTask->setPriority(random_int(1, 5));
            $childTask->setParent($parentTask);
            $manager->persist($childTask);

            $parentTask->addChildren($childTask);

            $this->createChildrenTasks($manager, $childTask, $depth - 1);
        }
    }
}
