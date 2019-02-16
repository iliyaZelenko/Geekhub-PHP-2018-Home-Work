<?php

namespace App\Controllers\RESTful\Auth;

use App\Entity\User;
use App\Entity\UserInterface;
use App\Repository\UserRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AuthController extends AbstractController
{
    /**
     * @var string
     */
    private $tokenTTL;

    /**
     * @var \App\RESTResources\User\UserResource
     */
    private $resource;
    /**
     * @var JWTTokenManagerInterface
     */
    private $JWTManager;

    public function __construct(
        string $tokenTTL,
        \App\RESTResources\User\UserResource $resource,
        JWTTokenManagerInterface $JWTManager
    )
    {
        $this->tokenTTL = $tokenTTL;
        $this->resource = $resource;
        $this->JWTManager = $JWTManager;
    }

    public function register(
        Request $request,
        UserPasswordEncoderInterface $encoder,
        EntityManagerInterface $em
    )
    {
        [
            'username' => $username,
            'password' => $password,
            'email' => $email
        ] = $request->request->all();

        // TODO validation
        $user = new User($username, $email);
        $user->setPassword(
            $encoder->encodePassword($user, $password)
        );

        $em->persist($user);
        $em->flush();

        return new JsonResponse(
            $this->getAuthResponseData($user)
        );
    }

    public function signIn(
        Request $request,
        UserRepositoryInterface $userRepo,
        UserPasswordEncoderInterface $encoder
    )
    {
        [
            'username' => $username,
            'email' => $email,
            'password' => $password
        ] = $request->request->all();

        // похоже в симфони сделать пользовательский вход довольно тяжело
        $userByUsername = $userRepo->findOneBy([
            'username' => $username
        ]);
        $userByEmail = $userRepo->findOneBy([
            'email' => $email
        ]);
        $user = $userByUsername ?? $userByEmail;

        if (!$user) {
            // TODO Сделать централизованную систему ошибок
            return new JsonResponse([
                'error' => 'No user found by ' . ($username ? 'username' : 'email') . '.',
                Response::HTTP_UNAUTHORIZED
            ]);
        }

        if(!$encoder->isPasswordValid($user->getPassword(), $password)) {
            return new JsonResponse(
                'Password is not valid.',
                Response::HTTP_UNAUTHORIZED
            );
        }

        /** @var UserInterface $user */
        return new JsonResponse(
            $this->getAuthResponseData($user)
        );
    }

    // Тестовая endpoint
    public function api(): JsonResponse
    {
        return new JsonResponse(
            sprintf('Logged in as %s', $this->getUser()->getUsername())
        );
    }

    public function getUser()
    {
        return new JsonResponse([
            'user' => $this->resource->toArray(
                $this->getUser()
            )
        ]);
    }

    private function getAuthResponseData (UserInterface $user): array
    {
        $token = $this->JWTManager->create($user);

        return [
            'message' => sprintf('User %s successfully created.', $user->getUsername()),
            'user' => $this->resource->toArray($user),
            'tokenInfo' => [
                'accessToken' => $token,
                // timestamp
                'expiresIn' => $this->getExpiredAt($this->tokenTTL),
                // timestamp TODO
                'refreshTokenExpiresIn' => ''
            ]
        ];
    }

    // Проще варианта походу нет, не до конца понятно как декодить токен (я видел метод), вытащил бы от туда exp
    private function getExpiredAt($ttl): int
    {
        $now = new \DateTimeImmutable('now', new \DateTimeZone('UTC'));
        $expiredAt = $now->add(
            new \DateInterval('PT' . $ttl . 'S')
        );

        return $expiredAt->getTimestamp();
    }
}
