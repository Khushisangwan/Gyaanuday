-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Apr 14, 2025 at 07:02 AM
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
-- Database: `gyaanuday`
--

-- --------------------------------------------------------

--
-- Table structure for table `bookmarks`
--

CREATE TABLE `bookmarks` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `project_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `project_id` int(11) NOT NULL,
  `comment` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`id`, `user_id`, `project_id`, `comment`, `created_at`) VALUES
(1, 2, 9, 'Good', '2025-04-13 10:17:53'),
(2, 2, 9, 'yess', '2025-04-13 10:18:07'),
(3, 2, 11, 'exam', '2025-04-13 10:18:45'),
(4, 2, 9, 'linkedin sucks', '2025-04-13 10:25:23'),
(8, 5, 17, 'Awesome Project', '2025-04-13 16:37:01'),
(9, 1, 17, 'good', '2025-04-13 17:45:50'),
(11, 9, 17, 'good', '2025-04-13 18:08:37');

-- --------------------------------------------------------

--
-- Table structure for table `likes`
--

CREATE TABLE `likes` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `project_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `likes`
--

INSERT INTO `likes` (`id`, `user_id`, `project_id`, `created_at`) VALUES
(2, 1, 9, '2025-04-13 09:43:02'),
(4, 1, 10, '2025-04-13 09:43:18'),
(8, 2, 9, '2025-04-13 09:45:59'),
(17, 2, 10, '2025-04-13 09:59:53'),
(18, 2, 11, '2025-04-13 10:04:02'),
(23, 1, 17, '2025-04-13 17:16:41'),
(25, 9, 17, '2025-04-13 18:08:28');

-- --------------------------------------------------------

--
-- Table structure for table `projects`
--

CREATE TABLE `projects` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `project_file` varchar(255) NOT NULL,
  `thumbnail` varchar(255) DEFAULT NULL,
  `description` text NOT NULL,
  `tags` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `projects`
--

INSERT INTO `projects` (`id`, `user_id`, `title`, `project_file`, `thumbnail`, `description`, `tags`, `created_at`) VALUES
(9, 1, 'Web Dev Codes', 'Screenshot_20250217_110103.png', 'thumb_67fa71c554dad7.17279829.png', 'Practicing code', 'Coding, PHP, HTML', '2025-04-12 13:59:33'),
(10, 1, 'Html Code', 'Screenshot_20250217_110103.png', 'thumb_67fa7f16282cc2.82984705.png', 'These are my codes. Download it.', 'Coding, Html', '2025-04-12 14:56:22'),
(11, 1, 'C++ Coding video', 'SampleVideo_1280x720_1mb.mp4', 'thumb_67fa95a4959721.05845070.png', 'Coding solutions are available.', 'Web Design,Coding, C++', '2025-04-12 16:32:36'),
(14, 5, 'AI-Based UI/UX Generator', 'AI-Based-UIUX-Generator.pdf', 'thumb_67fbdfe9d8f0d3.37347522.png', 'This presentation gives an overview of the AI-Based UI/UX Generator.', 'Web Development,UI/UX Design,AI & ML', '2025-04-13 16:01:45'),
(15, 5, 'Data Exploration', 'DATA-ANALYSIS.pdf', 'thumb_67fbe119e52c20.03882447.png', 'This dataset contains summary COVID-19 related cases as of 30th of June 2021.', 'Data Analysis,Web Development,Data Science', '2025-04-13 16:06:49'),
(16, 5, 'Artificial Intelligence Explained', 'Artificial Intelligence-Machine Learning.pdf', 'thumb_67fbe20027ea42.32403060.png', 'Specific defense-related AI applications are listed in this document.', 'AI & ML,Web Development,Cybersecurity', '2025-04-13 16:10:40'),
(17, 5, 'Artificial Intelligence Animated', 'aimlvideo.mp4', 'thumb_67fbe5bb2a4035.87725485.png', 'AI working explained through animated video.', 'AI & ML,Robotics,Web Development', '2025-04-13 16:26:35');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `profile_photo` varchar(255) DEFAULT NULL,
  `bio` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `created_at`, `profile_photo`, `bio`) VALUES
(1, 'niki', 'gurbakshkaur127@gmail.com', '$2y$10$xjliDhFSYv1AaoudSjgTW.9ghp4elG9sY1R/5t32f/8Hlq1l9sZ4y', '2025-04-12 08:09:35', 'profile_67faab454c420.jpg', 'updated bio'),
(2, 'abc', 'joe@schmoe.com', '$2y$10$vcK/KT.ss7bXTH7unjyanuWa3bg0UsXLAds77yVu6WkdFexPerRlG', '2025-04-12 08:10:18', 'profile_67fb8c5705e36.png', 'asdfsdf'),
(3, 'Nikita', 'technicalfun55555@gmail.com', '$2y$10$58d.R5PuyUsEcneedDuHn.5K9sHElbkOFclr7Qsp0Ij5vHh1DPElu', '2025-04-13 12:49:29', NULL, NULL),
(4, 'Nikita', 'nikita@gmail.com', '$2y$10$yMui/.srhJrqXviUnM5lSO0z8BbTJ6aE4uXqHvQjOfR7xHu6jhjQS', '2025-04-13 12:52:02', 'profile_67fbb4269bbdb.png', ''),
(5, 'abc', 'sdf@fds', '$2y$10$WbNX5tyPPRIjVRuAzX1g/uCZLsAeSUS6nfJPsuQcf7ZYmN4BHyiFu', '2025-04-13 15:09:34', NULL, 'Update'),
(6, 'abc', 'email@23.com', '$2y$10$cA/hAy.cjaap9qy.RbTIQumUI7zj3892E5VFc0wt8o7UDkqyoysra', '2025-04-13 17:18:44', NULL, NULL),
(7, 'dummy', 'demo@gmail.com', '$2y$10$lf1zfoFmdOcFjm4EupsGoeR4KOGHsnPSwSXbuIHpKIfygC.J8yDgC', '2025-04-13 17:50:20', '7_1744566711_ca.png', 'Update bio'),
(8, 'demo1', 'demo1@gmail.com', '$2y$10$bO58.62P9HVvq4x5mngseOnmXxlGTqRdLl9zx041ixFgpMWbbnKZK', '2025-04-13 18:02:48', NULL, NULL),
(9, 'demo2', 'demo3@gmail.com', '$2y$10$LXu2OAeOZWeau8YH0YfJkOTvCMKPzqwkNf7m1jlGWAkRFiz4yhXVi', '2025-04-13 18:06:26', '9_1744567667_Screenshot_20250323_214605.png', 'update bio');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bookmarks`
--
ALTER TABLE `bookmarks`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_bookmark` (`user_id`,`project_id`),
  ADD KEY `project_id` (`project_id`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `project_id` (`project_id`);

--
-- Indexes for table `likes`
--
ALTER TABLE `likes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_like` (`user_id`,`project_id`),
  ADD KEY `project_id` (`project_id`);

--
-- Indexes for table `projects`
--
ALTER TABLE `projects`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bookmarks`
--
ALTER TABLE `bookmarks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `likes`
--
ALTER TABLE `likes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `projects`
--
ALTER TABLE `projects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bookmarks`
--
ALTER TABLE `bookmarks`
  ADD CONSTRAINT `bookmarks_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `bookmarks_ibfk_2` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `comments_ibfk_2` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `likes`
--
ALTER TABLE `likes`
  ADD CONSTRAINT `likes_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `likes_ibfk_2` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `projects`
--
ALTER TABLE `projects`
  ADD CONSTRAINT `projects_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
