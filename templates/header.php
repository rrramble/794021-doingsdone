<header class="main-header">
    <a href="/">
        <img src="/img/logo.png" width="153" height="42" alt="Логотип Дела в порядке">
    </a>

    <div class="main-header__side">
        <a class="main-header__side-item button button--plus open-modal" href="/pages/add-task.php">Добавить задачу</a>

        <div class="main-header__side-item user-menu">
            <div class="user-menu__image">
                <img src="img/user.png" width="40" height="40" alt="Пользователь">
            </div>

            <div class="user-menu__data">
                <p><?= $data["user"]["userName"]; ?></p>

                <a href="/pages/logout.php">Выйти</a>
            </div>
        </div>
    </div>
</header>
