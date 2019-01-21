<?php

namespace App\Form\Handler;

use App\DomainManager\AccountManager;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\User;

class RegistrationFormHandler
{
    /**
     * @var AccountManager
     */
    private $accountManager;

    // TODO если использовать DI, то можно будет инджектить в конструктор по интерфейсу / классу
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
//        if (!$request->isMethod('POST')) {
//            return false;
//        }
//
//        $form->bind($request);
//
//        if (!$form->isValid()) {
//            return false;
//        }
//
//        $validAccount = $form->getData();
//
//        $this->createAccount($validAccount);
//
//        return true;

        $form->handleRequest($request);

        if (!$form->isSubmitted() || !$form->isValid()) {
            return false;
        }

        return $this->accountManager->createAccount(
            $form->getData()
        );
    }
}
