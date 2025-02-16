<?php

namespace App\DataFixtures;

use App\Entity\Departments;
use App\Entity\Regions;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function getDependencies(): array
    {
        return [
            RegionFixtures::class,
        ];
    }

    public function load(ObjectManager $manager): void
    {
        /* Adding an admin user */
        $user = new User();
        $user->setUsername('admin');
        $user->setPassword($this->passwordHasher->hashPassword($user, 'admin'));
        $user->setRoles(['ROLE_ADMIN']);
        $manager->persist($user);

        /* Adding a normal user */
        $user = new User();
        $user->setUsername('user');
        $user->setPassword($this->passwordHasher->hashPassword($user, 'user'));
        $user->setRoles(['ROLE_USER']);
        $user->setRegion($this->getReference('region-93', Regions::class));
        $user->setDepartment($this->getReference('department-13', Departments::class));
        $manager->persist($user);

        $manager->flush();
    }
}
