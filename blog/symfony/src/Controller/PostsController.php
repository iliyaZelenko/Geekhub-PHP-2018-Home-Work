<?php

namespace App\Controller;

use App\DomainManagers\CommentManager;
use App\DomainManagers\PostVoteManager;
use App\Entity\Post;
use App\Form\DataObjects\CommentData;
use App\Form\Handler\CommentFormHandler;
use App\Repository\CommentRepository;
use App\Repository\PostRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;

class PostsController extends AbstractController
{
    public const POSTS_PER_PAGE = 3;
    public const COMMENTS_PER_PAGE = 6;

    /**
     * Get all paginated posts
     *
     * @param Request $request
     * @param PostRepository $repo
     * @param int $page
     * @return Response
     */
    public function allPosts(
        Request $request,
        PostRepository $repo,
        $page = 1
    ): Response
    {
        $perPageFromRequest = $request->get('perPage');
        $perPage = $perPageFromRequest ?? static::POSTS_PER_PAGE;
        $posts = $repo->getPaginated($page, $perPage);

        return $this->render('blog/posts/all_posts.html.twig', [
            'posts' => $posts,
            'pagesCount' => $posts->getPageCount(),
            'totalPosts' => $posts->getTotalItemCount(),
            'perPage' => $perPage,
            'vue_data' => [
                'currentPage' => $page,
                'basePathURL' => '"/blog/"'
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

        /* Old comment creation:
        $authUser = $this->getUser();
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
        */

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

    /**
     * @ParamConverter("post", options={"mapping" = {"id" = "id"}})
     * @param Request $request
     * @param Post $post
     * @param PostVoteManager $postVoteManager
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function postDoVote(
        Request $request,
        Post $post,
        PostVoteManager $postVoteManager
    ): RedirectResponse
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $user = $this->getUser();
        $requestVoteValue = +$request->get('voteValue');
        $redirectResponse = $this->redirectToRoute('post', [
            'id' => $post->getId(),
            'slug' => $post->getSlug()
        ]);

        if ($userVote = $post->getUserVote($user)) {
            $userVote->getValue() === $requestVoteValue
                ? $postVoteManager->removeVote($post, $userVote)
                : $postVoteManager->updateVoteValue($userVote, $requestVoteValue);
        } else {
            $postVoteManager->createVote($user, $post, $requestVoteValue);
        }


        return $redirectResponse;
    }

    public function createPost(
        Request $request
    )
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $user = $this->getUser();

//        $form = $this->createForm(PostCreationFormType::class, $registrationData);
//
//        if ($user = $formHandler->handle($form, $request)) {
//            // do anything else you need here, like send an email
//
//            return $guardHandler->authenticateUserAndHandleSuccess(
//                $user,
//                $request,
//                $authenticator,
//                'main' // firewall name in security.yaml
//            );
//        }

        return $this->render('blog/posts/post_creation.html.twig');
    }
}
