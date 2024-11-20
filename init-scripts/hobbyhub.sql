-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: mysql
-- Generation Time: Nov 20, 2024 at 11:39 AM
-- Server version: 5.7.44
-- PHP Version: 8.2.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

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
(10, 1, 9, 'AI technology is growing so fast. Itâ€™s exciting to learn more.', '2024-11-19 09:45:23'),
(11, 2, 7, 'Italy is such a beautiful destination. I love your travel blog!', '2024-11-19 09:50:23'),
(12, 3, 8, 'This is a great fitness routine! Will try it this week!', '2024-11-19 09:55:23'),
(13, 1, 8, 'AI is transforming everything! Exciting times ahead.', '2024-11-19 10:00:23');

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `event_id` int(10) UNSIGNED NOT NULL,
  `event_name` varchar(255) NOT NULL,
  `event_description` text NOT NULL,
  `event_date` datetime NOT NULL,
  `location` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`event_id`, `event_name`, `event_description`, `event_date`, `location`, `created_at`) VALUES
(1, 'Tech Conference 2025', 'An annual event showcasing the latest in technology and innovation. Experts from around the world will gather to share insights.', '2025-06-15 09:00:00', 'New York, USA', '2024-11-20 08:56:41'),
(2, 'Travel Expo', 'A global travel expo featuring top destinations, vacation tips, and travel gear.', '2025-07-01 10:00:00', 'Los Angeles, USA', '2024-11-20 08:56:41'),
(3, 'Fitness Bootcamp', 'A weekend of intense fitness training with top trainers and wellness experts. Perfect for those looking to push their limits.', '2025-05-10 08:00:00', 'Miami, USA', '2024-11-20 08:56:41'),
(4, 'Business Summit', 'A summit for entrepreneurs and business leaders to network and share strategies for growth in 2025.', '2025-08-20 09:00:00', 'London, UK', '2024-11-20 08:56:41'),
(5, 'Fashion Week 2025', 'The latest fashion trends from top designers. A week-long event featuring runway shows, exhibitions, and industry networking.', '2025-09-15 18:00:00', 'Paris, France', '2024-11-20 08:56:41');

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
  `is_question` tinyint(1) DEFAULT '0',
  `image` blob NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`post_id`, `user_id`, `category_id`, `content`, `created_at`, `title`, `is_question`, `image`) VALUES
