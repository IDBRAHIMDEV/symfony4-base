<?php

namespace App\DataFixtures;

use App\Entity\Post;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {

        for($i = 0; $i < 100; $i++) {

            $myPost = new Post();

            $myPost->setTitle("Title n° $i");
            $myPost->setSlug("Slug-n-$i");
            $myPost->setContent("Content n° $i");
            $myPost->setAuthor("Mohamed IDBRAHIM $i");
            $myPost->setPublished(new \DateTime());

            $manager->persist($myPost);
        }


        $manager->flush();
    }
}
