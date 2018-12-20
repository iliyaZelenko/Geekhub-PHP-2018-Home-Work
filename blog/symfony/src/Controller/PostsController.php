<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Post;
use App\Form\CommentType;
use App\Repository\PostRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Response;

class PostsController extends AbstractController
{
    public const PER_PAGE = 3;

    public function allPosts(Request $request, PaginatorInterface $paginator, $page = 1): Response
    {
        $repo = $this
            ->getDoctrine()
            ->getRepository(Post::class);
        $query = $repo
            ->createQueryBuilder('p')
            ->getQuery();

        // Возвращается экземпляр: https://github.com/KnpLabs/KnpPaginatorBundle/blob/master/Pagination/SlidingPagination.php
        $posts = $paginator->paginate(
            $query,
            $page,
            static::PER_PAGE
        );

        return $this->render('blog/posts/allPosts.html.twig', [
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
//        $repoPost = $this
//            ->getDoctrine()
//            ->getRepository(Post::class);

//        $post = $repoPost
//            ->getWithRootComments($id);
        $rootComments = $post->getComments();

//        if (!$post) {
//            throw $this->createNotFoundException(
//                'No post found for id ' . $id
//            );
//        }

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

        // TODO вот этот кусок кода нужно вынести в сервис, очень важно подумать хорошо о том
        // как писать не жирные контроллеры, даже если текущий контроллер пока не жирный
        // TODO сразу добавляется в post, поэтому нет createdAt
//        $comment = new Comment(new \App\Entity\User('b', 'b', 'b'), $post);
//
//        TODO , $comment
        $form = $this->createForm(CommentType::class);
//        $form->handleRequest($request);
//
//        if ($form->isSubmitted() && $form->isValid()) {
//            // я так понимаю это ссылка на тот же $comment
//            // $commentRequest = $form->getData();
//
//            $comment->setPost($post);
//            $comment->setAuthorId(1);
//
//            $manager->persist($comment);
//            $manager->flush();
//
//            $rootComments->add($comment);
//
//            $this->addFlash(
//                'success',
//                'Comment saved!'
//            );
//        }

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
                    'body' => '',
                ],
            ],
        ]);
    }
}
