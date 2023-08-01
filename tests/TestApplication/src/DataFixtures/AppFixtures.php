<?php

namespace EasyCorp\Bundle\EasyAdminBundle\Tests\TestApplication\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use EasyCorp\Bundle\EasyAdminBundle\Tests\TestApplication\Entity\AssociationBar;
use EasyCorp\Bundle\EasyAdminBundle\Tests\TestApplication\Entity\AssociationBaz;
use EasyCorp\Bundle\EasyAdminBundle\Tests\TestApplication\Entity\AssociationFizz;
use EasyCorp\Bundle\EasyAdminBundle\Tests\TestApplication\Entity\AssociationFoo;
use EasyCorp\Bundle\EasyAdminBundle\Tests\TestApplication\Entity\AssociationIpsum;
use EasyCorp\Bundle\EasyAdminBundle\Tests\TestApplication\Entity\AssociationLorem;
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

        for ($i = 0; $i < 20; ++$i) {
            $blogPost = (new BlogPost())
                ->setTitle('Blog Post '.$i)
                ->setSlug('blog-post-'.$i)
                ->setContent('Lorem Ipsum Dolor Sit Amet.')
                ->setCreatedAt(new \DateTime('2020-11-'.($i + 1).' 09:00:00'))
                ->setPublishedAt(new \DateTimeImmutable('2020-11-'.($i + 1).' 11:00:00'))
                ->addCategory($this->getReference('category'.($i % 10), Category::class))
                ->setAuthor($this->getReference('user'.($i % 5), User::class));

            if ($i < 10) {
                $blogPost->setPublisher(
                    $this->getReference('user'.(($i + 1) % 5), User::class)
                );
            }

            $manager->persist($blogPost);
        }

        $this->addAssociationFixtures($manager);

        $manager->flush();
    }

    private function addAssociationFixtures(ObjectManager $manager)
    {
        // AssociationFoo <-Many-To-Many-> AssociationBar

        // Add 10 AssociationFoo
        for ($i = 0; $i < 10; ++$i) {
            $associationFoo = (new AssociationFoo())
                ->setName('AssociationFoo '.$i);

            $this->addReference('associationFoo'.$i, $associationFoo);
            $manager->persist($associationFoo);
        }

        // Pregenerated random amount of elements for each AssociationFoo
        $manyToManyAmount = [3, 0, 0, 1, 4, 0, 2, 2, 1, 1];

        // Amount of elements is the sum of the manyToManyAmount array (Generation was implemented in a way that does not include duplicates)
        $manyToManyMapping = [6, 5, 6, 3, 1, 7, 2, 9, 4, 6, 2, 6, 1, 4, 0, 2, 4, 1, 6, 0, 9, 6, 7, 9, 8, 5, 2, 4, 9, 4, 0, 8, 7, 1, 3, 2, 1, 8, 4, 9, 7, 5, 3, 0, 6];
        $manyToManyIndex = 0;

        // Add 10 AssociationBar
        for ($i = 0; $i < 10; ++$i) {
            $associationBar = (new AssociationBar())
                ->setName('AssociationBar '.$i);

            $amount = $manyToManyAmount[$i];

            if ($amount > 0) {
                for ($j = 0; $j < $amount; ++$j) {
                    $associationBar->addAssociationFoo(
                        $this->getReference('associationFoo'.$manyToManyMapping[$manyToManyIndex++], AssociationFoo::class)
                    );
                }
            }

            $this->addReference('associationBar'.$i, $associationBar);
            $manager->persist($associationBar);
        }

        // AssociationFizz <-Many-To-One-> AssociationBaz

        // Add 10 AssociationFizz
        for ($i = 0; $i < 10; ++$i) {
            $associationFizz = (new AssociationFizz())
                ->setName('AssociationFizz '.$i);

            $this->addReference('associationFizz'.$i, $associationFizz);
            $manager->persist($associationFizz);
        }

        // Pregenerated random amount of elements for each AssociationBaz
        $oneToManyAmount = [4, 1, 2, 5, 4, 4, 0, 4, 4, 3];

        // Amount of elements is the sum of the oneToManyAmount array (Generation was implemented in a way that does not include duplicates)
        $oneToManyMapping = [3, 8, 0, 7, 4, 0, 5, 2, 0, 1, 0, 8, 2, 4, 3, 3, 2, 5, 4, 7, 9, 2, 0, 1, 9, 6, 8, 5, 2, 9, 5, 0, 6, 1, 3, 8, 6, 9, 2, 0, 4, 8, 3, 7, 1];
        $oneToManyIndex = 0;

        // Add 10 AssociationBaz
        for ($i = 0; $i < 10; ++$i) {
            $associationBaz = (new AssociationBaz())
                ->setName('AssociationBaz '.$i);

            $amount = $oneToManyAmount[$i];

            if ($amount > 0) {
                for ($j = 0; $j < $amount; ++$j) {
                    $associationBaz->addAssociationFizz(
                        $this->getReference('associationFizz'.$oneToManyMapping[$oneToManyIndex++], AssociationFizz::class)
                    );
                }
            }

            $this->addReference('associationBaz'.$i, $associationBaz);
            $manager->persist($associationBaz);
        }

        // AssociationLorem <-One-To-One-> AssociationIpsum

        // Add 10 AssociationLorem
        for ($i = 0; $i < 10; ++$i) {
            $associationLorem = (new AssociationLorem())
                ->setName('AssociationLorem '.$i);

            $this->addReference('associationLorem'.$i, $associationLorem);

            $manager->persist($associationLorem);
        }

        $oneToOneMapping = [7, 5, 1, 6, 4, 3];

        // Add 6 AssociationIpsum
        for ($i = 0; $i < 6; ++$i) {
            $associationIpsum = (new AssociationIpsum())
                ->setName('AssociationIpsum '.$i);

            $lorem = $this->getReference('associationLorem'.$oneToOneMapping[$i], AssociationLorem::class);

            $associationIpsum->setAssociationLorem($lorem);
            $lorem->setAssociationIpsum($associationIpsum);

            $this->addReference('associationIpsum'.$i, $associationIpsum);
            $manager->persist($associationIpsum);
        }
    }
}
