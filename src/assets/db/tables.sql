-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 14, 2026 at 11:41 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `wiki`
--

-- --------------------------------------------------------

--
-- Table structure for table `articles`
--

CREATE TABLE `articles` (
  `id` int(11) NOT NULL,
  `name` varchar(60) NOT NULL,
  `description` varchar(1023) NOT NULL,
  `text` mediumtext NOT NULL,
  `code` mediumtext NOT NULL,
  `user_id` int(11) NOT NULL,
  `last_edit` timestamp(6) NOT NULL DEFAULT current_timestamp(6) ON UPDATE current_timestamp(6)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `articles`
--

INSERT INTO `articles` (`id`, `name`, `description`, `text`, `code`, `user_id`, `last_edit`) VALUES
(1, 'Getting Started with Docker', 'A beginner-friendly guide to running containers with Docker.', 'Full article body text goes here...', 'docker run -d -p 80:80 nginx', 1, '2026-04-22 12:49:25.194750'),
(2, 'Testing Creation', 'Description test', 'Text test', 'Code test', 2, '2026-04-28 14:00:08.561119'),
(3, 'Test Adding Article to DB', 'Description Lorem Ipsum', 'Explaining Lorem Ipsum', 'echo \'hello\';', 1, '2026-05-08 08:41:13.964292'),
(11, 'Installing OpenClaw', 'How to install OpenClaw', 'Bit more text about installing OpenClaw', 'sudo run openclaw', 1, '2026-05-13 13:31:10.106020');

-- --------------------------------------------------------

--
-- Table structure for table `images`
--

CREATE TABLE `images` (
  `id` int(11) NOT NULL,
  `description` varchar(255) NOT NULL,
  `last_edit` timestamp(6) NOT NULL DEFAULT current_timestamp(6) ON UPDATE current_timestamp(6),
  `path` varchar(255) NOT NULL,
  `article_id` int(11) NOT NULL,
  `display_order` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `images`
--

INSERT INTO `images` (`id`, `description`, `last_edit`, `path`, `article_id`, `display_order`) VALUES
(1, 'First description', '2026-05-12 09:53:26.890435', '\"\\Images\\limit.png\"', 1, 1),
(2, 'Second description', '2026-05-12 09:53:44.433934', '\"\\Images\\olifant.jpg\"', 1, 2);

-- --------------------------------------------------------

--
-- Table structure for table `join_articles_tags`
--

CREATE TABLE `join_articles_tags` (
  `article_id` int(11) NOT NULL,
  `tag_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `join_articles_tags`
--

INSERT INTO `join_articles_tags` (`article_id`, `tag_id`) VALUES
(1, 1),
(1, 3),
(1, 4),
(1, 5),
(3, 1),
(3, 2),
(3, 3),
(3, 4),
(3, 5),
(11, 4),
(11, 5),
(11, 6);

-- --------------------------------------------------------

--
-- Table structure for table `ratings`
--

CREATE TABLE `ratings` (
  `user_id` int(11) NOT NULL,
  `article_id` int(11) NOT NULL,
  `rating` int(1) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tags`
--

CREATE TABLE `tags` (
  `id` int(11) NOT NULL,
  `tag` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tags`
--

INSERT INTO `tags` (`id`, `tag`) VALUES
(1, 'php'),
(2, 'oop'),
(3, 'docker'),
(4, 'devops'),
(5, 'containers'),
(6, 'python');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `email` varchar(60) NOT NULL,
  `password` varchar(40) NOT NULL,
  `name` varchar(60) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `email`, `password`, `name`) VALUES
(1, 'rens@mail.com', 'password123', 'Rens van Eck'),
(2, 'claude@mail.com', 'clanker123', 'Claude Shannon'),
(4, 'iwrotec', 'ritchie@mail.com', 'Dennis Ritchie');


CREATE TABLE `_form_fields` (
  `page_id` int(11) NOT NULL,
  `label` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `placeholder` varchar(255) NOT NULL,
  `sort_order` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `_form_fields`
--

INSERT INTO `_form_fields` (`page_id`, `label`, `type`, `name`, `placeholder`, `sort_order`) VALUES
(3, 'name', 'text', 'Name:', 'Enter your name', 1),
(3, 'email', 'email', 'Email address:', 'Enter your email address', 2),
(3, 'message', 'textarea', 'Message:', 'Enter your message', 3),
(4, 'email', 'email', 'Email address:', 'Enter your email address', 1),
(4, 'password', 'password', 'Password:', 'Enter your password', 2),
(5, 'name', 'text', 'Name:', 'Enter your name', 1),
(5, 'email', 'email', 'Email address:', 'Enter your email address', 2),
(5, 'password', 'password', 'Password:', 'Enter your password', 3),
(5, 'password_repeat', 'password', 'Repeat password:', 'Repeat your password', 4),
(7, 'text_edit', 'textarea', 'Article text:', 'Write your article text here', 1),
(7, 'code_edit', 'textarea', 'Code:', 'Write your code example here', 2),
(7, 'tag', 'text', 'Add tag:', 'Select tag or add a new one', 3);

-- --------------------------------------------------------

--
-- Table structure for table `_hamburger_items`
--

CREATE TABLE `_hamburger_items` (
  `page` varchar(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `li_class` varchar(100) NOT NULL,
  `a_class` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `_hamburger_items`
--

INSERT INTO `_hamburger_items` (`page`, `name`, `li_class`, `a_class`) VALUES
('home', 'Home', 'nav-item', 'nav-link'),
('about', 'About', 'nav-item', 'nav-link'),
('contact', 'Contact', 'nav-item', 'nav-link');

-- --------------------------------------------------------

--
-- Table structure for table `_nav_items`
--

CREATE TABLE `_nav_items` (
  `page` varchar(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `li_class` varchar(100) NOT NULL,
  `a_class` varchar(100) NOT NULL,
  `logged_in` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `_nav_items`
--

INSERT INTO `_nav_items` (`page`, `name`, `li_class`, `a_class`, `logged_in`) VALUES
('my_articles', 'My Articles', 'nav-item', 'nav-link', 1),
('logout', 'Logout', 'nav-item', 'nav-link', 1),
('login', 'Login', 'nav-item', 'nav-link', 0),
('register', 'Register', 'nav-item', 'nav-link', 0);

-- --------------------------------------------------------

--
-- Table structure for table `_pages`
--

CREATE TABLE `_pages` (
  `id` int(10) NOT NULL,
  `page` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `_pages`
--

INSERT INTO `_pages` (`id`, `page`) VALUES
(1, 'home'),
(2, 'about'),
(3, 'contact'),
(4, 'login'),
(5, 'register'),
(6, 'article'),
(7, 'edit'),
(8, 'search'),
(9, 'search_results'),
(10, 'author_page');

-- --------------------------------------------------------

--
-- Table structure for table `_page_footer_logo_path`
--

CREATE TABLE `_page_footer_logo_path` (
  `logo_path` varchar(255) NOT NULL,
  `footer` int(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `_page_footer_logo_path`
--

INSERT INTO `_page_footer_logo_path` (`logo_path`, `footer`) VALUES
('Images/mdj_logo.png', 0);

-- --------------------------------------------------------

--
-- Table structure for table `_page_title_description`
--

CREATE TABLE `_page_title_description` (
  `page` varchar(100) NOT NULL,
  `page_title` varchar(100) NOT NULL,
  `page_description` varchar(1000) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `_page_title_description`
--

INSERT INTO `_page_title_description` (`page`, `page_title`, `page_description`) VALUES
('home', 'Home', 'Home description'),
('about', 'About', 'About description'),
('contact', 'Contact', 'Contact description'),
('login', 'Login', ''),
('register', 'Register', ''),
('edit', 'Edit Article', ''),
('search', 'Search', 'Search articles by author and tag.'),
('my_articles', 'My Articles', 'Manage the articles you have written.');
--
-- Indexes for dumped tables
--

--
-- Indexes for table `articles`
--
ALTER TABLE `articles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `images`
--
ALTER TABLE `images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `article_id` (`article_id`),
  ADD KEY `article_id_2` (`article_id`);

--
-- Indexes for table `join_articles_tags`
--
ALTER TABLE `join_articles_tags`
  ADD UNIQUE KEY `article_id` (`article_id`,`tag_id`),
  ADD KEY `join_id` (`article_id`,`tag_id`),
  ADD KEY `tag_id` (`tag_id`);

--
-- Indexes for table `ratings`
--
ALTER TABLE `ratings`
  ADD UNIQUE KEY `rating_id` (`user_id`,`article_id`),
  ADD KEY `article_id` (`article_id`);

--
-- Indexes for table `tags`
--
ALTER TABLE `tags`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `articles`
--
ALTER TABLE `articles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `images`
--
ALTER TABLE `images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `tags`
--
ALTER TABLE `tags`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `articles`
--
ALTER TABLE `articles`
  ADD CONSTRAINT `articles_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `images`
--
ALTER TABLE `images`
  ADD CONSTRAINT `fk_images_article` FOREIGN KEY (`article_id`) REFERENCES `articles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `join_articles_tags`
--
ALTER TABLE `join_articles_tags`
  ADD CONSTRAINT `join_articles_tags_ibfk_1` FOREIGN KEY (`article_id`) REFERENCES `articles` (`id`),
  ADD CONSTRAINT `join_articles_tags_ibfk_2` FOREIGN KEY (`tag_id`) REFERENCES `tags` (`id`);

--
-- Constraints for table `ratings`
--
ALTER TABLE `ratings`
  ADD CONSTRAINT `ratings_ibfk_1` FOREIGN KEY (`article_id`) REFERENCES `articles` (`id`),
  ADD CONSTRAINT `ratings_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
