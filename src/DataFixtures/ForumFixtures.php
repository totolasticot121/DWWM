<?php

namespace App\DataFixtures;

use App\Entity\ForumTopics;
use App\Entity\ForumCategory;
use App\Entity\ForumComments;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class ForumFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = \Faker\Factory::create('fr_FR');

        $title = ['HTML 5', 'CSS 3', 'Javascript', 'PHP', 'MySQL', 'Angular', 'Symfony', 'Wordpress'];

        for($k = 0; $k < 8; $k++)
        {
            $category = new ForumCategory;

            $category->setTitle($title[$k]);

            $manager->persist($category);

            for($i = 0; $i < 8; $i++)
            {
                $forumTopic = new ForumTopics;

                $content = '<p>' . join($faker->paragraphs(5), '</p><p>') . '</p>';

                $forumTopic->setAuthor($faker->userName)
                        ->setTitle($faker->sentence())
                        ->setContent($content)
                        ->setForumCategory($category)
                        ->setCreatedAt($faker->dateTimeBetween('-6 months'));
                
                $manager->persist($forumTopic);

                for($j = 0; $j < 2; $j++)
                {
                    $comment = new ForumComments;

                    $content = '<p>' . join($faker->paragraphs(2), '</p><p>') . '</p>';

                    $comment->setAuthor($faker->userName)
                            ->setContent($content)
                            ->setForumTopic($forumTopic)
                            ->setCreatedAt($faker->dateTimeBetween('-6 months'));

                    $manager->persist($comment);
                }
            }

            $manager->flush();

        }
    }
}
