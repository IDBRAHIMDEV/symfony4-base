<?php

namespace App\DataFixtures;

use App\Entity\Comment;
use App\Entity\Post;
use App\Entity\User;
use Faker\Factory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{

    public $faker;
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->faker = Factory::create();
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $this->loadUser($manager);
        $this->loadPost($manager);
        $this->loadComment($manager);
    }

    public function loadUser(ObjectManager $manager) {
        for($i = 0; $i < 10; $i++) {

            $myUser = new User();

            $myUser->setUsername($this->faker->userName);
            $myUser->setName($this->faker->name);
            $myUser->setPassword($this->passwordEncoder->encodePassword(
                $myUser,
                'secret'
            ));

            $myUser->setEmail($this->faker->email);

            $roles = ['ROLE_COMMENTATOR', 'ROLE_WRITER', 'ROLE_EDITOR', 'ROLE_ADMIN'];

            $myUser->setRoles([$roles[rand(0, 3)]]);

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
            $this->addReference("post_$i", $myPost);
        }


        $manager->flush();
    }


    public function loadComment(ObjectManager $manager) {
        for($i = 0; $i < 10000; $i++) {

            $myComment = new Comment();

            $myComment->setContent($this->faker->sentence(20));
            $user = $this->getReference("user_".rand(0, 9));
            $post = $this->getReference("post_".rand(0, 999));

            $myComment->setAuthor($user);
            $myComment->setPost($post);
            $manager->persist($myComment);
        }


        $manager->flush();
    }
}
