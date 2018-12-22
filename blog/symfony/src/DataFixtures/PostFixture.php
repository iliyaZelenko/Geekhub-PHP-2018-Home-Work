<?php

namespace App\DataFixtures;

use App\Entity\Post;
use App\Entity\Tag;
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

            $title = 'Post title ' . $i;
            $text = 'Post text ' . $i;
            $textShort = 'Post text short ' . $i;
            $tags = $this->getRandomTags();

            $post = new Post($user, $title, $text, $textShort, $tags);

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

    /**
     * @throws \Exception
     * @return Tag[]
     */
    private function getRandomTags(): array
    {
        for ($i = 1, $tags = []; $i < TagFixture::COUNT; $i++) {
            // пропускает рандомные теги
            if (random_int(0, 2)) {
                continue;
            }

            $ref = TagFixture::REFERENCE_PREFIX . $i;
            $tags[] = $this->getReference($ref);
        }

        return $tags;
    }
}
