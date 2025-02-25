-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Feb 14, 2025 at 02:12 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `quiz_night`
--

-- --------------------------------------------------------

--
-- Table structure for table `answers`
--

CREATE TABLE `answers` (
  `id` int(11) NOT NULL,
  `question_id` int(11) NOT NULL,
  `answer_text` text NOT NULL,
  `is_correct` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `answers`
--

INSERT INTO `answers` (`id`, `question_id`, `answer_text`, `is_correct`) VALUES
(1, 1, 'Córdoba', 0),
(2, 1, 'Buenos Aires', 1),
(3, 1, 'Mendoza', 0),
(4, 1, 'Rosario', 0),
(5, 2, 'Montréal', 0),
(6, 2, 'Toronto', 0),
(7, 2, 'Ottawa', 1),
(8, 2, 'Québec', 0),
(9, 3, 'Nairobi', 1),
(10, 3, 'Mombasa', 0),
(11, 3, 'Nakuru', 0),
(12, 3, 'Kampala', 0),
(13, 4, 'Queenstown', 0),
(14, 4, 'Auckland', 0),
(15, 4, 'Christchurch', 0),
(16, 4, 'Wellington', 1),
(17, 5, 'Karachi', 0),
(18, 5, 'Multan', 0),
(19, 5, 'Hyderabad', 0),
(20, 5, 'Islamabad', 1),
(21, 6, 'Sofia', 0),
(22, 6, 'Bratislava', 0),
(23, 6, 'Budapest', 1),
(24, 6, 'Bucarest', 0),
(25, 7, 'La Voie Lactée', 1),
(26, 7, 'Andromède', 0),
(27, 7, 'Omega Centuri', 0),
(28, 7, 'Galaxie du Triangle', 0),
(29, 8, '5', 0),
(30, 8, '6', 0),
(31, 8, '7', 0),
(32, 8, '8', 1),
(33, 9, 'Neptune', 0),
(34, 9, 'Jupiter', 1),
(35, 9, 'Saturne', 0),
(36, 9, 'Soleil', 0),
(37, 10, 'La Terre', 0),
(38, 10, 'Venus', 0),
(39, 10, 'Mars', 0),
(40, 10, 'Mercure', 1),
(41, 11, '57', 0),
(42, 11, '72', 0),
(43, 11, '95', 1),
(44, 11, '195', 0),
(45, 12, 'Neptune', 0),
(46, 12, 'La Terre', 1),
(47, 12, 'Mars', 0),
(48, 12, 'Uranus', 0),
(49, 13, '4,5 milliard d\'années', 1),
(50, 13, '5,4 milliard d\'années', 0),
(51, 13, '45 milliard d\'années', 0),
(52, 13, '54 milliard d\'années', 0),
(53, 14, '6', 0),
(54, 14, '7', 1),
(55, 14, '8', 0),
(56, 14, '9', 0),
(57, 15, 'Mercure', 0),
(58, 15, 'Mars', 0),
(59, 15, 'Vénus', 1),
(60, 15, 'La Terre', 0),
(61, 16, 'les corons', 0),
(62, 16, 'le charbon', 1),
(63, 16, 'l\'horizon', 0),
(64, 16, 'des mineurs de fond', 0);

-- --------------------------------------------------------

--
-- Table structure for table `questions`
--

CREATE TABLE `questions` (
  `id` int(11) NOT NULL,
  `quiz_id` int(11) NOT NULL,
  `question_text` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `questions`
--

INSERT INTO `questions` (`id`, `quiz_id`, `question_text`) VALUES
(1, 1, 'Quelle est la capitale de l\'Argentine ?'),
(2, 1, 'Quelle est la capitale du Canada ?'),
(3, 1, 'Quelle est la capitale du Kenya ?'),
(4, 1, 'Quelle est la capitale de la Nouvelle-Zélande ?'),
(5, 1, 'Quelle est la capitale du Pakistan ?'),
(6, 1, 'Quelle est la capitale de la Hongrie ?'),
(7, 2, 'A quelle galaxie appartient notre système solaire ?'),
(8, 2, 'Combien de planètes il y a-t-il dans le système solaire ?'),
(9, 2, 'Quelle est la plus grande planète du système solaire ?'),
(10, 2, 'Quelle est la planète la plus proche du Soleil ?'),
(11, 2, 'Combien de satellites naturels a Jupiter ?'),
(12, 2, 'Quelle planète est composée à 71% d\'eau ?'),
(13, 2, 'Quel est l\'âge de la planète Terre ?'),
(14, 2, 'Combien d\'anneaux possède Saturne ?'),
(15, 2, 'Quelle est la planète la plus chaude ?'),
(16, 2, 'La terre, c était...');

-- --------------------------------------------------------

--
-- Table structure for table `quizzes`
--

CREATE TABLE `quizzes` (
  `id` int(11) NOT NULL,
  `name` varchar(191) NOT NULL,
  `image` varchar(191) NOT NULL,
  `description` text NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `quizzes`
--

INSERT INTO `quizzes` (`id`, `name`, `image`, `description`, `created_by`, `created_at`) VALUES
(1, 'Capitales du monde', 'https://www.govloop.com/wp-content/uploads/2014/12/geography-e1501769793160.jpg', 'Quiz des capitales', 1, '2025-02-12 18:59:35'),
(2, 'Espace', 'https://offloadmedia.feverup.com/marseillesecrete.com/wp-content/uploads/2022/06/13123350/planetes-1024x639.jpg', 'Quiz sur notre système solaire', 1, '2025-02-12 22:49:29');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(191) NOT NULL,
  `email` varchar(191) NOT NULL,
  `password` varchar(191) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `created_at`) VALUES
(1, 'Noa', 'utilisateur@gmail.com', '$2y$10$OiwKXbNXQYIKAuVo/zUUi.e4px31lLnwHR3iJbIjL3Naa45TqUY1C', '2025-02-12 18:53:48');
-- --------------------------------------------------------

--
-- Table structure for table `users_score`
--

CREATE TABLE `users_score` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `score` int(11) NOT NULL,
  `completed_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `answers`
--
ALTER TABLE `answers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `question_id` (`question_id`);

--
-- Indexes for table `questions`
--
ALTER TABLE `questions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `quiz_id` (`quiz_id`);

--
-- Indexes for table `quizzes`
--
ALTER TABLE `quizzes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`),
  ADD KEY `created_by` (`created_by`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `users_score`
--
ALTER TABLE `users_score`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `answers`
--
ALTER TABLE `answers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=65;

--
-- AUTO_INCREMENT for table `questions`
--
ALTER TABLE `questions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `quizzes`
--
ALTER TABLE `quizzes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users_score`
--
ALTER TABLE `users_score`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `answers`
--
ALTER TABLE `answers`
  ADD CONSTRAINT `answers_ibfk_1` FOREIGN KEY (`question_id`) REFERENCES `questions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `questions`
--
ALTER TABLE `questions`
  ADD CONSTRAINT `questions_ibfk_1` FOREIGN KEY (`quiz_id`) REFERENCES `quizzes` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `quizzes`
--
ALTER TABLE `quizzes`
  ADD CONSTRAINT `quizzes_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `users_score`
--
ALTER TABLE `users_score`
  ADD CONSTRAINT `users_score_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;