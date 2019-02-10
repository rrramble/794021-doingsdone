CREATE DATABASE IF NOT EXISTS 794021_doingsdone
  DEFAULT CHARACTER SET utf8
  DEFAULT COLLATE utf8_general_ci;

CREATE TABLE projects (
  id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(60) NOT NULL,
  user_id INT NOT NULL
);

CREATE TABLE tasks (
  id INT AUTO_INCREMENT PRIMARY KEY,
  date_created TIMESTAMP NOT NULL,
  date_completed TIMESTAMP NULL,
  status_id INT NOT NULL,
  title VARCHAR(60) NOT NULL,
  file_path VARCHAR(1024) NULL,
  due_date TIMESTAMP NULL
);

CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  date_created TIMESTAMP NOT NULL,
  email VARCHAR(254) UNIQUE NOT NULL,
  name VARCHAR(68) NOT NULL,
  password_hash VARCHAR(266) NOT NULL
);

/*  About email length
    Although name part may be up to 64 and domain's up to 255,
    there is a restriction of mailbox: 256, including '<' and '>'
    which gives 254 = 256 - 2 character long email fields.
    See:
    https://blog.moonmail.io/what-is-the-maximum-length-of-a-valid-email-address-f712c6c4bc93
 */

/*  About name length
    Name might be constited of first, second (patronimic), and last.
    Mastercard limits first and second up to 22.
    My consideration is to 22 + 22 + 22 = 66,
    multiplied to UTF's length of 4,
    plus 2 spaces between the parts.
    So: (66 * 4) + 2 * 1 = 266
 */


CREATE TABLE statuses (
  id INT PRIMARY KEY,
  title VARCHAR(100) NOT NULL
);
