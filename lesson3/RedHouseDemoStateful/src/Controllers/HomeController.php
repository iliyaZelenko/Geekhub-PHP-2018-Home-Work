<?php

namespace App\Controllers;

class HomeController
{
    public function index()
    {
        $user = $_SESSION['user'] ?? null;

        if ($user) {
            ?>
            <div id="profile">
                <span>
                    Здравствуйте, <b><?= $user->getName() ?></b>.
                </span>

                <br><img src="<?= $user->getAvatar() ?>" id="avatar"><br>

                <?=
                $user->getEmail() ? 'Ваш электронный адрес: ' . $user->getEmail() : 'Не удалось получить электронный адрес.'
                ?>

                <form action="/oauth/logout" method="post">
                    <br>
                    <button>
                        Выйти
                    </button>
                </form>

                <pre style="font-size: 15px; max-width: 80vw; overflow-x: auto;">
                    <?php var_dump($user); ?>
                </pre>
            </div>
            <?php
        } else {
            ?>
            <form action="будет поставленно динамически" method="post" name="auth-form"></form>

            <div id="wrap">
                <button data-provider="google">
                    Войти через Google
                </button>
                <button data-provider="facebook">
                    Войти через Facebook
                </button>
                <button data-provider="github">
                    Войти через Github
                </button>
            </div>
            <?php
        }
    }
}

?>

<script>
    document.addEventListener('DOMContentLoaded', () => {
      const buttons = document.querySelectorAll('button[data-provider]')

      buttons.forEach(btn =>
        btn.onclick = signin.bind(null, btn.dataset.provider)
      )
    })

    function signin (provider) {
      const form = document.forms['auth-form']

      form.action = '/oauth/auth/' + provider
      form.submit()
    }
</script>

<style>
    #wrap {
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
    }

    [data-provider] {
        margin: 0 15px;
        padding: 15px;
        border: 1px solid black;
        border-radius: 10px;
        font-size: 25px;
        cursor: pointer;
        transition: padding linear .2s;
    }
    [data-provider]:hover {
        padding: 20px;
    }

    [data-provider="google"] {
        background: red;
        color: white;
    }

    [data-provider="facebook"] {
        background: blue;
        color: white;
    }

    [data-provider="github"] {
        background: grey;
        color: white;
    }

    #profile {
        padding: 10px;
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    #avatar {
        margin: 0 auto;
        background: #fff;
        width: 5em;
        padding: 0.25em;
        border-radius: .4em;
        box-shadow: 0 0 0.1em rgba(0, 0, 0, 0.35);
    }

    body {
        margin: 0;
    }
</style>
