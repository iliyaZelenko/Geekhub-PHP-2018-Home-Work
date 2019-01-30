<?php

namespace App\Controllers;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use App\Form\DataObjects\RegistrationData;
use App\Form\Handler\RegistrationFormHandler;
use App\Form\RegistrationFormType;
use App\Security\LoginFormAuthenticator;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;

class AuthController extends AbstractController
{
    /**
     * Sign in
     *
     * @param AuthenticationUtils $authenticationUtils
     * @return Response
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('auth/login.html.twig', [
            'last_username' => $lastUsername, 'error' => $error
        ]);
    }

    /**
     * Registration
     *
     * @param Request $request
     * @param RegistrationFormHandler $formHandler
     * @param GuardAuthenticatorHandler $guardHandler
     * @param LoginFormAuthenticator $authenticator
     * @param RegistrationData $registrationData
     * @return Response
     */
    public function register(
        Request $request,
        RegistrationFormHandler $formHandler,
        GuardAuthenticatorHandler $guardHandler,
        LoginFormAuthenticator $authenticator,
        RegistrationData $registrationData
    ): Response
    {
        $form = $this->createForm(RegistrationFormType::class, $registrationData);

        if ($user = $formHandler->handle($form, $request)) {
            // do anything else you need here, like send an email

            return $guardHandler->authenticateUserAndHandleSuccess(
                $user,
                $request,
                $authenticator,
                'main' // firewall name in security.yaml
            );
        }

        return $this->render('auth/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
}
