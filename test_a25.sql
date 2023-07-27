-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Июл 27 2023 г., 20:02
-- Версия сервера: 10.8.4-MariaDB-log
-- Версия PHP: 8.1.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `test_a25`
--

-- --------------------------------------------------------

--
-- Структура таблицы `a25_products`
--

CREATE TABLE `a25_products` (
  `ID` int(11) NOT NULL,
  `NAME` varchar(100) NOT NULL,
  `PRICE` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Дамп данных таблицы `a25_products`
--

INSERT INTO `a25_products` (`ID`, `NAME`, `PRICE`) VALUES
(1, 'Авто 1', 1000),
(2, 'Авто 2', 1800),
(3, 'Авто 3', 2500);

-- --------------------------------------------------------

--
-- Структура таблицы `a25_services`
--

CREATE TABLE `a25_services` (
  `id` int(11) NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tarif` int(11) NOT NULL,
  `setting_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `a25_services`
--

INSERT INTO `a25_services` (`id`, `name`, `tarif`, `setting_id`) VALUES
(1, 'Детское кресло', 300, 1),
(2, 'Мойка авто', 600, 1),
(3, 'Видеорегистратор', 100, 1),
(4, 'Антирадар', 0, 1);

-- --------------------------------------------------------

--
-- Структура таблицы `a25_settings`
--

CREATE TABLE `a25_settings` (
  `ID` int(11) NOT NULL,
  `set_key` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Дамп данных таблицы `a25_settings`
--

INSERT INTO `a25_settings` (`ID`, `set_key`) VALUES
(1, 'services');

-- --------------------------------------------------------

--
-- Структура таблицы `a25_tarifs`
--

CREATE TABLE `a25_tarifs` (
  `id` int(11) NOT NULL,
  `pricePerDay` int(11) NOT NULL,
  `products_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `a25_tarifs`
--

INSERT INTO `a25_tarifs` (`id`, `pricePerDay`, `products_id`) VALUES
(1, 0, 1),
(2, 1000, 1),
(3, 900, 1),
(4, 800, 1),
(5, 700, 1),
(6, 10, 1),
(7, 15, 1),
(8, 30, 1),
(9, 0, 2),
(10, 2000, 2),
(11, 1800, 2),
(12, 1700, 2),
(13, 1500, 2),
(14, 4, 2),
(15, 11, 2),
(16, 30, 2);

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `a25_products`
--
ALTER TABLE `a25_products`
  ADD PRIMARY KEY (`ID`);

--
-- Индексы таблицы `a25_services`
--
ALTER TABLE `a25_services`
  ADD PRIMARY KEY (`id`),
  ADD KEY `setting_id` (`setting_id`);

--
-- Индексы таблицы `a25_settings`
--
ALTER TABLE `a25_settings`
  ADD PRIMARY KEY (`ID`);

--
-- Индексы таблицы `a25_tarifs`
--
ALTER TABLE `a25_tarifs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `products_id` (`products_id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `a25_products`
--
ALTER TABLE `a25_products`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT для таблицы `a25_services`
--
ALTER TABLE `a25_services`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT для таблицы `a25_settings`
--
ALTER TABLE `a25_settings`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT для таблицы `a25_tarifs`
--
ALTER TABLE `a25_tarifs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `a25_services`
--
ALTER TABLE `a25_services`
  ADD CONSTRAINT `a25_services_ibfk_1` FOREIGN KEY (`setting_id`) REFERENCES `a25_settings` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `a25_tarifs`
--
ALTER TABLE `a25_tarifs`
  ADD CONSTRAINT `a25_tarifs_ibfk_1` FOREIGN KEY (`products_id`) REFERENCES `a25_products` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
