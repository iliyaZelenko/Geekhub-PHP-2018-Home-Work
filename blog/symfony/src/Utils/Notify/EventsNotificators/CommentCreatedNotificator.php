<?php

namespace App\Utils\Notify\EventsNotificators;

use App\EventListeners\Events\CommentCreatedEvent;
use App\Utils\Notify\Notifications\NotificationData;
use App\Utils\Notify\Notify;

class CommentCreatedNotificator
{
    /**
     * @var Notify
     */
    private $notify;

    public function __construct(
        Notify $notify
    )
    {
        $this->notify = $notify;
    }

    public function notify(CommentCreatedEvent $event): void
    {
        $comment = $event->getComment();
        $commentAuthor = $comment->getAuthor();
        $commentPost = $comment->getPost();
        $postAuthor = $commentPost->getAuthor();

        $title = 'Привет, ' . $postAuthor->getUsername();
        $text = 'Пользователь ' . $commentAuthor->getUsername() . 'оставил комментарий под вашим постом "' .
            $commentPost->getTitle(). '". Его текст: "' . $comment->getText() . '".'
        ;
        $notificationData = new NotificationData($title, $text);

        $this->notify->notify($postAuthor, $notificationData);
    }
}
