<?php

namespace App\Utils\Notify;

use App\Utils\Contracts\Notify\Notifiers\NotifierInterface;
use App\Utils\Contracts\Notify\NotifyInterface;
use App\Utils\Notify\Notifications\EmailNotification;
use App\Utils\Notify\Notifications\NotificationData;
use App\Utils\Notify\Notifiers\EmailNotifier;

class Notify implements NotifyInterface
{
    /**
     * Хранилище доступных нотификаторов
     *
     * @var NotifyChain
     */
    private $notifyChain;

    public function __construct(
        NotifyChain $notifyChain
    )
    {
        $this->notifyChain = $notifyChain;
    }

    /**
     * Пытается уведомить пользователя ($willBeNotified) сгенерированными уведомлениями из $notificationData
     *
     * @param $willBeNotified - who will be notified (e.g. User)
     * @param $notificationData
     */
    public function notify($willBeNotified, NotificationData $notificationData): void
    {
        $notifiers = $this->notifyChain->getNotifiers();
        $notification = null;

        foreach ($notifiers as $notifier) {
            $notification = $this->generateNotificationForNotifier($notifier, $notificationData);

            // поддерживается ли нотификатор для данного пользователя ($willBeNotified) и уведомления ($notification)
            if ($notification && $notifier->supports($willBeNotified, $notification)) {
                // уведомляет пользователя
                $notifier->notify($willBeNotified, $notification);
            }
        }
    }

    /**
     * Каждый нотификатор поддерживает свой класс уведомления. Преобразовывает $notificationData в уведомление.
     *
     * @param NotifierInterface $notifier
     * @param NotificationData $notificationData
     * @return EmailNotification
     */
    private function generateNotificationForNotifier(
        NotifierInterface $notifier,
        NotificationData $notificationData
    )
    {
        if ($notifier instanceof EmailNotifier) {
            $subject = $notificationData->getTitle();
            $message = $notificationData->getText();

            return new EmailNotification($subject, $message);
        }
    }
}
