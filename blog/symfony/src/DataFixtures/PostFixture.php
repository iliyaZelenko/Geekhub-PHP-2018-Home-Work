<?php

namespace App\DataFixtures;

use App\Entity\Post;
use App\Entity\Tag;
use App\Entity\User;
use App\Utils\Contracts\ContentGenerator\ContentGeneratorInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class PostFixture extends Fixture implements OrderedFixtureInterface
{
    public const REFERENCE_PREFIX = 'post';
    public const POSTS_COUNT = 5;
    /**
     * @var ContentGeneratorInterface
     */
    private $contentGenerator;

    public function __construct(ContentGeneratorInterface $contentGenerator)
    {

        $this->contentGenerator = $contentGenerator;
    }

    public function load(ObjectManager $manager): void
    {
        for ($i = 1; $i <= self::POSTS_COUNT; ++$i) {
            // случайный автор
            $userRef = UserFixture::getRandomReferenceName();

            /** @var User $user */
            $user = $this->getReference($userRef);

            // для последних двух
            if ($i >= self::POSTS_COUNT - 2) {
                $title = $this->contentGenerator->getRealContent('title');
                $text = $this->contentGenerator->getRealContent('text');
                $textShort = $this->contentGenerator->getRealContent('textShort');
                $tags = $this->getRandomTags();
            } else {
                $title = 'Post title ' . $i;
                $text = 'Post text ' . $i;
                $textShort = 'Post text short ' . $i;
                $tags = $this->getRandomTags();
            }

            $post = new Post($user, $title, $text, $textShort, $tags);

            $this->addReference(self::REFERENCE_PREFIX . $i, $post);

            $manager->persist($post);
        }

        $manager->flush();
    }

    /**
     * @param string $type 'title' | 'text' | 'textShort'
     * @return string | null Content
     * @throws \Exception
     */
    public function getRealContent(string $type): ?string
    {
        switch ($type) {
            case 'title':
                return substr(
                    strip_tags(
                        file_get_contents('https://loripsum.net/api/1/short')
                    )
                , 0, 100);

            case 'text':
                return file_get_contents('https://loripsum.net/api/' . random_int(10, 20));

            case 'textShort':
                return substr(
                    file_get_contents('https://loripsum.net/api/' . random_int(1, 2) . '/short')
                , 0, 255);

            default:
                throw new \Error("Please, use one of 'title', 'text' or 'textShort'.");
        }
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
