<?php

namespace App\Controllers;

class HomeController
{
    public function index()
    {
        $user = $_SESSION['user'] ?? null;

        if ($user) {
            ?>
            Здравствуйте, <b><?= $user->getName() ?></b>.

            <br><img src="<?= $user->getAvatar() ?>"><br>

            <?=
                $user->getEmail() ? 'Ваш электронный адрес: ' . $user->getEmail() : 'Не удалось получить электронный адрес.'
            ?>

            <form action="/oauth/logout" method="post">
                <button>
                    Выйти
                </button>
            </form>

            <pre>
                <?php var_dump($user); ?>
            </pre>
            <?php
        } else {
            ?>
            <form action="/oauth/signin/google" method="post">
                <button>
                    Войти через Google
                </button>
            </form>
            <?php
        }
    }
}

