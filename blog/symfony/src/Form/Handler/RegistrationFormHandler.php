<?php

namespace App\Form\Handler;

use App\DomainManagers\AccountManager;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\User;

class RegistrationFormHandler
{
    /**
     * @var AccountManager
     */
    private $accountManager;

    public function __construct(AccountManager $accountManager)
    {
        $this->accountManager = $accountManager;
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

        return $this->accountManager->createAccount(
            $form->getData()
        );
    }
}
