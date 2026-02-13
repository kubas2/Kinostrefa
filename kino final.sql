-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 13, 2026 at 12:56 PM
-- Wersja serwera: 10.4.32-MariaDB
-- Wersja PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `kino2`
--

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `filmy`
--

CREATE TABLE `filmy` (
  `id` int(11) NOT NULL,
  `tytul` varchar(100) NOT NULL,
  `rokWydania` int(11) NOT NULL,
  `rezyser` varchar(100) NOT NULL,
  `gatunek` varchar(50) NOT NULL,
  `opis` text NOT NULL,
  `czas_trwania` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `filmy`
--

INSERT INTO `filmy` (`id`, `tytul`, `rokWydania`, `rezyser`, `gatunek`, `opis`, `czas_trwania`) VALUES
(1, 'Incepcja', 2010, 'Christopher Nolan', 'Sci-Fi', 'Kradzież sekretów poprzez sny.', 148),
(2, 'Skazani na Shawshank', 1994, 'Frank Darabont', 'Dramat', 'Historia nadziei i przyjaźni w więzieniu.', 142),
(3, 'Matrix', 1999, 'Lana i Lilly Wachowski', 'Sci-Fi', 'Rzeczywistość kontrolowana przez maszyny.', 136),
(4, 'Pulp Fiction', 1994, 'Quentin Tarantino', 'Gangsterski', 'Przeplatające się historie kryminalne.', 154),
(5, 'Forrest Gump', 1994, 'Robert Zemeckis', 'Dramat', 'Życie niezwykłego człowieka.', 142),
(6, 'Gladiator', 2000, 'Ridley Scott', 'Historyczny', 'Rzymski generał walczy o zemstę.', 155),
(7, 'Interstellar', 2014, 'Christopher Nolan', 'Sci-Fi', 'Podróż przez czas i przestrzeń.', 169),
(8, 'Zielona mila', 1999, 'Frank Darabont', 'Dramat', 'Więzienna historia z nadprzyrodzonym wątkiem.', 188),
(9, 'Ojciec chrzestny', 1972, 'Francis Ford Coppola', 'Gangsterski', 'Saga rodziny mafijnej.', 175),
(10, 'Avatar', 2009, 'James Cameron', 'Sci-Fi', 'Podróż na planetę Pandora.', 162),
(13, 'Szybcy i Sztywni', 2026, 'Mango', 'Akcja', 'GÅ‚Ã³wnym bohaterem jest Andrzej â€žSztywnyâ€ DrÄ…g â€“ byÅ‚y kierowca rajdowy.', 213);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `plakaty`
--

CREATE TABLE `plakaty` (
  `id` int(11) NOT NULL,
  `idFilmu` int(11) NOT NULL,
  `sciezka` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `plakaty`
--

INSERT INTO `plakaty` (`id`, `idFilmu`, `sciezka`) VALUES
(1, 1, 'inception.png'),
(2, 2, 'shawshank.png'),
(3, 3, 'matrix.png'),
(4, 4, 'pulp_fiction.png'),
(5, 5, 'forrest_gump.png'),
(6, 6, 'gladiator.png'),
(7, 7, 'interstellar.png'),
(8, 8, 'zielona_mila.png'),
(9, 9, 'godfather.png'),
(10, 10, 'avatar.png'),
(12, 13, 'szybcyisztywni.png');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `recenzje`
--

CREATE TABLE `recenzje` (
  `id` int(11) NOT NULL,
  `idFilmu` int(11) NOT NULL,
  `idUser` int(11) NOT NULL,
  `ocena` int(11) NOT NULL CHECK (`ocena` between 1 and 10),
  `opis` text DEFAULT NULL,
  `data_dodania` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `recenzje`
--

INSERT INTO `recenzje` (`id`, `idFilmu`, `idUser`, `ocena`, `opis`, `data_dodania`) VALUES
(1, 1, 1, 10, 'Arcydzieło science fiction.', '2026-01-28 13:05:33'),
(2, 1, 2, 9, 'Świetny film, ale wymaga skupienia.', '2026-01-28 13:05:33'),
(3, 2, 3, 10, 'Najlepszy dramat w historii kina.', '2026-01-28 13:05:33'),
(4, 3, 1, 9, 'Rewolucja w kinie akcji.', '2026-01-28 13:05:33'),
(5, 7, 2, 10, 'Film, który zostaje w głowie na długo.', '2026-01-28 13:05:33'),
(6, 10, 4, 6, 'Jest jak jest', '2026-01-28 13:17:57');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `rezerwacje`
--

CREATE TABLE `rezerwacje` (
  `id` int(11) NOT NULL,
  `idSeansu` int(11) NOT NULL,
  `idSiedzenia` int(11) NOT NULL,
  `idUser` int(11) NOT NULL,
  `status` enum('zarezerwowana','oplacona','anulowana') DEFAULT 'zarezerwowana',
  `data_rezerwacji` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `rezerwacje`
--

INSERT INTO `rezerwacje` (`id`, `idSeansu`, `idSiedzenia`, `idUser`, `status`, `data_rezerwacji`) VALUES
(1, 1, 14, 9, 'zarezerwowana', '2026-02-11 14:38:21'),
(2, 1, 4, 9, 'zarezerwowana', '2026-02-11 15:01:38'),
(3, 1, 5, 9, 'zarezerwowana', '2026-02-11 15:01:38'),
(7, 1, 6, 9, 'zarezerwowana', '2026-02-11 15:08:07'),
(8, 1, 21, 9, 'zarezerwowana', '2026-02-11 15:08:29'),
(9, 1, 3, 9, 'zarezerwowana', '2026-02-11 15:09:06'),
(11, 1, 20, 9, 'zarezerwowana', '2026-02-11 15:10:14'),
(13, 1, 16, 9, 'zarezerwowana', '2026-02-11 15:10:41');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `seanse`
--

CREATE TABLE `seanse` (
  `id` int(11) NOT NULL,
  `idFilmu` int(11) NOT NULL,
  `data_start` datetime NOT NULL,
  `sala` varchar(50) NOT NULL,
  `cena` decimal(6,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `seanse`
--

INSERT INTO `seanse` (`id`, `idFilmu`, `data_start`, `sala`, `cena`) VALUES
(1, 3, '2026-02-12 20:00:00', '1', 0.00),
(2, 3, '2026-02-14 22:00:00', '3', 0.00),
(3, 1, '2026-03-12 12:00:00', '2', 0.00);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `siedzenia`
--

CREATE TABLE `siedzenia` (
  `id` int(11) NOT NULL,
  `rzad` int(11) NOT NULL,
  `numer` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `siedzenia`
--

INSERT INTO `siedzenia` (`id`, `rzad`, `numer`) VALUES
(1, 1, 1),
(2, 1, 2),
(3, 1, 3),
(4, 1, 4),
(5, 1, 5),
(6, 1, 6),
(7, 1, 7),
(8, 1, 8),
(9, 1, 9),
(10, 1, 10),
(11, 1, 11),
(12, 1, 12),
(13, 1, 13),
(14, 1, 14),
(15, 1, 15),
(16, 2, 1),
(17, 2, 2),
(18, 2, 3),
(19, 2, 4),
(20, 2, 5),
(21, 2, 6),
(22, 2, 7),
(23, 2, 8),
(24, 2, 9),
(25, 2, 10),
(26, 2, 11),
(27, 2, 12),
(28, 2, 13),
(29, 2, 14),
(30, 2, 15),
(31, 3, 1),
(32, 3, 2),
(33, 3, 3),
(34, 3, 4),
(35, 3, 5),
(36, 3, 6),
(37, 3, 7),
(38, 3, 8),
(39, 3, 9),
(40, 3, 10),
(41, 3, 11),
(42, 3, 12),
(43, 3, 13),
(44, 3, 14),
(45, 3, 15),
(46, 4, 1),
(47, 4, 2),
(48, 4, 3),
(49, 4, 4),
(50, 4, 5),
(51, 4, 6),
(52, 4, 7),
(53, 4, 8),
(54, 4, 9),
(55, 4, 10),
(56, 4, 11),
(57, 4, 12),
(58, 4, 13),
(59, 4, 14),
(60, 4, 15),
(61, 5, 1),
(62, 5, 2),
(63, 5, 3),
(64, 5, 4),
(65, 5, 5),
(66, 5, 6),
(67, 5, 7),
(68, 5, 8),
(69, 5, 9),
(70, 5, 10),
(71, 5, 11),
(72, 5, 12),
(73, 5, 13),
(74, 5, 14),
(75, 5, 15),
(76, 6, 1),
(77, 6, 2),
(78, 6, 3),
(79, 6, 4),
(80, 6, 5),
(81, 6, 6),
(82, 6, 7),
(83, 6, 8),
(84, 6, 9),
(85, 6, 10),
(86, 6, 11),
(87, 6, 12),
(88, 6, 13),
(89, 6, 14),
(90, 6, 15),
(91, 7, 1),
(92, 7, 2),
(93, 7, 3),
(94, 7, 4),
(95, 7, 5),
(96, 7, 6),
(97, 7, 7),
(98, 7, 8),
(99, 7, 9),
(100, 7, 10),
(101, 7, 11),
(102, 7, 12),
(103, 7, 13),
(104, 7, 14),
(105, 7, 15),
(106, 8, 1),
(107, 8, 2),
(108, 8, 3),
(109, 8, 4),
(110, 8, 5),
(111, 8, 6),
(112, 8, 7),
(113, 8, 8),
(114, 8, 9),
(115, 8, 10),
(116, 8, 11),
(117, 8, 12),
(118, 8, 13),
(119, 8, 14),
(120, 8, 15),
(121, 9, 1),
(122, 9, 2),
(123, 9, 3),
(124, 9, 4),
(125, 9, 5),
(126, 9, 6),
(127, 9, 7),
(128, 9, 8),
(129, 9, 9),
(130, 9, 10),
(131, 9, 11),
(132, 9, 12),
(133, 9, 13),
(134, 9, 14),
(135, 9, 15),
(136, 10, 1),
(137, 10, 2),
(138, 10, 3),
(139, 10, 4),
(140, 10, 5),
(141, 10, 6),
(142, 10, 7),
(143, 10, 8),
(144, 10, 9),
(145, 10, 10),
(146, 10, 11),
(147, 10, 12),
(148, 10, 13),
(149, 10, 14),
(150, 10, 15);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `imie` varchar(50) DEFAULT NULL,
  `nazwisko` varchar(100) DEFAULT NULL,
  `miasto` varchar(100) DEFAULT NULL,
  `kodKraju` varchar(2) DEFAULT NULL,
  `registration_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `isadmin` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `imie`, `nazwisko`, `miasto`, `kodKraju`, `registration_date`, `isadmin`) VALUES
(1, 'adam', 'adam@wp.pl', '$2y$10$Mvkp2crG5tV7nfDPu6WxP.JhV3Nbh2NKCNO0Yg86iEYZR5JQaCo0a', 'Adam', 'Kowalski', NULL, NULL, '2026-01-28 13:05:33', 0),
(2, 'hania', 'hania@wp.pl', '$2y$10$TIfsonnn3lkAVWcBPaCTZe9xBlzTmaWVrJ2IZ.4uAc06.rbL/9SnC', 'Hania', 'Nowak', NULL, NULL, '2026-01-28 13:05:33', 0),
(3, 'tomasz', 'tomasz@wp.pl', '$2y$10$KgMk/S9AExiZtWiZEwCPaOztKcbpgLnZQ6q93oDjIZ.K.DtIw2aje', 'Tomasz', 'Lewandowski', NULL, NULL, '2026-01-28 13:05:33', 0),
(4, 'halohalo', 'londyn@interia.pl', 'fdsfdsfdsfds', 'Hanna', 'Baran', NULL, NULL, '2026-01-28 13:17:57', 0),
(5, 'acz', 'dwa@123', '$2y$10$xGFBxtj0e0H931zze2f0p.RLYwKhrbV/4JIZEhoB9qikf39XLWrsS', NULL, NULL, NULL, NULL, '2026-02-04 12:35:23', 1),
(6, 'ewq', '123@123', '$2y$10$HLTKgsVpjV9/sOi1t9XkUOjRhOalpU7PDygcLFGOTJl4tnigybn/W', NULL, NULL, NULL, NULL, '2026-02-04 15:03:55', 0),
(7, 'kuba5@gmail.com', 'kuba5@gmail.com', '$2y$10$Ar0suKaaw5RNGfgF4YoDnOA6Kc/NcxvPSZNMbXr26ZWnRc9Bp/kI2', NULL, NULL, NULL, NULL, '2026-02-06 11:08:30', 1),
(8, 'kuba6@gmail.com', 'kuba6@gmail.com', '$2y$10$uy61mBFIGc5z.eFXUHlK2ef7wKT1WAKbEGLQlDXZ/CYeQvhOisqcK', NULL, NULL, NULL, NULL, '2026-02-06 11:13:49', 0),
(9, 'kuba7@gmail.com', 'kuba7@gmail.com', '$2y$10$c5t70zkSKlQKBNsgDN3j8uwSASIq6wZxpv7x2vTSpTATQZADX.PCm', NULL, NULL, NULL, NULL, '2026-02-11 12:45:42', 0);

--
-- Indeksy dla zrzutów tabel
--

--
-- Indeksy dla tabeli `filmy`
--
ALTER TABLE `filmy`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `plakaty`
--
ALTER TABLE `plakaty`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idFilmu` (`idFilmu`);

--
-- Indeksy dla tabeli `recenzje`
--
ALTER TABLE `recenzje`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idFilmu` (`idFilmu`),
  ADD KEY `idUser` (`idUser`);

--
-- Indeksy dla tabeli `rezerwacje`
--
ALTER TABLE `rezerwacje`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `idSeansu` (`idSeansu`,`idSiedzenia`),
  ADD KEY `idSiedzenia` (`idSiedzenia`),
  ADD KEY `idUser` (`idUser`);

--
-- Indeksy dla tabeli `seanse`
--
ALTER TABLE `seanse`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idFilmu` (`idFilmu`);

--
-- Indeksy dla tabeli `siedzenia`
--
ALTER TABLE `siedzenia`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `rzad` (`rzad`,`numer`);

--
-- Indeksy dla tabeli `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `filmy`
--
ALTER TABLE `filmy`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `plakaty`
--
ALTER TABLE `plakaty`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `recenzje`
--
ALTER TABLE `recenzje`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `rezerwacje`
--
ALTER TABLE `rezerwacje`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `seanse`
--
ALTER TABLE `seanse`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `siedzenia`
--
ALTER TABLE `siedzenia`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=151;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `plakaty`
--
ALTER TABLE `plakaty`
  ADD CONSTRAINT `plakaty_ibfk_1` FOREIGN KEY (`idFilmu`) REFERENCES `filmy` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `recenzje`
--
ALTER TABLE `recenzje`
  ADD CONSTRAINT `recenzje_ibfk_1` FOREIGN KEY (`idFilmu`) REFERENCES `filmy` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `recenzje_ibfk_2` FOREIGN KEY (`idUser`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `rezerwacje`
--
ALTER TABLE `rezerwacje`
  ADD CONSTRAINT `rezerwacje_ibfk_1` FOREIGN KEY (`idSeansu`) REFERENCES `seanse` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `rezerwacje_ibfk_2` FOREIGN KEY (`idSiedzenia`) REFERENCES `siedzenia` (`id`),
  ADD CONSTRAINT `rezerwacje_ibfk_3` FOREIGN KEY (`idUser`) REFERENCES `users` (`id`);

--
-- Constraints for table `seanse`
--
ALTER TABLE `seanse`
  ADD CONSTRAINT `seanse_ibfk_1` FOREIGN KEY (`idFilmu`) REFERENCES `filmy` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
