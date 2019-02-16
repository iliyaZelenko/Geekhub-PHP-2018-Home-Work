<?php

namespace App\Controllers\RESTful\Auth;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AuthController extends AbstractController
{
    /**
     * @var \App\RESTResources\User\UserResource
     */
    private $resource;

    public function __construct(\App\RESTResources\User\UserResource $resource)
    {
        $this->resource = $resource;
    }

    public function register(
        Request $request,
        UserPasswordEncoderInterface $encoder,
        JWTTokenManagerInterface $JWTManager,
        EntityManagerInterface $em
    )
    {
        [
            'username' => $username,
            'password' => $password,
            'email' => $email
        ] = $request->request->all();

        $user = new User($username, $email);
        $user->setPassword(
            $encoder->encodePassword($user, $password)
        );

        $em->persist($user);
        $em->flush();

        $token = $JWTManager->create($user);

        return new JsonResponse([
            'message' => sprintf('User %s successfully created.', $user->getUsername()),
            'user' => $this->resource->toArray($user),
            'tokenInfo' => [
              'accessToken' => $token,
              // timestamp
              'expiresIn' => '',
              // timestamp
              'refreshTokenExpiresIn' => ''
            ]
        ]);
    }

    // TODO сделать для маршрута auth/signin
    public function signIn()
    {
        // возвращать токен и все остальное
    }

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
}
