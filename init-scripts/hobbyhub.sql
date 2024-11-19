-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: mysql
-- Generation Time: Nov 19, 2024 at 12:26 PM
-- Server version: 5.7.44
-- PHP Version: 8.2.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `hobbyhub`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `category_id` int(10) UNSIGNED NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` text
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`category_id`, `name`, `description`) VALUES
(1, 'Technology', 'Posts related to new technology and gadgets'),
(2, 'Lifestyle', 'Posts about daily life, habits, and activities'),
(3, 'Health', 'Posts related to fitness, health tips, and wellness'),
(4, 'Travel', 'Posts about travel experiences and destinations'),
(5, 'Education', 'Posts about learning, courses, and educational experiences'),
(6, 'Entertainment', 'Posts about movies, music, and pop culture'),
(7, 'Business', 'Posts about entrepreneurship, startups, and business strategies'),
(8, 'Food', 'Posts about cooking, recipes, and food experiences'),
(9, 'Sports', 'Posts about sports events, news, and fitness'),
(10, 'Fashion', 'Posts about the latest fashion trends and style tips');

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `comment_id` int(10) UNSIGNED NOT NULL,
  `post_id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `content` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`comment_id`, `post_id`, `user_id`, `content`, `created_at`) VALUES
(1, 1, 8, 'This article is great! I love AI discussions.', '2024-11-19 09:00:23'),
(2, 2, 9, 'Italy is on my bucket list! Thanks for sharing your experience.', '2024-11-19 09:05:23'),
(3, 3, 7, 'I need to try this workout routine. Looks amazing!', '2024-11-19 09:10:23'),
(4, 1, 9, 'AI is definitely the future! Exciting times ahead.', '2024-11-19 09:15:23'),
(5, 2, 7, 'Love reading about new travel experiences! Italy sounds amazing.', '2024-11-19 09:20:23'),
(6, 3, 8, 'This fitness routine is just what I need. Thanks for sharing!', '2024-11-19 09:25:23'),
(7, 1, 7, 'Great post! Looking forward to more tech articles.', '2024-11-19 09:30:23'),
(8, 2, 8, 'Italy is stunning, hope to travel there soon!', '2024-11-19 09:35:23'),
(9, 3, 9, 'I need to start focusing on my fitness. Thanks for the inspiration!', '2024-11-19 09:40:23'),
(10, 1, 9, 'AI technology is growing so fast. It’s exciting to learn more.', '2024-11-19 09:45:23'),
(11, 2, 7, 'Italy is such a beautiful destination. I love your travel blog!', '2024-11-19 09:50:23'),
(12, 3, 8, 'This is a great fitness routine! Will try it this week!', '2024-11-19 09:55:23'),
(13, 1, 8, 'AI is transforming everything! Exciting times ahead.', '2024-11-19 10:00:23');

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `post_id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `category_id` int(10) UNSIGNED DEFAULT NULL,
  `content` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `title` varchar(255) NOT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `is_question` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`post_id`, `user_id`, `category_id`, `content`, `created_at`, `title`, `image_url`, `is_question`) VALUES
(1, 7, 1, 'Latest advancements in AI technology...', '2024-11-19 08:40:23', 'AI in Technology', 'https://example.com/posts/ai.jpg', 0),
(2, 8, 4, 'Exploring the beauty of Italy...', '2024-11-19 08:45:23', 'Travel in Italy', 'https://example.com/posts/italy.jpg', 0),
(3, 9, 3, 'Fitness routine for a healthy life...', '2024-11-19 08:50:23', 'Healthy Fitness Habits', 'https://example.com/posts/fitness.jpg', 0),
(4, 7, 2, 'Exploring the minimalist lifestyle and its benefits...', '2024-11-19 10:05:23', 'Minimalism in Lifestyle', 'https://example.com/posts/minimalism.jpg', 0),
(5, 8, 5, 'Why health and fitness are essential in daily life...', '2024-11-19 10:10:23', 'Health for Everyone', 'https://example.com/posts/health.jpg', 0),
(6, 9, 6, 'The impact of entertainment on our daily lives...', '2024-11-19 10:15:23', 'The World of Entertainment', 'https://example.com/posts/entertainment.jpg', 0),
(7, 7, 7, 'Business strategies for startups...', '2024-11-19 10:20:23', 'Business Tips for Entrepreneurs', 'https://example.com/posts/business.jpg', 0),
(8, 8, 8, 'Top 10 recipes for a healthy lifestyle...', '2024-11-19 10:25:23', 'Healthy Eating', 'https://example.com/posts/food.jpg', 0),
(9, 9, 9, 'The importance of sports in maintaining a healthy body...', '2024-11-19 10:30:23', 'Sports and Fitness', 'https://example.com/posts/sports.jpg', 0),
(10, 7, 10, 'The latest fashion trends for the fall season...', '2024-11-19 10:35:23', 'Fashion Trends', 'https://example.com/posts/fashion.jpg', 0),
(11, 8, 2, 'How to balance work and life effectively...', '2024-11-19 10:40:23', 'Work-Life Balance', 'https://example.com/posts/worklife.jpg', 0),
(12, 9, 5, 'Tips for creating a workout routine that works for you...', '2024-11-19 10:45:23', 'Personalized Fitness Plans', 'https://example.com/posts/workout.jpg', 0),
(13, 7, 6, 'The evolution of music and its influence on culture...', '2024-11-19 10:50:23', 'Music and Culture', 'https://example.com/posts/music.jpg', 0);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

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

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `full_name`, `email`, `password_hash`, `created_at`, `profile_picture_url`, `bio`) VALUES
(1, 'john_doe123', 'John Doe', 'john.doe123@example.com', 'hashedpassword123', '2024-11-19 08:35:23', 'https://example.com/profiles/john.jpg', 'Tech enthusiast and gadget lover.'),
(2, 'jane_smith456', 'Jane Smith', 'jane.smith456@example.com', 'hashedpassword456', '2024-11-19 08:35:23', 'https://example.com/profiles/jane.jpg', 'Travel blogger and health advocate.'),
(3, 'mark_taylor789', 'Mark Taylor', 'mark.taylor789@example.com', 'hashedpassword789', '2024-11-19 08:35:23', 'https://example.com/profiles/mark.jpg', 'Fitness coach and wellness expert.'),
(4, 'admin', 'admin', 'admin@example.com', 'admin', '2024-11-19 08:35:23', 'https://example.com/profiles/admin.jpg', 'Administrator of the platform.'),
(5, 'alice_jones321', 'Alice Jones', 'alice.jones321@example.com', 'hashedpassword321', '2024-11-19 08:40:23', 'https://example.com/profiles/alice.jpg', 'Passionate about tech and wellness.'),
(6, 'bob_brown654', 'Bob Brown', 'bob.brown654@example.com', 'hashedpassword654', '2024-11-19 08:40:23', 'https://example.com/profiles/bob.jpg', 'Travel enthusiast and foodie.'),
(7, 'carol_white987', 'Carol White', 'carol.white987@example.com', 'hashedpassword987', '2024-11-19 08:40:23', 'https://example.com/profiles/carol.jpg', 'Love to read and write about fitness and health.'),
(8, 'david_green543', 'David Green', 'david.green543@example.com', 'hashedpassword543', '2024-11-19 08:40:23', 'https://example.com/profiles/david.jpg', 'Entrepreneur and tech innovator.'),
(9, 'eva_black234', 'Eva Black', 'eva.black234@example.com', 'hashedpassword234', '2024-11-19 08:40:23', 'https://example.com/profiles/eva.jpg', 'Avid traveler and food critic.'),
(10, 'frank_young876', 'Frank Young', 'frank.young876@example.com', 'hashedpassword876', '2024-11-19 08:40:23', 'https://example.com/profiles/frank.jpg', 'Sports fan and fitness coach.'),
(11, 'grace_smith321', 'Grace Smith', 'grace.smith321@example.com', 'hashedpassword321', '2024-11-19 08:40:23', 'https://example.com/profiles/grace.jpg', 'Fashion and beauty expert.'),
(12, 'henry_white678', 'Henry White', 'henry.white678@example.com', 'hashedpassword678', '2024-11-19 08:40:23', 'https://example.com/profiles/henry.jpg', 'Tech and business blogger.'),
(13, 'irene_pink432', 'Irene Pink', 'irene.pink432@example.com', 'hashedpassword432', '2024-11-19 08:40:23', 'https://example.com/profiles/irene.jpg', 'Lover of fashion and fitness trends.'),
(14, 'jack_purple876', 'Jack Purple', 'jack.purple876@example.com', 'hashedpassword876', '2024-11-19 08:40:23', 'https://example.com/profiles/jack.jpg', 'Food blogger and traveler.');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;