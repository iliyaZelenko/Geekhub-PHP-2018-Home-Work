[Паттерн Команда](https://refactoring.guru/ru/design-patterns/command/php/example)

Пример использования в [composer](https://github.com/composer/composer/tree/1.5.6/src/Composer/Command):

---

Очень хороший пример я нашел в библиотеке [Intervention/image](https://github.com/Intervention/image).

Подробнее понять как там это работает помогут эти строчки:

- вызов не известного метода обрабатывает [эта функция](https://github.com/Intervention/image/blob/master/src/Intervention/Image/Image.php#L104).
Она вызывает метод `executeCommand` у драйвера (GD, Imagick) передавая туда имя вызываемого иетода и его аргументы.
Если команда имеет возвращаемое значение, то оно возвращается, иначе вернет `$this`:

```php
$command->hasOutput() ? $command->getOutput() : $this;
```

- теперь посмотрим на метод 
[`executeCommand`](https://github.com/Intervention/image/blob/master/src/Intervention/Image/AbstractDriver.php#L88) 
абстрактного драйвера. 
Благодаря своему методу `getCommandClassName` он получает namespace класса команды. 

В библиотеке есть 
[глоабальные](https://github.com/Intervention/image/tree/master/src/Intervention/Image/Commands) 
и 
[локальные](https://github.com/Intervention/image/tree/master/src/Intervention/Image/Gd/Commands) 
(конкретного драйвера) команды.

Далее создает объект и передает аргументы которые еще получал `__call`, команда использует их чтобы делать манипуляции над картинкой.
Сама команда вызывается методом `execute` который получает картинку.

Код `return $command;` дает объект в котором `__call` проверяет (уже писал):

```php
$command->hasOutput() ? $command->getOutput() : $this;
```
