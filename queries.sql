
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
    ("crimson_al@mail.ru", "oL", "$2y$10$sxxo6wkqHc2Lhs5vTyeXvuwPG5Eb0uSyGqcI4VtichA5gY5PrE9sG")

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
    ("Входящие", 1),
    ("Учеба", 2),
    ("Работа", 1),
    ("Домашние дела", 2),
    ("Авто", 1)
;


/*
  Наполнение таблицы
  Таблица: "Tasks" (Задачи)
 */

INSERT INTO tasks
  (project_id, state_id, title, due_date, author_user_id)
  VALUES
    (3, 0, "Собеседование в IT компании", "2019-12-01", 2),
    (3, 0, "Выполнить тестовое задание", "2019-12-25", 1),
    (2, 0, "Встреча с другом", "2019-12-22", 2),
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



/*
  Запрос:
  Получить список из всех проектов для одного пользователя с эл. адресом "ru7ar7@gmail.com"
 */

SELECT projects.id, projects.title, projects.author_user_id FROM projects
  JOIN users ON projects.author_user_id = users.id
  WHERE UPPER(users.email) = UPPER("ru7ar7@gmail.com")
;


/*
  Запрос:
  Получить список из всех проектов для одного пользователя с именем "ru"
 */

SELECT projects.id, projects.title, projects.author_user_id FROM projects
  JOIN users ON projects.author_user_id = users.id
  WHERE UPPER(users.name) = UPPER("ru")
;
