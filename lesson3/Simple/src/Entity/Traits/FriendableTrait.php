<?php

namespace App\Entity\Traits;

use App\Entity\Entity;

trait FriendableTrait
{
    public $friends = [];

    // тоже пример агрегации (агрегирование по ссылке)
    public function addFriend(Entity $friend)
    {
        $this->friends[] = $friend;

        if (isset($this->class_uses_deep($friend)['App\Entity\Traits\FriendableTrait'])) {
            // завершает рекурсию
            $alreadyHaveFriend = (bool) \count(array_filter($friend->friends, function (&$itemFriend) use ($friend) {
                if ($itemFriend == $this) {
                    return true;
                }
            }));

            if ($alreadyHaveFriend) return;

            $friend->addFriend($this);
        } else {
            throw new \InvalidArgumentException('Добавляемый друг должен иметь Trait.');
        }

        draw($this->getMention() . ' подружился с ' . $friend->getMention() . '.');
    }

    public function getFriends(): string
    {
        $mentions = [];

        foreach ($this->friends as $friend) {
            $mentions[] = $friend->getMention();
        }

        return implode(', ', $mentions);
    }

    // то же что и class_uses, но смотрит и у предков
    public function class_uses_deep($class, $autoload = true)
    {
        $traits = [];

        // Get traits of all parent classes
        do {
            $traits = array_merge(class_uses($class, $autoload), $traits);
        } while ($class = get_parent_class($class));

        // Get traits of all parent traits
        $traitsToSearch = $traits;
        while (!empty($traitsToSearch)) {
            $newTraits = class_uses(array_pop($traitsToSearch), $autoload);
            $traits = array_merge($newTraits, $traits);
            $traitsToSearch = array_merge($newTraits, $traitsToSearch);
        };

        foreach ($traits as $trait => $same) {
            $traits = array_merge(class_uses($trait, $autoload), $traits);
        }

        return array_unique($traits);
    }

    // интересно, что в трейте можно использовать абстрактные методы
    // abstract public function getWorld();
}
