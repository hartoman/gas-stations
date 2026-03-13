-- phpMyAdmin SQL Dump
-- version 4.9.5deb2
-- https://www.phpmyadmin.net/
--
-- Φιλοξενητής: localhost:3306
-- Χρόνος δημιουργίας: 11 Μαρ 2023 στις 15:15:19
-- Έκδοση διακομιστή: 8.0.32-0ubuntu0.20.04.2
-- Έκδοση PHP: 7.4.3-4ubuntu2.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Βάση δεδομένων: `chartomatsidis`
--

-- --------------------------------------------------------

--
-- Δομή πίνακα για τον πίνακα `announce`
--

CREATE TABLE `announce` (
  `announce_id` int NOT NULL,
  `announce_date` datetime DEFAULT NULL,
  `announce_title` varchar(255) DEFAULT NULL,
  `announce_body` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Άδειασμα δεδομένων του πίνακα `announce`
--

INSERT INTO `announce` (`announce_id`, `announce_date`, `announce_title`, `announce_body`) VALUES
(1, '2023-03-11 14:01:41', 'Πρώτη Ανακοίνωση', 'Κείμενο πρώτης ανακοίνωσης.'),
(2, '2023-03-11 14:01:49', 'Δεύτερη Ανακοίνωση', 'Κείμενο δεύτερης ανακοίνωσης.'),
(4, '2023-03-11 14:02:00', 'Τέταρτη Ανακοίνωση', 'Κείμενο τέταρτης ανακοίνωσης.'),
(5, '2023-03-11 14:03:13', 'Πέμπτη Ανακοίνωση', 'Κείμενο πέμπτης ανακοίνωσης');

-- --------------------------------------------------------

--
-- Δομή πίνακα για τον πίνακα `county`
--

CREATE TABLE `county` (
  `county_id` int NOT NULL,
  `county_name` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Άδειασμα δεδομένων του πίνακα `county`
--

INSERT INTO `county` (`county_id`, `county_name`) VALUES
(1, 'ΡΟΔΟΠΗΣ'),
(2, 'ΞΑΝΘΗΣ'),
(3, 'ΕΒΡΟΥ');

-- --------------------------------------------------------

--
-- Δομή πίνακα για τον πίνακα `fuel`
--

CREATE TABLE `fuel` (
  `fuel_id` int NOT NULL,
  `fuel_name` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Άδειασμα δεδομένων του πίνακα `fuel`
--

INSERT INTO `fuel` (`fuel_id`, `fuel_name`) VALUES
(1, 'Αμόλυβδη 95'),
(2, 'Πετρέλαιο Κίνησης'),
(3, 'Πετρέλαιο Θέρμανσης');

-- --------------------------------------------------------

--
-- Δομή πίνακα για τον πίνακα `municipality`
--

CREATE TABLE `municipality` (
  `municipal_id` int NOT NULL,
  `municipal_name` varchar(20) DEFAULT NULL,
  `belongs_to` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Άδειασμα δεδομένων του πίνακα `municipality`
--

INSERT INTO `municipality` (`municipal_id`, `municipal_name`, `belongs_to`) VALUES
(1, 'Κομοτηνης', 1),
(2, 'Μαρωνείας-Σαπών', 1),
(3, 'Ιάσμου', 1),
(4, 'Αλεξανδρούπολης', 3),
(5, 'Σαμοθράκης', 3),
(6, 'Ξάνθης', 2),
(7, 'Αβδήρων', 2);

-- --------------------------------------------------------

--
-- Δομή πίνακα για τον πίνακα `offers`
--

CREATE TABLE `offers` (
  `offer_id` int NOT NULL,
  `offered_by` int DEFAULT NULL,
  `offered_fuel` int DEFAULT NULL,
  `exp_date` date DEFAULT NULL,
  `price` decimal(3,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Άδειασμα δεδομένων του πίνακα `offers`
--

INSERT INTO `offers` (`offer_id`, `offered_by`, `offered_fuel`, `exp_date`, `price`) VALUES
(1, 2, 1, '2023-04-15', '1.90'),
(2, 2, 2, '2023-01-01', '1.20'),
(3, 2, 3, '2023-04-08', '1.70'),
(4, 3, 1, '2023-04-15', '1.50'),
(5, 3, 2, '2023-04-15', '3.00'),
(6, 3, 3, '2023-04-15', '5.00'),
(7, 4, 1, '2023-04-15', '5.50'),
(8, 4, 2, '2023-04-15', '5.60'),
(9, 4, 3, '2023-04-15', '5.00'),
(10, 5, 1, '2023-04-15', '0.01'),
(11, 5, 2, '2023-04-15', '0.02'),
(12, 5, 3, '2023-04-15', '0.03'),
(13, 6, 3, '2023-03-17', '2.00'),
(14, 6, 1, '2023-03-17', '1.23');

-- --------------------------------------------------------

--
-- Δομή πίνακα για τον πίνακα `users`
--

CREATE TABLE `users` (
  `user_id` int NOT NULL,
  `username` varchar(50) DEFAULT NULL,
  `user_password` varchar(50) DEFAULT NULL,
  `user_role` bit(1) DEFAULT NULL,
  `business_name` varchar(50) DEFAULT NULL,
  `AFM` int DEFAULT NULL,
  `user_address` varchar(50) DEFAULT NULL,
  `user_email` varchar(50) DEFAULT NULL,
  `lives_in` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Άδειασμα δεδομένων του πίνακα `users`
--

INSERT INTO `users` (`user_id`, `username`, `user_password`, `user_role`, `business_name`, `AFM`, `user_address`, `user_email`, `lives_in`) VALUES
(1, 'admin', 'password', b'0', NULL, NULL, NULL, 'helpdesk@kalyterakaysima.com', NULL),
(2, 'Prathrio1', 'YgraKaysima1', b'1', 'Πεντακάθαρη Βενζίνη', 999999999, 'Φιλήμωνος-Γεννηματά', 'venzinadiko1@hotmail.com', 1),
(3, 'Prathrio2', 'YgraKaysima2', b'1', 'Ανόθευτα Καύσιμα', 888888888, 'Μπακάλμπαση 1', 'venzinadiko2@hotmail.com', 1),
(4, 'Prathrio3', 'YgraKaysima3', b'1', 'Ο Χαρούμενος Κινητήρας', 777777777, 'Μπακάλμπαση 1', 'venzinadiko3@hotmail.com', 2),
(5, 'Prathrio4', 'YgraKaysima4', b'1', 'Το φτηνότερο', 666666666, 'Βασιλέως Αλεξάνδρου', 'venzinadiko4@hotmail.com', 4),
(6, 'Prathrio5', 'YgraKaysima5', b'1', 'Παραθαλάσσιο Φουλάρισμα', 555555555, 'Πόρτο Λάγος', 'venzinadiko5@hotmail.com', 7);

--
-- Ευρετήρια για άχρηστους πίνακες
--

--
-- Ευρετήρια για πίνακα `announce`
--
ALTER TABLE `announce`
  ADD PRIMARY KEY (`announce_id`);

--
-- Ευρετήρια για πίνακα `county`
--
ALTER TABLE `county`
  ADD PRIMARY KEY (`county_id`);

--
-- Ευρετήρια για πίνακα `fuel`
--
ALTER TABLE `fuel`
  ADD PRIMARY KEY (`fuel_id`);

--
-- Ευρετήρια για πίνακα `municipality`
--
ALTER TABLE `municipality`
  ADD PRIMARY KEY (`municipal_id`),
  ADD KEY `fk_belongsto` (`belongs_to`);

--
-- Ευρετήρια για πίνακα `offers`
--
ALTER TABLE `offers`
  ADD PRIMARY KEY (`offer_id`),
  ADD KEY `fk_offer_by` (`offered_by`),
  ADD KEY `fk_offer_fuel` (`offered_fuel`);

--
-- Ευρετήρια για πίνακα `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `AFM` (`AFM`),
  ADD KEY `fk_livesin` (`lives_in`);

--
-- AUTO_INCREMENT για άχρηστους πίνακες
--

--
-- AUTO_INCREMENT για πίνακα `announce`
--
ALTER TABLE `announce`
  MODIFY `announce_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT για πίνακα `county`
--
ALTER TABLE `county`
  MODIFY `county_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT για πίνακα `fuel`
--
ALTER TABLE `fuel`
  MODIFY `fuel_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT για πίνακα `municipality`
--
ALTER TABLE `municipality`
  MODIFY `municipal_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT για πίνακα `offers`
--
ALTER TABLE `offers`
  MODIFY `offer_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT για πίνακα `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Περιορισμοί για άχρηστους πίνακες
--

--
-- Περιορισμοί για πίνακα `municipality`
--
ALTER TABLE `municipality`
  ADD CONSTRAINT `fk_belongsto` FOREIGN KEY (`belongs_to`) REFERENCES `county` (`county_id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Περιορισμοί για πίνακα `offers`
--
ALTER TABLE `offers`
  ADD CONSTRAINT `fk_offer_by` FOREIGN KEY (`offered_by`) REFERENCES `users` (`user_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_offer_fuel` FOREIGN KEY (`offered_fuel`) REFERENCES `fuel` (`fuel_id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Περιορισμοί για πίνακα `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `fk_livesin` FOREIGN KEY (`lives_in`) REFERENCES `municipality` (`municipal_id`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
