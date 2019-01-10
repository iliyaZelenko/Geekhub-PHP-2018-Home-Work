<?php

namespace App\Controller;

use App\Entity\Post;
use App\Entity\Comment;
use App\Form\CommentType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Knp\Component\Pager\PaginatorInterface;
use Doctrine\Common\Persistence\ObjectManager;

class PostsController extends AbstractController
{
    public function allPosts(Request $request, PaginatorInterface $paginator, $page = 1)
    {
        $perPage = 3;

        $repo = $this
            ->getDoctrine()
            ->getRepository(Post::class);
        $query = $repo
            ->createQueryBuilder('p')
            ->getQuery();


        // $posts = $repo->findAll();
        // Возвращается экземпляр: https://github.com/KnpLabs/KnpPaginatorBundle/blob/master/Pagination/SlidingPagination.php
        $posts = $paginator->paginate(
            $query,
            $page,
            $perPage
        );

        return $this->render('blog/posts/allPosts.html.twig', [
            'posts' => $posts,
            'pages' => $posts->getPageCount(),
            'vue_data' => [
                'currentPage' => $page
            ]
        ]);
    }

    // в хороших практиках по symfony написано что лучше совмещать дейсвие обработки формы и рендеринга
    public function post(Request $request, ObjectManager $manager, $slug, $id)
    {
        $repoPost = $this
            ->getDoctrine()
            ->getRepository(Post::class);

        $post = $repoPost
            ->getWithRootComments($id);


        if (!$post) {
            throw $this->createNotFoundException(
                'No post found for id ' . $id
            );
        }

        // TODO вот этот кусок кода нужно вынести в сервис, очень важно подумать хорошо о том
        // как писать не жирные контроллеры, даже если текущий контроллер пока не жирный
        $comment = new Comment();

        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // я так понимаю это ссылка на тот же $comment
            // $commentRequest = $form->getData();

            // TODO не знаю пока как добавить коммент в уже полученные комменты $post.
            $comment->setPost($post);
            $comment->setAuthorId(1);

            // dump($commentRequest, $comment);

            $manager->persist($comment);
            $manager->flush();

            $this->addFlash(
                'success',
                'Comment saved!'
            );
            // печально что так нельзя
//            $this->addFlash(
//                'message',
//                [
//                    'type' => 'success',
//                    'text' => 'Comment saved!'
//                ]
//            );

        }

        return $this->render('blog/posts/post.html.twig', [
            'post' => $post,
            'form' => $form->createView(),
            'vue_data' => [
                'formComment' => json_encode([
                    'text' => 'Form text'
                ], JSON_FORCE_OBJECT)
            ],
            'vue_methods' => [
                'onSubmit' => [
                    'body' => '
                    alert(123)
                    '
                ]
            ]
        ]);
    }
}
