<?php

namespace App\Form\Handler;

use App\Form\DataObjects\CommentData;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CommentFormHandler
{
    /**
     * @var ValidatorInterface
     */
    private $validator;

    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    /**
     * @param CommentData $commentData
     * @param Request $request
     * @return string | bool
     */
    public function handle(CommentData $commentData, Request $request)
    {
        $requestContent = $request->getContent();
        [
            'message' => $message,
            'parentCommentId' => $parentCommentId
        ] = json_decode($requestContent, true);

        $commentData->setText($message);
        $commentData->setParentCommentId($parentCommentId);

        $errors = $this->validator->validate($commentData);

        if (count($errors)) {
            // first error message
            return $errors->get(0)->getMessage();
        }

        return true;
    }
}
