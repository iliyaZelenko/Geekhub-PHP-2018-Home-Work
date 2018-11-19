В проекте нет ничего необычного.

Я использовал [Homestead](https://laravel.com/docs/5.7/homestead) (nginx).

Для Symfony есть готовое решение для Homestead: [Using Symfony with Homestead/Vagrant](https://symfony.com/doc/current/setup/homestead.html).

Файлы касающиеся Homestead (в root проекта):
- Homestead.yaml
- Vagrantfile (описание типа машины, ее настройки etc..)
- aliases (алисы для команд)
- after.sh (что выполнить после `vagrant reload --provision`)

![](https://i.imgur.com/LLe28Rd.png)

Полезные ссылки для себя:

- Symfony Demo Application: https://github.com/symfony/demo
- Symfony Best Practices: https://symfony.com/doc/current/best_practices/index.html
- [Using Symfony with Homestead/Vagrant](https://symfony.com/doc/current/setup/homestead.html)
