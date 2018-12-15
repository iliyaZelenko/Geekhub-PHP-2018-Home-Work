<?php

namespace App\Controller\RESTful\Auth;

use App\Resources\UserResource;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\{JsonResponse, Request};
use App\Entity\User;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;

class AuthController extends AbstractController
{
    public function register(Request $request, UserPasswordEncoderInterface $encoder, JWTTokenManagerInterface $JWTManager)
    {
        $em = $this->getDoctrine()->getManager();
        $username = $request->request->get('username');
        $password = $request->request->get('password');

        $user = new User($username);
        $user->setPassword(
            $encoder->encodePassword($user, $password)
        );
        $em->persist($user);
        $em->flush();

        $token =  $JWTManager->create($user);

        // $JWTManager->create($user)
        return new JsonResponse([
            'message' => sprintf('User %s successfully created.', $user->getUsername()),
            'user' => (new UserResource($user))->toArray(),
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
    public function signIn ()
    {
        // возвращать токен и все остальное
    }

    public function api()
    {
        return new JsonResponse(
            sprintf('Logged in as %s', $this->getUser()->getUsername())
        );
    }

    public function res()
    {
        return new JsonResponse([
            'user' => (new UserResource($this->getUser()))->toArray(),
        ]);
    }
}