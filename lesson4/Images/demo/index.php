<div style="width: 1000px;">
<?php

require '../vendor/autoload.php';
require './captcha.php';


$captchaResult = captcha();

?>
<!-- Шрифты + огненный текст -->
    <link rel="stylesheet"
          href="https://fonts.googleapis.com/css?family=Rancho&effect=fire-animation|putting-green">


    <h1 class="font-effect-fire-animation">
    Captcha
    </h1>
    <br>

    <p>
        Текст на картинке:
        <b id="answer">
            <?= $captchaResult['text'] ?>
        </b>
    </p>

    <div id="captcha-wrap">
        <img
            id="captcha"
            src="data:image/gif;base64,<?= base64_encode($captchaResult['image']) ?>"
        >

        <button>
            Другой текст
        </button>
    </div>



    <a href="/examples-with-code" class="font-effect-putting-green">
        Перейти к описанию и примерам
    </a>



    <script>
        // скрипт для AJAX запроса
        document.querySelector('button').addEventListener('click', async () => {
          const response = await fetch('captchaRequest.php')
          const { captchaAnswer, captchaImgSource } = await response.json()

          document.querySelector('#answer').textContent = captchaAnswer
          document.querySelector('#captcha').src = 'data:image/gif;base64,' + captchaImgSource
        })
    </script>



    <style>
        #captcha {
            border: 1px solid #ccc;
            border-radius: 8px;
            margin-right: 20px;
        }

        #captcha-wrap {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-bottom: 65px;
        }

        :root {
            font-family: 'Rancho', serif !important;
            font-size: 30px;
        }

        body {
            display: flex;
            justify-content: center;
            align-items: center;
            background: azure;
            text-align: center;
        }
    </style>

