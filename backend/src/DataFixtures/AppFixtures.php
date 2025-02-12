<?php

namespace App\DataFixtures;

use App\Entity\Article;
use App\Entity\Tag;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

class AppFixtures extends Fixture
{
    private SluggerInterface $slugger;
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(SluggerInterface $slugger, UserPasswordHasherInterface $passwordHasher)
    {
        $this->slugger = $slugger;
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        // Creating 4 test users
        $user = new User();
        $user->setEmail('test@example.com');
        $hashedPassword = $this->passwordHasher->hashPassword($user, 'password');
        $user->setPassword($hashedPassword);
        $manager->persist($user);

        $user2 = new User();
        $user2->setEmail('test2@example.com');
        $hashedPassword2 = $this->passwordHasher->hashPassword($user2, 'password123');
        $user2->setPassword($hashedPassword2);
        $manager->persist($user2);

        $user3 = new User();
        $user3->setEmail('test3@example.com');
        $hashedPassword3 = $this->passwordHasher->hashPassword($user3, 'password456');
        $user3->setPassword($hashedPassword3);
        $manager->persist($user3);

        $user4 = new User();
        $user4->setEmail('test4@example.com');
        $hashedPassword4 = $this->passwordHasher->hashPassword($user4, 'password789');
        $user4->setPassword($hashedPassword4);
        $manager->persist($user4);

        // We create 4 tags
        $tags = [];
        foreach (['PHP', 'Symfony', 'JavaScript', 'HTML'] as $tagName) {
            $tag = new Tag();
            $tag->setName($tagName);
            $tags[] = $tag;
            $manager->persist($tag);
        }

        // We create 40 articles
        for ($i = 1; $i <= 10; $i++) {
            $article = new Article();
            $article->setTitle('Article ' . $i);
            $article->setContent('Content of the article  ' . $i);
            $article->setSlug($this->slugger->slug($article->getTitle())->lower());
            $article->setAuthor($user);

            // We randomly add some tags
            $article->addTag($tags[array_rand($tags)]);

            $manager->persist($article);
        }

        for ($i = 11; $i <= 20; $i++) {
            $article = new Article();
            $article->setTitle('Article ' . $i);
            $article->setContent('Content of the article  ' . $i);
            $article->setSlug($this->slugger->slug($article->getTitle())->lower());
            $article->setAuthor($user2);

            // We randomly add some tags
            $article->addTag($tags[array_rand($tags)]);

            $manager->persist($article);

        }

        for ($i = 21; $i <= 30; $i++) {
            $article = new Article();
            $article->setTitle('Article ' . $i);
            $article->setContent('Content of the article  ' . $i);
            $article->setSlug($this->slugger->slug($article->getTitle())->lower());
            $article->setAuthor($user3);

            // We randomly add some tags
            $article->addTag($tags[array_rand($tags)]);

            $manager->persist($article);

        }

        for ($i = 31; $i <= 40; $i++) {
            $article = new Article();
            $article->setTitle('Article ' . $i);
            $article->setContent('Content of the article  ' . $i);
            $article->setSlug($this->slugger->slug($article->getTitle())->lower());
            $article->setAuthor($user4);

            // We randomly add some tags
            $article->addTag($tags[array_rand($tags)]);

            $manager->persist($article);

        }

        $manager->flush();
    }
}
