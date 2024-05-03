-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1
-- Время создания: Май 03 2024 г., 14:27
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
-- Структура таблицы `performers`
--

CREATE TABLE `performers` (
  `id` int(11) NOT NULL,
  `name_performer` text NOT NULL,
  `id_types_of_fault` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Дамп данных таблицы `performers`
--

INSERT INTO `performers` (`id`, `name_performer`, `id_types_of_fault`) VALUES
(1, 'Исполнитель 1', '1,2'),
(2, 'Исполнитель 2', '3'),
(3, 'Исполнитель 3', '1,2,3');

-- --------------------------------------------------------

--
-- Структура таблицы `requests`
--

CREATE TABLE `requests` (
  `id` int(11) NOT NULL,
  `id_performer` int(11) NOT NULL,
  `id_client` int(11) NOT NULL,
  `name_phone` text NOT NULL,
  `model_phone` text NOT NULL,
  `serial_number` text NOT NULL,
  `fault_type` text NOT NULL,
  `additional_service` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Дамп данных таблицы `requests`
--

INSERT INTO `requests` (`id`, `id_performer`, `id_client`, `name_phone`, `model_phone`, `serial_number`, `fault_type`, `additional_service`) VALUES
(1, 1, 2, '123123', '11111111111', 'asfdsadsfdsa', '1', '2'),
(2, 1, 2, '123123', '11111111111', 'asfdsadsfdsa', '1,2', '1,1');

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

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `login` text NOT NULL,
  `email` text NOT NULL,
  `password` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `login`, `email`, `password`) VALUES
(1, 'asd', 'asd@asd', '$2y$10$fkpLoxyX5sYe55oRLEFtMOuaszx64Xmgid1QZ8Vu1rlC9WTTKNl9i'),
(2, 'zxc', 'zxc@zxc.ru', '$2y$10$gG.h3m5wJOX0cJWsmCr1SO2X6FqYCDJAvVSxD3WDkt1lOdTnhSk36');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `performers`
--
ALTER TABLE `performers`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `requests`
--
ALTER TABLE `requests`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `type_of_fault`
--
ALTER TABLE `type_of_fault`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `performers`
--
ALTER TABLE `performers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT для таблицы `requests`
--
ALTER TABLE `requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT для таблицы `type_of_fault`
--
ALTER TABLE `type_of_fault`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
