-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 05, 2026 at 03:51 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_voice_system`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_approve_insight` (IN `p_post_id` INT)   BEGIN
    UPDATE posts SET status = 'Published'
    WHERE post_id = p_post_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_cast_vote` (IN `p_post_id` INT, IN `p_user_id` INT, IN `p_type` VARCHAR(10))   BEGIN
    DECLARE existing_vote VARCHAR(10);
    
    SELECT vote INTO existing_vote FROM votes WHERE post_id = p_post_id AND user_id = p_user_id LIMIT 1;
    
    IF existing_vote IS NULL THEN
        INSERT INTO votes (post_id, user_id, vote, created_at, updated_at) VALUES (p_post_id, p_user_id, p_type, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP);
    ELSEIF existing_vote = p_type THEN
        DELETE FROM votes WHERE post_id = p_post_id AND user_id = p_user_id;
    ELSE
        UPDATE votes SET vote = p_type, updated_at = CURRENT_TIMESTAMP WHERE post_id = p_post_id AND user_id = p_user_id;
    END IF;

    UPDATE posts
    SET upvotes = (SELECT COUNT(*) FROM votes WHERE post_id = p_post_id AND vote = 'up'),
        downvotes = (SELECT COUNT(*) FROM votes WHERE post_id = p_post_id AND vote = 'down'),
        vote_score = (SELECT COUNT(*) FROM votes WHERE post_id = p_post_id AND vote = 'up') - (SELECT COUNT(*) FROM votes WHERE post_id = p_post_id AND vote = 'down')
    WHERE post_id = p_post_id;

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_search_student_activity` (IN `p_student_name` VARCHAR(100))   BEGIN
    SELECT 
        u.name AS student_name, 
        u.student_num,
        e.title AS event_title, 
        p.insight, 
        p.status,
        p.vote_score
    FROM posts p
    JOIN users u ON p.user_id = u.user_id
    JOIN events e ON p.event_id = e.event_id
    WHERE u.name LIKE CONCAT('%', p_student_name, '%')
    ORDER BY p.created_at DESC;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `event_id` int(11) NOT NULL,
  `title` varchar(150) NOT NULL,
  `category` varchar(50) DEFAULT 'General',
  `description` text DEFAULT NULL,
  `event_date` datetime DEFAULT NULL,
  `end_date` datetime DEFAULT NULL,
  `status` enum('Upcoming','Ongoing','Finished') DEFAULT 'Upcoming'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`event_id`, `title`, `category`, `description`, `event_date`, `end_date`, `status`) VALUES
(20, 'Dept Orientation', 'General', 'dhsjkdaskjhdad', '2026-05-18 12:00:00', '2026-05-19 12:00:00', 'Upcoming'),
(21, 'TOOLS', 'Academics', 'Find you all', '2026-05-18 14:05:00', '2026-05-18 14:08:00', 'Finished');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `notif_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `event_id` int(11) DEFAULT NULL,
  `message` varchar(255) DEFAULT NULL,
  `type` varchar(30) NOT NULL DEFAULT 'info',
  `status` enum('Unread','Read') NOT NULL DEFAULT 'Unread',
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`notif_id`, `user_id`, `event_id`, `message`, `type`, `status`, `is_read`, `created_at`) VALUES
(214, 32, NULL, 'SHYRINE NICOLE ANN E. QUITO submitted a post to review.', 'Post_Review_31', 'Read', 1, '2026-05-18 05:35:39'),
(216, 32, NULL, 'SHYRINE NICOLE ANN E. QUITO submitted a post to review.', 'Post_Review_32', 'Read', 1, '2026-05-18 05:37:34'),
(218, 32, NULL, 'SHYRINE NICOLE ANN E. QUITO submitted a post to review.', 'Post_Review_33', 'Unread', 0, '2026-05-18 05:40:42'),
(220, 32, NULL, 'SHYRINE NICOLE ANN E. QUITO submitted a post to review.', 'Post_Review_34', 'Read', 1, '2026-05-18 05:44:18'),
(221, 32, NULL, 'SHYRINE NICOLE ANN E. QUITO submitted a post to review.', 'Post_Review_35', 'Unread', 0, '2026-05-18 05:45:09'),
(229, 32, NULL, 'SHYRINE NICOLE ANN E. QUITO submitted a post to review.', 'Post_Review_36', 'Unread', 0, '2026-05-18 06:09:31'),
(231, 32, NULL, 'SHYRINE NICOLE ANN E. QUITO submitted a post to review.', 'Post_Review_37', 'Unread', 0, '2026-05-18 06:11:04');

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `post_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `event_id` int(11) DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `insight` text NOT NULL,
  `status` enum('Pending','Published','Rejected') DEFAULT 'Pending',
  `is_anonymous` tinyint(1) DEFAULT 0,
  `upvotes` int(11) DEFAULT 0,
  `downvotes` int(11) DEFAULT 0,
  `vote_score` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`post_id`, `user_id`, `event_id`, `title`, `insight`, `status`, `is_anonymous`, `upvotes`, `downvotes`, `vote_score`, `created_at`, `updated_at`) VALUES
