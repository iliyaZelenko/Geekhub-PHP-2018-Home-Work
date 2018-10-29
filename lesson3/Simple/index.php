<?php

require './vendor/autoload.php';

use App\Entity\PlaceOfStudy\Geekhub\Geekhub;
//use App\Entity\Human\Student\{GoodStudent, BadStudent};
use App\Entity\Human\Student\GoodStudent\GoodStudent;
use App\Entity\Human\Student\BadStudent\BadStudent;
use App\Entity\Human\Teacher\Teacher;



// Студенты
$VasyaStudent = new GoodStudent([
    'name' => 'Вася',
    'age' => 23
]);
$IlyaStudent = new BadStudent([
    'name' => 'Илья',
    'age' => 18
]);
$AndreyStudent = new GoodStudent([
    'name' => 'Андрей',
    'age' => 20
]);
$VadimStudent = new BadStudent([
    'name' => 'Вадим',
    'age' => 19
]);

// Преподаватели
$BillGatesTeacher = new Teacher([
    'name' => 'Билл Гейтс',
    'age' => 63
]);
$MarkZuckerbergTeacher = new Teacher([
    'name' => 'Марк Цукерберг',
    'age' => 34
]);
$LarryPageTeacher = new Teacher([
    'name' => 'Ларри Пейдж',
    'age' => 63
]);



// немного разметки для красоты
?><pre style="height: 95vh; display: flex; justify-content: center; align-items: center; font-size: 17px; text-align: center;"><div><?php




?><h3>Основание Geekhub и добавление студентов:</h3><?php

    // студенты которые пришли на лекцию
    Geekhub
        ::create([ // какие преподаватели
            $BillGatesTeacher,
            $MarkZuckerbergTeacher,
            $LarryPageTeacher
        ])
        ::addStudents([
            $VasyaStudent,
            $IlyaStudent,
            $AndreyStudent,
            $VadimStudent
        ])



?><h3>Начало лекции:</h3><?php

    $students = Geekhub::startLectureProcess();



?><h3>Вопросы и ответы:</h3><?php

    $firstStudent = $students[0];
    $question = $firstStudent->askQuestion('Что общего между студентами и ящерицами?', $BillGatesTeacher);

    $BillGatesTeacher->answerQuestion($question, 'Умение вовремя отбрасывать "хвосты".');



?><h3>Добавление друзей:</h3><?php

    $IlyaStudent->addFriend($VasyaStudent);
    $BillGatesTeacher->addFriend($IlyaStudent);
    $IlyaStudent->addFriend(
        new \App\Entity\Animal\Dog\Sheepdog\GermanShepherd([
            'nickname' => 'Шарик',
            'age' => 5,
            'weight' => '30kg'
        ])
    );



?><h3>Мои друзья:</h3><?php

    echo $IlyaStudent->getFriends();



// Функци-помощники
function draw($str) {
    echo $str . PHP_EOL;
}

function getRandomItems($arr, $min = 0)
{
    $randomCount = random_int($min, \count($arr));
    $items = [];

    if ($randomCount !== 0) {
        $keys = array_rand($arr, $randomCount);

        if (! is_array($keys)) {
            $items[] = $arr[$keys];
        } else {
            foreach ($keys as $index) {
                $items[] = $arr[$index];
            }
        }
    }

    return [
        'items' => $items,
        'count' => $randomCount
    ];
}


?>

<style>
    h3 {
        color: cornflowerblue;
        margin-top: 45px;
    }
</style>
