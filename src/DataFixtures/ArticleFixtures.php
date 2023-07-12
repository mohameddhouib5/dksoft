<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Article;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ArticleFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for ($i=1; $i <5; $i++) {
            $category = new Category();
            $category->setTitle("category $i");
            $category->setDescription("description for segment $1");
            $manager->persist($category);
      
            for ($j=1; $j <=2 ; $j++) {
                $article = new Article();
                $article->setTitle(" service n°$j")
                    ->setContent("Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.")
                    ->setCreatedAt(new \DateTimeImmutable())
                    ->setImage("https://picsum.photos/seed/picsum/300/150")
                    ->setPrice("---TND")
                    ->setCategory($category);
                

                $manager->persist($article);
            }
        }

        $manager->flush();
    }
}
