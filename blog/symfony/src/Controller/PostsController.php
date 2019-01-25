<?php

namespace App\Controller;

use App\DomainManagers\CommentManager;
use App\Entity\Comment;
use App\Entity\Post;
use App\Entity\User;
use App\Form\CommentType;
use App\Form\DataObjects\CommentData;
use App\Form\Handler\CommentFormHandler;
use App\Repository\CommentRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

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
     * @ParamConverter("post", options={"mapping" = {"slug" = "slug"}})
     * @param Request $request
     * @param ObjectManager $manager
     * @param Post $post
     * @param CommentRepository $repoComment
     * @param $slug
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function post(
        Request $request,
        ObjectManager $manager,
        Post $post,
        CommentRepository $repoComment,
        $slug,
        $id
    ): Response
    {
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

//        $authUser = $this->getUser();
//        $comment = new Comment($authUser, $post);

//        $form = $this->createForm(CommentType::class, $comment);
//        $form->handleRequest($request);

//        if ($form->isSubmitted() && $form->isValid()) {
//            $manager->persist($comment);
//            $manager->flush();
//
//            // если ASC: $rootComments[] = $comment;
//            // array_unshift($rootComments, $comment);
////            $rootComments->add($comment);
//
//            $this->addFlash(
//                'success',
//                'Comment saved!'
//            );
//        }

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
//            'form' => $form->createView(),
            'vue_data' => [
                'showFormAddRootComment' => false,
                'formComment' => json_encode([
                    'text' => 'Form text',
                ], JSON_FORCE_OBJECT),
            ]
        ]);
    }

    /**
     * AJAX POST for creating a comment.
     *
     * @ParamConverter("post", options={"mapping" = {"slug" = "slug"}})
     * @param Request $request
     * @param Post $post
     * @param CommentFormHandler $commentFormHandler
     * @param CommentData $commentData
     * @param CommentManager $commentManager
     * @param $slug
     * @param $id
     * @return JsonResponse
     */
    public function createComment(
        Request $request,
        Post $post,
        CommentFormHandler $commentFormHandler,
        CommentData $commentData,
        CommentManager $commentManager,
        $slug,
        $id
    ): JsonResponse
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $user = $this->getUser();

        if ($formResult = $commentFormHandler->handle($commentData, $request)) {
            $commentManager->createComment(
                $user,
                $post,
                $commentData
            );

            // TODO JsonResponseBuilder
            return new JsonResponse([
                'successMessage' => 'Comment saved!'
            ]);
        }

        return new JsonResponse([
            'error' => $formResult
        ]);
    }
}
