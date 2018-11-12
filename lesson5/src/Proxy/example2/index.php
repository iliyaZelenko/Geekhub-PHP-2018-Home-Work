<?php

namespace IlyaZelen\Proxy;


//require '../../vendor/autoload.php';

?><pre><?php

$proxy = new ProxyBrowser;
$proxy
    ->visiting()
    ->visiting();

?><hr><?php

(new Browser)
    ->visiting()
    ->visiting();




abstract class AbstractBrowser
{
    protected $driver;


    abstract public function visiting();
}

class Browser extends AbstractBrowser
{
    public function visiting(): AbstractBrowser
    {
        echo 'Пользователь посетил страницу.' . PHP_EOL;

        return $this;
    }
}

class ProxyBrowser extends AbstractBrowser
{
    public function visiting(): AbstractBrowser
    {
        // Цитата из вкипедии (в контексте Заместителя): "может отвечать за создание или удаление «Реального Субъекта»"
        // То есть Агрегирование (точнее композицию) можно использовать во так
        if (!isset($this->browser)) {
            $this->browser = new Browser();
        }

        $this->browser->visiting();
        $this->googleAnalytics();

        return $this;
    }

    protected function googleAnalytics()
    {
        echo 'Данные отправились в гугл аналитику.' . PHP_EOL;
    }
}

