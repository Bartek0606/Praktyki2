-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: mysql
-- Generation Time: Nov 20, 2024 at 10:45 AM
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
(10, 1, 9, 'AI technology is growing so fast. It’s exciting to learn more.', '2024-11-19 09:45:23'),
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
  `image_url` varchar(255) DEFAULT NULL,
  `is_question` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`post_id`, `user_id`, `category_id`, `content`, `created_at`, `title`, `image_url`, `is_question`) VALUES
(1, 7, 1, 'Latest advancements in AI technology are truly remarkable. Artificial intelligence is changing the world in profound ways. From automation to machine learning, AI is impacting every sector. The technology has already improved healthcare, education, and business operations. As AI becomes more advanced, its capabilities continue to expand. Researchers are exploring ways to make AI systems smarter and more intuitive. The future of AI holds incredible promise, particularly in areas like robotics and data analysis. However, there are also concerns about the ethical implications of AI. We must ensure AI is used responsibly and for the greater good. The potential for innovation in AI is limitless, and we are just getting started.', '2024-11-19 08:40:23', 'AI in Technology', 'https://example.com/posts/ai.jpg', 0),
(2, 8, 4, 'Exploring the beauty of Italy is an unforgettable experience. The country is rich in history, art, and culture. Each city has its own unique charm and personality. In Rome, you can walk through ancient ruins like the Colosseum and Roman Forum. Venice is known for its canals, beautiful architecture, and romantic atmosphere. Florence is the birthplace of the Renaissance and home to iconic art museums. Italy’s food culture is unmatched, with pasta, pizza, and gelato being favorites. The vineyards of Tuscany offer some of the best wines in the world. Italy is also famous for its fashion, especially in Milan. Traveling through Italy is a journey through time, offering something for everyone.', '2024-11-19 08:45:23', 'Travel in Italy', 'https://example.com/posts/italy.jpg', 0),
(3, 9, 3, 'Fitness routines are crucial for maintaining a healthy body and mind. Regular exercise boosts your cardiovascular health and strengthens your muscles. It helps to manage weight and increase energy levels. Incorporating strength training into your routine is vital for bone health. Cardio exercises, such as running or cycling, improve endurance and burn calories. Stretching enhances flexibility and reduces the risk of injury. A well-balanced diet works in harmony with exercise for optimal results. Hydration is key before, during, and after workouts. Consistency is essential to seeing progress over time. Remember, fitness is a journey, not a destination, and it’s important to enjoy the process.', '2024-11-19 08:50:23', 'Healthy Fitness Habits', 'https://example.com/posts/fitness.jpg', 0),
(4, 7, 2, 'Living a minimalist lifestyle can bring many benefits. It encourages you to focus on what truly matters. By removing excess clutter, you create more space for things that add value to your life. Minimalism allows you to be more mindful about your possessions and your choices. It can reduce stress and make life feel less overwhelming. Living simply often leads to increased financial freedom as you stop buying unnecessary items. It also helps you prioritize experiences over material things. Minimalism is about being intentional with your time and energy. By embracing minimalism, you can live a more fulfilling and purposeful life. It’s about making room for the things that truly align with your values and goals.', '2024-11-19 10:05:23', 'Minimalism in Lifestyle', 'https://example.com/posts/minimalism.jpg', 0),
(5, 8, 5, 'Health and fitness are crucial for a long, happy life. Regular exercise boosts both physical and mental health. It strengthens your heart, improves circulation, and supports a healthy weight. Fitness routines should include strength training, cardio, and flexibility exercises. Eating a balanced diet with plenty of fruits and vegetables is essential for overall health. Getting enough sleep each night allows your body to recover and function properly. Managing stress is equally important, as it can affect your physical well-being. Mental health plays a huge role in overall fitness, and practicing mindfulness can help. Fitness is not just about aesthetics but about feeling strong and confident in your body. When you prioritize health, you are investing in a better quality of life.', '2024-11-19 10:10:23', 'Health for Everyone', 'https://example.com/posts/health.jpg', 0),
(6, 9, 6, 'The world of entertainment is ever-changing and exciting. Movies, music, and television shows have a significant impact on society. The entertainment industry has evolved with advancements in technology. Streaming services have changed the way we consume media, offering greater convenience and variety. Music is a universal language that brings people together, no matter where they are from. From pop to classical, there is a genre for everyone. Television has become an outlet for diverse stories and perspectives. Many films and series now reflect global issues, sparking important conversations. The rise of social media influencers has also created a new form of entertainment. The future of entertainment looks bright with the possibilities of virtual reality and immersive experiences.', '2024-11-19 10:15:23', 'The World of Entertainment', 'https://example.com/posts/entertainment.jpg', 0),
(7, 7, 7, 'Business strategies are essential for entrepreneurs looking to succeed. Starting a business requires more than just a good idea—it takes planning and strategy. A clear vision, combined with a solid business model, sets the foundation for success. Networking is crucial, as building relationships with other professionals can open doors. Market research helps to understand your target audience and competition. Marketing and branding are key to standing out in a crowded market. Financial management is essential to ensure the longevity of a business. Entrepreneurs must also be adaptable and ready to pivot when needed. Learning from failure is a crucial part of the entrepreneurial journey. Success often comes down to persistence, resilience, and a passion for what you do.', '2024-11-19 10:20:23', 'Business Tips for Entrepreneurs', 'https://example.com/posts/business.jpg', 1),
(8, 8, 8, 'Eating healthy is essential for living a long, fulfilling life. A balanced diet supports overall health and helps prevent chronic diseases. Fruits and vegetables provide essential vitamins and minerals that the body needs. Lean proteins and whole grains are important for building and repairing tissues. Healthy fats, like those found in avocados, support brain function and cell growth. Drinking plenty of water is crucial for hydration and maintaining healthy skin. Avoiding processed foods can reduce the risk of health problems like obesity and diabetes. Meal prepping can help you stick to a healthy eating plan. It’s important to listen to your body’s hunger cues and eat in moderation. A healthy lifestyle is not about restriction but about making nourishing choices every day.', '2024-11-19 10:25:23', 'Healthy Eating', 'https://example.com/posts/food.jpg', 1),
(9, 9, 9, 'Sports play a vital role in maintaining physical fitness. Regular participation in sports improves cardiovascular health and builds strength. It also promotes teamwork and discipline. Sports can be a great way to stay active while having fun. Whether it’s running, swimming, or playing basketball, any form of physical activity is beneficial. Competitive sports encourage goal setting and perseverance. Watching sports can be a source of entertainment and motivation for many people. The importance of sports in schools cannot be understated, as they teach important life skills. Sports are also a great way to meet new people and form lasting friendships. Whether professional or recreational, sports bring people together in unique ways.', '2024-11-19 10:30:23', 'Sports and Fitness', 'https://example.com/posts/sports.jpg', 0),
(10, 7, 10, 'Fashion trends change every season, but style is timeless. The fall season brings rich colors and cozy fabrics, perfect for layering. Popular trends for this season include oversized coats, leather jackets, and chunky boots. Plaid patterns are also making a comeback, offering a classic yet edgy look. Accessories like scarves, hats, and gloves add warmth and style. Sustainable fashion is becoming more important as people look for eco-friendly alternatives. Vintage clothing is gaining popularity as a way to embrace unique pieces from past decades. Fashion is also about self-expression, and everyone has their own personal style. Whether it’s bold prints or minimalist looks, fashion is a way to showcase who you are. Fall fashion allows for versatility and creativity in outfit choices.', '2024-11-19 10:35:23', 'Fashion Trends', 'https://example.com/posts/fashion.jpg', 0),
(11, 8, 2, 'Finding the right work-life balance can be challenging in today’s fast-paced world. It’s easy to get caught up in work and neglect personal time. However, balancing your career and personal life is crucial for long-term happiness. Time management is a key factor in maintaining this balance. Setting boundaries between work and home life allows for more relaxation and time with family. Learning to delegate tasks at work can reduce stress and create more free time. Prioritizing self-care is equally important, as it allows you to recharge and avoid burnout. Taking breaks throughout the day can improve focus and productivity. Embracing a flexible work schedule can help you manage both work and personal responsibilities. Achieving work-life balance is a continuous journey that requires mindfulness and effort.', '2024-11-19 10:40:23', 'Work-Life Balance', 'https://example.com/posts/worklife.jpg', 0),
(12, 9, 5, 'Creating a personalized fitness plan is crucial for achieving your goals. Everyone’s body is different, and so are their fitness needs. The first step is to determine your fitness goals—whether it’s weight loss, muscle gain, or improving endurance. Next, design a workout routine that targets those goals. Incorporating a mix of strength training, cardio, and flexibility exercises will provide a balanced plan. It’s also important to track your progress to stay motivated and make adjustments as needed. A personalized plan should include rest days to allow your body to recover. Nutrition plays a key role in fitness, so fueling your body with the right foods is essential. Don’t be afraid to ask for advice from fitness professionals to optimize your plan. A personalized fitness plan ensures that you’re training in a way that works best for your body and your lifestyle.', '2024-11-19 10:45:23', 'Personalized Fitness Plans', 'https://example.com/posts/workout.jpg', 0),
(13, 7, 6, 'Music has evolved significantly over the years. From classical compositions to modern pop, music has always been an important part of culture. Advances in technology have made it easier for artists to create and share music. Streaming platforms have revolutionized the way people access music, giving artists a global reach. Music has the power to evoke emotions and bring people together. It has been used as a form of expression, resistance, and celebration throughout history. The diversity of music genres means there is something for everyone. Live music events allow fans to connect with their favorite artists. Music also has therapeutic benefits, helping to reduce stress and improve mental well-being. Whether you’re a listener or a musician, music is a universal language that transcends barriers.', '2024-11-19 10:50:23', 'Music and Culture', 'https://example.com/posts/music.jpg', 1),

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
(14, 'jack_purple876', 'Jack Purple', 'jack.purple876@example.com', 'hashedpassword876', '2024-11-19 08:40:23', 'Food blogger and traveler.', NULL),
(15, 'bartol136', 'Bartek Kostrzewski', 'bartek@gmail.com', '$2y$10$nWm2ikzuwtWhvX8kFxqsBelAemVEtyqADsj/gWtPaB10LyDmRgkUO', '2024-11-20 09:30:46', NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`event_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `event_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;
COMMIT;
