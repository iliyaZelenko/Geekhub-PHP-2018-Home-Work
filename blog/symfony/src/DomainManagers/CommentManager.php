<?php

namespace App\DomainManagers;

use App\Entity\Comment;
use App\Entity\Post;
use App\Entity\User;
use App\Form\DataObjects\CommentData;
use App\Repository\App\Controllers;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CommentManager
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var App\Controllers
     */
    private $commentRepo;

    public function __construct(EntityManagerInterface $entityManager, App\Controllers $commentRepo)
    {
        $this->entityManager = $entityManager;
        $this->commentRepo = $commentRepo;
    }

    public function createComment(User $user, Post $post, CommentData $data): Comment
    {
        $text = $data->getText();
        $parentCommentId = $data->getParentCommentId();
        $comment = new Comment($user, $post);

        if ($parentCommentId) {
            $parentComment = $this->commentRepo->find($parentCommentId);

            if (!$parentComment) {
                throw new NotFoundHttpException('Parent comment not found.');
            }

            $comment->setParent($parentComment);
        }

        $comment->setText($text);
        $this->entityManager->persist($comment);
        $this->entityManager->flush();


        return $comment;
    }
}
