<?php

namespace App\DataFixtures;

use App\Entity\Post;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Psr\Container\ContainerInterface;

class PostFixture extends Fixture implements OrderedFixtureInterface
{
    public const POST_REFERENCE_PREFIX = 'post';
    public const POSTS_COUNT = 5;

    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function load(ObjectManager $manager)
    {
        for ($i = 1; $i <= self::POSTS_COUNT; $i++) {
            $post = new Post();
            $post->setTitle('Post title ' . $i);
            $post->setText('Post text ' . $i);
            $post->setTextShort('Post text short ' . $i);

            $this->addReference(self::POST_REFERENCE_PREFIX . $i, $post);

            $manager->persist($post);
        }

        $manager->flush();
    }

    /**
     * Get the order of this fixture
     *
     * @return integer
     */
    public function getOrder()
    {
        return 101;
    }
}
