
<p t m>
Моя блиотека умеет работать с двумя бублиотеками (драйверами):

- *Встроенной* в PHP — **GD**.
- И которую надо устанавливать как расширение для PHP (я потратил на это целый день), но она в **РАЗЫ** мощнее — <span class="font-effect-fire-animation">ImageMagick</span>

Я сделал архитектуру через петтерн [Adapter](https://goo.gl/sq4N95). Не знаю насколько это правильно.
Пользователь с легкостью может выбрать нужный ему драйвер, библиотека не зависимо от выбранного драйвера имеет одинаковый интерфейс.

Конечно использовать мою библиотеку нет смысла, лучше использовать тот же ImageMagick, он очень мощный и не ограничивает в возможностях.
Эту библиотеку я создал чтобы ознакомиться с основными инструментами в PHP для работы с изображениями и чтобы взять опыт с разработки библиотеки в стиле ООП.

После ознакомления с GD, я понял что это очень не удобная библиотека и что ImageMagick наоборот очень мощная.
ImageMagick — это очень популярная либа для картинок, используется не только в PHP, имеет мощный функционал через CLI API.

# Инициализация

Чтобы библиотека работала ее нужно инициализировать. Есть два варианта:

- объектный
- статический

Статический удобно настроить один раз и потом использовать SuperImagesStatic, не заботится о передаче объекта как это было бы с объектным способом (если конечно не используется Dependency injection)
Правда объектный способ может лучше подойти в тестировании, так как можно создавать экземпляр много раз с разными настройками.

Вот статический:

```php
use IlyaZelen\SuperImagesStatic as SuperImages;

// первым аргументом идет имя драйвера, код сам определит какому названию какой класс соответствует
SuperImages::init('GD', [ // или ImageMagick
    // указываются настройки для драйвера
    'driverSettings' => [
        // какие шрифты доступны в драйвере
        'fonts' => [
            // ключ — это алиас (имя) шрифта, оно используется когда нужно указать какой шрифт применить
            'mySuperFontAlias' => [
                // путь шрифта
                'path' => './fonts/font.ttf'
            ],
            'myFont' => [
                'path' => './fonts/BlackCasperFont.ttf'
            ]
        ]
        // кроме шрифтов не придумал что еще можно настроить
    ]
]);
```

Дальше есть два путя что-бы получить объект драйвера через который возможно работать с изображениями:

- Создать пустую картинку:

```php
SuperImages::new(ширина, высота, цвет фона)
```
- Открыть существующую картинку:

```php
SuperImages::open(путь)
```

В SuperImages есть еще последний метод, для получения метрик строки для шрифта:

```php
SuperImages::queryFontMetrics(string $text, int $size = null, string $font = null, int $angle = 0);
```

# Возможности библиотеки

Независимо от драйвера, `SuperImages` умеет:

- new — новая картинка с указанными размерами и залитым фоном (по умолчанию прозрачным)
- open — читает файл
- getSize — ширина и высота
- crop — обрезает
- output — возвращает в строковом представлении
- save — сохраяняет на диск
- resize — изменяет размер
- fit — помещает в рамку указанных размеров
- rotate — поворачивает
- border — рисует рамку указанной толщины
- flip — поворот в указанную сторону
- text — текст нужным шрифтом, цветом и т.д.
- queryFontMetrics — запрос на получение данных о строке текста для шрифта

Все эти методы взяты из `src/Adapters/AdapterInterface.php`.
Дальше будут примеры методов.

Еще в папке `src/Colors/` есть классы для работы с разными форматами цветов.
Через них можно конвертировать с одного формата в другой и каждый формат имеет методы помощники.
Класс `UniversalColor` может определить формат переданого цвета и вернуть соответствующий экземляр цвета.

Поддерживаются такие цвета:
- Hex (шеснадцетеричный формат #RRGGBB, например `#00bfff`)
- RGB (например, `rgb(255, 0, 0)`)
- RGBA (RGB с прозрачностью)
- Именованные (например, `red`, `green`, `blue`)

# Примеры

## Создать пустую картинку и вывести в формате PNG

Передавать 3 параметр цвета не обязательно, без него картинка будет прозрачной (transparent), конечно же все ранее перечисленные форматы поддерживаются.
Вывести можно в любом из этих форматов:
- PNG
- JPG / JPEG
- GIF

Можно добавить и другие форматы, просто в GD это не так просто (там для каждого формата свои методы!)

Примечательно что `output` возвращает строку и не делает вывод сам, вывод делается тут через echo,
а так с этой строкой-изображением можно работать дальше, например, заенкодить через base 64.
В GD функции вывода не возвращают строку, а сразу выводят.

При выводе вторым параметром можно указать степень сжатия в процентном формате 0...100, по умолчанию стоит 65.
GD конечно же и в сжатии отличился, для PNG сжатие нужно было указывать в формате от 0 до 9, в JPG от 0 до 100, в GIF его вообще нельзя указать.
Вообще чтобы понять насколько на GD было тяжелее писать, просто сравните код двух драйверов, это я еще в хелперы две большие функции вынес.
</p>



<div class="block">
<code class="language-php special-code">
use IlyaZelen\SuperImages;

SuperImages::init('GD'); // или ImageMagick

echo SuperImages::new(400, 100, 'blue')->output('png');
</code>

    <div id="border">
        <img src="/examples-with-code/php/1.php">
    </div>
</div>



<p t m>
## Открыть картинку, изменить ее высоту, перевернуть вертикально и вывести в другом формате.

Ресайз как видите можно делать по одной величине, вторая величина будет сохранять пропорцию в таком случае.
Пероварачивать можно еще в 2 режимах: `horizontal` и `both`.
</p>

<div class="block">
<code class="language-php special-code">
echo SuperImages::open(__DIR__ . './img/2.png')
    ->resize(null, 300)
    ->flip('vertical')
    ->output('jpg');
</code>
    <div id="border">
        <img src="/examples-with-code/php/2.php">
    </div>
</div>




<p t m>
## Обрезать и маштабировать в рамку (fit).

Обрезка умеет определять что вышел за изображение и в таком случае обрезает до края изображения (ImageMagick конечно все это и сам умеет, в GD пришлось самому писать).
В `fit` тоже не обязательно указывать одну из величин, тогда вторая величина проигнорируется. Справа в изображении можно увидеть отступ, это такая картинка просто.

</p>
<div class="block">
<code class="language-php special-code">
echo SuperImages::open(__DIR__ . './img/2.jpg')
    ->crop(400, 200, 800, 620)
    ->fit(600, null)
    ->output('jpg');
</code>
    <div id="border">
        <img src="/examples-with-code/php/3.php">
    </div>
</div>



<p t m>
## Ресайз, рисует рамку, поворачиват, текст, маштабирует в рамку.

Можно указать толщину рамки и ее цвет. В rotate 2 аргумент это цвет фона где пустое место, изначально стоит прозрачный.
При повороте изначально картинка не обрезается, а ее всю видно, это можно исправить 3 аргументом (crop).
При рисовании текста указывается именно алиас (имя) шрифта который передал в настройках.
</p>


<div class="block">
    <code class="language-php special-code">
echo SuperImages::open(__DIR__ . './img/2.jpg')
    ->resize(600)
    ->border('blue', 25)
    ->rotate(45)
    ->text(
        'SuperImages', // text
        0, // x
        20, // y
        'rgba(255, 0, 0, 0.5)', // color (0.5 alpha)
        'mySuperFontAlias', // font alias
        90, // font size
        15 // angle
    )
    ->fit(600, 400)
    ->output('png');
    </code>
    <div id="border">
        <img src="/examples-with-code/php/4.php">
    </div>
</div>




<style>
    .block {
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .block > * {
        margin: 50px;
    }

    [t] {
        font-family: 'EB Garamond', serif;
        font-size: 22px;
        display: block;
        max-width: 950px;
        margin: 0 auto;
    }

    body {
        /*display: flex;*/
        /*justify-content: center;*/
        margin-top: 100px;
    }

    .special-code {
        white-space: pre !important;
        display: block;
        padding: 0 20px 15px 20px !important;
        margin: 0 !important;
    }

    code {
        font-size: 15px !important;
        border-radius: .3em !important;
    }

    pre[class*="language-"] {
        border-radius: .3em !important;
    }


    #border {
        display: flex;
        justify-content: center;
        align-items: center;
        border: 2px solid red;
        max-width: 600px;
        height: 400px;
        /*margin: 0 auto;*/
    }
</style>

<link href="/examples-with-code/prism.js/prism.css" rel="stylesheet">
<script src="/examples-with-code/prism.js/prism.js"></script>
<link href="https://fonts.googleapis.com/css?family=EB+Garamond&effect=fire-animation" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/showdown/1.8.7/showdown.js"></script>

<script>
  const converter = new showdown.Converter()

  const el = document.querySelectorAll('[m]')

  el.forEach(markdownToHTML)

    function markdownToHTML (el) {
      let text = el.innerHTML.trim()
      text = text.replace(/&amp;/g, '&').replace(/&lt;/g, '<').replace(/&gt;/g, '>');

      el.innerHTML = converter.makeHtml(text);
    }
</script>
