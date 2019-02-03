<?php

namespace App\Utils\Notify;

use App\Exceptions\AppException;
use App\Utils\Contracts\Notify\NotifyInterface;
use App\Utils\Contracts\Notify\NotifyNotificationsGeneratorInterface;
use App\Utils\Notify\Notifications\NotificationData;

class Notify implements NotifyInterface
{
    /**
     * Хранилище доступных нотификаторов
     *
     * @var NotifyChain
     */
    private $notifyChain;

    /**
     * @var NotifyNotificationsGeneratorInterface
     */
    private $notificationsGenerator;

    public function __construct(
        NotifyChain $notifyChain,
        NotifyNotificationsGeneratorInterface $notificationsGenerator
    )
    {
        $this->notifyChain = $notifyChain;
        $this->notificationsGenerator = $notificationsGenerator;
    }

    /**
     * Пытается уведомить пользователя ($willBeNotified) сгенерированными уведомлениями из $notificationData
     *
     * @param $willBeNotified - who will be notified (e.g. User)
     * @param $notificationData
     * @throws AppException
     * @throws \ReflectionException
     */
    public function notify($willBeNotified, NotificationData $notificationData): void
    {
        $notifiers = $this->notifyChain->getNotifiers();
        $notification = null;

        foreach ($notifiers as $notifier) {
            $notification = $this->notificationsGenerator->generateNotificationForNotifier($notifier, $notificationData);

            // поддерживается ли нотификатор для данного пользователя ($willBeNotified) и уведомления ($notification)
            if ($notification && $notifier->supports($willBeNotified, $notification)) {
                // уведомляет пользователя
                $notifier->notify($willBeNotified, $notification);
            }
        }
    }
}
