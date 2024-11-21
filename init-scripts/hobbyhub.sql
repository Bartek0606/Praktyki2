-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: mysql
-- Generation Time: Lis 21, 2024 at 12:06 PM
-- Wersja serwera: 5.7.44
-- Wersja PHP: 8.2.8

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
-- Struktura tabeli dla tabeli `blog_information`
--

CREATE TABLE `blog_information` (
  `id` int(10) UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` blob,
  `category_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `blog_information`
--

INSERT INTO `blog_information` (`id`, `title`, `content`, `image`, `category_id`) VALUES
(4, 'Exploring the Future of Technology!', 'Welcome to the technology blog! This is the place where innovations meet everyday life. Whether you are interested in artificial intelligence, mobile devices, or the latest in technology, you will find everything you need to know about the future of technology.\r\n\r\nOn the blog, you will find:\r\n- Technology guides – How to choose the best gadgets, manage devices, and use modern technology.\r\n- The history of innovation – Let’s explore the most important breakthroughs in the history of technology and their impact on our daily lives.\r\n- The latest in tech – Stay up-to-date with the newest discoveries and trends shaping the world.\r\n\r\nGet ready for a journey into the future of technology!', NULL, 1),
(5, 'Lifestyle: The Art of Living in Balance!', 'Welcome to the lifestyle blog! Here you will find inspiration for living life to the fullest – from healthy habits, mindfulness, to the art of relaxation. Whether you want to improve your well-being, find balance in life, or simply look for new ways to spend your free time, this blog is for you!\r\n\r\nOn the blog, you will find:\r\n- Lifestyle tips – How to take care of your physical and mental health, how to organize your time to feel fulfilled.\r\n- Stories of people who changed their lives – Learn inspiring stories of how changing habits can lead to great achievements.\r\n- Guides to daily rituals – From morning routines to evening relaxation, everything that can improve the quality of your life.\r\n\r\nStart your journey to a better life now!', NULL, 2),
(6, 'Health: Your Path to Better Well-Being!', 'Welcome to the health blog! This is the space where you will find all the information to take care of your health – both physical and mental. From daily habits to advanced techniques for taking care of your body, health is the key to success.\r\n\r\nOn the blog, you will find:\r\n- Practical health advice – How to stay fit, how to manage stress, and improve your sleep quality.\r\n- Workout plans – Proven programs to help you achieve better physical health results.\r\n- Health psychology – How to take care of your mental health and maintain balance in life.\r\n\r\nStart your path to better well-being today!', NULL, 3),
(7, 'Travel: Discover the World from a New Perspective!', 'Welcome to the travel blog! This is the space where we share our passion for travel, discovering unique places, cultures, and stories from every corner of the world. Want to explore unknown places, learn the rules of budget travel, or simply get inspired by travel stories? Let’s get started!\r\n\r\nOn the blog, you will find:\r\n- Travel guides – How to travel on a budget, how to plan dream trips, and what to pack for your travels.\r\n- Stories of travelers – Learn about journeys that change lives and see the world in a new way.\r\n- Travel inspirations – Discover the most beautiful places in the world you should visit before the end of your life.\r\n\r\nStart your adventure now!', NULL, 4),
(8, 'Education: The Key to a Better Future!', 'Welcome to the educational blog! Education is a powerful tool that changes lives. Whether you are a student, a teacher, or a learning enthusiast, you will find materials and advice to help you grow.\r\n\r\nOn the blog, you will find:\r\n- Educational tips – How to learn effectively, how to motivate yourself to study, and how to grasp difficult materials.\r\n- Course and book reviews – We’ll cover the best educational materials to help you grow.\r\n- Learning inspirations – Stories of people who gained knowledge in unconventional ways and succeeded.\r\n\r\nInvest in your educational future!', NULL, 5),
(9, 'Entertainment: Have Fun With Us!', 'Welcome to the entertainment blog! If you are looking for the latest updates on movies, TV shows, music, and pop culture, this is the place for you. We offer daily inspiration and recommendations from the world of entertainment!\r\n\r\nOn the blog, you will find:\r\n- Movie and TV show reviews – What’s worth watching in theaters and on TV.\r\n- Music news – When and where are the next concerts? What’s happening in the music world?\r\n- Pop culture guides – Check out the latest trends in games, books, and social media.\r\n\r\nGet ready for some fun!', NULL, 6),
(10, 'Business: The Art of Building Success!', 'Welcome to the business blog! Here you will find everything you need to build and grow your business. Whether you are just starting or already an experienced entrepreneur, you will find useful tips and inspiration here.\r\n\r\nOn the blog, you will find:\r\n- Tips for entrepreneurs – How to run a business, acquire clients, and manage finances.\r\n- Success stories – Learn about people who achieved success thanks to their passion and determination.\r\n- Growth strategies – How to develop your company, optimize processes, and increase profits.\r\n\r\nInvest in your business success!', NULL, 7),
(11, 'Food: Culinary Inspirations for Every Day!', 'Welcome to the food blog! Here you will find recipes for delicious dishes, cooking tips, and culinary inspirations. If you love cooking and trying new flavors, this blog is for you!\r\n\r\nOn the blog, you will find:\r\n- Culinary recipes – From simple meals to gourmet dishes that will surprise your taste buds.\r\n- Cooking tips – How to improve your cooking, use seasonal ingredients, and organize your kitchen.\r\n- Culinary inspiration – Find ideas for dishes to try and explore the tastes of the world.\r\n\r\nGet ready for a culinary journey!', NULL, 8);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `categories`
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
-- Struktura tabeli dla tabeli `comments`
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
(13, 1, 8, 'AI is transforming everything! Exciting times ahead.', '2024-11-19 10:00:23');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `events`
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
-- Struktura tabeli dla tabeli `posts`
--

CREATE TABLE `posts` (
  `post_id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `category_id` int(10) UNSIGNED DEFAULT NULL,
  `content` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `title` varchar(255) NOT NULL,
  `is_question` tinyint(1) DEFAULT '0',
  `image` blob
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`post_id`, `user_id`, `category_id`, `content`, `created_at`, `title`, `is_question`, `image`) VALUES
(1, 1, 1, 'Artificial intelligence is evolving at a rapid pace. Every year, we see new advancements in machine learning algorithms. AI is transforming industries across the globe, from healthcare to finance. In the future, AI will likely become more integrated into our daily lives. With self-driving cars and smart home devices, we are already seeing the benefits of AI. However, the ethical implications of AI remain a critical issue. As we develop more powerful AI, we must ensure it is used responsibly. This includes preventing biases in algorithms and ensuring transparency. The future of AI is exciting, but we must tread carefully to ensure it benefits all of humanity.', '2024-11-20 11:03:20', 'Exploring the Future of AI', 0, NULL),
(2, 2, 4, 'Italian food is loved by people all around the world. The rich flavors of pasta, pizza, and wine are just the beginning. Each region of Italy has its own unique take on traditional dishes. From the creamy risottos of Milan to the seafood in Sicily, the diversity of Italian cuisine is astounding. Italian food is often made with fresh, local ingredients. The simplicity of dishes like pasta with olive oil and garlic can be extraordinary when made with the best ingredients. No meal is complete without a good bottle of wine. Italian cuisine is not just about food; it\'s about a way of life. Sharing a meal with friends and family is an important part of Italian culture. Whether you\'re in Rome or a small village, the love for food is universal.', '2024-11-20 11:03:20', 'The Wonders of Italian Cuisine', 1, NULL),
(3, 3, 3, 'Exercise is one of the most important aspects of maintaining a healthy lifestyle. Regular physical activity has numerous benefits for both the body and the mind. It helps to reduce the risk of chronic diseases like heart disease, diabetes, and obesity. Exercise also improves mental health by reducing stress and anxiety. Whether it\'s running, swimming, or practicing yoga, all forms of exercise contribute to overall well-being. Additionally, exercise can improve sleep quality and boost energy levels. Strength training is especially beneficial for building muscle mass and improving bone density. For those who are just starting, even light exercise like walking can make a significant difference. The key is consistency, and making exercise a part of your daily routine. Remember, a healthy body leads to a healthy mind.', '2024-11-20 11:03:20', 'The Benefits of Regular Exercise', 0, NULL),
(4, 1, 1, 'The rapid growth of quantum computing could revolutionize industries from pharmaceuticals to finance. Unlike classical computers, quantum computers use quantum bits (qubits) which allow for faster and more efficient processing of complex problems. Although still in its early stages, the technology promises to solve problems that would take traditional computers thousands of years to process. The future of quantum computing looks incredibly exciting, and we may see significant breakthroughs within the next decade.', '2024-11-21 12:30:00', 'The Future of Quantum Computing', 0, NULL),
(5, 2, 1, '5G technology is set to transform the way we connect to the world. With ultra-fast speeds and low latency, 5G will unlock new possibilities for virtual reality, autonomous vehicles, and the Internet of Things (IoT). As 5G networks continue to roll out globally, we will see advancements in remote healthcare, smart cities, and entertainment. However, the widespread adoption of 5G will also raise important questions about security, privacy, and the impact on our health.', '2024-11-21 12:45:00', '5G: A Revolution in Connectivity', 0, NULL),
(6, 3, 2, 'Mindfulness is more than just a trend – it’s a powerful tool for managing stress and improving overall well-being. By taking a few minutes each day to focus on your breathing and clear your mind, you can reduce anxiety, increase concentration, and boost your mood. Many successful people swear by mindfulness practices to stay grounded in their busy lives. Incorporating mindfulness into your daily routine can transform your mental and physical health for the better.', '2024-11-21 13:00:00', 'Mindfulness: A Path to Mental Clarity', 0, NULL),
(7, 4, 2, 'Minimalism isn’t about owning fewer things; it’s about creating space for what truly matters. By letting go of unnecessary possessions, you can declutter your mind and life, allowing more room for experiences, relationships, and personal growth. Minimalism encourages intentional living, where every item you own adds value to your life. It’s a lifestyle choice that promotes simplicity, joy, and a deeper connection to the present moment.', '2024-11-21 13:15:00', 'The Power of Minimalism', 0, NULL),
(8, 7, 4, 'Traveling is a life-changing experience that opens your mind to new cultures and ways of life. Whether you’re exploring the vibrant streets of Tokyo or relaxing on a beach in the Caribbean, travel allows you to step out of your comfort zone. It’s about learning new perspectives, making memories, and discovering the beauty of the world. The more we travel, the more we understand the importance of embracing different cultures and experiencing the world beyond our borders.', '2024-11-21 14:00:00', 'Why Traveling is So Important', 0, NULL),
(9, 8, 4, 'Solo travel can be one of the most rewarding experiences. It allows you to be independent, make your own decisions, and immerse yourself fully in the local culture. Traveling solo helps you build confidence and learn more about yourself, as you are forced to adapt to new environments. Whether you’re backpacking through Europe or visiting remote destinations, solo travel can be transformative, allowing you to create unforgettable memories.', '2024-11-21 14:15:00', 'The Joys of Solo Travel', 0, NULL),
(10, 7, 4, 'Traveling is a life-changing experience that opens your mind to new cultures and ways of life. Whether you’re exploring the vibrant streets of Tokyo or relaxing on a beach in the Caribbean, travel allows you to step out of your comfort zone. It’s about learning new perspectives, making memories, and discovering the beauty of the world. The more we travel, the more we understand the importance of embracing different cultures and experiencing the world beyond our borders.', '2024-11-21 14:00:00', 'Why Traveling is So Important', 0, NULL),
(11, 8, 4, 'Solo travel can be one of the most rewarding experiences. It allows you to be independent, make your own decisions, and immerse yourself fully in the local culture. Traveling solo helps you build confidence and learn more about yourself, as you are forced to adapt to new environments. Whether you’re backpacking through Europe or visiting remote destinations, solo travel can be transformative, allowing you to create unforgettable memories.', '2024-11-21 14:15:00', 'The Joys of Solo Travel', 0, NULL),
(12, 9, 5, 'Online learning has revolutionized the way we acquire knowledge. With the ability to access courses from top universities around the world, students can learn at their own pace and from the comfort of their own home. Whether it’s a short-term certificate or a full degree program, online education offers flexibility that traditional learning environments cannot match. The future of education is digital, and it’s making learning more accessible than ever before.', '2024-11-21 14:30:00', 'The Rise of Online Learning', 0, NULL),
(13, 10, 5, 'Learning a new language opens doors to understanding different cultures and broadening your career opportunities. With technology, learning languages has become easier than ever. Apps, online courses, and language exchange programs allow you to practice speaking with native speakers and learn at your own pace. It takes dedication and patience, but the rewards are immense. From enhancing your cognitive abilities to making international connections, language learning is a valuable skill in today’s globalized world.', '2024-11-21 14:45:00', 'The Benefits of Learning a New Language', 0, NULL),
(14, 11, 6, 'The entertainment industry is constantly evolving with new trends, platforms, and content formats. From streaming services like Netflix and Spotify to live events and social media influencers, there’s always something new to discover. The way we consume entertainment is changing rapidly, and it’s important to keep up with these shifts. Whether it’s watching the latest blockbuster or attending a virtual concert, entertainment today offers more options than ever before.', '2024-11-21 15:00:00', 'The Changing World of Entertainment', 0, NULL),
(15, 12, 6, 'Music has the power to heal and inspire. Whether you’re listening to your favorite artist on a long drive or attending a live concert, music brings people together and evokes emotions like no other medium. With so many genres to explore, there’s always something for everyone. Today’s music scene is more diverse than ever, with genres fusing together and new artists emerging every day. The future of music is bright, and it’s an exciting time to be a fan.', '2024-11-21 15:15:00', 'The Power of Music in Our Lives', 0, NULL),
(18, 13, 7, 'Starting your own business can be both thrilling and overwhelming. From securing funding to building a brand, there are many steps involved in turning an idea into a successful venture. One of the most important things you can do is develop a solid business plan. It’s essential to know your market, identify potential competitors, and have a clear strategy for growth. Entrepreneurship is a journey, and the lessons you learn along the way will shape your future success.', '2024-11-21 15:30:00', 'The Journey of Starting a Business', 0, NULL),
(19, 14, 7, 'In today’s fast-paced world, innovation is the key to staying competitive in business. Whether it’s launching a new product, improving customer service, or embracing new technologies, innovation can give your business the edge it needs to succeed. Companies that fail to innovate often fall behind, while those that embrace change can set trends and disrupt industries. The future of business is all about innovation, and those who lead the way will thrive in an ever-changing marketplace.', '2024-11-21 15:45:00', 'The Importance of Innovation in Business', 0, NULL),
(20, 15, 8, 'Food is a universal language that brings people together. From street food to fine dining, there’s always something to explore. Each cuisine tells a unique story about culture, history, and tradition. As we become more health-conscious, plant-based and organic foods are gaining popularity. However, food is not just about nourishment; it’s about pleasure and the joy of sharing meals with friends and family. Every bite is an opportunity to experience something new.', '2024-11-21 16:00:00', 'The Joy of Exploring Global Cuisines', 0, NULL),
(21, 16, 8, 'Cooking at home can be a rewarding experience. Not only do you get to control the ingredients and flavors, but it’s also an opportunity to be creative in the kitchen. From trying new recipes to experimenting with different spices, cooking can be a fun and therapeutic activity. Whether you’re making a simple meal or a gourmet dish, there’s something satisfying about preparing food from scratch and sharing it with others.', '2024-11-21 16:15:00', 'The Art of Cooking at Home', 0, NULL),
(22, 19, 10, 'Fashion is not just about clothing; it’s an expression of personality, creativity, and culture. Over the years, fashion trends have evolved, influenced by everything from social movements to technological advances. Today, sustainable fashion is gaining momentum as consumers become more conscious about their environmental impact. Eco-friendly materials, upcycled fabrics, and ethical production are shaping the future of fashion, making it both stylish and responsible.', '2024-11-21 17:00:00', 'The Future of Sustainable Fashion', 0, NULL),
(23, 20, 10, 'Street style has become one of the most influential aspects of modern fashion. What started as casual, everyday wear has now turned into a global trend, with influencers and designers drawing inspiration from the streets. From oversized hoodies to statement sneakers, street style reflects the individuality and creativity of the people wearing it. It’s a celebration of personal expression and an important part of the fashion industry today.', '2024-11-21 17:15:00', 'The Rise of Street Style', 0, NULL);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `users`
--

CREATE TABLE `users` (
  `user_id` int(10) UNSIGNED NOT NULL,
  `username` varchar(50) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `bio` text,
  `profile_picture` blob
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `full_name`, `email`, `password_hash`, `created_at`, `bio`, `profile_picture`) VALUES
(1, 'user1', 'Jan Kowalski', 'jan.kowalski@example.com', 'hashed_password_1', '2024-11-20 11:56:58', 'Bio for Jan Kowalski', NULL),
(2, 'user2', 'Anna Nowak', 'anna.nowak@example.com', 'hashed_password_2', '2024-11-20 11:56:58', 'Bio for Anna Nowak', NULL),
(3, 'user3', 'Piotr Wisniewski', 'piotr.wisniewski@example.com', 'hashed_password_3', '2024-11-20 11:56:58', 'Bio for Piotr Wisniewski', NULL),
(4, 'user', 'user', 'user@gmail.com', '$2y$10$ASVPw2I4SegRLj.k2XGe5OBh5hsOKe1RNm3rTj7rU15tkhdWXebX.', '2024-11-20 12:03:29', 'a', NULL);

--
-- Indeksy dla zrzutów tabel
--

--
-- Indeksy dla tabeli `blog_information`
--
ALTER TABLE `blog_information`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indeksy dla tabeli `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`category_id`);

--
-- Indeksy dla tabeli `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`post_id`);

--
-- Indeksy dla tabeli `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `blog_information`
--
ALTER TABLE `blog_information`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `post_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `blog_information`
--
ALTER TABLE `blog_information`
  ADD CONSTRAINT `blog_information_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`category_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