(31, 38, NULL, 'KAHIT ANO', 'WOW! COOL', 'Published', 1, 0, 1, -1, '2026-05-18 05:35:39', '2026-05-18 05:46:45'),
(32, 38, 20, 'KALAPASTANGAN', 'KALAPASTANGAN', 'Rejected', 0, 0, 0, 0, '2026-05-18 05:37:34', '2026-05-18 05:38:06'),
(33, 38, NULL, 'rerer', ',mefel;wkf', 'Published', 0, 1, 0, 1, '2026-05-18 05:40:42', '2026-05-18 05:42:36'),
(34, 38, NULL, 'Voting', 'Is good', 'Published', 1, 1, 0, 1, '2026-05-18 05:44:18', '2026-05-18 06:03:35'),
(35, 38, 20, 'IT', 'Cooming soon ...', 'Published', 1, 0, 1, -1, '2026-05-18 05:45:09', '2026-05-18 06:04:03'),
(36, 38, 20, 'asdasd', 'asd', 'Published', 1, 0, 0, 0, '2026-05-18 06:09:31', '2026-05-18 06:10:21'),
(37, 38, 21, 'asdasd', 'asd', 'Published', 1, 0, 0, 0, '2026-05-18 06:11:04', '2026-05-18 06:15:19');

--
-- Triggers `posts`
--
DELIMITER $$
CREATE TRIGGER `tr_calculate_post_score` BEFORE UPDATE ON `posts` FOR EACH ROW BEGIN
    SET NEW.vote_score = NEW.upvotes - NEW.downvotes;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `registrations`
--

