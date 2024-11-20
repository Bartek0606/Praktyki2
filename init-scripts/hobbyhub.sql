-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: mysql
-- Generation Time: Nov 19, 2024 at 11:15 PM
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
(4, 'Travel', 'Posts about travel experiences and destinations');
 
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
(3, 3, 7, 'I need to try this workout routine. Looks amazing!', '2024-11-19 09:10:23');
 
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
  `image_url` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
 
--
-- Dumping data for table `posts`
--
 
INSERT INTO `posts` (`post_id`, `user_id`, `category_id`, `content`, `created_at`, `title`, `is_question`, `image_url`) VALUES
(1, 7, 1, 'Latest advancements in AI technology are shaping our future. The field of artificial intelligence has made significant strides in recent years. Researchers are constantly developing new algorithms. From machine learning to neural networks, AI is evolving rapidly. The applications of AI span across various industries, including healthcare, finance, and education. One exciting area is AI\'s ability to process large amounts of data. This allows for more accurate predictions and smarter systems. AI is also improving automation, making processes more efficient. As the technology advances, ethical considerations become crucial. It\'s essential to balance innovation with responsibility.', '2024-11-19 08:40:23', 'AI in Technology', 0, 'https://example.com/posts/ai.jpg'),
(2, 8, 4, 'Exploring the beauty of Italy is an unforgettable experience. The country is rich in history, art, and culture. Cities like Rome, Florence, and Venice are filled with architectural marvels. Italy is known for its stunning landscapes, from the Alps to the Mediterranean coast. One of the highlights of visiting Italy is the cuisine. Italian food, including pasta, pizza, and gelato, is world-renowned. Every region offers unique dishes and flavors. Italy is also home to some of the most famous art museums and galleries. Whether you\'re exploring ancient ruins or strolling through charming villages, Italy has something for everyone. It\'s a perfect destination for anyone seeking beauty and culture.', '2024-11-19 08:45:23', 'Travel in Italy', 0, 'https://example.com/posts/italy.jpg'),
(3, 9, 3, 'Fitness routine for a healthy life is essential for maintaining overall well-being. Regular exercise helps to keep the body strong and healthy. A well-rounded fitness routine includes both cardio and strength training. This combination improves cardiovascular health and builds muscle mass. It\'s important to set realistic fitness goals and track progress over time. Nutrition plays a crucial role in achieving fitness goals. Eating a balanced diet with plenty of fruits and vegetables is key. Hydration is also vital for supporting physical activity. Mental health benefits come from regular exercise as well, reducing stress and boosting mood. A healthy lifestyle requires consistency and commitment, but the rewards are worth it.', '2024-11-19 08:50:23', 'Healthy Fitness Habits', 0, 'https://example.com/posts/fitness.jpg'),
(4, 10, 2, 'Living a minimalist lifestyle can have numerous benefits. It encourages decluttering and organizing your space. Minimalism fosters a focus on what truly matters. Reducing distractions helps in achieving mental clarity. A minimalist home is typically more peaceful and less stressful. Minimalism can also lead to savings, as it reduces unnecessary purchases. By focusing on quality over quantity, it’s possible to find joy in the essentials. Living with less allows for a more intentional approach to life. This lifestyle encourages creativity and mindfulness. Embracing minimalism can bring a sense of calm and satisfaction to everyday life.', '2024-11-20 08:00:23', 'The Joy of Minimalism', 0, 'https://example.com/posts/minimalism.jpg'),
(5, 11, 2, 'Healthy habits are essential for a balanced lifestyle. It’s important to start your day with a nutritious breakfast. Incorporating regular exercise into your routine is key for overall health. Staying hydrated is another crucial habit that supports energy and mental focus. Adequate sleep is vital for physical and mental recovery. It’s also important to manage stress through activities like meditation or yoga. Setting realistic goals helps you stay motivated and focused. Prioritizing relationships and spending time with loved ones is a key aspect of a healthy life. A balanced life includes time for both work and play. Healthy habits lead to long-term well-being and happiness.', '2024-11-20 08:05:23', 'Building Healthy Habits', 0, 'https://example.com/posts/healthyhabits.jpg'),
(6, 12, 3, 'The importance of mental health cannot be overstated. Mental well-being is just as important as physical health. Practices like mindfulness and meditation can help manage stress. Talking to a therapist or counselor can provide valuable support. Regular physical exercise has been shown to boost mood and reduce anxiety. It’s important to maintain social connections and communicate openly with others. A balanced diet rich in nutrients also plays a role in mental health. Taking time for self-care is essential for managing emotional well-being. It’s okay to ask for help when needed, and seeking support is a sign of strength. By prioritizing mental health, you can lead a more fulfilling life.', '2024-11-20 08:10:23', 'Prioritizing Mental Health', 0, 'https://example.com/posts/mentalhealth.jpg'),
(7, 13, 4, 'Exploring new cultures is one of the most rewarding experiences. Traveling opens up the mind to different perspectives and ways of life. It allows you to try new foods, hear different languages, and understand diverse customs. Learning about history and traditions enhances cultural appreciation. Traveling also provides opportunities for personal growth and development. It encourages stepping out of your comfort zone and adapting to new environments. Every destination offers something unique, whether it’s nature, architecture, or people. Cultural exploration fosters empathy and global understanding. It’s a great way to build connections and create lasting memories. Traveling to new places can change the way you view the world.', '2024-11-20 08:15:23', 'The Beauty of Cultural Exploration', 0, 'https://example.com/posts/culture.jpg'),
(8, 14, 3, 'Staying fit doesn’t have to mean spending hours at the gym. A simple workout routine can be effective for maintaining good health. Bodyweight exercises like push-ups and squats can help build strength. Cardiovascular activities like jogging or cycling improve endurance. Flexibility exercises, such as stretching or yoga, are great for overall mobility. It’s important to focus on consistency rather than intensity. A healthy fitness routine also includes rest days for recovery. Nutrition plays an important role in supporting physical activity. The key is finding a balance between exercise, nutrition, and recovery. Staying fit is about making small, sustainable changes in your lifestyle.', '2024-11-20 08:20:23', 'Simple Fitness Tips for Everyday Life', 0, 'https://example.com/posts/simplefitness.jpg'),
(9, 15, 1, 'Artificial intelligence (AI) is transforming various industries. It’s improving efficiency in sectors like healthcare, transportation, and finance. Machine learning algorithms help businesses make data-driven decisions. AI is also used in customer service, providing chatbots for instant responses. In the healthcare industry, AI is helping doctors diagnose diseases faster. AI-powered automation is streamlining manufacturing processes. As AI technology advances, ethical questions about data privacy and bias arise. The future of AI holds great promise, but it’s important to address these challenges. Innovations in AI will continue to shape how we live and work. The possibilities for AI are endless, and its impact will only grow in the coming years.', '2024-11-20 08:25:23', 'The Impact of Artificial Intelligence', 0, 'https://example.com/posts/aiimpact.jpg'),
(10, 16, 4, 'Adventure travel is for those seeking thrill and excitement. Whether it’s hiking through rugged mountains or exploring remote islands, adventure travel offers a unique experience. It’s about stepping outside of the ordinary and pushing your limits. Activities like bungee jumping, skydiving, and scuba diving offer adrenaline-pumping fun. The beauty of adventure travel is in the unpredictability of each journey. It’s about immersing yourself in nature and testing your physical and mental endurance. Adventure travel is not just about the activity, but also about the people you meet along the way. It builds resilience and fosters a sense of accomplishment. Planning an adventure trip requires careful research and preparation. However, the rewards of pushing yourself and overcoming challenges are priceless.', '2024-11-20 08:30:23', 'Thrill-Seeking Adventure Travel', 0, 'https://example.com/posts/adventuretravel.jpg');
 
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
(4, 'admin', 'admin', 'admin@example.com', 'admin', '2024-11-19 08:35:23', 'https://example.com/profiles/admin.jpg', 'Administrator of the platform.');
COMMIT;
 