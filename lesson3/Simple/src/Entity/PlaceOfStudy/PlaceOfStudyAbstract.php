<?php

namespace App\Entity\PlaceOfStudy;

use App\Entity\Entity;
use App\Entity\Human\Student\StudentAbstract;

abstract class PlaceOfStudyAbstract extends Entity
{
    public static $name;
    public static $description;
    protected static $students = [];
    protected static $teachers = [];

    public static function create(array $teachers): string
    {
        static::$teachers = $teachers;
        $teachersNames = array_column(static::$teachers, 'name');

        draw('Образоволось новое место обучения — ' . static::getMention() . ', его преподаватели: <b>' . implode(', ', $teachersNames) . '</b>.');

        return static::class;
    }

    public static function addStudents(array $students): string
    {
        foreach ($students as $student) {
            static::addStudent($student);
        }

        return static::class;
    }

    public static function addStudent(StudentAbstract $student): string
    {
        static::$students[] = $student;
        $student->addPlaceOfStudy(static::class);

        return static::class;
    }

    public static function startLectureProcess(): array
    {
        // мое любимое деструктивное присваивание (в JS оно правда еще покороче вышло бы) https://wiki.php.net/rfc/short_list_syntax
        [
            'items' => $students,
            'count' => $studentsCount
        ] = getRandomItems(static::$students);
        [
            'items' => $teachers,
            'count' => $teachersCount
        ] = getRandomItems(static::$teachers, 1);

        $studentsNames = array_column($students, 'name');
        $teachersNames = array_column($teachers, 'name');

        $lectureText = sprintf(
            'Лекция %s началась, пришло студентов: %d%s, из преподавателей пришли: <b>%s</b>.',
            static::getMention(),
            $studentsCount,
            $studentsCount ? ' (<b>' . implode(', ', $studentsNames) . '</b>)' : '',
            implode(', ', $teachersNames)
        );

        draw($lectureText);

        if ($studentsCount === 0) {
            draw('Похоже ' . static::getMention() . ' никому не нужен.');
            die();
        }

        // выполняется процесс лекции
        foreach ($students as $student) {
            $student->lectureProcess(static::class);
        }

        draw('Лекция закончилась.');

        return $students;
    }
}
