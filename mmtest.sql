-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
--
-- Хост: 127.0.0.1
-- Время создания: Мар 12 2018 г., 07:40
-- Версия сервера: 5.5.25
-- Версия PHP: 5.4.45

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- База данных: `mmtest`
--

-- --------------------------------------------------------

--
-- Структура таблицы `logoperations`
--

CREATE TABLE IF NOT EXISTS `logoperations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL,
  `date_add` datetime NOT NULL,
  `operation` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `order_id` (`order_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=27 ;

--
-- Дамп данных таблицы `logoperations`
--

INSERT INTO `logoperations` (`id`, `order_id`, `date_add`, `operation`) VALUES
(11, 17, '2018-03-12 03:30:25', 'changed status to new'),
(12, 18, '2018-03-12 03:30:35', 'changed status to new'),
(13, 19, '2018-03-12 04:03:46', 'changed status to new'),
(14, 20, '2018-03-12 04:33:30', 'changed status to new'),
(15, 21, '2018-03-12 04:34:01', 'changed status to new'),
(16, 22, '2018-03-12 04:35:18', 'changed status to new'),
(17, 23, '2018-03-12 06:00:07', 'changed status to new'),
(18, 20, '2018-03-12 06:28:09', 'changed status to new'),
(19, 24, '2018-03-12 06:29:38', 'changed status to new'),
(20, 25, '2018-03-12 06:30:00', 'changed status to new'),
(21, 26, '2018-03-12 06:30:19', 'changed status to new'),
(22, 27, '2018-03-12 06:36:21', 'changed status to new'),
(23, 20, '2018-03-12 06:48:44', 'changed status to closed'),
(24, 20, '2018-03-12 06:49:04', 'changed status to new'),
(25, 23, '2018-03-12 07:26:48', 'changed status to canceled'),
(26, 23, '2018-03-12 07:27:00', 'changed status to new');

-- --------------------------------------------------------

--
-- Структура таблицы `orderrefproducts`
--

CREATE TABLE IF NOT EXISTS `orderrefproducts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `order_id` (`order_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=33 ;

--
-- Дамп данных таблицы `orderrefproducts`
--

INSERT INTO `orderrefproducts` (`id`, `order_id`, `product_id`) VALUES
(22, 17, 5),
(23, 17, 3),
(24, 18, 4),
(25, 18, 2),
(26, 19, 5),
(27, 19, 4),
(28, 19, 3),
(29, 19, 2),
(30, 19, 1),
(31, 23, 5),
(32, 27, 3);

-- --------------------------------------------------------

--
-- Структура таблицы `orders`
--

CREATE TABLE IF NOT EXISTS `orders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `status` enum('new','confirmed','canceled','closed') NOT NULL DEFAULT 'new',
  `user_id` int(11) NOT NULL,
  `date_add` datetime NOT NULL,
  `date_change` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=28 ;

--
-- Дамп данных таблицы `orders`
--

INSERT INTO `orders` (`id`, `status`, `user_id`, `date_add`, `date_change`) VALUES
(17, 'new', 5, '2018-03-12 03:30:25', '2018-03-12 07:27:02'),
(18, 'new', 5, '2018-03-12 03:30:35', '2018-03-12 07:27:02'),
(19, 'new', 5, '2018-03-12 04:03:46', '2018-03-12 07:27:02'),
(20, 'new', 5, '2018-03-12 04:33:30', '2018-03-12 07:27:02'),
(21, 'new', 5, '2018-03-12 04:34:01', '2018-03-12 07:27:02'),
(22, 'new', 5, '2018-03-12 04:35:18', '2018-03-12 07:27:02'),
(23, 'new', 5, '2018-03-12 06:00:07', '2018-03-12 07:27:02'),
(24, 'new', 5, '2018-03-12 06:29:38', '2018-03-12 07:27:02'),
(25, 'new', 5, '2018-03-12 06:30:00', '2018-03-12 07:27:02'),
(26, 'new', 5, '2018-03-12 06:30:19', '2018-03-12 07:27:02'),
(27, 'new', 5, '2018-03-12 06:36:21', '2018-03-12 07:27:02');

-- --------------------------------------------------------

--
-- Структура таблицы `products`
--

CREATE TABLE IF NOT EXISTS `products` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `articul` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `price` int(11) NOT NULL,
  `num` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- Дамп данных таблицы `products`
--

INSERT INTO `products` (`id`, `articul`, `name`, `description`, `price`, `num`) VALUES
(1, '111-2222', 'Тапки', 'Домашние', 500, 5),
(2, '333-1117622', 'Ботинки', 'Зимние', 1500, 6),
(3, '444-333744', 'Перчатки', 'Зимние', 1000, 7),
(4, '64-323477', 'Часы', 'Наручные', 3000, 4),
(5, '2-8678765', 'Колесо', 'Велосипедное', 2000, 7);

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `date_add` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `date_add`) VALUES
(4, 'Вася', 'vasya@mm.ru', '2018-03-11 20:03:45'),
(5, 'Петя', 'petr@mm.ru', '2018-03-11 20:04:13');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