CREATE TABLE `registrations` (
  `reg_id` int(11) NOT NULL,
  `event_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `attended_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `registrations`
--

INSERT INTO `registrations` (`reg_id`, `event_id`, `user_id`, `attended_at`) VALUES
(22, 21, 38, '2026-05-18 06:13:48');

-- --------------------------------------------------------

--
-- Table structure for table `surveys`
--

CREATE TABLE `surveys` (
  `survey_id` int(11) NOT NULL,
  `event_id` int(11) DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `survey_link` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `survey_responses`
--

CREATE TABLE `survey_responses` (
  `response_id` int(11) NOT NULL,
  `event_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `rating` tinyint(3) UNSIGNED NOT NULL,
  `feedback` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ;

--
-- Dumping data for table `survey_responses`
--

INSERT INTO `survey_responses` (`response_id`, `event_id`, `user_id`, `rating`, `feedback`, `created_at`) VALUES
(7, 21, 38, 4, 'asdsad', '2026-05-18 06:15:40');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `student_num` varchar(20) DEFAULT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `role` enum('Admin','Student','Officer','SSG') DEFAULT 'Student'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `student_num`, `name`, `email`, `password`, `role`) VALUES
(32, 'ADMIN', 'ADMIN 1', 'admin.one@olivarezcollege.edu.ph', '$2y$10$RE7fVhG/CXE5n0S1MtJB5.JxxYgSqEcPR.BM1ck4inxSbKaFU2f1C', 'Admin'),
(38, '241C-234', 'SHYRINE NICOLE ANN E. QUITO', 'shyrine.quito@olivarezcollege.edu.ph', '$2y$10$SFu3Q7t.9uSPAeyg8esgJumhCScBXUgroyrvcmrJnrbk.Fd9.ruIy', 'Student');

--
-- Triggers `users`
--
DELIMITER $$
CREATE TRIGGER `tr_cleanup_user_data` BEFORE DELETE ON `users` FOR EACH ROW BEGIN
    DELETE FROM survey_responses WHERE user_id = OLD.user_id;
    DELETE FROM votes WHERE user_id = OLD.user_id;
    DELETE FROM registrations WHERE user_id = OLD.user_id;

    DELETE FROM notifications WHERE user_id = OLD.user_id;

    DELETE FROM posts WHERE user_id = OLD.user_id;

    UPDATE posts p 
    SET 
        p.upvotes = (SELECT COUNT(*) FROM votes WHERE post_id = p.post_id AND vote = 'up'),
        p.downvotes = (SELECT COUNT(*) FROM votes WHERE post_id = p.post_id AND vote = 'down'),
        p.vote_score = (SELECT COUNT(*) FROM votes WHERE post_id = p.post_id AND vote = 'up') - (SELECT COUNT(*) FROM votes WHERE post_id = p.post_id AND vote = 'down');

    DELETE FROM notifications 
    WHERE (type LIKE 'Post_Review_%' OR type LIKE '%vote_group_%') 
      AND LOWER(message) LIKE CONCAT('%', LOWER(OLD.name), '%');
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `tr_format_user_data` BEFORE INSERT ON `users` FOR EACH ROW BEGIN
    SET NEW.name = UPPER(NEW.name);
    SET NEW.email = LOWER(NEW.email);
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `tr_protect_student_num` BEFORE UPDATE ON `users` FOR EACH ROW BEGIN
    IF (OLD.student_num <> NEW.student_num) THEN
        SET NEW.student_num = OLD.student_num;
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `comment_id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `content` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `votes`
--

CREATE TABLE `votes` (
  `vote_id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `vote` varchar(10) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `votes`
--

INSERT INTO `votes` (`vote_id`, `post_id`, `user_id`, `vote`, `created_at`, `updated_at`) VALUES
(79, 33, 38, 'up', '2026-05-18 05:42:32', '2026-05-18 05:42:36'),
(80, 31, 38, 'down', '2026-05-18 05:42:49', '2026-05-18 05:46:45'),
(81, 34, 32, 'up', '2026-05-18 06:03:35', '2026-05-18 06:03:35'),
(82, 35, 32, 'down', '2026-05-18 06:03:39', '2026-05-18 06:04:03');

-- --------------------------------------------------------

--
-- Stand-in structure for view `vw_event_participation_summary`
-- (See below for the actual view)
--
CREATE TABLE `vw_event_participation_summary` (
`event_id` int(11)
,`title` varchar(150)
,`category` varchar(50)
,`total_participants` bigint(21)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `vw_public_newsfeed`
-- (See below for the actual view)
--
CREATE TABLE `vw_public_newsfeed` (
`post_id` int(11)
,`student_name` varchar(100)
,`event_title` varchar(150)
,`insight` text
,`vote_score` int(11)
,`created_at` timestamp
);

-- --------------------------------------------------------

--
-- Structure for view `vw_event_participation_summary`
--
DROP TABLE IF EXISTS `vw_event_participation_summary`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vw_event_participation_summary`  AS SELECT `e`.`event_id` AS `event_id`, `e`.`title` AS `title`, `e`.`category` AS `category`, count(`r`.`reg_id`) AS `total_participants` FROM (`events` `e` left join `registrations` `r` on(`e`.`event_id` = `r`.`event_id`)) GROUP BY `e`.`event_id`, `e`.`title`, `e`.`category` ;

-- --------------------------------------------------------

--
-- Structure for view `vw_public_newsfeed`
--
DROP TABLE IF EXISTS `vw_public_newsfeed`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vw_public_newsfeed`  AS SELECT `p`.`post_id` AS `post_id`, `u`.`name` AS `student_name`, `e`.`title` AS `event_title`, `p`.`insight` AS `insight`, `p`.`vote_score` AS `vote_score`, `p`.`created_at` AS `created_at` FROM ((`posts` `p` join `users` `u` on(`p`.`user_id` = `u`.`user_id`)) join `events` `e` on(`p`.`event_id` = `e`.`event_id`)) WHERE `p`.`status` = 'Published' ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`event_id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`notif_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`post_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `event_id` (`event_id`);

--
-- Indexes for table `registrations`
--
ALTER TABLE `registrations`
  ADD PRIMARY KEY (`reg_id`),
  ADD UNIQUE KEY `uniq_event_registration` (`event_id`,`user_id`),
  ADD KEY `event_id` (`event_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `surveys`
--
ALTER TABLE `surveys`
  ADD PRIMARY KEY (`survey_id`),
  ADD KEY `event_id` (`event_id`);

--
-- Indexes for table `survey_responses`
--
ALTER TABLE `survey_responses`
  ADD PRIMARY KEY (`response_id`),
  ADD UNIQUE KEY `uniq_survey_response_per_user` (`event_id`,`user_id`),
  ADD KEY `idx_survey_responses_event` (`event_id`),
  ADD KEY `idx_survey_responses_user` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `student_num` (`student_num`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`comment_id`),
  ADD KEY `post_id` (`post_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `votes`
--
ALTER TABLE `votes`
  ADD PRIMARY KEY (`vote_id`),
  ADD UNIQUE KEY `uniq_vote_per_user` (`post_id`,`user_id`),
  ADD KEY `idx_votes_post` (`post_id`),
  ADD KEY `idx_votes_user` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `event_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `notif_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=235;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `post_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `registrations`
--
ALTER TABLE `registrations`
  MODIFY `reg_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `surveys`
--
ALTER TABLE `surveys`
  MODIFY `survey_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `survey_responses`
--
ALTER TABLE `survey_responses`
  MODIFY `response_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `comment_id` int(11) NOT NULL AUTO_INCREMENT;

-- AUTO_INCREMENT for table `votes`
--
ALTER TABLE `votes`
  MODIFY `vote_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=84;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `posts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `posts_ibfk_2` FOREIGN KEY (`event_id`) REFERENCES `events` (`event_id`);

--
-- Constraints for table `registrations`
--
ALTER TABLE `registrations`
  ADD CONSTRAINT `registrations_ibfk_1` FOREIGN KEY (`event_id`) REFERENCES `events` (`event_id`),
  ADD CONSTRAINT `registrations_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `surveys`
--
ALTER TABLE `surveys`
  ADD CONSTRAINT `surveys_ibfk_1` FOREIGN KEY (`event_id`) REFERENCES `events` (`event_id`);
--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`post_id`) REFERENCES `posts` (`post_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `comments_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
