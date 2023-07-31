<?php

namespace EasyCorp\Bundle\EasyAdminBundle\Tests\TestApplication\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use EasyCorp\Bundle\EasyAdminBundle\Tests\TestApplication\Entity\BlogPost;
use EasyCorp\Bundle\EasyAdminBundle\Tests\TestApplication\Entity\Category;
use EasyCorp\Bundle\EasyAdminBundle\Tests\TestApplication\Entity\User;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i < 30; ++$i) {
            $category = (new Category())
                ->setName('Category '.$i)
                ->setSlug('category-'.$i);

            $this->addReference('category'.$i, $category);
            $manager->persist($category);
        }

        for ($i = 0; $i < 5; ++$i) {
            $user = (new User())
                ->setName('User '.$i)
                ->setEmail('user'.$i.'@example.com');

            $this->addReference('user'.$i, $user);
            $manager->persist($user);
        }

        /**
         * Randomly generated.
         */
        $categoryIndex = 0;
        $categoryMapping = [
            9,  3, 28, 28, 15, 26, 25, 10, 11, 18, 25, 29,
            19, 15, 19, 21,  6, 18,  2, 15,  4, 11,  8, 20,
            24,  4, 28, 24, 17, 15,  9,  3, 20,  7,  6, 24,
            18, 12,  6, 22, 10,  5, 11,  7,  3, 25, 19, 25,
            10,  8, 16,  4, 11, 12, 26, 14, 11, 16,  0, 25,
            23, 29,  4, 24,  5, 18, 19, 26,  2,  1,  9,  8,
            21, 19,  0, 27,  8, 18,  3,  3,  6, 17, 18, 12,
            15, 14, 25,  2, 26,  0, 13, 21,  9,  0, 18, 27,
            11,  0, 29,  3,
        ];

        $categoryAmount = [
            3, 0, 0, 5, 2, 6, 5,
            5, 2, 3, 6, 3, 3, 0,
            6, 6, 1, 2, 2, 2,
        ];

        $authorMapping = [
            1, 3, 2, 3, 4, 1, 2,
            1, 1, 2, 3, 4, 1, 4,
            2, 4, 0, 4, 0, 0,
        ];

        for ($i = 0; $i < 20; ++$i) {
            $blogPost = (new BlogPost())
                ->setTitle('Blog Post '.$i)
                ->setSlug('blog-post-'.$i)
                ->setContent('Lorem Ipsum Dolor Sit Amet.')
                ->setCreatedAt(new \DateTime('2020-11-'.($i + 1).' 09:00:00'))
                ->setPublishedAt(new \DateTimeImmutable('2020-11-'.($i + 1).' 11:00:00'))
                ->setAuthor($this->getReference('user'.$authorMapping[$i], User::class));

            for ($j = 0; $j < $categoryAmount[$i]; ++$j) {
                $blogPost->addCategory($this->getReference('category'.$categoryMapping[$categoryIndex++], Category::class));
            }

            if ($i < 10) {
                $blogPost->setPublisher(
                    $this->getReference('user'.(($i + 1) % 5), User::class)
                );
            }

            $manager->persist($blogPost);
        }

        $manager->flush();
    }
}
