-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: mysql
-- Generation Time: Nov 21, 2024 at 09:56 AM
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
(10, 'Fashion', 'Posts about the latest fashion trends and style tips'),
(11, 'Music', 'Posts about music, albums, and artists'),
(12, 'Books', 'Posts about literature, reading, and book recommendations'),
(13, 'Technology Trends', 'Posts about the latest trends in the tech industry'),
(14, 'Fitness Challenges', 'Posts about fitness challenges and workout goals'),
(15, 'Mental Health', 'Posts related to mental health, therapy, and well-being'),
(16, 'Automotive', 'Posts about cars, motorcycles, and driving experiences'),
(17, 'Science', 'Posts about scientific discoveries, experiments, and research'),
(18, 'Gaming', 'Posts about video games, gaming consoles, and e-sports'),
(19, 'Photography', 'Posts about photography techniques, cameras, and photo editing'),
(20, 'Gardening', 'Posts about gardening tips, plants, and home gardening');

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
(10, 1, 9, 'AI technology is growing so fast. ItÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â€šÂ¬Ã…Â¡Ãƒâ€šÃ‚Â¬ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â€šÂ¬Ã…Â¾Ãƒâ€šÃ‚Â¢s exciting to learn more.', '2024-11-19 09:45:23'),
(11, 2, 7, 'Italy is such a beautiful destination. I love your travel blog!', '2024-11-19 09:50:23'),
(12, 3, 8, 'This is a great fitness routine! Will try it this week!', '2024-11-19 09:55:23'),
(13, 1, 8, 'AI is transforming everything! Exciting times ahead.', '2024-11-19 10:00:23'),
(14, 1, 2, 'Amazing insights on AI. Can\'t wait to see what\'s next!', '2024-11-20 11:10:23'),
(15, 2, 3, 'I love Italian food! The pasta is my favorite.', '2024-11-20 11:15:23'),
(16, 3, 1, 'I try to exercise every day, and it really helps my mood.', '2024-11-20 11:20:23'),
(17, 4, 2, 'I had a blast at the Tech Conference last year. Highly recommend it!', '2024-11-20 11:25:23'),
(18, 5, 3, 'Fashion Week in Paris is on my bucket list!', '2024-11-20 11:30:23'),
(19, 6, 1, 'Just booked my flight to the Travel Expo. Can\'t wait!', '2024-11-20 11:35:23'),
(20, 7, 2, 'I started a business last year. This summit will be helpful!', '2024-11-20 11:40:23'),
(21, 8, 3, 'Can\'t get enough of gaming. This is my favorite topic.', '2024-11-20 11:45:23'),
(22, 9, 1, 'Mental health is so important, especially in today\'s world.', '2024-11-20 11:50:23'),
(23, 10, 2, 'Love gardening! I\'ve been planting roses in my backyard.', '2024-11-20 11:55:23');

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
(5, 'Fashion Week 2025', 'The latest fashion trends from top designers. A week-long event featuring runway shows, exhibitions, and industry networking.', '2025-09-15 18:00:00', 'Paris, France', '2024-11-20 08:56:41'),
(6, 'Gaming Expo 2025', 'A global gaming expo where the best developers and gamers meet.', '2025-06-01 10:00:00', 'Los Angeles, USA', '2024-11-20 09:00:00'),
(7, 'Mental Health Summit', 'A summit to raise awareness about mental health issues and resources.', '2025-05-25 09:00:00', 'Chicago, USA', '2024-11-20 09:05:00'),
(8, 'Photography Convention 2025', 'A convention for photographers to showcase their work and learn from experts.', '2025-07-10 08:00:00', 'Berlin, Germany', '2024-11-20 09:10:00'),
(9, 'Book Fair 2025', 'A fair for book lovers with authors, publishers, and readers gathering together.', '2025-08-01 11:00:00', 'London, UK', '2024-11-20 09:15:00'),
(10, 'Art Exhibition 2025', 'An exhibition for emerging artists to display their works of art.', '2025-09-01 10:00:00', 'Paris, France', '2024-11-20 09:20:00'),
(11, 'Science Conference 2025', 'An event for scientists to discuss the latest discoveries and research.', '2025-10-05 08:00:00', 'Tokyo, Japan', '2024-11-20 09:25:00'),
(12, 'Tech Expo 2025', 'An expo to show the latest innovations in technology from startups to giants.', '2025-04-20 09:00:00', 'San Francisco, USA', '2024-11-20 09:30:00'),
(13, 'Startup Pitch Event', 'A pitch event for startups looking for funding and mentoring.', '2025-06-30 09:00:00', 'Austin, USA', '2024-11-20 09:35:00'),
(14, 'Fashion & Design Expo 2025', 'A comprehensive expo about the fashion industry and the latest designs.', '2025-09-05 10:00:00', 'Milan, Italy', '2024-11-20 09:40:00'),
(15, 'Food Festival 2025', 'A festival where food lovers come together to enjoy various cuisines from around the world.', '2025-10-10 11:00:00', 'New York, USA', '2024-11-20 09:45:00');

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
  `image` longblob
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`post_id`, `user_id`, `category_id`, `content`, `created_at`, `title`, `is_question`, `image`) VALUES
(1, 1, 1, 'Artificial intelligence is evolving at a rapid pace. Every year, we see new advancements in machine learning algorithms. AI is transforming industries across the globe, from healthcare to finance. In the future, AI will likely become more integrated into our daily lives. With self-driving cars and smart home devices, we are already seeing the benefits of AI. However, the ethical implications of AI remain a critical issue. As we develop more powerful AI, we must ensure it is used responsibly. This includes preventing biases in algorithms and ensuring transparency. The future of AI is exciting, but we must tread carefully to ensure it benefits all of humanity.', '2024-11-20 11:03:20', 'Exploring the Future of AI', 0, NULL),
(2, 2, 4, 'Italian food is loved by people all around the world. The rich flavors of pasta, pizza, and wine are just the beginning. Each region of Italy has its own unique take on traditional dishes. From the creamy risottos of Milan to the seafood in Sicily, the diversity of Italian cuisine is astounding. Italian food is often made with fresh, local ingredients. The simplicity of dishes like pasta with olive oil and garlic can be extraordinary when made with the best ingredients. No meal is complete without a good bottle of wine. Italian cuisine is not just about food; it\'s about a way of life. Sharing a meal with friends and family is an important part of Italian culture. Whether you\'re in Rome or a small village, the love for food is universal.', '2024-11-20 11:03:20', 'The Wonders of Italian Cuisine', 1, NULL),
(3, 3, 3, 'Exercise is one of the most important aspects of maintaining a healthy lifestyle. Regular physical activity has numerous benefits for both the body and the mind. It helps to reduce the risk of chronic diseases like heart disease, diabetes, and obesity. Exercise also improves mental health by reducing stress and anxiety. Whether it\'s running, swimming, or practicing yoga, all forms of exercise contribute to overall well-being. Additionally, exercise can improve sleep quality and boost energy levels. Strength training is especially beneficial for building muscle mass and improving bone density. For those who are just starting, even light exercise like walking can make a significant difference. The key is consistency, and making exercise a part of your daily routine. Remember, a healthy body leads to a healthy mind.', '2024-11-20 11:03:20', 'The Benefits of Regular Exercise', 0, NULL),
(4, 4, 5, 'Education has been evolving with the rise of online learning. Platforms like Coursera and Udemy are making education more accessible to people all around the world. While the convenience of online courses is undeniable, there are still debates about the quality of remote learning compared to traditional in-person classes. However, technology is helping to bridge the gap, making learning interactive and immersive.', '2024-11-21 10:00:00', 'The Future of Online Education', 0, NULL),
(5, 1, 6, 'Music is one of the most powerful forms of expression. It can evoke emotions, connect people, and even change the world. From classical to contemporary genres, music plays an important role in culture and society. In today\'s digital age, music streaming platforms have transformed the way we consume music, but vinyl records and live performances are still cherished by many music enthusiasts.', '2024-11-21 10:05:00', 'The Power of Music in Society', 0, NULL),
(6, 2, 8, 'The art of cooking has evolved over the centuries, but one thing remains the same: the love and creativity behind each dish. Whether you\'re cooking a family recipe passed down through generations or experimenting with new flavors, food has the ability to bring people together and create memories. In this post, I will share my favorite recipes and tips for creating delicious meals at home.', '2024-11-21 10:10:00', 'Cooking: More Than Just a Meal', 0, NULL),
(7, 3, 9, 'Sports have always been a great way to stay fit and active. Whether you enjoy team sports like football or individual activities like running, sports offer numerous benefits. They help to improve cardiovascular health, build strength, and boost mental focus. Many people also find sports to be an excellent stress reliever. Let\'s explore the top sports you should consider incorporating into your fitness routine.', '2024-11-21 10:15:00', 'Top Sports to Try for a Healthy Lifestyle', 0, NULL),
(8, 4, 7, 'Business strategies are always evolving as markets change and technology advances. In today\'s fast-paced world, companies must be agile and adaptable to stay ahead of the competition. This post explores some of the most effective business strategies for 2025, including digital transformation, customer-centric approaches, and sustainability.', '2024-11-21 10:20:00', 'Top Business Strategies for 2025', 0, NULL),
(9, 1, 10, 'Fashion is an ever-changing industry. Trends come and go, but personal style is timeless. In this post, I will share tips for building a wardrobe that reflects your unique style, including choosing the right colors, fabrics, and accessories. Additionally, I will discuss how to create a capsule wardrobe that allows you to mix and match pieces effortlessly.', '2024-11-21 10:25:00', 'Building a Timeless Wardrobe', 0, NULL),
(10, 2, 4, 'The world of travel has seen incredible changes over the years. From budget travel to luxury experiences, there is something for everyone. Whether you are backpacking through Europe or planning a luxury cruise, travel provides an opportunity for growth, learning, and adventure. Let\'s explore some of the top destinations to visit in 2025.', '2024-11-21 10:30:00', 'Top Travel Destinations for 2025', 0, NULL);

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
  `profile_picture` longblob
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `full_name`, `email`, `password_hash`, `created_at`, `bio`, `profile_picture`) VALUES
(1, 'user1', 'Jan Kowalski', 'jan.kowalski@example.com', 'hashed_password_1', '2024-11-20 11:56:58', 'Bio for Jan Kowalski', NULL),
(2, 'user2', 'Anna Nowak', 'anna.nowak@example.com', 'hashed_password_2', '2024-11-20 11:56:58', 'Bio for Anna Nowak', NULL),
(3, 'user3', 'Piotr Wisniewski', 'piotr.wisniewski@example.com', 'hashed_password_3', '2024-11-20 11:56:58', 'Bio for Piotr Wisniewski', NULL),
(4, 'user', 'user', 'user@example.com', '$2y$10$ASVPw2I4SegRLj.k2XGe5OBh5hsOKe1RNm3rTj7rU15tkhdWXebX.', '2024-11-20 12:03:29', 'a', NULL),
(5, 'user5', 'John Smith', 'john.smith@example.com', 'hashed_password_5', '2024-11-21 12:00:00', 'Bio for John Smith', NULL),
(6, 'user6', 'Katarzyna Kowalska', 'katarzyna.kowalska@example.com', 'hashed_password_6', '2024-11-21 12:05:00', 'Bio for Katarzyna Kowalska', NULL),
(7, 'user7', 'Marek Zielinski', 'marek.zielinski@example.com', 'hashed_password_7', '2024-11-21 12:10:00', 'Bio for Marek Zielinski', NULL),
(8, 'user8', 'Magdalena Jankowska', 'magdalena.jankowska@example.com', 'hashed_password_8', '2024-11-21 12:15:00', 'Bio for Magdalena Jankowska', NULL),
(9, 'user9', 'Pawel Adamczyk', 'pawel.adamczyk@example.com', 'hashed_password_9', '2024-11-21 12:20:00', 'Bio for Pawel Adamczyk', NULL),
(10, 'user10', 'Agnieszka Wojcik', 'agnieszka.wojcik@example.com', 'hashed_password_10', '2024-11-21 12:25:00', 'Bio for Agnieszka Wojcik', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`post_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `post_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
COMMIT;
