<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Post;
use App\Entity\User;
use App\Form\CommentType;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Response;

class PostsController extends AbstractController
{
    public const POSTS_PER_PAGE = 3;
    public const COMMENTS_PER_PAGE = 6;

    public function allPosts($page = 1): Response
    {
        $repo = $this
            ->getDoctrine()
            ->getRepository(Post::class);

        $posts = $repo->getPaginated($page, static::POSTS_PER_PAGE);

        return $this->render('blog/posts/all_posts.html.twig', [
            'posts' => $posts,
            'pagesCount' => $posts->getPageCount(),
            'totalPosts' => $posts->getTotalItemCount(),
            'vue_data' => [
                'currentPage' => $page
            ]
        ]);
    }

    /*
     ParamConverter:
     1) если использовать параметр "Post $post" без анотации ParamConverter,
        то Symfony сама будет использовать ParamConverter
     2) изначально ParamConverter ищет по id, чтобы осуществлялся поиск по другим полям, их нужно добавить в mapping.
     3) без mapping поиск будет только по id, будет делатся 2 запроса где WHERE id (на примере 16) будет "16" и 16
        Скрин: https://i.imgur.com/0VtcTDc.png
        С mapping со slug будет делатся 2 запроса: WHERE slug "post-title-1" и WHERE id 16
        Скрин: https://i.imgur.com/cYjSI2f.png
    */
    /**
     * @ParamConverter("post", options={"mapping"={"slug"="slug"}})
     * @param Request $request
     * @param ObjectManager $manager
     * @param Post $post
     * @param $slug
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function post(Request $request, ObjectManager $manager, Post $post, $slug, $id): Response
    {
        $repoUser = $this->getDoctrine()->getRepository(User::class);
        $repoComment = $this->getDoctrine()->getRepository(Comment::class);

        // если slug из url не совпадает со slug в посте, то редирект на слуг поста
        // таким образом: если была ссылка с slug который поменялся (вместе с title), то такая ссылка будет работать
        // изначально ParamConverter ищет по id и (или) slug, если нашло по id, а slug отличается, то будет редирект
        if ($slug !== $post->getSlug()) {
            return $this->redirect(
                $this->generateUrl('post', [
                    'id' => $post->getId(),
                    'slug' => $post->getSlug()
                ]),
                301
            );
        }

        // TODO Security
        $authUser = $repoUser->getFirst();
        $comment = new Comment($authUser, $post);

        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($comment);
            $manager->flush();

            // если ASC: $rootComments[] = $comment;
            // array_unshift($rootComments, $comment);
//            $rootComments->add($comment);

            $this->addFlash(
                'success',
                'Comment saved!'
            );
        }

        // $repoComment->getCommentsByPostId($id);
        $rootComments = $repoComment->getPaginatedByPostId(
            $id,
            // берется из query ?page=2 (так сделано на stackoverflow)
            $request->query->getInt('page', 1),
            static::COMMENTS_PER_PAGE
        );

        return $this->render('blog/posts/post.html.twig', [
            'post' => $post,
            'rootComments' => $rootComments,
            'form' => $form->createView(),
            'vue_data' => [
                'formComment' => json_encode([
                    'text' => 'Form text',
                ], JSON_FORCE_OBJECT),
            ],
            // TODO убрать
            'vue_methods' => [
                'onSubmit' => [
                    'body' => ''
                ]
            ]
        ]);
    }
}
