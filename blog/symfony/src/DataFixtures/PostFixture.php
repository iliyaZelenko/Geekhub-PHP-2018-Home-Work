<?php

namespace App\DataFixtures;

use App\Entity\Post;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class PostFixture extends Fixture implements OrderedFixtureInterface
{
    public const REFERENCE_PREFIX = 'post';
    public const POSTS_COUNT = 5;

    public function load(ObjectManager $manager): void
    {
        for ($i = 1; $i <= self::POSTS_COUNT; ++$i) {
            // случайный автор
            $userRef = UserFixture::REFERENCE_PREFIX . random_int(1, UserFixture::COUNT);
            $user = $this->getReference($userRef);

            $post = new Post($user);
            $post->setTitle('Post title ' . $i);
            $post->setText('Post text ' . $i);
            $post->setTextShort('Post text short ' . $i);

//            $post->setAuthor(
//                $this->getReference($userRef)
//            );

            $this->addReference(self::REFERENCE_PREFIX . $i, $post);

            $manager->persist($post);
        }

        $manager->flush();
    }

    /**
     * Get the order of this fixture.
     *
     * @return int
     */
    public function getOrder(): int
    {
        return 101;
    }
}
