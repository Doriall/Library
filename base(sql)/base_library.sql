-- phpMyAdmin SQL Dump
-- version 4.6.6
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Ноя 05 2019 г., 10:09
-- Версия сервера: 10.3.17-MariaDB-102+cba
-- Версия PHP: 7.1.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `darian`
--

-- --------------------------------------------------------

--
-- Структура таблицы `authors`
--

CREATE TABLE `authors` (
  `id` int(7) UNSIGNED NOT NULL,
  `a_fio` varchar(75) CHARACTER SET utf8mb4 NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `authors`
--

INSERT INTO `authors` (`id`, `a_fio`) VALUES
(1, 'Александр Сергеевич Пушкин'),
(2, 'Сергей Александрович Есенин'),
(3, 'Лев Николаевич Толстой'),
(4, 'Николай Васильевич Гоголь');

-- --------------------------------------------------------

--
-- Структура таблицы `books`
--

CREATE TABLE `books` (
  `id` int(7) UNSIGNED NOT NULL,
  `bookcase` tinyint(2) NOT NULL,
  `bookshelf` tinyint(2) NOT NULL,
  `id_books_type` int(7) NOT NULL,
  `on_hand` int(7) UNSIGNED NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `books`
--

INSERT INTO `books` (`id`, `bookcase`, `bookshelf`, `id_books_type`, `on_hand`) VALUES
(1, 1, 1, 1, 10),
(2, 6, 6, 2, 6),
(3, 2, 2, 3, 2),
(4, 2, 1, 4, 0),
(5, 2, 3, 5, 0),
(6, 3, 1, 6, 3),
(7, 3, 7, 6, 5),
(8, 1, 1, 7, 0),
(9, 1, 1, 7, 10),
(10, 1, 1, 7, 0);

-- --------------------------------------------------------

--
-- Структура таблицы `books_type`
--

CREATE TABLE `books_type` (
  `id` int(7) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `authors` varchar(300) NOT NULL,
  `categorys` varchar(300) NOT NULL,
  `year` smallint(4) UNSIGNED NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `books_type`
--

INSERT INTO `books_type` (`id`, `name`, `authors`, `categorys`, `year`) VALUES
(1, 'Сказка о царе Салтане о сыне его Гвидоне и о царевне лебеде', 'Александр Сергеевич Пушкин', 'Художественная литература;;Детская литература', 2007),
(2, 'Стихи', 'Александр Сергеевич Пушкин', 'Художественная литература', 2015),
(3, 'Черный человек', 'Сергей Александрович Есенин', 'Художественная литература', 2008),
(4, 'Анна Снегина', 'Сергей Александрович Есенин', 'Художественная литература', 2015),
(5, 'Анна Каренина', 'Лев Николаевич Толстой;;Сергей Александрович Есенин', 'Художественная литература', 2010),
(6, 'Три старца', 'Лев Николаевич Толстой', 'Художественная литература;;Семья, дом, дача', 2011),
(7, 'test2', 'Сергей Александрович Есенин', 'Компьютеры', 2011),
(8, 'test1', 'Лев Николаевич Толстой', 'Деловая литература', 1998);

-- --------------------------------------------------------

--
-- Структура таблицы `categorys`
--

CREATE TABLE `categorys` (
  `id` int(7) NOT NULL,
  `category` varchar(50) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `categorys`
--

INSERT INTO `categorys` (`id`, `category`) VALUES
(1, 'Художественная литература'),
(2, 'Компьютеры'),
(3, 'Деловая литература'),
(4, 'Наука, образование'),
(5, 'Школьные учебники'),
(6, 'Детская литература'),
(7, 'Семья, дом, дача'),
(8, 'Техника и технология'),
(9, 'Медицина, спорт, здоровье'),
(10, 'Общество, политика, религия'),
(11, 'Специальная и справочная литература'),
(12, 'Комиксы'),
(13, 'Журналы');

-- --------------------------------------------------------

--
-- Структура таблицы `students`
--

CREATE TABLE `students` (
  `id` int(7) UNSIGNED NOT NULL,
  `fio` varchar(75) NOT NULL,
  `class` varchar(3) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `students`
--

INSERT INTO `students` (`id`, `fio`, `class`) VALUES
(1, 'Иванов Иван Иванович', '1Б'),
(2, 'Петров Петр Петрович', '4А'),
(3, 'Сидоров Сидр Сидорович', '7Г'),
(4, 'Виссарион Лисньштах Игнатьевич', '11Х'),
(5, 'Семенов Михаил Сергеевич', '4А'),
(6, 'Семенов Михаил Сергеевич', '4А'),
(7, 'Сергеев Иван Герасимович', '2Б'),
(8, 'Иванов Иван Иванович', '1Б'),
(9, 'Иванов Иван Иванович', '1Б'),
(10, 'Смирнов Генадий Арнольдович', '7г'),
(11, 'Смирнов Потап Арнольдович', '7г');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `authors`
--
ALTER TABLE `authors`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `books`
--
ALTER TABLE `books`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `books_type`
--
ALTER TABLE `books_type`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `categorys`
--
ALTER TABLE `categorys`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `authors`
--
ALTER TABLE `authors`
  MODIFY `id` int(7) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT для таблицы `books`
--
ALTER TABLE `books`
  MODIFY `id` int(7) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT для таблицы `books_type`
--
ALTER TABLE `books_type`
  MODIFY `id` int(7) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT для таблицы `categorys`
--
ALTER TABLE `categorys`
  MODIFY `id` int(7) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
--
-- AUTO_INCREMENT для таблицы `students`
--
ALTER TABLE `students`
  MODIFY `id` int(7) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
