<?php

namespace App\DataFixtures;

use App\Entity\Comment;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;

class CommentFixture extends Fixture implements OrderedFixtureInterface
{
    public const COMMENT_REFERENCE_PREFIX = 'comment';
    // для каждого поста создать столько комментов
    public const COMMENT_REFERENCE_EACH_COUNT = 10;

    public function load(ObjectManager $manager)
    {
        $repo = $manager->getRepository(Comment::class);
        $totalComments = self::COMMENT_REFERENCE_EACH_COUNT * PostFixture::POSTS_COUNT;

        for ($i = 1; $i <= $totalComments; $i++) {
            // индекс текущего поста
            $currentPostIndex = ceil($i / self::COMMENT_REFERENCE_EACH_COUNT);

            $comment = new Comment();
            $comment->setText('Comment text ' . $i);
            $comment->setAuthorId(random_int(1, 5));

            // ставит коммент-родителя
            if (random_int(0, 3)) {
                $randomComment = $this->getRandomCommentFromCurrentPost($repo, $currentPostIndex, $i);

                $randomComment && $comment->setParent($randomComment);
            }

            // ставит соответсвующий пост для коммента
            $refId = PostFixture::POST_REFERENCE_PREFIX . $currentPostIndex;
            $post = $this->getReference($refId);
            $comment->setPost($post);


            $manager->persist($comment);
            // знаю что это не оптимизированно, но это же данные для теста
            // и мне нужно брать случайный коммент и делать setParent
            $manager->flush();
        }

        // $manager->flush();
    }

    /**
     * Get the order of this fixture
     *
     * @return integer
     */
    public function getOrder()
    {
        return 102;
    }

    // id первого коммента
    private function getFirstCommentId($repo)
    {
        $first = $repo->getFirst();

        if ($first) {
            return $first->getId();
        }
    }

    private function getRandomCommentFromCurrentPost($repo, $currentPostIndex, $currentCommentIndex)
    {
        // начальный индекс коммента для текущего поста
        // если COMMENT_REFERENCE_EACH_COUNT === 10, то для первого поста будет 0, для второго - 10, потом 20, 30...
        $currentPostCommentsStart = ($currentPostIndex - 1) * self::COMMENT_REFERENCE_EACH_COUNT;

        $firstId = $this->getFirstCommentId($repo);

        $parentRangeStart = (int) round($firstId + $currentPostCommentsStart);
        $parentRangeEnd = (int) max($parentRangeStart + $currentCommentIndex - 2, $parentRangeStart);

//        dump([
//            'comment' => $i,
//            'post' => $currentPostIndex,
//            'min' => $parentRangeStart,
//            'max' => $parentRangeEnd,
//            'firstId' => $firstId,
//            '$currentPostIndexCommentsStart' => $curruntPostCommentsStart
//        ]);

        if ($firstId) {
            $randomComment = $repo
                ->find(
                    random_int(
                        $parentRangeStart,
                        $parentRangeEnd
                    )
                )
            ;

            return $randomComment;
        }
    }
}

