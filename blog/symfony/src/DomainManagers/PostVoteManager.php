<?php

namespace App\DomainManagers;

use App\Entity\Post;
use App\Entity\PostVote;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class PostVoteManager
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function changeVoteValue(PostVote $vote, int $voteValue): PostVote
    {
        $vote->setValue($voteValue);
        $this->entityManager->flush();


        return $vote;
    }

    public function createVote(User $user, Post $post, int $voteValue): PostVote
    {
        $newVote = new PostVote($user, $post, $voteValue);

        $this->entityManager->persist($newVote);
        $this->entityManager->flush();


        return $newVote;
    }

    public function removeVote(Post $post, PostVote $vote): Post
    {
        $post->removeVote($vote);

        $this->entityManager->remove($vote);
        $this->entityManager->flush();

        return $post;
    }
}
