<?php

namespace App\Form\Handler;

use App\DomainManagers\AccountManager;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpKernel\Exception\HttpException;
use App\Entity\User;
use App\Utils\Contracts\Recaptcha\RecaptchaInterface;
use Symfony\Component\HttpFoundation\Request;

class RegistrationFormHandler
{
    /**
     * @var AccountManager
     */
    private $accountManager;
    /**
     * @var RecaptchaInterface
     */
    private $recaptcha;

    public function __construct(AccountManager $accountManager, RecaptchaInterface $recaptcha)
    {
        $this->accountManager = $accountManager;
        $this->recaptcha = $recaptcha;
    }

    /**
     * @param FormInterface $form
     * @param Request $request
     * @return User | bool
     */
    public function handle(FormInterface $form, Request $request)
    {
        $form->handleRequest($request);

        if (!$form->isSubmitted() || !$form->isValid()) {
            return false;
        }

        $this->checkCaptcha($request);

        return $this->accountManager->createAccount(
            $form->getData()
        );
    }

    private function checkCaptcha(Request $request): void
    {
        $status = $this->recaptcha->check(
            $request->get('g-recaptcha-response')
        );

        if (!$status) {
            throw new HttpException(422, 'Captcha check failed.');
        }
    }
}
