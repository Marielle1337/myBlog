<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Entity\Article;
use App\Entity\User;
use App\Entity\Challenge;
use App\Entity\Category;
use App\Repository\ChallengeRepository;

class AppFixtures extends Fixture
{
    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user->setEmail('doudouce@mail.fr');
        $user->setUsername('Doudouce');
        $user->setRoles(['ROLE_ADMIN']);
        $user->setNewsletter(false);
        $user->setPassword($this->passwordEncoder->encodePassword($user, 'doudou'));

        $manager->persist($user);

        $challenges = [];
        for ($a = 0; $a < 4; $a++) {
            $category = new Category();
            $category->setTitle('Catégorie ' . $a);
            $manager->persist($category);

            for ($b = 0; $b < 3; $b++) {
                $article = new Article();
                $article->setTitle('Article ' . $b . ' Catégorie ' . $a);
                $article->setContent('Contenu article' . $b . ' Catégorie ' . $a);
                $article->addCategory($category);
                $article->setUser($user);
                $manager->persist($article);

                $challenge = new Challenge();
                $challenge->setTitle('Challenge ' . $b . ' Catégorie ' . $a);
                $challenge->setDescription('Description Challenge ' . $b . ' Catégorie ' . $a);
                $challenge->addCategory($category);

                $challenges[] = $challenge;
                $manager->persist($challenge);
            }
        }

        foreach ($challenges as $challenge) {
            for ($i = 0; $i < 6; $i++) {
                $article = new Article();
                $article->setTitle('Article ' . $i . ' Challenge ' . $challenge->getId());
                $article->setContent('Contenu article' . $i . ' Challenge ' . $challenge->getId());
                $article->setChallenge($challenge);
                $article->setUser($user);
                $manager->persist($article);
            }
        }

        $manager->flush();
    }
}
