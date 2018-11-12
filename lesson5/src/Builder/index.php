<?php

namespace IlyaZelen\Builder;
//require '../../vendor/autoload.php';
?><pre><?php

class House
{
    public $materials = [];
}

// абстрактный строитель
abstract class AbstractHomeBuilder
{
    protected $house;

    abstract public function buildRoof();
    abstract public function buildWalls();
    abstract public function buildWindows();
    abstract public function buildDoors();

    public function __construct()
    {
        $this->reset();
    }

    public function getHouse() {
        return $this->house;
    }

    public function reset()
    {
        $this->house = new House();
    }
}

// конкретный строитель
class EliteHomeBuilder extends AbstractHomeBuilder
{
    public function buildRoof()
    {
        $this->house->materials[] = 'Крыша: элитная';
    }

    public function buildWalls()
    {
        $this->house->materials[] = 'Стены: элитные';
    }

    public function buildWindows()
    {
        $this->house->materials[] = 'Окна: элитные';
    }

    public function buildDoors()
    {
        $this->house->materials[] = 'Двери: элитные';
    }
}

class DirectorHomeBuilder
{
    /**
     * Очень полезнык комментарий, который сообщает IDE какие методы и свойства есть в атрибуте builder
     * @var AbstractHomeBuilder
     */
    protected $builder;

    // в примерах почему-то в отдельном метода, может для понятности, я решил через конструтор ставить
//    public function setBuilder(AbstractHomeBuilder $builder)
//    {
//        $this->builder = $builder;
//    }

    public function __construct(AbstractHomeBuilder $builder)
    {
        $this->builder = $builder;
    }

    public function constructHome()
    {
        $this->builder->buildRoof();
        $this->builder->buildWalls();
        $this->builder->buildWindows();
        $this->builder->buildDoors();
    }

    public function constructHomeOnlyWalls()
    {
        $this->builder->buildWalls();

    }

    public function getHome(): House
    {
        return $this->builder->getHouse();
    }

    public function reset()
    {
        $this->builder->reset();
    }
}


$builder = new EliteHomeBuilder();
$director = new DirectorHomeBuilder($builder);
$director->constructHome();

echo implode('<br>', $director->getHome()->materials);

$director->reset();
$director->constructHomeOnlyWalls();

echo '<h4>Дом только со стенами:</h4>';

echo implode('<br>', $director->getHome()->materials);


