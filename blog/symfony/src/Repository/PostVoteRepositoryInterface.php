<?php

namespace App\Repository;

use App\Entity\PostVote;

interface PostVoteRepositoryInterface extends BaseRepositroyInterface
{
    /**
     * Save entity
     *
     * @param PostVote $comment
     */
    public function save(PostVote $comment): void;

    /**
     * Remove entity
     * @param PostVote $post
     */
    public function remove(PostVote $post): void;

    /**
     * Update entity
     */
    public function update(): void;
}
