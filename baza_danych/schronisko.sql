-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Czas generowania: 25 Maj 2021, 00:33
-- Wersja serwera: 10.4.18-MariaDB
-- Wersja PHP: 8.0.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Baza danych: `schronisko`
--

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `animals`
--

CREATE TABLE `animals` (
  `animal_id` int(11) NOT NULL,
  `imie` varchar(45) NOT NULL,
  `nr_klatki` int(11) DEFAULT NULL,
  `opiekun` int(11) DEFAULT NULL,
  `rodzaj` varchar(45) DEFAULT NULL,
  `plec` enum('samiec','samica') DEFAULT NULL,
  `wykastrowany` enum('tak','nie') DEFAULT NULL,
  `nr_chipa` int(11) DEFAULT NULL,
  `szacowany_wiek` int(11) DEFAULT NULL COMMENT 'wiek liczy się w latach ziemskich',
  `data_dodania` date DEFAULT NULL,
  `stan` mediumtext DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `imie` varchar(45) DEFAULT NULL,
  `nazwisko` varchar(45) DEFAULT NULL,
  `login` varchar(60) NOT NULL,
  `password` char(60) NOT NULL,
  `uprawnienia` enum('admin','opiekun') NOT NULL,
  `data_dolaczenia` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Zrzut danych tabeli `users`
--

INSERT INTO `users` (`user_id`, `imie`, `nazwisko`, `login`, `password`, `uprawnienia`, `data_dolaczenia`) VALUES
(1, NULL, NULL, 'test1@email.pl', '$2y$10$gxBp0cq7k6lMTPN61ulesezxPHr0xJ2OOwqbXXaSW/k5pJ4sQH8s.', 'admin', '2021-05-24'),
(2, 'imie', 'nazwisko', 'test2', '$2y$10$SoxIAKC8zTLaj.D4RCoTDuJ5xJyyyORZxGKE6icQBAqDtWX5zDtAS', 'opiekun', '2021-05-25');

--
-- Indeksy dla zrzutów tabel
--

--
-- Indeksy dla tabeli `animals`
--
ALTER TABLE `animals`
  ADD PRIMARY KEY (`animal_id`),
  ADD UNIQUE KEY `animal_id_UNIQUE` (`animal_id`),
  ADD UNIQUE KEY `nr chipa_UNIQUE` (`nr_chipa`),
  ADD KEY `fk_animals_users_idx` (`opiekun`);

--
-- Indeksy dla tabeli `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `login_UNIQUE` (`login`),
  ADD UNIQUE KEY `user_id_UNIQUE` (`user_id`);

--
-- AUTO_INCREMENT dla zrzuconych tabel
--

--
-- AUTO_INCREMENT dla tabeli `animals`
--
ALTER TABLE `animals`
  MODIFY `animal_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT dla tabeli `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Ograniczenia dla zrzutów tabel
--

--
-- Ograniczenia dla tabeli `animals`
--
ALTER TABLE `animals`
  ADD CONSTRAINT `fk_animals_users` FOREIGN KEY (`opiekun`) REFERENCES `users` (`user_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
