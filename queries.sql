
/*
   Наполнение таблицы
   Таблица: "Task states" (Типы статусов задач)
 */

INSERT INTO task_states
  (id, title)
  VALUES
    (0, "Не выполнена"),
    (1, "Выполнена")
;


/*
   Наполнение таблицы
   Таблица: "Users" (Пользователи)
 */

INSERT INTO users
  (email, name, password_hash)
  VALUES
    ("ru7ar7@gmail.com", "ru", "$2y$10$sxxo6wkqHc2Lhs5vTyeXvuwPG5Eb0uSyGqcI4VtichA5gY5PrE9sG"),
    ("crimson_al@mail.ru", "ru", "$2y$10$sxxo6wkqHc2Lhs5vTyeXvuwPG5Eb0uSyGqcI4VtichA5gY5PrE9sG")

    /* the abovementioned and following hash is the hash of the empty password:
       $2y$10$sxxo6wkqHc2Lhs5vTyeXvuwPG5Eb0uSyGqcI4VtichA5gY5PrE9sG
    */
;


/*
   Наполнение таблицы
   Таблица: "Projects" (Проекты)
 */

INSERT INTO projects
  (title, author_user_id)
  VALUES
    ("Входящие", 0),
    ("Учеба", 0),
    ("Работа", 0),
    ("Домашние дела", 0),
    ("Авто", 0)
;


/*
   Наполнение таблицы
   Таблица: "Tasks" (Задачи)
 */

INSERT INTO tasks
  (project_id, state_id, title, due_date, author_user_id)
  VALUES
    (3, 0, "Собеседование в IT компании", "2019-12-01", 1),
    (3, 0, "Выполнить тестовое задание", "2019-12-25", 1),
    (2, 0, "Встреча с другом", "2019-12-22", 1),
    (4, 0, "Купить корм для кота", NULL, 1),
    (4, 0, "Заказать пиццу", NULL, 1)
;


/*
   Добавление в таблицу особых записей
   Таблица: "Tasks" (Задачи)
   В добавляемой записи есть поле "date_completed", которое не заполнялось в запросе выше
 */

INSERT INTO tasks
  (project_id, state_id, title, due_date, author_user_id, date_completed)
  VALUES
    (2, 1, "Сделать задание первого раздела", "2019-12-21", 1, "2019-01-29")
;
