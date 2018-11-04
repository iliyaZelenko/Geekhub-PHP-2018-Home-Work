<?php

require '../vendor/autoload.php';
require './captcha.php';


$captchaResult = captcha();

header('Content-type: application/json');

echo json_encode([
    'captchaAnswer' => $captchaResult['text'],
    'captchaImgSource' => base64_encode($captchaResult['image'])
]);
