-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Czas generowania: 02 Gru 2021, 02:08
-- Wersja serwera: 10.4.21-MariaDB
-- Wersja PHP: 8.0.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Baza danych: `programowanie`
--

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `posts`
--

CREATE TABLE `posts` (
  `id` int(11) NOT NULL,
  `isPrivate` tinyint(1) NOT NULL,
  `title` varchar(64) NOT NULL,
  `message` text NOT NULL,
  `sender` int(11) NOT NULL,
  `receiver` int(11) DEFAULT NULL,
  `date` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Zrzut danych tabeli `posts`
--

INSERT INTO `posts` (`id`, `isPrivate`, `title`, `message`, `sender`, `receiver`, `date`) VALUES
(1, 0, 'Wiadomość do wszystkich', 'Sample text', 1, NULL, '2021-12-01 21:38:11'),
(2, 1, 'Wiadomość do samego siebie', 'Prywatna wiadomość do samego siebie', 1, 1, '2021-12-01 21:41:16'),
(3, 1, 'example', 'ex', 1, 1, '2021-12-01 22:15:27');

-- --------------------------------------------------------

--
-- Zastąpiona struktura widoku `private_posts`
-- (Zobacz poniżej rzeczywisty widok)
--
CREATE TABLE `private_posts` (
`isPrivate` tinyint(1)
,`title` varchar(64)
,`message` text
,`senderId` int(11)
,`senderName` varchar(64)
,`senderSurname` varchar(64)
,`receiverId` int(11)
,`receiverName` varchar(64)
,`receiverSurname` varchar(64)
,`date` datetime
);

-- --------------------------------------------------------

--
-- Zastąpiona struktura widoku `public_posts`
-- (Zobacz poniżej rzeczywisty widok)
--
CREATE TABLE `public_posts` (
`isPrivate` tinyint(1)
,`title` varchar(64)
,`message` text
,`senderId` int(11)
,`senderName` varchar(64)
,`senderSurname` varchar(64)
,`date` datetime
);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(64) NOT NULL,
  `surname` varchar(64) NOT NULL,
  `email` varchar(64) NOT NULL,
  `role` enum('teacher','parent') NOT NULL,
  `password` char(60) NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Zrzut danych tabeli `users`
--

INSERT INTO `users` (`id`, `name`, `surname`, `email`, `role`, `password`, `updated_at`, `created_at`) VALUES
(1, 'Jan', 'Kowalski', 'mail@fake.com', 'teacher', '$2a$10$QGRzbEYPJU33p2UYUo/XNOgsFhUdIi.dbsau9pqxBuSVfOfpfJ0TW', '2021-12-02 00:07:38', '0000-00-00 00:00:00'),
(2, 'Piotr', 'Nowak', 'marcinkolos99@gmail.com', 'teacher', '$2y$10$6STcJl3G1.o7xfa.Nmq5jO40pWX.Zfwe6atwDCU2RH/fUzC78pAtO', '2021-12-01 23:50:37', '2021-12-02 00:50:37');

-- --------------------------------------------------------

--
-- Struktura widoku `private_posts`
--
DROP TABLE IF EXISTS `private_posts`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `private_posts`  AS SELECT `posts`.`isPrivate` AS `isPrivate`, `posts`.`title` AS `title`, `posts`.`message` AS `message`, `posts`.`sender` AS `senderId`, `sender`.`name` AS `senderName`, `sender`.`surname` AS `senderSurname`, `posts`.`receiver` AS `receiverId`, `receiver`.`name` AS `receiverName`, `receiver`.`surname` AS `receiverSurname`, `posts`.`date` AS `date` FROM ((`posts` join `users` `sender` on(`posts`.`sender` = `sender`.`id`)) join `users` `receiver` on(`posts`.`receiver` = `receiver`.`id`)) WHERE `posts`.`isPrivate` = 1 ;

-- --------------------------------------------------------

--
-- Struktura widoku `public_posts`
--
DROP TABLE IF EXISTS `public_posts`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `public_posts`  AS SELECT `posts`.`isPrivate` AS `isPrivate`, `posts`.`title` AS `title`, `posts`.`message` AS `message`, `posts`.`sender` AS `senderId`, `sender`.`name` AS `senderName`, `sender`.`surname` AS `senderSurname`, `posts`.`date` AS `date` FROM (`posts` join `users` `sender` on(`posts`.`sender` = `sender`.`id`)) WHERE `posts`.`isPrivate` = 0 ;

--
-- Indeksy dla zrzutów tabel
--

--
-- Indeksy dla tabeli `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sender` (`sender`),
  ADD KEY `receiver` (`receiver`);

--
-- Indeksy dla tabeli `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT dla zrzuconych tabel
--

--
-- AUTO_INCREMENT dla tabeli `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT dla tabeli `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Ograniczenia dla zrzutów tabel
--

--
-- Ograniczenia dla tabeli `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `posts_ibfk_1` FOREIGN KEY (`sender`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `posts_ibfk_2` FOREIGN KEY (`receiver`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
