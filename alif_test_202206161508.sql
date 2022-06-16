--
-- Скрипт сгенерирован Devart dbForge Studio 2020 for MySQL, Версия 9.0.470.0
-- Домашняя страница продукта: http://www.devart.com/ru/dbforge/mysql/studio
-- Дата скрипта: 16.06.2022 15:08:41
-- Версия сервера: 10.2.6
-- Версия клиента: 4.1
--

-- 
-- Отключение внешних ключей
-- 
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;

-- 
-- Установить режим SQL (SQL mode)
-- 
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- 
-- Установка кодировки, с использованием которой клиент будет посылать запросы на сервер
--
SET NAMES 'utf8';

--
-- Установка базы данных по умолчанию
--
USE alif_test;

--
-- Удалить таблицу `bron`
--
DROP TABLE IF EXISTS bron;

--
-- Удалить таблицу `client`
--
DROP TABLE IF EXISTS client;

--
-- Удалить таблицу `room`
--
DROP TABLE IF EXISTS room;

--
-- Установка базы данных по умолчанию
--
USE alif_test;

--
-- Создать таблицу `room`
--
CREATE TABLE room (
  id int(11) NOT NULL AUTO_INCREMENT,
  name varchar(255) DEFAULT NULL,
  status int(11) DEFAULT NULL,
  PRIMARY KEY (id)
)
ENGINE = INNODB,
AUTO_INCREMENT = 6,
AVG_ROW_LENGTH = 3276,
CHARACTER SET utf8,
COLLATE utf8_general_ci;

--
-- Создать таблицу `client`
--
CREATE TABLE client (
  id int(11) NOT NULL AUTO_INCREMENT,
  name varchar(50) DEFAULT NULL,
  email varchar(50) DEFAULT NULL,
  status varchar(255) DEFAULT NULL,
  PRIMARY KEY (id)
)
ENGINE = INNODB,
AUTO_INCREMENT = 5,
AVG_ROW_LENGTH = 4096,
CHARACTER SET utf8,
COLLATE utf8_general_ci;

--
-- Создать таблицу `bron`
--
CREATE TABLE bron (
  id int(11) NOT NULL AUTO_INCREMENT,
  room_id int(11) DEFAULT NULL,
  time_of_bron timestamp NULL DEFAULT NULL,
  time_of_free timestamp NULL DEFAULT NULL,
  client_id int(11) DEFAULT NULL,
  PRIMARY KEY (id)
)
ENGINE = INNODB,
AUTO_INCREMENT = 18,
AVG_ROW_LENGTH = 8192,
CHARACTER SET utf8,
COLLATE utf8_general_ci;

-- 
-- Вывод данных для таблицы room
--
INSERT INTO room VALUES
(1, 'Кабинет 1', 1),
(2, 'Кабинет 2', 1),
(3, 'Кабинет 3', 1),
(4, 'Кабинет 4', 1),
(5, 'Кабинет 5', 1);

-- 
-- Вывод данных для таблицы client
--
INSERT INTO client VALUES
(1, 'John', 'nmuhammedov@imon.tj', '1'),
(2, 'Jack', 'nmuhammedov@imon.tj', '1'),
(3, 'Jennifer', 'nmuhammedov@imon.tj', '1'),
(4, 'Colby', 'nmuhammedov@imon.tj', '1');

-- 
-- Вывод данных для таблицы bron
--
INSERT INTO bron VALUES
(16, 1, '2022-06-16 11:00:35', '2022-06-17 11:00:35', 1),
(17, 2, '2022-06-16 11:00:36', '2022-06-30 11:00:36', 4);

-- 
-- Восстановить предыдущий режим SQL (SQL mode)
--
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;

-- 
-- Включение внешних ключей
-- 
/*!40014 SET FOREIGN_KEY_CHECKS = @OLD_FOREIGN_KEY_CHECKS */;