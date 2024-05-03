-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1
-- Время создания: Май 03 2024 г., 14:01
-- Версия сервера: 10.4.28-MariaDB
-- Версия PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `fix_phone`
--

-- --------------------------------------------------------

--
-- Структура таблицы `type_of_fault`
--

CREATE TABLE `type_of_fault` (
  `id` int(11) NOT NULL,
  `name_type` text NOT NULL,
  `additional_services` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`additional_services`))
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Дамп данных таблицы `type_of_fault`
--

INSERT INTO `type_of_fault` (`id`, `name_type`, `additional_services`) VALUES
(1, 'Замена экрана', '{\n  \"additional_services\": [\n    {\n      \"id\": 1,\n      \"name\": \"Оригинал\",\n      \"price\": 30\n    },\n    {\n      \"id\": 2,\n      \"name\": \"Не Оригинал\",\n      \"price\": 15\n    }\n  ]\n}'),
(2, 'Замена задней крышки', '{\n  \"additional_services\": [\n    {\n      \"id\": 1,\n      \"name\": \"Оригинал\",\n      \"price\": 60\n    },\n    {\n      \"id\": 2,\n      \"name\": \"Не Оригинал\",\n      \"price\": 20\n    }\n  ]\n}'),
(3, 'Замена камеры', '{\n  \"additional_services\": [\n    {\n      \"id\": 1,\n      \"name\": \"Оригинал\",\n      \"price\": 100\n    },\n    {\n      \"id\": 2,\n      \"name\": \"Не Оригинал\",\n      \"price\": 70\n    }\n  ]\n}');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `type_of_fault`
--
ALTER TABLE `type_of_fault`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `type_of_fault`
--
ALTER TABLE `type_of_fault`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