(1, 1, 1, 'Artificial intelligence is evolving at a rapid pace. Every year, we see new advancements in machine learning algorithms. AI is transforming industries across the globe, from healthcare to finance. In the future, AI will likely become more integrated into our daily lives. With self-driving cars and smart home devices, we are already seeing the benefits of AI. However, the ethical implications of AI remain a critical issue. As we develop more powerful AI, we must ensure it is used responsibly. This includes preventing biases in algorithms and ensuring transparency. The future of AI is exciting, but we must tread carefully to ensure it benefits all of humanity.', '2024-11-20 11:03:20', 'Exploring the Future of AI', 0, ''),
(2, 2, 4, 'Italian food is loved by people all around the world. The rich flavors of pasta, pizza, and wine are just the beginning. Each region of Italy has its own unique take on traditional dishes. From the creamy risottos of Milan to the seafood in Sicily, the diversity of Italian cuisine is astounding. Italian food is often made with fresh, local ingredients. The simplicity of dishes like pasta with olive oil and garlic can be extraordinary when made with the best ingredients. No meal is complete without a good bottle of wine. Italian cuisine is not just about food; it\'s about a way of life. Sharing a meal with friends and family is an important part of Italian culture. Whether you\'re in Rome or a small village, the love for food is universal.', '2024-11-20 11:03:20', 'The Wonders of Italian Cuisine', 0, ''),
(3, 3, 3, 'Exercise is one of the most important aspects of maintaining a healthy lifestyle. Regular physical activity has numerous benefits for both the body and the mind. It helps to reduce the risk of chronic diseases like heart disease, diabetes, and obesity. Exercise also improves mental health by reducing stress and anxiety. Whether it\'s running, swimming, or practicing yoga, all forms of exercise contribute to overall well-being. Additionally, exercise can improve sleep quality and boost energy levels. Strength training is especially beneficial for building muscle mass and improving bone density. For those who are just starting, even light exercise like walking can make a significant difference. The key is consistency, and making exercise a part of your daily routine. Remember, a healthy body leads to a healthy mind.', '2024-11-20 11:03:20', 'The Benefits of Regular Exercise', 0, ''),
(4, 4, 2, 'Japan is one of the most fascinating countries to visit. From the bustling streets of Tokyo to the peaceful temples in Kyoto, Japan offers a perfect blend of modernity and tradition. The cherry blossoms in spring are a sight to behold, and the temples offer a peaceful retreat from the city life. Japan is also known for its exceptional cuisine, including sushi, ramen, and tempura. The transportation system is incredibly efficient, making it easy to travel between cities. Japanese culture is rich in history, from the samurai warriors to the ancient tea ceremonies. The people are incredibly polite and welcoming, making visitors feel at home. Traveling through Japan is a truly unique experience, and there is always something new to discover. Whether you\'re a nature lover or a history buff, Japan has something for everyone.', '2024-11-20 11:03:20', 'Traveling Through Japan: A Dream Come True', 0, ''),
(5, 5, 7, 'Remote work has become increasingly popular in recent years, especially after the global pandemic. Many companies have realized the benefits of allowing employees to work from home. Remote work offers flexibility and saves time by eliminating the need for commuting. It also allows workers to create a more balanced lifestyle. With the right technology, remote work can be just as effective as working in an office. Virtual meetings, cloud-based file sharing, and project management tools have made remote work more efficient than ever. However, remote work comes with its own set of challenges. Staying productive without the structure of an office environment can be difficult for some people. It\'s important to set up a designated workspace and maintain a routine. Despite the challenges, remote work is likely here to stay and will continue to grow in the coming years.', '2024-11-20 11:03:20', 'The Rise of Remote Work', 0, '');

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
  `bio` text,
  `image` blob
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `full_name`, `email`, `password_hash`, `created_at`, `bio`, `image`) VALUES
(1, 'john_doe123', 'John Doe', 'john.doe123@example.com', 'hashedpassword123', '2024-11-19 08:35:23', 'Tech enthusiast and gadget lover.', NULL),
(2, 'jane_smith456', 'Jane Smith', 'jane.smith456@example.com', 'hashedpassword456', '2024-11-19 08:35:23', 'Travel blogger and health advocate.', NULL),
(3, 'mark_taylor789', 'Mark Taylor', 'mark.taylor789@example.com', 'hashedpassword789', '2024-11-19 08:35:23', 'Fitness coach and wellness expert.', NULL),
(4, 'admin', 'admin', 'admin@example.com', 'admin', '2024-11-19 08:35:23', 'Administrator of the platform.', NULL),
(5, 'alice_jones321', 'Alice Jones', 'alice.jones321@example.com', 'hashedpassword321', '2024-11-19 08:40:23', 'Passionate about tech and wellness.', NULL),
(6, 'bob_brown654', 'Bob Brown', 'bob.brown654@example.com', 'hashedpassword654', '2024-11-19 08:40:23', 'Travel enthusiast and foodie.', NULL),
(7, 'carol_white987', 'Carol White', 'carol.white987@example.com', 'hashedpassword987', '2024-11-19 08:40:23', 'Love to read and write about fitness and health.', NULL),
(8, 'david_green543', 'David Green', 'david.green543@example.com', 'hashedpassword543', '2024-11-19 08:40:23', 'Entrepreneur and tech innovator.', NULL),
(9, 'eva_black234', 'Eva Black', 'eva.black234@example.com', 'hashedpassword234', '2024-11-19 08:40:23', 'Avid traveler and food critic.', NULL),
(10, 'frank_young876', 'Frank Young', 'frank.young876@example.com', 'hashedpassword876', '2024-11-19 08:40:23', 'Sports fan and fitness coach.', NULL),
(11, 'grace_smith321', 'Grace Smith', 'grace.smith321@example.com', 'hashedpassword321', '2024-11-19 08:40:23', 'Fashion and beauty expert.', NULL),
(12, 'henry_white678', 'Henry White', 'henry.white678@example.com', 'hashedpassword678', '2024-11-19 08:40:23', 'Tech and business blogger.', NULL),
(13, 'irene_pink432', 'Irene Pink', 'irene.pink432@example.com', 'hashedpassword432', '2024-11-19 08:40:23', 'Lover of fashion and fitness trends.', NULL),
(14, 'jack_purple876', 'Jack Purple', 'jack.purple876@example.com', 'hashedpassword876', '2024-11-19 08:40:23', 'Food blogger and traveler.', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`post_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `post_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;
