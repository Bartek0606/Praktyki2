-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: mysql
-- Generation Time: Nov 19, 2024 at 08:52 AM
-- Server version: 5.7.44
-- PHP Version: 8.2.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";
SET NAMES utf8mb4;

-- Database: `hobbyhub`

-- Table structure for table `categories`
CREATE TABLE `categories` (
  `category_id` int(10) UNSIGNED NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` text
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table `categories`
INSERT INTO `categories` (`category_id`, `name`, `description`) VALUES
(1, 'Technology', 'Posts related to new technology and gadgets'),
(2, 'Lifestyle', 'Posts about daily life, habits, and activities'),
(3, 'Health', 'Posts related to fitness, health tips, and wellness'),
(4, 'Travel', 'Posts about travel experiences and destinations');

-- Table structure for table `comments`
CREATE TABLE `comments` (
  `comment_id` int(10) UNSIGNED NOT NULL,
  `post_id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `content` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Table structure for table `posts`
CREATE TABLE `posts` (
  `post_id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `category_id` int(10) UNSIGNED DEFAULT NULL,
  `content` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `title` varchar(255) NOT NULL,
  `is_question` tinyint(1) DEFAULT '0',
  `image_url` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Table structure for table `users`
CREATE TABLE `users` (
  `user_id` int(10) UNSIGNED NOT NULL,
  `username` varchar(50) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `profile_picture_url` varchar(255) DEFAULT NULL,
  `bio` text
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table `users`
INSERT INTO `users` (`user_id`, `username`, `full_name`, `email`, `password_hash`, `created_at`, `profile_picture_url`, `bio`) VALUES
(1, 'john_doe123', 'John Doe', 'john.doe123@example.com', 'hashedpassword123', '2024-11-19 08:35:23', 'https://example.com/profiles/john.jpg', 'Tech enthusiast and gadget lover.'),
(2, 'jane_smith456', 'Jane Smith', 'jane.smith456@example.com', 'hashedpassword456', '2024-11-19 08:35:23', 'https://example.com/profiles/jane.jpg', 'Travel blogger and health advocate.'),
(3, 'mark_taylor789', 'Mark Taylor', 'mark.taylor789@example.com', 'hashedpassword789', '2024-11-19 08:35:23', 'https://example.com/profiles/mark.jpg', 'Fitness coach and wellness expert.'),
(4, 'admin', 'admin', 'admin@example.com', 'admin', '2024-11-19 08:35:23', 'https://example.com/profiles/admin.jpg', 'Administrator of the platform.');

-- Dumping data for table `posts`
INSERT INTO `posts` (`post_id`, `user_id`, `category_id`, `content`, `created_at`, `title`, `is_question`, `image_url`) VALUES
(1, 7, 1, 'Latest advancements in AI technology...', '2024-11-19 08:40:23', 'AI in Technology', 0, 'https://example.com/posts/ai.jpg'),
(2, 8, 4, 'Exploring the beauty of Italy...', '2024-11-19 08:45:23', 'Travel in Italy', 0, 'https://example.com/posts/italy.jpg'),
(3, 9, 3, 'Fitness routine for a healthy life...', '2024-11-19 08:50:23', 'Healthy Fitness Habits', 0, 'https://example.com/posts/fitness.jpg');

-- Dumping data for table `comments`
INSERT INTO `comments` (`comment_id`, `post_id`, `user_id`, `content`, `created_at`) VALUES
(1, 1, 8, 'This article is great! I love AI discussions.', '2024-11-19 09:00:23'),
(2, 2, 9, 'Italy is on my bucket list! Thanks for sharing your experience.', '2024-11-19 09:05:23'),
(3, 3, 7, 'I need to try this workout routine. Looks amazing!', '2024-11-19 09:10:23');

-- Constraints for dumped tables
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`post_id`) REFERENCES `posts` (`post_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `comments_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

ALTER TABLE `posts`
  ADD CONSTRAINT `posts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `posts_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `categories` (`category_id`) ON DELETE SET NULL;

COMMIT;
