<?php

namespace App\Utils\Notify\Notifiers;

use App\Entity\Resources\EmailNotifiableInterface;
use App\Utils\Contracts\Notify\Notifiers\NotifierInterface;
use App\Utils\Notify\Notifications\EmailNotification;

class EmailNotifier implements NotifierInterface
{
    public function supports($willBeNotified, $notification): bool
    {
        return $willBeNotified instanceof EmailNotifiableInterface
            && $willBeNotified->getEmail()
            && $notification instanceof EmailNotification
        ;
    }

    /**
     * @param EmailNotifiableInterface $willBeNotified
     * @param EmailNotification $notification
     */
    public function notify($willBeNotified, $notification): void
    {
        $email = $willBeNotified->getEmail();
        $subject = $notification->getSubject();
        $message = $notification->getMessage();

        dump(
            implode([$email, $subject, $message], ' | ')
        );
        // echo implode([$email, $subject, $message], ' | ');
        // TODO ->sendEmail($email, $subject, $message);
    }
}
