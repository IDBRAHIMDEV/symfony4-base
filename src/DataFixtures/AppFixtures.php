<?php

namespace App\DataFixtures;

use App\Entity\Post;
use App\Entity\User;
use Faker\Factory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{

    public $faker;

    public function __construct()
    {
        $this->faker = Factory::create();
    }

    public function load(ObjectManager $manager)
    {
        $this->loadUser($manager);
        $this->loadPost($manager);
    }

    public function loadUser(ObjectManager $manager) {
        for($i = 0; $i < 10; $i++) {

            $myUser = new User();

            $myUser->setUsername($this->faker->userName);
            $myUser->setName($this->faker->name);
            $myUser->setPassword("secret $i");
            $myUser->setEmail($this->faker->email);

            $manager->persist($myUser);

            $this->addReference("user_$i", $myUser);
        }


        $manager->flush();
    }

    public function loadPost(ObjectManager $manager) {
        for($i = 0; $i < 1000; $i++) {

            $myPost = new Post();
            $title = $this->faker->sentence(4);
            $myPost->setTitle($title);
            $myPost->setSlug(str_replace(' ', '-', $title));
            $myPost->setContent($this->faker->sentence(10));
            $myPost->setPublished($this->faker->dateTime);

            $user = $this->getReference("user_".rand(0, 9));
            $myPost->setAuthor($user);
            $manager->persist($myPost);
        }


        $manager->flush();
    }
}
