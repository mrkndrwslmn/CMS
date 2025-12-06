-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: mysql
-- Generation Time: Dec 06, 2025 at 04:04 AM
-- Server version: 8.0.44
-- PHP Version: 8.3.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `cms`
--

-- --------------------------------------------------------

--
-- Table structure for table `adiutor_calendar_integrations`
--

CREATE TABLE `adiutor_calendar_integrations` (
  `id` bigint UNSIGNED NOT NULL,
  `adiutor_id` bigint UNSIGNED NOT NULL,
  `provider` enum('google') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'google',
  `calendar_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `access_token` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `refresh_token` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `token_expires_at` timestamp NULL DEFAULT NULL,
  `is_connected` tinyint(1) NOT NULL DEFAULT '0',
  `last_synced_at` timestamp NULL DEFAULT NULL,
  `sync_settings` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `adiutor_calendar_integrations`
--

INSERT INTO `adiutor_calendar_integrations` (`id`, `adiutor_id`, `provider`, `calendar_id`, `access_token`, `refresh_token`, `token_expires_at`, `is_connected`, `last_synced_at`, `sync_settings`, `created_at`, `updated_at`) VALUES
(1, 34, 'google', 'calli.ri0904@gmail.com', 'eyJpdiI6ImpvNE5uVkFaYUY4RFIxakdKNklwTnc9PSIsInZhbHVlIjoiKzY4NWF0L1R1cUNaQlYyYTU4SGJNcVR6SUpPekdEdzRzKzNpUnhGemFDa2gzWlpDamNQbHczN2s3Q0FwckxzRHdmamxoVG9QaUMvQ3drRmU5VEFVTHg5QkJsTGw4RDJMOVNQMGVxemtVOU04ZWtQSW8yOFd6L2FCRE1wYTdjckFDNkllNUZuSjl3VTk3R0pNZEVuMUZqcThFajlXTjJCSCtNdTFlWUQ4UjlLWFVCamQ4UlJqVzRUSzMwY3hqb0Q5dlY3TkpKODdQM0luMUdEd3F5YUVVdDRsTEZ1S3N1akxqaExLamY1Yit1a0RkK0lBZlE5d0NpS0k3R01lM1hFaEpFYXpJWU4wK2ZETnpqdW5UVjJEVlpIeWVpYXdvaDlWQm0xSXRoQ1Q3VUJxQjYrZHErWldjZmFBVzNramFhbHczZ3N0dm1ZellzUFFXOUtMSy9vTmZ3PT0iLCJtYWMiOiJiODA5NmJkOGQ2MmI3OWZmMTIxYzlhYzc4Mzc3M2QxNDA4NGQ3MDg0Mzg2NGQ2ZTQ5ZGI1OWFlODEzNDgwMDFhIiwidGFnIjoiIn0=', 'eyJpdiI6IlpkMnJ3NFVHUTBjWnoxcElCQlZkTlE9PSIsInZhbHVlIjoiRmVQYUZvU21PQnFqRVBRTTZvR2tRVkdCNHNHeDhoaVJXaU50MysxMFAyR1h4bEkvTWE2T2xCZXMyaVZqcGQ0UmRUbml6YzkvbEtjcVZpV3VBRFhONGplay9BeXV4L3hJejcvYjhDQmNTTUo0TEtscU5adENZSWRLVkNBWE9hdWc2N0ZyRWhaZ1dHVEswb3hQdkswODZRPT0iLCJtYWMiOiI2MTI3MmJmMDYzZjAwZjY0MWZmMzA1ZjNjZWI5MDI4MjM0YzRmODAzNTVkM2YwMjE1N2IyNGVhYzYwYzA3YjFlIiwidGFnIjoiIn0=', '2025-11-23 18:46:58', 1, '2025-11-17 10:19:23', '{\"timezone\": \"Asia/Manila\", \"working_hours\": {\"end\": \"17:00:00\", \"start\": \"09:00:00\"}}', '2025-11-16 10:35:01', '2025-11-23 17:46:59');

-- --------------------------------------------------------

--
-- Table structure for table `adiutor_profiles`
--

CREATE TABLE `adiutor_profiles` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `bio` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `standard_hourly_rate` decimal(8,2) DEFAULT NULL,
  `currency` varchar(3) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'PHP',
  `minimum_payout_amount` decimal(10,2) NOT NULL DEFAULT '500.00',
  `preferred_payout_method` enum('bank_transfer','paypal','gcash','paymaya','other') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payout_details` json DEFAULT NULL,
  `availability` json DEFAULT NULL,
  `portfolio_url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `linkedin_url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `github_url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `experience` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `languages` json DEFAULT NULL,
  `location` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_verified` tinyint(1) NOT NULL DEFAULT '0',
  `rating` decimal(3,2) NOT NULL DEFAULT '0.00',
  `total_projects` int NOT NULL DEFAULT '0',
  `status` enum('active','inactive','busy') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `adiutor_profiles`
--

INSERT INTO `adiutor_profiles` (`id`, `user_id`, `bio`, `title`, `standard_hourly_rate`, `currency`, `minimum_payout_amount`, `preferred_payout_method`, `payout_details`, `availability`, `portfolio_url`, `linkedin_url`, `github_url`, `experience`, `languages`, `location`, `is_verified`, `rating`, `total_projects`, `status`, `created_at`, `updated_at`) VALUES
(1, 7, 'Experienced full-stack developer with expertise in React, Node.js, and Laravel. Passionate about creating scalable web applications.', 'Full-Stack Developer', 500.00, 'PHP', 500.00, 'bank_transfer', '{\"bank_name\": \"BDO\", \"account_name\": \"Mark Andrew Soliman\", \"paypal_email\": null, \"mobile_number\": null, \"other_details\": null, \"account_number\": \"123456789\"}', '\"{\\\"timezone\\\":\\\"America\\\\/New_York\\\",\\\"max_projects\\\":3}\"', 'https://alexdev.portfolio.com', NULL, NULL, 'BS Computer Science, MIT. 5 years of professional development experience.', '\"[\\\"English\\\",\\\"Spanish\\\"]\"', 'New York, NY', 0, 4.38, 0, 'active', '2025-10-25 06:38:18', '2025-12-02 10:45:01'),
(2, 8, 'Creative UI/UX designer with a passion for user-centered design and modern aesthetics. Expert in Figma and Adobe Creative Suite.', 'UI/UX Designer', NULL, 'PHP', 500.00, 'gcash', '{\"bank_name\": null, \"account_name\": null, \"paypal_email\": null, \"mobile_number\": \"09171415823\", \"other_details\": null, \"account_number\": null}', NULL, 'https://jessicadesign.portfolio.com', NULL, NULL, NULL, NULL, 'Los Angeles, CA', 0, 4.40, 0, 'active', '2025-10-25 06:38:18', '2025-12-02 10:45:01'),
(3, 9, 'Senior backend developer specializing in scalable API development and database optimization. Expert in Python, Django, and cloud architecture.', 'Senior Backend Developer', 80.00, 'PHP', 500.00, NULL, NULL, '\"{\\\"timezone\\\":\\\"Europe\\\\/London\\\",\\\"max_projects\\\":2}\"', 'https://michaelbackend.dev', NULL, NULL, 'MS Computer Science, Stanford. 7 years of professional backend development experience.', '\"[\\\"English\\\",\\\"German\\\"]\"', 'London, UK', 0, 4.20, 0, 'busy', '2025-10-25 06:38:18', '2025-12-02 10:45:01'),
(4, 10, 'Frontend developer passionate about creating beautiful, responsive user interfaces. Specializes in Vue.js and modern CSS frameworks.', 'Frontend Developer', 60.00, 'PHP', 500.00, NULL, NULL, '\"{\\\"timezone\\\":\\\"America\\\\/Los_Angeles\\\",\\\"max_projects\\\":3}\"', 'https://sarahfrontend.dev', NULL, NULL, 'BS Web Development, UCLA. 4 years of professional frontend development experience.', '\"[\\\"English\\\",\\\"Japanese\\\"]\"', 'Los Angeles, CA', 0, 4.17, 0, 'active', '2025-10-25 06:38:18', '2025-12-02 10:45:01'),
(5, 11, 'Mobile app developer with expertise in React Native and Flutter. Experienced in building cross-platform applications for startups and enterprises.', 'Mobile Developer', 70.00, 'PHP', 500.00, NULL, NULL, '\"{\\\"timezone\\\":\\\"America\\\\/Mexico_City\\\",\\\"max_projects\\\":2}\"', 'https://carlosmobile.dev', NULL, NULL, 'BS Software Engineering, USC. 5 years of professional mobile development experience.', '\"[\\\"English\\\",\\\"Spanish\\\",\\\"Portuguese\\\"]\"', 'Mexico City, Mexico', 0, 3.00, 0, 'active', '2025-10-25 06:38:18', '2025-12-02 10:45:01'),
(6, 34, 'Graduate of Bachelors of Science in Information Technology', 'Senior Developer', 400.00, 'PHP', 500.00, 'paymaya', '{\"bank_name\": null, \"account_name\": null, \"paypal_email\": null, \"mobile_number\": \"12456789012\", \"other_details\": null, \"account_number\": null}', NULL, NULL, NULL, NULL, NULL, NULL, 'Laguna, Philippines', 0, 0.00, 0, 'active', '2025-11-16 10:37:09', '2025-11-18 14:28:55');

-- --------------------------------------------------------

--
-- Table structure for table `adiutor_skills`
--

CREATE TABLE `adiutor_skills` (
  `id` bigint UNSIGNED NOT NULL,
  `adiutor_id` bigint UNSIGNED NOT NULL,
  `skill_id` bigint UNSIGNED NOT NULL,
  `proficiency_level` enum('beginner','intermediate','advanced','expert') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'intermediate',
  `years_experience` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `adiutor_skills`
--

INSERT INTO `adiutor_skills` (`id`, `adiutor_id`, `skill_id`, `proficiency_level`, `years_experience`, `created_at`, `updated_at`) VALUES
(1, 1, 2, 'expert', 5, '2025-10-25 06:38:18', '2025-10-25 06:38:18'),
(2, 1, 9, 'expert', 4, '2025-10-25 06:38:18', '2025-10-25 06:38:18'),
(3, 1, 12, 'advanced', 4, '2025-10-25 06:38:18', '2025-10-25 06:38:18'),
(4, 1, 13, 'advanced', 3, '2025-10-25 06:38:18', '2025-10-25 06:38:18'),
(5, 1, 14, 'advanced', 5, '2025-10-25 06:38:18', '2025-10-25 06:38:18'),
(11, 3, 3, 'expert', 7, '2025-10-25 06:38:18', '2025-10-25 06:38:18'),
(12, 3, 56, 'expert', 6, '2025-10-25 06:38:18', '2025-10-25 06:38:18'),
(13, 3, 15, 'expert', 7, '2025-10-25 06:38:18', '2025-10-25 06:38:18'),
(14, 3, 29, 'advanced', 5, '2025-10-25 06:38:18', '2025-10-25 06:38:18'),
(15, 3, 57, 'expert', 7, '2025-10-25 06:38:18', '2025-10-25 06:38:18'),
(16, 4, 10, 'expert', 4, '2025-10-25 06:38:18', '2025-10-25 06:38:18'),
(17, 4, 58, 'expert', 4, '2025-10-25 06:38:18', '2025-10-25 06:38:18'),
(18, 4, 59, 'advanced', 3, '2025-10-25 06:38:18', '2025-10-25 06:38:18'),
(19, 4, 2, 'advanced', 4, '2025-10-25 06:38:18', '2025-10-25 06:38:18'),
(20, 4, 60, 'expert', 4, '2025-10-25 06:38:18', '2025-10-25 06:38:18'),
(21, 5, 61, 'expert', 5, '2025-10-25 06:38:18', '2025-10-25 06:38:18'),
(22, 5, 62, 'advanced', 3, '2025-10-25 06:38:18', '2025-10-25 06:38:18'),
(23, 5, 63, 'intermediate', 3, '2025-10-25 06:38:18', '2025-10-25 06:38:18'),
(24, 5, 64, 'advanced', 4, '2025-10-25 06:38:18', '2025-10-25 06:38:18'),
(25, 5, 65, 'advanced', 5, '2025-10-25 06:38:18', '2025-10-25 06:38:18'),
(26, 6, 1, 'expert', 7, '2025-11-16 11:06:16', '2025-11-16 11:06:16'),
(27, 6, 3, 'advanced', 5, '2025-11-16 11:06:16', '2025-11-16 11:06:16'),
(28, 6, 4, 'intermediate', 3, '2025-11-16 11:06:16', '2025-11-16 11:06:16'),
(29, 6, 12, 'beginner', 1, '2025-11-16 11:06:16', '2025-11-16 11:06:16'),
(35, 2, 26, 'expert', 4, '2025-12-01 18:39:02', '2025-12-01 18:39:02'),
(36, 2, 52, 'expert', 6, '2025-12-01 18:39:02', '2025-12-01 18:39:02'),
(37, 2, 53, 'expert', 5, '2025-12-01 18:39:02', '2025-12-01 18:39:02'),
(38, 2, 54, 'expert', 6, '2025-12-01 18:39:02', '2025-12-01 18:39:02'),
(39, 2, 55, 'advanced', 5, '2025-12-01 18:39:02', '2025-12-01 18:39:02');

-- --------------------------------------------------------

--
-- Table structure for table `adiutor_work_schedules`
--

CREATE TABLE `adiutor_work_schedules` (
  `id` bigint UNSIGNED NOT NULL,
  `adiutor_id` bigint UNSIGNED NOT NULL,
  `day_of_week` enum('monday','tuesday','wednesday','thursday','friday','saturday','sunday') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `start_time` time NOT NULL DEFAULT '09:00:00',
  `end_time` time NOT NULL DEFAULT '17:00:00',
  `is_working_day` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `announcements`
--

CREATE TABLE `announcements` (
  `id` bigint UNSIGNED NOT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `priority` enum('low','medium','high') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'medium',
  `status` enum('active','scheduled','draft','expired') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `target_audience` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `starts_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NOT NULL,
  `updated_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `announcements`
--

INSERT INTO `announcements` (`id`, `title`, `content`, `priority`, `status`, `target_audience`, `starts_at`, `expires_at`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(2, 'Test 1', 'Active', 'high', 'expired', '', NULL, '2025-10-30 13:21:00', 1, NULL, '2025-10-30 13:20:30', '2025-10-30 13:21:05'),
(9, 'TESTING', 'ADIUTOR', 'high', 'expired', 'adiutor', NULL, '2025-10-30 14:26:00', 1, NULL, '2025-10-30 14:24:47', '2025-10-30 14:26:24'),
(10, 'TESTING', 'CLIENT', 'high', 'expired', 'client', NULL, '2025-10-30 14:27:00', 1, NULL, '2025-10-30 14:25:50', '2025-10-30 14:27:00'),
(11, 'TESTING', 'PUBLIC', 'high', 'expired', 'public', NULL, '2025-10-30 14:28:00', 1, NULL, '2025-10-30 14:26:54', '2025-10-30 14:30:49'),
(15, 'TESTING', 'TEXT FLASH', 'high', 'expired', 'all', NULL, '2025-10-30 14:48:00', 1, NULL, '2025-10-30 14:45:52', '2025-10-30 14:48:51'),
(16, 'TESTING', 'FLASH AND COLOR', 'high', 'expired', 'all', NULL, '2025-10-30 14:52:00', 1, NULL, '2025-10-30 14:50:01', '2025-10-30 14:52:15'),
(17, 'TEST', 'TEXT FLASH AND COLOR', 'high', 'expired', 'all', NULL, '2025-10-30 15:00:00', 1, NULL, '2025-10-30 14:53:12', '2025-10-30 15:01:16'),
(18, 'TESTING 1', 'MULTIPLE ANNOUNCEMENTS', 'high', 'expired', 'all', NULL, '2025-10-30 15:10:00', 1, NULL, '2025-10-30 15:03:44', '2025-10-30 15:10:10'),
(19, 'TESTING 2', 'ANNOUNCEMENTS', 'high', 'expired', 'all', NULL, '2025-10-30 15:10:00', 1, NULL, '2025-10-30 15:04:36', '2025-10-30 15:10:10');

-- --------------------------------------------------------

--
-- Table structure for table `audit_logs`
--

CREATE TABLE `audit_logs` (
  `id` bigint UNSIGNED NOT NULL,
  `auditable_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `auditable_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `action` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `event_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'model_change',
  `old_values` json DEFAULT NULL,
  `new_values` json DEFAULT NULL,
  `metadata` json DEFAULT NULL,
  `ip_address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `audit_logs`
--

INSERT INTO `audit_logs` (`id`, `auditable_type`, `auditable_id`, `user_id`, `action`, `event_type`, `old_values`, `new_values`, `metadata`, `ip_address`, `user_agent`, `created_at`) VALUES
(1, 'App\\Models\\Project', 10, 1, 'created', 'model_change', NULL, '{\"id\": 10, \"title\": \"TechStartup Corporate Website\", \"budget\": \"50000\", \"status\": \"active\", \"deadline\": \"2025-12-24 00:00:00\", \"priority\": \"high\", \"client_id\": 3, \"created_at\": \"2025-11-10 11:34:45\", \"updated_at\": \"2025-11-10 11:34:45\", \"description\": \"We need a modern, professional website for our AI startup that showcases our products, team, and company culture. The site should be responsive, fast-loading, and include a contact form, blog section, and integration with our CRM system.\", \"service_request_id\": 1}', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-10 03:34:45'),
(2, 'App\\Models\\Project', 11, 1, 'created', 'model_change', NULL, '{\"id\": 11, \"title\": \"AI Assistant Mobile App\", \"budget\": \"50000\", \"status\": \"active\", \"deadline\": \"2026-03-24 00:00:00\", \"priority\": \"high\", \"client_id\": 3, \"created_at\": \"2025-11-10 11:35:26\", \"updated_at\": \"2025-11-10 11:35:26\", \"description\": \"Cross-platform mobile app for our AI assistant service. Users should be able to interact with AI, save conversations, manage settings, and sync across devices. Integration with our existing API is required.\", \"service_request_id\": 5}', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-10 03:35:26'),
(3, 'App\\Models\\Project', 12, 1, 'created', 'model_change', NULL, '{\"id\": 12, \"title\": \"Ecommerce 2\", \"budget\": \"50000\", \"status\": \"active\", \"deadline\": null, \"priority\": \"medium\", \"client_id\": 3, \"created_at\": \"2025-11-10 11:35:53\", \"updated_at\": \"2025-11-10 11:35:53\", \"description\": \"Just make it pretty\", \"service_request_id\": 33}', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-10 03:35:53'),
(4, 'App\\Models\\Project', 13, 1, 'created', 'model_change', NULL, '{\"id\": 13, \"title\": \"Website SEO Optimization\", \"budget\": \"50000\", \"status\": \"active\", \"deadline\": \"2025-12-24 00:00:00\", \"priority\": \"medium\", \"client_id\": 3, \"created_at\": \"2025-11-10 11:36:46\", \"updated_at\": \"2025-11-10 11:36:46\", \"description\": \"Complete SEO optimization for our existing website to improve search rankings and organic traffic. Need keyword research, on-page optimization, and ongoing monitoring.\", \"service_request_id\": 9}', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-10 03:36:46'),
(5, 'App\\Models\\Project', 14, 1, 'created', 'model_change', NULL, '{\"id\": 14, \"title\": \"Ecommerce 3\", \"budget\": \"50000\", \"status\": \"active\", \"deadline\": \"2025-12-06 00:00:00\", \"priority\": \"medium\", \"client_id\": 3, \"created_at\": \"2025-11-10 11:37:12\", \"updated_at\": \"2025-11-10 11:37:12\", \"description\": \"kjvksjf\", \"service_request_id\": 34}', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-10 03:37:12'),
(6, 'App\\Models\\Project', 14, 1, 'updated', 'model_change', '{\"id\": 14, \"title\": \"Ecommerce 3\", \"budget\": \"50000.00\", \"status\": \"active\", \"deadline\": \"2025-12-05T16:00:00.000000Z\", \"priority\": \"medium\", \"client_id\": 3, \"created_at\": \"2025-11-10T03:37:12.000000Z\", \"started_at\": null, \"updated_at\": \"2025-11-10T03:37:12.000000Z\", \"attachments\": null, \"budget_type\": \"fixed\", \"description\": \"kjvksjf\", \"completed_at\": null, \"requirements\": null, \"skills_required\": null, \"service_request_id\": 34}', '{\"status\": \"completed\", \"updated_at\": \"2025-11-10 12:05:16\"}', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-10 04:05:16'),
(7, 'App\\Models\\Project', 13, 1, 'updated', 'model_change', '{\"id\": 13, \"title\": \"Website SEO Optimization\", \"budget\": \"50000.00\", \"status\": \"active\", \"deadline\": \"2025-12-23T16:00:00.000000Z\", \"priority\": \"medium\", \"client_id\": 3, \"created_at\": \"2025-11-10T03:36:46.000000Z\", \"started_at\": null, \"updated_at\": \"2025-11-10T03:36:46.000000Z\", \"attachments\": null, \"budget_type\": \"fixed\", \"description\": \"Complete SEO optimization for our existing website to improve search rankings and organic traffic. Need keyword research, on-page optimization, and ongoing monitoring.\", \"completed_at\": null, \"requirements\": null, \"skills_required\": null, \"service_request_id\": 9}', '{\"priority\": \"high\", \"updated_at\": \"2025-11-10 12:23:23\"}', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-10 04:23:23'),
(8, 'App\\Models\\Project', 13, 1, 'updated', 'model_change', '{\"id\": 13, \"title\": \"Website SEO Optimization\", \"budget\": \"50000.00\", \"status\": \"active\", \"deadline\": \"2025-12-23T16:00:00.000000Z\", \"priority\": \"high\", \"client_id\": 3, \"created_at\": \"2025-11-10T03:36:46.000000Z\", \"started_at\": null, \"updated_at\": \"2025-11-10T04:23:23.000000Z\", \"attachments\": null, \"budget_type\": \"fixed\", \"description\": \"Complete SEO optimization for our existing website to improve search rankings and organic traffic. Need keyword research, on-page optimization, and ongoing monitoring.\", \"completed_at\": null, \"requirements\": null, \"skills_required\": null, \"service_request_id\": 9}', '{\"status\": \"completed\", \"updated_at\": \"2025-11-10 12:52:25\"}', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-10 04:52:25'),
(9, 'App\\Models\\Project', 15, 1, 'created', 'model_change', NULL, '{\"id\": 15, \"title\": \"Game Mobile Application\", \"budget\": \"20000\", \"status\": \"active\", \"deadline\": \"2025-11-30 00:00:00\", \"priority\": \"medium\", \"client_id\": 32, \"created_at\": \"2025-11-12 01:51:16\", \"updated_at\": \"2025-11-12 01:51:16\", \"description\": \"Modern design\", \"service_request_id\": 37}', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-11 04:51:34'),
(10, 'App\\Models\\Project', 15, 1, 'deleted', 'model_change', '{\"id\": 15, \"title\": \"Game Mobile Application\", \"budget\": \"20000.00\", \"status\": \"active\", \"deadline\": \"2025-11-30\", \"priority\": \"medium\", \"client_id\": 32, \"created_at\": \"2025-11-12 01:51:16\", \"started_at\": null, \"updated_at\": \"2025-11-12 01:51:16\", \"attachments\": null, \"budget_type\": \"fixed\", \"description\": \"Modern design\", \"completed_at\": null, \"requirements\": null, \"skills_required\": null, \"service_request_id\": 37}', NULL, '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-11 04:54:37'),
(11, 'App\\Models\\Project', 16, 1, 'created', 'model_change', NULL, '{\"id\": 16, \"title\": \"Game Mobile Application\", \"budget\": \"500000\", \"status\": \"active\", \"deadline\": \"2025-11-30 00:00:00\", \"priority\": \"medium\", \"client_id\": 32, \"created_at\": \"2025-11-14 11:30:45\", \"updated_at\": \"2025-11-14 11:30:45\", \"description\": \"Modern design\", \"service_request_id\": 36}', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-14 03:30:47'),
(12, 'App\\Models\\Project', 17, 1, 'created', 'model_change', NULL, '{\"id\": 17, \"title\": \"Graphics Design\", \"budget\": \"350000\", \"status\": \"active\", \"deadline\": \"2025-11-20 00:00:00\", \"priority\": \"medium\", \"client_id\": 32, \"created_at\": \"2025-11-14 11:36:00\", \"updated_at\": \"2025-11-14 11:36:00\", \"description\": \"Branding Item\", \"service_request_id\": 38}', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-14 03:36:02'),
(13, 'App\\Models\\Project', 18, 1, 'created', 'model_change', NULL, '{\"id\": 18, \"title\": \"Ecommerce 3\", \"budget\": \"500000.00\", \"status\": \"active\", \"deadline\": \"2025-12-06 00:00:00\", \"priority\": \"medium\", \"client_id\": 3, \"created_at\": \"2025-11-14 12:04:27\", \"updated_at\": \"2025-11-14 12:04:27\", \"description\": \"aifqe\", \"service_request_id\": 35}', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-14 04:04:29'),
(14, 'App\\Models\\Project', 17, NULL, 'updated', 'model_change', '{\"id\": 17, \"title\": \"Graphics Design\", \"budget\": \"350000.00\", \"status\": \"active\", \"deadline\": \"2025-11-19T16:00:00.000000Z\", \"priority\": \"medium\", \"client_id\": 32, \"created_at\": \"2025-11-14T03:36:00.000000Z\", \"started_at\": null, \"updated_at\": \"2025-11-14T03:36:00.000000Z\", \"attachments\": null, \"budget_type\": \"fixed\", \"description\": \"Branding Item\", \"completed_at\": null, \"requirements\": null, \"skills_required\": null, \"service_request_id\": 38}', '{\"budget\": \"280000.00\", \"updated_at\": \"2025-11-14 12:07:39\"}', '[]', '127.0.0.1', 'Symfony', '2025-11-14 04:07:41'),
(15, 'App\\Models\\Project', 18, NULL, 'updated', 'model_change', '{\"id\": 18, \"title\": \"Ecommerce 3\", \"budget\": \"500000.00\", \"status\": \"active\", \"deadline\": \"2025-12-05T16:00:00.000000Z\", \"priority\": \"medium\", \"client_id\": 3, \"created_at\": \"2025-11-14T04:04:27.000000Z\", \"started_at\": null, \"updated_at\": \"2025-11-14T04:04:27.000000Z\", \"attachments\": null, \"budget_type\": \"fixed\", \"description\": \"aifqe\", \"completed_at\": null, \"requirements\": null, \"skills_required\": null, \"service_request_id\": 35}', '{\"budget\": \"400000.00\", \"updated_at\": \"2025-11-14 12:07:39\"}', '[]', '127.0.0.1', 'Symfony', '2025-11-14 04:07:41'),
(17, 'App\\Models\\Task', 17, 1, 'created', 'model_change', NULL, '{\"notes\": null, \"status\": \"pending\", \"taskID\": 17, \"deadline\": \"2025-11-20 00:00:00\", \"phase_id\": null, \"priority\": \"medium\", \"client_id\": 3, \"createdBy\": 1, \"taskTitle\": \"Develop Ecommerce\", \"assignedTo\": \"34\", \"created_at\": \"2025-11-16 11:17:05\", \"project_id\": \"18\", \"updated_at\": \"2025-11-16 11:17:05\", \"dateAssigned\": \"2025-11-16 11:17:05\", \"taskDescription\": \"Make a good ecommerce website\", \"allocated_budget\": \"5000\", \"use_fixed_budget\": true, \"requires_time_tracking\": false}', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-16 03:16:59'),
(18, 'App\\Models\\Task', 18, 1, 'created', 'model_change', NULL, '{\"notes\": null, \"status\": \"pending\", \"taskID\": 18, \"deadline\": \"2025-11-27 00:00:00\", \"phase_id\": null, \"priority\": \"medium\", \"client_id\": 32, \"createdBy\": 1, \"taskTitle\": \"Mobile Game Elements\", \"assignedTo\": \"34\", \"created_at\": \"2025-11-17 10:27:25\", \"project_id\": \"16\", \"updated_at\": \"2025-11-17 10:27:25\", \"dateAssigned\": \"2025-11-17 10:27:25\", \"taskDescription\": \"Create the surrounding elements for the mobile game\", \"allocated_budget\": \"5000\", \"use_fixed_budget\": true, \"requires_time_tracking\": false}', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-17 02:27:19'),
(19, 'App\\Models\\TimeEntry', 2, 34, 'created', 'model_change', NULL, '{\"id\": 2, \"task_id\": \"18\", \"adiutor_id\": 34, \"created_at\": \"2025-11-18 14:33:58\", \"project_id\": 16, \"start_time\": \"2025-11-18 14:33:58\", \"updated_at\": \"2025-11-18 14:33:58\", \"description\": null, \"hourly_rate\": 500, \"is_approved\": false}', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-18 06:33:54'),
(20, 'App\\Models\\Task', 19, 1, 'created', 'model_change', NULL, '{\"notes\": null, \"status\": \"pending\", \"taskID\": 19, \"deadline\": \"2025-11-20 00:00:00\", \"phase_id\": null, \"priority\": \"medium\", \"client_id\": 32, \"createdBy\": 1, \"taskTitle\": \"Graphics Designing\", \"assignedTo\": \"34\", \"created_at\": \"2025-11-18 14:50:06\", \"project_id\": \"17\", \"updated_at\": \"2025-11-18 14:50:06\", \"hourly_rate\": null, \"dateAssigned\": \"2025-11-18 14:50:06\", \"taskDescription\": \"Design\", \"allocated_budget\": \"2000\", \"use_fixed_budget\": false, \"requires_time_tracking\": true}', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-18 06:50:02'),
(21, 'App\\Models\\Project', 19, 1, 'created', 'model_change', NULL, '{\"id\": 19, \"title\": \"UNIMERCE – Full E-Commerce Platform Development & Optimization\", \"budget\": \"500000.00\", \"status\": \"active\", \"deadline\": \"2026-11-25 00:00:00\", \"priority\": \"medium\", \"client_id\": 18, \"created_at\": \"2025-12-01 11:20:52\", \"updated_at\": \"2025-12-01 11:20:52\", \"description\": \"I am developing an end-to-end e-commerce platform named UNIMERCE, intended to support multiple user roles (Super Admin, Sellers, and Buyers). The project involves creating a dynamic and responsive website using HTML, CSS, JavaScript, Python (Flask), and MySQL.\\r\\n\\r\\nThe system requires key modules including:\\r\\n\\r\\nUser registration, login, and role management\\r\\n\\r\\nDynamic homepage with banners, categories, subcategories, and product listings\\r\\n\\r\\nSeller dashboard with product management and analytics\\r\\n\\r\\nBuyer interface for product browsing, cart, checkout, and order tracking\\r\\n\\r\\nReal-time promotions such as “Deals of the Week” with countdown timers\\r\\n\\r\\nDatabase design and integration using SQLYog\\r\\n\\r\\nResponsive UI and improvements in layout, design, and user experience\\r\\n\\r\\nIntegration with Docker for local development (optional)\", \"service_request_id\": 40}', '[]', '172.19.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-01 03:20:52'),
(22, 'App\\Models\\Project', 20, 1, 'created', 'model_change', NULL, '{\"id\": 20, \"title\": \"Multi-Platform Study Cards System (Web + Android)\", \"budget\": \"500000.00\", \"status\": \"active\", \"deadline\": null, \"priority\": \"medium\", \"client_id\": 18, \"created_at\": \"2025-12-01 12:13:22\", \"updated_at\": \"2025-12-01 12:13:22\", \"description\": \"I am working on a Study Cards System that will be developed for both Android (Java + Firebase) and Web (Flask / Java). The project includes features such as creating study sets, viewing study cards, dynamic dashboards, account management, and syncing data between platforms.\\r\\n\\r\\nThe goals include:\\r\\n\\r\\nBuilding the web application version of the existing Android app\\r\\n\\r\\nDesigning a user-friendly interface for creating and managing study sets\\r\\n\\r\\nSetting up Firebase and MySQL/Flask integration depending on the module\\r\\n\\r\\nImplementing dynamic content loading (dashboard cards, categories, user data)\\r\\n\\r\\nEnsuring responsive layout and clean UI\\r\\n\\r\\nImproving performance and fixing existing bugs\\r\\n\\r\\nAssisting with database structure and flow diagrams\\r\\n\\r\\nThe timeline is ongoing, and I need help finalizing core features, connecting backend logic, and polishing the UI/UX.\", \"service_request_id\": 41}', '[]', '172.19.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-01 04:13:22'),
(23, 'App\\Models\\Task', 20, 1, 'created', 'model_change', NULL, '{\"notes\": \"Irure exercitationem\", \"status\": \"completed\", \"taskID\": 20, \"deadline\": null, \"phase_id\": null, \"priority\": \"high\", \"client_id\": 18, \"createdBy\": 1, \"taskTitle\": \"Aperiam nisi repelle\", \"assignedTo\": \"10\", \"created_at\": \"2025-12-01 12:18:38\", \"project_id\": \"20\", \"updated_at\": \"2025-12-01 12:18:38\", \"hourly_rate\": null, \"dateAssigned\": \"2025-12-01 12:18:38\", \"taskDescription\": \"Nam est magna quis\", \"allocated_budget\": \"500\", \"use_fixed_budget\": false, \"requires_time_tracking\": false}', '[]', '172.19.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-01 04:18:38'),
(24, 'App\\Models\\Project', 20, 1, 'updated', 'model_change', '{\"id\": 20, \"title\": \"Multi-Platform Study Cards System (Web + Android)\", \"budget\": \"500000.00\", \"status\": \"active\", \"deadline\": null, \"priority\": \"medium\", \"client_id\": 18, \"created_at\": \"2025-12-01T04:13:22.000000Z\", \"started_at\": null, \"updated_at\": \"2025-12-01T04:13:22.000000Z\", \"attachments\": null, \"budget_type\": \"fixed\", \"description\": \"I am working on a Study Cards System that will be developed for both Android (Java + Firebase) and Web (Flask / Java). The project includes features such as creating study sets, viewing study cards, dynamic dashboards, account management, and syncing data between platforms.\\r\\n\\r\\nThe goals include:\\r\\n\\r\\nBuilding the web application version of the existing Android app\\r\\n\\r\\nDesigning a user-friendly interface for creating and managing study sets\\r\\n\\r\\nSetting up Firebase and MySQL/Flask integration depending on the module\\r\\n\\r\\nImplementing dynamic content loading (dashboard cards, categories, user data)\\r\\n\\r\\nEnsuring responsive layout and clean UI\\r\\n\\r\\nImproving performance and fixing existing bugs\\r\\n\\r\\nAssisting with database structure and flow diagrams\\r\\n\\r\\nThe timeline is ongoing, and I need help finalizing core features, connecting backend logic, and polishing the UI/UX.\", \"completed_at\": null, \"requirements\": null, \"skills_required\": null, \"service_request_id\": 41}', '{\"status\": \"completed\", \"updated_at\": \"2025-12-01 12:19:49\"}', '[]', '172.19.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-01 04:19:49'),
(25, 'App\\Models\\Task', 21, 1, 'created', 'model_change', NULL, '{\"notes\": \"Rerum reiciendis cul\", \"status\": \"pending\", \"taskID\": 21, \"deadline\": \"2026-09-19 00:00:00\", \"phase_id\": null, \"priority\": \"high\", \"client_id\": 18, \"createdBy\": 1, \"taskTitle\": \"Expedita labore sit\", \"assignedTo\": \"34\", \"created_at\": \"2025-12-01 12:51:49\", \"project_id\": \"19\", \"updated_at\": \"2025-12-01 12:51:49\", \"hourly_rate\": null, \"dateAssigned\": \"2025-12-01 12:51:49\", \"taskDescription\": \"Sed fugiat voluptate\", \"allocated_budget\": \"55\", \"use_fixed_budget\": false, \"requires_time_tracking\": false}', '[]', '172.19.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-01 04:51:49'),
(26, 'App\\Models\\Task', 22, 1, 'created', 'model_change', NULL, '{\"notes\": \"Voluptatum tenetur t\", \"status\": \"completed\", \"taskID\": 22, \"deadline\": \"2025-12-23 00:00:00\", \"phase_id\": null, \"priority\": \"urgent\", \"client_id\": 18, \"createdBy\": 1, \"taskTitle\": \"Laboriosam similiqu\", \"assignedTo\": \"7\", \"created_at\": \"2025-12-01 12:53:38\", \"project_id\": \"19\", \"updated_at\": \"2025-12-01 12:53:38\", \"hourly_rate\": null, \"dateAssigned\": \"2025-12-01 12:53:38\", \"taskDescription\": \"Dolor cum reprehende\", \"allocated_budget\": \"500\", \"use_fixed_budget\": false, \"requires_time_tracking\": false}', '[]', '172.19.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-01 04:53:38'),
(27, 'App\\Models\\Project', 19, 1, 'updated', 'model_change', '{\"id\": 19, \"title\": \"UNIMERCE – Full E-Commerce Platform Development & Optimization\", \"budget\": \"500000.00\", \"status\": \"active\", \"deadline\": \"2026-11-24T16:00:00.000000Z\", \"priority\": \"medium\", \"client_id\": 18, \"created_at\": \"2025-12-01T03:20:52.000000Z\", \"started_at\": null, \"updated_at\": \"2025-12-01T03:20:52.000000Z\", \"attachments\": null, \"budget_type\": \"fixed\", \"description\": \"I am developing an end-to-end e-commerce platform named UNIMERCE, intended to support multiple user roles (Super Admin, Sellers, and Buyers). The project involves creating a dynamic and responsive website using HTML, CSS, JavaScript, Python (Flask), and MySQL.\\r\\n\\r\\nThe system requires key modules including:\\r\\n\\r\\nUser registration, login, and role management\\r\\n\\r\\nDynamic homepage with banners, categories, subcategories, and product listings\\r\\n\\r\\nSeller dashboard with product management and analytics\\r\\n\\r\\nBuyer interface for product browsing, cart, checkout, and order tracking\\r\\n\\r\\nReal-time promotions such as “Deals of the Week” with countdown timers\\r\\n\\r\\nDatabase design and integration using SQLYog\\r\\n\\r\\nResponsive UI and improvements in layout, design, and user experience\\r\\n\\r\\nIntegration with Docker for local development (optional)\", \"completed_at\": null, \"requirements\": null, \"skills_required\": null, \"service_request_id\": 40}', '{\"status\": \"completed\", \"updated_at\": \"2025-12-01 14:23:29\"}', '[]', '172.19.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-01 06:23:29'),
(28, 'App\\Models\\TimeEntry', 3, 7, 'created', 'model_change', NULL, '{\"id\": 3, \"task_id\": \"21\", \"adiutor_id\": 7, \"created_at\": \"2025-12-02 10:39:17\", \"project_id\": 19, \"start_time\": \"2025-12-02 10:39:17\", \"updated_at\": \"2025-12-02 10:39:17\", \"description\": null, \"hourly_rate\": 400, \"is_approved\": false}', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-02 02:39:17'),
(29, 'App\\Models\\TimeEntry', 3, 7, 'updated', 'model_change', '{\"id\": 3, \"notes\": null, \"amount\": null, \"is_paid\": false, \"task_id\": 21, \"end_time\": null, \"payout_id\": null, \"adiutor_id\": 7, \"created_at\": \"2025-12-02T02:39:17.000000Z\", \"project_id\": 19, \"start_time\": \"2025-12-02T02:39:17.000000Z\", \"updated_at\": \"2025-12-02T02:39:17.000000Z\", \"approved_at\": null, \"approved_by\": null, \"description\": null, \"hourly_rate\": \"400.00\", \"is_approved\": false, \"is_billable\": 1, \"duration_minutes\": null, \"calculated_amount\": null}', '{\"end_time\": \"2025-12-02 10:39:36\", \"updated_at\": \"2025-12-02 10:39:36\", \"duration_minutes\": 0.3267467333333334, \"calculated_amount\": 2.1783115555555557}', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-02 02:39:36'),
(30, 'App\\Models\\TimeEntry', 3, 7, 'deleted', 'model_change', '{\"id\": 3, \"notes\": null, \"amount\": null, \"is_paid\": 0, \"task_id\": 21, \"end_time\": \"2025-12-02 10:39:36\", \"payout_id\": null, \"adiutor_id\": 7, \"created_at\": \"2025-12-02 10:39:17\", \"project_id\": 19, \"start_time\": \"2025-12-02 10:39:17\", \"updated_at\": \"2025-12-02 10:39:36\", \"approved_at\": null, \"approved_by\": null, \"description\": null, \"hourly_rate\": \"400.00\", \"is_approved\": 0, \"is_billable\": 1, \"duration_minutes\": 0, \"calculated_amount\": \"2.18\"}', NULL, '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-02 02:39:50'),
(31, 'App\\Models\\Task', 23, 1, 'created', 'model_change', NULL, '{\"notes\": null, \"status\": \"pending\", \"taskID\": 23, \"deadline\": null, \"phase_id\": null, \"priority\": \"medium\", \"client_id\": 3, \"createdBy\": 1, \"taskTitle\": \"Task 1\", \"assignedTo\": \"8\", \"created_at\": \"2025-12-02 11:07:19\", \"project_id\": \"18\", \"updated_at\": \"2025-12-02 11:07:19\", \"hourly_rate\": null, \"dateAssigned\": \"2025-12-02 11:07:19\", \"taskDescription\": \"Task description\", \"allocated_budget\": \"500\", \"use_fixed_budget\": false, \"requires_time_tracking\": false}', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-02 03:07:19'),
(32, 'App\\Models\\Project', 21, 1, 'created', 'model_change', NULL, '{\"id\": 21, \"title\": \"NovaSync Vendor Intelligence Dashboard\", \"budget\": \"500000.00\", \"status\": \"active\", \"deadline\": null, \"priority\": \"medium\", \"client_id\": 3, \"created_at\": \"2025-12-05 17:17:57\", \"updated_at\": \"2025-12-05 17:17:57\", \"description\": \"This project is a web-based analytics and automation dashboard that plugs into an existing multi-vendor e-commerce platform (like Nova) to give vendors real-time insights into product performance, operational health, and customer behavior. The goal is to centralize metrics from orders, inventory, refunds, delivery SLAs, and marketing events into a single, vendor-facing control panel with role-based access and granular permissions.\\r\\n\\r\\nThe system will include a Laravel-based backend API, a modern SPA frontend (React or Vue), and integrations with third-party services such as payment gateways, shipment tracking APIs, and email providers. Key features include configurable KPIs, anomaly alerts (e.g., sudden spike in returns for a specific SKU), seller tier scoring based on green-certified products, and a lightweight recommendation engine that suggests catalog or pricing optimizations.\", \"service_request_id\": 42}', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-05 09:17:57'),
(33, 'App\\Models\\Project', 22, 1, 'created', 'model_change', NULL, '{\"id\": 22, \"title\": \"EdgeRelay - Decentralized Content Delivery Network for Small-Scale Creators\", \"budget\": \"250000.00\", \"status\": \"active\", \"deadline\": null, \"priority\": \"medium\", \"client_id\": 3, \"created_at\": \"2025-12-05 17:47:39\", \"updated_at\": \"2025-12-05 17:47:39\", \"description\": \"Building a peer-assisted CDN platform that allows independent content creators, educators, and small media publishers to distribute video, audio, and large files without relying on expensive enterprise CDN services. The system uses a hybrid model: origin servers for reliability plus voluntary edge nodes contributed by supporters who earn micro-rewards (tokens or credits) for bandwidth sharing.\\r\\n\\r\\nThe backend needs a node orchestration service (Golang or Rust preferred for performance), a content routing algorithm that selects optimal peers based on geographic proximity and availability, and a blockchain-anchored ledger for transparent bandwidth accounting. The admin panel (Python/Django or Node.js) will handle creator accounts, content uploads, usage analytics, payout calculations, and node health monitoring.\\r\\n\\r\\nKey technical challenges: ensuring content integrity via cryptographic checksums, implementing adaptive bitrate streaming for video, handling node churn gracefully, and building a lightweight desktop client for edge participants that runs quietly in the background without hogging resources.\", \"service_request_id\": 43}', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-05 09:47:39'),
(34, 'App\\Models\\Project', 23, 1, 'created', 'model_change', NULL, '{\"id\": 23, \"title\": \"QuantumShield - Zero-Trust Security Orchestration Platform\", \"budget\": \"400000.00\", \"status\": \"active\", \"deadline\": null, \"priority\": \"medium\", \"client_id\": 3, \"created_at\": \"2025-12-05 19:25:13\", \"updated_at\": \"2025-12-05 19:25:13\", \"description\": \"Developing an enterprise-grade zero-trust security orchestration platform that dynamically manages access policies across cloud infrastructure, SaaS applications, and on-premises systems using real-time risk scoring and behavioral analytics. The system continuously evaluates device posture, user identity signals, network context, and anomalous activity patterns to grant or revoke access without traditional perimeter-based security models.\\r\\n\\r\\nThe backend (Node.js or Go microservices) ingests telemetry from endpoints, identity providers (Okta, Azure AD), SIEM systems, and network sensors. A rules engine correlates signals to compute real-time risk scores; when thresholds are exceeded, the platform automatically triggers conditional access policies (MFA challenges, session termination, geo-fencing). The frontend provides security teams with a real-time threat dashboard, policy builder UI with drag-and-drop conditions, and detailed audit trails for compliance reporting (SOC 2, ISO 27001, HIPAA).\\r\\n\\r\\nTechnical components include a message queue (Kafka/RabbitMQ) for high-throughput event streaming, a time-series database (InfluxDB or TimescaleDB) for performance metrics, and integration adapters for AWS IAM, Okta, Slack, PagerDuty, and Splunk.\", \"service_request_id\": 44}', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-05 11:25:13'),
(35, 'App\\Models\\Project', 24, 1, 'created', 'model_change', NULL, '{\"id\": 24, \"title\": \"SynthAI - Autonomous Code Review and Technical Debt Analyzer\", \"budget\": \"400000.00\", \"status\": \"active\", \"deadline\": null, \"priority\": \"medium\", \"client_id\": 3, \"created_at\": \"2025-12-05 19:27:36\", \"updated_at\": \"2025-12-05 19:27:36\", \"description\": \"Building an intelligent code review automation platform that integrates directly into Git workflows (GitHub, GitLab, Bitbucket) to analyze pull requests for code quality, security vulnerabilities, performance bottlenecks, and technical debt accumulation. Unlike static linters, SynthAI uses multi-modal AI models to understand architectural patterns, suggest refactoring strategies aligned with team conventions, and predict which modules are becoming maintenance liabilities.\\r\\n\\r\\nThe backend (Python FastAPI + PostgreSQL) processes incoming webhook events from version control platforms, clones repositories, performs deep code analysis using custom AST parsers and LLVM-based performance profiling, and stores results in a queryable database. The platform learns from team feedback—when developers accept or reject suggestions, the models retrain to better match the team\'s coding style and priorities. A React/TypeScript frontend displays interactive diff annotations, debt trend charts, and team velocity metrics correlated with code quality scores.\\r\\n\\r\\nIntegration points include Slack for notifications, Jira for auto-creating technical debt tickets, and SonarQube/CodeClimate for complementary metrics. The system supports multiple languages (Python, JavaScript, Go, Java, Rust) with pluggable analyzers and custom rule definitions via YAML configuration.\", \"service_request_id\": 45}', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-05 11:27:36'),
(36, 'App\\Models\\Task', 24, 1, 'created', 'model_change', NULL, '{\"notes\": null, \"status\": \"pending\", \"taskID\": 24, \"deadline\": null, \"phase_id\": null, \"priority\": \"medium\", \"client_id\": 3, \"createdBy\": 1, \"taskTitle\": \"Project Setup & Infrastructure\", \"assignedTo\": null, \"created_at\": \"2025-12-05 19:52:07\", \"project_id\": \"24\", \"updated_at\": \"2025-12-05 19:52:07\", \"hourly_rate\": null, \"dateAssigned\": \"2025-12-05 19:52:07\", \"taskDescription\": \"Set up core FastAPI application, database schema, authentication, and API scaffolding. Establish project structure, environment configuration, and deployment pipeline basics.\", \"allocated_budget\": null, \"use_fixed_budget\": false, \"requires_time_tracking\": false}', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-05 11:52:07'),
(37, 'App\\Models\\Task', 24, 1, 'deleted', 'model_change', '{\"notes\": null, \"status\": \"pending\", \"taskID\": 24, \"deadline\": null, \"phase_id\": null, \"priority\": \"medium\", \"client_id\": 3, \"createdBy\": 1, \"taskTitle\": \"Project Setup & Infrastructure\", \"assignedTo\": null, \"created_at\": \"2025-12-05 19:52:07\", \"project_id\": 24, \"sort_order\": 0, \"updated_at\": \"2025-12-05 19:52:07\", \"actual_cost\": null, \"completedAt\": null, \"hourly_rate\": null, \"dateAssigned\": \"2025-12-05 19:52:07\", \"is_scheduled\": 0, \"estimated_hours\": null, \"taskDescription\": \"Set up core FastAPI application, database schema, authentication, and API scaffolding. Establish project structure, environment configuration, and deployment pipeline basics.\", \"allocated_budget\": null, \"completion_notes\": null, \"use_fixed_budget\": 0, \"service_request_id\": null, \"calculated_earnings\": \"0.00\", \"progress_percentage\": 0, \"total_hours_tracked\": \"0.00\", \"requires_time_tracking\": 0}', NULL, '[]', '127.0.0.1', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Mobile Safari/537.36', '2025-12-05 12:52:05'),
(38, 'App\\Models\\Task', 23, 1, 'deleted', 'model_change', '{\"notes\": null, \"status\": \"pending\", \"taskID\": 23, \"deadline\": null, \"phase_id\": null, \"priority\": \"medium\", \"client_id\": 3, \"createdBy\": 1, \"taskTitle\": \"Task 1\", \"assignedTo\": 8, \"created_at\": \"2025-12-02 11:07:19\", \"project_id\": 18, \"sort_order\": 2, \"updated_at\": \"2025-12-02 11:07:19\", \"actual_cost\": null, \"completedAt\": null, \"hourly_rate\": null, \"dateAssigned\": \"2025-12-02 11:07:19\", \"is_scheduled\": 0, \"estimated_hours\": null, \"taskDescription\": \"Task description\", \"allocated_budget\": \"500.00\", \"completion_notes\": null, \"use_fixed_budget\": 0, \"service_request_id\": null, \"calculated_earnings\": \"0.00\", \"progress_percentage\": 0, \"total_hours_tracked\": \"0.00\", \"requires_time_tracking\": 0}', NULL, '[]', '172.19.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-05 14:03:18'),
(39, 'App\\Models\\Task', 25, 1, 'created', 'model_change', NULL, '{\"notes\": null, \"status\": \"pending\", \"taskID\": 25, \"deadline\": null, \"phase_id\": null, \"priority\": \"medium\", \"client_id\": 3, \"createdBy\": 1, \"taskTitle\": \"Task 1: Backend Infrastructure & API Foundation\", \"assignedTo\": null, \"created_at\": \"2025-12-06 05:38:04\", \"project_id\": \"24\", \"updated_at\": \"2025-12-06 05:38:04\", \"hourly_rate\": null, \"dateAssigned\": \"2025-12-06 05:38:04\", \"taskDescription\": \"Set up core FastAPI application, database schema, authentication, and API scaffolding. Establish project structure, environment configuration, and deployment pipeline basics\", \"allocated_budget\": null, \"use_fixed_budget\": false, \"requires_time_tracking\": false}', '[]', '172.19.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-05 21:38:04'),
(40, 'App\\Models\\Task', 25, 1, 'updated', 'model_change', '{\"notes\": null, \"status\": \"pending\", \"taskID\": 25, \"deadline\": null, \"phase_id\": null, \"priority\": \"medium\", \"client_id\": 3, \"createdBy\": 1, \"taskTitle\": \"Task 1: Backend Infrastructure & API Foundation\", \"assignedTo\": null, \"created_at\": \"2025-12-05T21:38:04.000000Z\", \"project_id\": 24, \"sort_order\": 0, \"updated_at\": \"2025-12-05T21:38:04.000000Z\", \"actual_cost\": null, \"completedAt\": null, \"hourly_rate\": null, \"dateAssigned\": \"2025-12-05T21:38:04.000000Z\", \"is_scheduled\": 0, \"estimated_hours\": null, \"taskDescription\": \"Set up core FastAPI application, database schema, authentication, and API scaffolding. Establish project structure, environment configuration, and deployment pipeline basics\", \"allocated_budget\": null, \"completion_notes\": null, \"use_fixed_budget\": false, \"service_request_id\": null, \"calculated_earnings\": \"0.00\", \"progress_percentage\": 0, \"total_hours_tracked\": \"0.00\", \"requires_time_tracking\": false}', '{\"updated_at\": \"2025-12-06 05:38:32\", \"progress_percentage\": 10}', '[]', '172.19.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-05 21:38:32'),
(41, 'App\\Models\\Task', 25, 1, 'updated', 'model_change', '{\"notes\": null, \"status\": \"pending\", \"taskID\": 25, \"deadline\": null, \"phase_id\": null, \"priority\": \"medium\", \"client_id\": 3, \"createdBy\": 1, \"taskTitle\": \"Task 1: Backend Infrastructure & API Foundation\", \"assignedTo\": null, \"created_at\": \"2025-12-05T21:38:04.000000Z\", \"project_id\": 24, \"sort_order\": 0, \"updated_at\": \"2025-12-05T21:38:32.000000Z\", \"actual_cost\": null, \"completedAt\": null, \"hourly_rate\": null, \"dateAssigned\": \"2025-12-05T21:38:04.000000Z\", \"is_scheduled\": 0, \"estimated_hours\": null, \"taskDescription\": \"Set up core FastAPI application, database schema, authentication, and API scaffolding. Establish project structure, environment configuration, and deployment pipeline basics\", \"allocated_budget\": null, \"completion_notes\": null, \"use_fixed_budget\": false, \"service_request_id\": null, \"calculated_earnings\": \"0.00\", \"progress_percentage\": 10, \"total_hours_tracked\": \"0.00\", \"requires_time_tracking\": false}', '{\"updated_at\": \"2025-12-06 05:41:09\", \"progress_percentage\": 20}', '[]', '172.19.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-05 21:41:09'),
(42, 'App\\Models\\Task', 25, 1, 'updated', 'model_change', '{\"notes\": null, \"status\": \"pending\", \"taskID\": 25, \"deadline\": null, \"phase_id\": null, \"priority\": \"medium\", \"client_id\": 3, \"createdBy\": 1, \"taskTitle\": \"Task 1: Backend Infrastructure & API Foundation\", \"assignedTo\": null, \"created_at\": \"2025-12-05T21:38:04.000000Z\", \"project_id\": 24, \"sort_order\": 0, \"updated_at\": \"2025-12-05T21:41:09.000000Z\", \"actual_cost\": null, \"completedAt\": null, \"hourly_rate\": null, \"dateAssigned\": \"2025-12-05T21:38:04.000000Z\", \"is_scheduled\": 0, \"estimated_hours\": null, \"taskDescription\": \"Set up core FastAPI application, database schema, authentication, and API scaffolding. Establish project structure, environment configuration, and deployment pipeline basics\", \"allocated_budget\": null, \"completion_notes\": null, \"use_fixed_budget\": false, \"service_request_id\": null, \"calculated_earnings\": \"0.00\", \"progress_percentage\": 20, \"total_hours_tracked\": \"0.00\", \"requires_time_tracking\": false}', '{\"updated_at\": \"2025-12-06 05:42:56\", \"progress_percentage\": 30}', '[]', '172.19.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-05 21:42:56'),
(43, 'App\\Models\\Task', 25, 1, 'updated', 'model_change', '{\"notes\": null, \"status\": \"pending\", \"taskID\": 25, \"deadline\": null, \"phase_id\": null, \"priority\": \"medium\", \"client_id\": 3, \"createdBy\": 1, \"taskTitle\": \"Task 1: Backend Infrastructure & API Foundation\", \"assignedTo\": null, \"created_at\": \"2025-12-05T21:38:04.000000Z\", \"project_id\": 24, \"sort_order\": 0, \"updated_at\": \"2025-12-05T21:42:56.000000Z\", \"actual_cost\": null, \"completedAt\": null, \"hourly_rate\": null, \"dateAssigned\": \"2025-12-05T21:38:04.000000Z\", \"is_scheduled\": 0, \"estimated_hours\": null, \"taskDescription\": \"Set up core FastAPI application, database schema, authentication, and API scaffolding. Establish project structure, environment configuration, and deployment pipeline basics\", \"allocated_budget\": null, \"completion_notes\": null, \"use_fixed_budget\": false, \"service_request_id\": null, \"calculated_earnings\": \"0.00\", \"progress_percentage\": 30, \"total_hours_tracked\": \"0.00\", \"requires_time_tracking\": false}', '{\"updated_at\": \"2025-12-06 05:42:59\", \"progress_percentage\": 40}', '[]', '172.19.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-05 21:42:59'),
(44, 'App\\Models\\Task', 25, 1, 'updated', 'model_change', '{\"notes\": null, \"status\": \"pending\", \"taskID\": 25, \"deadline\": null, \"phase_id\": null, \"priority\": \"medium\", \"client_id\": 3, \"createdBy\": 1, \"taskTitle\": \"Task 1: Backend Infrastructure & API Foundation\", \"assignedTo\": null, \"created_at\": \"2025-12-05T21:38:04.000000Z\", \"project_id\": 24, \"sort_order\": 0, \"updated_at\": \"2025-12-05T21:42:59.000000Z\", \"actual_cost\": null, \"completedAt\": null, \"hourly_rate\": null, \"dateAssigned\": \"2025-12-05T21:38:04.000000Z\", \"is_scheduled\": 0, \"estimated_hours\": null, \"taskDescription\": \"Set up core FastAPI application, database schema, authentication, and API scaffolding. Establish project structure, environment configuration, and deployment pipeline basics\", \"allocated_budget\": null, \"completion_notes\": null, \"use_fixed_budget\": false, \"service_request_id\": null, \"calculated_earnings\": \"0.00\", \"progress_percentage\": 40, \"total_hours_tracked\": \"0.00\", \"requires_time_tracking\": false}', '{\"updated_at\": \"2025-12-06 05:43:01\", \"progress_percentage\": 50}', '[]', '172.19.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-05 21:43:01'),
(45, 'App\\Models\\Task', 25, 1, 'updated', 'model_change', '{\"notes\": null, \"status\": \"pending\", \"taskID\": 25, \"deadline\": null, \"phase_id\": null, \"priority\": \"medium\", \"client_id\": 3, \"createdBy\": 1, \"taskTitle\": \"Task 1: Backend Infrastructure & API Foundation\", \"assignedTo\": null, \"created_at\": \"2025-12-05T21:38:04.000000Z\", \"project_id\": 24, \"sort_order\": 0, \"updated_at\": \"2025-12-05T21:43:01.000000Z\", \"actual_cost\": null, \"completedAt\": null, \"hourly_rate\": null, \"dateAssigned\": \"2025-12-05T21:38:04.000000Z\", \"is_scheduled\": 0, \"estimated_hours\": null, \"taskDescription\": \"Set up core FastAPI application, database schema, authentication, and API scaffolding. Establish project structure, environment configuration, and deployment pipeline basics\", \"allocated_budget\": null, \"completion_notes\": null, \"use_fixed_budget\": false, \"service_request_id\": null, \"calculated_earnings\": \"0.00\", \"progress_percentage\": 50, \"total_hours_tracked\": \"0.00\", \"requires_time_tracking\": false}', '{\"updated_at\": \"2025-12-06 05:43:03\", \"progress_percentage\": 60}', '[]', '172.19.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-05 21:43:03'),
(46, 'App\\Models\\Task', 25, 1, 'updated', 'model_change', '{\"notes\": null, \"status\": \"pending\", \"taskID\": 25, \"deadline\": null, \"phase_id\": null, \"priority\": \"medium\", \"client_id\": 3, \"createdBy\": 1, \"taskTitle\": \"Task 1: Backend Infrastructure & API Foundation\", \"assignedTo\": null, \"created_at\": \"2025-12-05T21:38:04.000000Z\", \"project_id\": 24, \"sort_order\": 0, \"updated_at\": \"2025-12-05T21:43:03.000000Z\", \"actual_cost\": null, \"completedAt\": null, \"hourly_rate\": null, \"dateAssigned\": \"2025-12-05T21:38:04.000000Z\", \"is_scheduled\": 0, \"estimated_hours\": null, \"taskDescription\": \"Set up core FastAPI application, database schema, authentication, and API scaffolding. Establish project structure, environment configuration, and deployment pipeline basics\", \"allocated_budget\": null, \"completion_notes\": null, \"use_fixed_budget\": false, \"service_request_id\": null, \"calculated_earnings\": \"0.00\", \"progress_percentage\": 60, \"total_hours_tracked\": \"0.00\", \"requires_time_tracking\": false}', '{\"updated_at\": \"2025-12-06 05:43:07\", \"progress_percentage\": 70}', '[]', '172.19.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-05 21:43:07'),
(47, 'App\\Models\\Task', 25, 1, 'updated', 'model_change', '{\"notes\": null, \"status\": \"pending\", \"taskID\": 25, \"deadline\": null, \"phase_id\": null, \"priority\": \"medium\", \"client_id\": 3, \"createdBy\": 1, \"taskTitle\": \"Task 1: Backend Infrastructure & API Foundation\", \"assignedTo\": null, \"created_at\": \"2025-12-05T21:38:04.000000Z\", \"project_id\": 24, \"sort_order\": 0, \"updated_at\": \"2025-12-05T21:43:07.000000Z\", \"actual_cost\": null, \"completedAt\": null, \"hourly_rate\": null, \"dateAssigned\": \"2025-12-05T21:38:04.000000Z\", \"is_scheduled\": 0, \"estimated_hours\": null, \"taskDescription\": \"Set up core FastAPI application, database schema, authentication, and API scaffolding. Establish project structure, environment configuration, and deployment pipeline basics\", \"allocated_budget\": null, \"completion_notes\": null, \"use_fixed_budget\": false, \"service_request_id\": null, \"calculated_earnings\": \"0.00\", \"progress_percentage\": 70, \"total_hours_tracked\": \"0.00\", \"requires_time_tracking\": false}', '{\"updated_at\": \"2025-12-06 05:43:11\", \"progress_percentage\": 67}', '[]', '172.19.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-05 21:43:11'),
(48, 'App\\Models\\Task', 25, 1, 'updated', 'model_change', '{\"notes\": null, \"status\": \"pending\", \"taskID\": 25, \"deadline\": null, \"phase_id\": null, \"priority\": \"medium\", \"client_id\": 3, \"createdBy\": 1, \"taskTitle\": \"Task 1: Backend Infrastructure & API Foundation\", \"assignedTo\": null, \"created_at\": \"2025-12-05T21:38:04.000000Z\", \"project_id\": 24, \"sort_order\": 0, \"updated_at\": \"2025-12-05T21:43:11.000000Z\", \"actual_cost\": null, \"completedAt\": null, \"hourly_rate\": null, \"dateAssigned\": \"2025-12-05T21:38:04.000000Z\", \"is_scheduled\": 0, \"estimated_hours\": null, \"taskDescription\": \"Set up core FastAPI application, database schema, authentication, and API scaffolding. Establish project structure, environment configuration, and deployment pipeline basics\", \"allocated_budget\": null, \"completion_notes\": null, \"use_fixed_budget\": false, \"service_request_id\": null, \"calculated_earnings\": \"0.00\", \"progress_percentage\": 67, \"total_hours_tracked\": \"0.00\", \"requires_time_tracking\": false}', '{\"updated_at\": \"2025-12-06 05:43:22\", \"progress_percentage\": 63}', '[]', '172.19.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-05 21:43:22'),
(49, 'App\\Models\\Task', 25, 1, 'updated', 'model_change', '{\"notes\": null, \"status\": \"pending\", \"taskID\": 25, \"deadline\": null, \"phase_id\": null, \"priority\": \"medium\", \"client_id\": 3, \"createdBy\": 1, \"taskTitle\": \"Task 1: Backend Infrastructure & API Foundation\", \"assignedTo\": null, \"created_at\": \"2025-12-05T21:38:04.000000Z\", \"project_id\": 24, \"sort_order\": 0, \"updated_at\": \"2025-12-05T21:43:22.000000Z\", \"actual_cost\": null, \"completedAt\": null, \"hourly_rate\": null, \"dateAssigned\": \"2025-12-05T21:38:04.000000Z\", \"is_scheduled\": 0, \"estimated_hours\": null, \"taskDescription\": \"Set up core FastAPI application, database schema, authentication, and API scaffolding. Establish project structure, environment configuration, and deployment pipeline basics\", \"allocated_budget\": null, \"completion_notes\": null, \"use_fixed_budget\": false, \"service_request_id\": null, \"calculated_earnings\": \"0.00\", \"progress_percentage\": 63, \"total_hours_tracked\": \"0.00\", \"requires_time_tracking\": false}', '{\"updated_at\": \"2025-12-06 05:43:44\", \"progress_percentage\": 56}', '[]', '172.19.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-05 21:43:44');
INSERT INTO `audit_logs` (`id`, `auditable_type`, `auditable_id`, `user_id`, `action`, `event_type`, `old_values`, `new_values`, `metadata`, `ip_address`, `user_agent`, `created_at`) VALUES
(50, 'App\\Models\\Task', 25, 1, 'updated', 'model_change', '{\"notes\": null, \"status\": \"pending\", \"taskID\": 25, \"deadline\": null, \"phase_id\": null, \"priority\": \"medium\", \"client_id\": 3, \"createdBy\": 1, \"taskTitle\": \"Task 1: Backend Infrastructure & API Foundation\", \"assignedTo\": null, \"created_at\": \"2025-12-05T21:38:04.000000Z\", \"project_id\": 24, \"sort_order\": 0, \"updated_at\": \"2025-12-05T21:43:44.000000Z\", \"actual_cost\": null, \"completedAt\": null, \"hourly_rate\": null, \"dateAssigned\": \"2025-12-05T21:38:04.000000Z\", \"is_scheduled\": 0, \"estimated_hours\": null, \"taskDescription\": \"Set up core FastAPI application, database schema, authentication, and API scaffolding. Establish project structure, environment configuration, and deployment pipeline basics\", \"allocated_budget\": null, \"completion_notes\": null, \"use_fixed_budget\": false, \"service_request_id\": null, \"calculated_earnings\": \"0.00\", \"progress_percentage\": 56, \"total_hours_tracked\": \"0.00\", \"requires_time_tracking\": false}', '{\"updated_at\": \"2025-12-06 05:47:43\", \"progress_percentage\": 50}', '[]', '172.19.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-05 21:47:43'),
(51, 'App\\Models\\Task', 25, 1, 'updated', 'model_change', '{\"notes\": null, \"status\": \"pending\", \"taskID\": 25, \"deadline\": null, \"phase_id\": null, \"priority\": \"medium\", \"client_id\": 3, \"createdBy\": 1, \"taskTitle\": \"Task 1: Backend Infrastructure & API Foundation\", \"assignedTo\": null, \"created_at\": \"2025-12-05T21:38:04.000000Z\", \"project_id\": 24, \"sort_order\": 0, \"updated_at\": \"2025-12-05T21:47:43.000000Z\", \"actual_cost\": null, \"completedAt\": null, \"hourly_rate\": null, \"dateAssigned\": \"2025-12-05T21:38:04.000000Z\", \"is_scheduled\": 0, \"estimated_hours\": null, \"taskDescription\": \"Set up core FastAPI application, database schema, authentication, and API scaffolding. Establish project structure, environment configuration, and deployment pipeline basics\", \"allocated_budget\": null, \"completion_notes\": null, \"use_fixed_budget\": false, \"service_request_id\": null, \"calculated_earnings\": \"0.00\", \"progress_percentage\": 50, \"total_hours_tracked\": \"0.00\", \"requires_time_tracking\": false}', '{\"updated_at\": \"2025-12-06 05:47:45\", \"progress_percentage\": 45}', '[]', '172.19.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-05 21:47:45'),
(52, 'App\\Models\\Task', 25, 1, 'updated', 'model_change', '{\"notes\": null, \"status\": \"pending\", \"taskID\": 25, \"deadline\": null, \"phase_id\": null, \"priority\": \"medium\", \"client_id\": 3, \"createdBy\": 1, \"taskTitle\": \"Task 1: Backend Infrastructure & API Foundation\", \"assignedTo\": null, \"created_at\": \"2025-12-05T21:38:04.000000Z\", \"project_id\": 24, \"sort_order\": 0, \"updated_at\": \"2025-12-05T21:47:45.000000Z\", \"actual_cost\": null, \"completedAt\": null, \"hourly_rate\": null, \"dateAssigned\": \"2025-12-05T21:38:04.000000Z\", \"is_scheduled\": 0, \"estimated_hours\": null, \"taskDescription\": \"Set up core FastAPI application, database schema, authentication, and API scaffolding. Establish project structure, environment configuration, and deployment pipeline basics\", \"allocated_budget\": null, \"completion_notes\": null, \"use_fixed_budget\": false, \"service_request_id\": null, \"calculated_earnings\": \"0.00\", \"progress_percentage\": 45, \"total_hours_tracked\": \"0.00\", \"requires_time_tracking\": false}', '{\"updated_at\": \"2025-12-06 05:47:54\", \"progress_percentage\": 50}', '[]', '172.19.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-05 21:47:54'),
(53, 'App\\Models\\Task', 25, 1, 'updated', 'model_change', '{\"notes\": null, \"status\": \"pending\", \"taskID\": 25, \"deadline\": null, \"phase_id\": null, \"priority\": \"medium\", \"client_id\": 3, \"createdBy\": 1, \"taskTitle\": \"Task 1: Backend Infrastructure & API Foundation\", \"assignedTo\": null, \"created_at\": \"2025-12-05T21:38:04.000000Z\", \"project_id\": 24, \"sort_order\": 0, \"updated_at\": \"2025-12-05T21:47:54.000000Z\", \"actual_cost\": null, \"completedAt\": null, \"hourly_rate\": null, \"dateAssigned\": \"2025-12-05T21:38:04.000000Z\", \"is_scheduled\": 0, \"estimated_hours\": null, \"taskDescription\": \"Set up core FastAPI application, database schema, authentication, and API scaffolding. Establish project structure, environment configuration, and deployment pipeline basics\", \"allocated_budget\": null, \"completion_notes\": null, \"use_fixed_budget\": false, \"service_request_id\": null, \"calculated_earnings\": \"0.00\", \"progress_percentage\": 50, \"total_hours_tracked\": \"0.00\", \"requires_time_tracking\": false}', '{\"updated_at\": \"2025-12-06 05:47:55\", \"progress_percentage\": 56}', '[]', '172.19.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-05 21:47:55'),
(54, 'App\\Models\\Task', 25, 1, 'updated', 'model_change', '{\"notes\": null, \"status\": \"pending\", \"taskID\": 25, \"deadline\": null, \"phase_id\": null, \"priority\": \"medium\", \"client_id\": 3, \"createdBy\": 1, \"taskTitle\": \"Task 1: Backend Infrastructure & API Foundation\", \"assignedTo\": null, \"created_at\": \"2025-12-05T21:38:04.000000Z\", \"project_id\": 24, \"sort_order\": 0, \"updated_at\": \"2025-12-05T21:47:55.000000Z\", \"actual_cost\": null, \"completedAt\": null, \"hourly_rate\": null, \"dateAssigned\": \"2025-12-05T21:38:04.000000Z\", \"is_scheduled\": 0, \"estimated_hours\": null, \"taskDescription\": \"Set up core FastAPI application, database schema, authentication, and API scaffolding. Establish project structure, environment configuration, and deployment pipeline basics\", \"allocated_budget\": null, \"completion_notes\": null, \"use_fixed_budget\": false, \"service_request_id\": null, \"calculated_earnings\": \"0.00\", \"progress_percentage\": 56, \"total_hours_tracked\": \"0.00\", \"requires_time_tracking\": false}', '{\"status\": \"in_progress\", \"assignedTo\": \"7\", \"updated_at\": \"2025-12-06 06:56:51\", \"dateAssigned\": \"2025-12-06 06:56:51\"}', '[]', '172.19.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-05 22:56:51'),
(55, 'App\\Models\\TimeEntry', 4, 7, 'created', 'model_change', NULL, '{\"id\": 4, \"task_id\": \"25\", \"adiutor_id\": 7, \"created_at\": \"2025-12-06 07:28:28\", \"project_id\": 24, \"start_time\": \"2025-12-06 07:28:28\", \"updated_at\": \"2025-12-06 07:28:28\", \"description\": null, \"hourly_rate\": 500, \"is_approved\": false}', '[]', '172.19.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-05 23:28:28'),
(56, 'App\\Models\\Task', 26, 1, 'created', 'model_change', NULL, '{\"notes\": null, \"status\": \"pending\", \"taskID\": 26, \"deadline\": \"2025-12-15 00:00:00\", \"phase_id\": null, \"priority\": \"medium\", \"client_id\": 3, \"createdBy\": 1, \"taskTitle\": \"GitHub Integration & Webhook Handler\", \"assignedTo\": \"8\", \"created_at\": \"2025-12-06 07:33:32\", \"project_id\": \"24\", \"updated_at\": \"2025-12-06 07:33:32\", \"hourly_rate\": null, \"dateAssigned\": \"2025-12-06 07:33:32\", \"taskDescription\": \"Build webhook receiver to handle GitHub PR events, implement secure webhook validation, and establish two-way communication with GitHub API.\", \"allocated_budget\": \"5000\", \"use_fixed_budget\": false, \"requires_time_tracking\": false}', '[]', '172.19.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-05 23:33:32'),
(57, 'App\\Models\\Task', 26, 8, 'updated', 'model_change', '{\"notes\": null, \"status\": \"pending\", \"taskID\": 26, \"deadline\": \"2025-12-14T16:00:00.000000Z\", \"phase_id\": null, \"priority\": \"medium\", \"client_id\": 3, \"createdBy\": 1, \"taskTitle\": \"GitHub Integration & Webhook Handler\", \"assignedTo\": 8, \"created_at\": \"2025-12-05T23:33:32.000000Z\", \"project_id\": 24, \"sort_order\": 0, \"updated_at\": \"2025-12-05T23:33:32.000000Z\", \"actual_cost\": null, \"completedAt\": null, \"hourly_rate\": null, \"dateAssigned\": \"2025-12-05T23:33:32.000000Z\", \"is_scheduled\": 0, \"estimated_hours\": null, \"taskDescription\": \"Build webhook receiver to handle GitHub PR events, implement secure webhook validation, and establish two-way communication with GitHub API.\", \"allocated_budget\": \"5000.00\", \"completion_notes\": null, \"use_fixed_budget\": false, \"service_request_id\": null, \"calculated_earnings\": \"0.00\", \"progress_percentage\": 0, \"total_hours_tracked\": \"0.00\", \"requires_time_tracking\": false}', '{\"updated_at\": \"2025-12-06 07:34:30\", \"progress_percentage\": 10}', '[]', '172.19.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-05 23:34:30'),
(58, 'App\\Models\\Task', 26, 8, 'updated', 'model_change', '{\"notes\": null, \"status\": \"pending\", \"taskID\": 26, \"deadline\": \"2025-12-14T16:00:00.000000Z\", \"phase_id\": null, \"priority\": \"medium\", \"client_id\": 3, \"createdBy\": 1, \"taskTitle\": \"GitHub Integration & Webhook Handler\", \"assignedTo\": 8, \"created_at\": \"2025-12-05T23:33:32.000000Z\", \"project_id\": 24, \"sort_order\": 0, \"updated_at\": \"2025-12-05T23:34:30.000000Z\", \"actual_cost\": null, \"completedAt\": null, \"hourly_rate\": null, \"dateAssigned\": \"2025-12-05T23:33:32.000000Z\", \"is_scheduled\": 0, \"estimated_hours\": null, \"taskDescription\": \"Build webhook receiver to handle GitHub PR events, implement secure webhook validation, and establish two-way communication with GitHub API.\", \"allocated_budget\": \"5000.00\", \"completion_notes\": null, \"use_fixed_budget\": false, \"service_request_id\": null, \"calculated_earnings\": \"0.00\", \"progress_percentage\": 10, \"total_hours_tracked\": \"0.00\", \"requires_time_tracking\": false}', '{\"updated_at\": \"2025-12-06 07:34:31\", \"progress_percentage\": 0}', '[]', '172.19.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-05 23:34:31'),
(59, 'App\\Models\\Task', 26, 8, 'updated', 'model_change', '{\"notes\": null, \"status\": \"pending\", \"taskID\": 26, \"deadline\": \"2025-12-14T16:00:00.000000Z\", \"phase_id\": null, \"priority\": \"medium\", \"client_id\": 3, \"createdBy\": 1, \"taskTitle\": \"GitHub Integration & Webhook Handler\", \"assignedTo\": 8, \"created_at\": \"2025-12-05T23:33:32.000000Z\", \"project_id\": 24, \"sort_order\": 0, \"updated_at\": \"2025-12-05T23:34:31.000000Z\", \"actual_cost\": null, \"completedAt\": null, \"hourly_rate\": null, \"dateAssigned\": \"2025-12-05T23:33:32.000000Z\", \"is_scheduled\": 0, \"estimated_hours\": null, \"taskDescription\": \"Build webhook receiver to handle GitHub PR events, implement secure webhook validation, and establish two-way communication with GitHub API.\", \"allocated_budget\": \"5000.00\", \"completion_notes\": null, \"use_fixed_budget\": false, \"service_request_id\": null, \"calculated_earnings\": \"0.00\", \"progress_percentage\": 0, \"total_hours_tracked\": \"0.00\", \"requires_time_tracking\": false}', '{\"updated_at\": \"2025-12-06 07:34:34\", \"progress_percentage\": 10}', '[]', '172.19.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-05 23:34:34'),
(60, 'App\\Models\\Task', 26, 8, 'updated', 'model_change', '{\"notes\": null, \"status\": \"pending\", \"taskID\": 26, \"deadline\": \"2025-12-14T16:00:00.000000Z\", \"phase_id\": null, \"priority\": \"medium\", \"client_id\": 3, \"createdBy\": 1, \"taskTitle\": \"GitHub Integration & Webhook Handler\", \"assignedTo\": 8, \"created_at\": \"2025-12-05T23:33:32.000000Z\", \"project_id\": 24, \"sort_order\": 0, \"updated_at\": \"2025-12-05T23:34:34.000000Z\", \"actual_cost\": null, \"completedAt\": null, \"hourly_rate\": null, \"dateAssigned\": \"2025-12-05T23:33:32.000000Z\", \"is_scheduled\": 0, \"estimated_hours\": null, \"taskDescription\": \"Build webhook receiver to handle GitHub PR events, implement secure webhook validation, and establish two-way communication with GitHub API.\", \"allocated_budget\": \"5000.00\", \"completion_notes\": null, \"use_fixed_budget\": false, \"service_request_id\": null, \"calculated_earnings\": \"0.00\", \"progress_percentage\": 10, \"total_hours_tracked\": \"0.00\", \"requires_time_tracking\": false}', '{\"progress_percentage\": 0}', '[]', '172.19.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-05 23:34:34'),
(61, 'App\\Models\\Task', 26, 8, 'updated', 'model_change', '{\"notes\": null, \"status\": \"pending\", \"taskID\": 26, \"deadline\": \"2025-12-14T16:00:00.000000Z\", \"phase_id\": null, \"priority\": \"medium\", \"client_id\": 3, \"createdBy\": 1, \"taskTitle\": \"GitHub Integration & Webhook Handler\", \"assignedTo\": 8, \"created_at\": \"2025-12-05T23:33:32.000000Z\", \"project_id\": 24, \"sort_order\": 0, \"updated_at\": \"2025-12-05T23:34:34.000000Z\", \"actual_cost\": null, \"completedAt\": null, \"hourly_rate\": null, \"dateAssigned\": \"2025-12-05T23:33:32.000000Z\", \"is_scheduled\": 0, \"estimated_hours\": null, \"taskDescription\": \"Build webhook receiver to handle GitHub PR events, implement secure webhook validation, and establish two-way communication with GitHub API.\", \"allocated_budget\": \"5000.00\", \"completion_notes\": null, \"use_fixed_budget\": false, \"service_request_id\": null, \"calculated_earnings\": \"0.00\", \"progress_percentage\": 0, \"total_hours_tracked\": \"0.00\", \"requires_time_tracking\": false}', '{\"progress_percentage\": 10}', '[]', '172.19.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-05 23:34:34'),
(62, 'App\\Models\\Task', 26, 8, 'updated', 'model_change', '{\"notes\": null, \"status\": \"pending\", \"taskID\": 26, \"deadline\": \"2025-12-14T16:00:00.000000Z\", \"phase_id\": null, \"priority\": \"medium\", \"client_id\": 3, \"createdBy\": 1, \"taskTitle\": \"GitHub Integration & Webhook Handler\", \"assignedTo\": 8, \"created_at\": \"2025-12-05T23:33:32.000000Z\", \"project_id\": 24, \"sort_order\": 0, \"updated_at\": \"2025-12-05T23:34:34.000000Z\", \"actual_cost\": null, \"completedAt\": null, \"hourly_rate\": null, \"dateAssigned\": \"2025-12-05T23:33:32.000000Z\", \"is_scheduled\": 0, \"estimated_hours\": null, \"taskDescription\": \"Build webhook receiver to handle GitHub PR events, implement secure webhook validation, and establish two-way communication with GitHub API.\", \"allocated_budget\": \"5000.00\", \"completion_notes\": null, \"use_fixed_budget\": false, \"service_request_id\": null, \"calculated_earnings\": \"0.00\", \"progress_percentage\": 10, \"total_hours_tracked\": \"0.00\", \"requires_time_tracking\": false}', '{\"progress_percentage\": 0}', '[]', '172.19.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-05 23:34:34'),
(63, 'App\\Models\\Task', 26, 8, 'updated', 'model_change', '{\"notes\": null, \"status\": \"pending\", \"taskID\": 26, \"deadline\": \"2025-12-14T16:00:00.000000Z\", \"phase_id\": null, \"priority\": \"medium\", \"client_id\": 3, \"createdBy\": 1, \"taskTitle\": \"GitHub Integration & Webhook Handler\", \"assignedTo\": 8, \"created_at\": \"2025-12-05T23:33:32.000000Z\", \"project_id\": 24, \"sort_order\": 0, \"updated_at\": \"2025-12-05T23:34:34.000000Z\", \"actual_cost\": null, \"completedAt\": null, \"hourly_rate\": null, \"dateAssigned\": \"2025-12-05T23:33:32.000000Z\", \"is_scheduled\": 0, \"estimated_hours\": null, \"taskDescription\": \"Build webhook receiver to handle GitHub PR events, implement secure webhook validation, and establish two-way communication with GitHub API.\", \"allocated_budget\": \"5000.00\", \"completion_notes\": null, \"use_fixed_budget\": false, \"service_request_id\": null, \"calculated_earnings\": \"0.00\", \"progress_percentage\": 0, \"total_hours_tracked\": \"0.00\", \"requires_time_tracking\": false}', '{\"progress_percentage\": 10}', '[]', '172.19.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-05 23:34:34'),
(64, 'App\\Models\\Task', 26, 8, 'updated', 'model_change', '{\"notes\": null, \"status\": \"pending\", \"taskID\": 26, \"deadline\": \"2025-12-14T16:00:00.000000Z\", \"phase_id\": null, \"priority\": \"medium\", \"client_id\": 3, \"createdBy\": 1, \"taskTitle\": \"GitHub Integration & Webhook Handler\", \"assignedTo\": 8, \"created_at\": \"2025-12-05T23:33:32.000000Z\", \"project_id\": 24, \"sort_order\": 0, \"updated_at\": \"2025-12-05T23:34:34.000000Z\", \"actual_cost\": null, \"completedAt\": null, \"hourly_rate\": null, \"dateAssigned\": \"2025-12-05T23:33:32.000000Z\", \"is_scheduled\": 0, \"estimated_hours\": null, \"taskDescription\": \"Build webhook receiver to handle GitHub PR events, implement secure webhook validation, and establish two-way communication with GitHub API.\", \"allocated_budget\": \"5000.00\", \"completion_notes\": null, \"use_fixed_budget\": false, \"service_request_id\": null, \"calculated_earnings\": \"0.00\", \"progress_percentage\": 10, \"total_hours_tracked\": \"0.00\", \"requires_time_tracking\": false}', '{\"progress_percentage\": 0}', '[]', '172.19.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-05 23:34:34'),
(65, 'App\\Models\\Task', 26, 8, 'updated', 'model_change', '{\"notes\": null, \"status\": \"pending\", \"taskID\": 26, \"deadline\": \"2025-12-14T16:00:00.000000Z\", \"phase_id\": null, \"priority\": \"medium\", \"client_id\": 3, \"createdBy\": 1, \"taskTitle\": \"GitHub Integration & Webhook Handler\", \"assignedTo\": 8, \"created_at\": \"2025-12-05T23:33:32.000000Z\", \"project_id\": 24, \"sort_order\": 0, \"updated_at\": \"2025-12-05T23:34:34.000000Z\", \"actual_cost\": null, \"completedAt\": null, \"hourly_rate\": null, \"dateAssigned\": \"2025-12-05T23:33:32.000000Z\", \"is_scheduled\": 0, \"estimated_hours\": null, \"taskDescription\": \"Build webhook receiver to handle GitHub PR events, implement secure webhook validation, and establish two-way communication with GitHub API.\", \"allocated_budget\": \"5000.00\", \"completion_notes\": null, \"use_fixed_budget\": false, \"service_request_id\": null, \"calculated_earnings\": \"0.00\", \"progress_percentage\": 0, \"total_hours_tracked\": \"0.00\", \"requires_time_tracking\": false}', '{\"progress_percentage\": 10}', '[]', '172.19.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-05 23:34:34'),
(66, 'App\\Models\\Task', 26, 8, 'updated', 'model_change', '{\"notes\": null, \"status\": \"pending\", \"taskID\": 26, \"deadline\": \"2025-12-14T16:00:00.000000Z\", \"phase_id\": null, \"priority\": \"medium\", \"client_id\": 3, \"createdBy\": 1, \"taskTitle\": \"GitHub Integration & Webhook Handler\", \"assignedTo\": 8, \"created_at\": \"2025-12-05T23:33:32.000000Z\", \"project_id\": 24, \"sort_order\": 0, \"updated_at\": \"2025-12-05T23:34:34.000000Z\", \"actual_cost\": null, \"completedAt\": null, \"hourly_rate\": null, \"dateAssigned\": \"2025-12-05T23:33:32.000000Z\", \"is_scheduled\": 0, \"estimated_hours\": null, \"taskDescription\": \"Build webhook receiver to handle GitHub PR events, implement secure webhook validation, and establish two-way communication with GitHub API.\", \"allocated_budget\": \"5000.00\", \"completion_notes\": null, \"use_fixed_budget\": false, \"service_request_id\": null, \"calculated_earnings\": \"0.00\", \"progress_percentage\": 10, \"total_hours_tracked\": \"0.00\", \"requires_time_tracking\": false}', '{\"updated_at\": \"2025-12-06 07:34:35\", \"progress_percentage\": 0}', '[]', '172.19.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-05 23:34:35'),
(67, 'App\\Models\\Task', 26, 8, 'updated', 'model_change', '{\"notes\": null, \"status\": \"pending\", \"taskID\": 26, \"deadline\": \"2025-12-14T16:00:00.000000Z\", \"phase_id\": null, \"priority\": \"medium\", \"client_id\": 3, \"createdBy\": 1, \"taskTitle\": \"GitHub Integration & Webhook Handler\", \"assignedTo\": 8, \"created_at\": \"2025-12-05T23:33:32.000000Z\", \"project_id\": 24, \"sort_order\": 0, \"updated_at\": \"2025-12-05T23:34:35.000000Z\", \"actual_cost\": null, \"completedAt\": null, \"hourly_rate\": null, \"dateAssigned\": \"2025-12-05T23:33:32.000000Z\", \"is_scheduled\": 0, \"estimated_hours\": null, \"taskDescription\": \"Build webhook receiver to handle GitHub PR events, implement secure webhook validation, and establish two-way communication with GitHub API.\", \"allocated_budget\": \"5000.00\", \"completion_notes\": null, \"use_fixed_budget\": false, \"service_request_id\": null, \"calculated_earnings\": \"0.00\", \"progress_percentage\": 0, \"total_hours_tracked\": \"0.00\", \"requires_time_tracking\": false}', '{\"progress_percentage\": 10}', '[]', '172.19.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-05 23:34:35'),
(68, 'App\\Models\\Task', 26, 8, 'updated', 'model_change', '{\"notes\": null, \"status\": \"pending\", \"taskID\": 26, \"deadline\": \"2025-12-14T16:00:00.000000Z\", \"phase_id\": null, \"priority\": \"medium\", \"client_id\": 3, \"createdBy\": 1, \"taskTitle\": \"GitHub Integration & Webhook Handler\", \"assignedTo\": 8, \"created_at\": \"2025-12-05T23:33:32.000000Z\", \"project_id\": 24, \"sort_order\": 0, \"updated_at\": \"2025-12-05T23:34:35.000000Z\", \"actual_cost\": null, \"completedAt\": null, \"hourly_rate\": null, \"dateAssigned\": \"2025-12-05T23:33:32.000000Z\", \"is_scheduled\": 0, \"estimated_hours\": null, \"taskDescription\": \"Build webhook receiver to handle GitHub PR events, implement secure webhook validation, and establish two-way communication with GitHub API.\", \"allocated_budget\": \"5000.00\", \"completion_notes\": null, \"use_fixed_budget\": false, \"service_request_id\": null, \"calculated_earnings\": \"0.00\", \"progress_percentage\": 10, \"total_hours_tracked\": \"0.00\", \"requires_time_tracking\": false}', '{\"progress_percentage\": 0}', '[]', '172.19.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-05 23:34:35'),
(69, 'App\\Models\\Task', 26, 8, 'updated', 'model_change', '{\"notes\": null, \"status\": \"pending\", \"taskID\": 26, \"deadline\": \"2025-12-14T16:00:00.000000Z\", \"phase_id\": null, \"priority\": \"medium\", \"client_id\": 3, \"createdBy\": 1, \"taskTitle\": \"GitHub Integration & Webhook Handler\", \"assignedTo\": 8, \"created_at\": \"2025-12-05T23:33:32.000000Z\", \"project_id\": 24, \"sort_order\": 0, \"updated_at\": \"2025-12-05T23:34:35.000000Z\", \"actual_cost\": null, \"completedAt\": null, \"hourly_rate\": null, \"dateAssigned\": \"2025-12-05T23:33:32.000000Z\", \"is_scheduled\": 0, \"estimated_hours\": null, \"taskDescription\": \"Build webhook receiver to handle GitHub PR events, implement secure webhook validation, and establish two-way communication with GitHub API.\", \"allocated_budget\": \"5000.00\", \"completion_notes\": null, \"use_fixed_budget\": false, \"service_request_id\": null, \"calculated_earnings\": \"0.00\", \"progress_percentage\": 0, \"total_hours_tracked\": \"0.00\", \"requires_time_tracking\": false}', '{\"updated_at\": \"2025-12-06 07:38:25\", \"progress_percentage\": 10}', '[]', '172.19.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-05 23:38:25'),
(70, 'App\\Models\\Task', 26, 8, 'updated', 'model_change', '{\"notes\": null, \"status\": \"pending\", \"taskID\": 26, \"deadline\": \"2025-12-14T16:00:00.000000Z\", \"phase_id\": null, \"priority\": \"medium\", \"client_id\": 3, \"createdBy\": 1, \"taskTitle\": \"GitHub Integration & Webhook Handler\", \"assignedTo\": 8, \"created_at\": \"2025-12-05T23:33:32.000000Z\", \"project_id\": 24, \"sort_order\": 0, \"updated_at\": \"2025-12-05T23:38:25.000000Z\", \"actual_cost\": null, \"completedAt\": null, \"hourly_rate\": null, \"dateAssigned\": \"2025-12-05T23:33:32.000000Z\", \"is_scheduled\": 0, \"estimated_hours\": null, \"taskDescription\": \"Build webhook receiver to handle GitHub PR events, implement secure webhook validation, and establish two-way communication with GitHub API.\", \"allocated_budget\": \"5000.00\", \"completion_notes\": null, \"use_fixed_budget\": false, \"service_request_id\": null, \"calculated_earnings\": \"0.00\", \"progress_percentage\": 10, \"total_hours_tracked\": \"0.00\", \"requires_time_tracking\": false}', '{\"updated_at\": \"2025-12-06 07:38:28\", \"progress_percentage\": 20}', '[]', '172.19.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-05 23:38:28'),
(71, 'App\\Models\\Task', 26, 8, 'updated', 'model_change', '{\"notes\": null, \"status\": \"pending\", \"taskID\": 26, \"deadline\": \"2025-12-14T16:00:00.000000Z\", \"phase_id\": null, \"priority\": \"medium\", \"client_id\": 3, \"createdBy\": 1, \"taskTitle\": \"GitHub Integration & Webhook Handler\", \"assignedTo\": 8, \"created_at\": \"2025-12-05T23:33:32.000000Z\", \"project_id\": 24, \"sort_order\": 0, \"updated_at\": \"2025-12-05T23:38:28.000000Z\", \"actual_cost\": null, \"completedAt\": null, \"hourly_rate\": null, \"dateAssigned\": \"2025-12-05T23:33:32.000000Z\", \"is_scheduled\": 0, \"estimated_hours\": null, \"taskDescription\": \"Build webhook receiver to handle GitHub PR events, implement secure webhook validation, and establish two-way communication with GitHub API.\", \"allocated_budget\": \"5000.00\", \"completion_notes\": null, \"use_fixed_budget\": false, \"service_request_id\": null, \"calculated_earnings\": \"0.00\", \"progress_percentage\": 20, \"total_hours_tracked\": \"0.00\", \"requires_time_tracking\": false}', '{\"updated_at\": \"2025-12-06 07:38:32\", \"progress_percentage\": 30}', '[]', '172.19.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-05 23:38:32'),
(72, 'App\\Models\\Task', 26, 8, 'updated', 'model_change', '{\"notes\": null, \"status\": \"pending\", \"taskID\": 26, \"deadline\": \"2025-12-14T16:00:00.000000Z\", \"phase_id\": null, \"priority\": \"medium\", \"client_id\": 3, \"createdBy\": 1, \"taskTitle\": \"GitHub Integration & Webhook Handler\", \"assignedTo\": 8, \"created_at\": \"2025-12-05T23:33:32.000000Z\", \"project_id\": 24, \"sort_order\": 0, \"updated_at\": \"2025-12-05T23:38:32.000000Z\", \"actual_cost\": null, \"completedAt\": null, \"hourly_rate\": null, \"dateAssigned\": \"2025-12-05T23:33:32.000000Z\", \"is_scheduled\": 0, \"estimated_hours\": null, \"taskDescription\": \"Build webhook receiver to handle GitHub PR events, implement secure webhook validation, and establish two-way communication with GitHub API.\", \"allocated_budget\": \"5000.00\", \"completion_notes\": null, \"use_fixed_budget\": false, \"service_request_id\": null, \"calculated_earnings\": \"0.00\", \"progress_percentage\": 30, \"total_hours_tracked\": \"0.00\", \"requires_time_tracking\": false}', '{\"updated_at\": \"2025-12-06 07:38:33\", \"progress_percentage\": 20}', '[]', '172.19.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-05 23:38:33'),
(73, 'App\\Models\\Task', 26, 8, 'updated', 'model_change', '{\"notes\": null, \"status\": \"pending\", \"taskID\": 26, \"deadline\": \"2025-12-14T16:00:00.000000Z\", \"phase_id\": null, \"priority\": \"medium\", \"client_id\": 3, \"createdBy\": 1, \"taskTitle\": \"GitHub Integration & Webhook Handler\", \"assignedTo\": 8, \"created_at\": \"2025-12-05T23:33:32.000000Z\", \"project_id\": 24, \"sort_order\": 0, \"updated_at\": \"2025-12-05T23:38:33.000000Z\", \"actual_cost\": null, \"completedAt\": null, \"hourly_rate\": null, \"dateAssigned\": \"2025-12-05T23:33:32.000000Z\", \"is_scheduled\": 0, \"estimated_hours\": null, \"taskDescription\": \"Build webhook receiver to handle GitHub PR events, implement secure webhook validation, and establish two-way communication with GitHub API.\", \"allocated_budget\": \"5000.00\", \"completion_notes\": null, \"use_fixed_budget\": false, \"service_request_id\": null, \"calculated_earnings\": \"0.00\", \"progress_percentage\": 20, \"total_hours_tracked\": \"0.00\", \"requires_time_tracking\": false}', '{\"updated_at\": \"2025-12-06 07:38:36\", \"progress_percentage\": 30}', '[]', '172.19.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-05 23:38:36'),
(74, 'App\\Models\\Task', 26, 8, 'updated', 'model_change', '{\"notes\": null, \"status\": \"pending\", \"taskID\": 26, \"deadline\": \"2025-12-14T16:00:00.000000Z\", \"phase_id\": null, \"priority\": \"medium\", \"client_id\": 3, \"createdBy\": 1, \"taskTitle\": \"GitHub Integration & Webhook Handler\", \"assignedTo\": 8, \"created_at\": \"2025-12-05T23:33:32.000000Z\", \"project_id\": 24, \"sort_order\": 0, \"updated_at\": \"2025-12-05T23:38:36.000000Z\", \"actual_cost\": null, \"completedAt\": null, \"hourly_rate\": null, \"dateAssigned\": \"2025-12-05T23:33:32.000000Z\", \"is_scheduled\": 0, \"estimated_hours\": null, \"taskDescription\": \"Build webhook receiver to handle GitHub PR events, implement secure webhook validation, and establish two-way communication with GitHub API.\", \"allocated_budget\": \"5000.00\", \"completion_notes\": null, \"use_fixed_budget\": false, \"service_request_id\": null, \"calculated_earnings\": \"0.00\", \"progress_percentage\": 30, \"total_hours_tracked\": \"0.00\", \"requires_time_tracking\": false}', '{\"updated_at\": \"2025-12-06 07:38:39\", \"progress_percentage\": 40}', '[]', '172.19.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-05 23:38:39'),
(75, 'App\\Models\\Task', 26, 8, 'updated', 'model_change', '{\"notes\": null, \"status\": \"pending\", \"taskID\": 26, \"deadline\": \"2025-12-14T16:00:00.000000Z\", \"phase_id\": null, \"priority\": \"medium\", \"client_id\": 3, \"createdBy\": 1, \"taskTitle\": \"GitHub Integration & Webhook Handler\", \"assignedTo\": 8, \"created_at\": \"2025-12-05T23:33:32.000000Z\", \"project_id\": 24, \"sort_order\": 0, \"updated_at\": \"2025-12-05T23:38:39.000000Z\", \"actual_cost\": null, \"completedAt\": null, \"hourly_rate\": null, \"dateAssigned\": \"2025-12-05T23:33:32.000000Z\", \"is_scheduled\": 0, \"estimated_hours\": null, \"taskDescription\": \"Build webhook receiver to handle GitHub PR events, implement secure webhook validation, and establish two-way communication with GitHub API.\", \"allocated_budget\": \"5000.00\", \"completion_notes\": null, \"use_fixed_budget\": false, \"service_request_id\": null, \"calculated_earnings\": \"0.00\", \"progress_percentage\": 40, \"total_hours_tracked\": \"0.00\", \"requires_time_tracking\": false}', '{\"updated_at\": \"2025-12-06 07:38:42\", \"progress_percentage\": 50}', '[]', '172.19.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-05 23:38:42'),
(76, 'App\\Models\\Task', 26, 8, 'updated', 'model_change', '{\"notes\": null, \"status\": \"pending\", \"taskID\": 26, \"deadline\": \"2025-12-14T16:00:00.000000Z\", \"phase_id\": null, \"priority\": \"medium\", \"client_id\": 3, \"createdBy\": 1, \"taskTitle\": \"GitHub Integration & Webhook Handler\", \"assignedTo\": 8, \"created_at\": \"2025-12-05T23:33:32.000000Z\", \"project_id\": 24, \"sort_order\": 0, \"updated_at\": \"2025-12-05T23:38:42.000000Z\", \"actual_cost\": null, \"completedAt\": null, \"hourly_rate\": null, \"dateAssigned\": \"2025-12-05T23:33:32.000000Z\", \"is_scheduled\": 0, \"estimated_hours\": null, \"taskDescription\": \"Build webhook receiver to handle GitHub PR events, implement secure webhook validation, and establish two-way communication with GitHub API.\", \"allocated_budget\": \"5000.00\", \"completion_notes\": null, \"use_fixed_budget\": false, \"service_request_id\": null, \"calculated_earnings\": \"0.00\", \"progress_percentage\": 50, \"total_hours_tracked\": \"0.00\", \"requires_time_tracking\": false}', '{\"updated_at\": \"2025-12-06 07:38:45\", \"progress_percentage\": 60}', '[]', '172.19.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-05 23:38:45'),
(77, 'App\\Models\\Task', 26, 8, 'updated', 'model_change', '{\"notes\": null, \"status\": \"pending\", \"taskID\": 26, \"deadline\": \"2025-12-14T16:00:00.000000Z\", \"phase_id\": null, \"priority\": \"medium\", \"client_id\": 3, \"createdBy\": 1, \"taskTitle\": \"GitHub Integration & Webhook Handler\", \"assignedTo\": 8, \"created_at\": \"2025-12-05T23:33:32.000000Z\", \"project_id\": 24, \"sort_order\": 0, \"updated_at\": \"2025-12-05T23:38:45.000000Z\", \"actual_cost\": null, \"completedAt\": null, \"hourly_rate\": null, \"dateAssigned\": \"2025-12-05T23:33:32.000000Z\", \"is_scheduled\": 0, \"estimated_hours\": null, \"taskDescription\": \"Build webhook receiver to handle GitHub PR events, implement secure webhook validation, and establish two-way communication with GitHub API.\", \"allocated_budget\": \"5000.00\", \"completion_notes\": null, \"use_fixed_budget\": false, \"service_request_id\": null, \"calculated_earnings\": \"0.00\", \"progress_percentage\": 60, \"total_hours_tracked\": \"0.00\", \"requires_time_tracking\": false}', '{\"updated_at\": \"2025-12-06 07:38:48\", \"progress_percentage\": 50}', '[]', '172.19.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-05 23:38:48'),
(78, 'App\\Models\\Task', 26, 8, 'updated', 'model_change', '{\"notes\": null, \"status\": \"pending\", \"taskID\": 26, \"deadline\": \"2025-12-14T16:00:00.000000Z\", \"phase_id\": null, \"priority\": \"medium\", \"client_id\": 3, \"createdBy\": 1, \"taskTitle\": \"GitHub Integration & Webhook Handler\", \"assignedTo\": 8, \"created_at\": \"2025-12-05T23:33:32.000000Z\", \"project_id\": 24, \"sort_order\": 0, \"updated_at\": \"2025-12-05T23:38:48.000000Z\", \"actual_cost\": null, \"completedAt\": null, \"hourly_rate\": null, \"dateAssigned\": \"2025-12-05T23:33:32.000000Z\", \"is_scheduled\": 0, \"estimated_hours\": null, \"taskDescription\": \"Build webhook receiver to handle GitHub PR events, implement secure webhook validation, and establish two-way communication with GitHub API.\", \"allocated_budget\": \"5000.00\", \"completion_notes\": null, \"use_fixed_budget\": false, \"service_request_id\": null, \"calculated_earnings\": \"0.00\", \"progress_percentage\": 50, \"total_hours_tracked\": \"0.00\", \"requires_time_tracking\": false}', '{\"updated_at\": \"2025-12-06 07:38:51\", \"progress_percentage\": 60}', '[]', '172.19.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-05 23:38:51'),
(79, 'App\\Models\\Task', 26, 8, 'updated', 'model_change', '{\"notes\": null, \"status\": \"pending\", \"taskID\": 26, \"deadline\": \"2025-12-14T16:00:00.000000Z\", \"phase_id\": null, \"priority\": \"medium\", \"client_id\": 3, \"createdBy\": 1, \"taskTitle\": \"GitHub Integration & Webhook Handler\", \"assignedTo\": 8, \"created_at\": \"2025-12-05T23:33:32.000000Z\", \"project_id\": 24, \"sort_order\": 0, \"updated_at\": \"2025-12-05T23:38:51.000000Z\", \"actual_cost\": null, \"completedAt\": null, \"hourly_rate\": null, \"dateAssigned\": \"2025-12-05T23:33:32.000000Z\", \"is_scheduled\": 0, \"estimated_hours\": null, \"taskDescription\": \"Build webhook receiver to handle GitHub PR events, implement secure webhook validation, and establish two-way communication with GitHub API.\", \"allocated_budget\": \"5000.00\", \"completion_notes\": null, \"use_fixed_budget\": false, \"service_request_id\": null, \"calculated_earnings\": \"0.00\", \"progress_percentage\": 60, \"total_hours_tracked\": \"0.00\", \"requires_time_tracking\": false}', '{\"updated_at\": \"2025-12-06 07:38:54\", \"progress_percentage\": 70}', '[]', '172.19.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-05 23:38:54'),
(80, 'App\\Models\\Task', 26, 8, 'updated', 'model_change', '{\"notes\": null, \"status\": \"pending\", \"taskID\": 26, \"deadline\": \"2025-12-14T16:00:00.000000Z\", \"phase_id\": null, \"priority\": \"medium\", \"client_id\": 3, \"createdBy\": 1, \"taskTitle\": \"GitHub Integration & Webhook Handler\", \"assignedTo\": 8, \"created_at\": \"2025-12-05T23:33:32.000000Z\", \"project_id\": 24, \"sort_order\": 0, \"updated_at\": \"2025-12-05T23:38:54.000000Z\", \"actual_cost\": null, \"completedAt\": null, \"hourly_rate\": null, \"dateAssigned\": \"2025-12-05T23:33:32.000000Z\", \"is_scheduled\": 0, \"estimated_hours\": null, \"taskDescription\": \"Build webhook receiver to handle GitHub PR events, implement secure webhook validation, and establish two-way communication with GitHub API.\", \"allocated_budget\": \"5000.00\", \"completion_notes\": null, \"use_fixed_budget\": false, \"service_request_id\": null, \"calculated_earnings\": \"0.00\", \"progress_percentage\": 70, \"total_hours_tracked\": \"0.00\", \"requires_time_tracking\": false}', '{\"updated_at\": \"2025-12-06 07:38:56\", \"progress_percentage\": 80}', '[]', '172.19.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-05 23:38:56'),
(81, 'App\\Models\\Task', 26, 8, 'updated', 'model_change', '{\"notes\": null, \"status\": \"pending\", \"taskID\": 26, \"deadline\": \"2025-12-14T16:00:00.000000Z\", \"phase_id\": null, \"priority\": \"medium\", \"client_id\": 3, \"createdBy\": 1, \"taskTitle\": \"GitHub Integration & Webhook Handler\", \"assignedTo\": 8, \"created_at\": \"2025-12-05T23:33:32.000000Z\", \"project_id\": 24, \"sort_order\": 0, \"updated_at\": \"2025-12-05T23:38:56.000000Z\", \"actual_cost\": null, \"completedAt\": null, \"hourly_rate\": null, \"dateAssigned\": \"2025-12-05T23:33:32.000000Z\", \"is_scheduled\": 0, \"estimated_hours\": null, \"taskDescription\": \"Build webhook receiver to handle GitHub PR events, implement secure webhook validation, and establish two-way communication with GitHub API.\", \"allocated_budget\": \"5000.00\", \"completion_notes\": null, \"use_fixed_budget\": false, \"service_request_id\": null, \"calculated_earnings\": \"0.00\", \"progress_percentage\": 80, \"total_hours_tracked\": \"0.00\", \"requires_time_tracking\": false}', '{\"updated_at\": \"2025-12-06 07:39:02\", \"progress_percentage\": 90}', '[]', '172.19.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-05 23:39:02'),
(82, 'App\\Models\\Task', 26, 8, 'updated', 'model_change', '{\"notes\": null, \"status\": \"pending\", \"taskID\": 26, \"deadline\": \"2025-12-14T16:00:00.000000Z\", \"phase_id\": null, \"priority\": \"medium\", \"client_id\": 3, \"createdBy\": 1, \"taskTitle\": \"GitHub Integration & Webhook Handler\", \"assignedTo\": 8, \"created_at\": \"2025-12-05T23:33:32.000000Z\", \"project_id\": 24, \"sort_order\": 0, \"updated_at\": \"2025-12-05T23:39:02.000000Z\", \"actual_cost\": null, \"completedAt\": null, \"hourly_rate\": null, \"dateAssigned\": \"2025-12-05T23:33:32.000000Z\", \"is_scheduled\": 0, \"estimated_hours\": null, \"taskDescription\": \"Build webhook receiver to handle GitHub PR events, implement secure webhook validation, and establish two-way communication with GitHub API.\", \"allocated_budget\": \"5000.00\", \"completion_notes\": null, \"use_fixed_budget\": false, \"service_request_id\": null, \"calculated_earnings\": \"0.00\", \"progress_percentage\": 90, \"total_hours_tracked\": \"0.00\", \"requires_time_tracking\": false}', '{\"updated_at\": \"2025-12-06 07:39:10\", \"progress_percentage\": 100}', '[]', '172.19.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-05 23:39:10'),
(83, 'App\\Models\\Task', 26, 8, 'updated', 'model_change', '{\"notes\": null, \"status\": \"pending\", \"taskID\": 26, \"deadline\": \"2025-12-14T16:00:00.000000Z\", \"phase_id\": null, \"priority\": \"medium\", \"client_id\": 3, \"createdBy\": 1, \"taskTitle\": \"GitHub Integration & Webhook Handler\", \"assignedTo\": 8, \"created_at\": \"2025-12-05T23:33:32.000000Z\", \"project_id\": 24, \"sort_order\": 0, \"updated_at\": \"2025-12-05T23:39:10.000000Z\", \"actual_cost\": null, \"completedAt\": null, \"hourly_rate\": null, \"dateAssigned\": \"2025-12-05T23:33:32.000000Z\", \"is_scheduled\": 0, \"estimated_hours\": null, \"taskDescription\": \"Build webhook receiver to handle GitHub PR events, implement secure webhook validation, and establish two-way communication with GitHub API.\", \"allocated_budget\": \"5000.00\", \"completion_notes\": null, \"use_fixed_budget\": false, \"service_request_id\": null, \"calculated_earnings\": \"0.00\", \"progress_percentage\": 100, \"total_hours_tracked\": \"0.00\", \"requires_time_tracking\": false}', '{\"status\": \"completed\", \"updated_at\": \"2025-12-06 07:39:11\", \"completedAt\": \"2025-12-06 07:39:11\"}', '[]', '172.19.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-05 23:39:11'),
(84, 'App\\Models\\Task', 27, 1, 'created', 'model_change', NULL, '{\"notes\": null, \"status\": \"pending\", \"taskID\": 27, \"deadline\": null, \"phase_id\": null, \"priority\": \"medium\", \"client_id\": 3, \"createdBy\": 1, \"taskTitle\": \"Code Analysis Engine - Python Support\", \"assignedTo\": \"8\", \"created_at\": \"2025-12-06 07:45:46\", \"project_id\": \"24\", \"updated_at\": \"2025-12-06 07:45:46\", \"hourly_rate\": null, \"dateAssigned\": \"2025-12-06 07:45:46\", \"taskDescription\": \"Build Python-specific code analyzer using AST parsing to detect code quality issues, vulnerabilities, and architectural patterns.\", \"allocated_budget\": \"8000\", \"use_fixed_budget\": false, \"requires_time_tracking\": false}', '[]', '172.19.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-05 23:45:46'),
(85, 'App\\Models\\Task', 25, 7, 'updated', 'model_change', '{\"notes\": null, \"status\": \"in_progress\", \"taskID\": 25, \"deadline\": null, \"phase_id\": null, \"priority\": \"medium\", \"client_id\": 3, \"createdBy\": 1, \"taskTitle\": \"Task 1: Backend Infrastructure & API Foundation\", \"assignedTo\": 7, \"created_at\": \"2025-12-05T21:38:04.000000Z\", \"project_id\": 24, \"sort_order\": 0, \"updated_at\": \"2025-12-05T22:56:51.000000Z\", \"actual_cost\": null, \"completedAt\": null, \"hourly_rate\": null, \"dateAssigned\": \"2025-12-05T22:56:51.000000Z\", \"is_scheduled\": 0, \"estimated_hours\": null, \"taskDescription\": \"Set up core FastAPI application, database schema, authentication, and API scaffolding. Establish project structure, environment configuration, and deployment pipeline basics\", \"allocated_budget\": null, \"completion_notes\": null, \"use_fixed_budget\": false, \"service_request_id\": null, \"calculated_earnings\": \"0.00\", \"progress_percentage\": 56, \"total_hours_tracked\": \"0.00\", \"requires_time_tracking\": false}', '{\"status\": \"completed\", \"updated_at\": \"2025-12-06 08:07:02\", \"completedAt\": \"2025-12-06 08:07:02\"}', '[]', '172.19.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-06 00:07:02'),
(86, 'App\\Models\\Document', 14, 1, 'approved', 'deliverable_approval', NULL, '{\"task_id\": 25, \"fileName\": \"sales_report_20251203 (1).pdf\", \"approved_by\": 1, \"document_id\": 14}', '{\"approval_type\": \"admin_approval\"}', '172.19.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-06 00:34:44'),
(87, 'App\\Models\\Document', 13, 1, 'approved', 'deliverable_approval', NULL, '{\"task_id\": 25, \"fileName\": \"Material Design\", \"approved_by\": 1, \"document_id\": 13}', '{\"approval_type\": \"admin_approval\"}', '172.19.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-06 00:38:10'),
(88, 'App\\Models\\Document', 12, 1, 'approved', 'deliverable_approval', NULL, '{\"task_id\": 25, \"fileName\": \"Figma Design\", \"approved_by\": 1, \"document_id\": 12}', '{\"approval_type\": \"admin_approval\"}', '172.19.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-06 00:38:17'),
(89, 'App\\Models\\TimeEntry', 4, 7, 'updated', 'model_change', '{\"id\": 4, \"notes\": null, \"amount\": null, \"is_paid\": false, \"task_id\": 25, \"end_time\": null, \"is_capped\": false, \"payout_id\": null, \"adiutor_id\": 7, \"created_at\": \"2025-12-05T23:28:28.000000Z\", \"project_id\": 24, \"start_time\": \"2025-12-05T23:28:28.000000Z\", \"updated_at\": \"2025-12-05T23:28:28.000000Z\", \"adjusted_at\": null, \"adjusted_by\": null, \"approved_at\": null, \"approved_by\": null, \"description\": null, \"hourly_rate\": \"500.00\", \"is_approved\": false, \"is_billable\": 1, \"admin_adjusted\": false, \"billable_minutes\": null, \"duration_minutes\": null, \"adjustment_reason\": null, \"calculated_amount\": null, \"non_billable_minutes\": null, \"original_duration_minutes\": null, \"original_calculated_amount\": null}', '{\"end_time\": \"2025-12-06 09:03:06\", \"updated_at\": \"2025-12-06 09:03:06\", \"duration_minutes\": 94.63361946666666}', '[]', '172.19.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-06 01:03:06'),
(90, 'App\\Models\\TimeEntry', 4, 7, 'updated', 'model_change', '{\"id\": 4, \"notes\": null, \"amount\": null, \"is_paid\": false, \"task_id\": 25, \"end_time\": \"2025-12-06T01:03:06.000000Z\", \"is_capped\": false, \"payout_id\": null, \"adiutor_id\": 7, \"created_at\": \"2025-12-05T23:28:28.000000Z\", \"project_id\": 24, \"start_time\": \"2025-12-05T23:28:28.000000Z\", \"updated_at\": \"2025-12-06T01:03:06.000000Z\", \"adjusted_at\": null, \"adjusted_by\": null, \"approved_at\": null, \"approved_by\": null, \"description\": null, \"hourly_rate\": \"500.00\", \"is_approved\": false, \"is_billable\": 1, \"admin_adjusted\": false, \"billable_minutes\": null, \"duration_minutes\": 94, \"adjustment_reason\": null, \"calculated_amount\": null, \"non_billable_minutes\": null, \"original_duration_minutes\": null, \"original_calculated_amount\": null}', '{\"billable_minutes\": 94, \"calculated_amount\": 783.33, \"non_billable_minutes\": 0}', '[]', '172.19.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-06 01:03:06'),
(91, 'App\\Models\\TimeEntry', 1, 1, 'deleted', 'model_change', '{\"id\": 1, \"notes\": null, \"amount\": null, \"is_paid\": 0, \"task_id\": 9, \"end_time\": \"2025-10-29 21:50:33\", \"is_capped\": 0, \"payout_id\": null, \"adiutor_id\": 7, \"created_at\": \"2025-10-29 19:12:45\", \"project_id\": 3, \"start_time\": \"2025-10-29 19:12:45\", \"updated_at\": \"2025-10-29 19:12:45\", \"adjusted_at\": null, \"adjusted_by\": null, \"approved_at\": null, \"approved_by\": null, \"description\": null, \"hourly_rate\": null, \"is_approved\": 0, \"is_billable\": 1, \"admin_adjusted\": 0, \"billable_minutes\": null, \"duration_minutes\": null, \"adjustment_reason\": null, \"calculated_amount\": null, \"non_billable_minutes\": null, \"original_duration_minutes\": null, \"original_calculated_amount\": null}', NULL, '[]', '172.19.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-06 01:14:12');
INSERT INTO `audit_logs` (`id`, `auditable_type`, `auditable_id`, `user_id`, `action`, `event_type`, `old_values`, `new_values`, `metadata`, `ip_address`, `user_agent`, `created_at`) VALUES
(92, 'App\\Models\\TimeEntry', 4, 1, 'updated', 'model_change', '{\"id\": 4, \"notes\": null, \"amount\": null, \"is_paid\": false, \"task_id\": 25, \"end_time\": \"2025-12-06T01:03:06.000000Z\", \"is_capped\": false, \"payout_id\": null, \"adiutor_id\": 7, \"created_at\": \"2025-12-05T23:28:28.000000Z\", \"project_id\": 24, \"start_time\": \"2025-12-05T23:28:28.000000Z\", \"updated_at\": \"2025-12-06T01:03:06.000000Z\", \"adjusted_at\": null, \"adjusted_by\": null, \"approved_at\": null, \"approved_by\": null, \"description\": null, \"hourly_rate\": \"500.00\", \"is_approved\": false, \"is_billable\": 1, \"admin_adjusted\": false, \"billable_minutes\": 94, \"duration_minutes\": 95, \"adjustment_reason\": null, \"calculated_amount\": \"783.33\", \"non_billable_minutes\": 0, \"original_duration_minutes\": null, \"original_calculated_amount\": null}', '{\"updated_at\": \"2025-12-06 09:33:28\", \"approved_at\": \"2025-12-06 09:33:28\", \"approved_by\": 1, \"is_approved\": true}', '[]', '172.19.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-06 01:33:28'),
(93, 'App\\Models\\Task', 28, 1, 'created', 'model_change', NULL, '{\"notes\": null, \"status\": \"pending\", \"taskID\": 28, \"deadline\": null, \"phase_id\": null, \"priority\": \"medium\", \"client_id\": 3, \"createdBy\": 1, \"taskTitle\": \"Code Analysis Engine - JavaScript/TypeScript Support\", \"assignedTo\": \"7\", \"created_at\": \"2025-12-06 09:50:34\", \"project_id\": \"24\", \"updated_at\": \"2025-12-06 09:50:34\", \"hourly_rate\": null, \"dateAssigned\": \"2025-12-06 09:50:34\", \"taskDescription\": \"Build JavaScript/TypeScript code analyzer using AST and ESLint integration for detecting issues and best practice violations.\", \"allocated_budget\": null, \"use_fixed_budget\": false, \"requires_time_tracking\": false}', '[]', '172.19.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-06 01:50:34'),
(94, 'App\\Models\\TimeEntry', 5, 7, 'created', 'model_change', NULL, '{\"id\": 5, \"task_id\": \"28\", \"adiutor_id\": 7, \"created_at\": \"2025-12-06 09:51:59\", \"project_id\": 24, \"start_time\": \"2025-12-06 09:51:59\", \"updated_at\": \"2025-12-06 09:51:59\", \"description\": null, \"hourly_rate\": 500, \"is_approved\": false}', '[]', '172.19.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-06 01:51:59'),
(95, 'App\\Models\\Task', 27, 8, 'updated', 'model_change', '{\"notes\": null, \"status\": \"pending\", \"taskID\": 27, \"deadline\": null, \"phase_id\": null, \"priority\": \"medium\", \"client_id\": 3, \"createdBy\": 1, \"taskTitle\": \"Code Analysis Engine - Python Support\", \"assignedTo\": 8, \"created_at\": \"2025-12-05T23:45:46.000000Z\", \"project_id\": 24, \"sort_order\": 0, \"updated_at\": \"2025-12-05T23:45:46.000000Z\", \"actual_cost\": null, \"completedAt\": null, \"hourly_rate\": null, \"dateAssigned\": \"2025-12-05T23:45:46.000000Z\", \"is_scheduled\": 0, \"estimated_hours\": null, \"taskDescription\": \"Build Python-specific code analyzer using AST parsing to detect code quality issues, vulnerabilities, and architectural patterns.\", \"allocated_budget\": \"8000.00\", \"completion_notes\": null, \"use_fixed_budget\": false, \"service_request_id\": null, \"calculated_earnings\": \"0.00\", \"progress_percentage\": 0, \"total_hours_tracked\": \"0.00\", \"requires_time_tracking\": false}', '{\"updated_at\": \"2025-12-06 10:00:11\", \"progress_percentage\": 10}', '[]', '172.19.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-06 02:00:11'),
(96, 'App\\Models\\Task', 27, 8, 'updated', 'model_change', '{\"notes\": null, \"status\": \"pending\", \"taskID\": 27, \"deadline\": null, \"phase_id\": null, \"priority\": \"medium\", \"client_id\": 3, \"createdBy\": 1, \"taskTitle\": \"Code Analysis Engine - Python Support\", \"assignedTo\": 8, \"created_at\": \"2025-12-05T23:45:46.000000Z\", \"project_id\": 24, \"sort_order\": 0, \"updated_at\": \"2025-12-06T02:00:11.000000Z\", \"actual_cost\": null, \"completedAt\": null, \"hourly_rate\": null, \"dateAssigned\": \"2025-12-05T23:45:46.000000Z\", \"is_scheduled\": 0, \"estimated_hours\": null, \"taskDescription\": \"Build Python-specific code analyzer using AST parsing to detect code quality issues, vulnerabilities, and architectural patterns.\", \"allocated_budget\": \"8000.00\", \"completion_notes\": null, \"use_fixed_budget\": false, \"service_request_id\": null, \"calculated_earnings\": \"0.00\", \"progress_percentage\": 10, \"total_hours_tracked\": \"0.00\", \"requires_time_tracking\": false}', '{\"updated_at\": \"2025-12-06 10:00:15\", \"progress_percentage\": 20}', '[]', '172.19.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-06 02:00:15'),
(97, 'App\\Models\\Task', 27, 8, 'updated', 'model_change', '{\"notes\": null, \"status\": \"pending\", \"taskID\": 27, \"deadline\": null, \"phase_id\": null, \"priority\": \"medium\", \"client_id\": 3, \"createdBy\": 1, \"taskTitle\": \"Code Analysis Engine - Python Support\", \"assignedTo\": 8, \"created_at\": \"2025-12-05T23:45:46.000000Z\", \"project_id\": 24, \"sort_order\": 0, \"updated_at\": \"2025-12-06T02:00:15.000000Z\", \"actual_cost\": null, \"completedAt\": null, \"hourly_rate\": null, \"dateAssigned\": \"2025-12-05T23:45:46.000000Z\", \"is_scheduled\": 0, \"estimated_hours\": null, \"taskDescription\": \"Build Python-specific code analyzer using AST parsing to detect code quality issues, vulnerabilities, and architectural patterns.\", \"allocated_budget\": \"8000.00\", \"completion_notes\": null, \"use_fixed_budget\": false, \"service_request_id\": null, \"calculated_earnings\": \"0.00\", \"progress_percentage\": 20, \"total_hours_tracked\": \"0.00\", \"requires_time_tracking\": false}', '{\"updated_at\": \"2025-12-06 10:00:18\", \"progress_percentage\": 30}', '[]', '172.19.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-06 02:00:18'),
(98, 'App\\Models\\Task', 27, 8, 'updated', 'model_change', '{\"notes\": null, \"status\": \"pending\", \"taskID\": 27, \"deadline\": null, \"phase_id\": null, \"priority\": \"medium\", \"client_id\": 3, \"createdBy\": 1, \"taskTitle\": \"Code Analysis Engine - Python Support\", \"assignedTo\": 8, \"created_at\": \"2025-12-05T23:45:46.000000Z\", \"project_id\": 24, \"sort_order\": 0, \"updated_at\": \"2025-12-06T02:00:18.000000Z\", \"actual_cost\": null, \"completedAt\": null, \"hourly_rate\": null, \"dateAssigned\": \"2025-12-05T23:45:46.000000Z\", \"is_scheduled\": 0, \"estimated_hours\": null, \"taskDescription\": \"Build Python-specific code analyzer using AST parsing to detect code quality issues, vulnerabilities, and architectural patterns.\", \"allocated_budget\": \"8000.00\", \"completion_notes\": null, \"use_fixed_budget\": false, \"service_request_id\": null, \"calculated_earnings\": \"0.00\", \"progress_percentage\": 30, \"total_hours_tracked\": \"0.00\", \"requires_time_tracking\": false}', '{\"updated_at\": \"2025-12-06 10:05:11\", \"progress_percentage\": 40}', '[]', '172.19.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-06 02:05:11'),
(99, 'App\\Models\\Task', 27, 8, 'updated', 'model_change', '{\"notes\": null, \"status\": \"pending\", \"taskID\": 27, \"deadline\": null, \"phase_id\": null, \"priority\": \"medium\", \"client_id\": 3, \"createdBy\": 1, \"taskTitle\": \"Code Analysis Engine - Python Support\", \"assignedTo\": 8, \"created_at\": \"2025-12-05T23:45:46.000000Z\", \"project_id\": 24, \"sort_order\": 0, \"updated_at\": \"2025-12-06T02:05:11.000000Z\", \"actual_cost\": null, \"completedAt\": null, \"hourly_rate\": null, \"dateAssigned\": \"2025-12-05T23:45:46.000000Z\", \"is_scheduled\": 0, \"estimated_hours\": null, \"taskDescription\": \"Build Python-specific code analyzer using AST parsing to detect code quality issues, vulnerabilities, and architectural patterns.\", \"allocated_budget\": \"8000.00\", \"completion_notes\": null, \"use_fixed_budget\": false, \"service_request_id\": null, \"calculated_earnings\": \"0.00\", \"progress_percentage\": 40, \"total_hours_tracked\": \"0.00\", \"requires_time_tracking\": false}', '{\"updated_at\": \"2025-12-06 10:05:13\", \"progress_percentage\": 50}', '[]', '172.19.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-06 02:05:13'),
(100, 'App\\Models\\Task', 27, 8, 'updated', 'model_change', '{\"notes\": null, \"status\": \"pending\", \"taskID\": 27, \"deadline\": null, \"phase_id\": null, \"priority\": \"medium\", \"client_id\": 3, \"createdBy\": 1, \"taskTitle\": \"Code Analysis Engine - Python Support\", \"assignedTo\": 8, \"created_at\": \"2025-12-05T23:45:46.000000Z\", \"project_id\": 24, \"sort_order\": 0, \"updated_at\": \"2025-12-06T02:05:13.000000Z\", \"actual_cost\": null, \"completedAt\": null, \"hourly_rate\": null, \"dateAssigned\": \"2025-12-05T23:45:46.000000Z\", \"is_scheduled\": 0, \"estimated_hours\": null, \"taskDescription\": \"Build Python-specific code analyzer using AST parsing to detect code quality issues, vulnerabilities, and architectural patterns.\", \"allocated_budget\": \"8000.00\", \"completion_notes\": null, \"use_fixed_budget\": false, \"service_request_id\": null, \"calculated_earnings\": \"0.00\", \"progress_percentage\": 50, \"total_hours_tracked\": \"0.00\", \"requires_time_tracking\": false}', '{\"updated_at\": \"2025-12-06 10:05:16\", \"progress_percentage\": 60}', '[]', '172.19.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-06 02:05:16'),
(101, 'App\\Models\\Task', 27, 8, 'updated', 'model_change', '{\"notes\": null, \"status\": \"pending\", \"taskID\": 27, \"deadline\": null, \"phase_id\": null, \"priority\": \"medium\", \"client_id\": 3, \"createdBy\": 1, \"taskTitle\": \"Code Analysis Engine - Python Support\", \"assignedTo\": 8, \"created_at\": \"2025-12-05T23:45:46.000000Z\", \"project_id\": 24, \"sort_order\": 0, \"updated_at\": \"2025-12-06T02:05:16.000000Z\", \"actual_cost\": null, \"completedAt\": null, \"hourly_rate\": null, \"dateAssigned\": \"2025-12-05T23:45:46.000000Z\", \"is_scheduled\": 0, \"estimated_hours\": null, \"taskDescription\": \"Build Python-specific code analyzer using AST parsing to detect code quality issues, vulnerabilities, and architectural patterns.\", \"allocated_budget\": \"8000.00\", \"completion_notes\": null, \"use_fixed_budget\": false, \"service_request_id\": null, \"calculated_earnings\": \"0.00\", \"progress_percentage\": 60, \"total_hours_tracked\": \"0.00\", \"requires_time_tracking\": false}', '{\"updated_at\": \"2025-12-06 10:05:18\", \"progress_percentage\": 70}', '[]', '172.19.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-06 02:05:18'),
(102, 'App\\Models\\Task', 27, 8, 'updated', 'model_change', '{\"notes\": null, \"status\": \"pending\", \"taskID\": 27, \"deadline\": null, \"phase_id\": null, \"priority\": \"medium\", \"client_id\": 3, \"createdBy\": 1, \"taskTitle\": \"Code Analysis Engine - Python Support\", \"assignedTo\": 8, \"created_at\": \"2025-12-05T23:45:46.000000Z\", \"project_id\": 24, \"sort_order\": 0, \"updated_at\": \"2025-12-06T02:05:18.000000Z\", \"actual_cost\": null, \"completedAt\": null, \"hourly_rate\": null, \"dateAssigned\": \"2025-12-05T23:45:46.000000Z\", \"is_scheduled\": 0, \"estimated_hours\": null, \"taskDescription\": \"Build Python-specific code analyzer using AST parsing to detect code quality issues, vulnerabilities, and architectural patterns.\", \"allocated_budget\": \"8000.00\", \"completion_notes\": null, \"use_fixed_budget\": false, \"service_request_id\": null, \"calculated_earnings\": \"0.00\", \"progress_percentage\": 70, \"total_hours_tracked\": \"0.00\", \"requires_time_tracking\": false}', '{\"updated_at\": \"2025-12-06 10:05:20\", \"progress_percentage\": 80}', '[]', '172.19.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-06 02:05:20'),
(103, 'App\\Models\\Task', 27, 8, 'updated', 'model_change', '{\"notes\": null, \"status\": \"pending\", \"taskID\": 27, \"deadline\": null, \"phase_id\": null, \"priority\": \"medium\", \"client_id\": 3, \"createdBy\": 1, \"taskTitle\": \"Code Analysis Engine - Python Support\", \"assignedTo\": 8, \"created_at\": \"2025-12-05T23:45:46.000000Z\", \"project_id\": 24, \"sort_order\": 0, \"updated_at\": \"2025-12-06T02:05:20.000000Z\", \"actual_cost\": null, \"completedAt\": null, \"hourly_rate\": null, \"dateAssigned\": \"2025-12-05T23:45:46.000000Z\", \"is_scheduled\": 0, \"estimated_hours\": null, \"taskDescription\": \"Build Python-specific code analyzer using AST parsing to detect code quality issues, vulnerabilities, and architectural patterns.\", \"allocated_budget\": \"8000.00\", \"completion_notes\": null, \"use_fixed_budget\": false, \"service_request_id\": null, \"calculated_earnings\": \"0.00\", \"progress_percentage\": 80, \"total_hours_tracked\": \"0.00\", \"requires_time_tracking\": false}', '{\"updated_at\": \"2025-12-06 10:05:22\", \"progress_percentage\": 90}', '[]', '172.19.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-06 02:05:22'),
(104, 'App\\Models\\Task', 27, 8, 'updated', 'model_change', '{\"notes\": null, \"status\": \"pending\", \"taskID\": 27, \"deadline\": null, \"phase_id\": null, \"priority\": \"medium\", \"client_id\": 3, \"createdBy\": 1, \"taskTitle\": \"Code Analysis Engine - Python Support\", \"assignedTo\": 8, \"created_at\": \"2025-12-05T23:45:46.000000Z\", \"project_id\": 24, \"sort_order\": 0, \"updated_at\": \"2025-12-06T02:05:22.000000Z\", \"actual_cost\": null, \"completedAt\": null, \"hourly_rate\": null, \"dateAssigned\": \"2025-12-05T23:45:46.000000Z\", \"is_scheduled\": 0, \"estimated_hours\": null, \"taskDescription\": \"Build Python-specific code analyzer using AST parsing to detect code quality issues, vulnerabilities, and architectural patterns.\", \"allocated_budget\": \"8000.00\", \"completion_notes\": null, \"use_fixed_budget\": false, \"service_request_id\": null, \"calculated_earnings\": \"0.00\", \"progress_percentage\": 90, \"total_hours_tracked\": \"0.00\", \"requires_time_tracking\": false}', '{\"updated_at\": \"2025-12-06 10:11:54\", \"progress_percentage\": 100}', '[]', '172.19.0.1', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Mobile Safari/537.36', '2025-12-06 02:11:54'),
(105, 'App\\Models\\Task', 27, 8, 'updated', 'model_change', '{\"notes\": null, \"status\": \"pending\", \"taskID\": 27, \"deadline\": null, \"phase_id\": null, \"priority\": \"medium\", \"client_id\": 3, \"createdBy\": 1, \"taskTitle\": \"Code Analysis Engine - Python Support\", \"assignedTo\": 8, \"created_at\": \"2025-12-05T23:45:46.000000Z\", \"project_id\": 24, \"sort_order\": 0, \"updated_at\": \"2025-12-06T02:11:54.000000Z\", \"actual_cost\": null, \"completedAt\": null, \"hourly_rate\": null, \"dateAssigned\": \"2025-12-05T23:45:46.000000Z\", \"is_scheduled\": 0, \"estimated_hours\": null, \"taskDescription\": \"Build Python-specific code analyzer using AST parsing to detect code quality issues, vulnerabilities, and architectural patterns.\", \"allocated_budget\": \"8000.00\", \"completion_notes\": null, \"use_fixed_budget\": false, \"service_request_id\": null, \"calculated_earnings\": \"0.00\", \"progress_percentage\": 100, \"total_hours_tracked\": \"0.00\", \"requires_time_tracking\": false}', '{\"status\": \"completed\", \"completedAt\": \"2025-12-06 10:11:54\"}', '[]', '172.19.0.1', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Mobile Safari/537.36', '2025-12-06 02:11:54'),
(106, 'App\\Models\\Task', 29, 1, 'created', 'model_change', NULL, '{\"notes\": null, \"status\": \"pending\", \"taskID\": 29, \"deadline\": null, \"phase_id\": null, \"priority\": \"medium\", \"client_id\": 3, \"createdBy\": 1, \"taskTitle\": \"Issue Classification & Explanation Generation\", \"assignedTo\": \"8\", \"created_at\": \"2025-12-06 10:24:57\", \"project_id\": \"24\", \"updated_at\": \"2025-12-06 10:24:57\", \"hourly_rate\": null, \"dateAssigned\": \"2025-12-06 10:24:57\", \"taskDescription\": \"Categorize detected issues by severity/type and generate human-readable explanations with actionable suggestions.\", \"allocated_budget\": null, \"use_fixed_budget\": false, \"requires_time_tracking\": false}', '[]', '172.19.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-06 02:24:57'),
(107, 'App\\Models\\Task', 25, 1, 'updated', 'model_change', '{\"notes\": null, \"status\": \"completed\", \"taskID\": 25, \"deadline\": null, \"phase_id\": null, \"priority\": \"medium\", \"client_id\": 3, \"createdBy\": 1, \"taskTitle\": \"Task 1: Backend Infrastructure & API Foundation\", \"assignedTo\": 7, \"created_at\": \"2025-12-05T21:38:04.000000Z\", \"project_id\": 24, \"sort_order\": 0, \"updated_at\": \"2025-12-06T00:07:02.000000Z\", \"actual_cost\": null, \"completedAt\": \"2025-12-06T00:07:02.000000Z\", \"hourly_rate\": null, \"dateAssigned\": \"2025-12-05T22:56:51.000000Z\", \"is_scheduled\": 0, \"estimated_hours\": null, \"taskDescription\": \"Set up core FastAPI application, database schema, authentication, and API scaffolding. Establish project structure, environment configuration, and deployment pipeline basics\", \"allocated_budget\": null, \"completion_notes\": null, \"use_fixed_budget\": false, \"service_request_id\": null, \"calculated_earnings\": \"0.00\", \"progress_percentage\": 56, \"total_hours_tracked\": \"0.00\", \"requires_time_tracking\": false}', '{\"taskTitle\": \"Backend Infrastructure & API Foundation\", \"updated_at\": \"2025-12-06 10:25:18\", \"completedAt\": \"2025-12-06 00:00:00\"}', '[]', '172.19.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-06 02:25:18'),
(108, 'App\\Models\\Task', 28, 7, 'updated', 'model_change', '{\"notes\": null, \"status\": \"pending\", \"taskID\": 28, \"deadline\": null, \"phase_id\": null, \"priority\": \"medium\", \"client_id\": 3, \"createdBy\": 1, \"taskTitle\": \"Code Analysis Engine - JavaScript/TypeScript Support\", \"assignedTo\": 7, \"created_at\": \"2025-12-06T01:50:34.000000Z\", \"project_id\": 24, \"sort_order\": 0, \"updated_at\": \"2025-12-06T01:50:34.000000Z\", \"actual_cost\": null, \"completedAt\": null, \"hourly_rate\": null, \"dateAssigned\": \"2025-12-06T01:50:34.000000Z\", \"is_scheduled\": 0, \"estimated_hours\": null, \"taskDescription\": \"Build JavaScript/TypeScript code analyzer using AST and ESLint integration for detecting issues and best practice violations.\", \"allocated_budget\": null, \"completion_notes\": null, \"use_fixed_budget\": false, \"service_request_id\": null, \"calculated_earnings\": \"0.00\", \"progress_percentage\": 0, \"total_hours_tracked\": \"0.00\", \"requires_time_tracking\": false}', '{\"updated_at\": \"2025-12-06 10:26:07\", \"progress_percentage\": 10}', '[]', '172.19.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-06 02:26:07'),
(109, 'App\\Models\\Task', 28, 7, 'updated', 'model_change', '{\"notes\": null, \"status\": \"pending\", \"taskID\": 28, \"deadline\": null, \"phase_id\": null, \"priority\": \"medium\", \"client_id\": 3, \"createdBy\": 1, \"taskTitle\": \"Code Analysis Engine - JavaScript/TypeScript Support\", \"assignedTo\": 7, \"created_at\": \"2025-12-06T01:50:34.000000Z\", \"project_id\": 24, \"sort_order\": 0, \"updated_at\": \"2025-12-06T02:26:07.000000Z\", \"actual_cost\": null, \"completedAt\": null, \"hourly_rate\": null, \"dateAssigned\": \"2025-12-06T01:50:34.000000Z\", \"is_scheduled\": 0, \"estimated_hours\": null, \"taskDescription\": \"Build JavaScript/TypeScript code analyzer using AST and ESLint integration for detecting issues and best practice violations.\", \"allocated_budget\": null, \"completion_notes\": null, \"use_fixed_budget\": false, \"service_request_id\": null, \"calculated_earnings\": \"0.00\", \"progress_percentage\": 10, \"total_hours_tracked\": \"0.00\", \"requires_time_tracking\": false}', '{\"updated_at\": \"2025-12-06 10:26:08\", \"progress_percentage\": 20}', '[]', '172.19.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-06 02:26:08'),
(110, 'App\\Models\\Task', 28, 7, 'updated', 'model_change', '{\"notes\": null, \"status\": \"pending\", \"taskID\": 28, \"deadline\": null, \"phase_id\": null, \"priority\": \"medium\", \"client_id\": 3, \"createdBy\": 1, \"taskTitle\": \"Code Analysis Engine - JavaScript/TypeScript Support\", \"assignedTo\": 7, \"created_at\": \"2025-12-06T01:50:34.000000Z\", \"project_id\": 24, \"sort_order\": 0, \"updated_at\": \"2025-12-06T02:26:08.000000Z\", \"actual_cost\": null, \"completedAt\": null, \"hourly_rate\": null, \"dateAssigned\": \"2025-12-06T01:50:34.000000Z\", \"is_scheduled\": 0, \"estimated_hours\": null, \"taskDescription\": \"Build JavaScript/TypeScript code analyzer using AST and ESLint integration for detecting issues and best practice violations.\", \"allocated_budget\": null, \"completion_notes\": null, \"use_fixed_budget\": false, \"service_request_id\": null, \"calculated_earnings\": \"0.00\", \"progress_percentage\": 20, \"total_hours_tracked\": \"0.00\", \"requires_time_tracking\": false}', '{\"updated_at\": \"2025-12-06 10:26:11\", \"progress_percentage\": 30}', '[]', '172.19.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-06 02:26:11'),
(111, 'App\\Models\\Task', 28, 7, 'updated', 'model_change', '{\"notes\": null, \"status\": \"pending\", \"taskID\": 28, \"deadline\": null, \"phase_id\": null, \"priority\": \"medium\", \"client_id\": 3, \"createdBy\": 1, \"taskTitle\": \"Code Analysis Engine - JavaScript/TypeScript Support\", \"assignedTo\": 7, \"created_at\": \"2025-12-06T01:50:34.000000Z\", \"project_id\": 24, \"sort_order\": 0, \"updated_at\": \"2025-12-06T02:26:11.000000Z\", \"actual_cost\": null, \"completedAt\": null, \"hourly_rate\": null, \"dateAssigned\": \"2025-12-06T01:50:34.000000Z\", \"is_scheduled\": 0, \"estimated_hours\": null, \"taskDescription\": \"Build JavaScript/TypeScript code analyzer using AST and ESLint integration for detecting issues and best practice violations.\", \"allocated_budget\": null, \"completion_notes\": null, \"use_fixed_budget\": false, \"service_request_id\": null, \"calculated_earnings\": \"0.00\", \"progress_percentage\": 30, \"total_hours_tracked\": \"0.00\", \"requires_time_tracking\": false}', '{\"updated_at\": \"2025-12-06 10:26:12\", \"progress_percentage\": 40}', '[]', '172.19.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-06 02:26:12'),
(112, 'App\\Models\\Task', 28, 7, 'updated', 'model_change', '{\"notes\": null, \"status\": \"pending\", \"taskID\": 28, \"deadline\": null, \"phase_id\": null, \"priority\": \"medium\", \"client_id\": 3, \"createdBy\": 1, \"taskTitle\": \"Code Analysis Engine - JavaScript/TypeScript Support\", \"assignedTo\": 7, \"created_at\": \"2025-12-06T01:50:34.000000Z\", \"project_id\": 24, \"sort_order\": 0, \"updated_at\": \"2025-12-06T02:26:12.000000Z\", \"actual_cost\": null, \"completedAt\": null, \"hourly_rate\": null, \"dateAssigned\": \"2025-12-06T01:50:34.000000Z\", \"is_scheduled\": 0, \"estimated_hours\": null, \"taskDescription\": \"Build JavaScript/TypeScript code analyzer using AST and ESLint integration for detecting issues and best practice violations.\", \"allocated_budget\": null, \"completion_notes\": null, \"use_fixed_budget\": false, \"service_request_id\": null, \"calculated_earnings\": \"0.00\", \"progress_percentage\": 40, \"total_hours_tracked\": \"0.00\", \"requires_time_tracking\": false}', '{\"updated_at\": \"2025-12-06 10:26:16\", \"progress_percentage\": 50}', '[]', '172.19.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-06 02:26:16'),
(113, 'App\\Models\\Task', 28, 7, 'updated', 'model_change', '{\"notes\": null, \"status\": \"pending\", \"taskID\": 28, \"deadline\": null, \"phase_id\": null, \"priority\": \"medium\", \"client_id\": 3, \"createdBy\": 1, \"taskTitle\": \"Code Analysis Engine - JavaScript/TypeScript Support\", \"assignedTo\": 7, \"created_at\": \"2025-12-06T01:50:34.000000Z\", \"project_id\": 24, \"sort_order\": 0, \"updated_at\": \"2025-12-06T02:26:16.000000Z\", \"actual_cost\": null, \"completedAt\": null, \"hourly_rate\": null, \"dateAssigned\": \"2025-12-06T01:50:34.000000Z\", \"is_scheduled\": 0, \"estimated_hours\": null, \"taskDescription\": \"Build JavaScript/TypeScript code analyzer using AST and ESLint integration for detecting issues and best practice violations.\", \"allocated_budget\": null, \"completion_notes\": null, \"use_fixed_budget\": false, \"service_request_id\": null, \"calculated_earnings\": \"0.00\", \"progress_percentage\": 50, \"total_hours_tracked\": \"0.00\", \"requires_time_tracking\": false}', '{\"updated_at\": \"2025-12-06 10:26:17\", \"progress_percentage\": 60}', '[]', '172.19.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-06 02:26:17'),
(114, 'App\\Models\\Task', 28, 7, 'updated', 'model_change', '{\"notes\": null, \"status\": \"pending\", \"taskID\": 28, \"deadline\": null, \"phase_id\": null, \"priority\": \"medium\", \"client_id\": 3, \"createdBy\": 1, \"taskTitle\": \"Code Analysis Engine - JavaScript/TypeScript Support\", \"assignedTo\": 7, \"created_at\": \"2025-12-06T01:50:34.000000Z\", \"project_id\": 24, \"sort_order\": 0, \"updated_at\": \"2025-12-06T02:26:17.000000Z\", \"actual_cost\": null, \"completedAt\": null, \"hourly_rate\": null, \"dateAssigned\": \"2025-12-06T01:50:34.000000Z\", \"is_scheduled\": 0, \"estimated_hours\": null, \"taskDescription\": \"Build JavaScript/TypeScript code analyzer using AST and ESLint integration for detecting issues and best practice violations.\", \"allocated_budget\": null, \"completion_notes\": null, \"use_fixed_budget\": false, \"service_request_id\": null, \"calculated_earnings\": \"0.00\", \"progress_percentage\": 60, \"total_hours_tracked\": \"0.00\", \"requires_time_tracking\": false}', '{\"updated_at\": \"2025-12-06 10:26:18\", \"progress_percentage\": 70}', '[]', '172.19.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-06 02:26:18'),
(115, 'App\\Models\\Task', 28, 7, 'updated', 'model_change', '{\"notes\": null, \"status\": \"pending\", \"taskID\": 28, \"deadline\": null, \"phase_id\": null, \"priority\": \"medium\", \"client_id\": 3, \"createdBy\": 1, \"taskTitle\": \"Code Analysis Engine - JavaScript/TypeScript Support\", \"assignedTo\": 7, \"created_at\": \"2025-12-06T01:50:34.000000Z\", \"project_id\": 24, \"sort_order\": 0, \"updated_at\": \"2025-12-06T02:26:18.000000Z\", \"actual_cost\": null, \"completedAt\": null, \"hourly_rate\": null, \"dateAssigned\": \"2025-12-06T01:50:34.000000Z\", \"is_scheduled\": 0, \"estimated_hours\": null, \"taskDescription\": \"Build JavaScript/TypeScript code analyzer using AST and ESLint integration for detecting issues and best practice violations.\", \"allocated_budget\": null, \"completion_notes\": null, \"use_fixed_budget\": false, \"service_request_id\": null, \"calculated_earnings\": \"0.00\", \"progress_percentage\": 70, \"total_hours_tracked\": \"0.00\", \"requires_time_tracking\": false}', '{\"updated_at\": \"2025-12-06 10:26:19\", \"progress_percentage\": 80}', '[]', '172.19.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-06 02:26:19'),
(116, 'App\\Models\\Task', 28, 7, 'updated', 'model_change', '{\"notes\": null, \"status\": \"pending\", \"taskID\": 28, \"deadline\": null, \"phase_id\": null, \"priority\": \"medium\", \"client_id\": 3, \"createdBy\": 1, \"taskTitle\": \"Code Analysis Engine - JavaScript/TypeScript Support\", \"assignedTo\": 7, \"created_at\": \"2025-12-06T01:50:34.000000Z\", \"project_id\": 24, \"sort_order\": 0, \"updated_at\": \"2025-12-06T02:26:19.000000Z\", \"actual_cost\": null, \"completedAt\": null, \"hourly_rate\": null, \"dateAssigned\": \"2025-12-06T01:50:34.000000Z\", \"is_scheduled\": 0, \"estimated_hours\": null, \"taskDescription\": \"Build JavaScript/TypeScript code analyzer using AST and ESLint integration for detecting issues and best practice violations.\", \"allocated_budget\": null, \"completion_notes\": null, \"use_fixed_budget\": false, \"service_request_id\": null, \"calculated_earnings\": \"0.00\", \"progress_percentage\": 80, \"total_hours_tracked\": \"0.00\", \"requires_time_tracking\": false}', '{\"updated_at\": \"2025-12-06 10:26:20\", \"progress_percentage\": 90}', '[]', '172.19.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-06 02:26:20'),
(117, 'App\\Models\\Task', 28, 7, 'updated', 'model_change', '{\"notes\": null, \"status\": \"pending\", \"taskID\": 28, \"deadline\": null, \"phase_id\": null, \"priority\": \"medium\", \"client_id\": 3, \"createdBy\": 1, \"taskTitle\": \"Code Analysis Engine - JavaScript/TypeScript Support\", \"assignedTo\": 7, \"created_at\": \"2025-12-06T01:50:34.000000Z\", \"project_id\": 24, \"sort_order\": 0, \"updated_at\": \"2025-12-06T02:26:20.000000Z\", \"actual_cost\": null, \"completedAt\": null, \"hourly_rate\": null, \"dateAssigned\": \"2025-12-06T01:50:34.000000Z\", \"is_scheduled\": 0, \"estimated_hours\": null, \"taskDescription\": \"Build JavaScript/TypeScript code analyzer using AST and ESLint integration for detecting issues and best practice violations.\", \"allocated_budget\": null, \"completion_notes\": null, \"use_fixed_budget\": false, \"service_request_id\": null, \"calculated_earnings\": \"0.00\", \"progress_percentage\": 90, \"total_hours_tracked\": \"0.00\", \"requires_time_tracking\": false}', '{\"updated_at\": \"2025-12-06 10:26:22\", \"progress_percentage\": 100}', '[]', '172.19.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-06 02:26:22'),
(118, 'App\\Models\\Document', 17, 1, 'approved', 'deliverable_approval', NULL, '{\"task_id\": 28, \"fileName\": \"Bug Fixes Compilations (with code snippets)\", \"approved_by\": 1, \"document_id\": 17}', '{\"approval_type\": \"admin_approval\"}', '172.19.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-06 02:30:00'),
(119, 'App\\Models\\Document', 16, 1, 'approved', 'deliverable_approval', NULL, '{\"task_id\": 28, \"fileName\": \"Documentation\", \"approved_by\": 1, \"document_id\": 16}', '{\"approval_type\": \"admin_approval\"}', '172.19.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-06 02:30:07'),
(120, 'App\\Models\\Document', 15, 1, 'approved', 'deliverable_approval', NULL, '{\"task_id\": 28, \"fileName\": \"Github Repository\", \"approved_by\": 1, \"document_id\": 15}', '{\"approval_type\": \"admin_approval\"}', '172.19.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-06 02:30:20'),
(121, 'App\\Models\\Task', 28, 7, 'updated', 'model_change', '{\"notes\": null, \"status\": \"pending\", \"taskID\": 28, \"deadline\": null, \"phase_id\": null, \"priority\": \"medium\", \"client_id\": 3, \"createdBy\": 1, \"taskTitle\": \"Code Analysis Engine - JavaScript/TypeScript Support\", \"assignedTo\": 7, \"created_at\": \"2025-12-06T01:50:34.000000Z\", \"project_id\": 24, \"sort_order\": 0, \"updated_at\": \"2025-12-06T02:26:22.000000Z\", \"actual_cost\": null, \"completedAt\": null, \"hourly_rate\": null, \"dateAssigned\": \"2025-12-06T01:50:34.000000Z\", \"is_scheduled\": 0, \"estimated_hours\": null, \"taskDescription\": \"Build JavaScript/TypeScript code analyzer using AST and ESLint integration for detecting issues and best practice violations.\", \"allocated_budget\": null, \"completion_notes\": null, \"use_fixed_budget\": false, \"service_request_id\": null, \"calculated_earnings\": \"0.00\", \"progress_percentage\": 100, \"total_hours_tracked\": \"0.00\", \"requires_time_tracking\": false}', '{\"status\": \"completed\", \"updated_at\": \"2025-12-06 10:31:44\", \"completedAt\": \"2025-12-06 10:31:44\"}', '[]', '172.19.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-06 02:31:44'),
(122, 'App\\Models\\Task', 28, 1, 'updated', 'model_change', '{\"notes\": null, \"status\": \"completed\", \"taskID\": 28, \"deadline\": null, \"phase_id\": null, \"priority\": \"medium\", \"client_id\": 3, \"createdBy\": 1, \"taskTitle\": \"Code Analysis Engine - JavaScript/TypeScript Support\", \"assignedTo\": 7, \"created_at\": \"2025-12-06T01:50:34.000000Z\", \"project_id\": 24, \"sort_order\": 0, \"updated_at\": \"2025-12-06T02:31:44.000000Z\", \"actual_cost\": null, \"completedAt\": \"2025-12-06T02:31:44.000000Z\", \"hourly_rate\": null, \"dateAssigned\": \"2025-12-06T01:50:34.000000Z\", \"is_scheduled\": 0, \"estimated_hours\": null, \"taskDescription\": \"Build JavaScript/TypeScript code analyzer using AST and ESLint integration for detecting issues and best practice violations.\", \"allocated_budget\": null, \"completion_notes\": null, \"use_fixed_budget\": false, \"service_request_id\": null, \"calculated_earnings\": \"0.00\", \"progress_percentage\": 100, \"total_hours_tracked\": \"0.00\", \"requires_time_tracking\": false}', '{\"status\": \"in_progress\", \"updated_at\": \"2025-12-06 11:02:13\"}', '[]', '172.19.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-06 03:02:13');

-- --------------------------------------------------------

--
-- Table structure for table `budget_change_requests`
--

CREATE TABLE `budget_change_requests` (
  `id` bigint UNSIGNED NOT NULL,
  `task_id` bigint UNSIGNED NOT NULL,
  `adiutor_id` bigint UNSIGNED NOT NULL,
  `current_budget` decimal(10,2) NOT NULL,
  `requested_budget` decimal(10,2) NOT NULL,
  `reason` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('pending','approved','rejected') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `reviewed_by` bigint UNSIGNED DEFAULT NULL,
  `reviewed_at` timestamp NULL DEFAULT NULL,
  `review_notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `budget_change_requests`
--

INSERT INTO `budget_change_requests` (`id`, `task_id`, `adiutor_id`, `current_budget`, `requested_budget`, `reason`, `status`, `reviewed_by`, `reviewed_at`, `review_notes`, `created_at`, `updated_at`) VALUES
(1, 14, 7, 10000.00, 25000.00, 'We need to charge for additional 3rd party tools.', 'approved', 1, '2025-10-25 07:12:36', NULL, '2025-10-25 07:10:35', '2025-10-25 07:12:36'),
(2, 28, 7, 0.00, 5000.00, 'We need additional budget for the additional request of revision.', 'pending', NULL, NULL, NULL, '2025-12-06 11:12:24', '2025-12-06 11:12:24');

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cache`
--

INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('treis-adiutor-cache-adiutor_rating_10', 's:6:\"4.0000\";', 1764941161),
('treis-adiutor-cache-adiutor_rating_11', 's:6:\"4.0000\";', 1764941161),
('treis-adiutor-cache-adiutor_rating_34', 'N;', 1764941312),
('treis-adiutor-cache-adiutor_rating_7', 's:6:\"4.3333\";', 1764941161),
('treis-adiutor-cache-adiutor_rating_8', 's:6:\"4.3333\";', 1764941161),
('treis-adiutor-cache-adiutor_rating_9', 's:6:\"4.7500\";', 1764941161);
INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('treis-adiutor-cache-project_24_available_adiutors_b0edfdd4732627397317f1a2b4562d18', 'O:29:\"Illuminate\\Support\\Collection\":2:{s:8:\"\0*\0items\";a:6:{i:0;a:11:{s:2:\"id\";i:10;s:8:\"fullName\";s:5:\"Sofia\";s:5:\"email\";s:22:\"sarah@treisadiutor.com\";s:18:\"calendar_connected\";b:0;s:6:\"skills\";O:39:\"Illuminate\\Database\\Eloquent\\Collection\":2:{s:8:\"\0*\0items\";a:5:{i:0;O:16:\"App\\Models\\Skill\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:6:\"skills\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:7:{s:2:\"id\";i:10;s:4:\"name\";s:6:\"Vue.js\";s:11:\"description\";N;s:8:\"category\";s:11:\"Programming\";s:9:\"is_active\";i:1;s:10:\"created_at\";s:19:\"2025-10-25 06:38:12\";s:10:\"updated_at\";s:19:\"2025-10-25 06:38:12\";}s:11:\"\0*\0original\";a:13:{s:2:\"id\";i:10;s:4:\"name\";s:6:\"Vue.js\";s:11:\"description\";N;s:8:\"category\";s:11:\"Programming\";s:9:\"is_active\";i:1;s:10:\"created_at\";s:19:\"2025-10-25 06:38:12\";s:10:\"updated_at\";s:19:\"2025-10-25 06:38:12\";s:16:\"pivot_adiutor_id\";i:4;s:14:\"pivot_skill_id\";i:10;s:23:\"pivot_proficiency_level\";s:6:\"expert\";s:22:\"pivot_years_experience\";i:4;s:16:\"pivot_created_at\";s:19:\"2025-10-25 06:38:18\";s:16:\"pivot_updated_at\";s:19:\"2025-10-25 06:38:18\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:1:{s:9:\"is_active\";s:7:\"boolean\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:1:{s:5:\"pivot\";O:44:\"Illuminate\\Database\\Eloquent\\Relations\\Pivot\":37:{s:13:\"\0*\0connection\";N;s:8:\"\0*\0table\";s:14:\"adiutor_skills\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:0;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:6:{s:10:\"adiutor_id\";i:4;s:8:\"skill_id\";i:10;s:17:\"proficiency_level\";s:6:\"expert\";s:16:\"years_experience\";i:4;s:10:\"created_at\";s:19:\"2025-10-25 06:38:18\";s:10:\"updated_at\";s:19:\"2025-10-25 06:38:18\";}s:11:\"\0*\0original\";a:6:{s:10:\"adiutor_id\";i:4;s:8:\"skill_id\";i:10;s:17:\"proficiency_level\";s:6:\"expert\";s:16:\"years_experience\";i:4;s:10:\"created_at\";s:19:\"2025-10-25 06:38:18\";s:10:\"updated_at\";s:19:\"2025-10-25 06:38:18\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:0:{}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:0:{}s:10:\"\0*\0guarded\";a:0:{}s:11:\"pivotParent\";O:25:\"App\\Models\\AdiutorProfile\":33:{s:13:\"\0*\0connection\";N;s:8:\"\0*\0table\";s:16:\"adiutor_profiles\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:0;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:0:{}s:11:\"\0*\0original\";a:0:{}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:7:{s:12:\"availability\";s:5:\"array\";s:9:\"languages\";s:5:\"array\";s:20:\"standard_hourly_rate\";s:9:\"decimal:2\";s:21:\"minimum_payout_amount\";s:9:\"decimal:2\";s:14:\"payout_details\";s:5:\"array\";s:6:\"rating\";s:9:\"decimal:2\";s:11:\"is_verified\";s:7:\"boolean\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:19:{i:0;s:7:\"user_id\";i:1;s:3:\"bio\";i:2;s:5:\"title\";i:3;s:20:\"standard_hourly_rate\";i:4;s:8:\"currency\";i:5;s:21:\"minimum_payout_amount\";i:6;s:23:\"preferred_payout_method\";i:7;s:14:\"payout_details\";i:8;s:12:\"availability\";i:9;s:13:\"portfolio_url\";i:10;s:12:\"linkedin_url\";i:11;s:10:\"github_url\";i:12;s:10:\"experience\";i:13;s:9:\"languages\";i:14;s:8:\"location\";i:15;s:11:\"is_verified\";i:16;s:6:\"rating\";i:17;s:14:\"total_projects\";i:18;s:6:\"status\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}s:12:\"pivotRelated\";O:16:\"App\\Models\\Skill\":33:{s:13:\"\0*\0connection\";N;s:8:\"\0*\0table\";N;s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:0;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:0:{}s:11:\"\0*\0original\";a:0:{}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:1:{s:9:\"is_active\";s:7:\"boolean\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:4:{i:0;s:4:\"name\";i:1;s:8:\"category\";i:2;s:4:\"icon\";i:3;s:9:\"is_active\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}s:13:\"\0*\0foreignKey\";s:10:\"adiutor_id\";s:13:\"\0*\0relatedKey\";s:8:\"skill_id\";}}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:4:{i:0;s:4:\"name\";i:1;s:8:\"category\";i:2;s:4:\"icon\";i:3;s:9:\"is_active\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}i:1;O:16:\"App\\Models\\Skill\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:6:\"skills\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:7:{s:2:\"id\";i:58;s:4:\"name\";s:8:\"HTML/CSS\";s:11:\"description\";N;s:8:\"category\";N;s:9:\"is_active\";i:1;s:10:\"created_at\";s:19:\"2025-10-25 06:38:18\";s:10:\"updated_at\";s:19:\"2025-10-25 06:38:18\";}s:11:\"\0*\0original\";a:13:{s:2:\"id\";i:58;s:4:\"name\";s:8:\"HTML/CSS\";s:11:\"description\";N;s:8:\"category\";N;s:9:\"is_active\";i:1;s:10:\"created_at\";s:19:\"2025-10-25 06:38:18\";s:10:\"updated_at\";s:19:\"2025-10-25 06:38:18\";s:16:\"pivot_adiutor_id\";i:4;s:14:\"pivot_skill_id\";i:58;s:23:\"pivot_proficiency_level\";s:6:\"expert\";s:22:\"pivot_years_experience\";i:4;s:16:\"pivot_created_at\";s:19:\"2025-10-25 06:38:18\";s:16:\"pivot_updated_at\";s:19:\"2025-10-25 06:38:18\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:1:{s:9:\"is_active\";s:7:\"boolean\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:1:{s:5:\"pivot\";O:44:\"Illuminate\\Database\\Eloquent\\Relations\\Pivot\":37:{s:13:\"\0*\0connection\";N;s:8:\"\0*\0table\";s:14:\"adiutor_skills\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:0;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:6:{s:10:\"adiutor_id\";i:4;s:8:\"skill_id\";i:58;s:17:\"proficiency_level\";s:6:\"expert\";s:16:\"years_experience\";i:4;s:10:\"created_at\";s:19:\"2025-10-25 06:38:18\";s:10:\"updated_at\";s:19:\"2025-10-25 06:38:18\";}s:11:\"\0*\0original\";a:6:{s:10:\"adiutor_id\";i:4;s:8:\"skill_id\";i:58;s:17:\"proficiency_level\";s:6:\"expert\";s:16:\"years_experience\";i:4;s:10:\"created_at\";s:19:\"2025-10-25 06:38:18\";s:10:\"updated_at\";s:19:\"2025-10-25 06:38:18\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:0:{}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:0:{}s:10:\"\0*\0guarded\";a:0:{}s:11:\"pivotParent\";r:102;s:12:\"pivotRelated\";r:163;s:13:\"\0*\0foreignKey\";s:10:\"adiutor_id\";s:13:\"\0*\0relatedKey\";s:8:\"skill_id\";}}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:4:{i:0;s:4:\"name\";i:1;s:8:\"category\";i:2;s:4:\"icon\";i:3;s:9:\"is_active\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}i:2;O:16:\"App\\Models\\Skill\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:6:\"skills\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:7:{s:2:\"id\";i:59;s:4:\"name\";s:12:\"Tailwind CSS\";s:11:\"description\";N;s:8:\"category\";N;s:9:\"is_active\";i:1;s:10:\"created_at\";s:19:\"2025-10-25 06:38:18\";s:10:\"updated_at\";s:19:\"2025-10-25 06:38:18\";}s:11:\"\0*\0original\";a:13:{s:2:\"id\";i:59;s:4:\"name\";s:12:\"Tailwind CSS\";s:11:\"description\";N;s:8:\"category\";N;s:9:\"is_active\";i:1;s:10:\"created_at\";s:19:\"2025-10-25 06:38:18\";s:10:\"updated_at\";s:19:\"2025-10-25 06:38:18\";s:16:\"pivot_adiutor_id\";i:4;s:14:\"pivot_skill_id\";i:59;s:23:\"pivot_proficiency_level\";s:8:\"advanced\";s:22:\"pivot_years_experience\";i:3;s:16:\"pivot_created_at\";s:19:\"2025-10-25 06:38:18\";s:16:\"pivot_updated_at\";s:19:\"2025-10-25 06:38:18\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:1:{s:9:\"is_active\";s:7:\"boolean\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:1:{s:5:\"pivot\";O:44:\"Illuminate\\Database\\Eloquent\\Relations\\Pivot\":37:{s:13:\"\0*\0connection\";N;s:8:\"\0*\0table\";s:14:\"adiutor_skills\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:0;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:6:{s:10:\"adiutor_id\";i:4;s:8:\"skill_id\";i:59;s:17:\"proficiency_level\";s:8:\"advanced\";s:16:\"years_experience\";i:3;s:10:\"created_at\";s:19:\"2025-10-25 06:38:18\";s:10:\"updated_at\";s:19:\"2025-10-25 06:38:18\";}s:11:\"\0*\0original\";a:6:{s:10:\"adiutor_id\";i:4;s:8:\"skill_id\";i:59;s:17:\"proficiency_level\";s:8:\"advanced\";s:16:\"years_experience\";i:3;s:10:\"created_at\";s:19:\"2025-10-25 06:38:18\";s:10:\"updated_at\";s:19:\"2025-10-25 06:38:18\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:0:{}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:0:{}s:10:\"\0*\0guarded\";a:0:{}s:11:\"pivotParent\";r:102;s:12:\"pivotRelated\";r:163;s:13:\"\0*\0foreignKey\";s:10:\"adiutor_id\";s:13:\"\0*\0relatedKey\";s:8:\"skill_id\";}}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:4:{i:0;s:4:\"name\";i:1;s:8:\"category\";i:2;s:4:\"icon\";i:3;s:9:\"is_active\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}i:3;O:16:\"App\\Models\\Skill\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:6:\"skills\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:7:{s:2:\"id\";i:2;s:4:\"name\";s:10:\"JavaScript\";s:11:\"description\";N;s:8:\"category\";s:11:\"Programming\";s:9:\"is_active\";i:1;s:10:\"created_at\";s:19:\"2025-10-25 06:38:12\";s:10:\"updated_at\";s:19:\"2025-10-25 06:38:12\";}s:11:\"\0*\0original\";a:13:{s:2:\"id\";i:2;s:4:\"name\";s:10:\"JavaScript\";s:11:\"description\";N;s:8:\"category\";s:11:\"Programming\";s:9:\"is_active\";i:1;s:10:\"created_at\";s:19:\"2025-10-25 06:38:12\";s:10:\"updated_at\";s:19:\"2025-10-25 06:38:12\";s:16:\"pivot_adiutor_id\";i:4;s:14:\"pivot_skill_id\";i:2;s:23:\"pivot_proficiency_level\";s:8:\"advanced\";s:22:\"pivot_years_experience\";i:4;s:16:\"pivot_created_at\";s:19:\"2025-10-25 06:38:18\";s:16:\"pivot_updated_at\";s:19:\"2025-10-25 06:38:18\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:1:{s:9:\"is_active\";s:7:\"boolean\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:1:{s:5:\"pivot\";O:44:\"Illuminate\\Database\\Eloquent\\Relations\\Pivot\":37:{s:13:\"\0*\0connection\";N;s:8:\"\0*\0table\";s:14:\"adiutor_skills\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:0;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:6:{s:10:\"adiutor_id\";i:4;s:8:\"skill_id\";i:2;s:17:\"proficiency_level\";s:8:\"advanced\";s:16:\"years_experience\";i:4;s:10:\"created_at\";s:19:\"2025-10-25 06:38:18\";s:10:\"updated_at\";s:19:\"2025-10-25 06:38:18\";}s:11:\"\0*\0original\";a:6:{s:10:\"adiutor_id\";i:4;s:8:\"skill_id\";i:2;s:17:\"proficiency_level\";s:8:\"advanced\";s:16:\"years_experience\";i:4;s:10:\"created_at\";s:19:\"2025-10-25 06:38:18\";s:10:\"updated_at\";s:19:\"2025-10-25 06:38:18\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:0:{}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:0:{}s:10:\"\0*\0guarded\";a:0:{}s:11:\"pivotParent\";r:102;s:12:\"pivotRelated\";r:163;s:13:\"\0*\0foreignKey\";s:10:\"adiutor_id\";s:13:\"\0*\0relatedKey\";s:8:\"skill_id\";}}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:4:{i:0;s:4:\"name\";i:1;s:8:\"category\";i:2;s:4:\"icon\";i:3;s:9:\"is_active\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}i:4;O:16:\"App\\Models\\Skill\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:6:\"skills\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:7:{s:2:\"id\";i:60;s:4:\"name\";s:17:\"Responsive Design\";s:11:\"description\";N;s:8:\"category\";N;s:9:\"is_active\";i:1;s:10:\"created_at\";s:19:\"2025-10-25 06:38:18\";s:10:\"updated_at\";s:19:\"2025-10-25 06:38:18\";}s:11:\"\0*\0original\";a:13:{s:2:\"id\";i:60;s:4:\"name\";s:17:\"Responsive Design\";s:11:\"description\";N;s:8:\"category\";N;s:9:\"is_active\";i:1;s:10:\"created_at\";s:19:\"2025-10-25 06:38:18\";s:10:\"updated_at\";s:19:\"2025-10-25 06:38:18\";s:16:\"pivot_adiutor_id\";i:4;s:14:\"pivot_skill_id\";i:60;s:23:\"pivot_proficiency_level\";s:6:\"expert\";s:22:\"pivot_years_experience\";i:4;s:16:\"pivot_created_at\";s:19:\"2025-10-25 06:38:18\";s:16:\"pivot_updated_at\";s:19:\"2025-10-25 06:38:18\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:1:{s:9:\"is_active\";s:7:\"boolean\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:1:{s:5:\"pivot\";O:44:\"Illuminate\\Database\\Eloquent\\Relations\\Pivot\":37:{s:13:\"\0*\0connection\";N;s:8:\"\0*\0table\";s:14:\"adiutor_skills\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:0;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:6:{s:10:\"adiutor_id\";i:4;s:8:\"skill_id\";i:60;s:17:\"proficiency_level\";s:6:\"expert\";s:16:\"years_experience\";i:4;s:10:\"created_at\";s:19:\"2025-10-25 06:38:18\";s:10:\"updated_at\";s:19:\"2025-10-25 06:38:18\";}s:11:\"\0*\0original\";a:6:{s:10:\"adiutor_id\";i:4;s:8:\"skill_id\";i:60;s:17:\"proficiency_level\";s:6:\"expert\";s:16:\"years_experience\";i:4;s:10:\"created_at\";s:19:\"2025-10-25 06:38:18\";s:10:\"updated_at\";s:19:\"2025-10-25 06:38:18\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:0:{}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:0:{}s:10:\"\0*\0guarded\";a:0:{}s:11:\"pivotParent\";r:102;s:12:\"pivotRelated\";r:163;s:13:\"\0*\0foreignKey\";s:10:\"adiutor_id\";s:13:\"\0*\0relatedKey\";s:8:\"skill_id\";}}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:4:{i:0;s:4:\"name\";i:1;s:8:\"category\";i:2;s:4:\"icon\";i:3;s:9:\"is_active\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}s:6:\"rating\";s:6:\"4.0000\";s:21:\"active_projects_count\";i:3;s:10:\"rank_score\";d:88;s:11:\"skill_score\";i:70;s:14:\"workload_score\";i:18;s:18:\"has_matching_skill\";b:1;}i:1;a:11:{s:2:\"id\";i:34;s:8:\"fullName\";s:11:\"Lena Quizon\";s:5:\"email\";s:22:\"calli.ri0904@gmail.com\";s:18:\"calendar_connected\";b:1;s:6:\"skills\";O:39:\"Illuminate\\Database\\Eloquent\\Collection\":2:{s:8:\"\0*\0items\";a:4:{i:0;O:16:\"App\\Models\\Skill\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:6:\"skills\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:7:{s:2:\"id\";i:1;s:4:\"name\";s:3:\"PHP\";s:11:\"description\";N;s:8:\"category\";s:11:\"Programming\";s:9:\"is_active\";i:1;s:10:\"created_at\";s:19:\"2025-10-25 06:38:12\";s:10:\"updated_at\";s:19:\"2025-10-25 06:38:12\";}s:11:\"\0*\0original\";a:13:{s:2:\"id\";i:1;s:4:\"name\";s:3:\"PHP\";s:11:\"description\";N;s:8:\"category\";s:11:\"Programming\";s:9:\"is_active\";i:1;s:10:\"created_at\";s:19:\"2025-10-25 06:38:12\";s:10:\"updated_at\";s:19:\"2025-10-25 06:38:12\";s:16:\"pivot_adiutor_id\";i:6;s:14:\"pivot_skill_id\";i:1;s:23:\"pivot_proficiency_level\";s:6:\"expert\";s:22:\"pivot_years_experience\";i:7;s:16:\"pivot_created_at\";s:19:\"2025-11-16 11:06:16\";s:16:\"pivot_updated_at\";s:19:\"2025-11-16 11:06:16\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:1:{s:9:\"is_active\";s:7:\"boolean\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:1:{s:5:\"pivot\";O:44:\"Illuminate\\Database\\Eloquent\\Relations\\Pivot\":37:{s:13:\"\0*\0connection\";N;s:8:\"\0*\0table\";s:14:\"adiutor_skills\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:0;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:6:{s:10:\"adiutor_id\";i:6;s:8:\"skill_id\";i:1;s:17:\"proficiency_level\";s:6:\"expert\";s:16:\"years_experience\";i:7;s:10:\"created_at\";s:19:\"2025-11-16 11:06:16\";s:10:\"updated_at\";s:19:\"2025-11-16 11:06:16\";}s:11:\"\0*\0original\";a:6:{s:10:\"adiutor_id\";i:6;s:8:\"skill_id\";i:1;s:17:\"proficiency_level\";s:6:\"expert\";s:16:\"years_experience\";i:7;s:10:\"created_at\";s:19:\"2025-11-16 11:06:16\";s:10:\"updated_at\";s:19:\"2025-11-16 11:06:16\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:0:{}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:0:{}s:10:\"\0*\0guarded\";a:0:{}s:11:\"pivotParent\";r:102;s:12:\"pivotRelated\";r:163;s:13:\"\0*\0foreignKey\";s:10:\"adiutor_id\";s:13:\"\0*\0relatedKey\";s:8:\"skill_id\";}}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:4:{i:0;s:4:\"name\";i:1;s:8:\"category\";i:2;s:4:\"icon\";i:3;s:9:\"is_active\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}i:1;O:16:\"App\\Models\\Skill\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:6:\"skills\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:7:{s:2:\"id\";i:3;s:4:\"name\";s:6:\"Python\";s:11:\"description\";N;s:8:\"category\";s:11:\"Programming\";s:9:\"is_active\";i:1;s:10:\"created_at\";s:19:\"2025-10-25 06:38:12\";s:10:\"updated_at\";s:19:\"2025-10-25 06:38:12\";}s:11:\"\0*\0original\";a:13:{s:2:\"id\";i:3;s:4:\"name\";s:6:\"Python\";s:11:\"description\";N;s:8:\"category\";s:11:\"Programming\";s:9:\"is_active\";i:1;s:10:\"created_at\";s:19:\"2025-10-25 06:38:12\";s:10:\"updated_at\";s:19:\"2025-10-25 06:38:12\";s:16:\"pivot_adiutor_id\";i:6;s:14:\"pivot_skill_id\";i:3;s:23:\"pivot_proficiency_level\";s:8:\"advanced\";s:22:\"pivot_years_experience\";i:5;s:16:\"pivot_created_at\";s:19:\"2025-11-16 11:06:16\";s:16:\"pivot_updated_at\";s:19:\"2025-11-16 11:06:16\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:1:{s:9:\"is_active\";s:7:\"boolean\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:1:{s:5:\"pivot\";O:44:\"Illuminate\\Database\\Eloquent\\Relations\\Pivot\":37:{s:13:\"\0*\0connection\";N;s:8:\"\0*\0table\";s:14:\"adiutor_skills\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:0;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:6:{s:10:\"adiutor_id\";i:6;s:8:\"skill_id\";i:3;s:17:\"proficiency_level\";s:8:\"advanced\";s:16:\"years_experience\";i:5;s:10:\"created_at\";s:19:\"2025-11-16 11:06:16\";s:10:\"updated_at\";s:19:\"2025-11-16 11:06:16\";}s:11:\"\0*\0original\";a:6:{s:10:\"adiutor_id\";i:6;s:8:\"skill_id\";i:3;s:17:\"proficiency_level\";s:8:\"advanced\";s:16:\"years_experience\";i:5;s:10:\"created_at\";s:19:\"2025-11-16 11:06:16\";s:10:\"updated_at\";s:19:\"2025-11-16 11:06:16\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:0:{}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:0:{}s:10:\"\0*\0guarded\";a:0:{}s:11:\"pivotParent\";r:102;s:12:\"pivotRelated\";r:163;s:13:\"\0*\0foreignKey\";s:10:\"adiutor_id\";s:13:\"\0*\0relatedKey\";s:8:\"skill_id\";}}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:4:{i:0;s:4:\"name\";i:1;s:8:\"category\";i:2;s:4:\"icon\";i:3;s:9:\"is_active\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}i:2;O:16:\"App\\Models\\Skill\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:6:\"skills\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:7:{s:2:\"id\";i:4;s:4:\"name\";s:4:\"Java\";s:11:\"description\";N;s:8:\"category\";s:11:\"Programming\";s:9:\"is_active\";i:1;s:10:\"created_at\";s:19:\"2025-10-25 06:38:12\";s:10:\"updated_at\";s:19:\"2025-10-25 06:38:12\";}s:11:\"\0*\0original\";a:13:{s:2:\"id\";i:4;s:4:\"name\";s:4:\"Java\";s:11:\"description\";N;s:8:\"category\";s:11:\"Programming\";s:9:\"is_active\";i:1;s:10:\"created_at\";s:19:\"2025-10-25 06:38:12\";s:10:\"updated_at\";s:19:\"2025-10-25 06:38:12\";s:16:\"pivot_adiutor_id\";i:6;s:14:\"pivot_skill_id\";i:4;s:23:\"pivot_proficiency_level\";s:12:\"intermediate\";s:22:\"pivot_years_experience\";i:3;s:16:\"pivot_created_at\";s:19:\"2025-11-16 11:06:16\";s:16:\"pivot_updated_at\";s:19:\"2025-11-16 11:06:16\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:1:{s:9:\"is_active\";s:7:\"boolean\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:1:{s:5:\"pivot\";O:44:\"Illuminate\\Database\\Eloquent\\Relations\\Pivot\":37:{s:13:\"\0*\0connection\";N;s:8:\"\0*\0table\";s:14:\"adiutor_skills\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:0;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:6:{s:10:\"adiutor_id\";i:6;s:8:\"skill_id\";i:4;s:17:\"proficiency_level\";s:12:\"intermediate\";s:16:\"years_experience\";i:3;s:10:\"created_at\";s:19:\"2025-11-16 11:06:16\";s:10:\"updated_at\";s:19:\"2025-11-16 11:06:16\";}s:11:\"\0*\0original\";a:6:{s:10:\"adiutor_id\";i:6;s:8:\"skill_id\";i:4;s:17:\"proficiency_level\";s:12:\"intermediate\";s:16:\"years_experience\";i:3;s:10:\"created_at\";s:19:\"2025-11-16 11:06:16\";s:10:\"updated_at\";s:19:\"2025-11-16 11:06:16\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:0:{}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:0:{}s:10:\"\0*\0guarded\";a:0:{}s:11:\"pivotParent\";r:102;s:12:\"pivotRelated\";r:163;s:13:\"\0*\0foreignKey\";s:10:\"adiutor_id\";s:13:\"\0*\0relatedKey\";s:8:\"skill_id\";}}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:4:{i:0;s:4:\"name\";i:1;s:8:\"category\";i:2;s:4:\"icon\";i:3;s:9:\"is_active\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}i:3;O:16:\"App\\Models\\Skill\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:6:\"skills\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:7:{s:2:\"id\";i:12;s:4:\"name\";s:7:\"Node.js\";s:11:\"description\";N;s:8:\"category\";s:11:\"Programming\";s:9:\"is_active\";i:1;s:10:\"created_at\";s:19:\"2025-10-25 06:38:12\";s:10:\"updated_at\";s:19:\"2025-10-25 06:38:12\";}s:11:\"\0*\0original\";a:13:{s:2:\"id\";i:12;s:4:\"name\";s:7:\"Node.js\";s:11:\"description\";N;s:8:\"category\";s:11:\"Programming\";s:9:\"is_active\";i:1;s:10:\"created_at\";s:19:\"2025-10-25 06:38:12\";s:10:\"updated_at\";s:19:\"2025-10-25 06:38:12\";s:16:\"pivot_adiutor_id\";i:6;s:14:\"pivot_skill_id\";i:12;s:23:\"pivot_proficiency_level\";s:8:\"beginner\";s:22:\"pivot_years_experience\";i:1;s:16:\"pivot_created_at\";s:19:\"2025-11-16 11:06:16\";s:16:\"pivot_updated_at\";s:19:\"2025-11-16 11:06:16\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:1:{s:9:\"is_active\";s:7:\"boolean\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:1:{s:5:\"pivot\";O:44:\"Illuminate\\Database\\Eloquent\\Relations\\Pivot\":37:{s:13:\"\0*\0connection\";N;s:8:\"\0*\0table\";s:14:\"adiutor_skills\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:0;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:6:{s:10:\"adiutor_id\";i:6;s:8:\"skill_id\";i:12;s:17:\"proficiency_level\";s:8:\"beginner\";s:16:\"years_experience\";i:1;s:10:\"created_at\";s:19:\"2025-11-16 11:06:16\";s:10:\"updated_at\";s:19:\"2025-11-16 11:06:16\";}s:11:\"\0*\0original\";a:6:{s:10:\"adiutor_id\";i:6;s:8:\"skill_id\";i:12;s:17:\"proficiency_level\";s:8:\"beginner\";s:16:\"years_experience\";i:1;s:10:\"created_at\";s:19:\"2025-11-16 11:06:16\";s:10:\"updated_at\";s:19:\"2025-11-16 11:06:16\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:0:{}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:0:{}s:10:\"\0*\0guarded\";a:0:{}s:11:\"pivotParent\";r:102;s:12:\"pivotRelated\";r:163;s:13:\"\0*\0foreignKey\";s:10:\"adiutor_id\";s:13:\"\0*\0relatedKey\";s:8:\"skill_id\";}}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:4:{i:0;s:4:\"name\";i:1;s:8:\"category\";i:2;s:4:\"icon\";i:3;s:9:\"is_active\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}s:6:\"rating\";i:0;s:21:\"active_projects_count\";i:3;s:10:\"rank_score\";d:76.4;s:11:\"skill_score\";d:58.4;s:14:\"workload_score\";i:18;s:18:\"has_matching_skill\";b:1;}i:2;a:11:{s:2:\"id\";i:7;s:8:\"fullName\";s:11:\"Mark Andrew\";s:5:\"email\";s:21:\"mark@treisadiutor.com\";s:18:\"calendar_connected\";b:0;s:6:\"skills\";O:39:\"Illuminate\\Database\\Eloquent\\Collection\":2:{s:8:\"\0*\0items\";a:5:{i:0;O:16:\"App\\Models\\Skill\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:6:\"skills\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:7:{s:2:\"id\";i:2;s:4:\"name\";s:10:\"JavaScript\";s:11:\"description\";N;s:8:\"category\";s:11:\"Programming\";s:9:\"is_active\";i:1;s:10:\"created_at\";s:19:\"2025-10-25 06:38:12\";s:10:\"updated_at\";s:19:\"2025-10-25 06:38:12\";}s:11:\"\0*\0original\";a:13:{s:2:\"id\";i:2;s:4:\"name\";s:10:\"JavaScript\";s:11:\"description\";N;s:8:\"category\";s:11:\"Programming\";s:9:\"is_active\";i:1;s:10:\"created_at\";s:19:\"2025-10-25 06:38:12\";s:10:\"updated_at\";s:19:\"2025-10-25 06:38:12\";s:16:\"pivot_adiutor_id\";i:1;s:14:\"pivot_skill_id\";i:2;s:23:\"pivot_proficiency_level\";s:6:\"expert\";s:22:\"pivot_years_experience\";i:5;s:16:\"pivot_created_at\";s:19:\"2025-10-25 06:38:18\";s:16:\"pivot_updated_at\";s:19:\"2025-10-25 06:38:18\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:1:{s:9:\"is_active\";s:7:\"boolean\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:1:{s:5:\"pivot\";O:44:\"Illuminate\\Database\\Eloquent\\Relations\\Pivot\":37:{s:13:\"\0*\0connection\";N;s:8:\"\0*\0table\";s:14:\"adiutor_skills\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:0;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:6:{s:10:\"adiutor_id\";i:1;s:8:\"skill_id\";i:2;s:17:\"proficiency_level\";s:6:\"expert\";s:16:\"years_experience\";i:5;s:10:\"created_at\";s:19:\"2025-10-25 06:38:18\";s:10:\"updated_at\";s:19:\"2025-10-25 06:38:18\";}s:11:\"\0*\0original\";a:6:{s:10:\"adiutor_id\";i:1;s:8:\"skill_id\";i:2;s:17:\"proficiency_level\";s:6:\"expert\";s:16:\"years_experience\";i:5;s:10:\"created_at\";s:19:\"2025-10-25 06:38:18\";s:10:\"updated_at\";s:19:\"2025-10-25 06:38:18\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:0:{}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:0:{}s:10:\"\0*\0guarded\";a:0:{}s:11:\"pivotParent\";r:102;s:12:\"pivotRelated\";r:163;s:13:\"\0*\0foreignKey\";s:10:\"adiutor_id\";s:13:\"\0*\0relatedKey\";s:8:\"skill_id\";}}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:4:{i:0;s:4:\"name\";i:1;s:8:\"category\";i:2;s:4:\"icon\";i:3;s:9:\"is_active\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}i:1;O:16:\"App\\Models\\Skill\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:6:\"skills\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:7:{s:2:\"id\";i:9;s:4:\"name\";s:5:\"React\";s:11:\"description\";N;s:8:\"category\";s:11:\"Programming\";s:9:\"is_active\";i:1;s:10:\"created_at\";s:19:\"2025-10-25 06:38:12\";s:10:\"updated_at\";s:19:\"2025-10-25 06:38:12\";}s:11:\"\0*\0original\";a:13:{s:2:\"id\";i:9;s:4:\"name\";s:5:\"React\";s:11:\"description\";N;s:8:\"category\";s:11:\"Programming\";s:9:\"is_active\";i:1;s:10:\"created_at\";s:19:\"2025-10-25 06:38:12\";s:10:\"updated_at\";s:19:\"2025-10-25 06:38:12\";s:16:\"pivot_adiutor_id\";i:1;s:14:\"pivot_skill_id\";i:9;s:23:\"pivot_proficiency_level\";s:6:\"expert\";s:22:\"pivot_years_experience\";i:4;s:16:\"pivot_created_at\";s:19:\"2025-10-25 06:38:18\";s:16:\"pivot_updated_at\";s:19:\"2025-10-25 06:38:18\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:1:{s:9:\"is_active\";s:7:\"boolean\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:1:{s:5:\"pivot\";O:44:\"Illuminate\\Database\\Eloquent\\Relations\\Pivot\":37:{s:13:\"\0*\0connection\";N;s:8:\"\0*\0table\";s:14:\"adiutor_skills\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:0;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:6:{s:10:\"adiutor_id\";i:1;s:8:\"skill_id\";i:9;s:17:\"proficiency_level\";s:6:\"expert\";s:16:\"years_experience\";i:4;s:10:\"created_at\";s:19:\"2025-10-25 06:38:18\";s:10:\"updated_at\";s:19:\"2025-10-25 06:38:18\";}s:11:\"\0*\0original\";a:6:{s:10:\"adiutor_id\";i:1;s:8:\"skill_id\";i:9;s:17:\"proficiency_level\";s:6:\"expert\";s:16:\"years_experience\";i:4;s:10:\"created_at\";s:19:\"2025-10-25 06:38:18\";s:10:\"updated_at\";s:19:\"2025-10-25 06:38:18\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:0:{}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:0:{}s:10:\"\0*\0guarded\";a:0:{}s:11:\"pivotParent\";r:102;s:12:\"pivotRelated\";r:163;s:13:\"\0*\0foreignKey\";s:10:\"adiutor_id\";s:13:\"\0*\0relatedKey\";s:8:\"skill_id\";}}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:4:{i:0;s:4:\"name\";i:1;s:8:\"category\";i:2;s:4:\"icon\";i:3;s:9:\"is_active\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}i:2;O:16:\"App\\Models\\Skill\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:6:\"skills\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:7:{s:2:\"id\";i:12;s:4:\"name\";s:7:\"Node.js\";s:11:\"description\";N;s:8:\"category\";s:11:\"Programming\";s:9:\"is_active\";i:1;s:10:\"created_at\";s:19:\"2025-10-25 06:38:12\";s:10:\"updated_at\";s:19:\"2025-10-25 06:38:12\";}s:11:\"\0*\0original\";a:13:{s:2:\"id\";i:12;s:4:\"name\";s:7:\"Node.js\";s:11:\"description\";N;s:8:\"category\";s:11:\"Programming\";s:9:\"is_active\";i:1;s:10:\"created_at\";s:19:\"2025-10-25 06:38:12\";s:10:\"updated_at\";s:19:\"2025-10-25 06:38:12\";s:16:\"pivot_adiutor_id\";i:1;s:14:\"pivot_skill_id\";i:12;s:23:\"pivot_proficiency_level\";s:8:\"advanced\";s:22:\"pivot_years_experience\";i:4;s:16:\"pivot_created_at\";s:19:\"2025-10-25 06:38:18\";s:16:\"pivot_updated_at\";s:19:\"2025-10-25 06:38:18\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:1:{s:9:\"is_active\";s:7:\"boolean\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:1:{s:5:\"pivot\";O:44:\"Illuminate\\Database\\Eloquent\\Relations\\Pivot\":37:{s:13:\"\0*\0connection\";N;s:8:\"\0*\0table\";s:14:\"adiutor_skills\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:0;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:6:{s:10:\"adiutor_id\";i:1;s:8:\"skill_id\";i:12;s:17:\"proficiency_level\";s:8:\"advanced\";s:16:\"years_experience\";i:4;s:10:\"created_at\";s:19:\"2025-10-25 06:38:18\";s:10:\"updated_at\";s:19:\"2025-10-25 06:38:18\";}s:11:\"\0*\0original\";a:6:{s:10:\"adiutor_id\";i:1;s:8:\"skill_id\";i:12;s:17:\"proficiency_level\";s:8:\"advanced\";s:16:\"years_experience\";i:4;s:10:\"created_at\";s:19:\"2025-10-25 06:38:18\";s:10:\"updated_at\";s:19:\"2025-10-25 06:38:18\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:0:{}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:0:{}s:10:\"\0*\0guarded\";a:0:{}s:11:\"pivotParent\";r:102;s:12:\"pivotRelated\";r:163;s:13:\"\0*\0foreignKey\";s:10:\"adiutor_id\";s:13:\"\0*\0relatedKey\";s:8:\"skill_id\";}}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:4:{i:0;s:4:\"name\";i:1;s:8:\"category\";i:2;s:4:\"icon\";i:3;s:9:\"is_active\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}i:3;O:16:\"App\\Models\\Skill\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:6:\"skills\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:7:{s:2:\"id\";i:13;s:4:\"name\";s:7:\"Laravel\";s:11:\"description\";N;s:8:\"category\";s:11:\"Programming\";s:9:\"is_active\";i:1;s:10:\"created_at\";s:19:\"2025-10-25 06:38:12\";s:10:\"updated_at\";s:19:\"2025-10-25 06:38:12\";}s:11:\"\0*\0original\";a:13:{s:2:\"id\";i:13;s:4:\"name\";s:7:\"Laravel\";s:11:\"description\";N;s:8:\"category\";s:11:\"Programming\";s:9:\"is_active\";i:1;s:10:\"created_at\";s:19:\"2025-10-25 06:38:12\";s:10:\"updated_at\";s:19:\"2025-10-25 06:38:12\";s:16:\"pivot_adiutor_id\";i:1;s:14:\"pivot_skill_id\";i:13;s:23:\"pivot_proficiency_level\";s:8:\"advanced\";s:22:\"pivot_years_experience\";i:3;s:16:\"pivot_created_at\";s:19:\"2025-10-25 06:38:18\";s:16:\"pivot_updated_at\";s:19:\"2025-10-25 06:38:18\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:1:{s:9:\"is_active\";s:7:\"boolean\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:1:{s:5:\"pivot\";O:44:\"Illuminate\\Database\\Eloquent\\Relations\\Pivot\":37:{s:13:\"\0*\0connection\";N;s:8:\"\0*\0table\";s:14:\"adiutor_skills\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:0;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:6:{s:10:\"adiutor_id\";i:1;s:8:\"skill_id\";i:13;s:17:\"proficiency_level\";s:8:\"advanced\";s:16:\"years_experience\";i:3;s:10:\"created_at\";s:19:\"2025-10-25 06:38:18\";s:10:\"updated_at\";s:19:\"2025-10-25 06:38:18\";}s:11:\"\0*\0original\";a:6:{s:10:\"adiutor_id\";i:1;s:8:\"skill_id\";i:13;s:17:\"proficiency_level\";s:8:\"advanced\";s:16:\"years_experience\";i:3;s:10:\"created_at\";s:19:\"2025-10-25 06:38:18\";s:10:\"updated_at\";s:19:\"2025-10-25 06:38:18\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:0:{}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:0:{}s:10:\"\0*\0guarded\";a:0:{}s:11:\"pivotParent\";r:102;s:12:\"pivotRelated\";r:163;s:13:\"\0*\0foreignKey\";s:10:\"adiutor_id\";s:13:\"\0*\0relatedKey\";s:8:\"skill_id\";}}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:4:{i:0;s:4:\"name\";i:1;s:8:\"category\";i:2;s:4:\"icon\";i:3;s:9:\"is_active\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}i:4;O:16:\"App\\Models\\Skill\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:6:\"skills\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:7:{s:2:\"id\";i:14;s:4:\"name\";s:5:\"MySQL\";s:11:\"description\";N;s:8:\"category\";s:8:\"Database\";s:9:\"is_active\";i:1;s:10:\"created_at\";s:19:\"2025-10-25 06:38:12\";s:10:\"updated_at\";s:19:\"2025-10-25 06:38:12\";}s:11:\"\0*\0original\";a:13:{s:2:\"id\";i:14;s:4:\"name\";s:5:\"MySQL\";s:11:\"description\";N;s:8:\"category\";s:8:\"Database\";s:9:\"is_active\";i:1;s:10:\"created_at\";s:19:\"2025-10-25 06:38:12\";s:10:\"updated_at\";s:19:\"2025-10-25 06:38:12\";s:16:\"pivot_adiutor_id\";i:1;s:14:\"pivot_skill_id\";i:14;s:23:\"pivot_proficiency_level\";s:8:\"advanced\";s:22:\"pivot_years_experience\";i:5;s:16:\"pivot_created_at\";s:19:\"2025-10-25 06:38:18\";s:16:\"pivot_updated_at\";s:19:\"2025-10-25 06:38:18\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:1:{s:9:\"is_active\";s:7:\"boolean\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:1:{s:5:\"pivot\";O:44:\"Illuminate\\Database\\Eloquent\\Relations\\Pivot\":37:{s:13:\"\0*\0connection\";N;s:8:\"\0*\0table\";s:14:\"adiutor_skills\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:0;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:6:{s:10:\"adiutor_id\";i:1;s:8:\"skill_id\";i:14;s:17:\"proficiency_level\";s:8:\"advanced\";s:16:\"years_experience\";i:5;s:10:\"created_at\";s:19:\"2025-10-25 06:38:18\";s:10:\"updated_at\";s:19:\"2025-10-25 06:38:18\";}s:11:\"\0*\0original\";a:6:{s:10:\"adiutor_id\";i:1;s:8:\"skill_id\";i:14;s:17:\"proficiency_level\";s:8:\"advanced\";s:16:\"years_experience\";i:5;s:10:\"created_at\";s:19:\"2025-10-25 06:38:18\";s:10:\"updated_at\";s:19:\"2025-10-25 06:38:18\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:0:{}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:0:{}s:10:\"\0*\0guarded\";a:0:{}s:11:\"pivotParent\";r:102;s:12:\"pivotRelated\";r:163;s:13:\"\0*\0foreignKey\";s:10:\"adiutor_id\";s:13:\"\0*\0relatedKey\";s:8:\"skill_id\";}}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:4:{i:0;s:4:\"name\";i:1;s:8:\"category\";i:2;s:4:\"icon\";i:3;s:9:\"is_active\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}s:6:\"rating\";s:6:\"4.3333\";s:21:\"active_projects_count\";i:8;s:10:\"rank_score\";d:76;s:11:\"skill_score\";i:70;s:14:\"workload_score\";i:6;s:18:\"has_matching_skill\";b:1;}i:3;a:11:{s:2:\"id\";i:11;s:8:\"fullName\";s:13:\"Carlos Mobile\";s:5:\"email\";s:23:\"carlos@treisadiutor.com\";s:18:\"calendar_connected\";b:0;s:6:\"skills\";O:39:\"Illuminate\\Database\\Eloquent\\Collection\":2:{s:8:\"\0*\0items\";a:5:{i:0;O:16:\"App\\Models\\Skill\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:6:\"skills\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:7:{s:2:\"id\";i:61;s:4:\"name\";s:12:\"React Native\";s:11:\"description\";N;s:8:\"category\";N;s:9:\"is_active\";i:1;s:10:\"created_at\";s:19:\"2025-10-25 06:38:18\";s:10:\"updated_at\";s:19:\"2025-10-25 06:38:18\";}s:11:\"\0*\0original\";a:13:{s:2:\"id\";i:61;s:4:\"name\";s:12:\"React Native\";s:11:\"description\";N;s:8:\"category\";N;s:9:\"is_active\";i:1;s:10:\"created_at\";s:19:\"2025-10-25 06:38:18\";s:10:\"updated_at\";s:19:\"2025-10-25 06:38:18\";s:16:\"pivot_adiutor_id\";i:5;s:14:\"pivot_skill_id\";i:61;s:23:\"pivot_proficiency_level\";s:6:\"expert\";s:22:\"pivot_years_experience\";i:5;s:16:\"pivot_created_at\";s:19:\"2025-10-25 06:38:18\";s:16:\"pivot_updated_at\";s:19:\"2025-10-25 06:38:18\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:1:{s:9:\"is_active\";s:7:\"boolean\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:1:{s:5:\"pivot\";O:44:\"Illuminate\\Database\\Eloquent\\Relations\\Pivot\":37:{s:13:\"\0*\0connection\";N;s:8:\"\0*\0table\";s:14:\"adiutor_skills\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:0;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:6:{s:10:\"adiutor_id\";i:5;s:8:\"skill_id\";i:61;s:17:\"proficiency_level\";s:6:\"expert\";s:16:\"years_experience\";i:5;s:10:\"created_at\";s:19:\"2025-10-25 06:38:18\";s:10:\"updated_at\";s:19:\"2025-10-25 06:38:18\";}s:11:\"\0*\0original\";a:6:{s:10:\"adiutor_id\";i:5;s:8:\"skill_id\";i:61;s:17:\"proficiency_level\";s:6:\"expert\";s:16:\"years_experience\";i:5;s:10:\"created_at\";s:19:\"2025-10-25 06:38:18\";s:10:\"updated_at\";s:19:\"2025-10-25 06:38:18\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:0:{}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:0:{}s:10:\"\0*\0guarded\";a:0:{}s:11:\"pivotParent\";r:102;s:12:\"pivotRelated\";r:163;s:13:\"\0*\0foreignKey\";s:10:\"adiutor_id\";s:13:\"\0*\0relatedKey\";s:8:\"skill_id\";}}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:4:{i:0;s:4:\"name\";i:1;s:8:\"category\";i:2;s:4:\"icon\";i:3;s:9:\"is_active\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}i:1;O:16:\"App\\Models\\Skill\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:6:\"skills\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:7:{s:2:\"id\";i:62;s:4:\"name\";s:7:\"Flutter\";s:11:\"description\";N;s:8:\"category\";N;s:9:\"is_active\";i:1;s:10:\"created_at\";s:19:\"2025-10-25 06:38:18\";s:10:\"updated_at\";s:19:\"2025-10-25 06:38:18\";}s:11:\"\0*\0original\";a:13:{s:2:\"id\";i:62;s:4:\"name\";s:7:\"Flutter\";s:11:\"description\";N;s:8:\"category\";N;s:9:\"is_active\";i:1;s:10:\"created_at\";s:19:\"2025-10-25 06:38:18\";s:10:\"updated_at\";s:19:\"2025-10-25 06:38:18\";s:16:\"pivot_adiutor_id\";i:5;s:14:\"pivot_skill_id\";i:62;s:23:\"pivot_proficiency_level\";s:8:\"advanced\";s:22:\"pivot_years_experience\";i:3;s:16:\"pivot_created_at\";s:19:\"2025-10-25 06:38:18\";s:16:\"pivot_updated_at\";s:19:\"2025-10-25 06:38:18\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:1:{s:9:\"is_active\";s:7:\"boolean\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:1:{s:5:\"pivot\";O:44:\"Illuminate\\Database\\Eloquent\\Relations\\Pivot\":37:{s:13:\"\0*\0connection\";N;s:8:\"\0*\0table\";s:14:\"adiutor_skills\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:0;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:6:{s:10:\"adiutor_id\";i:5;s:8:\"skill_id\";i:62;s:17:\"proficiency_level\";s:8:\"advanced\";s:16:\"years_experience\";i:3;s:10:\"created_at\";s:19:\"2025-10-25 06:38:18\";s:10:\"updated_at\";s:19:\"2025-10-25 06:38:18\";}s:11:\"\0*\0original\";a:6:{s:10:\"adiutor_id\";i:5;s:8:\"skill_id\";i:62;s:17:\"proficiency_level\";s:8:\"advanced\";s:16:\"years_experience\";i:3;s:10:\"created_at\";s:19:\"2025-10-25 06:38:18\";s:10:\"updated_at\";s:19:\"2025-10-25 06:38:18\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:0:{}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:0:{}s:10:\"\0*\0guarded\";a:0:{}s:11:\"pivotParent\";r:102;s:12:\"pivotRelated\";r:163;s:13:\"\0*\0foreignKey\";s:10:\"adiutor_id\";s:13:\"\0*\0relatedKey\";s:8:\"skill_id\";}}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:4:{i:0;s:4:\"name\";i:1;s:8:\"category\";i:2;s:4:\"icon\";i:3;s:9:\"is_active\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}i:2;O:16:\"App\\Models\\Skill\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:6:\"skills\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:7:{s:2:\"id\";i:63;s:4:\"name\";s:15:\"iOS Development\";s:11:\"description\";N;s:8:\"category\";N;s:9:\"is_active\";i:1;s:10:\"created_at\";s:19:\"2025-10-25 06:38:18\";s:10:\"updated_at\";s:19:\"2025-10-25 06:38:18\";}s:11:\"\0*\0original\";a:13:{s:2:\"id\";i:63;s:4:\"name\";s:15:\"iOS Development\";s:11:\"description\";N;s:8:\"category\";N;s:9:\"is_active\";i:1;s:10:\"created_at\";s:19:\"2025-10-25 06:38:18\";s:10:\"updated_at\";s:19:\"2025-10-25 06:38:18\";s:16:\"pivot_adiutor_id\";i:5;s:14:\"pivot_skill_id\";i:63;s:23:\"pivot_proficiency_level\";s:12:\"intermediate\";s:22:\"pivot_years_experience\";i:3;s:16:\"pivot_created_at\";s:19:\"2025-10-25 06:38:18\";s:16:\"pivot_updated_at\";s:19:\"2025-10-25 06:38:18\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:1:{s:9:\"is_active\";s:7:\"boolean\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:1:{s:5:\"pivot\";O:44:\"Illuminate\\Database\\Eloquent\\Relations\\Pivot\":37:{s:13:\"\0*\0connection\";N;s:8:\"\0*\0table\";s:14:\"adiutor_skills\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:0;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:6:{s:10:\"adiutor_id\";i:5;s:8:\"skill_id\";i:63;s:17:\"proficiency_level\";s:12:\"intermediate\";s:16:\"years_experience\";i:3;s:10:\"created_at\";s:19:\"2025-10-25 06:38:18\";s:10:\"updated_at\";s:19:\"2025-10-25 06:38:18\";}s:11:\"\0*\0original\";a:6:{s:10:\"adiutor_id\";i:5;s:8:\"skill_id\";i:63;s:17:\"proficiency_level\";s:12:\"intermediate\";s:16:\"years_experience\";i:3;s:10:\"created_at\";s:19:\"2025-10-25 06:38:18\";s:10:\"updated_at\";s:19:\"2025-10-25 06:38:18\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:0:{}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:0:{}s:10:\"\0*\0guarded\";a:0:{}s:11:\"pivotParent\";r:102;s:12:\"pivotRelated\";r:163;s:13:\"\0*\0foreignKey\";s:10:\"adiutor_id\";s:13:\"\0*\0relatedKey\";s:8:\"skill_id\";}}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:4:{i:0;s:4:\"name\";i:1;s:8:\"category\";i:2;s:4:\"icon\";i:3;s:9:\"is_active\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}i:3;O:16:\"App\\Models\\Skill\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:6:\"skills\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:7:{s:2:\"id\";i:64;s:4:\"name\";s:19:\"Android Development\";s:11:\"description\";N;s:8:\"category\";N;s:9:\"is_active\";i:1;s:10:\"created_at\";s:19:\"2025-10-25 06:38:18\";s:10:\"updated_at\";s:19:\"2025-10-25 06:38:18\";}s:11:\"\0*\0original\";a:13:{s:2:\"id\";i:64;s:4:\"name\";s:19:\"Android Development\";s:11:\"description\";N;s:8:\"category\";N;s:9:\"is_active\";i:1;s:10:\"created_at\";s:19:\"2025-10-25 06:38:18\";s:10:\"updated_at\";s:19:\"2025-10-25 06:38:18\";s:16:\"pivot_adiutor_id\";i:5;s:14:\"pivot_skill_id\";i:64;s:23:\"pivot_proficiency_level\";s:8:\"advanced\";s:22:\"pivot_years_experience\";i:4;s:16:\"pivot_created_at\";s:19:\"2025-10-25 06:38:18\";s:16:\"pivot_updated_at\";s:19:\"2025-10-25 06:38:18\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:1:{s:9:\"is_active\";s:7:\"boolean\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:1:{s:5:\"pivot\";O:44:\"Illuminate\\Database\\Eloquent\\Relations\\Pivot\":37:{s:13:\"\0*\0connection\";N;s:8:\"\0*\0table\";s:14:\"adiutor_skills\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:0;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:6:{s:10:\"adiutor_id\";i:5;s:8:\"skill_id\";i:64;s:17:\"proficiency_level\";s:8:\"advanced\";s:16:\"years_experience\";i:4;s:10:\"created_at\";s:19:\"2025-10-25 06:38:18\";s:10:\"updated_at\";s:19:\"2025-10-25 06:38:18\";}s:11:\"\0*\0original\";a:6:{s:10:\"adiutor_id\";i:5;s:8:\"skill_id\";i:64;s:17:\"proficiency_level\";s:8:\"advanced\";s:16:\"years_experience\";i:4;s:10:\"created_at\";s:19:\"2025-10-25 06:38:18\";s:10:\"updated_at\";s:19:\"2025-10-25 06:38:18\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:0:{}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:0:{}s:10:\"\0*\0guarded\";a:0:{}s:11:\"pivotParent\";r:102;s:12:\"pivotRelated\";r:163;s:13:\"\0*\0foreignKey\";s:10:\"adiutor_id\";s:13:\"\0*\0relatedKey\";s:8:\"skill_id\";}}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:4:{i:0;s:4:\"name\";i:1;s:8:\"category\";i:2;s:4:\"icon\";i:3;s:9:\"is_active\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}i:4;O:16:\"App\\Models\\Skill\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:6:\"skills\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:7:{s:2:\"id\";i:65;s:4:\"name\";s:12:\"Mobile UI/UX\";s:11:\"description\";N;s:8:\"category\";N;s:9:\"is_active\";i:1;s:10:\"created_at\";s:19:\"2025-10-25 06:38:18\";s:10:\"updated_at\";s:19:\"2025-10-25 06:38:18\";}s:11:\"\0*\0original\";a:13:{s:2:\"id\";i:65;s:4:\"name\";s:12:\"Mobile UI/UX\";s:11:\"description\";N;s:8:\"category\";N;s:9:\"is_active\";i:1;s:10:\"created_at\";s:19:\"2025-10-25 06:38:18\";s:10:\"updated_at\";s:19:\"2025-10-25 06:38:18\";s:16:\"pivot_adiutor_id\";i:5;s:14:\"pivot_skill_id\";i:65;s:23:\"pivot_proficiency_level\";s:8:\"advanced\";s:22:\"pivot_years_experience\";i:5;s:16:\"pivot_created_at\";s:19:\"2025-10-25 06:38:18\";s:16:\"pivot_updated_at\";s:19:\"2025-10-25 06:38:18\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:1:{s:9:\"is_active\";s:7:\"boolean\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:1:{s:5:\"pivot\";O:44:\"Illuminate\\Database\\Eloquent\\Relations\\Pivot\":37:{s:13:\"\0*\0connection\";N;s:8:\"\0*\0table\";s:14:\"adiutor_skills\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:0;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:6:{s:10:\"adiutor_id\";i:5;s:8:\"skill_id\";i:65;s:17:\"proficiency_level\";s:8:\"advanced\";s:16:\"years_experience\";i:5;s:10:\"created_at\";s:19:\"2025-10-25 06:38:18\";s:10:\"updated_at\";s:19:\"2025-10-25 06:38:18\";}s:11:\"\0*\0original\";a:6:{s:10:\"adiutor_id\";i:5;s:8:\"skill_id\";i:65;s:17:\"proficiency_level\";s:8:\"advanced\";s:16:\"years_experience\";i:5;s:10:\"created_at\";s:19:\"2025-10-25 06:38:18\";s:10:\"updated_at\";s:19:\"2025-10-25 06:38:18\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:0:{}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:0:{}s:10:\"\0*\0guarded\";a:0:{}s:11:\"pivotParent\";r:102;s:12:\"pivotRelated\";r:163;s:13:\"\0*\0foreignKey\";s:10:\"adiutor_id\";s:13:\"\0*\0relatedKey\";s:8:\"skill_id\";}}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:4:{i:0;s:4:\"name\";i:1;s:8:\"category\";i:2;s:4:\"icon\";i:3;s:9:\"is_active\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}s:6:\"rating\";s:6:\"4.0000\";s:21:\"active_projects_count\";i:1;s:10:\"rank_score\";d:54;s:11:\"skill_score\";d:30;s:14:\"workload_score\";i:24;s:18:\"has_matching_skill\";b:1;}i:4;a:11:{s:2:\"id\";i:9;s:8:\"fullName\";s:4:\"Lena\";s:5:\"email\";s:24:\"michael@treisadiutor.com\";s:18:\"calendar_connected\";b:0;s:6:\"skills\";O:39:\"Illuminate\\Database\\Eloquent\\Collection\":2:{s:8:\"\0*\0items\";a:5:{i:0;O:16:\"App\\Models\\Skill\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:6:\"skills\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:7:{s:2:\"id\";i:3;s:4:\"name\";s:6:\"Python\";s:11:\"description\";N;s:8:\"category\";s:11:\"Programming\";s:9:\"is_active\";i:1;s:10:\"created_at\";s:19:\"2025-10-25 06:38:12\";s:10:\"updated_at\";s:19:\"2025-10-25 06:38:12\";}s:11:\"\0*\0original\";a:13:{s:2:\"id\";i:3;s:4:\"name\";s:6:\"Python\";s:11:\"description\";N;s:8:\"category\";s:11:\"Programming\";s:9:\"is_active\";i:1;s:10:\"created_at\";s:19:\"2025-10-25 06:38:12\";s:10:\"updated_at\";s:19:\"2025-10-25 06:38:12\";s:16:\"pivot_adiutor_id\";i:3;s:14:\"pivot_skill_id\";i:3;s:23:\"pivot_proficiency_level\";s:6:\"expert\";s:22:\"pivot_years_experience\";i:7;s:16:\"pivot_created_at\";s:19:\"2025-10-25 06:38:18\";s:16:\"pivot_updated_at\";s:19:\"2025-10-25 06:38:18\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:1:{s:9:\"is_active\";s:7:\"boolean\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:1:{s:5:\"pivot\";O:44:\"Illuminate\\Database\\Eloquent\\Relations\\Pivot\":37:{s:13:\"\0*\0connection\";N;s:8:\"\0*\0table\";s:14:\"adiutor_skills\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:0;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:6:{s:10:\"adiutor_id\";i:3;s:8:\"skill_id\";i:3;s:17:\"proficiency_level\";s:6:\"expert\";s:16:\"years_experience\";i:7;s:10:\"created_at\";s:19:\"2025-10-25 06:38:18\";s:10:\"updated_at\";s:19:\"2025-10-25 06:38:18\";}s:11:\"\0*\0original\";a:6:{s:10:\"adiutor_id\";i:3;s:8:\"skill_id\";i:3;s:17:\"proficiency_level\";s:6:\"expert\";s:16:\"years_experience\";i:7;s:10:\"created_at\";s:19:\"2025-10-25 06:38:18\";s:10:\"updated_at\";s:19:\"2025-10-25 06:38:18\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:0:{}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:0:{}s:10:\"\0*\0guarded\";a:0:{}s:11:\"pivotParent\";r:102;s:12:\"pivotRelated\";r:163;s:13:\"\0*\0foreignKey\";s:10:\"adiutor_id\";s:13:\"\0*\0relatedKey\";s:8:\"skill_id\";}}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:4:{i:0;s:4:\"name\";i:1;s:8:\"category\";i:2;s:4:\"icon\";i:3;s:9:\"is_active\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}i:1;O:16:\"App\\Models\\Skill\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:6:\"skills\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:7:{s:2:\"id\";i:56;s:4:\"name\";s:6:\"Django\";s:11:\"description\";N;s:8:\"category\";N;s:9:\"is_active\";i:1;s:10:\"created_at\";s:19:\"2025-10-25 06:38:18\";s:10:\"updated_at\";s:19:\"2025-10-25 06:38:18\";}s:11:\"\0*\0original\";a:13:{s:2:\"id\";i:56;s:4:\"name\";s:6:\"Django\";s:11:\"description\";N;s:8:\"category\";N;s:9:\"is_active\";i:1;s:10:\"created_at\";s:19:\"2025-10-25 06:38:18\";s:10:\"updated_at\";s:19:\"2025-10-25 06:38:18\";s:16:\"pivot_adiutor_id\";i:3;s:14:\"pivot_skill_id\";i:56;s:23:\"pivot_proficiency_level\";s:6:\"expert\";s:22:\"pivot_years_experience\";i:6;s:16:\"pivot_created_at\";s:19:\"2025-10-25 06:38:18\";s:16:\"pivot_updated_at\";s:19:\"2025-10-25 06:38:18\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:1:{s:9:\"is_active\";s:7:\"boolean\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:1:{s:5:\"pivot\";O:44:\"Illuminate\\Database\\Eloquent\\Relations\\Pivot\":37:{s:13:\"\0*\0connection\";N;s:8:\"\0*\0table\";s:14:\"adiutor_skills\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:0;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:6:{s:10:\"adiutor_id\";i:3;s:8:\"skill_id\";i:56;s:17:\"proficiency_level\";s:6:\"expert\";s:16:\"years_experience\";i:6;s:10:\"created_at\";s:19:\"2025-10-25 06:38:18\";s:10:\"updated_at\";s:19:\"2025-10-25 06:38:18\";}s:11:\"\0*\0original\";a:6:{s:10:\"adiutor_id\";i:3;s:8:\"skill_id\";i:56;s:17:\"proficiency_level\";s:6:\"expert\";s:16:\"years_experience\";i:6;s:10:\"created_at\";s:19:\"2025-10-25 06:38:18\";s:10:\"updated_at\";s:19:\"2025-10-25 06:38:18\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:0:{}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:0:{}s:10:\"\0*\0guarded\";a:0:{}s:11:\"pivotParent\";r:102;s:12:\"pivotRelated\";r:163;s:13:\"\0*\0foreignKey\";s:10:\"adiutor_id\";s:13:\"\0*\0relatedKey\";s:8:\"skill_id\";}}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:4:{i:0;s:4:\"name\";i:1;s:8:\"category\";i:2;s:4:\"icon\";i:3;s:9:\"is_active\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}i:2;O:16:\"App\\Models\\Skill\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:6:\"skills\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:7:{s:2:\"id\";i:15;s:4:\"name\";s:10:\"PostgreSQL\";s:11:\"description\";N;s:8:\"category\";s:8:\"Database\";s:9:\"is_active\";i:1;s:10:\"created_at\";s:19:\"2025-10-25 06:38:12\";s:10:\"updated_at\";s:19:\"2025-10-25 06:38:12\";}s:11:\"\0*\0original\";a:13:{s:2:\"id\";i:15;s:4:\"name\";s:10:\"PostgreSQL\";s:11:\"description\";N;s:8:\"category\";s:8:\"Database\";s:9:\"is_active\";i:1;s:10:\"created_at\";s:19:\"2025-10-25 06:38:12\";s:10:\"updated_at\";s:19:\"2025-10-25 06:38:12\";s:16:\"pivot_adiutor_id\";i:3;s:14:\"pivot_skill_id\";i:15;s:23:\"pivot_proficiency_level\";s:6:\"expert\";s:22:\"pivot_years_experience\";i:7;s:16:\"pivot_created_at\";s:19:\"2025-10-25 06:38:18\";s:16:\"pivot_updated_at\";s:19:\"2025-10-25 06:38:18\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:1:{s:9:\"is_active\";s:7:\"boolean\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:1:{s:5:\"pivot\";O:44:\"Illuminate\\Database\\Eloquent\\Relations\\Pivot\":37:{s:13:\"\0*\0connection\";N;s:8:\"\0*\0table\";s:14:\"adiutor_skills\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:0;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:6:{s:10:\"adiutor_id\";i:3;s:8:\"skill_id\";i:15;s:17:\"proficiency_level\";s:6:\"expert\";s:16:\"years_experience\";i:7;s:10:\"created_at\";s:19:\"2025-10-25 06:38:18\";s:10:\"updated_at\";s:19:\"2025-10-25 06:38:18\";}s:11:\"\0*\0original\";a:6:{s:10:\"adiutor_id\";i:3;s:8:\"skill_id\";i:15;s:17:\"proficiency_level\";s:6:\"expert\";s:16:\"years_experience\";i:7;s:10:\"created_at\";s:19:\"2025-10-25 06:38:18\";s:10:\"updated_at\";s:19:\"2025-10-25 06:38:18\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:0:{}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:0:{}s:10:\"\0*\0guarded\";a:0:{}s:11:\"pivotParent\";r:102;s:12:\"pivotRelated\";r:163;s:13:\"\0*\0foreignKey\";s:10:\"adiutor_id\";s:13:\"\0*\0relatedKey\";s:8:\"skill_id\";}}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:4:{i:0;s:4:\"name\";i:1;s:8:\"category\";i:2;s:4:\"icon\";i:3;s:9:\"is_active\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}i:3;O:16:\"App\\Models\\Skill\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:6:\"skills\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:7:{s:2:\"id\";i:29;s:4:\"name\";s:3:\"AWS\";s:11:\"description\";N;s:8:\"category\";s:6:\"DevOps\";s:9:\"is_active\";i:1;s:10:\"created_at\";s:19:\"2025-10-25 06:38:12\";s:10:\"updated_at\";s:19:\"2025-10-25 06:38:12\";}s:11:\"\0*\0original\";a:13:{s:2:\"id\";i:29;s:4:\"name\";s:3:\"AWS\";s:11:\"description\";N;s:8:\"category\";s:6:\"DevOps\";s:9:\"is_active\";i:1;s:10:\"created_at\";s:19:\"2025-10-25 06:38:12\";s:10:\"updated_at\";s:19:\"2025-10-25 06:38:12\";s:16:\"pivot_adiutor_id\";i:3;s:14:\"pivot_skill_id\";i:29;s:23:\"pivot_proficiency_level\";s:8:\"advanced\";s:22:\"pivot_years_experience\";i:5;s:16:\"pivot_created_at\";s:19:\"2025-10-25 06:38:18\";s:16:\"pivot_updated_at\";s:19:\"2025-10-25 06:38:18\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:1:{s:9:\"is_active\";s:7:\"boolean\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:1:{s:5:\"pivot\";O:44:\"Illuminate\\Database\\Eloquent\\Relations\\Pivot\":37:{s:13:\"\0*\0connection\";N;s:8:\"\0*\0table\";s:14:\"adiutor_skills\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:0;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:6:{s:10:\"adiutor_id\";i:3;s:8:\"skill_id\";i:29;s:17:\"proficiency_level\";s:8:\"advanced\";s:16:\"years_experience\";i:5;s:10:\"created_at\";s:19:\"2025-10-25 06:38:18\";s:10:\"updated_at\";s:19:\"2025-10-25 06:38:18\";}s:11:\"\0*\0original\";a:6:{s:10:\"adiutor_id\";i:3;s:8:\"skill_id\";i:29;s:17:\"proficiency_level\";s:8:\"advanced\";s:16:\"years_experience\";i:5;s:10:\"created_at\";s:19:\"2025-10-25 06:38:18\";s:10:\"updated_at\";s:19:\"2025-10-25 06:38:18\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:0:{}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:0:{}s:10:\"\0*\0guarded\";a:0:{}s:11:\"pivotParent\";r:102;s:12:\"pivotRelated\";r:163;s:13:\"\0*\0foreignKey\";s:10:\"adiutor_id\";s:13:\"\0*\0relatedKey\";s:8:\"skill_id\";}}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:4:{i:0;s:4:\"name\";i:1;s:8:\"category\";i:2;s:4:\"icon\";i:3;s:9:\"is_active\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}i:4;O:16:\"App\\Models\\Skill\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:6:\"skills\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:7:{s:2:\"id\";i:57;s:4:\"name\";s:15:\"API Development\";s:11:\"description\";N;s:8:\"category\";N;s:9:\"is_active\";i:1;s:10:\"created_at\";s:19:\"2025-10-25 06:38:18\";s:10:\"updated_at\";s:19:\"2025-10-25 06:38:18\";}s:11:\"\0*\0original\";a:13:{s:2:\"id\";i:57;s:4:\"name\";s:15:\"API Development\";s:11:\"description\";N;s:8:\"category\";N;s:9:\"is_active\";i:1;s:10:\"created_at\";s:19:\"2025-10-25 06:38:18\";s:10:\"updated_at\";s:19:\"2025-10-25 06:38:18\";s:16:\"pivot_adiutor_id\";i:3;s:14:\"pivot_skill_id\";i:57;s:23:\"pivot_proficiency_level\";s:6:\"expert\";s:22:\"pivot_years_experience\";i:7;s:16:\"pivot_created_at\";s:19:\"2025-10-25 06:38:18\";s:16:\"pivot_updated_at\";s:19:\"2025-10-25 06:38:18\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:1:{s:9:\"is_active\";s:7:\"boolean\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:1:{s:5:\"pivot\";O:44:\"Illuminate\\Database\\Eloquent\\Relations\\Pivot\":37:{s:13:\"\0*\0connection\";N;s:8:\"\0*\0table\";s:14:\"adiutor_skills\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:0;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:6:{s:10:\"adiutor_id\";i:3;s:8:\"skill_id\";i:57;s:17:\"proficiency_level\";s:6:\"expert\";s:16:\"years_experience\";i:7;s:10:\"created_at\";s:19:\"2025-10-25 06:38:18\";s:10:\"updated_at\";s:19:\"2025-10-25 06:38:18\";}s:11:\"\0*\0original\";a:6:{s:10:\"adiutor_id\";i:3;s:8:\"skill_id\";i:57;s:17:\"proficiency_level\";s:6:\"expert\";s:16:\"years_experience\";i:7;s:10:\"created_at\";s:19:\"2025-10-25 06:38:18\";s:10:\"updated_at\";s:19:\"2025-10-25 06:38:18\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:0:{}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:0:{}s:10:\"\0*\0guarded\";a:0:{}s:11:\"pivotParent\";r:102;s:12:\"pivotRelated\";r:163;s:13:\"\0*\0foreignKey\";s:10:\"adiutor_id\";s:13:\"\0*\0relatedKey\";s:8:\"skill_id\";}}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:4:{i:0;s:4:\"name\";i:1;s:8:\"category\";i:2;s:4:\"icon\";i:3;s:9:\"is_active\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}s:6:\"rating\";s:6:\"4.7500\";s:21:\"active_projects_count\";i:3;s:10:\"rank_score\";d:18;s:11:\"skill_score\";d:0;s:14:\"workload_score\";i:18;s:18:\"has_matching_skill\";b:0;}i:5;a:11:{s:2:\"id\";i:8;s:8:\"fullName\";s:5:\"Shann\";s:5:\"email\";s:24:\"jessica@treisadiutor.com\";s:18:\"calendar_connected\";b:0;s:6:\"skills\";O:39:\"Illuminate\\Database\\Eloquent\\Collection\":2:{s:8:\"\0*\0items\";a:5:{i:0;O:16:\"App\\Models\\Skill\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:6:\"skills\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:7:{s:2:\"id\";i:26;s:4:\"name\";s:5:\"Figma\";s:11:\"description\";N;s:8:\"category\";s:6:\"Design\";s:9:\"is_active\";i:1;s:10:\"created_at\";s:19:\"2025-10-25 06:38:12\";s:10:\"updated_at\";s:19:\"2025-10-25 06:38:12\";}s:11:\"\0*\0original\";a:13:{s:2:\"id\";i:26;s:4:\"name\";s:5:\"Figma\";s:11:\"description\";N;s:8:\"category\";s:6:\"Design\";s:9:\"is_active\";i:1;s:10:\"created_at\";s:19:\"2025-10-25 06:38:12\";s:10:\"updated_at\";s:19:\"2025-10-25 06:38:12\";s:16:\"pivot_adiutor_id\";i:2;s:14:\"pivot_skill_id\";i:26;s:23:\"pivot_proficiency_level\";s:6:\"expert\";s:22:\"pivot_years_experience\";i:4;s:16:\"pivot_created_at\";s:19:\"2025-12-01 18:39:02\";s:16:\"pivot_updated_at\";s:19:\"2025-12-01 18:39:02\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:1:{s:9:\"is_active\";s:7:\"boolean\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:1:{s:5:\"pivot\";O:44:\"Illuminate\\Database\\Eloquent\\Relations\\Pivot\":37:{s:13:\"\0*\0connection\";N;s:8:\"\0*\0table\";s:14:\"adiutor_skills\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:0;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:6:{s:10:\"adiutor_id\";i:2;s:8:\"skill_id\";i:26;s:17:\"proficiency_level\";s:6:\"expert\";s:16:\"years_experience\";i:4;s:10:\"created_at\";s:19:\"2025-12-01 18:39:02\";s:10:\"updated_at\";s:19:\"2025-12-01 18:39:02\";}s:11:\"\0*\0original\";a:6:{s:10:\"adiutor_id\";i:2;s:8:\"skill_id\";i:26;s:17:\"proficiency_level\";s:6:\"expert\";s:16:\"years_experience\";i:4;s:10:\"created_at\";s:19:\"2025-12-01 18:39:02\";s:10:\"updated_at\";s:19:\"2025-12-01 18:39:02\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:0:{}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:0:{}s:10:\"\0*\0guarded\";a:0:{}s:11:\"pivotParent\";r:102;s:12:\"pivotRelated\";r:163;s:13:\"\0*\0foreignKey\";s:10:\"adiutor_id\";s:13:\"\0*\0relatedKey\";s:8:\"skill_id\";}}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:4:{i:0;s:4:\"name\";i:1;s:8:\"category\";i:2;s:4:\"icon\";i:3;s:9:\"is_active\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}i:1;O:16:\"App\\Models\\Skill\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:6:\"skills\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:7:{s:2:\"id\";i:52;s:4:\"name\";s:9:\"UI Design\";s:11:\"description\";N;s:8:\"category\";N;s:9:\"is_active\";i:1;s:10:\"created_at\";s:19:\"2025-10-25 06:38:18\";s:10:\"updated_at\";s:19:\"2025-10-25 06:38:18\";}s:11:\"\0*\0original\";a:13:{s:2:\"id\";i:52;s:4:\"name\";s:9:\"UI Design\";s:11:\"description\";N;s:8:\"category\";N;s:9:\"is_active\";i:1;s:10:\"created_at\";s:19:\"2025-10-25 06:38:18\";s:10:\"updated_at\";s:19:\"2025-10-25 06:38:18\";s:16:\"pivot_adiutor_id\";i:2;s:14:\"pivot_skill_id\";i:52;s:23:\"pivot_proficiency_level\";s:6:\"expert\";s:22:\"pivot_years_experience\";i:6;s:16:\"pivot_created_at\";s:19:\"2025-12-01 18:39:02\";s:16:\"pivot_updated_at\";s:19:\"2025-12-01 18:39:02\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:1:{s:9:\"is_active\";s:7:\"boolean\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:1:{s:5:\"pivot\";O:44:\"Illuminate\\Database\\Eloquent\\Relations\\Pivot\":37:{s:13:\"\0*\0connection\";N;s:8:\"\0*\0table\";s:14:\"adiutor_skills\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:0;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:6:{s:10:\"adiutor_id\";i:2;s:8:\"skill_id\";i:52;s:17:\"proficiency_level\";s:6:\"expert\";s:16:\"years_experience\";i:6;s:10:\"created_at\";s:19:\"2025-12-01 18:39:02\";s:10:\"updated_at\";s:19:\"2025-12-01 18:39:02\";}s:11:\"\0*\0original\";a:6:{s:10:\"adiutor_id\";i:2;s:8:\"skill_id\";i:52;s:17:\"proficiency_level\";s:6:\"expert\";s:16:\"years_experience\";i:6;s:10:\"created_at\";s:19:\"2025-12-01 18:39:02\";s:10:\"updated_at\";s:19:\"2025-12-01 18:39:02\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:0:{}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:0:{}s:10:\"\0*\0guarded\";a:0:{}s:11:\"pivotParent\";r:102;s:12:\"pivotRelated\";r:163;s:13:\"\0*\0foreignKey\";s:10:\"adiutor_id\";s:13:\"\0*\0relatedKey\";s:8:\"skill_id\";}}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:4:{i:0;s:4:\"name\";i:1;s:8:\"category\";i:2;s:4:\"icon\";i:3;s:9:\"is_active\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}i:2;O:16:\"App\\Models\\Skill\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:6:\"skills\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:7:{s:2:\"id\";i:53;s:4:\"name\";s:11:\"UX Research\";s:11:\"description\";N;s:8:\"category\";N;s:9:\"is_active\";i:1;s:10:\"created_at\";s:19:\"2025-10-25 06:38:18\";s:10:\"updated_at\";s:19:\"2025-10-25 06:38:18\";}s:11:\"\0*\0original\";a:13:{s:2:\"id\";i:53;s:4:\"name\";s:11:\"UX Research\";s:11:\"description\";N;s:8:\"category\";N;s:9:\"is_active\";i:1;s:10:\"created_at\";s:19:\"2025-10-25 06:38:18\";s:10:\"updated_at\";s:19:\"2025-10-25 06:38:18\";s:16:\"pivot_adiutor_id\";i:2;s:14:\"pivot_skill_id\";i:53;s:23:\"pivot_proficiency_level\";s:6:\"expert\";s:22:\"pivot_years_experience\";i:5;s:16:\"pivot_created_at\";s:19:\"2025-12-01 18:39:02\";s:16:\"pivot_updated_at\";s:19:\"2025-12-01 18:39:02\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:1:{s:9:\"is_active\";s:7:\"boolean\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:1:{s:5:\"pivot\";O:44:\"Illuminate\\Database\\Eloquent\\Relations\\Pivot\":37:{s:13:\"\0*\0connection\";N;s:8:\"\0*\0table\";s:14:\"adiutor_skills\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:0;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:6:{s:10:\"adiutor_id\";i:2;s:8:\"skill_id\";i:53;s:17:\"proficiency_level\";s:6:\"expert\";s:16:\"years_experience\";i:5;s:10:\"created_at\";s:19:\"2025-12-01 18:39:02\";s:10:\"updated_at\";s:19:\"2025-12-01 18:39:02\";}s:11:\"\0*\0original\";a:6:{s:10:\"adiutor_id\";i:2;s:8:\"skill_id\";i:53;s:17:\"proficiency_level\";s:6:\"expert\";s:16:\"years_experience\";i:5;s:10:\"created_at\";s:19:\"2025-12-01 18:39:02\";s:10:\"updated_at\";s:19:\"2025-12-01 18:39:02\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:0:{}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:0:{}s:10:\"\0*\0guarded\";a:0:{}s:11:\"pivotParent\";r:102;s:12:\"pivotRelated\";r:163;s:13:\"\0*\0foreignKey\";s:10:\"adiutor_id\";s:13:\"\0*\0relatedKey\";s:8:\"skill_id\";}}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:4:{i:0;s:4:\"name\";i:1;s:8:\"category\";i:2;s:4:\"icon\";i:3;s:9:\"is_active\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}i:3;O:16:\"App\\Models\\Skill\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:6:\"skills\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:7:{s:2:\"id\";i:54;s:4:\"name\";s:20:\"Adobe Creative Suite\";s:11:\"description\";N;s:8:\"category\";N;s:9:\"is_active\";i:1;s:10:\"created_at\";s:19:\"2025-10-25 06:38:18\";s:10:\"updated_at\";s:19:\"2025-10-25 06:38:18\";}s:11:\"\0*\0original\";a:13:{s:2:\"id\";i:54;s:4:\"name\";s:20:\"Adobe Creative Suite\";s:11:\"description\";N;s:8:\"category\";N;s:9:\"is_active\";i:1;s:10:\"created_at\";s:19:\"2025-10-25 06:38:18\";s:10:\"updated_at\";s:19:\"2025-10-25 06:38:18\";s:16:\"pivot_adiutor_id\";i:2;s:14:\"pivot_skill_id\";i:54;s:23:\"pivot_proficiency_level\";s:6:\"expert\";s:22:\"pivot_years_experience\";i:6;s:16:\"pivot_created_at\";s:19:\"2025-12-01 18:39:02\";s:16:\"pivot_updated_at\";s:19:\"2025-12-01 18:39:02\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:1:{s:9:\"is_active\";s:7:\"boolean\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:1:{s:5:\"pivot\";O:44:\"Illuminate\\Database\\Eloquent\\Relations\\Pivot\":37:{s:13:\"\0*\0connection\";N;s:8:\"\0*\0table\";s:14:\"adiutor_skills\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:0;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:6:{s:10:\"adiutor_id\";i:2;s:8:\"skill_id\";i:54;s:17:\"proficiency_level\";s:6:\"expert\";s:16:\"years_experience\";i:6;s:10:\"created_at\";s:19:\"2025-12-01 18:39:02\";s:10:\"updated_at\";s:19:\"2025-12-01 18:39:02\";}s:11:\"\0*\0original\";a:6:{s:10:\"adiutor_id\";i:2;s:8:\"skill_id\";i:54;s:17:\"proficiency_level\";s:6:\"expert\";s:16:\"years_experience\";i:6;s:10:\"created_at\";s:19:\"2025-12-01 18:39:02\";s:10:\"updated_at\";s:19:\"2025-12-01 18:39:02\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:0:{}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:0:{}s:10:\"\0*\0guarded\";a:0:{}s:11:\"pivotParent\";r:102;s:12:\"pivotRelated\";r:163;s:13:\"\0*\0foreignKey\";s:10:\"adiutor_id\";s:13:\"\0*\0relatedKey\";s:8:\"skill_id\";}}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:4:{i:0;s:4:\"name\";i:1;s:8:\"category\";i:2;s:4:\"icon\";i:3;s:9:\"is_active\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}i:4;O:16:\"App\\Models\\Skill\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:6:\"skills\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:7:{s:2:\"id\";i:55;s:4:\"name\";s:11:\"Prototyping\";s:11:\"description\";N;s:8:\"category\";N;s:9:\"is_active\";i:1;s:10:\"created_at\";s:19:\"2025-10-25 06:38:18\";s:10:\"updated_at\";s:19:\"2025-10-25 06:38:18\";}s:11:\"\0*\0original\";a:13:{s:2:\"id\";i:55;s:4:\"name\";s:11:\"Prototyping\";s:11:\"description\";N;s:8:\"category\";N;s:9:\"is_active\";i:1;s:10:\"created_at\";s:19:\"2025-10-25 06:38:18\";s:10:\"updated_at\";s:19:\"2025-10-25 06:38:18\";s:16:\"pivot_adiutor_id\";i:2;s:14:\"pivot_skill_id\";i:55;s:23:\"pivot_proficiency_level\";s:8:\"advanced\";s:22:\"pivot_years_experience\";i:5;s:16:\"pivot_created_at\";s:19:\"2025-12-01 18:39:02\";s:16:\"pivot_updated_at\";s:19:\"2025-12-01 18:39:02\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:1:{s:9:\"is_active\";s:7:\"boolean\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:1:{s:5:\"pivot\";O:44:\"Illuminate\\Database\\Eloquent\\Relations\\Pivot\":37:{s:13:\"\0*\0connection\";N;s:8:\"\0*\0table\";s:14:\"adiutor_skills\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:0;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:6:{s:10:\"adiutor_id\";i:2;s:8:\"skill_id\";i:55;s:17:\"proficiency_level\";s:8:\"advanced\";s:16:\"years_experience\";i:5;s:10:\"created_at\";s:19:\"2025-12-01 18:39:02\";s:10:\"updated_at\";s:19:\"2025-12-01 18:39:02\";}s:11:\"\0*\0original\";a:6:{s:10:\"adiutor_id\";i:2;s:8:\"skill_id\";i:55;s:17:\"proficiency_level\";s:8:\"advanced\";s:16:\"years_experience\";i:5;s:10:\"created_at\";s:19:\"2025-12-01 18:39:02\";s:10:\"updated_at\";s:19:\"2025-12-01 18:39:02\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:0:{}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:0:{}s:10:\"\0*\0guarded\";a:0:{}s:11:\"pivotParent\";r:102;s:12:\"pivotRelated\";r:163;s:13:\"\0*\0foreignKey\";s:10:\"adiutor_id\";s:13:\"\0*\0relatedKey\";s:8:\"skill_id\";}}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:4:{i:0;s:4:\"name\";i:1;s:8:\"category\";i:2;s:4:\"icon\";i:3;s:9:\"is_active\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}s:6:\"rating\";s:6:\"4.3333\";s:21:\"active_projects_count\";i:7;s:10:\"rank_score\";d:6;s:11:\"skill_score\";d:0;s:14:\"workload_score\";i:6;s:18:\"has_matching_skill\";b:0;}}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}', 1764941132);

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `client_profiles`
--

CREATE TABLE `client_profiles` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `company_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `company_description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `industry` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `company_size` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `bio` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `website` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `linkedin` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `twitter` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `preferred_contact_methods` json DEFAULT NULL,
  `timezone` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `business_hours` json DEFAULT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `is_verified` tinyint(1) NOT NULL DEFAULT '0',
  `total_projects` int NOT NULL DEFAULT '0',
  `total_spent` decimal(12,2) NOT NULL DEFAULT '0.00',
  `client_type` enum('individual','small_business','enterprise') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'individual',
  `contact_person` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contact_email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contact_phone` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `billing_address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tax_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `client_profiles`
--

INSERT INTO `client_profiles` (`id`, `user_id`, `company_name`, `company_description`, `industry`, `phone`, `company_size`, `address`, `bio`, `website`, `linkedin`, `twitter`, `preferred_contact_methods`, `timezone`, `business_hours`, `notes`, `is_verified`, `total_projects`, `total_spent`, `client_type`, `contact_person`, `contact_email`, `contact_phone`, `billing_address`, `tax_id`, `created_at`, `updated_at`) VALUES
(1, 3, 'TechStartup Inc.', 'A fast-growing tech startup focused on AI solutions for small businesses.', 'Technology', NULL, '11-50', '123 Innovation Drive, Silicon Valley, CA 94025', NULL, 'https://techstartup.com', NULL, NULL, '\"[\\\"email\\\"]\"', NULL, NULL, NULL, 0, 0, 0.00, 'individual', NULL, NULL, NULL, NULL, NULL, '2025-10-25 06:38:15', '2025-10-25 06:38:15'),
(2, 4, 'Creative Design Studio', 'A boutique design agency specializing in brand identity and digital experiences.', 'Design & Marketing', NULL, '2-10', '456 Art District, New York, NY 10001', NULL, 'https://creativedesignstudio.com', NULL, NULL, '\"[\\\"phone\\\"]\"', NULL, NULL, NULL, 0, 0, 0.00, 'individual', NULL, NULL, NULL, NULL, NULL, '2025-10-25 06:38:15', '2025-10-25 06:38:15'),
(3, 5, 'E-commerce Solutions Co.', 'Helping businesses build and scale their online presence with custom e-commerce solutions.', 'E-commerce', NULL, '51-200', '789 Business Park, Austin, TX 78701', NULL, 'https://ecommerceco.com', NULL, NULL, '\"[\\\"email\\\"]\"', NULL, NULL, NULL, 0, 0, 0.00, 'individual', NULL, NULL, NULL, NULL, NULL, '2025-10-25 06:38:15', '2025-10-25 06:38:15'),
(4, 6, 'Help Communities Nonprofit', 'A nonprofit organization focused on community development and education initiatives.', 'Non-profit', NULL, '11-50', '321 Community Street, Denver, CO 80202', NULL, 'https://nonprofithelp.org', NULL, NULL, '\"[\\\"email\\\"]\"', NULL, NULL, NULL, 0, 0, 0.00, 'individual', NULL, NULL, NULL, NULL, NULL, '2025-10-25 06:38:15', '2025-10-25 06:38:15'),
(5, 12, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '[\"email\"]', NULL, NULL, NULL, 0, 0, 0.00, 'individual', NULL, 'markandrewsoliman@outlook.com', NULL, NULL, NULL, '2025-10-25 06:40:16', '2025-10-25 06:40:16'),
(6, 14, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '[\"phone\"]', NULL, NULL, NULL, 0, 0, 0.00, 'individual', NULL, NULL, '+639614736286', NULL, NULL, '2025-10-28 03:05:46', '2025-10-28 03:05:46'),
(7, 15, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '[\"email\"]', NULL, NULL, NULL, 0, 0, 0.00, 'individual', NULL, 'punutez@treisadiutor.com', NULL, NULL, NULL, '2025-10-28 03:36:26', '2025-10-28 03:36:26'),
(8, 16, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '[\"messenger\"]', NULL, NULL, NULL, 0, 0, 0.00, 'individual', NULL, NULL, NULL, NULL, NULL, '2025-10-28 03:48:59', '2025-10-28 03:48:59'),
(9, 17, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '[\"phone\"]', NULL, NULL, NULL, 0, 0, 0.00, 'individual', NULL, NULL, '+1 (265) 112-7798', NULL, NULL, '2025-10-28 03:50:04', '2025-10-28 03:50:04'),
(10, 18, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '[\"messenger\"]', NULL, NULL, NULL, 0, 0, 0.00, 'individual', NULL, NULL, NULL, NULL, NULL, '2025-10-28 03:50:45', '2025-10-28 03:50:45'),
(11, 19, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '[\"phone\"]', NULL, NULL, NULL, 0, 0, 0.00, 'individual', NULL, NULL, '+1 (956) 873-1851', NULL, NULL, '2025-10-28 04:00:30', '2025-10-28 04:00:30'),
(12, 20, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '[\"phone\"]', NULL, NULL, NULL, 0, 0, 0.00, 'individual', NULL, NULL, 'Modi do dolore susci', NULL, NULL, '2025-10-28 04:08:47', '2025-10-28 04:08:47'),
(13, 21, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '[\"phone\"]', NULL, NULL, NULL, 0, 0, 0.00, 'individual', NULL, NULL, 'Quia officia laborio', NULL, NULL, '2025-10-28 04:14:59', '2025-10-28 04:14:59'),
(14, 22, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '[\"messenger\"]', NULL, NULL, NULL, 0, 0, 0.00, 'individual', NULL, NULL, NULL, NULL, NULL, '2025-10-28 04:19:48', '2025-10-28 04:19:48'),
(15, 23, 'Treis Adiutor', NULL, 'Retail', NULL, NULL, 'Dayap, Calauan, Laguna, Philippines', NULL, NULL, NULL, NULL, '[\"email\"]', NULL, NULL, NULL, 0, 0, 0.00, 'individual', NULL, 'samdm@treisadiutor.com', '+639614736286', NULL, NULL, '2025-10-28 22:22:15', '2025-10-29 00:54:44'),
(16, 25, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '[\"email\"]', NULL, NULL, NULL, 0, 0, 0.00, 'individual', NULL, 'hesyv92aa@treisadiutor.com', NULL, NULL, NULL, '2025-10-29 08:38:36', '2025-10-29 08:38:36'),
(19, 29, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '[\"email\"]', NULL, NULL, NULL, 0, 0, 0.00, 'individual', NULL, 'lenatheresequizon04@gmail.com', NULL, NULL, NULL, '2025-10-29 16:49:48', '2025-10-29 16:49:48'),
(20, 32, 'LSPU CCS', NULL, 'Marketing', NULL, NULL, 'Secret', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0.00, 'individual', NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-12 02:04:38');

-- --------------------------------------------------------

--
-- Table structure for table `conversations`
--

CREATE TABLE `conversations` (
  `id` bigint UNSIGNED NOT NULL,
  `conversation_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `project_id` bigint UNSIGNED NOT NULL,
  `client_id` bigint UNSIGNED NOT NULL,
  `last_message_id` bigint UNSIGNED DEFAULT NULL,
  `last_message_at` timestamp NULL DEFAULT NULL,
  `unread_count_client` int NOT NULL DEFAULT '0',
  `unread_count_admin` int NOT NULL DEFAULT '0',
  `is_archived` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `conversations`
--

INSERT INTO `conversations` (`id`, `conversation_id`, `project_id`, `client_id`, `last_message_id`, `last_message_at`, `unread_count_client`, `unread_count_admin`, `is_archived`, `created_at`, `updated_at`) VALUES
(1, 'project_6', 6, 23, NULL, NULL, 0, 0, 0, '2025-10-29 00:09:25', '2025-10-29 00:09:25'),
(2, 'project_7', 7, 25, NULL, NULL, 0, 0, 0, '2025-10-29 08:45:39', '2025-10-29 08:45:39'),
(3, 'project_8', 8, 13, 11, '2025-11-12 01:31:28', 2, 0, 0, '2025-10-29 15:58:00', '2025-11-12 01:31:28'),
(5, 'project_16', 16, 32, 12, '2025-12-01 17:53:47', 1, 0, 0, '2025-12-01 17:51:16', '2025-12-01 17:53:47'),
(6, 'project_18', 18, 3, NULL, NULL, 0, 0, 0, '2025-12-01 18:04:16', '2025-12-01 18:04:16'),
(7, 'project_12', 12, 3, NULL, NULL, 0, 0, 0, '2025-12-01 18:22:10', '2025-12-01 18:22:10'),
(8, 'project_1', 1, 4, NULL, NULL, 0, 0, 0, '2025-12-03 16:48:58', '2025-12-03 16:48:58'),
(9, 'project_24', 24, 3, 30, '2025-12-06 06:18:14', 0, 2, 0, '2025-12-06 06:17:56', '2025-12-06 06:18:14');

-- --------------------------------------------------------

--
-- Table structure for table `coupons`
--

CREATE TABLE `coupons` (
  `id` bigint UNSIGNED NOT NULL,
  `code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `discount_type` enum('percentage','fixed_amount') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `discount_value` decimal(10,2) NOT NULL,
  `max_discount_amount` decimal(10,2) DEFAULT NULL,
  `min_purchase_amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `coupon_type` enum('public','user_specific','request_specific') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `specific_user_id` bigint UNSIGNED DEFAULT NULL,
  `specific_request_id` bigint UNSIGNED DEFAULT NULL,
  `max_total_uses` int DEFAULT NULL,
  `max_uses_per_user` int NOT NULL DEFAULT '1',
  `current_uses` int NOT NULL DEFAULT '0',
  `valid_from` timestamp NULL DEFAULT NULL,
  `valid_until` timestamp NULL DEFAULT NULL,
  `status` enum('active','inactive','expired') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `created_by` bigint UNSIGNED NOT NULL,
  `admin_notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `stackable_with_loyalty_tier` tinyint(1) NOT NULL DEFAULT '0',
  `stackable_with_points` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `coupons`
--

INSERT INTO `coupons` (`id`, `code`, `name`, `description`, `discount_type`, `discount_value`, `max_discount_amount`, `min_purchase_amount`, `coupon_type`, `specific_user_id`, `specific_request_id`, `max_total_uses`, `max_uses_per_user`, `current_uses`, `valid_from`, `valid_until`, `status`, `created_by`, `admin_notes`, `stackable_with_loyalty_tier`, `stackable_with_points`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'EK3K-A4J5-BK1Y', 'Damian Kane', 'At ullam et ut incid', 'fixed_amount', 5000.00, 0.00, 20000.00, 'public', NULL, NULL, 20, 1, 0, '2025-11-14 09:44:00', '2025-12-20 09:44:00', 'inactive', 1, 'Laboris do enim in o', 1, 1, '2025-11-14 09:45:25', '2025-11-14 11:10:22', NULL),
(2, 'WELCOME20', 'Welcome 20% Discount', 'Get 20% off your first service', 'percentage', 20.00, NULL, 0.00, 'public', NULL, NULL, 200, 1, 4, '2025-11-14 10:13:00', '2026-12-30 10:13:00', 'active', 1, NULL, 1, 1, '2025-11-14 10:14:57', '2025-12-05 19:27:36', NULL),
(3, 'SAVE1000', '₱1000 Off Service', NULL, 'fixed_amount', 1000.00, NULL, 10000.00, 'public', NULL, NULL, 50, 1, 1, '2025-11-14 11:00:00', '2026-05-14 11:00:00', 'active', 1, NULL, 1, 1, '2025-11-14 11:01:56', '2025-12-01 12:13:54', NULL),
(4, 'VIP50USER', 'VIP 50% Discount', NULL, 'percentage', 50.00, NULL, 0.00, 'user_specific', 18, NULL, 1, 1, 1, '2025-11-14 11:02:00', '2025-12-30 11:02:00', 'expired', 1, NULL, 1, 1, '2025-11-14 11:03:36', '2025-12-05 17:47:38', NULL),
(5, 'PROMO-JAHF5G', 'PROMO Discount #1', NULL, 'percentage', 15.00, NULL, 0.00, 'public', NULL, NULL, NULL, 1, 0, NULL, '2025-12-14 11:07:11', 'active', 1, NULL, 0, 0, '2025-11-14 11:07:11', '2025-11-14 11:07:11', NULL),
(6, 'PROMO-VQ83SD', 'PROMO Discount #2', NULL, 'percentage', 15.00, NULL, 0.00, 'public', NULL, NULL, NULL, 1, 0, NULL, '2025-12-14 11:07:11', 'active', 1, NULL, 0, 0, '2025-11-14 11:07:11', '2025-11-14 11:07:11', NULL),
(7, 'PROMO-662HJ7', 'PROMO Discount #3', NULL, 'percentage', 15.00, NULL, 0.00, 'public', NULL, NULL, NULL, 1, 0, NULL, '2025-12-14 11:07:11', 'active', 1, NULL, 0, 0, '2025-11-14 11:07:12', '2025-11-14 11:07:12', NULL),
(8, 'PROMO-XR4YPU', 'PROMO Discount #4', NULL, 'percentage', 15.00, NULL, 0.00, 'public', NULL, NULL, NULL, 1, 0, NULL, '2025-12-14 11:07:11', 'active', 1, NULL, 0, 0, '2025-11-14 11:07:12', '2025-11-14 11:07:12', NULL),
(9, 'PROMO-7EVG9W', 'PROMO Discount #5', NULL, 'percentage', 15.00, NULL, 0.00, 'public', NULL, NULL, NULL, 1, 0, NULL, '2025-12-14 11:07:11', 'active', 1, NULL, 0, 0, '2025-11-14 11:07:12', '2025-11-14 11:07:12', NULL),
(10, 'PROMO-ZTEQRC', 'PROMO Discount #6', NULL, 'percentage', 15.00, NULL, 0.00, 'public', NULL, NULL, NULL, 1, 0, NULL, '2025-12-14 11:07:11', 'active', 1, NULL, 0, 0, '2025-11-14 11:07:12', '2025-11-14 11:07:12', NULL),
(11, 'PROMO-F4AL8S', 'PROMO Discount #7', NULL, 'percentage', 15.00, NULL, 0.00, 'public', NULL, NULL, NULL, 1, 0, NULL, '2025-12-14 11:07:11', 'active', 1, NULL, 0, 0, '2025-11-14 11:07:12', '2025-11-14 11:07:12', NULL),
(12, 'PROMO-G52KRQ', 'PROMO Discount #8', NULL, 'percentage', 15.00, NULL, 0.00, 'public', NULL, NULL, NULL, 1, 0, NULL, '2025-12-14 11:07:11', 'active', 1, NULL, 0, 0, '2025-11-14 11:07:13', '2025-11-14 11:07:13', NULL),
(13, 'PROMO-USDUGL', 'PROMO Discount #9', NULL, 'percentage', 15.00, NULL, 0.00, 'public', NULL, NULL, NULL, 1, 1, NULL, '2025-12-14 11:07:11', 'active', 1, NULL, 0, 0, '2025-11-14 11:07:13', '2025-12-05 17:38:21', NULL),
(14, 'PROMO-VSBGQQ', 'PROMO Discount #10', NULL, 'percentage', 15.00, NULL, 0.00, 'public', NULL, NULL, NULL, 1, 0, NULL, '2025-12-14 11:07:11', 'active', 1, NULL, 0, 0, '2025-11-14 11:07:13', '2025-11-14 11:07:13', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `coupon_usages`
--

CREATE TABLE `coupon_usages` (
  `id` bigint UNSIGNED NOT NULL,
  `coupon_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `service_request_id` bigint UNSIGNED NOT NULL,
  `payment_id` bigint UNSIGNED DEFAULT NULL,
  `original_amount` decimal(10,2) NOT NULL,
  `discount_amount` decimal(10,2) NOT NULL,
  `final_amount` decimal(10,2) NOT NULL,
  `payment_status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `used_at` timestamp NOT NULL,
  `ip_address` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `coupon_usages`
--

INSERT INTO `coupon_usages` (`id`, `coupon_id`, `user_id`, `service_request_id`, `payment_id`, `original_amount`, `discount_amount`, `final_amount`, `payment_status`, `used_at`, `ip_address`, `user_agent`, `created_at`, `updated_at`) VALUES
(1, 2, 32, 38, NULL, 350000.00, 70000.00, 280000.00, 'completed', '2025-11-14 11:44:34', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-14 11:44:34', '2025-11-14 11:48:02'),
(2, 2, 3, 35, NULL, 500000.00, 100000.00, 400000.00, 'completed', '2025-11-14 12:05:02', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-14 12:05:02', '2025-11-14 12:06:00'),
(3, 2, 18, 40, NULL, 500000.00, 100000.00, 400000.00, 'cancelled', '2025-12-01 11:21:37', '172.19.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-01 11:21:37', '2025-12-01 11:36:32'),
(4, 3, 18, 41, NULL, 500000.00, 1000.00, 499000.00, 'completed', '2025-12-01 12:13:54', '172.19.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-01 12:13:54', '2025-12-01 12:14:37'),
(5, 13, 3, 42, NULL, 500000.00, 75000.00, 425000.00, 'completed', '2025-12-05 17:38:21', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-05 17:38:21', '2025-12-05 17:52:23'),
(6, 4, 3, 43, NULL, 500000.00, 250000.00, 250000.00, 'completed', '2025-12-05 17:47:38', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-05 17:47:38', '2025-12-05 17:53:10'),
(7, 2, 3, 44, NULL, 500000.00, 100000.00, 400000.00, 'pending', '2025-12-05 19:25:13', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-05 19:25:13', '2025-12-05 19:25:13'),
(8, 2, 3, 45, NULL, 500000.00, 100000.00, 400000.00, 'completed', '2025-12-05 19:27:36', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-05 19:27:36', '2025-12-05 19:30:03');

-- --------------------------------------------------------

--
-- Table structure for table `custom_report_templates`
--

CREATE TABLE `custom_report_templates` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `config` json NOT NULL,
  `created_by` bigint UNSIGNED NOT NULL,
  `is_public` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `documents`
--

CREATE TABLE `documents` (
  `documentID` bigint UNSIGNED NOT NULL,
  `documentable_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `documentable_id` bigint UNSIGNED DEFAULT NULL,
  `taskID` bigint UNSIGNED DEFAULT NULL,
  `service_request_id` bigint UNSIGNED DEFAULT NULL,
  `project_id` bigint UNSIGNED DEFAULT NULL,
  `client_id` bigint UNSIGNED DEFAULT NULL,
  `fileName` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `filePath` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `link_url` varchar(2048) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fileType` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `fileSize` bigint UNSIGNED NOT NULL DEFAULT '0',
  `version` int UNSIGNED NOT NULL DEFAULT '1',
  `parent_document_id` bigint UNSIGNED DEFAULT NULL,
  `original_document_id` bigint UNSIGNED DEFAULT NULL,
  `document_type` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `is_public` tinyint(1) NOT NULL DEFAULT '0',
  `is_archived` tinyint(1) NOT NULL DEFAULT '0',
  `is_deliverable` tinyint(1) NOT NULL DEFAULT '0',
  `deliverable_type` enum('file','link') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_approved` tinyint(1) NOT NULL DEFAULT '0',
  `approved_by` bigint UNSIGNED DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `rejection_reason` text COLLATE utf8mb4_unicode_ci,
  `rejected_at` timestamp NULL DEFAULT NULL,
  `uploaded_by` bigint UNSIGNED DEFAULT NULL,
  `uploadedAt` timestamp NULL DEFAULT NULL,
  `verified_by` bigint UNSIGNED DEFAULT NULL,
  `verified_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `documents`
--

INSERT INTO `documents` (`documentID`, `documentable_type`, `documentable_id`, `taskID`, `service_request_id`, `project_id`, `client_id`, `fileName`, `filePath`, `link_url`, `fileType`, `fileSize`, `version`, `parent_document_id`, `original_document_id`, `document_type`, `description`, `is_public`, `is_archived`, `is_deliverable`, `deliverable_type`, `is_approved`, `approved_by`, `approved_at`, `rejection_reason`, `rejected_at`, `uploaded_by`, `uploadedAt`, `verified_by`, `verified_at`, `created_at`, `updated_at`, `deleted_at`) VALUES
(2, NULL, NULL, 13, NULL, NULL, NULL, 'POSTER.png', 'https://a6fe2e85b735f93c074908510fedbfe4.r2.cloudflarestorage.com/cms/documents/unknown/doc-2-POSTER.png', NULL, 'image/png', 4196784, 1, NULL, NULL, NULL, NULL, 0, 0, 0, NULL, 0, NULL, NULL, NULL, NULL, 7, '2025-10-25 06:57:48', NULL, NULL, '2025-10-25 06:57:48', '2025-10-29 09:39:12', NULL),
(3, NULL, NULL, 14, NULL, NULL, NULL, 'TREIS ADIUTOR.pdf', 'https://a6fe2e85b735f93c074908510fedbfe4.r2.cloudflarestorage.com/cms/documents/unknown/doc-3-TREIS ADIUTOR.pdf', NULL, 'application/pdf', 8640354, 1, NULL, NULL, NULL, NULL, 0, 0, 0, NULL, 0, NULL, NULL, NULL, NULL, 7, '2025-10-25 07:13:16', NULL, NULL, '2025-10-25 07:13:16', '2025-10-29 09:39:16', NULL),
(4, NULL, NULL, 15, NULL, NULL, NULL, 'POSTER (1).png', 'https://a6fe2e85b735f93c074908510fedbfe4.r2.cloudflarestorage.com/cms/documents/unknown/doc-4-POSTER (1).png', NULL, 'image/png', 4196784, 1, NULL, NULL, NULL, NULL, 0, 0, 0, NULL, 0, NULL, NULL, NULL, NULL, 7, '2025-10-29 00:06:24', NULL, NULL, '2025-10-29 00:06:24', '2025-10-29 09:39:18', NULL),
(5, NULL, NULL, 16, NULL, NULL, NULL, 'nova-a434a-45ca0110b59a.json', 'https://a6fe2e85b735f93c074908510fedbfe4.r2.cloudflarestorage.com/cms/documents/unknown/doc-5-nova-a434a-45ca0110b59a.json', NULL, 'application/json', 2370, 1, NULL, NULL, NULL, NULL, 0, 0, 0, NULL, 0, NULL, NULL, NULL, NULL, 7, '2025-10-29 08:50:36', NULL, NULL, '2025-10-29 08:50:36', '2025-10-29 09:39:19', NULL),
(6, NULL, NULL, 3, NULL, NULL, NULL, 'POSTER (1).png', 'https://a6fe2e85b735f93c074908510fedbfe4.r2.cloudflarestorage.com/cms/documents/unknown/doc-6-POSTER (1).png', NULL, 'image/png', 4196784, 1, NULL, NULL, NULL, NULL, 0, 0, 0, NULL, 0, NULL, NULL, NULL, NULL, 7, '2025-10-29 09:47:31', NULL, NULL, '2025-10-29 09:47:31', '2025-10-29 09:53:22', NULL),
(7, NULL, NULL, 3, NULL, NULL, NULL, 'nova-a434a-45ca0110b59a.json', 'https://pub-6fd423b7cdc244b4b0b5b3ff3e41f649.r2.dev/documents/2/doc-3-nova-a434a-45ca0110b59a.json', NULL, 'application/json', 2370, 1, NULL, NULL, NULL, NULL, 0, 0, 0, NULL, 0, NULL, NULL, NULL, NULL, 7, '2025-10-29 10:08:19', NULL, NULL, '2025-10-29 10:08:19', '2025-10-29 10:08:19', NULL),
(8, NULL, NULL, 3, NULL, NULL, NULL, 'POSTER (1).png', 'https://pub-6fd423b7cdc244b4b0b5b3ff3e41f649.r2.dev/documents/2/doc-3-poster-1.png', NULL, 'image/png', 4196784, 1, NULL, NULL, NULL, NULL, 0, 0, 0, NULL, 0, NULL, NULL, NULL, NULL, 7, '2025-10-29 10:09:09', NULL, NULL, '2025-10-29 10:09:09', '2025-10-29 10:09:09', NULL),
(9, NULL, NULL, 3, NULL, NULL, NULL, 'download-idNZIam-Y9-1761370281683.zip', 'https://pub-6fd423b7cdc244b4b0b5b3ff3e41f649.r2.dev/documents/2/doc-3-download-idnziam-y9-1761370281683.zip', NULL, 'application/zip', 16026, 1, NULL, NULL, NULL, NULL, 0, 0, 0, NULL, 0, NULL, NULL, NULL, NULL, 7, '2025-10-29 10:12:53', NULL, NULL, '2025-10-29 10:12:53', '2025-10-29 10:12:53', NULL),
(10, NULL, NULL, 2, NULL, NULL, NULL, 'Server-Based Network (PDU).png', 'https://pub-6fd423b7cdc244b4b0b5b3ff3e41f649.r2.dev/documents/2/doc-2-server-based-network-pdu.png', NULL, 'image/png', 213681, 1, NULL, NULL, NULL, NULL, 0, 0, 0, NULL, 0, NULL, NULL, NULL, NULL, 7, '2025-11-12 08:16:16', NULL, NULL, '2025-11-12 08:16:16', '2025-11-12 08:16:16', NULL),
(11, NULL, NULL, 5, NULL, NULL, NULL, 'drivers_license.png', 'https://pub-6fd423b7cdc244b4b0b5b3ff3e41f649.r2.dev/documents/4/doc-5-drivers-license.png', NULL, 'image/png', 861988, 1, NULL, NULL, NULL, NULL, 0, 0, 0, NULL, 0, NULL, NULL, NULL, NULL, 7, '2025-12-04 22:41:05', NULL, NULL, '2025-12-04 22:41:05', '2025-12-04 22:41:05', NULL),
(12, NULL, NULL, 25, NULL, NULL, NULL, 'Figma Design', '', 'https://www.figma.com/community/file/1035203688168086460/material-3-design-kit', 'link', 0, 1, NULL, NULL, NULL, NULL, 0, 0, 1, 'link', 1, 1, '2025-12-06 08:38:17', NULL, NULL, 7, '2025-12-06 08:04:27', NULL, NULL, '2025-12-06 08:04:27', '2025-12-06 08:38:17', NULL),
(13, NULL, NULL, 25, NULL, NULL, NULL, 'Material Design', '', 'https://www.figma.com/community/file/1035203688168086460/material-3-design-kit', 'link', 0, 1, NULL, NULL, NULL, NULL, 0, 0, 1, 'link', 1, 1, '2025-12-06 08:38:10', NULL, NULL, 7, '2025-12-06 08:06:32', NULL, NULL, '2025-12-06 08:06:32', '2025-12-06 08:38:10', NULL),
(14, NULL, NULL, 25, NULL, NULL, NULL, 'sales_report_20251203 (1).pdf', 'https://pub-6fd423b7cdc244b4b0b5b3ff3e41f649.r2.dev/documents/45/doc-25-sales-report-20251203-1.pdf', NULL, 'application/pdf', 6066, 1, NULL, NULL, NULL, NULL, 0, 0, 1, 'file', 1, 1, '2025-12-06 08:34:44', NULL, NULL, 7, '2025-12-06 08:06:54', NULL, NULL, '2025-12-06 08:06:54', '2025-12-06 08:34:44', NULL),
(15, NULL, NULL, 28, NULL, NULL, NULL, 'Github Repository', '', 'https://github.com/mrkndrwslmn/CMS', 'link', 0, 1, NULL, NULL, NULL, NULL, 0, 0, 1, 'link', 1, 1, '2025-12-06 10:30:20', NULL, NULL, 7, '2025-12-06 10:26:42', NULL, NULL, '2025-12-06 10:26:42', '2025-12-06 10:30:20', NULL),
(16, NULL, NULL, 28, NULL, NULL, NULL, 'Documentation', '', 'https://github.com/mrkndrwslmn/CMS', 'link', 0, 1, NULL, NULL, NULL, NULL, 0, 0, 1, 'link', 1, 1, '2025-12-06 10:30:07', NULL, NULL, 7, '2025-12-06 10:27:39', NULL, NULL, '2025-12-06 10:27:39', '2025-12-06 10:30:07', NULL),
(17, NULL, NULL, 28, NULL, NULL, NULL, 'Bug Fixes Compilations (with code snippets)', '', 'https://github.com/mrkndrwslmn/CMS', 'link', 0, 1, NULL, NULL, NULL, NULL, 0, 0, 1, 'link', 1, 1, '2025-12-06 10:30:00', NULL, NULL, 7, '2025-12-06 10:28:16', NULL, NULL, '2025-12-06 10:28:16', '2025-12-06 10:30:00', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `feedbacks`
--

CREATE TABLE `feedbacks` (
  `id` bigint UNSIGNED NOT NULL,
  `client_id` bigint UNSIGNED NOT NULL,
  `adiutor_id` bigint UNSIGNED DEFAULT NULL,
  `task_id` bigint UNSIGNED DEFAULT NULL,
  `project_id` bigint UNSIGNED DEFAULT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `message` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `rating` int DEFAULT NULL,
  `type` enum('general','service','technical','complaint','suggestion') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'general',
  `status` enum('pending','reviewed','in_progress','resolved','closed') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `priority` enum('low','medium','high','urgent') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'medium',
  `admin_response` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `responded_by` bigint UNSIGNED DEFAULT NULL,
  `responded_at` timestamp NULL DEFAULT NULL,
  `resolved_by` bigint UNSIGNED DEFAULT NULL,
  `resolved_at` timestamp NULL DEFAULT NULL,
  `internal_notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `category` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tags` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `feedbacks`
--

INSERT INTO `feedbacks` (`id`, `client_id`, `adiutor_id`, `task_id`, `project_id`, `title`, `message`, `rating`, `type`, `status`, `priority`, `admin_response`, `responded_by`, `responded_at`, `resolved_by`, `resolved_at`, `internal_notes`, `category`, `tags`, `created_at`, `updated_at`) VALUES
(1, 6, 8, NULL, 1, 'Excellent Service!', 'The team delivered exceptional work on our project. Communication was clear, and the final product exceeded our expectations. Highly recommend!', 5, 'service', 'resolved', 'low', 'Thank you for taking the time to share your experience. We\'re delighted that we exceeded your expectations and look forward to future collaborations!', 2, '2025-10-12 06:38:18', 2, '2025-10-17 06:38:18', NULL, NULL, NULL, '2025-10-24 06:38:18', '2025-10-25 06:38:18'),
(3, 5, 8, NULL, 3, 'Minor Delays', 'The work quality was good, but there were some delays in the delivery schedule. Would appreciate better timeline management.', 3, 'complaint', 'resolved', 'high', 'Thank you for your feedback. We appreciate you bringing this to our attention. We\'re constantly working to improve our services and will take your suggestions into consideration.', 1, '2025-10-08 06:38:18', 1, '2025-10-15 06:38:18', NULL, NULL, NULL, '2025-10-11 06:38:18', '2025-10-25 06:38:18'),
(5, 5, 7, NULL, 1, 'Outstanding Support', 'The post-project support has been amazing. Any issues were resolved quickly and professionally.', 5, 'service', 'resolved', 'low', 'Thank you for your wonderful feedback! We\'re thrilled that you\'re satisfied with our service. Your success is our priority, and we look forward to working with you again.', 2, '2025-10-06 06:38:18', 2, '2025-10-23 06:38:18', NULL, NULL, NULL, '2025-10-09 06:38:18', '2025-10-25 06:38:18'),
(7, 4, 11, NULL, 1, 'Impressed with Quality', 'The attention to detail and code quality is impressive. Our website performs excellently and looks great.', 5, 'service', 'resolved', 'low', 'Thank you for your wonderful feedback! We\'re thrilled that you\'re satisfied with our service. Your success is our priority, and we look forward to working with you again.', 1, '2025-10-24 06:38:18', 1, '2025-10-21 06:38:18', NULL, NULL, NULL, '2025-10-23 06:38:18', '2025-10-25 06:38:18'),
(8, 4, 10, NULL, 2, 'Pricing Concern', 'While the service was good, I feel the pricing was a bit higher than market rates. More transparency in pricing breakdown would help.', 3, 'complaint', 'pending', 'high', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-20 06:38:18', '2025-10-25 06:38:18'),
(9, 3, 8, NULL, 2, 'Responsive and Professional', 'Quick to respond to queries and very professional in all interactions. Made the project smooth and stress-free.', 5, 'general', 'resolved', 'low', 'We greatly appreciate your kind words! It\'s feedback like yours that motivates our team to continue delivering excellent service. Thank you for choosing Treis Adiutor!', 2, '2025-10-19 06:38:18', 2, '2025-10-21 06:38:18', NULL, NULL, NULL, '2025-10-05 06:38:18', '2025-10-25 06:38:18'),
(10, 6, 9, NULL, 4, 'Could Improve UI/UX', 'Functionality is great, but the user interface could use some improvements. Consider hiring a dedicated UX designer.', 4, 'suggestion', 'reviewed', 'medium', 'Thank you for your wonderful feedback! We\'re thrilled that you\'re satisfied with our service. Your success is our priority, and we look forward to working with you again.', 2, '2025-10-06 06:38:18', NULL, NULL, NULL, NULL, NULL, '2025-10-03 06:38:18', '2025-10-25 06:38:18'),
(11, 18, 8, NULL, 20, NULL, 'Overall Experience: 5/5 stars\nQuality of Work: 5/5 stars\nCommunication: 5/5 stars\nTimeliness: 5/5 stars\n\nDetailed Feedback:\nThis adiutor was really helpful.\n\nWould recommend this Adiutor to others.\n[Public Review]', 5, 'service', 'reviewed', 'medium', NULL, NULL, NULL, NULL, NULL, NULL, 'project_completion', NULL, '2025-12-01 14:19:32', '2025-12-01 14:19:32');

-- --------------------------------------------------------

--
-- Table structure for table `group_chats`
--

CREATE TABLE `group_chats` (
  `id` bigint UNSIGNED NOT NULL,
  `project_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('open','archived') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'open',
  `archived_at` timestamp NULL DEFAULT NULL,
  `archived_by` bigint UNSIGNED DEFAULT NULL,
  `last_message_id` bigint UNSIGNED DEFAULT NULL,
  `last_message_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `group_chats`
--

INSERT INTO `group_chats` (`id`, `project_id`, `name`, `status`, `archived_at`, `archived_by`, `last_message_id`, `last_message_at`, `created_at`, `updated_at`) VALUES
(1, 8, 'Tarik Michael', 'open', NULL, NULL, NULL, NULL, '2025-12-01 17:34:34', '2025-12-01 17:34:34'),
(2, 1, 'Complete Brand Identity Package', 'open', NULL, NULL, NULL, NULL, '2025-12-01 17:44:48', '2025-12-01 17:44:48'),
(3, 2, 'Nonprofit Website with Donation System', 'open', NULL, NULL, NULL, NULL, '2025-12-01 17:44:48', '2025-12-01 17:44:48'),
(4, 3, 'E-commerce Integration API', 'open', NULL, NULL, NULL, NULL, '2025-12-01 17:44:48', '2025-12-01 17:44:48'),
(5, 6, 'Digital Strategy Consulting', 'open', NULL, NULL, NULL, NULL, '2025-12-01 17:44:48', '2025-12-01 17:44:48'),
(6, 7, 'Digital Strategy Consulting', 'open', NULL, NULL, NULL, NULL, '2025-12-01 17:44:48', '2025-12-01 17:44:48'),
(7, 10, 'TechStartup Corporate Website', 'open', NULL, NULL, NULL, NULL, '2025-12-01 17:44:48', '2025-12-01 17:44:48'),
(8, 11, 'AI Assistant Mobile App', 'open', NULL, NULL, NULL, NULL, '2025-12-01 17:44:48', '2025-12-01 17:44:48'),
(9, 12, 'Ecommerce 2', 'open', NULL, NULL, 28, '2025-12-01 18:36:39', '2025-12-01 17:44:48', '2025-12-01 18:36:39'),
(10, 16, 'Game Mobile Application', 'open', NULL, NULL, 14, '2025-12-01 18:03:35', '2025-12-01 17:44:48', '2025-12-01 18:03:35'),
(11, 17, 'Graphics Design', 'open', NULL, NULL, NULL, NULL, '2025-12-01 17:44:48', '2025-12-01 17:44:48'),
(12, 18, 'Ecommerce 3', 'open', NULL, NULL, NULL, NULL, '2025-12-01 17:44:48', '2025-12-01 17:44:48'),
(13, 4, 'Ongoing Website Maintenance', 'open', NULL, NULL, NULL, NULL, '2025-12-01 17:46:37', '2025-12-01 17:46:37'),
(14, 5, 'Digital Strategy Consulting', 'open', NULL, NULL, NULL, NULL, '2025-12-01 17:46:37', '2025-12-01 17:46:37'),
(15, 13, 'Website SEO Optimization', 'open', NULL, NULL, NULL, NULL, '2025-12-01 17:46:38', '2025-12-01 17:46:38'),
(16, 14, 'Ecommerce 3', 'open', NULL, NULL, NULL, NULL, '2025-12-01 17:46:38', '2025-12-01 17:46:38'),
(17, 19, 'UNIMERCE – Full E-Commerce Platform Development & Optimization', 'open', NULL, NULL, NULL, NULL, '2025-12-01 17:46:38', '2025-12-01 17:46:38'),
(18, 20, 'Multi-Platform Study Cards System (Web + Android)', 'open', NULL, NULL, NULL, NULL, '2025-12-01 17:46:38', '2025-12-01 17:46:38'),
(19, 21, 'NovaSync Vendor Intelligence Dashboard', 'open', NULL, NULL, NULL, NULL, '2025-12-05 17:17:57', '2025-12-05 17:17:57'),
(20, 22, 'EdgeRelay - Decentralized Content Delivery Network for Small-Scale Creators', 'open', NULL, NULL, NULL, NULL, '2025-12-05 17:47:39', '2025-12-05 17:47:39'),
(21, 23, 'QuantumShield - Zero-Trust Security Orchestration Platform', 'open', NULL, NULL, NULL, NULL, '2025-12-05 19:25:13', '2025-12-05 19:25:13'),
(22, 24, 'SynthAI - Autonomous Code Review and Technical Debt Analyzer', 'open', NULL, NULL, NULL, NULL, '2025-12-05 19:27:36', '2025-12-05 19:27:36');

-- --------------------------------------------------------

--
-- Table structure for table `group_chat_members`
--

CREATE TABLE `group_chat_members` (
  `id` bigint UNSIGNED NOT NULL,
  `group_chat_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `unread_count` int NOT NULL DEFAULT '0',
  `last_read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `group_chat_members`
--

INSERT INTO `group_chat_members` (`id`, `group_chat_id`, `user_id`, `unread_count`, `last_read_at`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 0, '2025-12-01 17:50:47', '2025-12-01 17:34:34', '2025-12-01 17:34:34'),
(2, 1, 2, 0, '2025-12-01 17:34:34', '2025-12-01 17:34:34', '2025-12-01 17:34:34'),
(3, 2, 1, 0, '2025-12-03 16:57:52', '2025-12-01 17:44:48', '2025-12-01 17:44:48'),
(4, 2, 2, 0, '2025-12-01 17:44:48', '2025-12-01 17:44:48', '2025-12-01 17:44:48'),
(5, 3, 1, 0, '2025-12-01 17:44:48', '2025-12-01 17:44:48', '2025-12-01 17:44:48'),
(6, 3, 2, 0, '2025-12-01 17:44:48', '2025-12-01 17:44:48', '2025-12-01 17:44:48'),
(7, 4, 1, 0, '2025-12-01 17:44:48', '2025-12-01 17:44:48', '2025-12-01 17:44:48'),
(8, 4, 2, 0, '2025-12-01 17:44:48', '2025-12-01 17:44:48', '2025-12-01 17:44:48'),
(9, 5, 1, 0, '2025-12-01 17:44:48', '2025-12-01 17:44:48', '2025-12-01 17:44:48'),
(10, 5, 2, 0, '2025-12-01 17:44:48', '2025-12-01 17:44:48', '2025-12-01 17:44:48'),
(11, 6, 1, 0, '2025-12-01 17:44:48', '2025-12-01 17:44:48', '2025-12-01 17:44:48'),
(12, 6, 2, 0, '2025-12-01 17:44:48', '2025-12-01 17:44:48', '2025-12-01 17:44:48'),
(13, 7, 1, 0, '2025-12-01 17:44:48', '2025-12-01 17:44:48', '2025-12-01 17:44:48'),
(14, 7, 2, 0, '2025-12-01 17:44:48', '2025-12-01 17:44:48', '2025-12-01 17:44:48'),
(15, 8, 1, 0, '2025-12-01 17:44:48', '2025-12-01 17:44:48', '2025-12-01 17:44:48'),
(16, 8, 2, 0, '2025-12-01 17:44:48', '2025-12-01 17:44:48', '2025-12-01 17:44:48'),
(17, 9, 1, 0, '2025-12-05 09:22:33', '2025-12-01 17:44:48', '2025-12-01 17:44:48'),
(18, 9, 2, 14, '2025-12-01 17:44:48', '2025-12-01 17:44:48', '2025-12-01 17:44:48'),
(19, 10, 1, 0, '2025-12-03 17:03:26', '2025-12-01 17:44:48', '2025-12-01 17:44:48'),
(20, 10, 2, 2, '2025-12-01 17:44:48', '2025-12-01 17:44:48', '2025-12-01 17:44:48'),
(21, 11, 1, 0, '2025-12-01 17:44:48', '2025-12-01 17:44:48', '2025-12-01 17:44:48'),
(22, 11, 2, 0, '2025-12-01 17:44:48', '2025-12-01 17:44:48', '2025-12-01 17:44:48'),
(23, 12, 1, 0, '2025-12-01 18:53:27', '2025-12-01 17:44:48', '2025-12-01 17:44:48'),
(24, 12, 2, 0, '2025-12-01 17:44:48', '2025-12-01 17:44:48', '2025-12-01 17:44:48'),
(25, 13, 1, 0, '2025-12-01 17:46:37', '2025-12-01 17:46:37', '2025-12-01 17:46:37'),
(26, 13, 2, 0, '2025-12-01 17:46:37', '2025-12-01 17:46:37', '2025-12-01 17:46:37'),
(27, 14, 1, 0, '2025-12-01 17:46:37', '2025-12-01 17:46:37', '2025-12-01 17:46:37'),
(28, 14, 2, 0, '2025-12-01 17:46:37', '2025-12-01 17:46:37', '2025-12-01 17:46:37'),
(29, 15, 1, 0, '2025-12-01 17:46:38', '2025-12-01 17:46:38', '2025-12-01 17:46:38'),
(30, 15, 2, 0, '2025-12-01 17:46:38', '2025-12-01 17:46:38', '2025-12-01 17:46:38'),
(31, 16, 1, 0, '2025-12-01 17:46:38', '2025-12-01 17:46:38', '2025-12-01 17:46:38'),
(32, 16, 2, 0, '2025-12-01 17:46:38', '2025-12-01 17:46:38', '2025-12-01 17:46:38'),
(33, 17, 1, 0, '2025-12-01 17:46:38', '2025-12-01 17:46:38', '2025-12-01 17:46:38'),
(34, 17, 2, 0, '2025-12-01 17:46:38', '2025-12-01 17:46:38', '2025-12-01 17:46:38'),
(35, 18, 1, 0, '2025-12-01 17:46:38', '2025-12-01 17:46:38', '2025-12-01 17:46:38'),
(36, 18, 2, 0, '2025-12-01 17:46:38', '2025-12-01 17:46:38', '2025-12-01 17:46:38'),
(37, 9, 8, 0, '2025-12-01 18:36:53', '2025-12-01 18:21:44', '2025-12-01 18:21:44'),
(38, 12, 7, 0, '2025-12-02 11:45:17', '2025-12-02 11:45:17', '2025-12-02 11:45:17'),
(39, 19, 1, 0, '2025-12-05 17:17:57', '2025-12-05 17:17:57', '2025-12-05 17:17:57'),
(40, 19, 2, 0, '2025-12-05 17:17:57', '2025-12-05 17:17:57', '2025-12-05 17:17:57'),
(41, 20, 1, 0, '2025-12-05 17:47:39', '2025-12-05 17:47:39', '2025-12-05 17:47:39'),
(42, 20, 2, 0, '2025-12-05 17:47:39', '2025-12-05 17:47:39', '2025-12-05 17:47:39'),
(43, 21, 1, 0, '2025-12-05 19:25:13', '2025-12-05 19:25:13', '2025-12-05 19:25:13'),
(44, 21, 2, 0, '2025-12-05 19:25:13', '2025-12-05 19:25:13', '2025-12-05 19:25:13'),
(45, 22, 1, 0, '2025-12-05 19:27:36', '2025-12-05 19:27:36', '2025-12-05 19:27:36'),
(46, 22, 2, 0, '2025-12-05 19:27:36', '2025-12-05 19:27:36', '2025-12-05 19:27:36'),
(47, 22, 7, 0, '2025-12-06 06:09:17', '2025-12-06 06:09:17', '2025-12-06 06:09:17'),
(48, 22, 8, 0, '2025-12-06 06:10:36', '2025-12-06 06:10:36', '2025-12-06 06:10:36');

-- --------------------------------------------------------

--
-- Table structure for table `hour_increase_requests`
--

CREATE TABLE `hour_increase_requests` (
  `id` bigint UNSIGNED NOT NULL,
  `task_id` bigint UNSIGNED DEFAULT NULL COMMENT 'Task for which hours are requested (if task-level)',
  `project_assignment_id` bigint UNSIGNED DEFAULT NULL COMMENT 'Project assignment for which hours are requested (if assignment-level)',
  `adiutor_id` bigint UNSIGNED NOT NULL,
  `project_id` bigint UNSIGNED NOT NULL,
  `current_max_hours` decimal(8,2) NOT NULL COMMENT 'Max hours at time of request',
  `requested_max_hours` decimal(8,2) NOT NULL COMMENT 'New max hours requested',
  `hours_already_tracked` decimal(8,2) NOT NULL COMMENT 'Hours logged at time of request',
  `reason` text COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Justification for requesting more hours',
  `status` enum('pending','approved','rejected') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `reviewed_by` bigint UNSIGNED DEFAULT NULL,
  `reviewed_at` timestamp NULL DEFAULT NULL,
  `review_notes` text COLLATE utf8mb4_unicode_ci COMMENT 'Admin notes when approving/rejecting',
  `approved_hours` decimal(8,2) DEFAULT NULL COMMENT 'Actual hours approved (may differ from requested)',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `queue` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `loyalty_coupons`
--

CREATE TABLE `loyalty_coupons` (
  `id` bigint UNSIGNED NOT NULL,
  `client_id` bigint UNSIGNED NOT NULL,
  `code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `discount_percentage` int NOT NULL DEFAULT '10',
  `is_used` tinyint(1) NOT NULL DEFAULT '0',
  `used_on_project_id` bigint UNSIGNED DEFAULT NULL,
  `used_at` timestamp NULL DEFAULT NULL,
  `notified_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `loyalty_points`
--

CREATE TABLE `loyalty_points` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `total_points` int NOT NULL DEFAULT '0',
  `available_points` int NOT NULL DEFAULT '0',
  `lifetime_earned` int NOT NULL DEFAULT '0',
  `lifetime_redeemed` int NOT NULL DEFAULT '0',
  `tier` enum('bronze','silver','gold','platinum') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'bronze',
  `tier_achieved_at` timestamp NULL DEFAULT NULL,
  `points_to_next_tier` int NOT NULL DEFAULT '5000',
  `last_earned_at` timestamp NULL DEFAULT NULL,
  `last_redeemed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `loyalty_points`
--

INSERT INTO `loyalty_points` (`id`, `user_id`, `total_points`, `available_points`, `lifetime_earned`, `lifetime_redeemed`, `tier`, `tier_achieved_at`, `points_to_next_tier`, `last_earned_at`, `last_redeemed_at`, `created_at`, `updated_at`) VALUES
(1, 32, 56, 56, 56, 0, 'bronze', NULL, 4944, '2025-11-14 11:48:49', NULL, '2025-11-14 11:32:50', '2025-11-14 11:48:49'),
(2, 3, 202, 202, 202, 0, 'bronze', NULL, 4798, '2025-12-05 19:30:02', NULL, '2025-11-14 12:04:45', '2025-12-05 19:30:03'),
(3, 18, 89, 89, 89, 0, 'bronze', NULL, 4911, '2025-12-01 12:14:36', NULL, '2025-12-01 11:18:05', '2025-12-01 12:14:37'),
(4, 14, 0, 0, 0, 0, 'bronze', NULL, 5000, NULL, NULL, '2025-12-03 19:52:04', '2025-12-03 19:52:04');

-- --------------------------------------------------------

--
-- Table structure for table `loyalty_tiers`
--

CREATE TABLE `loyalty_tiers` (
  `id` bigint UNSIGNED NOT NULL,
  `tier` enum('bronze','silver','gold','platinum') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `points_required` int NOT NULL,
  `earning_rate_percentage` int NOT NULL,
  `discount_percentage` int NOT NULL DEFAULT '0',
  `benefits` json DEFAULT NULL,
  `badge_icon` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `badge_color` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `order` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `loyalty_transactions`
--

CREATE TABLE `loyalty_transactions` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `transaction_type` enum('earned','redeemed','expired','adjusted','refunded') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `points` int NOT NULL,
  `balance_before` int NOT NULL,
  `balance_after` int NOT NULL,
  `source` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `service_request_id` bigint UNSIGNED DEFAULT NULL,
  `payment_id` bigint UNSIGNED DEFAULT NULL,
  `coupon_id` bigint UNSIGNED DEFAULT NULL,
  `performed_by` bigint UNSIGNED DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `expiry_warning_sent` tinyint(1) NOT NULL DEFAULT '0',
  `expired_at` timestamp NULL DEFAULT NULL,
  `expired` tinyint(1) NOT NULL DEFAULT '0',
  `expiry_warning_sent_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `loyalty_transactions`
--

INSERT INTO `loyalty_transactions` (`id`, `user_id`, `transaction_type`, `points`, `balance_before`, `balance_after`, `source`, `description`, `service_request_id`, `payment_id`, `coupon_id`, `performed_by`, `expires_at`, `expiry_warning_sent`, `expired_at`, `expired`, `expiry_warning_sent_at`, `created_at`, `updated_at`) VALUES
(1, 32, 'earned', 28, 0, 28, 'payment_completed', 'Payment completed for Graphics Design', 38, NULL, NULL, NULL, NULL, 0, NULL, 0, NULL, '2025-11-14 11:48:01', '2025-11-14 11:48:01'),
(2, 32, 'earned', 28, 28, 56, 'payment_completed', 'Payment completed for Graphics Design', 38, NULL, NULL, NULL, NULL, 0, NULL, 0, NULL, '2025-11-14 11:48:49', '2025-11-14 11:48:49'),
(3, 3, 'earned', 40, 0, 40, 'payment_completed', 'Payment completed for Ecommerce 3', 35, NULL, NULL, NULL, NULL, 0, NULL, 0, NULL, '2025-11-14 12:06:00', '2025-11-14 12:06:00'),
(4, 3, 'earned', 5, 40, 45, 'payment_completed', 'Payment completed for Website SEO Optimization', 9, NULL, NULL, NULL, NULL, 0, NULL, 0, NULL, '2025-11-14 12:12:46', '2025-11-14 12:12:46'),
(5, 18, 'earned', 40, 0, 40, 'payment_completed', 'Payment completed for UNIMERCE – Full E-Commerce Platform Development & Optimization', 40, NULL, NULL, NULL, NULL, 0, NULL, 0, NULL, '2025-12-01 11:35:05', '2025-12-01 11:35:05'),
(6, 18, 'earned', 49, 40, 89, 'payment_completed', 'Payment completed for Multi-Platform Study Cards System (Web + Android)', 41, NULL, NULL, NULL, NULL, 0, NULL, 0, NULL, '2025-12-01 12:14:37', '2025-12-01 12:14:37'),
(10, 3, 'earned', 42, 45, 87, 'payment_completed', 'Payment completed for NovaSync Vendor Intelligence Dashboard', 42, NULL, NULL, NULL, '2026-12-05 17:52:22', 0, NULL, 0, NULL, '2025-12-05 17:52:22', '2025-12-05 17:52:22'),
(11, 3, 'earned', 25, 87, 112, 'payment_completed', 'Payment completed for EdgeRelay - Decentralized Content Delivery Network for Small-Scale Creators', 43, NULL, NULL, NULL, '2026-12-05 17:53:09', 0, NULL, 0, NULL, '2025-12-05 17:53:09', '2025-12-05 17:53:09'),
(12, 3, 'earned', 25, 112, 137, 'payment_completed', 'Payment completed for EdgeRelay - Decentralized Content Delivery Network for Small-Scale Creators', 43, NULL, NULL, NULL, '2026-12-05 17:55:23', 0, NULL, 0, NULL, '2025-12-05 17:55:23', '2025-12-05 17:55:23'),
(13, 3, 'earned', 25, 137, 162, 'payment_completed', 'Payment completed for EdgeRelay - Decentralized Content Delivery Network for Small-Scale Creators', 43, NULL, NULL, NULL, '2026-12-05 17:55:58', 0, NULL, 0, NULL, '2025-12-05 17:55:58', '2025-12-05 17:55:58'),
(14, 3, 'earned', 40, 162, 202, 'payment_completed', 'Payment completed for SynthAI - Autonomous Code Review and Technical Debt Analyzer', 45, NULL, NULL, NULL, '2026-12-05 19:30:02', 0, NULL, 0, NULL, '2025-12-05 19:30:02', '2025-12-05 19:30:02');

-- --------------------------------------------------------

--
-- Table structure for table `meetings`
--

CREATE TABLE `meetings` (
  `id` bigint UNSIGNED NOT NULL,
  `project_id` bigint UNSIGNED NOT NULL,
  `client_id` bigint UNSIGNED NOT NULL,
  `admin_id` bigint UNSIGNED DEFAULT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `requested_date` date NOT NULL,
  `requested_time` time NOT NULL,
  `rescheduled_date` date DEFAULT NULL,
  `rescheduled_time` time DEFAULT NULL,
  `scheduled_date` date DEFAULT NULL,
  `scheduled_time` time DEFAULT NULL,
  `status` enum('pending','approved','rescheduled','rejected','completed','cancelled') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `zoom_meeting_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `zoom_join_url` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `zoom_start_url` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `zoom_password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `admin_notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `meetings`
--

INSERT INTO `meetings` (`id`, `project_id`, `client_id`, `admin_id`, `title`, `description`, `requested_date`, `requested_time`, `rescheduled_date`, `rescheduled_time`, `scheduled_date`, `scheduled_time`, `status`, `zoom_meeting_id`, `zoom_join_url`, `zoom_start_url`, `zoom_password`, `admin_notes`, `created_at`, `updated_at`) VALUES
(1, 6, 23, 1, 'Meeting 1', 'Meeting discussion', '2025-10-30', '00:09:00', NULL, NULL, NULL, NULL, 'rejected', NULL, NULL, NULL, NULL, 'Please reschedule into your most appropriate time on or before November 3', '2025-10-29 00:09:52', '2025-10-29 00:10:38'),
(2, 6, 23, 1, 'Meeting 1', NULL, '2025-11-03', '12:30:00', '2025-11-04', '12:30:00', '2025-11-04', '12:30:00', 'approved', '87161097077', 'https://us05web.zoom.us/j/87161097077?pwd=EM6amT2TSO0WmUgzBMS1MHEJsjF8TX.1', 'https://us05web.zoom.us/s/87161097077?zak=eyJ0eXAiOiJKV1QiLCJzdiI6IjAwMDAwMiIsInptX3NrbSI6InptX28ybSIsImFsZyI6IkhTMjU2In0.eyJpc3MiOiJ3ZWIiLCJjbHQiOjAsIm1udW0iOiI4NzE2MTA5NzA3NyIsImF1ZCI6ImNsaWVudHNtIiwidWlkIjoiay1ZTU15Vl9TdnVxVDhqNkNzMHM2ZyIsInppZCI6IjI4ZjE1NjI4ZjFhMDQxM2Q5NTZhMThmNjVkZGFjZmFmIiwic2siOiIwIiwic3R5IjoxLCJ3Y2QiOiJ1czA1IiwiZXhwIjoxNzYxNjc1NzcyLCJpYXQiOjE3NjE2Njg1NzIsImFpZCI6ImNJeXF2TERHVHdhNHBTWFMyZ3p1amciLCJjaWQiOiIifQ.aQeiCiW5CABOXa8RyHLoFbgs4PDoRQ5nYoxjnacwe-0', 'mjt3V3', NULL, '2025-10-29 00:11:51', '2025-10-29 00:22:53'),
(3, 7, 25, 1, 'Meeting 1', NULL, '2025-10-31', '12:30:00', NULL, NULL, '2025-10-31', '12:30:00', 'approved', '81469977086', 'https://us05web.zoom.us/j/81469977086?pwd=Rv1biTaBOpsyusm799P0nZfsr4dxyC.1', 'https://us05web.zoom.us/s/81469977086?zak=eyJ0eXAiOiJKV1QiLCJzdiI6IjAwMDAwMiIsInptX3NrbSI6InptX28ybSIsImFsZyI6IkhTMjU2In0.eyJpc3MiOiJ3ZWIiLCJjbHQiOjAsIm1udW0iOiI4MTQ2OTk3NzA4NiIsImF1ZCI6ImNsaWVudHNtIiwidWlkIjoiay1ZTU15Vl9TdnVxVDhqNkNzMHM2ZyIsInppZCI6IjNkODQxMTg1NDVjMzQyMTNhZWUzNDg3Mzk4Mzg2MTBhIiwic2siOiIwIiwic3R5IjoxLCJ3Y2QiOiJ1czA1IiwiZXhwIjoxNzYxNzA2MDAyLCJpYXQiOjE3NjE2OTg4MDIsImFpZCI6ImNJeXF2TERHVHdhNHBTWFMyZ3p1amciLCJjaWQiOiIifQ.asLdGg9xX2w0kwRvHhkI5dBrBySR8fJE1DOKMOtYBeU', 'LXD9br', NULL, '2025-10-29 08:46:03', '2025-10-29 08:46:42');

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` bigint UNSIGNED NOT NULL,
  `conversation_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `group_chat_id` bigint UNSIGNED DEFAULT NULL,
  `sender_id` bigint UNSIGNED NOT NULL,
  `recipient_id` bigint UNSIGNED DEFAULT NULL,
  `subject` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `message` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `attachments` json DEFAULT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT '0',
  `status` enum('sent','delivered','read') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'sent',
  `message_type` enum('general','project','task','system') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'general',
  `project_id` bigint UNSIGNED DEFAULT NULL,
  `task_id` bigint UNSIGNED DEFAULT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `delivered_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`id`, `conversation_id`, `group_chat_id`, `sender_id`, `recipient_id`, `subject`, `message`, `attachments`, `is_read`, `status`, `message_type`, `project_id`, `task_id`, `read_at`, `delivered_at`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'project_8', NULL, 13, 1, NULL, 'Hello!', NULL, 1, 'read', 'project', 8, NULL, '2025-11-12 01:31:08', NULL, '2025-10-29 15:58:06', '2025-11-12 01:31:08', NULL),
(2, 'project_8', NULL, 13, 1, NULL, 'Hello!', NULL, 1, 'read', 'project', 8, NULL, '2025-11-12 01:31:08', NULL, '2025-10-29 15:58:09', '2025-11-12 01:31:08', NULL),
(10, 'project_8', NULL, 1, 13, NULL, 'hi', NULL, 0, 'sent', 'project', 8, NULL, NULL, NULL, '2025-11-12 01:31:19', '2025-11-12 01:31:19', NULL),
(11, 'project_8', NULL, 1, 13, NULL, 'hi', NULL, 0, 'sent', 'project', 8, NULL, NULL, NULL, '2025-11-12 01:31:28', '2025-11-12 01:31:28', NULL),
(12, 'project_16', NULL, 1, 32, NULL, 'HELLO', NULL, 0, 'sent', 'project', 16, NULL, NULL, NULL, '2025-12-01 17:53:47', '2025-12-01 17:53:47', NULL),
(13, NULL, 10, 1, NULL, NULL, 'hll', NULL, 0, 'sent', 'project', 16, NULL, NULL, NULL, '2025-12-01 18:03:29', '2025-12-01 18:03:29', NULL),
(14, NULL, 10, 1, NULL, NULL, 'hii', NULL, 0, 'sent', 'project', 16, NULL, NULL, NULL, '2025-12-01 18:03:35', '2025-12-01 18:03:35', NULL),
(15, NULL, 9, 1, NULL, NULL, 'hello', NULL, 0, 'sent', 'project', 12, NULL, NULL, NULL, '2025-12-01 18:22:15', '2025-12-01 18:22:15', NULL),
(16, NULL, 9, 8, NULL, NULL, 'hi', NULL, 0, 'sent', 'project', 12, NULL, NULL, NULL, '2025-12-01 18:26:31', '2025-12-01 18:26:31', NULL),
(17, NULL, 9, 8, NULL, NULL, 'how are you', NULL, 0, 'sent', 'project', 12, NULL, NULL, NULL, '2025-12-01 18:26:41', '2025-12-01 18:26:41', NULL),
(18, NULL, 9, 1, NULL, NULL, 'we are good', NULL, 0, 'sent', 'project', 12, NULL, NULL, NULL, '2025-12-01 18:26:50', '2025-12-01 18:26:50', NULL),
(19, NULL, 9, 1, NULL, NULL, 'hello', NULL, 0, 'sent', 'project', 12, NULL, NULL, NULL, '2025-12-01 18:29:51', '2025-12-01 18:29:51', NULL),
(20, NULL, 9, 8, NULL, NULL, 'hi', NULL, 0, 'sent', 'project', 12, NULL, NULL, NULL, '2025-12-01 18:29:57', '2025-12-01 18:29:57', NULL),
(21, NULL, 9, 1, NULL, NULL, 'hello', NULL, 0, 'sent', 'project', 12, NULL, NULL, NULL, '2025-12-01 18:30:02', '2025-12-01 18:30:02', NULL),
(22, NULL, 9, 1, NULL, NULL, 'are you okay?', NULL, 0, 'sent', 'project', 12, NULL, NULL, NULL, '2025-12-01 18:30:05', '2025-12-01 18:30:05', NULL),
(23, NULL, 9, 1, NULL, NULL, 'hi', NULL, 0, 'sent', 'project', 12, NULL, NULL, NULL, '2025-12-01 18:32:55', '2025-12-01 18:32:55', NULL),
(24, NULL, 9, 8, NULL, NULL, 'hello', NULL, 0, 'sent', 'project', 12, NULL, NULL, NULL, '2025-12-01 18:33:02', '2025-12-01 18:33:02', NULL),
(25, NULL, 9, 1, NULL, NULL, 'hiii', NULL, 0, 'sent', 'project', 12, NULL, NULL, NULL, '2025-12-01 18:33:09', '2025-12-01 18:33:09', NULL),
(26, NULL, 9, 1, NULL, NULL, 'hello', NULL, 0, 'sent', 'project', 12, NULL, NULL, NULL, '2025-12-01 18:36:24', '2025-12-01 18:36:24', NULL),
(27, NULL, 9, 1, NULL, NULL, 'how are you', NULL, 0, 'sent', 'project', 12, NULL, NULL, NULL, '2025-12-01 18:36:32', '2025-12-01 18:36:32', NULL),
(28, NULL, 9, 8, NULL, NULL, 'hey', NULL, 0, 'sent', 'project', 12, NULL, NULL, NULL, '2025-12-01 18:36:39', '2025-12-01 18:36:39', NULL),
(29, 'project_24', NULL, 3, 1, NULL, 'Hello!', NULL, 0, 'sent', 'project', 24, NULL, NULL, NULL, '2025-12-06 06:18:09', '2025-12-06 06:18:09', NULL),
(30, 'project_24', NULL, 3, 1, NULL, 'I have a question', NULL, 0, 'sent', 'project', 24, NULL, NULL, NULL, '2025-12-06 06:18:14', '2025-12-06 06:18:14', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2025_10_15_000001_create_users_system', 1),
(2, '2025_10_15_000002_create_profile_system', 1),
(3, '2025_10_15_000003_create_service_request_system', 1),
(4, '2025_10_15_000004_create_project_system', 1),
(5, '2025_10_15_000005_create_task_system', 1),
(6, '2025_10_15_000006_create_payment_system', 1),
(7, '2025_10_15_000007_create_services_system', 1),
(8, '2025_10_15_000008_create_supporting_system_tables', 1),
(9, '2025_10_15_000009_create_feedbacks_table', 1),
(10, '2025_10_15_000010_create_documents_table', 1),
(11, '2025_10_15_054011_create_budget_change_requests_table', 1),
(12, '2025_10_22_000001_enhance_messages_for_firebase_fcm', 1),
(13, '2025_10_22_105118_create_meetings_table', 1),
(14, '2025_10_22_120000_create_milestone_management_system', 1),
(15, '2025_10_22_120001_add_payment_type_to_service_requests', 1),
(16, '2025_10_22_120003_add_milestone_tracking_to_payments', 1),
(17, '2025_10_22_120004_add_phase_foreign_key_to_tasks', 1),
(18, '2025_10_22_130000_create_revision_requests_table', 1),
(19, '2025_10_25_152457_add_priority_to_revision_requests_table', 2),
(20, '2025_10_25_153953_make_document_id_nullable_in_revision_requests_table', 3),
(21, '2025_10_28_095552_add_auth0_fields_to_users_table', 4),
(22, '2025_10_27_005500_create_announcements_table', 5),
(23, '2025_10_27_010754_add_updated_tracking_to_announcements_table', 5),
(24, '2025_10_28_025245_add_starts_at_to_announcements_table', 5),
(25, '2025_10_28_112324_add_timezone_to_users_table', 5),
(26, '2025_10_29_005214_add_missing_fields_to_client_profiles_table', 6),
(27, '2025_10_29_add_file_url_to_request_attachments', 6),
(28, '2025_10_29_162000_fix_missing_columns', 7),
(29, '2025_10_29_175053_create_project_templates_table', 8),
(30, '2025_10_29_175203_create_time_entries_table', 8),
(33, '2025_10_29_184726_add_performance_indexes_for_azure_mysql', 9),
(34, '2025_10_29_185129_add_critical_performance_indexes', 10),
(36, '2025_10_29_175230_create_audit_logs_table', 11),
(37, '2025_10_29_201432_create_custom_report_templates_table', 12),
(38, '2025_10_29_200000_create_showcases_table', 13),
(39, '2025_10_29_200001_create_showcase_screenshots_table', 14),
(40, '2025_10_29_210000_create_tech_stack_table', 15),
(41, '2025_10_29_223015_add_domain_to_tech_stack_table', 16),
(43, '2025_11_10_111809_add_coupon_columns_to_service_requests_table', 18),
(44, '2025_11_10_113154_create_loyalty_coupons_table', 18),
(45, '2025_11_11_000001_create_coupons_table', 19),
(46, '2025_10_30_185848_migrate_auth0_to_firebase_in_users_table', 20),
(47, '2025_11_13_000001_create_coupons_table', 20),
(48, '2025_11_13_000002_create_coupon_usages_table', 20),
(49, '2025_11_13_000003_create_loyalty_system_tables', 20),
(50, '2025_11_13_000004_add_coupon_loyalty_to_service_requests', 20),
(51, '2025_11_13_000005_add_expiry_tracking_to_loyalty_transactions', 21),
(52, '2025_11_14_000001_create_referral_codes_table', 22),
(53, '2025_11_14_000002_create_referrals_table', 22),
(54, '2025_11_14_000003_add_referral_fields_to_users', 22),
(55, '2025_11_14_000004_create_referral_campaigns_table', 22),
(56, '2025_11_14_000001_add_standard_rate_to_adiutor_profiles', 23),
(57, '2025_11_14_000002_add_hourly_rate_to_project_assignments', 23),
(58, '2025_11_14_000003_add_hourly_rate_and_tracking_to_tasks', 23),
(59, '2025_11_14_000004_update_time_entries_for_earnings', 24),
(60, '2025_11_14_000005_create_payouts_table', 24),
(61, '2025_12_01_123220_add_revision_task_tracking_columns', 25),
(62, '2025_12_01_000001_add_referral_credits_system', 26),
(63, '2025_12_01_000001_create_group_chats_table', 27),
(64, '2025_12_01_000002_create_group_chat_members_table', 27),
(65, '2025_12_01_000004_add_group_chat_support_to_messages', 27),
(66, '2025_12_02_111949_add_payment_type_to_project_assignments', 28),
(67, '2025_12_02_150000_add_max_hours_and_billable_tracking', 29),
(68, '2025_12_02_160000_add_admin_adjustment_to_time_entries', 30),
(69, '2025_12_02_170000_add_unified_wallet_system', 31),
(70, '2025_12_02_180000_create_hour_increase_requests_table', 32),
(71, '2025_12_03_160103_create_notes_table', 33),
(72, '2025_12_04_173725_add_firebase_uid_index_to_users_table', 34),
(73, '2025_12_04_180555_add_unique_constraint_to_project_assignments', 35),
(74, '2025_12_04_181228_add_performance_indexes_to_project_tables', 36),
(75, '2025_12_04_181719_add_order_column_to_tasks_table', 37),
(76, '2025_12_04_000001_add_versioning_to_documents_table', 38),
(77, '2025_12_04_000002_add_soft_deletes_to_documents_table', 39),
(78, '2025_12_04_185202_add_financial_performance_indexes', 40),
(79, '2025_12_04_193444_add_communication_system_indexes', 40),
(80, '2025_12_04_200000_add_loyalty_performance_indexes', 41),
(81, '2025_12_04_204630_create_task_deliverables_table', 41),
(82, '2025_12_04_204730_create_subtasks_table', 41),
(83, '2025_12_04_205951_add_deliverable_fields_to_documents_table', 41),
(84, '2025_12_04_220946_add_unique_constraint_to_feedbacks_table', 42),
(85, '2025_12_05_082303_add_service_customization_fields_to_service_requests_table', 43),
(86, '2025_12_05_082321_add_service_customization_fields_to_service_requests_table', 43),
(87, '2025_12_05_193547_add_notification_sent_at_to_payments_table', 44),
(88, '2024_12_05_000001_add_performance_indexes', 45),
(89, '2025_12_05_220000_add_dashboard_performance_indexes', 46);

-- --------------------------------------------------------

--
-- Table structure for table `milestone_payments`
--

CREATE TABLE `milestone_payments` (
  `id` bigint UNSIGNED NOT NULL,
  `milestone_id` bigint UNSIGNED NOT NULL,
  `payment_id` bigint UNSIGNED DEFAULT NULL,
  `service_request_id` bigint UNSIGNED NOT NULL,
  `client_id` bigint UNSIGNED NOT NULL,
  `amount_due` decimal(10,2) NOT NULL,
  `amount_paid` decimal(10,2) NOT NULL DEFAULT '0.00',
  `status` enum('pending','partial','paid','overdue','cancelled') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `due_date` date DEFAULT NULL,
  `paid_at` timestamp NULL DEFAULT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `confirmed_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `milestone_payments`
--

INSERT INTO `milestone_payments` (`id`, `milestone_id`, `payment_id`, `service_request_id`, `client_id`, `amount_due`, `amount_paid`, `status`, `due_date`, `paid_at`, `notes`, `confirmed_by`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 11, 12, 150000.00, 150000.00, 'paid', '2025-11-01', '2025-10-25 06:46:54', NULL, NULL, '2025-10-25 06:46:54', '2025-10-25 06:46:54'),
(2, 2, 2, 11, 12, 200000.00, 200000.00, 'paid', '2025-11-01', '2025-10-25 07:07:04', NULL, NULL, '2025-10-25 07:07:04', '2025-10-25 07:07:04'),
(3, 3, 3, 11, 12, 150000.00, 150000.00, 'paid', '2025-11-01', '2025-10-25 07:08:11', NULL, NULL, '2025-10-25 07:08:11', '2025-10-25 07:08:11'),
(4, 4, 4, 22, 23, 150000.00, 150000.00, 'paid', '2025-11-04', '2025-10-28 23:50:16', NULL, NULL, '2025-10-28 23:50:16', '2025-10-28 23:50:16'),
(5, 4, 4, 22, 23, 150000.00, 150000.00, 'paid', '2025-11-04', '2025-10-28 23:51:30', NULL, NULL, '2025-10-28 23:51:30', '2025-10-28 23:51:30'),
(6, 5, 5, 22, 23, 200000.00, 200000.00, 'paid', '2025-11-04', '2025-10-28 23:52:58', NULL, NULL, '2025-10-28 23:52:58', '2025-10-28 23:52:58'),
(7, 5, 5, 22, 23, 200000.00, 200000.00, 'paid', '2025-11-04', '2025-10-28 23:52:58', NULL, NULL, '2025-10-28 23:52:58', '2025-10-28 23:52:58'),
(8, 6, 6, 22, 23, 150000.00, 150000.00, 'paid', '2025-11-04', '2025-10-28 23:54:58', NULL, NULL, '2025-10-28 23:54:58', '2025-10-28 23:54:58'),
(9, 6, 6, 22, 23, 150000.00, 150000.00, 'paid', '2025-11-04', '2025-10-28 23:55:11', NULL, NULL, '2025-10-28 23:55:11', '2025-10-28 23:55:11'),
(10, 7, 7, 23, 25, 150000.00, 150000.00, 'paid', '2025-11-05', '2025-10-29 08:44:31', NULL, NULL, '2025-10-29 08:44:31', '2025-10-29 08:44:31'),
(11, 10, 8, 29, 13, 150000.00, 150000.00, 'paid', '2025-11-05', '2025-10-29 14:34:24', NULL, NULL, '2025-10-29 14:34:24', '2025-10-29 14:34:24'),
(12, 10, 8, 29, 13, 150000.00, 150000.00, 'paid', '2025-11-05', '2025-10-29 14:34:32', NULL, NULL, '2025-10-29 14:34:32', '2025-10-29 14:34:32');

-- --------------------------------------------------------

--
-- Table structure for table `notes`
--

CREATE TABLE `notes` (
  `id` bigint UNSIGNED NOT NULL,
  `client_id` bigint UNSIGNED NOT NULL,
  `added_by` bigint UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` enum('general','important','reminder','issue') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'general',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `notifiable_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `notifiable_id` bigint UNSIGNED NOT NULL,
  `data` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `type`, `notifiable_type`, `notifiable_id`, `data`, `read_at`, `created_at`, `updated_at`) VALUES
('026620b8-770f-4039-aa77-f152ae76ad04', 'App\\Notifications\\NewServiceRequestNotification', 'App\\Models\\User', 1, '{\"type\":\"new_service_request\",\"title\":\"New Service Request\",\"message\":\"New service request \'UI\\/UX Design\' submitted by Lena Quizon (new user)\",\"action_url\":\"http:\\/\\/127.0.0.1:8000\\/admin\\/requests\\/31\",\"service_request_id\":31,\"project_name\":\"UI\\/UX Design\",\"user_name\":\"Lena Quizon\",\"is_new_user\":true}', '2025-11-14 10:04:38', '2025-10-29 16:37:37', '2025-10-29 16:37:37'),
('032c363a-cb71-4ec3-a800-68a8445b2368', 'App\\Notifications\\TaskCreatedNotification', 'App\\Models\\User', 2, '{\"type\":\"task_created\",\"task_id\":28,\"task_title\":\"Code Analysis Engine - JavaScript\\/TypeScript Support\",\"project_title\":\"SynthAI - Autonomous Code Review and Technical Debt Analyzer\",\"priority\":\"medium\",\"deadline\":null,\"assigned_to\":\"Mark Andrew Soliman\",\"created_by\":\"Mark Admin\",\"message\":\"New task \'Code Analysis Engine - JavaScript\\/TypeScript Support\' has been created\",\"action_url\":\"http:\\/\\/localhost:8000\\/admin\\/tasks\\/28\",\"icon\":\"plus-square\",\"color\":\"success\"}', NULL, '2025-12-06 09:50:34', '2025-12-06 09:50:34'),
('03a85c61-6bc7-401a-880b-fbcc52621b55', 'App\\Notifications\\TaskCompletedNotification', 'App\\Models\\User', 1, '{\"title\":\"Task Completed\",\"message\":\"Alex Developer has completed task: TASK 2\",\"action_url\":\"http:\\/\\/localhost\\/admin\\/tasks\\/14\",\"task_id\":14,\"task_title\":\"TASK 2\",\"completed_by\":\"Alex Developer\"}', '2025-11-14 10:04:38', '2025-10-28 03:54:40', '2025-10-28 03:54:40'),
('05164f27-becb-4b59-b34a-2b4afc52655f', 'App\\Notifications\\TaskCreatedNotification', 'App\\Models\\User', 2, '{\"type\":\"task_created\",\"task_id\":23,\"task_title\":\"Task 1\",\"project_title\":\"Ecommerce 3\",\"priority\":\"medium\",\"deadline\":null,\"assigned_to\":\"Shann\",\"created_by\":\"Mark Admin\",\"message\":\"New task \'Task 1\' has been created\",\"action_url\":\"http:\\/\\/localhost:8000\\/admin\\/tasks\\/23\",\"icon\":\"plus-square\",\"color\":\"success\"}', NULL, '2025-12-05 09:46:31', '2025-12-05 09:46:31'),
('05f7361f-96db-4088-bfa4-d6eaf918ff45', 'App\\Notifications\\PaymentConfirmedNotification', 'App\\Models\\User', 2, '{\"type\":\"payment_confirmed\",\"title\":\"Payment Confirmed\",\"message\":\"Payment confirmed for \'Digital Strategy Consulting\'. Project created.\",\"action_url\":\"\",\"service_request_id\":23,\"project_name\":\"Digital Strategy Consulting\"}', NULL, '2025-10-29 08:44:35', '2025-10-29 08:44:35'),
('06c1033a-aae9-4bae-a9e0-0aec2035d171', 'App\\Notifications\\NewServiceRequestNotification', 'App\\Models\\User', 1, '{\"type\":\"new_service_request\",\"title\":\"New Service Request\",\"message\":\"New service request \'Serina Cote\' submitted by Cooper Case (new user)\",\"action_url\":\"http:\\/\\/localhost\\/admin\\/requests\\/13\",\"service_request_id\":13,\"project_name\":\"Serina Cote\",\"user_name\":\"Cooper Case\",\"is_new_user\":true}', '2025-11-14 10:04:38', '2025-10-28 03:54:47', '2025-10-28 03:54:47'),
('06c58328-6a2f-4dcd-b956-4c723b113448', 'App\\Notifications\\NewServiceRequestNotification', 'App\\Models\\User', 2, '{\"type\":\"new_service_request\",\"title\":\"New Service Request\",\"message\":\"New service request \'Nayda Harvey\' submitted by Rose Finch\",\"action_url\":\"http:\\/\\/localhost:8000\\/admin\\/requests\\/20\",\"service_request_id\":20,\"project_name\":\"Nayda Harvey\",\"user_name\":\"Rose Finch\",\"is_new_user\":false}', NULL, '2025-10-28 04:18:05', '2025-10-28 04:18:05'),
('0907aa16-50c5-4bce-a939-8fdbc245a903', 'App\\Notifications\\TaskCompletedNotification', 'App\\Models\\User', 2, '{\"title\":\"Task Completed\",\"message\":\"Mark Andrew has completed task: Task 1\",\"action_url\":\"http:\\/\\/localhost:8000\\/admin\\/tasks\\/15\",\"task_id\":15,\"task_title\":\"Task 1\",\"completed_by\":\"Mark Andrew\"}', NULL, '2025-10-29 00:08:28', '2025-10-29 00:08:28'),
('095da064-e354-49b7-94de-f59ed3f9046e', 'App\\Notifications\\TaskCompletedNotification', 'App\\Models\\User', 1, '{\"title\":\"Task Completed\",\"message\":\"Mark Andrew has completed task: Task 1\",\"action_url\":\"http:\\/\\/localhost:8000\\/admin\\/tasks\\/15\",\"task_id\":15,\"task_title\":\"Task 1\",\"completed_by\":\"Mark Andrew\"}', '2025-11-14 10:04:38', '2025-10-29 00:06:34', '2025-10-29 00:06:34'),
('0adbff0d-8d8e-42c8-baf3-e00321bc92b6', 'App\\Notifications\\TaskDeletedNotification', 'App\\Models\\User', 3, '{\"type\":\"task_deleted\",\"task_data\":{\"id\":23,\"title\":\"Task 1\",\"project_title\":\"Ecommerce 3\",\"assigned_to\":\"Shann\",\"status\":\"pending\",\"priority\":\"medium\"},\"deleted_by\":\"Mark Admin\",\"message\":\"Task \'Task 1\' has been deleted\",\"icon\":\"minus-square\",\"color\":\"danger\"}', '2025-12-06 05:35:39', '2025-12-05 22:03:18', '2025-12-05 22:03:18'),
('0bd39adf-4278-4d85-a970-b7cf63c08be8', 'App\\Notifications\\AdiutorRemovedFromProjectNotification', 'App\\Models\\User', 8, '{\"type\":\"adiutor_removed_from_project\",\"project_id\":12,\"project_title\":\"Ecommerce 2\",\"client_name\":\"John Smith\",\"removed_by\":\"Mark Admin\",\"message\":\"You have been removed from project \'Ecommerce 2\'\",\"icon\":\"user-minus\",\"color\":\"warning\"}', '2025-12-01 18:36:50', '2025-12-01 18:21:37', '2025-12-01 18:21:37'),
('0cc8166c-67cb-4744-9870-87576b20e14e', 'App\\Notifications\\FileUploadedNotification', 'App\\Models\\User', 2, '{\"type\":\"file_uploaded\",\"file_name\":\"sales_report_20251203 (1).pdf\",\"task_title\":\"Task 1: Backend Infrastructure & API Foundation\",\"task_id\":25,\"uploaded_by\":\"Mark Andrew Soliman\",\"is_deliverable\":true,\"message\":\"New deliverable \'sales_report_20251203 (1).pdf\' uploaded for task \'Task 1: Backend Infrastructure & API Foundation\'\",\"action_url\":\"http:\\/\\/localhost:8000\\/adiutor\\/tasks\\/25\",\"icon\":\"package\",\"color\":\"success\"}', NULL, '2025-12-06 08:06:54', '2025-12-06 08:06:54'),
('0d11073d-7537-4ee0-9149-51ebe3f5d605', 'App\\Notifications\\TaskCreatedNotification', 'App\\Models\\User', 3, '{\"type\":\"task_created\",\"task_id\":28,\"task_title\":\"Code Analysis Engine - JavaScript\\/TypeScript Support\",\"project_title\":\"SynthAI - Autonomous Code Review and Technical Debt Analyzer\",\"priority\":\"medium\",\"deadline\":null,\"assigned_to\":\"Mark Andrew Soliman\",\"created_by\":\"Admin\",\"message\":\"New task \'Code Analysis Engine - JavaScript\\/TypeScript Support\' has been created\",\"action_url\":\"http:\\/\\/localhost:8000\\/admin\\/tasks\\/28\",\"icon\":\"plus-square\",\"color\":\"success\"}', NULL, '2025-12-06 09:50:34', '2025-12-06 09:50:34'),
('0d4d8004-22f5-4897-a163-2aaf8851e120', 'App\\Notifications\\PaymentConfirmedNotification', 'App\\Models\\User', 2, '{\"type\":\"payment_confirmed\",\"title\":\"Payment Confirmed\",\"message\":\"Payment of \\u20b1250,000.00 confirmed for \'EdgeRelay - Decentralized Content Delivery Network for Small-Scale Creators\'.\",\"action_url\":\"\",\"payment_id\":16,\"service_request_id\":43,\"amount\":\"250000.00\",\"payment_type\":\"Full Payment\",\"project_name\":\"EdgeRelay - Decentralized Content Delivery Network for Small-Scale Creators\"}', NULL, '2025-12-05 17:55:58', '2025-12-05 17:55:58'),
('0f1f1dc1-1f3a-4669-bd81-b82448a874b5', 'App\\Notifications\\PaymentConfirmedNotification', 'App\\Models\\User', 3, '{\"type\":\"payment_confirmed\",\"title\":\"Payment Confirmed\",\"message\":\"Payment of \\u20b1250,000.00 confirmed for \'EdgeRelay - Decentralized Content Delivery Network for Small-Scale Creators\'.\",\"action_url\":\"\",\"payment_id\":16,\"service_request_id\":43,\"amount\":\"250000.00\",\"payment_type\":\"Full Payment\",\"project_name\":\"EdgeRelay - Decentralized Content Delivery Network for Small-Scale Creators\"}', '2025-12-06 05:35:39', '2025-12-05 17:53:07', '2025-12-05 17:53:07'),
('10fc43fb-2042-465d-abd3-695e1d2facf3', 'App\\Notifications\\NewServiceRequestNotification', 'App\\Models\\User', 2, '{\"type\":\"new_service_request\",\"title\":\"New Service Request\",\"message\":\"New service request \'EdgeRelay - Decentralized Content Delivery Network for Small-Scale Creators\' submitted by John Smith\",\"action_url\":\"http:\\/\\/localhost:8000\\/admin\\/requests\\/43\",\"service_request_id\":43,\"project_name\":\"EdgeRelay - Decentralized Content Delivery Network for Small-Scale Creators\",\"user_name\":\"John Smith\",\"is_new_user\":false}', NULL, '2025-12-05 17:44:18', '2025-12-05 17:44:18'),
('120b52f8-c2ab-41f0-82fb-3cac7ddb5877', 'App\\Notifications\\NewDeliverableAvailableNotification', 'App\\Models\\User', 3, '{\"type\":\"new_deliverable\",\"document_id\":12,\"document_name\":\"Figma Design\",\"task_id\":25,\"task_title\":\"Task 1: Backend Infrastructure & API Foundation\",\"project_title\":\"SynthAI - Autonomous Code Review and Technical Debt Analyzer\",\"message\":\"New deliverable available: Figma Design\"}', NULL, '2025-12-06 08:38:19', '2025-12-06 08:38:19'),
('1258f4d8-4058-4c8e-8344-b320bf79d2ce', 'App\\Notifications\\PaymentConfirmedNotification', 'App\\Models\\User', 32, '{\"type\":\"payment_confirmed\",\"title\":\"Payment Confirmed\",\"message\":\"Payment of \\u20b1280,000.00 confirmed for \'Graphics Design\'.\",\"action_url\":\"\",\"payment_id\":10,\"service_request_id\":38,\"amount\":\"280000.00\",\"payment_type\":\"Full Payment\",\"project_name\":\"Graphics Design\"}', NULL, '2025-11-14 11:47:58', '2025-11-14 11:47:58'),
('1536ca33-f91c-4215-b66c-fe7c01515b75', 'App\\Notifications\\RevisionRequestedNotification', 'App\\Models\\User', 1, '{\"type\":\"revision_requested\",\"revision_request_id\":4,\"document_id\":null,\"document_name\":\"N\\/A\",\"client_name\":\"John Smith\",\"source_type\":\"project\",\"source_description\":\"Project: Website SEO Optimization\",\"reason\":\"[2025-11-14 12:17:24] development.INFO: Revision request received {\\\"project_id\\\":\\\"13\\\",\\\"user_id\\\":3,\\\"re\",\"message\":\"New revision request from John Smith\",\"action_url\":\"http:\\/\\/localhost:8000\\/admin\\/revisions\\/4\"}', '2025-11-14 13:40:23', '2025-11-14 12:22:49', '2025-11-14 12:22:49'),
('1543cc95-f39b-424e-9334-4df37ec1ba76', 'App\\Notifications\\PaymentConfirmedNotification', 'App\\Models\\User', 1, '{\"type\":\"payment_confirmed\",\"title\":\"Payment Confirmed\",\"message\":\"Payment of \\u20b1280,000.00 confirmed for \'Graphics Design\'.\",\"action_url\":\"\",\"payment_id\":10,\"service_request_id\":38,\"amount\":\"280000.00\",\"payment_type\":\"Full Payment\",\"project_name\":\"Graphics Design\"}', '2025-11-14 13:40:23', '2025-11-14 11:48:50', '2025-11-14 11:48:50'),
('16176c55-4ced-442b-9e0a-00d27671e3ef', 'App\\Notifications\\TaskCreatedNotification', 'App\\Models\\User', 3, '{\"type\":\"task_created\",\"task_id\":23,\"task_title\":\"Task 1\",\"project_title\":\"Ecommerce 3\",\"priority\":\"medium\",\"deadline\":null,\"assigned_to\":\"Shann\",\"created_by\":\"Admin\",\"message\":\"New task \'Task 1\' has been created\",\"action_url\":\"http:\\/\\/localhost:8000\\/admin\\/tasks\\/23\",\"icon\":\"plus-square\",\"color\":\"success\"}', '2025-12-05 16:08:51', '2025-12-05 09:46:39', '2025-12-05 09:46:39'),
('16317e06-aeef-46f5-824c-cc704ef4cd23', 'App\\Notifications\\FileUploadedNotification', 'App\\Models\\User', 2, '{\"type\":\"file_uploaded\",\"file_name\":\"Bug Fixes Compilations (with code snippets) (Link)\",\"task_title\":\"Code Analysis Engine - JavaScript\\/TypeScript Support\",\"task_id\":28,\"uploaded_by\":\"Mark Andrew Soliman\",\"is_deliverable\":true,\"message\":\"New deliverable \'Bug Fixes Compilations (with code snippets) (Link)\' uploaded for task \'Code Analysis Engine - JavaScript\\/TypeScript Support\'\",\"action_url\":\"http:\\/\\/localhost:8000\\/adiutor\\/tasks\\/28\",\"icon\":\"package\",\"color\":\"success\"}', NULL, '2025-12-06 10:28:16', '2025-12-06 10:28:16'),
('16fa93b0-f3f8-4a18-8bcd-d0ef92ad4168', 'App\\Notifications\\NewServiceRequestNotification', 'App\\Models\\User', 1, '{\"type\":\"new_service_request\",\"title\":\"New Service Request\",\"message\":\"New service request \'Ecommerce 3\' submitted by John Smith\",\"action_url\":\"http:\\/\\/localhost:8000\\/admin\\/requests\\/35\",\"service_request_id\":35,\"project_name\":\"Ecommerce 3\",\"user_name\":\"John Smith\",\"is_new_user\":false}', '2025-11-14 10:04:38', '2025-11-10 11:29:39', '2025-11-10 11:29:39'),
('1741404a-977a-4820-a202-89f422a509be', 'App\\Notifications\\UserStatusChangedNotification', 'App\\Models\\User', 2, '{\"type\":\"user_status_changed\",\"user_id\":32,\"user_name\":\"Sofia Lorraine\",\"user_email\":\"yuicutie1975@gmail.com\",\"new_status\":\"inactive\",\"changed_by\":\"Mark Admin\",\"message\":\"User Sofia Lorraine has been inactive\",\"action_url\":\"http:\\/\\/localhost:8000\\/admin\\/users\\/32\",\"icon\":\"user-x\",\"color\":\"warning\"}', NULL, '2025-12-05 09:46:39', '2025-12-05 09:46:39'),
('1a12e116-0cfc-4f47-8a3a-d41dff526ee1', 'App\\Notifications\\PaymentConfirmedNotification', 'App\\Models\\User', 3, '{\"type\":\"payment_confirmed\",\"title\":\"Payment Confirmed\",\"message\":\"Payment of \\u20b1425,000.00 confirmed for \'NovaSync Vendor Intelligence Dashboard\'.\",\"action_url\":\"\",\"payment_id\":15,\"service_request_id\":42,\"amount\":\"425000.00\",\"payment_type\":\"Full Payment\",\"project_name\":\"NovaSync Vendor Intelligence Dashboard\"}', '2025-12-06 05:35:39', '2025-12-05 17:49:46', '2025-12-05 17:49:46'),
('1c196be5-6bd1-45d5-9b1e-2a26b52b342c', 'App\\Notifications\\RevisionRequestedNotification', 'App\\Models\\User', 7, '{\"type\":\"revision_requested\",\"revision_request_id\":6,\"document_id\":null,\"document_name\":\"N\\/A\",\"client_name\":\"Nissim Dalton\",\"source_type\":\"task\",\"source_description\":\"Unknown source\",\"reason\":\"The design doesn\'t match the approved mockups. Need adjustments to color scheme.\",\"message\":\"New revision request from Nissim Dalton\",\"action_url\":\"http:\\/\\/localhost:8000\\/admin\\/revisions\\/6\"}', '2025-12-06 06:45:51', '2025-12-01 13:20:40', '2025-12-01 13:20:40'),
('1e6ad252-b45a-4d6d-924c-4c7e02fb1afe', 'App\\Notifications\\RevisionApprovedNotification', 'App\\Models\\User', 1, '{\"type\":\"revision_approved\",\"revision_request_id\":5,\"document_id\":null,\"document_name\":\"N\\/A\",\"source_type\":\"project\",\"source_description\":\"Project: Complete Brand Identity Package\",\"task_id\":null,\"task_reopened\":false,\"requested_due_date\":null,\"admin_notes\":null,\"message\":\"Your revision request has been approved for: Project: Complete Brand Identity Package\",\"action_url\":\"http:\\/\\/localhost:8000\\/client\\/revisions\\/5\"}', '2025-12-01 17:34:23', '2025-12-01 12:57:06', '2025-12-01 12:57:06'),
('1f3f1838-b8cd-47fa-a496-f30c1aa5ccac', 'App\\Notifications\\PaymentConfirmedNotification', 'App\\Models\\User', 18, '{\"type\":\"payment_confirmed\",\"title\":\"Payment Confirmed\",\"message\":\"Payment of \\u20b1400,000.00 confirmed for \'UNIMERCE \\u2013 Full E-Commerce Platform Development & Optimization\'.\",\"action_url\":\"\",\"payment_id\":13,\"service_request_id\":40,\"amount\":\"400000.00\",\"payment_type\":\"Full Payment\",\"project_name\":\"UNIMERCE \\u2013 Full E-Commerce Platform Development & Optimization\"}', '2025-12-01 11:43:24', '2025-12-01 11:35:03', '2025-12-01 11:35:03'),
('2100370d-18a2-4169-9c26-7e1d40f6cec2', 'App\\Notifications\\PaymentConfirmedNotification', 'App\\Models\\User', 3, '{\"type\":\"payment_confirmed\",\"title\":\"Payment Confirmed\",\"message\":\"Payment of \\u20b1250,000.00 confirmed for \'EdgeRelay - Decentralized Content Delivery Network for Small-Scale Creators\'.\",\"action_url\":\"\",\"payment_id\":16,\"service_request_id\":43,\"amount\":\"250000.00\",\"payment_type\":\"Full Payment\",\"project_name\":\"EdgeRelay - Decentralized Content Delivery Network for Small-Scale Creators\"}', '2025-12-06 05:35:39', '2025-12-05 17:55:55', '2025-12-05 17:55:55'),
('239ac559-5c11-4e4d-802c-2bdca863248d', 'App\\Notifications\\NewServiceRequestNotification', 'App\\Models\\User', 1, '{\"type\":\"new_service_request\",\"title\":\"New Service Request\",\"message\":\"New service request \'Wanda Bartlett\' submitted by Hope Browning (new user)\",\"action_url\":\"http:\\/\\/localhost\\/admin\\/requests\\/17\",\"service_request_id\":17,\"project_name\":\"Wanda Bartlett\",\"user_name\":\"Hope Browning\",\"is_new_user\":true}', '2025-11-14 10:04:38', '2025-10-28 04:07:14', '2025-10-28 04:07:14'),
('23cd3166-a14e-4571-8a8b-098669b3ec56', 'App\\Notifications\\NewServiceRequestNotification', 'App\\Models\\User', 2, '{\"type\":\"new_service_request\",\"title\":\"New Service Request\",\"message\":\"New service request \'Eugenia Caldwell\' submitted by Emerson Jacobson (new user)\",\"action_url\":\"http:\\/\\/localhost:8000\\/admin\\/requests\\/21\",\"service_request_id\":21,\"project_name\":\"Eugenia Caldwell\",\"user_name\":\"Emerson Jacobson\",\"is_new_user\":true}', NULL, '2025-10-28 04:19:50', '2025-10-28 04:19:50'),
('280fd792-9165-4e64-a47a-20b226a16e75', 'App\\Notifications\\ProjectCompletedNotification', 'App\\Models\\User', 3, '{\"type\":\"project_completed\",\"title\":\"Project Completed\",\"message\":\"Your project \'Ecommerce 3\' has been completed!\",\"action_url\":\"http:\\/\\/localhost:8000\\/client\\/projects\\/14\",\"project_id\":14,\"project_title\":\"Ecommerce 3\",\"completion_date\":\"2025-11-10 12:05:17\"}', '2025-11-14 13:30:25', '2025-11-10 12:05:17', '2025-11-10 12:05:17'),
('2929b9f1-3d27-4c57-a1d3-43f849514105', 'App\\Notifications\\FileUploadedNotification', 'App\\Models\\User', 2, '{\"type\":\"file_uploaded\",\"file_name\":\"Github Repository (Link)\",\"task_title\":\"Code Analysis Engine - JavaScript\\/TypeScript Support\",\"task_id\":28,\"uploaded_by\":\"Mark Andrew Soliman\",\"is_deliverable\":true,\"message\":\"New deliverable \'Github Repository (Link)\' uploaded for task \'Code Analysis Engine - JavaScript\\/TypeScript Support\'\",\"action_url\":\"http:\\/\\/localhost:8000\\/adiutor\\/tasks\\/28\",\"icon\":\"package\",\"color\":\"success\"}', NULL, '2025-12-06 10:26:42', '2025-12-06 10:26:42'),
('2969fb7b-97ff-4d91-9296-1ae81a333fa0', 'App\\Notifications\\ProjectCompletedNotification', 'App\\Models\\User', 3, '{\"type\":\"project_completed\",\"title\":\"Project Completed\",\"message\":\"Your project \'Website SEO Optimization\' has been completed!\",\"action_url\":\"http:\\/\\/localhost:8000\\/client\\/projects\\/13\",\"project_id\":13,\"project_title\":\"Website SEO Optimization\",\"completion_date\":\"2025-11-10 12:52:26\"}', '2025-11-14 13:30:25', '2025-11-10 12:52:26', '2025-11-10 12:52:26'),
('29821bae-182f-437e-9060-2cef0e046530', 'App\\Notifications\\NewServiceRequestNotification', 'App\\Models\\User', 1, '{\"type\":\"new_service_request\",\"title\":\"New Service Request\",\"message\":\"New service request \'Jaquelyn Burke\' submitted by Mark Andrew Soliman\",\"action_url\":\"http:\\/\\/localhost:8000\\/admin\\/requests\\/27\",\"service_request_id\":27,\"project_name\":\"Jaquelyn Burke\",\"user_name\":\"Mark Andrew Soliman\",\"is_new_user\":false}', '2025-11-14 10:04:38', '2025-10-29 13:54:14', '2025-10-29 13:54:14'),
('29b90ed1-c2d8-4363-9fcf-15af65b334f9', 'App\\Notifications\\PaymentConfirmedNotification', 'App\\Models\\User', 1, '{\"type\":\"payment_confirmed\",\"title\":\"Payment Confirmed\",\"message\":\"Payment confirmed for \'Digital Strategy Consulting\'. Project created.\",\"action_url\":\"http:\\/\\/localhost\\/admin\\/projects\\/5\",\"service_request_id\":11,\"project_id\":5,\"payment_id\":3,\"project_name\":\"Digital Strategy Consulting\"}', '2025-11-14 10:04:38', '2025-10-28 03:54:33', '2025-10-28 03:54:33'),
('2b0dac6f-88bc-4524-a98c-8001e15bfe0c', 'App\\Notifications\\PaymentConfirmedNotification', 'App\\Models\\User', 3, '{\"type\":\"payment_confirmed\",\"title\":\"Payment Confirmed\",\"message\":\"Payment of \\u20b150,000.00 confirmed for \'Website SEO Optimization\'.\",\"action_url\":\"\",\"payment_id\":12,\"service_request_id\":9,\"amount\":\"50000.00\",\"payment_type\":\"Full Payment\",\"project_name\":\"Website SEO Optimization\"}', '2025-11-14 13:30:25', '2025-11-14 12:12:42', '2025-11-14 12:12:42'),
('2dae8710-d84a-451c-8766-ecc1d447a60f', 'App\\Notifications\\PaymentConfirmedNotification', 'App\\Models\\User', 1, '{\"type\":\"payment_confirmed\",\"title\":\"Payment Confirmed\",\"message\":\"Payment of \\u20b1400,000.00 confirmed for \'UNIMERCE \\u2013 Full E-Commerce Platform Development & Optimization\'.\",\"action_url\":\"\",\"payment_id\":13,\"service_request_id\":40,\"amount\":\"400000.00\",\"payment_type\":\"Full Payment\",\"project_name\":\"UNIMERCE \\u2013 Full E-Commerce Platform Development & Optimization\"}', '2025-12-01 17:34:28', '2025-12-01 11:35:05', '2025-12-01 11:35:05'),
('2e408165-fafc-49c5-9871-8f6308503455', 'App\\Notifications\\PaymentConfirmedNotification', 'App\\Models\\User', 2, '{\"type\":\"payment_confirmed\",\"title\":\"Payment Confirmed\",\"message\":\"Payment of \\u20b1425,000.00 confirmed for \'NovaSync Vendor Intelligence Dashboard\'.\",\"action_url\":\"\",\"payment_id\":15,\"service_request_id\":42,\"amount\":\"425000.00\",\"payment_type\":\"Full Payment\",\"project_name\":\"NovaSync Vendor Intelligence Dashboard\"}', NULL, '2025-12-05 17:52:23', '2025-12-05 17:52:23'),
('320dfe9c-e6e1-4043-8d69-1cd7594566f1', 'App\\Notifications\\NewDeliverableAvailableNotification', 'App\\Models\\User', 3, '{\"type\":\"new_deliverable\",\"document_id\":16,\"document_name\":\"Documentation\",\"task_id\":28,\"task_title\":\"Code Analysis Engine - JavaScript\\/TypeScript Support\",\"project_title\":\"SynthAI - Autonomous Code Review and Technical Debt Analyzer\",\"message\":\"New deliverable available: Documentation\"}', NULL, '2025-12-06 10:30:10', '2025-12-06 10:30:10'),
('323db035-bc12-41e6-a3af-045d6bf5fff2', 'App\\Notifications\\TaskCompletedNotification', 'App\\Models\\User', 1, '{\"title\":\"Task Completed\",\"message\":\"Mark Andrew Soliman has completed task: Task 1: Backend Infrastructure & API Foundation\",\"action_url\":\"http:\\/\\/localhost:8000\\/admin\\/tasks\\/25\",\"task_id\":25,\"task_title\":\"Task 1: Backend Infrastructure & API Foundation\",\"completed_by\":\"Mark Andrew Soliman\"}', NULL, '2025-12-06 08:07:02', '2025-12-06 08:07:02'),
('340a7cf7-8643-4e87-a8cf-d671ddc7af48', 'App\\Notifications\\TaskCreatedNotification', 'App\\Models\\User', 2, '{\"type\":\"task_created\",\"task_id\":25,\"task_title\":\"Task 1: Backend Infrastructure & API Foundation\",\"project_title\":\"SynthAI - Autonomous Code Review and Technical Debt Analyzer\",\"priority\":\"medium\",\"deadline\":null,\"assigned_to\":\"Unassigned\",\"created_by\":\"Mark Admin\",\"message\":\"New task \'Task 1: Backend Infrastructure & API Foundation\' has been created\",\"action_url\":\"http:\\/\\/localhost:8000\\/admin\\/tasks\\/25\",\"icon\":\"plus-square\",\"color\":\"success\"}', NULL, '2025-12-06 05:38:04', '2025-12-06 05:38:04'),
('353fa6ae-f4d2-4a0e-8cf5-037604426799', 'App\\Notifications\\DeliverableApprovedNotification', 'App\\Models\\User', 7, '{\"type\":\"deliverable_approved\",\"document_id\":14,\"document_name\":\"sales_report_20251203 (1).pdf\",\"task_id\":25,\"task_title\":\"Task 1: Backend Infrastructure & API Foundation\",\"message\":\"Your deliverable \\\"sales_report_20251203 (1).pdf\\\" has been approved.\"}', NULL, '2025-12-06 08:34:46', '2025-12-06 08:34:46'),
('354f76fb-85d4-46c2-8996-39d156f5ab7a', 'App\\Notifications\\ProjectCompletedNotification', 'App\\Models\\User', 3, '{\"type\":\"project_completed\",\"title\":\"Project Completed\",\"message\":\"Your project \'Ecommerce 3\' has been completed!\",\"action_url\":\"http:\\/\\/localhost:8000\\/client\\/projects\\/14\",\"project_id\":14,\"project_title\":\"Ecommerce 3\",\"completion_date\":\"2025-11-10 12:09:47\"}', '2025-11-14 13:30:25', '2025-11-10 12:09:47', '2025-11-10 12:09:47'),
('36b83c24-df95-45b0-8280-ef997eb994d5', 'App\\Notifications\\TaskCreatedNotification', 'App\\Models\\User', 2, '{\"type\":\"task_created\",\"task_id\":26,\"task_title\":\"GitHub Integration & Webhook Handler\",\"project_title\":\"SynthAI - Autonomous Code Review and Technical Debt Analyzer\",\"priority\":\"medium\",\"deadline\":\"2025-12-14T16:00:00.000000Z\",\"assigned_to\":\"Princess Anne Azucena\",\"created_by\":\"Mark Admin\",\"message\":\"New task \'GitHub Integration & Webhook Handler\' has been created\",\"action_url\":\"http:\\/\\/localhost:8000\\/admin\\/tasks\\/26\",\"icon\":\"plus-square\",\"color\":\"success\"}', NULL, '2025-12-06 07:33:32', '2025-12-06 07:33:32'),
('36f002b5-f1a1-42cf-a3b4-eae0f0b535fe', 'App\\Notifications\\PaymentConfirmedNotification', 'App\\Models\\User', 1, '{\"type\":\"payment_confirmed\",\"title\":\"Payment Confirmed\",\"message\":\"Payment of \\u20b1250,000.00 confirmed for \'EdgeRelay - Decentralized Content Delivery Network for Small-Scale Creators\'.\",\"action_url\":\"\",\"payment_id\":16,\"service_request_id\":43,\"amount\":\"250000.00\",\"payment_type\":\"Full Payment\",\"project_name\":\"EdgeRelay - Decentralized Content Delivery Network for Small-Scale Creators\"}', NULL, '2025-12-05 17:53:10', '2025-12-05 17:53:10'),
('375d7350-4590-4734-bbd4-dea2f217e656', 'App\\Notifications\\TaskCompletedNotification', 'App\\Models\\User', 1, '{\"title\":\"Task Completed\",\"message\":\"Alex Developer has completed task: TASK 1\",\"action_url\":\"http:\\/\\/localhost\\/admin\\/tasks\\/13\",\"task_id\":13,\"task_title\":\"TASK 1\",\"completed_by\":\"Alex Developer\"}', '2025-11-14 10:04:38', '2025-10-28 03:54:35', '2025-10-28 03:54:35'),
('397dae83-1768-4826-a7a5-2e6fc59e41c9', 'App\\Notifications\\NewServiceRequestNotification', 'App\\Models\\User', 1, '{\"type\":\"new_service_request\",\"title\":\"New Service Request\",\"message\":\"New service request \'Game Mobile Application\' submitted by Sofia Lorraine\",\"action_url\":\"http:\\/\\/127.0.0.1:8000\\/admin\\/requests\\/36\",\"service_request_id\":36,\"project_name\":\"Game Mobile Application\",\"user_name\":\"Sofia Lorraine\",\"is_new_user\":false}', '2025-11-14 10:04:38', '2025-11-12 01:39:20', '2025-11-12 01:39:20'),
('3da25e3f-2ee0-4a3e-b8b8-1a3a53cd516c', 'App\\Notifications\\NewServiceRequestNotification', 'App\\Models\\User', 2, '{\"type\":\"new_service_request\",\"title\":\"New Service Request\",\"message\":\"New service request \'Digital Strategy Consulting\' submitted by Mark Andrew Zapatero Soliman (new user)\",\"action_url\":\"http:\\/\\/localhost:8000\\/admin\\/requests\\/22\",\"service_request_id\":22,\"project_name\":\"Digital Strategy Consulting\",\"user_name\":\"Mark Andrew Zapatero Soliman\",\"is_new_user\":true}', NULL, '2025-10-28 22:23:07', '2025-10-28 22:23:07'),
('3eec6a9e-e63e-4aed-be5f-8b89cc7bdd1b', 'App\\Notifications\\NewServiceRequestNotification', 'App\\Models\\User', 2, '{\"type\":\"new_service_request\",\"title\":\"New Service Request\",\"message\":\"New service request \'Ecommerce 3\' submitted by John Smith\",\"action_url\":\"http:\\/\\/localhost:8000\\/admin\\/requests\\/35\",\"service_request_id\":35,\"project_name\":\"Ecommerce 3\",\"user_name\":\"John Smith\",\"is_new_user\":false}', NULL, '2025-11-10 11:29:41', '2025-11-10 11:29:41'),
('3fee5c6e-80f8-4898-b603-4ac7903ec89e', 'App\\Notifications\\NewServiceRequestNotification', 'App\\Models\\User', 2, '{\"type\":\"new_service_request\",\"title\":\"New Service Request\",\"message\":\"New service request \'Mobile App Development\' submitted by Lena Quizon (new user)\",\"action_url\":\"http:\\/\\/127.0.0.1:8000\\/admin\\/requests\\/32\",\"service_request_id\":32,\"project_name\":\"Mobile App Development\",\"user_name\":\"Lena Quizon\",\"is_new_user\":true}', NULL, '2025-10-29 16:49:52', '2025-10-29 16:49:52'),
('3ff5a764-2269-4278-a007-8178454a9519', 'App\\Notifications\\UserCreatedNotification', 'App\\Models\\User', 1, '{\"type\":\"user_created\",\"user_id\":32,\"user_name\":\"Sofia Lorraine\",\"user_email\":\"yuicutie1975@gmail.com\",\"user_role\":\"client\",\"created_by\":\"Self-Registration\",\"is_manual_creation\":false,\"message\":\"New client user Sofia Lorraine has been created\",\"action_url\":\"http:\\/\\/localhost:8000\\/admin\\/users\\/32\",\"icon\":\"user-plus\",\"color\":\"success\"}', '2025-12-01 11:20:07', '2025-11-16 10:47:46', '2025-11-16 10:47:46'),
('403f21d1-6215-490d-a979-d605de641a0e', 'App\\Notifications\\PaymentConfirmedNotification', 'App\\Models\\User', 2, '{\"type\":\"payment_confirmed\",\"title\":\"Payment Confirmed\",\"message\":\"Payment confirmed for \'Digital Strategy Consulting\'. Project created.\",\"action_url\":\"http:\\/\\/localhost\\/admin\\/projects\\/5\",\"service_request_id\":11,\"project_id\":5,\"payment_id\":2,\"project_name\":\"Digital Strategy Consulting\"}', NULL, '2025-10-28 03:54:33', '2025-10-28 03:54:33'),
('43a77a67-fa9c-46bb-a2b4-c01fa3b57707', 'App\\Notifications\\NewServiceRequestNotification', 'App\\Models\\User', 1, '{\"type\":\"new_service_request\",\"title\":\"New Service Request\",\"message\":\"New service request \'Mobile App Development\' submitted by Lena Quizon (new user)\",\"action_url\":\"http:\\/\\/127.0.0.1:8000\\/admin\\/requests\\/32\",\"service_request_id\":32,\"project_name\":\"Mobile App Development\",\"user_name\":\"Lena Quizon\",\"is_new_user\":true}', '2025-11-14 10:04:38', '2025-10-29 16:49:49', '2025-10-29 16:49:49'),
('43cf3105-d1dc-4494-bfee-6f88b482fbbd', 'App\\Notifications\\PaymentConfirmedNotification', 'App\\Models\\User', 1, '{\"type\":\"payment_confirmed\",\"title\":\"Payment Confirmed\",\"message\":\"Payment confirmed for \'Digital Strategy Consulting\'. Project created.\",\"action_url\":\"http:\\/\\/localhost\\/admin\\/projects\\/5\",\"service_request_id\":11,\"project_id\":5,\"payment_id\":1,\"project_name\":\"Digital Strategy Consulting\"}', '2025-11-14 10:04:38', '2025-10-28 03:54:30', '2025-10-28 03:54:30'),
('44346369-926b-4cbb-a90c-dba832387410', 'App\\Notifications\\TaskCompletedNotification', 'App\\Models\\User', 1, '{\"title\":\"Task Completed\",\"message\":\"Mark Andrew Soliman has completed task: Code Analysis Engine - JavaScript\\/TypeScript Support\",\"action_url\":\"http:\\/\\/localhost:8000\\/admin\\/tasks\\/28\",\"task_id\":28,\"task_title\":\"Code Analysis Engine - JavaScript\\/TypeScript Support\",\"completed_by\":\"Mark Andrew Soliman\"}', NULL, '2025-12-06 10:31:44', '2025-12-06 10:31:44'),
('4641d432-b944-42f7-b881-23c7a76b2f88', 'App\\Notifications\\PaymentConfirmedNotification', 'App\\Models\\User', 18, '{\"type\":\"payment_confirmed\",\"title\":\"Payment Confirmed\",\"message\":\"Payment of \\u20b1499,000.00 confirmed for \'Multi-Platform Study Cards System (Web + Android)\'.\",\"action_url\":\"\",\"payment_id\":14,\"service_request_id\":41,\"amount\":\"499000.00\",\"payment_type\":\"Full Payment\",\"project_name\":\"Multi-Platform Study Cards System (Web + Android)\"}', '2025-12-01 12:14:57', '2025-12-01 12:14:35', '2025-12-01 12:14:35'),
('46a3c968-bf08-4e70-931c-bda539c77c2a', 'App\\Notifications\\NewServiceRequestNotification', 'App\\Models\\User', 2, '{\"type\":\"new_service_request\",\"title\":\"New Service Request\",\"message\":\"New service request \'Multi-Platform Study Cards System (Web + Android)\' submitted by Nissim Dalton\",\"action_url\":\"http:\\/\\/localhost:8000\\/admin\\/requests\\/41\",\"service_request_id\":41,\"project_name\":\"Multi-Platform Study Cards System (Web + Android)\",\"user_name\":\"Nissim Dalton\",\"is_new_user\":false}', NULL, '2025-12-01 12:12:59', '2025-12-01 12:12:59'),
('47cf562a-8fe9-494a-9dbd-55954c0cd0ba', 'App\\Notifications\\NewServiceRequestNotification', 'App\\Models\\User', 2, '{\"type\":\"new_service_request\",\"title\":\"New Service Request\",\"message\":\"New service request \'Wanda Bartlett\' submitted by Hope Browning (new user)\",\"action_url\":\"http:\\/\\/localhost\\/admin\\/requests\\/17\",\"service_request_id\":17,\"project_name\":\"Wanda Bartlett\",\"user_name\":\"Hope Browning\",\"is_new_user\":true}', NULL, '2025-10-28 04:07:18', '2025-10-28 04:07:18'),
('488bc49b-fdee-4b31-b224-a29dd0125e3a', 'App\\Notifications\\NewServiceRequestNotification', 'App\\Models\\User', 2, '{\"type\":\"new_service_request\",\"title\":\"New Service Request\",\"message\":\"New service request \'Sigourney Lynn\' submitted by Mark Andrew Soliman\",\"action_url\":\"http:\\/\\/localhost:8000\\/admin\\/requests\\/28\",\"service_request_id\":28,\"project_name\":\"Sigourney Lynn\",\"user_name\":\"Mark Andrew Soliman\",\"is_new_user\":false}', NULL, '2025-10-29 14:28:56', '2025-10-29 14:28:56'),
('4a22aa4d-b6f7-4831-ab7f-e6b13e6a9675', 'App\\Notifications\\PaymentConfirmedNotification', 'App\\Models\\User', 3, '{\"type\":\"payment_confirmed\",\"title\":\"Payment Confirmed\",\"message\":\"Payment of \\u20b1250,000.00 confirmed for \'EdgeRelay - Decentralized Content Delivery Network for Small-Scale Creators\'.\",\"action_url\":\"\",\"payment_id\":16,\"service_request_id\":43,\"amount\":\"250000.00\",\"payment_type\":\"Full Payment\",\"project_name\":\"EdgeRelay - Decentralized Content Delivery Network for Small-Scale Creators\"}', '2025-12-06 05:35:39', '2025-12-05 17:55:21', '2025-12-05 17:55:21'),
('4cfc8bcf-4abf-4ca5-89d0-f612231c750d', 'App\\Notifications\\PaymentConfirmedNotification', 'App\\Models\\User', 1, '{\"type\":\"payment_confirmed\",\"title\":\"Payment Confirmed\",\"message\":\"Payment confirmed for \'Tarik Michael\'. Project created.\",\"action_url\":\"\",\"service_request_id\":29,\"project_name\":\"Tarik Michael\"}', '2025-11-14 10:04:38', '2025-10-29 14:34:30', '2025-10-29 14:34:30'),
('4d308ba8-0cd2-4c77-b14e-33536dbc84cf', 'App\\Notifications\\NewServiceRequestNotification', 'App\\Models\\User', 2, '{\"type\":\"new_service_request\",\"title\":\"New Service Request\",\"message\":\"New service request \'Website Redesign\' submitted by Lena Quizon\",\"action_url\":\"http:\\/\\/127.0.0.1:8000\\/admin\\/requests\\/39\",\"service_request_id\":39,\"project_name\":\"Website Redesign\",\"user_name\":\"Lena Quizon\",\"is_new_user\":false}', NULL, '2025-11-12 08:09:11', '2025-11-12 08:09:11'),
('4ef09b54-5a59-4703-9335-45972d3fa6ac', 'App\\Notifications\\NewServiceRequestNotification', 'App\\Models\\User', 1, '{\"type\":\"new_service_request\",\"title\":\"New Service Request\",\"message\":\"New service request \'SynthAI - Autonomous Code Review and Technical Debt Analyzer\' submitted by John Smith\",\"action_url\":\"http:\\/\\/localhost:8000\\/admin\\/requests\\/45\",\"service_request_id\":45,\"project_name\":\"SynthAI - Autonomous Code Review and Technical Debt Analyzer\",\"user_name\":\"John Smith\",\"is_new_user\":false}', NULL, '2025-12-05 19:23:01', '2025-12-05 19:23:01'),
('50c844c7-21fe-4b3c-ad7d-604a0e28d1ff', 'App\\Notifications\\NewServiceRequestNotification', 'App\\Models\\User', 2, '{\"type\":\"new_service_request\",\"title\":\"New Service Request\",\"message\":\"New service request \'SynthAI - Autonomous Code Review and Technical Debt Analyzer\' submitted by John Smith\",\"action_url\":\"http:\\/\\/localhost:8000\\/admin\\/requests\\/45\",\"service_request_id\":45,\"project_name\":\"SynthAI - Autonomous Code Review and Technical Debt Analyzer\",\"user_name\":\"John Smith\",\"is_new_user\":false}', NULL, '2025-12-05 19:23:07', '2025-12-05 19:23:07'),
('52374b52-8da0-46b8-8cb6-5a494f406c59', 'App\\Notifications\\RevisionRequestedNotification', 'App\\Models\\User', 2, '{\"type\":\"revision_requested\",\"revision_request_id\":6,\"document_id\":null,\"document_name\":\"N\\/A\",\"client_name\":\"Nissim Dalton\",\"source_type\":\"task\",\"source_description\":\"Unknown source\",\"reason\":\"The design doesn\'t match the approved mockups. Need adjustments to color scheme.\",\"message\":\"New revision request from Nissim Dalton\",\"action_url\":\"http:\\/\\/localhost:8000\\/admin\\/revisions\\/6\"}', NULL, '2025-12-01 13:20:39', '2025-12-01 13:20:39'),
('532cba85-939e-462a-b4b6-39f622775a9d', 'App\\Notifications\\PaymentConfirmedNotification', 'App\\Models\\User', 1, '{\"type\":\"payment_confirmed\",\"title\":\"Payment Confirmed\",\"message\":\"Payment confirmed for \'Digital Strategy Consulting\'. Project created.\",\"action_url\":\"\",\"service_request_id\":23,\"project_name\":\"Digital Strategy Consulting\"}', '2025-11-14 10:04:38', '2025-10-29 08:44:35', '2025-10-29 08:44:35'),
('544e1fa5-83d9-42bd-9eb4-05ea781e85d6', 'App\\Notifications\\NewServiceRequestNotification', 'App\\Models\\User', 1, '{\"type\":\"new_service_request\",\"title\":\"New Service Request\",\"message\":\"New service request \'Jaquelyn Burke\' submitted by Mark Andrew Soliman\",\"action_url\":\"http:\\/\\/localhost:8000\\/admin\\/requests\\/24\",\"service_request_id\":24,\"project_name\":\"Jaquelyn Burke\",\"user_name\":\"Mark Andrew Soliman\",\"is_new_user\":false}', '2025-11-14 10:04:38', '2025-10-29 13:50:54', '2025-10-29 13:50:54'),
('55b48567-db62-408f-a289-f72fdeeeade5', 'App\\Notifications\\NewServiceRequestNotification', 'App\\Models\\User', 1, '{\"type\":\"new_service_request\",\"title\":\"New Service Request\",\"message\":\"New service request \'Digital Strategy Consulting\' submitted by Mark Andrew Zapatero Soliman (new user)\",\"action_url\":\"http:\\/\\/localhost:8000\\/admin\\/requests\\/23\",\"service_request_id\":23,\"project_name\":\"Digital Strategy Consulting\",\"user_name\":\"Mark Andrew Zapatero Soliman\",\"is_new_user\":true}', '2025-11-14 10:04:38', '2025-10-29 08:38:37', '2025-10-29 08:38:37'),
('56e13635-b827-4a2d-b1d6-d75e9a150f6f', 'App\\Notifications\\PaymentConfirmedNotification', 'App\\Models\\User', 3, '{\"type\":\"payment_confirmed\",\"title\":\"Payment Confirmed\",\"message\":\"Payment of \\u20b1400,000.00 confirmed for \'Ecommerce 3\'.\",\"action_url\":\"\",\"payment_id\":11,\"service_request_id\":35,\"amount\":\"400000.00\",\"payment_type\":\"Full Payment\",\"project_name\":\"Ecommerce 3\"}', '2025-11-14 13:30:25', '2025-11-14 12:05:57', '2025-11-14 12:05:57'),
('58240ffe-5ee5-454a-a7a6-9409f4627da8', 'App\\Notifications\\RevisionRequestedNotification', 'App\\Models\\User', 1, '{\"type\":\"revision_requested\",\"revision_request_id\":6,\"document_id\":null,\"document_name\":\"N\\/A\",\"client_name\":\"Nissim Dalton\",\"source_type\":\"task\",\"source_description\":\"Unknown source\",\"reason\":\"The design doesn\'t match the approved mockups. Need adjustments to color scheme.\",\"message\":\"New revision request from Nissim Dalton\",\"action_url\":\"http:\\/\\/localhost:8000\\/admin\\/revisions\\/6\"}', '2025-12-01 17:34:25', '2025-12-01 13:20:37', '2025-12-01 13:20:37'),
('59a14e39-a6a8-48b5-af43-8ec1eddb3f62', 'App\\Notifications\\RevisionApprovedNotification', 'App\\Models\\User', 3, '{\"type\":\"revision_approved\",\"revision_request_id\":7,\"document_id\":null,\"document_name\":\"N\\/A\",\"source_type\":\"task\",\"source_description\":\"Task: \",\"task_id\":28,\"task_reopened\":true,\"requested_due_date\":null,\"admin_notes\":null,\"message\":\"Your revision request has been approved for: Task: \",\"action_url\":\"http:\\/\\/localhost:8000\\/client\\/revisions\\/7\"}', NULL, '2025-12-06 11:02:13', '2025-12-06 11:02:13'),
('5a55dc42-6032-4e6b-a06a-c0374ed46245', 'App\\Notifications\\FileUploadedNotification', 'App\\Models\\User', 2, '{\"type\":\"file_uploaded\",\"file_name\":\"drivers_license.png\",\"task_title\":\"Implementation & Development\",\"task_id\":5,\"uploaded_by\":\"Mark Andrew\",\"is_deliverable\":false,\"message\":\"New file \'drivers_license.png\' uploaded for task \'Implementation & Development\'\",\"action_url\":\"http:\\/\\/localhost:8000\\/adiutor\\/tasks\\/5\",\"icon\":\"file\",\"color\":\"info\"}', NULL, '2025-12-05 09:46:39', '2025-12-05 09:46:39'),
('5b10672e-7b51-4019-890f-ac63d8d353a2', 'App\\Notifications\\FileUploadedNotification', 'App\\Models\\User', 3, '{\"type\":\"file_uploaded\",\"file_name\":\"sales_report_20251203 (1).pdf\",\"task_title\":\"Task 1: Backend Infrastructure & API Foundation\",\"task_id\":25,\"uploaded_by\":\"Mark Andrew Soliman\",\"is_deliverable\":true,\"message\":\"New deliverable \'sales_report_20251203 (1).pdf\' uploaded for task \'Task 1: Backend Infrastructure & API Foundation\'\",\"action_url\":\"http:\\/\\/localhost:8000\\/adiutor\\/tasks\\/25\",\"icon\":\"package\",\"color\":\"success\"}', NULL, '2025-12-06 08:06:54', '2025-12-06 08:06:54'),
('5cf84aed-7219-40ca-81dd-509429eb5dd2', 'App\\Notifications\\NewServiceRequestNotification', 'App\\Models\\User', 1, '{\"type\":\"new_service_request\",\"title\":\"New Service Request\",\"message\":\"New service request \'Jaquelyn Burke\' submitted by Mark Andrew Soliman\",\"action_url\":\"http:\\/\\/localhost:8000\\/admin\\/requests\\/26\",\"service_request_id\":26,\"project_name\":\"Jaquelyn Burke\",\"user_name\":\"Mark Andrew Soliman\",\"is_new_user\":false}', '2025-11-14 10:04:38', '2025-10-29 13:52:19', '2025-10-29 13:52:19'),
('5dfde186-b050-4e05-bf49-c3fac496d99a', 'App\\Notifications\\UserStatusChangedNotification', 'App\\Models\\User', 2, '{\"type\":\"user_status_changed\",\"user_id\":32,\"user_name\":\"Sofia Lorraine\",\"user_email\":\"yuicutie1975@gmail.com\",\"new_status\":\"active\",\"changed_by\":\"Mark Admin\",\"message\":\"User Sofia Lorraine has been active\",\"action_url\":\"http:\\/\\/localhost:8000\\/admin\\/users\\/32\",\"icon\":\"user-check\",\"color\":\"success\"}', NULL, '2025-12-05 09:46:39', '2025-12-05 09:46:39'),
('5f9584e1-9310-4111-bd06-5ae9ce965af5', 'App\\Notifications\\ProjectCompletedNotification', 'App\\Models\\User', 18, '{\"type\":\"project_completed\",\"title\":\"Project Completed\",\"message\":\"Your project \'UNIMERCE \\u2013 Full E-Commerce Platform Development & Optimization\' has been completed!\",\"action_url\":\"http:\\/\\/localhost:8000\\/client\\/projects\\/19\",\"project_id\":19,\"project_title\":\"UNIMERCE \\u2013 Full E-Commerce Platform Development & Optimization\",\"completion_date\":\"2025-12-01 14:25:54\"}', '2025-12-01 15:03:38', '2025-12-01 14:25:54', '2025-12-01 14:25:54'),
('6167faa5-e247-48da-9240-340b56fd89fb', 'App\\Notifications\\NewServiceRequestNotification', 'App\\Models\\User', 2, '{\"type\":\"new_service_request\",\"title\":\"New Service Request\",\"message\":\"New service request \'Jordan Huber\' submitted by Nissim Dalton (new user)\",\"action_url\":\"http:\\/\\/localhost\\/admin\\/requests\\/16\",\"service_request_id\":16,\"project_name\":\"Jordan Huber\",\"user_name\":\"Nissim Dalton\",\"is_new_user\":true}', NULL, '2025-10-28 03:54:55', '2025-10-28 03:54:55'),
('62096692-e7f5-497b-819a-c9ab58bf9ff6', 'App\\Notifications\\RevisionRejectedNotification', 'App\\Models\\User', 3, '{\"type\":\"revision_rejected\",\"revision_request_id\":4,\"document_id\":null,\"document_name\":\"N\\/A\",\"source_description\":\"Project: Website SEO Optimization\",\"admin_notes\":\"[2025-11-14 12:24:29] development.ERROR: Failed to approve revision request {\\\"revision_id\\\":\\\"4\\\",\\\"error\\\":\\\"SQLSTATE[42S22]: Column not found: 1054 Unknown column \'projectID\' in \'where clause\' (Connection: azure_mysql, SQL: select * from `tasks` where `projectID` = 13 and `status` = completed)\\\"} \\r\\n[2025-11-14 13:26:45] development.ERROR: Failed to reject revision request {\\\"revision_id\\\":\\\"4\\\",\\\"error\\\":\\\"Attempt to read property \\\\\\\"fileName\\\\\\\" on null\\\"}\",\"message\":\"Your revision request was not approved for: Project: Website SEO Optimization\",\"action_url\":\"http:\\/\\/localhost:8000\\/client\\/revisions\\/4\"}', '2025-11-14 13:30:22', '2025-11-14 13:29:40', '2025-11-14 13:29:40'),
('6362b3c9-213b-4329-a281-d82f8da4ce0e', 'App\\Notifications\\NewServiceRequestNotification', 'App\\Models\\User', 1, '{\"type\":\"new_service_request\",\"title\":\"New Service Request\",\"message\":\"New service request \'Nayda Harvey\' submitted by Rose Finch\",\"action_url\":\"http:\\/\\/localhost:8000\\/admin\\/requests\\/20\",\"service_request_id\":20,\"project_name\":\"Nayda Harvey\",\"user_name\":\"Rose Finch\",\"is_new_user\":false}', '2025-11-14 10:04:38', '2025-10-28 04:18:00', '2025-10-28 04:18:00'),
('66570ad9-f7ca-4fef-bd1b-4d53a04d012f', 'App\\Notifications\\FileUploadedNotification', 'App\\Models\\User', 2, '{\"type\":\"file_uploaded\",\"file_name\":\"Figma Design (Link)\",\"task_title\":\"Task 1: Backend Infrastructure & API Foundation\",\"task_id\":25,\"uploaded_by\":\"Mark Andrew Soliman\",\"is_deliverable\":true,\"message\":\"New deliverable \'Figma Design (Link)\' uploaded for task \'Task 1: Backend Infrastructure & API Foundation\'\",\"action_url\":\"http:\\/\\/localhost:8000\\/adiutor\\/tasks\\/25\",\"icon\":\"package\",\"color\":\"success\"}', NULL, '2025-12-06 08:04:27', '2025-12-06 08:04:27'),
('6677b169-f131-4521-bb54-bd4ca250a3aa', 'App\\Notifications\\FileUploadedNotification', 'App\\Models\\User', 1, '{\"type\":\"file_uploaded\",\"file_name\":\"Documentation (Link)\",\"task_title\":\"Code Analysis Engine - JavaScript\\/TypeScript Support\",\"task_id\":28,\"uploaded_by\":\"Mark Andrew Soliman\",\"is_deliverable\":true,\"message\":\"New deliverable \'Documentation (Link)\' uploaded for task \'Code Analysis Engine - JavaScript\\/TypeScript Support\'\",\"action_url\":\"http:\\/\\/localhost:8000\\/adiutor\\/tasks\\/28\",\"icon\":\"package\",\"color\":\"success\"}', NULL, '2025-12-06 10:27:39', '2025-12-06 10:27:39'),
('66b05ead-76a0-40fa-8010-34ba8cde9fe9', 'App\\Notifications\\TaskCompletedNotification', 'App\\Models\\User', 2, '{\"title\":\"Task Completed\",\"message\":\"Mark Andrew Soliman has completed task: Task 1: Backend Infrastructure & API Foundation\",\"action_url\":\"http:\\/\\/localhost:8000\\/admin\\/tasks\\/25\",\"task_id\":25,\"task_title\":\"Task 1: Backend Infrastructure & API Foundation\",\"completed_by\":\"Mark Andrew Soliman\"}', NULL, '2025-12-06 08:07:03', '2025-12-06 08:07:03'),
('66fb0c7c-7fe9-417b-89cc-4b9239d4697e', 'App\\Notifications\\NewServiceRequestNotification', 'App\\Models\\User', 2, '{\"type\":\"new_service_request\",\"title\":\"New Service Request\",\"message\":\"New service request \'Digital Strategy Consulting\' submitted by Mark Andrew Soliman (new user)\",\"action_url\":\"http:\\/\\/localhost\\/admin\\/requests\\/11\",\"service_request_id\":11,\"project_name\":\"Digital Strategy Consulting\",\"user_name\":\"Mark Andrew Soliman\",\"is_new_user\":true}', NULL, '2025-10-28 03:54:29', '2025-10-28 03:54:29'),
('66fea1bd-0eb8-449d-b990-a3eb97218e4d', 'App\\Notifications\\AdiutorRemovedFromProjectNotification', 'App\\Models\\User', 8, '{\"type\":\"adiutor_removed_from_project\",\"project_id\":18,\"project_title\":\"Ecommerce 3\",\"client_name\":\"John Smith\",\"removed_by\":\"Mark Admin\",\"message\":\"You have been removed from project \'Ecommerce 3\'\",\"icon\":\"user-minus\",\"color\":\"warning\"}', '2025-12-01 18:36:49', '2025-12-01 18:12:50', '2025-12-01 18:12:50'),
('6808f9cc-f499-4be2-97f5-8c5faba8784b', 'App\\Notifications\\DeliverableApprovedNotification', 'App\\Models\\User', 7, '{\"type\":\"deliverable_approved\",\"document_id\":15,\"document_name\":\"Github Repository\",\"task_id\":28,\"task_title\":\"Code Analysis Engine - JavaScript\\/TypeScript Support\",\"message\":\"Your deliverable \\\"Github Repository\\\" has been approved.\"}', NULL, '2025-12-06 10:30:22', '2025-12-06 10:30:22'),
('685be82a-0662-4583-bf65-a90edd56971c', 'App\\Notifications\\PaymentConfirmedNotification', 'App\\Models\\User', 1, '{\"type\":\"payment_confirmed\",\"title\":\"Payment Confirmed\",\"message\":\"Payment of \\u20b1400,000.00 confirmed for \'Ecommerce 3\'.\",\"action_url\":\"\",\"payment_id\":11,\"service_request_id\":35,\"amount\":\"400000.00\",\"payment_type\":\"Full Payment\",\"project_name\":\"Ecommerce 3\"}', '2025-11-14 13:40:23', '2025-11-14 12:06:01', '2025-11-14 12:06:01'),
('6a861441-b748-4350-950c-11bed62fbeac', 'App\\Notifications\\TaskCreatedNotification', 'App\\Models\\User', 18, '{\"type\":\"task_created\",\"task_id\":21,\"task_title\":\"Expedita labore sit\",\"project_title\":\"UNIMERCE \\u2013 Full E-Commerce Platform Development & Optimization\",\"priority\":\"high\",\"deadline\":\"2026-09-18T16:00:00.000000Z\",\"assigned_to\":\"Lena Quizon\",\"created_by\":\"Admin\",\"message\":\"New task \'Expedita labore sit\' has been created\",\"action_url\":\"http:\\/\\/localhost:8000\\/admin\\/tasks\\/21\",\"icon\":\"plus-square\",\"color\":\"success\"}', '2025-12-01 13:11:58', '2025-12-01 12:51:52', '2025-12-01 12:51:52'),
('6acf985b-149d-4df2-9607-523c9679e31b', 'App\\Notifications\\TaskCompletedNotification', 'App\\Models\\User', 1, '{\"title\":\"Task Completed\",\"message\":\"Mark Andrew has completed task: Task 1\",\"action_url\":\"http:\\/\\/localhost:8000\\/admin\\/tasks\\/16\",\"task_id\":16,\"task_title\":\"Task 1\",\"completed_by\":\"Mark Andrew\"}', '2025-11-14 10:04:38', '2025-10-29 08:50:46', '2025-10-29 08:50:46'),
('6b5a962a-d6d1-41d9-b2b2-53d2aa6906d5', 'App\\Notifications\\FileUploadedNotification', 'App\\Models\\User', 2, '{\"type\":\"file_uploaded\",\"file_name\":\"Material Design (Link)\",\"task_title\":\"Task 1: Backend Infrastructure & API Foundation\",\"task_id\":25,\"uploaded_by\":\"Mark Andrew Soliman\",\"is_deliverable\":true,\"message\":\"New deliverable \'Material Design (Link)\' uploaded for task \'Task 1: Backend Infrastructure & API Foundation\'\",\"action_url\":\"http:\\/\\/localhost:8000\\/adiutor\\/tasks\\/25\",\"icon\":\"package\",\"color\":\"success\"}', NULL, '2025-12-06 08:06:32', '2025-12-06 08:06:32');
INSERT INTO `notifications` (`id`, `type`, `notifiable_type`, `notifiable_id`, `data`, `read_at`, `created_at`, `updated_at`) VALUES
('6b92ccaf-e932-4f8d-b3bd-f88a8dff773c', 'App\\Notifications\\PaymentConfirmedNotification', 'App\\Models\\User', 3, '{\"type\":\"payment_confirmed\",\"title\":\"Payment Confirmed\",\"message\":\"Payment of \\u20b1425,000.00 confirmed for \'NovaSync Vendor Intelligence Dashboard\'.\",\"action_url\":\"\",\"payment_id\":15,\"service_request_id\":42,\"amount\":\"425000.00\",\"payment_type\":\"Full Payment\",\"project_name\":\"NovaSync Vendor Intelligence Dashboard\"}', '2025-12-06 05:35:39', '2025-12-05 17:52:20', '2025-12-05 17:52:20'),
('6d5b1710-5136-4eb1-babd-50f897a84e6b', 'App\\Notifications\\NewServiceRequestNotification', 'App\\Models\\User', 1, '{\"type\":\"new_service_request\",\"title\":\"New Service Request\",\"message\":\"New service request \'Dexter Drake\' submitted by Victoria Mckee (new user)\",\"action_url\":\"http:\\/\\/localhost\\/admin\\/requests\\/15\",\"service_request_id\":15,\"project_name\":\"Dexter Drake\",\"user_name\":\"Victoria Mckee\",\"is_new_user\":true}', '2025-11-14 10:04:38', '2025-10-28 03:54:52', '2025-10-28 03:54:52'),
('6d8eb09b-47c3-4aef-a47f-c4c27e17a8a0', 'App\\Notifications\\TaskCreatedNotification', 'App\\Models\\User', 18, '{\"type\":\"task_created\",\"task_id\":20,\"task_title\":\"Aperiam nisi repelle\",\"project_title\":\"Multi-Platform Study Cards System (Web + Android)\",\"priority\":\"high\",\"deadline\":null,\"assigned_to\":\"Sofia\",\"created_by\":\"Admin\",\"message\":\"New task \'Aperiam nisi repelle\' has been created\",\"action_url\":\"http:\\/\\/localhost:8000\\/admin\\/tasks\\/20\",\"icon\":\"plus-square\",\"color\":\"success\"}', '2025-12-01 12:18:54', '2025-12-01 12:18:41', '2025-12-01 12:18:41'),
('6dfe43a8-295f-4a18-9cc6-8b1c8566e759', 'App\\Notifications\\NewDeliverableAvailableNotification', 'App\\Models\\User', 3, '{\"type\":\"new_deliverable\",\"document_id\":17,\"document_name\":\"Bug Fixes Compilations (with code snippets)\",\"task_id\":28,\"task_title\":\"Code Analysis Engine - JavaScript\\/TypeScript Support\",\"project_title\":\"SynthAI - Autonomous Code Review and Technical Debt Analyzer\",\"message\":\"New deliverable available: Bug Fixes Compilations (with code snippets)\"}', NULL, '2025-12-06 10:30:03', '2025-12-06 10:30:03'),
('714561ab-96e6-46da-8085-2fa09576116d', 'App\\Notifications\\ProjectCompletedNotification', 'App\\Models\\User', 18, '{\"type\":\"project_completed\",\"title\":\"Project Completed\",\"message\":\"Your project \'Multi-Platform Study Cards System (Web + Android)\' has been completed!\",\"action_url\":\"http:\\/\\/localhost:8000\\/client\\/projects\\/20\",\"project_id\":20,\"project_title\":\"Multi-Platform Study Cards System (Web + Android)\",\"completion_date\":\"2025-12-01 12:21:01\"}', '2025-12-01 12:49:57', '2025-12-01 12:21:01', '2025-12-01 12:21:01'),
('7501ea21-ac55-43ec-82e9-4dcc17c38225', 'App\\Notifications\\PaymentConfirmedNotification', 'App\\Models\\User', 13, '{\"type\":\"payment_confirmed\",\"title\":\"Payment Confirmed\",\"message\":\"Payment confirmed for \'Tarik Michael\'. Project created.\",\"action_url\":\"\",\"service_request_id\":29,\"project_name\":\"Tarik Michael\"}', '2025-10-29 14:48:11', '2025-10-29 14:34:26', '2025-10-29 14:34:26'),
('760c9cf8-54e9-4799-b1ef-f62da965a6a2', 'App\\Notifications\\PaymentConfirmedNotification', 'App\\Models\\User', 1, '{\"type\":\"payment_confirmed\",\"title\":\"Payment Confirmed\",\"message\":\"Payment of \\u20b1250,000.00 confirmed for \'EdgeRelay - Decentralized Content Delivery Network for Small-Scale Creators\'.\",\"action_url\":\"\",\"payment_id\":16,\"service_request_id\":43,\"amount\":\"250000.00\",\"payment_type\":\"Full Payment\",\"project_name\":\"EdgeRelay - Decentralized Content Delivery Network for Small-Scale Creators\"}', NULL, '2025-12-05 17:55:58', '2025-12-05 17:55:58'),
('7bd955cf-b458-4868-9474-6fcb8c5be559', 'App\\Notifications\\PaymentConfirmedNotification', 'App\\Models\\User', 2, '{\"type\":\"payment_confirmed\",\"title\":\"Payment Confirmed\",\"message\":\"Payment of \\u20b1250,000.00 confirmed for \'EdgeRelay - Decentralized Content Delivery Network for Small-Scale Creators\'.\",\"action_url\":\"\",\"payment_id\":16,\"service_request_id\":43,\"amount\":\"250000.00\",\"payment_type\":\"Full Payment\",\"project_name\":\"EdgeRelay - Decentralized Content Delivery Network for Small-Scale Creators\"}', NULL, '2025-12-05 17:55:23', '2025-12-05 17:55:23'),
('7d39bc93-e66d-4615-bd5b-76a6c1db0b04', 'App\\Notifications\\RevisionApprovedNotification', 'App\\Models\\User', 7, '{\"type\":\"revision_approved\",\"revision_request_id\":5,\"document_id\":null,\"document_name\":\"N\\/A\",\"source_type\":\"project\",\"source_description\":\"Project: Complete Brand Identity Package\",\"task_id\":null,\"task_reopened\":false,\"requested_due_date\":null,\"admin_notes\":null,\"message\":\"Revision request approved for: Project: Complete Brand Identity Package\",\"action_url\":\"http:\\/\\/localhost:8000\\/adiutor\\/revisions\\/5\"}', '2025-12-06 06:45:51', '2025-12-01 12:57:06', '2025-12-01 12:57:06'),
('7e38bf67-3be5-43b6-ae66-9dc0457708ef', 'App\\Notifications\\ProjectCompletedNotification', 'App\\Models\\User', 12, '{\"type\":\"project_completed\",\"title\":\"Project Completed\",\"message\":\"Your project \'Digital Strategy Consulting\' has been completed!\",\"action_url\":\"http:\\/\\/localhost\\/client\\/projects\\/5\",\"project_id\":5,\"project_title\":\"Digital Strategy Consulting\",\"completion_date\":\"2025-10-28 11:54:42\"}', '2025-10-28 04:13:20', '2025-10-28 03:54:42', '2025-10-28 03:54:42'),
('7e92bcb4-3b5f-4844-b535-1a83f25b9219', 'App\\Notifications\\NewServiceRequestNotification', 'App\\Models\\User', 2, '{\"type\":\"new_service_request\",\"title\":\"New Service Request\",\"message\":\"New service request \'Serina Cote\' submitted by Cooper Case (new user)\",\"action_url\":\"http:\\/\\/localhost\\/admin\\/requests\\/13\",\"service_request_id\":13,\"project_name\":\"Serina Cote\",\"user_name\":\"Cooper Case\",\"is_new_user\":true}', NULL, '2025-10-28 03:54:48', '2025-10-28 03:54:48'),
('7fb96765-79ee-4e90-a7dc-3ff9f14280a5', 'App\\Notifications\\NewServiceRequestNotification', 'App\\Models\\User', 1, '{\"type\":\"new_service_request\",\"title\":\"New Service Request\",\"message\":\"New service request \'Digital Strategy Consulting\' submitted by Mark Andrew Soliman (new user)\",\"action_url\":\"http:\\/\\/localhost\\/admin\\/requests\\/11\",\"service_request_id\":11,\"project_name\":\"Digital Strategy Consulting\",\"user_name\":\"Mark Andrew Soliman\",\"is_new_user\":true}', '2025-11-14 10:04:38', '2025-10-28 03:53:56', '2025-10-28 03:53:56'),
('82aeb1e6-c8c3-437a-8845-7146b2139e62', 'App\\Notifications\\BudgetChangeRequestNotification', 'App\\Models\\User', 2, '{\"title\":\"Budget Change Request\",\"message\":\"Alex Developer has requested a budget change for task: TASK 2\",\"action_url\":\"http:\\/\\/localhost\\/admin\\/budget-requests\",\"task_id\":14,\"task_title\":\"TASK 2\",\"current_budget\":\"10000.00\",\"requested_budget\":\"25000\"}', NULL, '2025-10-28 03:54:38', '2025-10-28 03:54:38'),
('86298d43-f8bb-4a51-b3bd-3f872c518a2f', 'App\\Notifications\\TaskCompletedNotification', 'App\\Models\\User', 2, '{\"title\":\"Task Completed\",\"message\":\"Mark Andrew Soliman has completed task: Code Analysis Engine - JavaScript\\/TypeScript Support\",\"action_url\":\"http:\\/\\/localhost:8000\\/admin\\/tasks\\/28\",\"task_id\":28,\"task_title\":\"Code Analysis Engine - JavaScript\\/TypeScript Support\",\"completed_by\":\"Mark Andrew Soliman\"}', NULL, '2025-12-06 10:31:45', '2025-12-06 10:31:45'),
('88b9b438-08e0-4851-a0f5-3ce1a033112a', 'App\\Notifications\\FileUploadedNotification', 'App\\Models\\User', 1, '{\"type\":\"file_uploaded\",\"file_name\":\"Figma Design (Link)\",\"task_title\":\"Task 1: Backend Infrastructure & API Foundation\",\"task_id\":25,\"uploaded_by\":\"Mark Andrew Soliman\",\"is_deliverable\":true,\"message\":\"New deliverable \'Figma Design (Link)\' uploaded for task \'Task 1: Backend Infrastructure & API Foundation\'\",\"action_url\":\"http:\\/\\/localhost:8000\\/adiutor\\/tasks\\/25\",\"icon\":\"package\",\"color\":\"success\"}', NULL, '2025-12-06 08:04:27', '2025-12-06 08:04:27'),
('88dc8956-6b79-4e19-8587-3c5868d5da34', 'App\\Notifications\\RevisionApprovedNotification', 'App\\Models\\User', 7, '{\"type\":\"revision_approved\",\"revision_request_id\":7,\"document_id\":null,\"document_name\":\"N\\/A\",\"source_type\":\"task\",\"source_description\":\"Task: \",\"task_id\":28,\"task_reopened\":true,\"requested_due_date\":null,\"admin_notes\":null,\"message\":\"Revision request approved for: Task: \",\"action_url\":\"http:\\/\\/localhost:8000\\/adiutor\\/revisions\\/7\"}', '2025-12-06 11:02:40', '2025-12-06 11:02:13', '2025-12-06 11:02:13'),
('8a0bc104-e124-4c46-b598-6340177b3e4a', 'App\\Notifications\\PaymentConfirmedNotification', 'App\\Models\\User', 1, '{\"type\":\"payment_confirmed\",\"title\":\"Payment Confirmed\",\"message\":\"Payment confirmed for \'Tarik Michael\'. Project created.\",\"action_url\":\"\",\"service_request_id\":29,\"project_name\":\"Tarik Michael\"}', '2025-11-14 10:04:38', '2025-10-29 14:34:35', '2025-10-29 14:34:35'),
('8a540ee4-cbe5-4a50-b6ae-950af87a1832', 'App\\Notifications\\NewServiceRequestNotification', 'App\\Models\\User', 2, '{\"type\":\"new_service_request\",\"title\":\"New Service Request\",\"message\":\"New service request \'QuantumShield - Zero-Trust Security Orchestration Platform\' submitted by John Smith\",\"action_url\":\"http:\\/\\/localhost:8000\\/admin\\/requests\\/44\",\"service_request_id\":44,\"project_name\":\"QuantumShield - Zero-Trust Security Orchestration Platform\",\"user_name\":\"John Smith\",\"is_new_user\":false}', NULL, '2025-12-05 17:56:56', '2025-12-05 17:56:56'),
('8ad751f5-4cbf-494f-bceb-1daa477a05ac', 'App\\Notifications\\NewServiceRequestNotification', 'App\\Models\\User', 1, '{\"type\":\"new_service_request\",\"title\":\"New Service Request\",\"message\":\"New service request \'Drake Maynard\' submitted by Francis Lancaster (new user)\",\"action_url\":\"http:\\/\\/localhost\\/admin\\/requests\\/14\",\"service_request_id\":14,\"project_name\":\"Drake Maynard\",\"user_name\":\"Francis Lancaster\",\"is_new_user\":true}', '2025-11-14 10:04:38', '2025-10-28 03:54:49', '2025-10-28 03:54:49'),
('8b3c6a27-6256-471d-bea2-d7b07c05d134', 'App\\Notifications\\NewServiceRequestNotification', 'App\\Models\\User', 2, '{\"type\":\"new_service_request\",\"title\":\"New Service Request\",\"message\":\"New service request \'Ecommerce 2\' submitted by John Smith\",\"action_url\":\"http:\\/\\/localhost:8000\\/admin\\/requests\\/33\",\"service_request_id\":33,\"project_name\":\"Ecommerce 2\",\"user_name\":\"John Smith\",\"is_new_user\":false}', NULL, '2025-11-10 11:28:28', '2025-11-10 11:28:28'),
('8c7c1d99-a197-470d-ba29-24dbf412d8a8', 'App\\Notifications\\NewServiceRequestNotification', 'App\\Models\\User', 1, '{\"type\":\"new_service_request\",\"title\":\"New Service Request\",\"message\":\"New service request \'UNIMERCE \\u2013 Full E-Commerce Platform Development & Optimization\' submitted by Nissim Dalton\",\"action_url\":\"http:\\/\\/localhost:8000\\/admin\\/requests\\/40\",\"service_request_id\":40,\"project_name\":\"UNIMERCE \\u2013 Full E-Commerce Platform Development & Optimization\",\"user_name\":\"Nissim Dalton\",\"is_new_user\":false}', '2025-12-01 11:20:06', '2025-12-01 11:19:32', '2025-12-01 11:19:32'),
('8d3067fa-8208-4e5e-8295-42e8b5a90cdc', 'App\\Notifications\\NewServiceRequestNotification', 'App\\Models\\User', 2, '{\"type\":\"new_service_request\",\"title\":\"New Service Request\",\"message\":\"New service request \'UNIMERCE \\u2013 Full E-Commerce Platform Development & Optimization\' submitted by Nissim Dalton\",\"action_url\":\"http:\\/\\/localhost:8000\\/admin\\/requests\\/40\",\"service_request_id\":40,\"project_name\":\"UNIMERCE \\u2013 Full E-Commerce Platform Development & Optimization\",\"user_name\":\"Nissim Dalton\",\"is_new_user\":false}', NULL, '2025-12-01 11:19:34', '2025-12-01 11:19:34'),
('8d9c2f4d-40ab-4f1e-9752-28b5cfc375cd', 'App\\Notifications\\NewServiceRequestNotification', 'App\\Models\\User', 1, '{\"type\":\"new_service_request\",\"title\":\"New Service Request\",\"message\":\"New service request \'Website Redesign\' submitted by Lena Quizon (new user)\",\"action_url\":\"http:\\/\\/127.0.0.1:8000\\/admin\\/requests\\/30\",\"service_request_id\":30,\"project_name\":\"Website Redesign\",\"user_name\":\"Lena Quizon\",\"is_new_user\":true}', '2025-11-14 10:04:38', '2025-10-29 16:16:01', '2025-10-29 16:16:01'),
('8e24f55a-9149-4a00-a313-9df348c57ef5', 'App\\Notifications\\TaskDeletedNotification', 'App\\Models\\User', 2, '{\"type\":\"task_deleted\",\"task_data\":{\"id\":24,\"title\":\"Project Setup & Infrastructure\",\"project_title\":\"SynthAI - Autonomous Code Review and Technical Debt Analyzer\",\"assigned_to\":\"Unassigned\",\"status\":\"pending\",\"priority\":\"medium\"},\"deleted_by\":\"Mark Admin\",\"message\":\"Task \'Project Setup & Infrastructure\' has been deleted\",\"icon\":\"minus-square\",\"color\":\"danger\"}', NULL, '2025-12-05 20:52:05', '2025-12-05 20:52:05'),
('90049516-30eb-4f4e-873b-ce5b21d1daa4', 'App\\Notifications\\NewServiceRequestNotification', 'App\\Models\\User', 1, '{\"type\":\"new_service_request\",\"title\":\"New Service Request\",\"message\":\"New service request \'Game Mobile Application\' submitted by Sofia Lorraine\",\"action_url\":\"http:\\/\\/127.0.0.1:8000\\/admin\\/requests\\/37\",\"service_request_id\":37,\"project_name\":\"Game Mobile Application\",\"user_name\":\"Sofia Lorraine\",\"is_new_user\":false}', '2025-11-14 10:04:38', '2025-11-12 01:46:35', '2025-11-12 01:46:35'),
('908ce6b5-54e7-44ea-8541-70b415896dfb', 'App\\Notifications\\FileUploadedNotification', 'App\\Models\\User', 1, '{\"type\":\"file_uploaded\",\"file_name\":\"sales_report_20251203 (1).pdf\",\"task_title\":\"Task 1: Backend Infrastructure & API Foundation\",\"task_id\":25,\"uploaded_by\":\"Mark Andrew Soliman\",\"is_deliverable\":true,\"message\":\"New deliverable \'sales_report_20251203 (1).pdf\' uploaded for task \'Task 1: Backend Infrastructure & API Foundation\'\",\"action_url\":\"http:\\/\\/localhost:8000\\/adiutor\\/tasks\\/25\",\"icon\":\"package\",\"color\":\"success\"}', NULL, '2025-12-06 08:06:54', '2025-12-06 08:06:54'),
('91b8c899-8954-43bb-a5ca-73c7d7963c28', 'App\\Notifications\\ProjectCompletedNotification', 'App\\Models\\User', 18, '{\"type\":\"project_completed\",\"title\":\"Project Completed\",\"message\":\"Your project \'UNIMERCE \\u2013 Full E-Commerce Platform Development & Optimization\' has been completed!\",\"action_url\":\"http:\\/\\/localhost:8000\\/client\\/projects\\/19\",\"project_id\":19,\"project_title\":\"UNIMERCE \\u2013 Full E-Commerce Platform Development & Optimization\",\"completion_date\":\"2025-12-01 14:23:29\"}', '2025-12-01 15:03:41', '2025-12-01 14:23:29', '2025-12-01 14:23:29'),
('94c06557-3c40-4418-8158-ad4e70bb019d', 'App\\Notifications\\TaskCompletedNotification', 'App\\Models\\User', 1, '{\"title\":\"Task Completed\",\"message\":\"Mark Andrew has completed task: Task 1\",\"action_url\":\"http:\\/\\/localhost:8000\\/admin\\/tasks\\/15\",\"task_id\":15,\"task_title\":\"Task 1\",\"completed_by\":\"Mark Andrew\"}', '2025-11-14 10:04:38', '2025-10-29 00:08:22', '2025-10-29 00:08:22'),
('96376f19-7d85-419c-a1b0-1a0191c75d71', 'App\\Notifications\\PaymentConfirmedNotification', 'App\\Models\\User', 2, '{\"type\":\"payment_confirmed\",\"title\":\"Payment Confirmed\",\"message\":\"Payment confirmed for \'Tarik Michael\'. Project created.\",\"action_url\":\"\",\"service_request_id\":29,\"project_name\":\"Tarik Michael\"}', NULL, '2025-10-29 14:34:30', '2025-10-29 14:34:30'),
('96665797-b9e4-4760-b73a-0d58581d747a', 'App\\Notifications\\BudgetChangeRequestNotification', 'App\\Models\\User', 1, '{\"title\":\"Budget Change Request\",\"message\":\"Alex Developer has requested a budget change for task: TASK 2\",\"action_url\":\"http:\\/\\/localhost\\/admin\\/budget-requests\",\"task_id\":14,\"task_title\":\"TASK 2\",\"current_budget\":\"10000.00\",\"requested_budget\":\"25000\"}', '2025-11-14 10:04:38', '2025-10-28 03:54:38', '2025-10-28 03:54:38'),
('97e54fbb-a280-4c66-9bc2-786e40ba0886', 'App\\Notifications\\TaskDeletedNotification', 'App\\Models\\User', 3, '{\"type\":\"task_deleted\",\"task_data\":{\"id\":24,\"title\":\"Project Setup & Infrastructure\",\"project_title\":\"SynthAI - Autonomous Code Review and Technical Debt Analyzer\",\"assigned_to\":\"Unassigned\",\"status\":\"pending\",\"priority\":\"medium\"},\"deleted_by\":\"Mark Admin\",\"message\":\"Task \'Project Setup & Infrastructure\' has been deleted\",\"icon\":\"minus-square\",\"color\":\"danger\"}', '2025-12-06 05:35:39', '2025-12-05 20:52:05', '2025-12-05 20:52:05'),
('98cfb43b-19dc-423e-b37d-d6eb399e07e6', 'App\\Notifications\\NewServiceRequestNotification', 'App\\Models\\User', 2, '{\"type\":\"new_service_request\",\"title\":\"New Service Request\",\"message\":\"New service request \'Jaquelyn Burke\' submitted by Mark Andrew Soliman\",\"action_url\":\"http:\\/\\/localhost:8000\\/admin\\/requests\\/27\",\"service_request_id\":27,\"project_name\":\"Jaquelyn Burke\",\"user_name\":\"Mark Andrew Soliman\",\"is_new_user\":false}', NULL, '2025-10-29 13:54:19', '2025-10-29 13:54:19'),
('999f0820-7578-4b1c-9471-e0762ea81dda', 'App\\Notifications\\PaymentConfirmedNotification', 'App\\Models\\User', 1, '{\"type\":\"payment_confirmed\",\"title\":\"Payment Confirmed\",\"message\":\"Payment of \\u20b1425,000.00 confirmed for \'NovaSync Vendor Intelligence Dashboard\'.\",\"action_url\":\"\",\"payment_id\":15,\"service_request_id\":42,\"amount\":\"425000.00\",\"payment_type\":\"Full Payment\",\"project_name\":\"NovaSync Vendor Intelligence Dashboard\"}', NULL, '2025-12-05 17:52:23', '2025-12-05 17:52:23'),
('99ed05a2-fdc6-42ac-b54d-bc18da2e2436', 'App\\Notifications\\NewServiceRequestNotification', 'App\\Models\\User', 1, '{\"type\":\"new_service_request\",\"title\":\"New Service Request\",\"message\":\"New service request \'Tarik Michael\' submitted by Mark Andrew Soliman\",\"action_url\":\"http:\\/\\/localhost:8000\\/admin\\/requests\\/29\",\"service_request_id\":29,\"project_name\":\"Tarik Michael\",\"user_name\":\"Mark Andrew Soliman\",\"is_new_user\":false}', '2025-11-14 10:04:38', '2025-10-29 14:29:33', '2025-10-29 14:29:33'),
('9caa56c9-398e-43e7-85c2-52e4f758f220', 'App\\Notifications\\PaymentConfirmedNotification', 'App\\Models\\User', 2, '{\"type\":\"payment_confirmed\",\"title\":\"Payment Confirmed\",\"message\":\"Payment of \\u20b1499,000.00 confirmed for \'Multi-Platform Study Cards System (Web + Android)\'.\",\"action_url\":\"\",\"payment_id\":14,\"service_request_id\":41,\"amount\":\"499000.00\",\"payment_type\":\"Full Payment\",\"project_name\":\"Multi-Platform Study Cards System (Web + Android)\"}', NULL, '2025-12-01 12:14:37', '2025-12-01 12:14:37'),
('a05b263f-a39e-4884-ad13-af998b3a779d', 'App\\Notifications\\NewServiceRequestNotification', 'App\\Models\\User', 1, '{\"type\":\"new_service_request\",\"title\":\"New Service Request\",\"message\":\"New service request \'Eugenia Caldwell\' submitted by Emerson Jacobson (new user)\",\"action_url\":\"http:\\/\\/localhost:8000\\/admin\\/requests\\/21\",\"service_request_id\":21,\"project_name\":\"Eugenia Caldwell\",\"user_name\":\"Emerson Jacobson\",\"is_new_user\":true}', '2025-11-14 10:04:38', '2025-10-28 04:19:48', '2025-10-28 04:19:48'),
('a0d6c5f5-4f04-4105-92b0-2dff667e5d9e', 'App\\Notifications\\TaskCompletedNotification', 'App\\Models\\User', 2, '{\"title\":\"Task Completed\",\"message\":\"Alex Developer has completed task: TASK 1\",\"action_url\":\"http:\\/\\/localhost\\/admin\\/tasks\\/13\",\"task_id\":13,\"task_title\":\"TASK 1\",\"completed_by\":\"Alex Developer\"}', NULL, '2025-10-28 03:54:37', '2025-10-28 03:54:37'),
('a20cec77-c344-47ca-9084-1318d921ce06', 'App\\Notifications\\NewServiceRequestNotification', 'App\\Models\\User', 2, '{\"type\":\"new_service_request\",\"title\":\"New Service Request\",\"message\":\"New service request \'NovaSync Vendor Intelligence Dashboard\' submitted by John Smith\",\"action_url\":\"http:\\/\\/localhost:8000\\/admin\\/requests\\/42\",\"service_request_id\":42,\"project_name\":\"NovaSync Vendor Intelligence Dashboard\",\"user_name\":\"John Smith\",\"is_new_user\":false}', NULL, '2025-12-05 16:23:03', '2025-12-05 16:23:03'),
('a2fb3937-18af-4c6e-a15c-0206e5f13ae4', 'App\\Notifications\\PaymentConfirmedNotification', 'App\\Models\\User', 3, '{\"type\":\"payment_confirmed\",\"title\":\"Payment Confirmed\",\"message\":\"Payment of \\u20b1400,000.00 confirmed for \'SynthAI - Autonomous Code Review and Technical Debt Analyzer\'.\",\"action_url\":\"\",\"payment_id\":17,\"service_request_id\":45,\"amount\":\"400000.00\",\"payment_type\":\"Full Payment\",\"project_name\":\"SynthAI - Autonomous Code Review and Technical Debt Analyzer\"}', '2025-12-06 05:35:39', '2025-12-05 19:30:00', '2025-12-05 19:30:00'),
('a4a4d2d9-2525-4514-ae81-fb183edcc53a', 'App\\Notifications\\ProjectCompletedNotification', 'App\\Models\\User', 3, '{\"type\":\"project_completed\",\"title\":\"Project Completed\",\"message\":\"Your project \'Website SEO Optimization\' has been completed!\",\"action_url\":\"http:\\/\\/localhost:8000\\/client\\/projects\\/13\",\"project_id\":13,\"project_title\":\"Website SEO Optimization\",\"completion_date\":\"2025-11-10 12:52:29\"}', '2025-11-14 13:30:25', '2025-11-10 12:52:29', '2025-11-10 12:52:29'),
('a4bb7f44-474b-4f1f-9049-31b2d9350b11', 'App\\Notifications\\PaymentConfirmedNotification', 'App\\Models\\User', 1, '{\"type\":\"payment_confirmed\",\"title\":\"Payment Confirmed\",\"message\":\"Payment of \\u20b150,000.00 confirmed for \'Website SEO Optimization\'.\",\"action_url\":\"\",\"payment_id\":12,\"service_request_id\":9,\"amount\":\"50000.00\",\"payment_type\":\"Full Payment\",\"project_name\":\"Website SEO Optimization\"}', '2025-11-14 13:40:23', '2025-11-14 12:12:46', '2025-11-14 12:12:46'),
('a6a2eb33-9a56-4957-b1ae-40ea4d427dd1', 'App\\Notifications\\TaskCreatedNotification', 'App\\Models\\User', 2, '{\"type\":\"task_created\",\"task_id\":22,\"task_title\":\"Laboriosam similiqu\",\"project_title\":\"UNIMERCE \\u2013 Full E-Commerce Platform Development & Optimization\",\"priority\":\"urgent\",\"deadline\":\"2025-12-22T16:00:00.000000Z\",\"assigned_to\":\"Mark Andrew\",\"created_by\":\"Mark Admin\",\"message\":\"New task \'Laboriosam similiqu\' has been created\",\"action_url\":\"http:\\/\\/localhost:8000\\/admin\\/tasks\\/22\",\"icon\":\"plus-square\",\"color\":\"success\"}', NULL, '2025-12-01 12:53:40', '2025-12-01 12:53:40'),
('a7d04b55-78ab-4242-9139-4ea878dfe6f7', 'App\\Notifications\\NewServiceRequestNotification', 'App\\Models\\User', 2, '{\"type\":\"new_service_request\",\"title\":\"New Service Request\",\"message\":\"New service request \'Game Mobile Application\' submitted by Sofia Lorraine\",\"action_url\":\"http:\\/\\/127.0.0.1:8000\\/admin\\/requests\\/37\",\"service_request_id\":37,\"project_name\":\"Game Mobile Application\",\"user_name\":\"Sofia Lorraine\",\"is_new_user\":false}', NULL, '2025-11-12 01:46:37', '2025-11-12 01:46:37'),
('a8e9a571-098a-485d-9792-a5399d16e870', 'App\\Notifications\\TaskCreatedNotification', 'App\\Models\\User', 2, '{\"type\":\"task_created\",\"task_id\":29,\"task_title\":\"Issue Classification & Explanation Generation\",\"project_title\":\"SynthAI - Autonomous Code Review and Technical Debt Analyzer\",\"priority\":\"medium\",\"deadline\":null,\"assigned_to\":\"Princess Anne Azucena\",\"created_by\":\"Mark Admin\",\"message\":\"New task \'Issue Classification & Explanation Generation\' has been created\",\"action_url\":\"http:\\/\\/localhost:8000\\/admin\\/tasks\\/29\",\"icon\":\"plus-square\",\"color\":\"success\"}', NULL, '2025-12-06 10:24:57', '2025-12-06 10:24:57'),
('a8f2ffc5-743a-4d2f-8728-bcb35e988f05', 'App\\Notifications\\RevisionRequestedNotification', 'App\\Models\\User', 2, '{\"type\":\"revision_requested\",\"revision_request_id\":7,\"document_id\":null,\"document_name\":\"N\\/A\",\"client_name\":\"John Smith\",\"source_type\":\"task\",\"source_description\":\"Task: \",\"reason\":\"We need Vue.js-specific linting beyond standard JavaScript analysis. Our team uses Vue 3 composition\",\"message\":\"New revision request from John Smith\",\"action_url\":\"http:\\/\\/localhost:8000\\/admin\\/revisions\\/7\"}', NULL, '2025-12-06 10:59:29', '2025-12-06 10:59:29'),
('acd6c0a5-e8d5-44df-a70c-2b7a3b4bb22c', 'App\\Notifications\\NewServiceRequestNotification', 'App\\Models\\User', 1, '{\"type\":\"new_service_request\",\"title\":\"New Service Request\",\"message\":\"New service request \'TechStartup Corporate Website\' submitted by John Smith\",\"action_url\":\"http:\\/\\/localhost:8000\\/admin\\/requests\\/1\",\"service_request_id\":1,\"project_name\":\"TechStartup Corporate Website\",\"user_name\":\"John Smith\",\"is_new_user\":false}', '2025-11-14 10:04:38', '2025-10-29 13:54:26', '2025-10-29 13:54:26'),
('ad02be6b-68e3-4d61-99f9-6e80e6d41e94', 'App\\Notifications\\PaymentConfirmedNotification', 'App\\Models\\User', 13, '{\"type\":\"payment_confirmed\",\"title\":\"Payment Confirmed\",\"message\":\"Payment confirmed for \'Tarik Michael\'. Project created.\",\"action_url\":\"\",\"service_request_id\":29,\"project_name\":\"Tarik Michael\"}', '2025-10-29 14:48:07', '2025-10-29 14:34:33', '2025-10-29 14:34:33'),
('ad14bd73-276c-4800-8206-2327dc3c5d05', 'App\\Notifications\\TaskCreatedNotification', 'App\\Models\\User', 3, '{\"type\":\"task_created\",\"task_id\":25,\"task_title\":\"Task 1: Backend Infrastructure & API Foundation\",\"project_title\":\"SynthAI - Autonomous Code Review and Technical Debt Analyzer\",\"priority\":\"medium\",\"deadline\":null,\"assigned_to\":\"Unassigned\",\"created_by\":\"Admin\",\"message\":\"New task \'Task 1: Backend Infrastructure & API Foundation\' has been created\",\"action_url\":\"http:\\/\\/localhost:8000\\/admin\\/tasks\\/25\",\"icon\":\"plus-square\",\"color\":\"success\"}', NULL, '2025-12-06 05:38:04', '2025-12-06 05:38:04'),
('b14ac9b1-cfaf-470f-a08c-88bd9570ea03', 'App\\Notifications\\PaymentConfirmedNotification', 'App\\Models\\User', 32, '{\"type\":\"payment_confirmed\",\"title\":\"Payment Confirmed\",\"message\":\"Payment of \\u20b1280,000.00 confirmed for \'Graphics Design\'.\",\"action_url\":\"\",\"payment_id\":10,\"service_request_id\":38,\"amount\":\"280000.00\",\"payment_type\":\"Full Payment\",\"project_name\":\"Graphics Design\"}', NULL, '2025-11-14 11:47:05', '2025-11-14 11:47:05'),
('b15a1ad5-a18e-4102-a890-c6825b379dce', 'App\\Notifications\\RevisionRequestedNotification', 'App\\Models\\User', 1, '{\"type\":\"revision_requested\",\"revision_request_id\":7,\"document_id\":null,\"document_name\":\"N\\/A\",\"client_name\":\"John Smith\",\"source_type\":\"task\",\"source_description\":\"Task: \",\"reason\":\"We need Vue.js-specific linting beyond standard JavaScript analysis. Our team uses Vue 3 composition\",\"message\":\"New revision request from John Smith\",\"action_url\":\"http:\\/\\/localhost:8000\\/admin\\/revisions\\/7\"}', NULL, '2025-12-06 10:59:27', '2025-12-06 10:59:27'),
('b198c1f1-abd2-495f-8808-0aaed6b6b6f8', 'App\\Notifications\\TaskCreatedNotification', 'App\\Models\\User', 18, '{\"type\":\"task_created\",\"task_id\":22,\"task_title\":\"Laboriosam similiqu\",\"project_title\":\"UNIMERCE \\u2013 Full E-Commerce Platform Development & Optimization\",\"priority\":\"urgent\",\"deadline\":\"2025-12-22T16:00:00.000000Z\",\"assigned_to\":\"Mark Andrew\",\"created_by\":\"Admin\",\"message\":\"New task \'Laboriosam similiqu\' has been created\",\"action_url\":\"http:\\/\\/localhost:8000\\/admin\\/tasks\\/22\",\"icon\":\"plus-square\",\"color\":\"success\"}', '2025-12-01 13:11:56', '2025-12-01 12:53:40', '2025-12-01 12:53:40'),
('b39b107f-e65c-44db-9943-68bd0bbe2fb3', 'App\\Notifications\\NewServiceRequestNotification', 'App\\Models\\User', 1, '{\"type\":\"new_service_request\",\"title\":\"New Service Request\",\"message\":\"New service request \'Ecommerce 3\' submitted by John Smith\",\"action_url\":\"http:\\/\\/localhost:8000\\/admin\\/requests\\/34\",\"service_request_id\":34,\"project_name\":\"Ecommerce 3\",\"user_name\":\"John Smith\",\"is_new_user\":false}', '2025-11-14 10:04:38', '2025-11-10 11:29:05', '2025-11-10 11:29:05'),
('b69ce148-660e-4e16-b829-161ab5b71ad8', 'App\\Notifications\\NewServiceRequestNotification', 'App\\Models\\User', 2, '{\"type\":\"new_service_request\",\"title\":\"New Service Request\",\"message\":\"New service request \'Dexter Drake\' submitted by Victoria Mckee (new user)\",\"action_url\":\"http:\\/\\/localhost\\/admin\\/requests\\/15\",\"service_request_id\":15,\"project_name\":\"Dexter Drake\",\"user_name\":\"Victoria Mckee\",\"is_new_user\":true}', NULL, '2025-10-28 03:54:53', '2025-10-28 03:54:53'),
('b82dba8a-4239-47fd-a4a5-0c3ce086ebcb', 'App\\Notifications\\RevisionRequestedNotification', 'App\\Models\\User', 7, '{\"type\":\"revision_requested\",\"revision_request_id\":7,\"document_id\":null,\"document_name\":\"N\\/A\",\"client_name\":\"John Smith\",\"source_type\":\"task\",\"source_description\":\"Task: \",\"reason\":\"We need Vue.js-specific linting beyond standard JavaScript analysis. Our team uses Vue 3 composition\",\"message\":\"New revision request from John Smith\",\"action_url\":\"http:\\/\\/localhost:8000\\/admin\\/revisions\\/7\"}', NULL, '2025-12-06 10:59:30', '2025-12-06 10:59:30'),
('b864546b-269f-4a94-bb61-89511ba0ac51', 'App\\Notifications\\NewServiceRequestNotification', 'App\\Models\\User', 2, '{\"type\":\"new_service_request\",\"title\":\"New Service Request\",\"message\":\"New service request \'Tarik Michael\' submitted by Mark Andrew Soliman\",\"action_url\":\"http:\\/\\/localhost:8000\\/admin\\/requests\\/29\",\"service_request_id\":29,\"project_name\":\"Tarik Michael\",\"user_name\":\"Mark Andrew Soliman\",\"is_new_user\":false}', NULL, '2025-10-29 14:29:37', '2025-10-29 14:29:37'),
('b878dd7d-8936-40a7-82fd-7b7491b14b89', 'App\\Notifications\\NewServiceRequestNotification', 'App\\Models\\User', 1, '{\"type\":\"new_service_request\",\"title\":\"New Service Request\",\"message\":\"New service request \'Jaquelyn Burke\' submitted by Mark Andrew Soliman\",\"action_url\":\"http:\\/\\/localhost:8000\\/admin\\/requests\\/25\",\"service_request_id\":25,\"project_name\":\"Jaquelyn Burke\",\"user_name\":\"Mark Andrew Soliman\",\"is_new_user\":false}', '2025-11-14 10:04:38', '2025-10-29 13:51:56', '2025-10-29 13:51:56'),
('baff726a-74c0-4d55-9663-d414985cb52f', 'App\\Notifications\\PaymentConfirmedNotification', 'App\\Models\\User', 2, '{\"type\":\"payment_confirmed\",\"title\":\"Payment Confirmed\",\"message\":\"Payment of \\u20b1400,000.00 confirmed for \'Ecommerce 3\'.\",\"action_url\":\"\",\"payment_id\":11,\"service_request_id\":35,\"amount\":\"400000.00\",\"payment_type\":\"Full Payment\",\"project_name\":\"Ecommerce 3\"}', NULL, '2025-11-14 12:06:01', '2025-11-14 12:06:01'),
('bdcada05-1609-4121-893e-15b4b5deb61e', 'App\\Notifications\\TaskCompletedNotification', 'App\\Models\\User', 2, '{\"title\":\"Task Completed\",\"message\":\"Alex Developer has completed task: TASK 2\",\"action_url\":\"http:\\/\\/localhost\\/admin\\/tasks\\/14\",\"task_id\":14,\"task_title\":\"TASK 2\",\"completed_by\":\"Alex Developer\"}', NULL, '2025-10-28 03:54:41', '2025-10-28 03:54:41'),
('c0079132-03d1-4870-8b71-7f83d7fa7987', 'App\\Notifications\\NewServiceRequestNotification', 'App\\Models\\User', 1, '{\"type\":\"new_service_request\",\"title\":\"New Service Request\",\"message\":\"New service request \'Website Redesign\' submitted by Lena Quizon\",\"action_url\":\"http:\\/\\/127.0.0.1:8000\\/admin\\/requests\\/39\",\"service_request_id\":39,\"project_name\":\"Website Redesign\",\"user_name\":\"Lena Quizon\",\"is_new_user\":false}', '2025-11-14 10:04:38', '2025-11-12 08:09:02', '2025-11-12 08:09:02'),
('c0695f0f-c970-4c45-b657-21dfe7e8491e', 'App\\Notifications\\TaskCompletedNotification', 'App\\Models\\User', 2, '{\"title\":\"Task Completed\",\"message\":\"Mark Andrew has completed task: Task 1\",\"action_url\":\"http:\\/\\/localhost:8000\\/admin\\/tasks\\/16\",\"task_id\":16,\"task_title\":\"Task 1\",\"completed_by\":\"Mark Andrew\"}', NULL, '2025-10-29 08:50:49', '2025-10-29 08:50:49'),
('c1d9a693-a1cd-437d-b2a6-30ca655d29f2', 'App\\Notifications\\DeliverableApprovedNotification', 'App\\Models\\User', 7, '{\"type\":\"deliverable_approved\",\"document_id\":16,\"document_name\":\"Documentation\",\"task_id\":28,\"task_title\":\"Code Analysis Engine - JavaScript\\/TypeScript Support\",\"message\":\"Your deliverable \\\"Documentation\\\" has been approved.\"}', NULL, '2025-12-06 10:30:09', '2025-12-06 10:30:09'),
('c1dd506b-3227-4c53-a421-d61772693921', 'App\\Notifications\\NewServiceRequestNotification', 'App\\Models\\User', 1, '{\"type\":\"new_service_request\",\"title\":\"New Service Request\",\"message\":\"New service request \'Jordan Huber\' submitted by Nissim Dalton (new user)\",\"action_url\":\"http:\\/\\/localhost\\/admin\\/requests\\/16\",\"service_request_id\":16,\"project_name\":\"Jordan Huber\",\"user_name\":\"Nissim Dalton\",\"is_new_user\":true}', '2025-11-14 10:04:38', '2025-10-28 03:54:54', '2025-10-28 03:54:54'),
('c20202f3-777b-430c-87ee-292207b2bbed', 'App\\Notifications\\PaymentConfirmedNotification', 'App\\Models\\User', 1, '{\"type\":\"payment_confirmed\",\"title\":\"Payment Confirmed\",\"message\":\"Payment of \\u20b1499,000.00 confirmed for \'Multi-Platform Study Cards System (Web + Android)\'.\",\"action_url\":\"\",\"payment_id\":14,\"service_request_id\":41,\"amount\":\"499000.00\",\"payment_type\":\"Full Payment\",\"project_name\":\"Multi-Platform Study Cards System (Web + Android)\"}', '2025-12-01 17:34:24', '2025-12-01 12:14:37', '2025-12-01 12:14:37'),
('c35f8f3b-6a60-4831-83e1-4fec867b9677', 'App\\Notifications\\NewDeliverableAvailableNotification', 'App\\Models\\User', 3, '{\"type\":\"new_deliverable\",\"document_id\":13,\"document_name\":\"Material Design\",\"task_id\":25,\"task_title\":\"Task 1: Backend Infrastructure & API Foundation\",\"project_title\":\"SynthAI - Autonomous Code Review and Technical Debt Analyzer\",\"message\":\"New deliverable available: Material Design\"}', NULL, '2025-12-06 08:38:13', '2025-12-06 08:38:13'),
('c385c137-a166-4f20-9a59-5423b6fe0f39', 'App\\Notifications\\PaymentConfirmedNotification', 'App\\Models\\User', 2, '{\"type\":\"payment_confirmed\",\"title\":\"Payment Confirmed\",\"message\":\"Payment of \\u20b1400,000.00 confirmed for \'UNIMERCE \\u2013 Full E-Commerce Platform Development & Optimization\'.\",\"action_url\":\"\",\"payment_id\":13,\"service_request_id\":40,\"amount\":\"400000.00\",\"payment_type\":\"Full Payment\",\"project_name\":\"UNIMERCE \\u2013 Full E-Commerce Platform Development & Optimization\"}', NULL, '2025-12-01 11:35:05', '2025-12-01 11:35:05'),
('c3a7c3e9-d478-4b8b-9fa2-95b99a07607d', 'App\\Notifications\\NewServiceRequestNotification', 'App\\Models\\User', 2, '{\"type\":\"new_service_request\",\"title\":\"New Service Request\",\"message\":\"New service request \'Graphics Design\' submitted by Sofia Lorraine\",\"action_url\":\"http:\\/\\/127.0.0.1:8000\\/admin\\/requests\\/38\",\"service_request_id\":38,\"project_name\":\"Graphics Design\",\"user_name\":\"Sofia Lorraine\",\"is_new_user\":false}', NULL, '2025-11-12 02:01:54', '2025-11-12 02:01:54'),
('c3f01440-f92f-466d-bc7b-9111b09e4b26', 'App\\Notifications\\PaymentConfirmedNotification', 'App\\Models\\User', 2, '{\"type\":\"payment_confirmed\",\"title\":\"Payment Confirmed\",\"message\":\"Payment confirmed for \'Digital Strategy Consulting\'. Project created.\",\"action_url\":\"http:\\/\\/localhost\\/admin\\/projects\\/5\",\"service_request_id\":11,\"project_id\":5,\"payment_id\":3,\"project_name\":\"Digital Strategy Consulting\"}', NULL, '2025-10-28 03:54:34', '2025-10-28 03:54:34'),
('c4150ba8-1625-455e-acc3-414e51d6f758', 'App\\Notifications\\PaymentConfirmedNotification', 'App\\Models\\User', 2, '{\"type\":\"payment_confirmed\",\"title\":\"Payment Confirmed\",\"message\":\"Payment confirmed for \'Tarik Michael\'. Project created.\",\"action_url\":\"\",\"service_request_id\":29,\"project_name\":\"Tarik Michael\"}', NULL, '2025-10-29 14:34:35', '2025-10-29 14:34:35'),
('c6af7f9b-f591-4159-94ea-1d53e663f150', 'App\\Notifications\\ProjectCompletedNotification', 'App\\Models\\User', 3, '{\"type\":\"project_completed\",\"title\":\"Project Completed\",\"message\":\"Your project \'Ecommerce 3\' has been completed!\",\"action_url\":\"http:\\/\\/localhost:8000\\/client\\/projects\\/14\",\"project_id\":14,\"project_title\":\"Ecommerce 3\",\"completion_date\":\"2025-11-10 12:09:25\"}', '2025-11-14 13:30:25', '2025-11-10 12:09:25', '2025-11-10 12:09:25'),
('c8f6bf3e-f9ed-46e8-9fa1-76af53cedf4e', 'App\\Notifications\\PaymentConfirmedNotification', 'App\\Models\\User', 2, '{\"type\":\"payment_confirmed\",\"title\":\"Payment Confirmed\",\"message\":\"Payment of \\u20b150,000.00 confirmed for \'Website SEO Optimization\'.\",\"action_url\":\"\",\"payment_id\":12,\"service_request_id\":9,\"amount\":\"50000.00\",\"payment_type\":\"Full Payment\",\"project_name\":\"Website SEO Optimization\"}', NULL, '2025-11-14 12:12:47', '2025-11-14 12:12:47'),
('c9590bdc-2615-4887-b31c-9e0bc99973b5', 'App\\Notifications\\NewServiceRequestNotification', 'App\\Models\\User', 2, '{\"type\":\"new_service_request\",\"title\":\"New Service Request\",\"message\":\"New service request \'Ecommerce 3\' submitted by John Smith\",\"action_url\":\"http:\\/\\/localhost:8000\\/admin\\/requests\\/34\",\"service_request_id\":34,\"project_name\":\"Ecommerce 3\",\"user_name\":\"John Smith\",\"is_new_user\":false}', NULL, '2025-11-10 11:29:07', '2025-11-10 11:29:07'),
('c9d468ab-cc25-4751-b2ca-7824a3eeec08', 'App\\Notifications\\TaskCreatedNotification', 'App\\Models\\User', 2, '{\"type\":\"task_created\",\"task_id\":27,\"task_title\":\"Code Analysis Engine - Python Support\",\"project_title\":\"SynthAI - Autonomous Code Review and Technical Debt Analyzer\",\"priority\":\"medium\",\"deadline\":null,\"assigned_to\":\"Princess Anne Azucena\",\"created_by\":\"Mark Admin\",\"message\":\"New task \'Code Analysis Engine - Python Support\' has been created\",\"action_url\":\"http:\\/\\/localhost:8000\\/admin\\/tasks\\/27\",\"icon\":\"plus-square\",\"color\":\"success\"}', NULL, '2025-12-06 07:45:46', '2025-12-06 07:45:46'),
('cb007660-4392-4906-a0b8-33c40e6d4279', 'App\\Notifications\\NewServiceRequestNotification', 'App\\Models\\User', 1, '{\"type\":\"new_service_request\",\"title\":\"New Service Request\",\"message\":\"New service request \'Graphics Design\' submitted by Sofia Lorraine\",\"action_url\":\"http:\\/\\/127.0.0.1:8000\\/admin\\/requests\\/38\",\"service_request_id\":38,\"project_name\":\"Graphics Design\",\"user_name\":\"Sofia Lorraine\",\"is_new_user\":false}', '2025-11-14 10:04:38', '2025-11-12 02:01:52', '2025-11-12 02:01:52'),
('cb0eaa1e-62d6-4fae-b75a-2e5a29a871cc', 'App\\Notifications\\TaskUpdatedNotification', 'App\\Models\\User', 7, '{\"type\":\"task_updated\",\"task_id\":25,\"task_title\":\"Backend Infrastructure & API Foundation\",\"project_title\":\"SynthAI - Autonomous Code Review and Technical Debt Analyzer\",\"changes\":{\"title\":{\"old\":\"Task 1: Backend Infrastructure & API Foundation\",\"new\":\"Backend Infrastructure & API Foundation\"}},\"changes_text\":\"Title: Task 1: Backend Infrastructure & API Foundation \\u2192 Backend Infrastructure & API Foundation\",\"updated_by\":\"Mark Admin\",\"message\":\"Task \'Backend Infrastructure & API Foundation\' has been updated: Title: Task 1: Backend Infrastructure & API Foundation \\u2192 Backend Infrastructure & API Foundation\",\"action_url\":\"http:\\/\\/localhost:8000\\/admin\\/tasks\\/25\",\"icon\":\"edit\",\"color\":\"info\"}', NULL, '2025-12-06 10:25:18', '2025-12-06 10:25:18'),
('cde82aa0-cfca-4348-aa68-ac7f58f56fc5', 'App\\Notifications\\PaymentConfirmedNotification', 'App\\Models\\User', 2, '{\"type\":\"payment_confirmed\",\"title\":\"Payment Confirmed\",\"message\":\"Payment of \\u20b1400,000.00 confirmed for \'SynthAI - Autonomous Code Review and Technical Debt Analyzer\'.\",\"action_url\":\"\",\"payment_id\":17,\"service_request_id\":45,\"amount\":\"400000.00\",\"payment_type\":\"Full Payment\",\"project_name\":\"SynthAI - Autonomous Code Review and Technical Debt Analyzer\"}', NULL, '2025-12-05 19:30:03', '2025-12-05 19:30:03'),
('ce106c5a-e1f6-434b-91fd-01d565118e3f', 'App\\Notifications\\PaymentConfirmedNotification', 'App\\Models\\User', 1, '{\"type\":\"payment_confirmed\",\"title\":\"Payment Confirmed\",\"message\":\"Payment of \\u20b1250,000.00 confirmed for \'EdgeRelay - Decentralized Content Delivery Network for Small-Scale Creators\'.\",\"action_url\":\"\",\"payment_id\":16,\"service_request_id\":43,\"amount\":\"250000.00\",\"payment_type\":\"Full Payment\",\"project_name\":\"EdgeRelay - Decentralized Content Delivery Network for Small-Scale Creators\"}', NULL, '2025-12-05 17:55:23', '2025-12-05 17:55:23'),
('cf80a36e-2f33-44db-b81e-08887c77ac22', 'App\\Notifications\\DeliverableApprovedNotification', 'App\\Models\\User', 7, '{\"type\":\"deliverable_approved\",\"document_id\":12,\"document_name\":\"Figma Design\",\"task_id\":25,\"task_title\":\"Task 1: Backend Infrastructure & API Foundation\",\"message\":\"Your deliverable \\\"Figma Design\\\" has been approved.\"}', NULL, '2025-12-06 08:38:19', '2025-12-06 08:38:19'),
('d2a28707-177e-44ed-957c-84e37cb55acb', 'App\\Notifications\\TaskCreatedNotification', 'App\\Models\\User', 3, '{\"type\":\"task_created\",\"task_id\":24,\"task_title\":\"Project Setup & Infrastructure\",\"project_title\":\"SynthAI - Autonomous Code Review and Technical Debt Analyzer\",\"priority\":\"medium\",\"deadline\":null,\"assigned_to\":\"Unassigned\",\"created_by\":\"Admin\",\"message\":\"New task \'Project Setup & Infrastructure\' has been created\",\"action_url\":\"http:\\/\\/localhost:8000\\/admin\\/tasks\\/24\",\"icon\":\"plus-square\",\"color\":\"success\"}', '2025-12-06 05:35:39', '2025-12-05 19:52:07', '2025-12-05 19:52:07'),
('d390ce4b-cc43-4fb4-b832-2695b84abc57', 'App\\Notifications\\NewDeliverableAvailableNotification', 'App\\Models\\User', 3, '{\"type\":\"new_deliverable\",\"document_id\":14,\"document_name\":\"sales_report_20251203 (1).pdf\",\"task_id\":25,\"task_title\":\"Task 1: Backend Infrastructure & API Foundation\",\"project_title\":\"SynthAI - Autonomous Code Review and Technical Debt Analyzer\",\"message\":\"New deliverable available: sales_report_20251203 (1).pdf\"}', NULL, '2025-12-06 08:34:47', '2025-12-06 08:34:47'),
('d3a3e00d-f75c-4e69-bd45-5c0360ef44ae', 'App\\Notifications\\PaymentConfirmedNotification', 'App\\Models\\User', 3, '{\"type\":\"payment_confirmed\",\"title\":\"Payment Confirmed\",\"message\":\"Payment of \\u20b1425,000.00 confirmed for \'NovaSync Vendor Intelligence Dashboard\'.\",\"action_url\":\"\",\"payment_id\":15,\"service_request_id\":42,\"amount\":\"425000.00\",\"payment_type\":\"Full Payment\",\"project_name\":\"NovaSync Vendor Intelligence Dashboard\"}', '2025-12-06 05:35:39', '2025-12-05 17:50:12', '2025-12-05 17:50:12'),
('d3e5b7af-617c-4937-88f8-e9e8a8e30cf2', 'App\\Notifications\\NewServiceRequestNotification', 'App\\Models\\User', 1, '{\"type\":\"new_service_request\",\"title\":\"New Service Request\",\"message\":\"New service request \'NovaSync Vendor Intelligence Dashboard\' submitted by John Smith\",\"action_url\":\"http:\\/\\/localhost:8000\\/admin\\/requests\\/42\",\"service_request_id\":42,\"project_name\":\"NovaSync Vendor Intelligence Dashboard\",\"user_name\":\"John Smith\",\"is_new_user\":false}', '2025-12-05 17:17:02', '2025-12-05 16:23:01', '2025-12-05 16:23:01'),
('d417d966-a30f-4087-8d94-b985e43782da', 'App\\Notifications\\NewServiceRequestNotification', 'App\\Models\\User', 1, '{\"type\":\"new_service_request\",\"title\":\"New Service Request\",\"message\":\"New service request \'Digital Strategy Consulting\' submitted by Mark Andrew Zapatero Soliman (new user)\",\"action_url\":\"http:\\/\\/localhost\\/admin\\/requests\\/12\",\"service_request_id\":12,\"project_name\":\"Digital Strategy Consulting\",\"user_name\":\"Mark Andrew Zapatero Soliman\",\"is_new_user\":true}', '2025-11-14 10:04:38', '2025-10-28 03:54:44', '2025-10-28 03:54:44'),
('d4a8d071-b037-4e3c-9719-2e0d339ed739', 'App\\Notifications\\ProjectCompletedNotification', 'App\\Models\\User', 3, '{\"type\":\"project_completed\",\"title\":\"Project Completed\",\"message\":\"Your project \'Ecommerce 3\' has been completed!\",\"action_url\":\"http:\\/\\/localhost:8000\\/client\\/projects\\/14\",\"project_id\":14,\"project_title\":\"Ecommerce 3\",\"completion_date\":\"2025-11-10 12:05:52\"}', '2025-11-14 13:30:25', '2025-11-10 12:05:52', '2025-11-10 12:05:52'),
('d5107d15-e7d4-40a5-ac9f-53572211bcbd', 'App\\Notifications\\ProjectCompletedNotification', 'App\\Models\\User', 3, '{\"type\":\"project_completed\",\"title\":\"Project Completed\",\"message\":\"Your project \'Ecommerce 3\' has been completed!\",\"action_url\":\"http:\\/\\/localhost:8000\\/client\\/projects\\/14\",\"project_id\":14,\"project_title\":\"Ecommerce 3\",\"completion_date\":\"2025-11-10 12:11:21\"}', '2025-11-14 13:30:25', '2025-11-10 12:11:21', '2025-11-10 12:11:21'),
('d6a776b0-3645-4d4e-93c2-76037acf5bbe', 'App\\Notifications\\TaskCreatedNotification', 'App\\Models\\User', 2, '{\"type\":\"task_created\",\"task_id\":24,\"task_title\":\"Project Setup & Infrastructure\",\"project_title\":\"SynthAI - Autonomous Code Review and Technical Debt Analyzer\",\"priority\":\"medium\",\"deadline\":null,\"assigned_to\":\"Unassigned\",\"created_by\":\"Mark Admin\",\"message\":\"New task \'Project Setup & Infrastructure\' has been created\",\"action_url\":\"http:\\/\\/localhost:8000\\/admin\\/tasks\\/24\",\"icon\":\"plus-square\",\"color\":\"success\"}', NULL, '2025-12-05 19:52:07', '2025-12-05 19:52:07'),
('d6a9b98f-346d-4450-af85-7cc462e4f8a7', 'App\\Notifications\\DeliverableApprovedNotification', 'App\\Models\\User', 7, '{\"type\":\"deliverable_approved\",\"document_id\":13,\"document_name\":\"Material Design\",\"task_id\":25,\"task_title\":\"Task 1: Backend Infrastructure & API Foundation\",\"message\":\"Your deliverable \\\"Material Design\\\" has been approved.\"}', NULL, '2025-12-06 08:38:13', '2025-12-06 08:38:13'),
('d858be7f-5b7b-479d-a619-b6719714e6e9', 'App\\Notifications\\AdiutorRemovedFromProjectNotification', 'App\\Models\\User', 34, '{\"type\":\"adiutor_removed_from_project\",\"project_id\":18,\"project_title\":\"Ecommerce 3\",\"client_name\":\"John Smith\",\"removed_by\":\"Mark Admin\",\"message\":\"You have been removed from project \'Ecommerce 3\'\",\"icon\":\"user-minus\",\"color\":\"warning\"}', NULL, '2025-12-01 18:13:05', '2025-12-01 18:13:05'),
('d9cb26be-d609-46e2-8fb6-e38ad7d8e849', 'App\\Notifications\\PaymentConfirmedNotification', 'App\\Models\\User', 1, '{\"type\":\"payment_confirmed\",\"title\":\"Payment Confirmed\",\"message\":\"Payment of \\u20b1400,000.00 confirmed for \'SynthAI - Autonomous Code Review and Technical Debt Analyzer\'.\",\"action_url\":\"\",\"payment_id\":17,\"service_request_id\":45,\"amount\":\"400000.00\",\"payment_type\":\"Full Payment\",\"project_name\":\"SynthAI - Autonomous Code Review and Technical Debt Analyzer\"}', NULL, '2025-12-05 19:30:03', '2025-12-05 19:30:03'),
('de3f6605-4346-4f44-9625-8af5ab5e31d1', 'App\\Notifications\\NewServiceRequestNotification', 'App\\Models\\User', 1, '{\"type\":\"new_service_request\",\"title\":\"New Service Request\",\"message\":\"New service request \'Sigourney Lynn\' submitted by Mark Andrew Soliman\",\"action_url\":\"http:\\/\\/localhost:8000\\/admin\\/requests\\/28\",\"service_request_id\":28,\"project_name\":\"Sigourney Lynn\",\"user_name\":\"Mark Andrew Soliman\",\"is_new_user\":false}', '2025-11-14 10:04:38', '2025-10-29 14:28:54', '2025-10-29 14:28:54'),
('e1772ac5-5b8a-4424-b4ad-67c682702af3', 'App\\Notifications\\FeedbackReceivedNotification', 'App\\Models\\User', 8, '{\"type\":\"feedback_received\",\"title\":\"New Feedback Received\",\"message\":\"Nissim Dalton left feedback for project: Multi-Platform Study Cards System (Web + Android)\",\"action_url\":\"http:\\/\\/localhost:8000\\/adiutor\\/projects\\/20\",\"project_id\":20,\"feedback_id\":11,\"rating\":5}', '2025-12-01 18:09:43', '2025-12-01 14:19:32', '2025-12-01 14:19:32'),
('e23ec56b-c701-472b-a257-580aaf92d284', 'App\\Notifications\\FileUploadedNotification', 'App\\Models\\User', 1, '{\"type\":\"file_uploaded\",\"file_name\":\"Github Repository (Link)\",\"task_title\":\"Code Analysis Engine - JavaScript\\/TypeScript Support\",\"task_id\":28,\"uploaded_by\":\"Mark Andrew Soliman\",\"is_deliverable\":true,\"message\":\"New deliverable \'Github Repository (Link)\' uploaded for task \'Code Analysis Engine - JavaScript\\/TypeScript Support\'\",\"action_url\":\"http:\\/\\/localhost:8000\\/adiutor\\/tasks\\/28\",\"icon\":\"package\",\"color\":\"success\"}', NULL, '2025-12-06 10:26:42', '2025-12-06 10:26:42'),
('e2dc2ef2-ba5d-4f17-834b-82af49b86410', 'App\\Notifications\\BudgetChangeReviewedNotification', 'App\\Models\\User', 7, '{\"title\":\"Budget Change Request Approved\",\"message\":\"Your budget change request for task \\\"TASK 2\\\" has been approved. New budget: \\u20b125,000.00\",\"action_url\":\"http:\\/\\/localhost\\/adiutor\\/tasks\\/14\",\"task_id\":14,\"task_title\":\"TASK 2\",\"status\":\"approved\",\"new_budget\":\"25000.00\"}', '2025-10-28 23:47:10', '2025-10-28 03:54:39', '2025-10-28 03:54:39');
INSERT INTO `notifications` (`id`, `type`, `notifiable_type`, `notifiable_id`, `data`, `read_at`, `created_at`, `updated_at`) VALUES
('e2efbd51-cd78-4bf6-b2ab-4a213e8c0215', 'App\\Notifications\\PaymentConfirmedNotification', 'App\\Models\\User', 1, '{\"type\":\"payment_confirmed\",\"title\":\"Payment Confirmed\",\"message\":\"Payment confirmed for \'Digital Strategy Consulting\'. Project created.\",\"action_url\":\"\",\"service_request_id\":22,\"project_name\":\"Digital Strategy Consulting\"}', '2025-11-14 10:04:38', '2025-10-28 23:55:02', '2025-10-28 23:55:02'),
('e3a2177f-d934-4005-9b54-323980c454f7', 'App\\Notifications\\RevisionRequestedNotification', 'App\\Models\\User', 2, '{\"type\":\"revision_requested\",\"revision_request_id\":4,\"document_id\":null,\"document_name\":\"N\\/A\",\"client_name\":\"John Smith\",\"source_type\":\"project\",\"source_description\":\"Project: Website SEO Optimization\",\"reason\":\"[2025-11-14 12:17:24] development.INFO: Revision request received {\\\"project_id\\\":\\\"13\\\",\\\"user_id\\\":3,\\\"re\",\"message\":\"New revision request from John Smith\",\"action_url\":\"http:\\/\\/localhost:8000\\/admin\\/revisions\\/4\"}', NULL, '2025-11-14 12:22:51', '2025-11-14 12:22:51'),
('e4b2ca13-4fa3-4864-8621-44f1ff5a78ab', 'App\\Notifications\\NewServiceRequestNotification', 'App\\Models\\User', 1, '{\"type\":\"new_service_request\",\"title\":\"New Service Request\",\"message\":\"New service request \'Digital Strategy Consulting\' submitted by Mark Andrew Zapatero Soliman (new user)\",\"action_url\":\"http:\\/\\/localhost:8000\\/admin\\/requests\\/22\",\"service_request_id\":22,\"project_name\":\"Digital Strategy Consulting\",\"user_name\":\"Mark Andrew Zapatero Soliman\",\"is_new_user\":true}', '2025-11-14 10:04:38', '2025-10-28 22:22:23', '2025-10-28 22:22:23'),
('e54297f7-0642-4111-ba9b-985fb5b40002', 'App\\Notifications\\PaymentConfirmedNotification', 'App\\Models\\User', 2, '{\"type\":\"payment_confirmed\",\"title\":\"Payment Confirmed\",\"message\":\"Payment of \\u20b1250,000.00 confirmed for \'EdgeRelay - Decentralized Content Delivery Network for Small-Scale Creators\'.\",\"action_url\":\"\",\"payment_id\":16,\"service_request_id\":43,\"amount\":\"250000.00\",\"payment_type\":\"Full Payment\",\"project_name\":\"EdgeRelay - Decentralized Content Delivery Network for Small-Scale Creators\"}', NULL, '2025-12-05 17:53:10', '2025-12-05 17:53:10'),
('e5c73c28-b74f-423e-ac2b-4798d1013487', 'App\\Notifications\\PaymentConfirmedNotification', 'App\\Models\\User', 1, '{\"type\":\"payment_confirmed\",\"title\":\"Payment Confirmed\",\"message\":\"Payment confirmed for \'Digital Strategy Consulting\'. Project created.\",\"action_url\":\"\",\"service_request_id\":22,\"project_name\":\"Digital Strategy Consulting\"}', '2025-11-14 10:04:38', '2025-10-28 23:55:13', '2025-10-28 23:55:13'),
('e659931d-128c-465a-b64b-98095ac2d973', 'App\\Notifications\\NewServiceRequestNotification', 'App\\Models\\User', 1, '{\"type\":\"new_service_request\",\"title\":\"New Service Request\",\"message\":\"New service request \'QuantumShield - Zero-Trust Security Orchestration Platform\' submitted by John Smith\",\"action_url\":\"http:\\/\\/localhost:8000\\/admin\\/requests\\/44\",\"service_request_id\":44,\"project_name\":\"QuantumShield - Zero-Trust Security Orchestration Platform\",\"user_name\":\"John Smith\",\"is_new_user\":false}', NULL, '2025-12-05 17:56:53', '2025-12-05 17:56:53'),
('e74b9735-3ee1-4c42-9d72-ceeaf51e5948', 'App\\Notifications\\FileUploadedNotification', 'App\\Models\\User', 1, '{\"type\":\"file_uploaded\",\"file_name\":\"Material Design (Link)\",\"task_title\":\"Task 1: Backend Infrastructure & API Foundation\",\"task_id\":25,\"uploaded_by\":\"Mark Andrew Soliman\",\"is_deliverable\":true,\"message\":\"New deliverable \'Material Design (Link)\' uploaded for task \'Task 1: Backend Infrastructure & API Foundation\'\",\"action_url\":\"http:\\/\\/localhost:8000\\/adiutor\\/tasks\\/25\",\"icon\":\"package\",\"color\":\"success\"}', NULL, '2025-12-06 08:06:32', '2025-12-06 08:06:32'),
('e7e9705b-9166-497a-98d5-6a0fb487a426', 'App\\Notifications\\ProjectCompletedNotification', 'App\\Models\\User', 18, '{\"type\":\"project_completed\",\"title\":\"Project Completed\",\"message\":\"Your project \'UNIMERCE \\u2013 Full E-Commerce Platform Development & Optimization\' has been completed!\",\"action_url\":\"http:\\/\\/localhost:8000\\/client\\/projects\\/19\",\"project_id\":19,\"project_title\":\"UNIMERCE \\u2013 Full E-Commerce Platform Development & Optimization\",\"completion_date\":\"2025-12-01 14:24:14\"}', '2025-12-01 15:03:40', '2025-12-01 14:24:14', '2025-12-01 14:24:14'),
('e8a50cea-2ce1-4d33-a684-5a09a1ff79c2', 'App\\Notifications\\TaskUpdatedNotification', 'App\\Models\\User', 3, '{\"type\":\"task_updated\",\"task_id\":25,\"task_title\":\"Backend Infrastructure & API Foundation\",\"project_title\":\"SynthAI - Autonomous Code Review and Technical Debt Analyzer\",\"changes\":{\"title\":{\"old\":\"Task 1: Backend Infrastructure & API Foundation\",\"new\":\"Backend Infrastructure & API Foundation\"}},\"changes_text\":\"Title: Task 1: Backend Infrastructure & API Foundation \\u2192 Backend Infrastructure & API Foundation\",\"updated_by\":\"Mark Admin\",\"message\":\"Task \'Backend Infrastructure & API Foundation\' has been updated: Title: Task 1: Backend Infrastructure & API Foundation \\u2192 Backend Infrastructure & API Foundation\",\"action_url\":\"http:\\/\\/localhost:8000\\/admin\\/tasks\\/25\",\"icon\":\"edit\",\"color\":\"info\"}', NULL, '2025-12-06 10:25:18', '2025-12-06 10:25:18'),
('ea7cae3b-e754-4377-97ba-f53d7fcd7cac', 'App\\Notifications\\TaskUpdatedNotification', 'App\\Models\\User', 2, '{\"type\":\"task_updated\",\"task_id\":25,\"task_title\":\"Backend Infrastructure & API Foundation\",\"project_title\":\"SynthAI - Autonomous Code Review and Technical Debt Analyzer\",\"changes\":{\"title\":{\"old\":\"Task 1: Backend Infrastructure & API Foundation\",\"new\":\"Backend Infrastructure & API Foundation\"}},\"changes_text\":\"Title: Task 1: Backend Infrastructure & API Foundation \\u2192 Backend Infrastructure & API Foundation\",\"updated_by\":\"Mark Admin\",\"message\":\"Task \'Backend Infrastructure & API Foundation\' has been updated: Title: Task 1: Backend Infrastructure & API Foundation \\u2192 Backend Infrastructure & API Foundation\",\"action_url\":\"http:\\/\\/localhost:8000\\/admin\\/tasks\\/25\",\"icon\":\"edit\",\"color\":\"info\"}', NULL, '2025-12-06 10:25:18', '2025-12-06 10:25:18'),
('eb24e607-700b-4b88-864d-da248e982c62', 'App\\Notifications\\PaymentConfirmedNotification', 'App\\Models\\User', 32, '{\"type\":\"payment_confirmed\",\"title\":\"Payment Confirmed\",\"message\":\"Payment of \\u20b1280,000.00 confirmed for \'Graphics Design\'.\",\"action_url\":\"\",\"payment_id\":10,\"service_request_id\":38,\"amount\":\"280000.00\",\"payment_type\":\"Full Payment\",\"project_name\":\"Graphics Design\"}', NULL, '2025-11-14 11:48:46', '2025-11-14 11:48:46'),
('ec37568a-815f-4468-babe-51e930b8ee25', 'App\\Notifications\\TaskCreatedNotification', 'App\\Models\\User', 3, '{\"type\":\"task_created\",\"task_id\":26,\"task_title\":\"GitHub Integration & Webhook Handler\",\"project_title\":\"SynthAI - Autonomous Code Review and Technical Debt Analyzer\",\"priority\":\"medium\",\"deadline\":\"2025-12-14T16:00:00.000000Z\",\"assigned_to\":\"Princess Anne Azucena\",\"created_by\":\"Admin\",\"message\":\"New task \'GitHub Integration & Webhook Handler\' has been created\",\"action_url\":\"http:\\/\\/localhost:8000\\/admin\\/tasks\\/26\",\"icon\":\"plus-square\",\"color\":\"success\"}', NULL, '2025-12-06 07:33:32', '2025-12-06 07:33:32'),
('ed35cbb6-e194-4b1a-88df-a4d5ab5d9a40', 'App\\Notifications\\PaymentConfirmedNotification', 'App\\Models\\User', 2, '{\"type\":\"payment_confirmed\",\"title\":\"Payment Confirmed\",\"message\":\"Payment of \\u20b1280,000.00 confirmed for \'Graphics Design\'.\",\"action_url\":\"\",\"payment_id\":10,\"service_request_id\":38,\"amount\":\"280000.00\",\"payment_type\":\"Full Payment\",\"project_name\":\"Graphics Design\"}', NULL, '2025-11-14 11:48:51', '2025-11-14 11:48:51'),
('eff7f0e2-f3c3-4de5-9c2c-07c90d968bfd', 'App\\Notifications\\NewServiceRequestNotification', 'App\\Models\\User', 1, '{\"type\":\"new_service_request\",\"title\":\"New Service Request\",\"message\":\"New service request \'Multi-Platform Study Cards System (Web + Android)\' submitted by Nissim Dalton\",\"action_url\":\"http:\\/\\/localhost:8000\\/admin\\/requests\\/41\",\"service_request_id\":41,\"project_name\":\"Multi-Platform Study Cards System (Web + Android)\",\"user_name\":\"Nissim Dalton\",\"is_new_user\":false}', '2025-12-01 17:34:27', '2025-12-01 12:12:57', '2025-12-01 12:12:57'),
('f1396475-b18a-4cfd-8cf1-a8e737bee00d', 'App\\Notifications\\FileUploadedNotification', 'App\\Models\\User', 1, '{\"type\":\"file_uploaded\",\"file_name\":\"drivers_license.png\",\"task_title\":\"Implementation & Development\",\"task_id\":5,\"uploaded_by\":\"Mark Andrew\",\"is_deliverable\":false,\"message\":\"New file \'drivers_license.png\' uploaded for task \'Implementation & Development\'\",\"action_url\":\"http:\\/\\/localhost:8000\\/adiutor\\/tasks\\/5\",\"icon\":\"file\",\"color\":\"info\"}', '2025-12-05 09:48:59', '2025-12-05 09:46:39', '2025-12-05 09:46:39'),
('f15cdffc-0390-4840-a26e-f0e13528ba95', 'App\\Notifications\\TaskDeletedNotification', 'App\\Models\\User', 2, '{\"type\":\"task_deleted\",\"task_data\":{\"id\":23,\"title\":\"Task 1\",\"project_title\":\"Ecommerce 3\",\"assigned_to\":\"Shann\",\"status\":\"pending\",\"priority\":\"medium\"},\"deleted_by\":\"Mark Admin\",\"message\":\"Task \'Task 1\' has been deleted\",\"icon\":\"minus-square\",\"color\":\"danger\"}', NULL, '2025-12-05 22:03:18', '2025-12-05 22:03:18'),
('f217e8f6-68ce-4667-9e27-37607077af3e', 'App\\Notifications\\NewServiceRequestNotification', 'App\\Models\\User', 1, '{\"type\":\"new_service_request\",\"title\":\"New Service Request\",\"message\":\"New service request \'Ecommerce 2\' submitted by John Smith\",\"action_url\":\"http:\\/\\/localhost:8000\\/admin\\/requests\\/33\",\"service_request_id\":33,\"project_name\":\"Ecommerce 2\",\"user_name\":\"John Smith\",\"is_new_user\":false}', '2025-11-14 10:04:38', '2025-11-10 11:28:24', '2025-11-10 11:28:24'),
('f274910e-37a0-4ebd-9dd2-9d6d5e4d38a8', 'App\\Notifications\\DeliverableApprovedNotification', 'App\\Models\\User', 7, '{\"type\":\"deliverable_approved\",\"document_id\":17,\"document_name\":\"Bug Fixes Compilations (with code snippets)\",\"task_id\":28,\"task_title\":\"Code Analysis Engine - JavaScript\\/TypeScript Support\",\"message\":\"Your deliverable \\\"Bug Fixes Compilations (with code snippets)\\\" has been approved.\"}', NULL, '2025-12-06 10:30:02', '2025-12-06 10:30:02'),
('f511c6b8-8dfc-4492-8424-b999136efea5', 'App\\Notifications\\PaymentConfirmedNotification', 'App\\Models\\User', 2, '{\"type\":\"payment_confirmed\",\"title\":\"Payment Confirmed\",\"message\":\"Payment confirmed for \'Digital Strategy Consulting\'. Project created.\",\"action_url\":\"http:\\/\\/localhost\\/admin\\/projects\\/5\",\"service_request_id\":11,\"project_id\":5,\"payment_id\":1,\"project_name\":\"Digital Strategy Consulting\"}', NULL, '2025-10-28 03:54:31', '2025-10-28 03:54:31'),
('f53d4acc-cc5e-4d8a-9db8-4730353d7d11', 'App\\Notifications\\TaskCreatedNotification', 'App\\Models\\User', 2, '{\"type\":\"task_created\",\"task_id\":21,\"task_title\":\"Expedita labore sit\",\"project_title\":\"UNIMERCE \\u2013 Full E-Commerce Platform Development & Optimization\",\"priority\":\"high\",\"deadline\":\"2026-09-18T16:00:00.000000Z\",\"assigned_to\":\"Lena Quizon\",\"created_by\":\"Mark Admin\",\"message\":\"New task \'Expedita labore sit\' has been created\",\"action_url\":\"http:\\/\\/localhost:8000\\/admin\\/tasks\\/21\",\"icon\":\"plus-square\",\"color\":\"success\"}', NULL, '2025-12-01 12:51:52', '2025-12-01 12:51:52'),
('f55b1e06-758f-427e-9ca9-571cff7f2003', 'App\\Notifications\\ProjectCompletedNotification', 'App\\Models\\User', 18, '{\"type\":\"project_completed\",\"title\":\"Project Completed\",\"message\":\"Your project \'UNIMERCE \\u2013 Full E-Commerce Platform Development & Optimization\' has been completed!\",\"action_url\":\"http:\\/\\/localhost:8000\\/client\\/projects\\/19\",\"project_id\":19,\"project_title\":\"UNIMERCE \\u2013 Full E-Commerce Platform Development & Optimization\",\"completion_date\":\"2025-12-01 14:24:30\"}', '2025-12-01 15:03:39', '2025-12-01 14:24:30', '2025-12-01 14:24:30'),
('f58c7eec-0f37-4cbf-8861-2297cff8d409', 'App\\Notifications\\TaskCreatedNotification', 'App\\Models\\User', 3, '{\"type\":\"task_created\",\"task_id\":29,\"task_title\":\"Issue Classification & Explanation Generation\",\"project_title\":\"SynthAI - Autonomous Code Review and Technical Debt Analyzer\",\"priority\":\"medium\",\"deadline\":null,\"assigned_to\":\"Princess Anne Azucena\",\"created_by\":\"Admin\",\"message\":\"New task \'Issue Classification & Explanation Generation\' has been created\",\"action_url\":\"http:\\/\\/localhost:8000\\/admin\\/tasks\\/29\",\"icon\":\"plus-square\",\"color\":\"success\"}', NULL, '2025-12-06 10:24:57', '2025-12-06 10:24:57'),
('f7786cde-89b5-435d-8b3d-62fe9d54b0d3', 'App\\Notifications\\NewServiceRequestNotification', 'App\\Models\\User', 2, '{\"type\":\"new_service_request\",\"title\":\"New Service Request\",\"message\":\"New service request \'Digital Strategy Consulting\' submitted by Mark Andrew Zapatero Soliman (new user)\",\"action_url\":\"http:\\/\\/localhost\\/admin\\/requests\\/12\",\"service_request_id\":12,\"project_name\":\"Digital Strategy Consulting\",\"user_name\":\"Mark Andrew Zapatero Soliman\",\"is_new_user\":true}', NULL, '2025-10-28 03:54:46', '2025-10-28 03:54:46'),
('f7cd32a5-ba1b-4996-89f5-f58e7707e45b', 'App\\Notifications\\TaskCreatedNotification', 'App\\Models\\User', 3, '{\"type\":\"task_created\",\"task_id\":27,\"task_title\":\"Code Analysis Engine - Python Support\",\"project_title\":\"SynthAI - Autonomous Code Review and Technical Debt Analyzer\",\"priority\":\"medium\",\"deadline\":null,\"assigned_to\":\"Princess Anne Azucena\",\"created_by\":\"Admin\",\"message\":\"New task \'Code Analysis Engine - Python Support\' has been created\",\"action_url\":\"http:\\/\\/localhost:8000\\/admin\\/tasks\\/27\",\"icon\":\"plus-square\",\"color\":\"success\"}', NULL, '2025-12-06 07:45:46', '2025-12-06 07:45:46'),
('f7d237a8-fd1c-4c49-988a-c4cb329a3a17', 'App\\Notifications\\ProjectCompletedNotification', 'App\\Models\\User', 18, '{\"type\":\"project_completed\",\"title\":\"Project Completed\",\"message\":\"Your project \'Multi-Platform Study Cards System (Web + Android)\' has been completed!\",\"action_url\":\"http:\\/\\/localhost:8000\\/client\\/projects\\/20\",\"project_id\":20,\"project_title\":\"Multi-Platform Study Cards System (Web + Android)\",\"completion_date\":\"2025-12-01 12:19:49\"}', '2025-12-01 12:20:02', '2025-12-01 12:19:49', '2025-12-01 12:19:49'),
('f81f6897-7fa6-4887-9132-08a299237844', 'App\\Notifications\\TaskCreatedNotification', 'App\\Models\\User', 2, '{\"type\":\"task_created\",\"task_id\":20,\"task_title\":\"Aperiam nisi repelle\",\"project_title\":\"Multi-Platform Study Cards System (Web + Android)\",\"priority\":\"high\",\"deadline\":null,\"assigned_to\":\"Sofia\",\"created_by\":\"Mark Admin\",\"message\":\"New task \'Aperiam nisi repelle\' has been created\",\"action_url\":\"http:\\/\\/localhost:8000\\/admin\\/tasks\\/20\",\"icon\":\"plus-square\",\"color\":\"success\"}', NULL, '2025-12-01 12:18:41', '2025-12-01 12:18:41'),
('f9592f12-1f03-47d7-b236-c02b5a56df77', 'App\\Notifications\\FileUploadedNotification', 'App\\Models\\User', 2, '{\"type\":\"file_uploaded\",\"file_name\":\"Documentation (Link)\",\"task_title\":\"Code Analysis Engine - JavaScript\\/TypeScript Support\",\"task_id\":28,\"uploaded_by\":\"Mark Andrew Soliman\",\"is_deliverable\":true,\"message\":\"New deliverable \'Documentation (Link)\' uploaded for task \'Code Analysis Engine - JavaScript\\/TypeScript Support\'\",\"action_url\":\"http:\\/\\/localhost:8000\\/adiutor\\/tasks\\/28\",\"icon\":\"package\",\"color\":\"success\"}', NULL, '2025-12-06 10:27:39', '2025-12-06 10:27:39'),
('f97c8921-692a-42a1-b7b3-2ac734d5e2a9', 'App\\Notifications\\NewServiceRequestNotification', 'App\\Models\\User', 1, '{\"type\":\"new_service_request\",\"title\":\"New Service Request\",\"message\":\"New service request \'EdgeRelay - Decentralized Content Delivery Network for Small-Scale Creators\' submitted by John Smith\",\"action_url\":\"http:\\/\\/localhost:8000\\/admin\\/requests\\/43\",\"service_request_id\":43,\"project_name\":\"EdgeRelay - Decentralized Content Delivery Network for Small-Scale Creators\",\"user_name\":\"John Smith\",\"is_new_user\":false}', NULL, '2025-12-05 17:44:15', '2025-12-05 17:44:15'),
('fa6029d5-a47f-407b-8218-4dc046b4b1f6', 'App\\Notifications\\NewServiceRequestNotification', 'App\\Models\\User', 2, '{\"type\":\"new_service_request\",\"title\":\"New Service Request\",\"message\":\"New service request \'Drake Maynard\' submitted by Francis Lancaster (new user)\",\"action_url\":\"http:\\/\\/localhost\\/admin\\/requests\\/14\",\"service_request_id\":14,\"project_name\":\"Drake Maynard\",\"user_name\":\"Francis Lancaster\",\"is_new_user\":true}', NULL, '2025-10-28 03:54:50', '2025-10-28 03:54:50'),
('fbc153cc-ded8-46d0-8c25-ef14eea6c0cb', 'App\\Notifications\\NewDeliverableAvailableNotification', 'App\\Models\\User', 3, '{\"type\":\"new_deliverable\",\"document_id\":15,\"document_name\":\"Github Repository\",\"task_id\":28,\"task_title\":\"Code Analysis Engine - JavaScript\\/TypeScript Support\",\"project_title\":\"SynthAI - Autonomous Code Review and Technical Debt Analyzer\",\"message\":\"New deliverable available: Github Repository\"}', NULL, '2025-12-06 10:30:22', '2025-12-06 10:30:22'),
('fc8177e1-e20d-40f7-aa82-0594b98d9202', 'App\\Notifications\\FileUploadedNotification', 'App\\Models\\User', 1, '{\"type\":\"file_uploaded\",\"file_name\":\"Bug Fixes Compilations (with code snippets) (Link)\",\"task_title\":\"Code Analysis Engine - JavaScript\\/TypeScript Support\",\"task_id\":28,\"uploaded_by\":\"Mark Andrew Soliman\",\"is_deliverable\":true,\"message\":\"New deliverable \'Bug Fixes Compilations (with code snippets) (Link)\' uploaded for task \'Code Analysis Engine - JavaScript\\/TypeScript Support\'\",\"action_url\":\"http:\\/\\/localhost:8000\\/adiutor\\/tasks\\/28\",\"icon\":\"package\",\"color\":\"success\"}', NULL, '2025-12-06 10:28:16', '2025-12-06 10:28:16'),
('fe518061-7345-4bb9-9d8b-dd8c81f79147', 'App\\Notifications\\PaymentConfirmedNotification', 'App\\Models\\User', 1, '{\"type\":\"payment_confirmed\",\"title\":\"Payment Confirmed\",\"message\":\"Payment confirmed for \'Digital Strategy Consulting\'. Project created.\",\"action_url\":\"http:\\/\\/localhost\\/admin\\/projects\\/5\",\"service_request_id\":11,\"project_id\":5,\"payment_id\":2,\"project_name\":\"Digital Strategy Consulting\"}', '2025-11-14 10:04:38', '2025-10-28 03:54:32', '2025-10-28 03:54:32');

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `password_reset_tokens`
--

INSERT INTO `password_reset_tokens` (`email`, `token`, `created_at`) VALUES
('john.smith@techstartup.com', '$2y$12$4uFWpAMVV30icuF.yQ9Sp..P0ubyNKYfE0/UbzBOTAqiHqRmQnS1e', '2025-12-05 15:51:16');

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` bigint UNSIGNED NOT NULL,
  `service_request_id` bigint UNSIGNED NOT NULL,
  `milestone_id` bigint UNSIGNED DEFAULT NULL,
  `payment_type` enum('full_payment','milestone_payment','downpayment','remaining_balance') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `client_id` bigint UNSIGNED NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `payment_method` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payment_reference` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('pending','confirmed','failed','refunded','cancelled') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `confirmed_at` timestamp NULL DEFAULT NULL,
  `notification_sent_at` timestamp NULL DEFAULT NULL,
  `confirmed_by` bigint UNSIGNED DEFAULT NULL,
  `payment_details` json DEFAULT NULL,
  `transaction_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gateway_response` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `gateway_fee` decimal(8,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`id`, `service_request_id`, `milestone_id`, `payment_type`, `client_id`, `amount`, `payment_method`, `payment_reference`, `status`, `notes`, `confirmed_at`, `notification_sent_at`, `confirmed_by`, `payment_details`, `transaction_id`, `gateway_response`, `gateway_fee`, `created_at`, `updated_at`) VALUES
(1, 11, 1, 'milestone_payment', 12, 150000.00, 'maya', 'TREIS-1761403576-11', 'confirmed', NULL, '2025-10-25 06:46:54', NULL, NULL, '{\"checkout_id\": \"2706f6b6-7cfd-4003-b691-08973a5cee1b\", \"environment\": \"sandbox\", \"payment_description\": \"Phase 1: Phase 1\"}', '2706f6b6-7cfd-4003-b691-08973a5cee1b', '{\"id\":\"2706f6b6-7cfd-4003-b691-08973a5cee1b\",\"items\":[{\"name\":\"Digital Strategy Consulting - Phase 1: Phase 1\",\"quantity\":\"1\",\"code\":\"SERVICE-11\",\"description\":\"I\'m interested in: Digital Strategy Consulting\\r\\n\\r\\nStrategic consulting for digital transformation, o\",\"amount\":{\"value\":150000,\"details\":{\"discount\":\"0\",\"serviceCharge\":\"0\",\"shippingFee\":\"0\",\"tax\":\"0\",\"subtotal\":\"150000\"}},\"totalAmount\":{\"value\":150000,\"details\":{\"discount\":\"0\",\"serviceCharge\":\"0\",\"shippingFee\":\"0\",\"tax\":\"0\",\"subtotal\":\"150000\"}}}],\"metadata\":null,\"requestReferenceNumber\":\"TREIS-1761403576-11\",\"receiptNumber\":\"8835b3579348\",\"createdAt\":\"2025-10-25T14:46:20.541Z\",\"updatedAt\":\"2025-10-25T14:46:51.253Z\",\"paymentScheme\":\"master-card\",\"expressCheckout\":true,\"refundedAmount\":\"0\",\"canPayPal\":false,\"expiredAt\":\"2025-10-25T15:46:20.540Z\",\"status\":\"COMPLETED\",\"paymentStatus\":\"PAYMENT_SUCCESS\",\"paymentDetails\":{\"responses\":{\"efs\":{\"paymentTransactionReferenceNo\":\"2853f0f9-f2f5-483a-ac08-8835b3579348\",\"createdAt\":\"2025-10-25T14:46:50.282Z\",\"status\":\"SUCCESS\",\"receipt\":{\"transactionId\":\"7391827d-3232-461e-9cfd-450ab385f88d\",\"approvalCode\":\"00001234\",\"receiptNo\":\"8835b3579348\",\"approval_code\":\"00001234\"},\"payer\":{\"fundingInstrument\":{\"card\":{\"cardNumber\":\"512345******2346\",\"expiryMonth\":\"12\",\"expiryYear\":\"2025\"}}},\"amount\":{\"total\":{\"currency\":\"PHP\",\"value\":150000}}}},\"paymentAt\":\"2025-10-25T14:46:51.252Z\",\"3ds\":false},\"buyer\":{\"contact\":{\"email\":\"markandrewsoliman@outlook.com\",\"phone\":\"09761022819\"},\"firstName\":\"Mark\",\"lastName\":\"Andrew Soliman\",\"billingAddress\":{\"line1\":\"456\",\"line2\":\"Qui saepe ut quisqua\",\"city\":\"Placeat laboris duc\",\"zipCode\":\"62917\",\"countryCode\":\"PH\"},\"shippingAddress\":{\"firstName\":\"Mark\",\"lastName\":\"Andrew Soliman\",\"line1\":\"456\",\"line2\":\"Qui saepe ut quisqua\",\"city\":\"Placeat laboris duc\",\"zipCode\":\"62917\",\"countryCode\":\"PH\",\"phone\":\"09761022819\",\"email\":\"markandrewsoliman@outlook.com\"}},\"merchant\":{\"currency\":\"PHP\",\"email\":null,\"locale\":\"en\",\"homepageUrl\":\"https:\\/\\/d8924522-8000.asse.devtunnels.ms\\/\",\"isEmailToMerchantEnabled\":false,\"isEmailToBuyerEnabled\":false,\"isPaymentFacilitator\":false,\"isPageCustomized\":false,\"supportedSchemes\":[\"Visa\",\"Mastercard\"],\"canPayPal\":false,\"payPalEmail\":null,\"payPalWebExperienceId\":null,\"expressCheckout\":true,\"name\":\"Treis Adiutor\"},\"totalAmount\":{\"amount\":\"150000\",\"currency\":\"PHP\",\"details\":null},\"redirectUrl\":{\"success\":\"http:\\/\\/localhost:8000\\/client\\/maya\\/success?ref=TREIS-1761403576-11\",\"failure\":\"http:\\/\\/localhost:8000\\/client\\/maya\\/failure?ref=TREIS-1761403576-11\",\"cancel\":\"http:\\/\\/localhost:8000\\/client\\/maya\\/cancel?ref=TREIS-1761403576-11\"},\"transactionReferenceNumber\":\"2853f0f9-f2f5-483a-ac08-8835b3579348\"}', NULL, '2025-10-25 06:46:21', '2025-10-25 06:46:54'),
(2, 11, 2, 'milestone_payment', 12, 200000.00, 'maya', 'TREIS-1761404790-11', 'confirmed', NULL, '2025-10-25 07:07:04', NULL, NULL, '{\"checkout_id\": \"4c9e42b8-b1b3-4720-b863-98526d8ea541\", \"environment\": \"sandbox\", \"payment_description\": \"Phase 2: Phase 2\"}', '4c9e42b8-b1b3-4720-b863-98526d8ea541', '{\"id\":\"4c9e42b8-b1b3-4720-b863-98526d8ea541\",\"items\":[{\"name\":\"Digital Strategy Consulting - Phase 2: Phase 2\",\"quantity\":\"1\",\"code\":\"SERVICE-11\",\"description\":\"I\'m interested in: Digital Strategy Consulting\\r\\n\\r\\nStrategic consulting for digital transformation, o\",\"amount\":{\"value\":200000,\"details\":{\"discount\":\"0\",\"serviceCharge\":\"0\",\"shippingFee\":\"0\",\"tax\":\"0\",\"subtotal\":\"200000\"}},\"totalAmount\":{\"value\":200000,\"details\":{\"discount\":\"0\",\"serviceCharge\":\"0\",\"shippingFee\":\"0\",\"tax\":\"0\",\"subtotal\":\"200000\"}}}],\"metadata\":null,\"requestReferenceNumber\":\"TREIS-1761404790-11\",\"receiptNumber\":\"99d3e4d6c22b\",\"createdAt\":\"2025-10-25T15:06:31.863Z\",\"updatedAt\":\"2025-10-25T15:06:58.759Z\",\"paymentScheme\":\"master-card\",\"expressCheckout\":true,\"refundedAmount\":\"0\",\"canPayPal\":false,\"expiredAt\":\"2025-10-25T16:06:31.863Z\",\"status\":\"COMPLETED\",\"paymentStatus\":\"PAYMENT_SUCCESS\",\"paymentDetails\":{\"responses\":{\"efs\":{\"paymentTransactionReferenceNo\":\"c6b17c77-9c08-47c3-ab78-99d3e4d6c22b\",\"createdAt\":\"2025-10-25T15:06:57.786Z\",\"status\":\"SUCCESS\",\"receipt\":{\"transactionId\":\"78771df6-6a6b-49fd-813c-845099f98a9d\",\"approvalCode\":\"00001234\",\"receiptNo\":\"99d3e4d6c22b\",\"approval_code\":\"00001234\"},\"payer\":{\"fundingInstrument\":{\"card\":{\"cardNumber\":\"512345******2346\",\"expiryMonth\":\"12\",\"expiryYear\":\"2025\"}}},\"amount\":{\"total\":{\"currency\":\"PHP\",\"value\":200000}}}},\"paymentAt\":\"2025-10-25T15:06:58.758Z\",\"3ds\":false},\"buyer\":{\"contact\":{\"email\":\"markandrewsoliman@outlook.com\",\"phone\":\"09761022819\"},\"firstName\":\"Mark\",\"lastName\":\"Andrew Soliman\",\"billingAddress\":{\"line1\":\"239\",\"line2\":\"Suscipit omnis deser\",\"city\":\"Repellendus Unde al\",\"zipCode\":\"47899\",\"countryCode\":\"PH\"},\"shippingAddress\":{\"firstName\":\"Mark\",\"lastName\":\"Andrew Soliman\",\"line1\":\"239\",\"line2\":\"Suscipit omnis deser\",\"city\":\"Repellendus Unde al\",\"zipCode\":\"47899\",\"countryCode\":\"PH\",\"phone\":\"09761022819\",\"email\":\"markandrewsoliman@outlook.com\"}},\"merchant\":{\"currency\":\"PHP\",\"email\":null,\"locale\":\"en\",\"homepageUrl\":\"https:\\/\\/d8924522-8000.asse.devtunnels.ms\\/\",\"isEmailToMerchantEnabled\":false,\"isEmailToBuyerEnabled\":false,\"isPaymentFacilitator\":false,\"isPageCustomized\":false,\"supportedSchemes\":[\"Visa\",\"Mastercard\"],\"canPayPal\":false,\"payPalEmail\":null,\"payPalWebExperienceId\":null,\"expressCheckout\":true,\"name\":\"Treis Adiutor\"},\"totalAmount\":{\"amount\":\"200000\",\"currency\":\"PHP\",\"details\":null},\"redirectUrl\":{\"success\":\"http:\\/\\/localhost:8000\\/client\\/maya\\/success?ref=TREIS-1761404790-11\",\"failure\":\"http:\\/\\/localhost:8000\\/client\\/maya\\/failure?ref=TREIS-1761404790-11\",\"cancel\":\"http:\\/\\/localhost:8000\\/client\\/maya\\/cancel?ref=TREIS-1761404790-11\"},\"transactionReferenceNumber\":\"c6b17c77-9c08-47c3-ab78-99d3e4d6c22b\"}', NULL, '2025-10-25 07:06:31', '2025-10-25 07:07:04'),
(3, 11, 3, 'milestone_payment', 12, 150000.00, 'maya', 'TREIS-1761404864-11', 'confirmed', NULL, '2025-10-25 07:08:11', NULL, NULL, '{\"checkout_id\": \"eacb3f1b-3089-4f9b-9737-c538595ae44a\", \"environment\": \"sandbox\", \"payment_description\": \"Phase 3: Phase 3\"}', 'eacb3f1b-3089-4f9b-9737-c538595ae44a', '{\"id\":\"eacb3f1b-3089-4f9b-9737-c538595ae44a\",\"items\":[{\"name\":\"Digital Strategy Consulting - Phase 3: Phase 3\",\"quantity\":\"1\",\"code\":\"SERVICE-11\",\"description\":\"I\'m interested in: Digital Strategy Consulting\\r\\n\\r\\nStrategic consulting for digital transformation, o\",\"amount\":{\"value\":150000,\"details\":{\"discount\":\"0\",\"serviceCharge\":\"0\",\"shippingFee\":\"0\",\"tax\":\"0\",\"subtotal\":\"150000\"}},\"totalAmount\":{\"value\":150000,\"details\":{\"discount\":\"0\",\"serviceCharge\":\"0\",\"shippingFee\":\"0\",\"tax\":\"0\",\"subtotal\":\"150000\"}}}],\"metadata\":null,\"requestReferenceNumber\":\"TREIS-1761404864-11\",\"receiptNumber\":\"c810873d1691\",\"createdAt\":\"2025-10-25T15:07:45.579Z\",\"updatedAt\":\"2025-10-25T15:08:07.733Z\",\"paymentScheme\":\"master-card\",\"expressCheckout\":true,\"refundedAmount\":\"0\",\"canPayPal\":false,\"expiredAt\":\"2025-10-25T16:07:45.579Z\",\"status\":\"COMPLETED\",\"paymentStatus\":\"PAYMENT_SUCCESS\",\"paymentDetails\":{\"responses\":{\"efs\":{\"paymentTransactionReferenceNo\":\"189505c6-8ec0-480b-b83e-c810873d1691\",\"createdAt\":\"2025-10-25T15:08:06.762Z\",\"status\":\"SUCCESS\",\"receipt\":{\"transactionId\":\"d05d8aa0-e2d1-4072-8337-4b91de76d2c5\",\"approvalCode\":\"00001234\",\"receiptNo\":\"c810873d1691\",\"approval_code\":\"00001234\"},\"payer\":{\"fundingInstrument\":{\"card\":{\"cardNumber\":\"512345******2346\",\"expiryMonth\":\"12\",\"expiryYear\":\"2025\"}}},\"amount\":{\"total\":{\"currency\":\"PHP\",\"value\":150000}}}},\"paymentAt\":\"2025-10-25T15:08:07.733Z\",\"3ds\":false},\"buyer\":{\"contact\":{\"email\":\"markandrewsoliman@outlook.com\",\"phone\":\"09761022819\"},\"firstName\":\"Mark\",\"lastName\":\"Andrew Soliman\",\"billingAddress\":{\"line1\":\"558\",\"line2\":\"Proident exercitati\",\"city\":\"Cupiditate veniam s\",\"zipCode\":\"80106\",\"countryCode\":\"PH\"},\"shippingAddress\":{\"firstName\":\"Mark\",\"lastName\":\"Andrew Soliman\",\"line1\":\"558\",\"line2\":\"Proident exercitati\",\"city\":\"Cupiditate veniam s\",\"zipCode\":\"80106\",\"countryCode\":\"PH\",\"phone\":\"09761022819\",\"email\":\"markandrewsoliman@outlook.com\"}},\"merchant\":{\"currency\":\"PHP\",\"email\":null,\"locale\":\"en\",\"homepageUrl\":\"https:\\/\\/d8924522-8000.asse.devtunnels.ms\\/\",\"isEmailToMerchantEnabled\":false,\"isEmailToBuyerEnabled\":false,\"isPaymentFacilitator\":false,\"isPageCustomized\":false,\"supportedSchemes\":[\"Visa\",\"Mastercard\"],\"canPayPal\":false,\"payPalEmail\":null,\"payPalWebExperienceId\":null,\"expressCheckout\":true,\"name\":\"Treis Adiutor\"},\"totalAmount\":{\"amount\":\"150000\",\"currency\":\"PHP\",\"details\":null},\"redirectUrl\":{\"success\":\"http:\\/\\/localhost:8000\\/client\\/maya\\/success?ref=TREIS-1761404864-11\",\"failure\":\"http:\\/\\/localhost:8000\\/client\\/maya\\/failure?ref=TREIS-1761404864-11\",\"cancel\":\"http:\\/\\/localhost:8000\\/client\\/maya\\/cancel?ref=TREIS-1761404864-11\"},\"transactionReferenceNumber\":\"189505c6-8ec0-480b-b83e-c810873d1691\"}', NULL, '2025-10-25 07:07:45', '2025-10-25 07:08:11'),
(4, 22, 4, 'milestone_payment', 23, 150000.00, 'maya', 'TREIS-1761666574-22', 'confirmed', NULL, '2025-10-28 23:51:30', NULL, NULL, '{\"checkout_id\": \"bb0ffe25-bd0a-4937-b5d6-4cf704d5e242\", \"environment\": \"sandbox\", \"payment_description\": \"Phase 1: Phase 1\"}', 'bb0ffe25-bd0a-4937-b5d6-4cf704d5e242', '{\"id\":\"bb0ffe25-bd0a-4937-b5d6-4cf704d5e242\",\"items\":[{\"name\":\"Digital Strategy Consulting - Phase 1: Phase 1\",\"quantity\":\"1\",\"code\":\"SERVICE-22\",\"description\":\"I\'m interested in: Digital Strategy Consulting\\r\\n\\r\\nStrategic consulting for digital transformation, o\",\"amount\":{\"value\":150000,\"details\":{\"discount\":\"0\",\"serviceCharge\":\"0\",\"shippingFee\":\"0\",\"tax\":\"0\",\"subtotal\":\"150000\"}},\"totalAmount\":{\"value\":150000,\"details\":{\"discount\":\"0\",\"serviceCharge\":\"0\",\"shippingFee\":\"0\",\"tax\":\"0\",\"subtotal\":\"150000\"}}}],\"metadata\":null,\"requestReferenceNumber\":\"TREIS-1761666574-22\",\"receiptNumber\":\"bffb7acbe755\",\"createdAt\":\"2025-10-28T15:49:33.859Z\",\"updatedAt\":\"2025-10-28T15:50:11.501Z\",\"paymentScheme\":\"master-card\",\"expressCheckout\":true,\"refundedAmount\":\"0\",\"canPayPal\":false,\"expiredAt\":\"2025-10-28T16:49:33.859Z\",\"status\":\"COMPLETED\",\"paymentStatus\":\"PAYMENT_SUCCESS\",\"paymentDetails\":{\"responses\":{\"efs\":{\"paymentTransactionReferenceNo\":\"e2b64dbc-89d2-44c0-acc5-bffb7acbe755\",\"createdAt\":\"2025-10-28T15:50:12.036Z\",\"status\":\"SUCCESS\",\"receipt\":{\"transactionId\":\"0f81abb1-950c-48ce-9b09-2d8f27633b6b\",\"approvalCode\":\"00001234\",\"receiptNo\":\"bffb7acbe755\",\"approval_code\":\"00001234\"},\"payer\":{\"fundingInstrument\":{\"card\":{\"cardNumber\":\"512345******2346\",\"expiryMonth\":\"12\",\"expiryYear\":\"2025\"}}},\"amount\":{\"total\":{\"currency\":\"PHP\",\"value\":150000}}}},\"paymentAt\":\"2025-10-28T15:50:11.500Z\",\"3ds\":false},\"buyer\":{\"contact\":{\"email\":\"samdm@treisadiutor.com\",\"phone\":\"09614736286\"},\"firstName\":\"Mark\",\"lastName\":\"Andrew Zapatero Soliman\",\"billingAddress\":{\"line1\":\"292\",\"line2\":\"Quam maiores do ea e\",\"city\":\"Nobis qui possimus\",\"zipCode\":\"61696\",\"countryCode\":\"PH\"},\"shippingAddress\":{\"firstName\":\"Mark\",\"lastName\":\"Andrew Zapatero Soliman\",\"line1\":\"292\",\"line2\":\"Quam maiores do ea e\",\"city\":\"Nobis qui possimus\",\"zipCode\":\"61696\",\"countryCode\":\"PH\",\"phone\":\"09614736286\",\"email\":\"samdm@treisadiutor.com\"}},\"merchant\":{\"currency\":\"PHP\",\"email\":null,\"locale\":\"en\",\"homepageUrl\":\"https:\\/\\/d8924522-8000.asse.devtunnels.ms\\/\",\"isEmailToMerchantEnabled\":false,\"isEmailToBuyerEnabled\":false,\"isPaymentFacilitator\":false,\"isPageCustomized\":false,\"supportedSchemes\":[\"Visa\",\"Mastercard\"],\"canPayPal\":false,\"payPalEmail\":null,\"payPalWebExperienceId\":null,\"expressCheckout\":true,\"name\":\"Treis Adiutor\"},\"totalAmount\":{\"amount\":\"150000\",\"currency\":\"PHP\",\"details\":null},\"redirectUrl\":{\"success\":\"http:\\/\\/localhost:8000\\/client\\/maya\\/success?ref=TREIS-1761666574-22\",\"failure\":\"http:\\/\\/localhost:8000\\/client\\/maya\\/failure?ref=TREIS-1761666574-22\",\"cancel\":\"http:\\/\\/localhost:8000\\/client\\/maya\\/cancel?ref=TREIS-1761666574-22\"},\"transactionReferenceNumber\":\"e2b64dbc-89d2-44c0-acc5-bffb7acbe755\"}', NULL, '2025-10-28 23:49:35', '2025-10-28 23:51:30'),
(5, 22, 5, 'milestone_payment', 23, 200000.00, 'maya', 'TREIS-1761666712-22', 'confirmed', NULL, '2025-10-28 23:52:58', NULL, NULL, '{\"checkout_id\": \"48f9dd77-de51-4ff3-aba9-0ff9444f6601\", \"environment\": \"sandbox\", \"payment_description\": \"Phase 2: Phase 2\"}', '48f9dd77-de51-4ff3-aba9-0ff9444f6601', '{\"id\":\"48f9dd77-de51-4ff3-aba9-0ff9444f6601\",\"items\":[{\"name\":\"Digital Strategy Consulting - Phase 2: Phase 2\",\"quantity\":\"1\",\"code\":\"SERVICE-22\",\"description\":\"I\'m interested in: Digital Strategy Consulting\\r\\n\\r\\nStrategic consulting for digital transformation, o\",\"amount\":{\"value\":200000,\"details\":{\"discount\":\"0\",\"serviceCharge\":\"0\",\"shippingFee\":\"0\",\"tax\":\"0\",\"subtotal\":\"200000\"}},\"totalAmount\":{\"value\":200000,\"details\":{\"discount\":\"0\",\"serviceCharge\":\"0\",\"shippingFee\":\"0\",\"tax\":\"0\",\"subtotal\":\"200000\"}}}],\"metadata\":null,\"requestReferenceNumber\":\"TREIS-1761666712-22\",\"receiptNumber\":\"d31eeb26ac7b\",\"createdAt\":\"2025-10-28T15:51:51.672Z\",\"updatedAt\":\"2025-10-28T15:52:42.670Z\",\"paymentScheme\":\"master-card\",\"expressCheckout\":true,\"refundedAmount\":\"0\",\"canPayPal\":false,\"expiredAt\":\"2025-10-28T16:51:51.672Z\",\"status\":\"COMPLETED\",\"paymentStatus\":\"PAYMENT_SUCCESS\",\"paymentDetails\":{\"responses\":{\"efs\":{\"paymentTransactionReferenceNo\":\"fdc9603a-9407-4619-98eb-d31eeb26ac7b\",\"createdAt\":\"2025-10-28T15:52:42.432Z\",\"status\":\"SUCCESS\",\"receipt\":{\"transactionId\":\"3140a6e2-3b37-4872-8aef-69cd2ef197da\",\"approvalCode\":\"00001234\",\"receiptNo\":\"d31eeb26ac7b\",\"approval_code\":\"00001234\"},\"payer\":{\"fundingInstrument\":{\"card\":{\"cardNumber\":\"512345******2346\",\"expiryMonth\":\"12\",\"expiryYear\":\"2025\"}}},\"amount\":{\"total\":{\"currency\":\"PHP\",\"value\":200000}}}},\"paymentAt\":\"2025-10-28T15:52:42.668Z\",\"3ds\":false},\"buyer\":{\"contact\":{\"email\":\"samdm@treisadiutor.com\",\"phone\":\"09614736286\"},\"firstName\":\"Mark\",\"lastName\":\"Andrew Zapatero Soliman\",\"billingAddress\":{\"line1\":\"760\",\"line2\":\"Dolorem nulla adipis\",\"city\":\"Sint cupidatat id i\",\"zipCode\":\"11465\",\"countryCode\":\"PH\"},\"shippingAddress\":{\"firstName\":\"Mark\",\"lastName\":\"Andrew Zapatero Soliman\",\"line1\":\"760\",\"line2\":\"Dolorem nulla adipis\",\"city\":\"Sint cupidatat id i\",\"zipCode\":\"11465\",\"countryCode\":\"PH\",\"phone\":\"09614736286\",\"email\":\"samdm@treisadiutor.com\"}},\"merchant\":{\"currency\":\"PHP\",\"email\":null,\"locale\":\"en\",\"homepageUrl\":\"https:\\/\\/d8924522-8000.asse.devtunnels.ms\\/\",\"isEmailToMerchantEnabled\":false,\"isEmailToBuyerEnabled\":false,\"isPaymentFacilitator\":false,\"isPageCustomized\":false,\"supportedSchemes\":[\"Visa\",\"Mastercard\"],\"canPayPal\":false,\"payPalEmail\":null,\"payPalWebExperienceId\":null,\"expressCheckout\":true,\"name\":\"Treis Adiutor\"},\"totalAmount\":{\"amount\":\"200000\",\"currency\":\"PHP\",\"details\":null},\"redirectUrl\":{\"success\":\"http:\\/\\/localhost:8000\\/client\\/maya\\/success?ref=TREIS-1761666712-22\",\"failure\":\"http:\\/\\/localhost:8000\\/client\\/maya\\/failure?ref=TREIS-1761666712-22\",\"cancel\":\"http:\\/\\/localhost:8000\\/client\\/maya\\/cancel?ref=TREIS-1761666712-22\"},\"transactionReferenceNumber\":\"fdc9603a-9407-4619-98eb-d31eeb26ac7b\"}', NULL, '2025-10-28 23:51:52', '2025-10-28 23:52:58'),
(6, 22, 6, 'milestone_payment', 23, 150000.00, 'maya', 'TREIS-1761666865-22', 'confirmed', NULL, '2025-10-28 23:55:11', NULL, NULL, '{\"checkout_id\": \"3d7428b8-7c2f-47d8-b0fa-368dcbefd0f1\", \"environment\": \"sandbox\", \"payment_description\": \"Phase 3: Phase 3\"}', '3d7428b8-7c2f-47d8-b0fa-368dcbefd0f1', '{\"id\":\"3d7428b8-7c2f-47d8-b0fa-368dcbefd0f1\",\"items\":[{\"name\":\"Digital Strategy Consulting - Phase 3: Phase 3\",\"quantity\":\"1\",\"code\":\"SERVICE-22\",\"description\":\"I\'m interested in: Digital Strategy Consulting\\r\\n\\r\\nStrategic consulting for digital transformation, o\",\"amount\":{\"value\":150000,\"details\":{\"discount\":\"0\",\"serviceCharge\":\"0\",\"shippingFee\":\"0\",\"tax\":\"0\",\"subtotal\":\"150000\"}},\"totalAmount\":{\"value\":150000,\"details\":{\"discount\":\"0\",\"serviceCharge\":\"0\",\"shippingFee\":\"0\",\"tax\":\"0\",\"subtotal\":\"150000\"}}}],\"metadata\":null,\"requestReferenceNumber\":\"TREIS-1761666865-22\",\"receiptNumber\":\"39ec1e976fb6\",\"createdAt\":\"2025-10-28T15:54:26.150Z\",\"updatedAt\":\"2025-10-28T15:54:55.015Z\",\"paymentScheme\":\"master-card\",\"expressCheckout\":true,\"refundedAmount\":\"0\",\"canPayPal\":false,\"expiredAt\":\"2025-10-28T16:54:26.150Z\",\"status\":\"COMPLETED\",\"paymentStatus\":\"PAYMENT_SUCCESS\",\"paymentDetails\":{\"responses\":{\"efs\":{\"paymentTransactionReferenceNo\":\"0a2f9034-b90e-4b65-a945-39ec1e976fb6\",\"createdAt\":\"2025-10-28T15:54:54.170Z\",\"status\":\"SUCCESS\",\"receipt\":{\"transactionId\":\"b8976de0-fd06-4deb-a66b-1cf9412ec305\",\"approvalCode\":\"00001234\",\"receiptNo\":\"39ec1e976fb6\",\"approval_code\":\"00001234\"},\"payer\":{\"fundingInstrument\":{\"card\":{\"cardNumber\":\"512345******2346\",\"expiryMonth\":\"12\",\"expiryYear\":\"2025\"}}},\"amount\":{\"total\":{\"currency\":\"PHP\",\"value\":150000}}}},\"paymentAt\":\"2025-10-28T15:54:55.014Z\",\"3ds\":false},\"buyer\":{\"contact\":{\"email\":\"samdm@treisadiutor.com\",\"phone\":\"09614736286\"},\"firstName\":\"Mark\",\"lastName\":\"Andrew Zapatero Soliman\",\"billingAddress\":{\"line1\":\"379\",\"line2\":\"Et aspernatur deleni\",\"city\":\"Et sequi amet dolor\",\"zipCode\":\"90850\",\"countryCode\":\"PH\"},\"shippingAddress\":{\"firstName\":\"Mark\",\"lastName\":\"Andrew Zapatero Soliman\",\"line1\":\"379\",\"line2\":\"Et aspernatur deleni\",\"city\":\"Et sequi amet dolor\",\"zipCode\":\"90850\",\"countryCode\":\"PH\",\"phone\":\"09614736286\",\"email\":\"samdm@treisadiutor.com\"}},\"merchant\":{\"currency\":\"PHP\",\"email\":null,\"locale\":\"en\",\"homepageUrl\":\"https:\\/\\/d8924522-8000.asse.devtunnels.ms\\/\",\"isEmailToMerchantEnabled\":false,\"isEmailToBuyerEnabled\":false,\"isPaymentFacilitator\":false,\"isPageCustomized\":false,\"supportedSchemes\":[\"Visa\",\"Mastercard\"],\"canPayPal\":false,\"payPalEmail\":null,\"payPalWebExperienceId\":null,\"expressCheckout\":true,\"name\":\"Treis Adiutor\"},\"totalAmount\":{\"amount\":\"150000\",\"currency\":\"PHP\",\"details\":null},\"redirectUrl\":{\"success\":\"http:\\/\\/localhost:8000\\/client\\/maya\\/success?ref=TREIS-1761666865-22\",\"failure\":\"http:\\/\\/localhost:8000\\/client\\/maya\\/failure?ref=TREIS-1761666865-22\",\"cancel\":\"http:\\/\\/localhost:8000\\/client\\/maya\\/cancel?ref=TREIS-1761666865-22\"},\"transactionReferenceNumber\":\"0a2f9034-b90e-4b65-a945-39ec1e976fb6\"}', NULL, '2025-10-28 23:54:27', '2025-10-28 23:55:11'),
(7, 23, 7, 'milestone_payment', 25, 150000.00, 'maya', 'TREIS-1761698615-23', 'confirmed', NULL, '2025-10-29 08:44:30', NULL, NULL, '{\"checkout_id\": \"6fd308f7-536e-413c-9632-bba68b47c082\", \"environment\": \"sandbox\", \"payment_description\": \"Phase 1: Phase 1\"}', '6fd308f7-536e-413c-9632-bba68b47c082', '{\"id\":\"6fd308f7-536e-413c-9632-bba68b47c082\",\"items\":[{\"name\":\"Digital Strategy Consulting - Phase 1: Phase 1\",\"quantity\":\"1\",\"code\":\"SERVICE-23\",\"description\":\"I\'m interested in: Digital Strategy Consulting\\r\\n\\r\\nStrategic consulting for digital transformation, o\",\"amount\":{\"value\":150000,\"details\":{\"discount\":\"0\",\"serviceCharge\":\"0\",\"shippingFee\":\"0\",\"tax\":\"0\",\"subtotal\":\"150000\"}},\"totalAmount\":{\"value\":150000,\"details\":{\"discount\":\"0\",\"serviceCharge\":\"0\",\"shippingFee\":\"0\",\"tax\":\"0\",\"subtotal\":\"150000\"}}}],\"metadata\":null,\"requestReferenceNumber\":\"TREIS-1761698615-23\",\"receiptNumber\":\"66be07884204\",\"createdAt\":\"2025-10-29T00:43:36.013Z\",\"updatedAt\":\"2025-10-29T00:44:25.204Z\",\"paymentScheme\":\"master-card\",\"expressCheckout\":true,\"refundedAmount\":\"0\",\"canPayPal\":false,\"expiredAt\":\"2025-10-29T01:43:36.013Z\",\"status\":\"COMPLETED\",\"paymentStatus\":\"PAYMENT_SUCCESS\",\"paymentDetails\":{\"responses\":{\"efs\":{\"paymentTransactionReferenceNo\":\"4a57b0a1-b52e-41a0-b372-66be07884204\",\"createdAt\":\"2025-10-29T00:44:25.473Z\",\"status\":\"SUCCESS\",\"receipt\":{\"transactionId\":\"ce5bfeac-0813-4478-ab93-cf4fa3fbec49\",\"approvalCode\":\"00001234\",\"receiptNo\":\"66be07884204\",\"approval_code\":\"00001234\"},\"payer\":{\"fundingInstrument\":{\"card\":{\"cardNumber\":\"512345******2346\",\"expiryMonth\":\"12\",\"expiryYear\":\"2025\"}}},\"amount\":{\"total\":{\"currency\":\"PHP\",\"value\":150000}}}},\"paymentAt\":\"2025-10-29T00:44:25.203Z\",\"3ds\":false},\"buyer\":{\"contact\":{\"email\":\"hesyv92aa@treisadiutor.com\",\"phone\":\"09614736286\"},\"firstName\":\"Mark\",\"lastName\":\"Andrew Zapatero Soliman\",\"billingAddress\":{\"line1\":\"574\",\"line2\":\"Ut nulla officia cil\",\"city\":\"Mollit maxime quibus\",\"zipCode\":\"56269\",\"countryCode\":\"PH\"},\"shippingAddress\":{\"firstName\":\"Mark\",\"lastName\":\"Andrew Zapatero Soliman\",\"line1\":\"574\",\"line2\":\"Ut nulla officia cil\",\"city\":\"Mollit maxime quibus\",\"zipCode\":\"56269\",\"countryCode\":\"PH\",\"phone\":\"09614736286\",\"email\":\"hesyv92aa@treisadiutor.com\"}},\"merchant\":{\"currency\":\"PHP\",\"email\":null,\"locale\":\"en\",\"homepageUrl\":\"https:\\/\\/d8924522-8000.asse.devtunnels.ms\\/\",\"isEmailToMerchantEnabled\":false,\"isEmailToBuyerEnabled\":false,\"isPaymentFacilitator\":false,\"isPageCustomized\":false,\"supportedSchemes\":[\"Visa\",\"Mastercard\"],\"canPayPal\":false,\"payPalEmail\":null,\"payPalWebExperienceId\":null,\"expressCheckout\":true,\"name\":\"Treis Adiutor\"},\"totalAmount\":{\"amount\":\"150000\",\"currency\":\"PHP\",\"details\":null},\"redirectUrl\":{\"success\":\"http:\\/\\/localhost:8000\\/client\\/maya\\/success?ref=TREIS-1761698615-23\",\"failure\":\"http:\\/\\/localhost:8000\\/client\\/maya\\/failure?ref=TREIS-1761698615-23\",\"cancel\":\"http:\\/\\/localhost:8000\\/client\\/maya\\/cancel?ref=TREIS-1761698615-23\"},\"transactionReferenceNumber\":\"4a57b0a1-b52e-41a0-b372-66be07884204\"}', NULL, '2025-10-29 08:43:37', '2025-10-29 08:44:30'),
(8, 29, 10, 'milestone_payment', 13, 150000.00, 'maya', 'TREIS-1761719569-29', 'confirmed', NULL, '2025-10-29 14:34:31', NULL, NULL, '{\"checkout_id\": \"1fad4aff-cab5-4759-9193-8a1d1d50d562\", \"environment\": \"sandbox\", \"payment_description\": \"Phase 1: Phase 1\"}', '1fad4aff-cab5-4759-9193-8a1d1d50d562', '{\"id\":\"1fad4aff-cab5-4759-9193-8a1d1d50d562\",\"items\":[{\"name\":\"Tarik Michael - Phase 1: Phase 1\",\"quantity\":\"1\",\"code\":\"SERVICE-29\",\"description\":\"Quis ratione autem s\",\"amount\":{\"value\":150000,\"details\":{\"discount\":\"0\",\"serviceCharge\":\"0\",\"shippingFee\":\"0\",\"tax\":\"0\",\"subtotal\":\"150000\"}},\"totalAmount\":{\"value\":150000,\"details\":{\"discount\":\"0\",\"serviceCharge\":\"0\",\"shippingFee\":\"0\",\"tax\":\"0\",\"subtotal\":\"150000\"}}}],\"metadata\":null,\"requestReferenceNumber\":\"TREIS-1761719569-29\",\"receiptNumber\":\"a34ef7101637\",\"createdAt\":\"2025-10-29T06:32:51.000Z\",\"updatedAt\":\"2025-10-29T06:34:17.908Z\",\"paymentScheme\":\"master-card\",\"expressCheckout\":true,\"refundedAmount\":\"0\",\"canPayPal\":false,\"expiredAt\":\"2025-10-29T07:32:51.000Z\",\"status\":\"COMPLETED\",\"paymentStatus\":\"PAYMENT_SUCCESS\",\"paymentDetails\":{\"responses\":{\"efs\":{\"paymentTransactionReferenceNo\":\"40ef4257-bdef-4e69-b02b-a34ef7101637\",\"createdAt\":\"2025-10-29T06:34:17.350Z\",\"status\":\"SUCCESS\",\"receipt\":{\"transactionId\":\"e5c7b0a3-d844-46a0-9d14-93c8543809de\",\"approvalCode\":\"00001234\",\"receiptNo\":\"a34ef7101637\",\"approval_code\":\"00001234\"},\"payer\":{\"fundingInstrument\":{\"card\":{\"cardNumber\":\"512345******2346\",\"expiryMonth\":\"12\",\"expiryYear\":\"2025\"}}},\"amount\":{\"total\":{\"currency\":\"PHP\",\"value\":150000}}}},\"paymentAt\":\"2025-10-29T06:34:17.907Z\",\"3ds\":false},\"buyer\":{\"contact\":{\"email\":\"markandrewsoliman.personal@gmail.com\",\"phone\":\"09761022819\"},\"firstName\":\"Mark\",\"lastName\":\"Andrew Soliman\",\"billingAddress\":{\"line1\":\"88\",\"line2\":\"Mollit et eos volupt\",\"city\":\"Cumque aliquip moles\",\"zipCode\":\"63923\",\"countryCode\":\"PH\"},\"shippingAddress\":{\"firstName\":\"Mark\",\"lastName\":\"Andrew Soliman\",\"line1\":\"88\",\"line2\":\"Mollit et eos volupt\",\"city\":\"Cumque aliquip moles\",\"zipCode\":\"63923\",\"countryCode\":\"PH\",\"phone\":\"09761022819\",\"email\":\"markandrewsoliman.personal@gmail.com\"}},\"merchant\":{\"currency\":\"PHP\",\"email\":null,\"locale\":\"en\",\"homepageUrl\":\"https:\\/\\/d8924522-8000.asse.devtunnels.ms\\/\",\"isEmailToMerchantEnabled\":false,\"isEmailToBuyerEnabled\":false,\"isPaymentFacilitator\":false,\"isPageCustomized\":false,\"supportedSchemes\":[\"Visa\",\"Mastercard\"],\"canPayPal\":false,\"payPalEmail\":null,\"payPalWebExperienceId\":null,\"expressCheckout\":true,\"name\":\"Treis Adiutor\"},\"totalAmount\":{\"amount\":\"150000\",\"currency\":\"PHP\",\"details\":null},\"redirectUrl\":{\"success\":\"http:\\/\\/localhost:8000\\/client\\/maya\\/success?ref=TREIS-1761719569-29\",\"failure\":\"http:\\/\\/localhost:8000\\/client\\/maya\\/failure?ref=TREIS-1761719569-29\",\"cancel\":\"http:\\/\\/localhost:8000\\/client\\/maya\\/cancel?ref=TREIS-1761719569-29\"},\"transactionReferenceNumber\":\"40ef4257-bdef-4e69-b02b-a34ef7101637\"}', NULL, '2025-10-29 14:32:51', '2025-10-29 14:34:31'),
(9, 38, NULL, 'full_payment', 32, 350000.00, 'maya', 'TREIS-1763091573-38', 'pending', NULL, NULL, NULL, NULL, '{\"checkout_id\": \"d5b48d3b-08f9-40b3-8323-d62db1d06cb2\", \"environment\": \"sandbox\", \"payment_description\": \"Full Project Payment\"}', NULL, NULL, NULL, '2025-11-14 11:39:35', '2025-11-14 11:39:35'),
(10, 38, NULL, 'full_payment', 32, 280000.00, 'maya', 'TREIS-1763091906-38', 'confirmed', NULL, '2025-11-14 11:48:45', NULL, NULL, '{\"checkout_id\": \"cf6902af-bda9-4e8a-a866-224f3d338fd3\", \"environment\": \"sandbox\", \"payment_description\": \"Full Project Payment\"}', 'cf6902af-bda9-4e8a-a866-224f3d338fd3', '{\"id\":\"cf6902af-bda9-4e8a-a866-224f3d338fd3\",\"items\":[{\"name\":\"Graphics Design - Full Project Payment\",\"quantity\":\"1\",\"code\":\"SERVICE-38\",\"description\":\"Branding Item\",\"amount\":{\"value\":280000,\"details\":{\"discount\":\"0\",\"serviceCharge\":\"0\",\"shippingFee\":\"0\",\"tax\":\"0\",\"subtotal\":\"280000\"}},\"totalAmount\":{\"value\":280000,\"details\":{\"discount\":\"0\",\"serviceCharge\":\"0\",\"shippingFee\":\"0\",\"tax\":\"0\",\"subtotal\":\"280000\"}}}],\"metadata\":null,\"requestReferenceNumber\":\"TREIS-1763091906-38\",\"receiptNumber\":\"8e8460420b2f\",\"createdAt\":\"2025-11-14T03:45:09.347Z\",\"updatedAt\":\"2025-11-14T03:45:59.462Z\",\"paymentScheme\":\"master-card\",\"expressCheckout\":true,\"refundedAmount\":\"0\",\"canPayPal\":false,\"expiredAt\":\"2025-11-14T04:45:09.347Z\",\"status\":\"COMPLETED\",\"paymentStatus\":\"PAYMENT_SUCCESS\",\"paymentDetails\":{\"responses\":{\"efs\":{\"paymentTransactionReferenceNo\":\"06b062ad-187a-4c6c-b514-8e8460420b2f\",\"createdAt\":\"2025-11-14T03:45:59.519Z\",\"status\":\"SUCCESS\",\"receipt\":{\"transactionId\":\"1095d2fb-0271-4abe-8204-32af3183036b\",\"approvalCode\":\"00001234\",\"receiptNo\":\"8e8460420b2f\",\"approval_code\":\"00001234\"},\"payer\":{\"fundingInstrument\":{\"card\":{\"cardNumber\":\"512345******2346\",\"expiryMonth\":\"12\",\"expiryYear\":\"2025\"}}},\"amount\":{\"total\":{\"currency\":\"PHP\",\"value\":280000}}}},\"paymentAt\":\"2025-11-14T03:45:59.460Z\",\"3ds\":false},\"buyer\":{\"contact\":{\"email\":\"yuicutie1975@gmail.com\",\"phone\":\"09761022819\"},\"firstName\":\"Sofia\",\"lastName\":\"Lorraine\",\"billingAddress\":{\"line1\":\"Dayap, Calauan, Laguna, Philippines\",\"line2\":\"Dicta quisquam bland\",\"city\":\"Calauan Calauan\",\"zipCode\":\"4012\",\"countryCode\":\"PH\"},\"shippingAddress\":{\"firstName\":\"Sofia\",\"lastName\":\"Lorraine\",\"line1\":\"Dayap, Calauan, Laguna, Philippines\",\"line2\":\"Dicta quisquam bland\",\"city\":\"Calauan Calauan\",\"zipCode\":\"4012\",\"countryCode\":\"PH\",\"phone\":\"09761022819\",\"email\":\"yuicutie1975@gmail.com\"}},\"merchant\":{\"currency\":\"PHP\",\"email\":null,\"locale\":\"en\",\"homepageUrl\":\"https:\\/\\/d8924522-8000.asse.devtunnels.ms\\/\",\"isEmailToMerchantEnabled\":false,\"isEmailToBuyerEnabled\":false,\"isPaymentFacilitator\":false,\"isPageCustomized\":false,\"supportedSchemes\":[\"Visa\",\"Mastercard\"],\"canPayPal\":false,\"payPalEmail\":null,\"payPalWebExperienceId\":null,\"expressCheckout\":true,\"name\":\"Treis Adiutor\"},\"totalAmount\":{\"amount\":\"280000\",\"currency\":\"PHP\",\"details\":null},\"redirectUrl\":{\"success\":\"http:\\/\\/localhost:8000\\/client\\/maya\\/success?ref=TREIS-1763091906-38\",\"failure\":\"http:\\/\\/localhost:8000\\/client\\/maya\\/failure?ref=TREIS-1763091906-38\",\"cancel\":\"http:\\/\\/localhost:8000\\/client\\/maya\\/cancel?ref=TREIS-1763091906-38\"},\"transactionReferenceNumber\":\"06b062ad-187a-4c6c-b514-8e8460420b2f\"}', NULL, '2025-11-14 11:45:07', '2025-11-14 11:48:45'),
(11, 35, NULL, 'full_payment', 3, 400000.00, 'maya', 'TREIS-1763093127-35', 'confirmed', NULL, '2025-11-14 12:05:56', NULL, NULL, '{\"checkout_id\": \"6ecd7ba2-19f1-4d40-bbdb-296c0b32920e\", \"environment\": \"sandbox\", \"payment_description\": \"Full Project Payment\"}', '6ecd7ba2-19f1-4d40-bbdb-296c0b32920e', '{\"id\":\"6ecd7ba2-19f1-4d40-bbdb-296c0b32920e\",\"items\":[{\"name\":\"Ecommerce 3 - Full Project Payment\",\"quantity\":\"1\",\"code\":\"SERVICE-35\",\"description\":\"aifqe\",\"amount\":{\"value\":400000,\"details\":{\"discount\":\"0\",\"serviceCharge\":\"0\",\"shippingFee\":\"0\",\"tax\":\"0\",\"subtotal\":\"400000\"}},\"totalAmount\":{\"value\":400000,\"details\":{\"discount\":\"0\",\"serviceCharge\":\"0\",\"shippingFee\":\"0\",\"tax\":\"0\",\"subtotal\":\"400000\"}}}],\"metadata\":null,\"requestReferenceNumber\":\"TREIS-1763093127-35\",\"receiptNumber\":\"6d3afef0f068\",\"createdAt\":\"2025-11-14T04:05:30.109Z\",\"updatedAt\":\"2025-11-14T04:05:54.461Z\",\"paymentScheme\":\"master-card\",\"expressCheckout\":true,\"refundedAmount\":\"0\",\"canPayPal\":false,\"expiredAt\":\"2025-11-14T05:05:30.108Z\",\"status\":\"COMPLETED\",\"paymentStatus\":\"PAYMENT_SUCCESS\",\"paymentDetails\":{\"responses\":{\"efs\":{\"paymentTransactionReferenceNo\":\"9e7ac672-88e9-4818-8731-6d3afef0f068\",\"createdAt\":\"2025-11-14T04:05:32.287Z\",\"status\":\"SUCCESS\",\"receipt\":{\"transactionId\":\"de600f22-774f-4cf9-9ddc-a4032f31d678\",\"approvalCode\":\"00001234\",\"receiptNo\":\"6d3afef0f068\",\"approval_code\":\"00001234\"},\"payer\":{\"fundingInstrument\":{\"card\":{\"cardNumber\":\"512345******2346\",\"expiryMonth\":\"12\",\"expiryYear\":\"2025\"}}},\"amount\":{\"total\":{\"currency\":\"PHP\",\"value\":400000}}}},\"paymentAt\":\"2025-11-14T04:05:54.460Z\",\"3ds\":false},\"buyer\":{\"contact\":{\"email\":\"john.smith@techstartup.com\",\"phone\":\"09614736286\"},\"firstName\":\"John\",\"lastName\":\"Smith\",\"billingAddress\":{\"line1\":\"580\",\"line2\":\"Laborum Minim aliqu\",\"city\":\"Et in voluptatem La\",\"zipCode\":\"17766\",\"countryCode\":\"PH\"},\"shippingAddress\":{\"firstName\":\"John\",\"lastName\":\"Smith\",\"line1\":\"580\",\"line2\":\"Laborum Minim aliqu\",\"city\":\"Et in voluptatem La\",\"zipCode\":\"17766\",\"countryCode\":\"PH\",\"phone\":\"09614736286\",\"email\":\"john.smith@techstartup.com\"}},\"merchant\":{\"currency\":\"PHP\",\"email\":null,\"locale\":\"en\",\"homepageUrl\":\"https:\\/\\/d8924522-8000.asse.devtunnels.ms\\/\",\"isEmailToMerchantEnabled\":false,\"isEmailToBuyerEnabled\":false,\"isPaymentFacilitator\":false,\"isPageCustomized\":false,\"supportedSchemes\":[\"Visa\",\"Mastercard\"],\"canPayPal\":false,\"payPalEmail\":null,\"payPalWebExperienceId\":null,\"expressCheckout\":true,\"name\":\"Treis Adiutor\"},\"totalAmount\":{\"amount\":\"400000\",\"currency\":\"PHP\",\"details\":null},\"redirectUrl\":{\"success\":\"http:\\/\\/localhost:8000\\/client\\/maya\\/success?ref=TREIS-1763093127-35\",\"failure\":\"http:\\/\\/localhost:8000\\/client\\/maya\\/failure?ref=TREIS-1763093127-35\",\"cancel\":\"http:\\/\\/localhost:8000\\/client\\/maya\\/cancel?ref=TREIS-1763093127-35\"},\"transactionReferenceNumber\":\"9e7ac672-88e9-4818-8731-6d3afef0f068\"}', NULL, '2025-11-14 12:05:28', '2025-11-14 12:05:56'),
(12, 9, NULL, 'full_payment', 3, 50000.00, 'maya', 'TREIS-1763093533-9', 'confirmed', NULL, '2025-11-14 12:12:42', NULL, NULL, '{\"checkout_id\": \"e62416b8-cbda-4e5d-99d3-5b97a51c1bf9\", \"environment\": \"sandbox\", \"payment_description\": \"Full Project Payment\"}', 'e62416b8-cbda-4e5d-99d3-5b97a51c1bf9', '{\"id\":\"e62416b8-cbda-4e5d-99d3-5b97a51c1bf9\",\"items\":[{\"name\":\"Website SEO Optimization - Full Project Payment\",\"quantity\":\"1\",\"code\":\"SERVICE-9\",\"description\":\"Complete SEO optimization for our existing website to improve search rankings and organic traffic. N\",\"amount\":{\"value\":50000,\"details\":{\"discount\":\"0\",\"serviceCharge\":\"0\",\"shippingFee\":\"0\",\"tax\":\"0\",\"subtotal\":\"50000\"}},\"totalAmount\":{\"value\":50000,\"details\":{\"discount\":\"0\",\"serviceCharge\":\"0\",\"shippingFee\":\"0\",\"tax\":\"0\",\"subtotal\":\"50000\"}}}],\"metadata\":null,\"requestReferenceNumber\":\"TREIS-1763093533-9\",\"receiptNumber\":\"159ddb28278d\",\"createdAt\":\"2025-11-14T04:12:15.987Z\",\"updatedAt\":\"2025-11-14T04:12:39.958Z\",\"paymentScheme\":\"master-card\",\"expressCheckout\":true,\"refundedAmount\":\"0\",\"canPayPal\":false,\"expiredAt\":\"2025-11-14T05:12:15.987Z\",\"status\":\"COMPLETED\",\"paymentStatus\":\"PAYMENT_SUCCESS\",\"paymentDetails\":{\"responses\":{\"efs\":{\"paymentTransactionReferenceNo\":\"bb57ed4a-5d88-429d-9d2b-159ddb28278d\",\"createdAt\":\"2025-11-14T04:12:41.315Z\",\"status\":\"SUCCESS\",\"receipt\":{\"transactionId\":\"2c2e925b-e945-4013-a88e-e1037a5a450f\",\"approvalCode\":\"00001234\",\"receiptNo\":\"159ddb28278d\",\"approval_code\":\"00001234\"},\"payer\":{\"fundingInstrument\":{\"card\":{\"cardNumber\":\"512345******2346\",\"expiryMonth\":\"12\",\"expiryYear\":\"2025\"}}},\"amount\":{\"total\":{\"currency\":\"PHP\",\"value\":50000}}}},\"paymentAt\":\"2025-11-14T04:12:39.957Z\",\"3ds\":false},\"buyer\":{\"contact\":{\"email\":\"john.smith@techstartup.com\",\"phone\":\"09614736286\"},\"firstName\":\"John\",\"lastName\":\"Smith\",\"billingAddress\":{\"line1\":\"9\",\"line2\":\"Doloremque fuga Ill\",\"city\":\"Eligendi explicabo\",\"zipCode\":\"59784\",\"countryCode\":\"PH\"},\"shippingAddress\":{\"firstName\":\"John\",\"lastName\":\"Smith\",\"line1\":\"9\",\"line2\":\"Doloremque fuga Ill\",\"city\":\"Eligendi explicabo\",\"zipCode\":\"59784\",\"countryCode\":\"PH\",\"phone\":\"09614736286\",\"email\":\"john.smith@techstartup.com\"}},\"merchant\":{\"currency\":\"PHP\",\"email\":null,\"locale\":\"en\",\"homepageUrl\":\"https:\\/\\/d8924522-8000.asse.devtunnels.ms\\/\",\"isEmailToMerchantEnabled\":false,\"isEmailToBuyerEnabled\":false,\"isPaymentFacilitator\":false,\"isPageCustomized\":false,\"supportedSchemes\":[\"Visa\",\"Mastercard\"],\"canPayPal\":false,\"payPalEmail\":null,\"payPalWebExperienceId\":null,\"expressCheckout\":true,\"name\":\"Treis Adiutor\"},\"totalAmount\":{\"amount\":\"50000\",\"currency\":\"PHP\",\"details\":null},\"redirectUrl\":{\"success\":\"http:\\/\\/localhost:8000\\/client\\/maya\\/success?ref=TREIS-1763093533-9\",\"failure\":\"http:\\/\\/localhost:8000\\/client\\/maya\\/failure?ref=TREIS-1763093533-9\",\"cancel\":\"http:\\/\\/localhost:8000\\/client\\/maya\\/cancel?ref=TREIS-1763093533-9\"},\"transactionReferenceNumber\":\"bb57ed4a-5d88-429d-9d2b-159ddb28278d\"}', NULL, '2025-11-14 12:12:14', '2025-11-14 12:12:42'),
(13, 40, NULL, 'full_payment', 18, 500000.00, 'maya', 'TREIS-1764560070-40', 'confirmed', NULL, '2025-12-01 11:35:03', NULL, NULL, '{\"checkout_id\": \"6a3df609-200b-46ca-94bd-6f4a7db00cfd\", \"environment\": \"sandbox\", \"payment_description\": \"Full Project Payment\"}', '6a3df609-200b-46ca-94bd-6f4a7db00cfd', '{\"id\":\"6a3df609-200b-46ca-94bd-6f4a7db00cfd\",\"items\":[{\"name\":\"UNIMERCE \\u2013 Full E-Commerce Platform Development & Optimization - Full Project Payment\",\"quantity\":\"1\",\"code\":\"SERVICE-40\",\"description\":\"I am developing an end-to-end e-commerce platform named UNIMERCE, intended to support multiple user \",\"amount\":{\"value\":400000,\"details\":{\"discount\":\"0\",\"serviceCharge\":\"0\",\"shippingFee\":\"0\",\"tax\":\"0\",\"subtotal\":\"400000\"}},\"totalAmount\":{\"value\":400000,\"details\":{\"discount\":\"0\",\"serviceCharge\":\"0\",\"shippingFee\":\"0\",\"tax\":\"0\",\"subtotal\":\"400000\"}}}],\"metadata\":null,\"requestReferenceNumber\":\"TREIS-1764560070-40\",\"receiptNumber\":\"3b780ead1925\",\"createdAt\":\"2025-12-01T03:34:32.167Z\",\"updatedAt\":\"2025-12-01T03:35:01.553Z\",\"paymentScheme\":\"visa\",\"expressCheckout\":true,\"refundedAmount\":\"0\",\"canPayPal\":false,\"expiredAt\":\"2025-12-01T04:34:32.167Z\",\"status\":\"COMPLETED\",\"paymentStatus\":\"PAYMENT_SUCCESS\",\"paymentDetails\":{\"responses\":{\"efs\":{\"paymentTransactionReferenceNo\":\"132cad28-7af3-48c0-8565-3b780ead1925\",\"createdAt\":\"2025-12-01T03:35:01.067Z\",\"status\":\"SUCCESS\",\"receipt\":{\"transactionId\":\"b9ee3854-a4f9-48df-9cc3-b95b15e3526e\",\"approvalCode\":\"00001234\",\"receiptNo\":\"3b780ead1925\",\"approval_code\":\"00001234\"},\"payer\":{\"fundingInstrument\":{\"card\":{\"cardNumber\":\"401200******3335\",\"expiryMonth\":\"12\",\"expiryYear\":\"2027\"}}},\"amount\":{\"total\":{\"currency\":\"PHP\",\"value\":400000}}}},\"paymentAt\":\"2025-12-01T03:35:01.552Z\",\"3ds\":false},\"buyer\":{\"contact\":{\"email\":\"markandrew.soliman@lspu.edu.ph\",\"phone\":\"09614736286\"},\"firstName\":\"Nissim\",\"lastName\":\"Dalton\",\"billingAddress\":{\"line1\":\"49 Milton Freeway\",\"line2\":\"Dicta quisquam bland\",\"city\":\"Cum et facere aperia\",\"zipCode\":\"56624\",\"countryCode\":\"PH\"},\"shippingAddress\":{\"firstName\":\"Nissim\",\"lastName\":\"Dalton\",\"line1\":\"49 Milton Freeway\",\"line2\":\"Dicta quisquam bland\",\"city\":\"Cum et facere aperia\",\"zipCode\":\"56624\",\"countryCode\":\"PH\",\"phone\":\"09614736286\",\"email\":\"markandrew.soliman@lspu.edu.ph\"}},\"merchant\":{\"currency\":\"PHP\",\"email\":null,\"locale\":\"en\",\"homepageUrl\":\"https:\\/\\/d8924522-8000.asse.devtunnels.ms\\/\",\"isEmailToMerchantEnabled\":false,\"isEmailToBuyerEnabled\":false,\"isPaymentFacilitator\":false,\"isPageCustomized\":false,\"supportedSchemes\":[\"Visa\",\"Mastercard\"],\"canPayPal\":false,\"payPalEmail\":null,\"payPalWebExperienceId\":null,\"expressCheckout\":true,\"name\":\"Treis Adiutor\"},\"totalAmount\":{\"amount\":\"400000\",\"currency\":\"PHP\",\"details\":null},\"redirectUrl\":{\"success\":\"http:\\/\\/localhost:8000\\/client\\/maya\\/success?ref=TREIS-1764560070-40\",\"failure\":\"http:\\/\\/localhost:8000\\/client\\/maya\\/failure?ref=TREIS-1764560070-40\",\"cancel\":\"http:\\/\\/localhost:8000\\/client\\/maya\\/cancel?ref=TREIS-1764560070-40\"},\"transactionReferenceNumber\":\"132cad28-7af3-48c0-8565-3b780ead1925\"}', NULL, '2025-12-01 11:34:31', '2025-12-01 11:35:03'),
(14, 41, NULL, 'full_payment', 18, 499000.00, 'maya', 'TREIS-1764562439-41', 'confirmed', NULL, '2025-12-01 12:14:35', NULL, NULL, '{\"checkout_id\": \"b6eb2e4e-7253-41f5-ab57-7806d52b6b69\", \"environment\": \"sandbox\", \"payment_description\": \"Full Project Payment\"}', 'b6eb2e4e-7253-41f5-ab57-7806d52b6b69', '{\"id\":\"b6eb2e4e-7253-41f5-ab57-7806d52b6b69\",\"items\":[{\"name\":\"Multi-Platform Study Cards System (Web + Android) - Full Project Payment\",\"quantity\":\"1\",\"code\":\"SERVICE-41\",\"description\":\"I am working on a Study Cards System that will be developed for both Android (Java + Firebase) and W\",\"amount\":{\"value\":499000,\"details\":{\"discount\":\"0\",\"serviceCharge\":\"0\",\"shippingFee\":\"0\",\"tax\":\"0\",\"subtotal\":\"499000\"}},\"totalAmount\":{\"value\":499000,\"details\":{\"discount\":\"0\",\"serviceCharge\":\"0\",\"shippingFee\":\"0\",\"tax\":\"0\",\"subtotal\":\"499000\"}}}],\"metadata\":null,\"requestReferenceNumber\":\"TREIS-1764562439-41\",\"receiptNumber\":\"5addaa1cb698\",\"createdAt\":\"2025-12-01T04:14:01.616Z\",\"updatedAt\":\"2025-12-01T04:14:33.998Z\",\"paymentScheme\":\"visa\",\"expressCheckout\":true,\"refundedAmount\":\"0\",\"canPayPal\":false,\"expiredAt\":\"2025-12-01T05:14:01.616Z\",\"status\":\"COMPLETED\",\"paymentStatus\":\"PAYMENT_SUCCESS\",\"paymentDetails\":{\"responses\":{\"efs\":{\"paymentTransactionReferenceNo\":\"3123644e-e62a-48ed-bed7-5addaa1cb698\",\"createdAt\":\"2025-12-01T04:14:33.506Z\",\"status\":\"SUCCESS\",\"receipt\":{\"transactionId\":\"6764c0ab-67b7-40a2-a4b2-9d9b9d3ad6f0\",\"approvalCode\":\"00001234\",\"receiptNo\":\"5addaa1cb698\",\"approval_code\":\"00001234\"},\"payer\":{\"fundingInstrument\":{\"card\":{\"cardNumber\":\"401200******3335\",\"expiryMonth\":\"12\",\"expiryYear\":\"2027\"}}},\"amount\":{\"total\":{\"currency\":\"PHP\",\"value\":499000}}}},\"paymentAt\":\"2025-12-01T04:14:33.997Z\",\"3ds\":false},\"buyer\":{\"contact\":{\"email\":\"markandrew.soliman@lspu.edu.ph\",\"phone\":\"09614736286\"},\"firstName\":\"Nissim\",\"lastName\":\"Dalton\",\"billingAddress\":{\"line1\":\"Dayap, Calauan, Laguna, Philippines\",\"line2\":\"Dayap\",\"city\":\"Calauan\",\"zipCode\":\"4012\",\"countryCode\":\"PH\"},\"shippingAddress\":{\"firstName\":\"Nissim\",\"lastName\":\"Dalton\",\"line1\":\"Dayap, Calauan, Laguna, Philippines\",\"line2\":\"Dayap\",\"city\":\"Calauan\",\"zipCode\":\"4012\",\"countryCode\":\"PH\",\"phone\":\"09614736286\",\"email\":\"markandrew.soliman@lspu.edu.ph\"}},\"merchant\":{\"currency\":\"PHP\",\"email\":null,\"locale\":\"en\",\"homepageUrl\":\"https:\\/\\/d8924522-8000.asse.devtunnels.ms\\/\",\"isEmailToMerchantEnabled\":false,\"isEmailToBuyerEnabled\":false,\"isPaymentFacilitator\":false,\"isPageCustomized\":false,\"supportedSchemes\":[\"Visa\",\"Mastercard\"],\"canPayPal\":false,\"payPalEmail\":null,\"payPalWebExperienceId\":null,\"expressCheckout\":true,\"name\":\"Treis Adiutor\"},\"totalAmount\":{\"amount\":\"499000\",\"currency\":\"PHP\",\"details\":null},\"redirectUrl\":{\"success\":\"http:\\/\\/localhost:8000\\/client\\/maya\\/success?ref=TREIS-1764562439-41\",\"failure\":\"http:\\/\\/localhost:8000\\/client\\/maya\\/failure?ref=TREIS-1764562439-41\",\"cancel\":\"http:\\/\\/localhost:8000\\/client\\/maya\\/cancel?ref=TREIS-1764562439-41\"},\"transactionReferenceNumber\":\"3123644e-e62a-48ed-bed7-5addaa1cb698\"}', NULL, '2025-12-01 12:14:00', '2025-12-01 12:14:35'),
(15, 42, NULL, 'full_payment', 3, 425000.00, 'maya', 'TREIS-1764928068-42', 'confirmed', NULL, '2025-12-05 17:52:20', NULL, NULL, '{\"checkout_id\": \"df62e432-d713-4ec3-9290-3a959e8599a6\", \"environment\": \"sandbox\", \"payment_description\": \"Full Project Payment\"}', 'df62e432-d713-4ec3-9290-3a959e8599a6', '{\"id\":\"df62e432-d713-4ec3-9290-3a959e8599a6\",\"items\":[{\"name\":\"NovaSync Vendor Intelligence Dashboard - Full Project Payment\",\"quantity\":\"1\",\"code\":\"SERVICE-42\",\"description\":\"This project is a web-based analytics and automation dashboard that plugs into an existing multi-ven\",\"amount\":{\"value\":425000,\"details\":{\"discount\":\"0\",\"serviceCharge\":\"0\",\"shippingFee\":\"0\",\"tax\":\"0\",\"subtotal\":\"425000\"}},\"totalAmount\":{\"value\":425000,\"details\":{\"discount\":\"0\",\"serviceCharge\":\"0\",\"shippingFee\":\"0\",\"tax\":\"0\",\"subtotal\":\"425000\"}}}],\"metadata\":null,\"requestReferenceNumber\":\"TREIS-1764928068-42\",\"receiptNumber\":\"cf67b1b63aee\",\"createdAt\":\"2025-12-05T09:47:50.516Z\",\"updatedAt\":\"2025-12-05T09:49:41.493Z\",\"paymentScheme\":\"visa\",\"expressCheckout\":true,\"refundedAmount\":\"0\",\"canPayPal\":false,\"expiredAt\":\"2025-12-05T10:47:50.515Z\",\"status\":\"COMPLETED\",\"paymentStatus\":\"PAYMENT_SUCCESS\",\"paymentDetails\":{\"responses\":{\"efs\":{\"paymentTransactionReferenceNo\":\"eb9d4ee0-9d70-4517-a6e8-cf67b1b63aee\",\"createdAt\":\"2025-12-05T09:49:19.127Z\",\"status\":\"SUCCESS\",\"receipt\":{\"transactionId\":\"2c911804-9123-42f5-a0cc-f0b45a02fa41\",\"approvalCode\":\"00001234\",\"receiptNo\":\"cf67b1b63aee\",\"approval_code\":\"00001234\"},\"payer\":{\"fundingInstrument\":{\"card\":{\"cardNumber\":\"483442******2474\",\"expiryMonth\":\"12\",\"expiryYear\":\"2030\"}}},\"amount\":{\"total\":{\"currency\":\"PHP\",\"value\":425000}}}},\"paymentAt\":\"2025-12-05T09:49:41.493Z\",\"3ds\":false},\"buyer\":{\"contact\":{\"email\":\"john.smith@treisadiutor.com\",\"phone\":\"09614736286\"},\"firstName\":\"John\",\"lastName\":\"Smith\",\"billingAddress\":{\"line1\":\"866\",\"line2\":\"Magna non quibusdam\",\"city\":\"Excepturi sed saepe\",\"zipCode\":\"81664\",\"countryCode\":\"PH\"},\"shippingAddress\":{\"firstName\":\"John\",\"lastName\":\"Smith\",\"line1\":\"866\",\"line2\":\"Magna non quibusdam\",\"city\":\"Excepturi sed saepe\",\"zipCode\":\"81664\",\"countryCode\":\"PH\",\"phone\":\"09614736286\",\"email\":\"john.smith@treisadiutor.com\"}},\"merchant\":{\"currency\":\"PHP\",\"email\":null,\"locale\":\"en\",\"homepageUrl\":\"https:\\/\\/d8924522-8000.asse.devtunnels.ms\\/\",\"isEmailToMerchantEnabled\":false,\"isEmailToBuyerEnabled\":false,\"isPaymentFacilitator\":false,\"isPageCustomized\":false,\"supportedSchemes\":[\"Visa\",\"Mastercard\"],\"canPayPal\":false,\"payPalEmail\":null,\"payPalWebExperienceId\":null,\"expressCheckout\":true,\"name\":\"Treis Adiutor\"},\"totalAmount\":{\"amount\":\"425000\",\"currency\":\"PHP\",\"details\":null},\"redirectUrl\":{\"success\":\"http:\\/\\/localhost:8000\\/client\\/maya\\/success?ref=TREIS-1764928068-42\",\"failure\":\"http:\\/\\/localhost:8000\\/client\\/maya\\/failure?ref=TREIS-1764928068-42\",\"cancel\":\"http:\\/\\/localhost:8000\\/client\\/maya\\/cancel?ref=TREIS-1764928068-42\"},\"transactionReferenceNumber\":\"eb9d4ee0-9d70-4517-a6e8-cf67b1b63aee\"}', NULL, '2025-12-05 17:47:49', '2025-12-05 17:52:20');
INSERT INTO `payments` (`id`, `service_request_id`, `milestone_id`, `payment_type`, `client_id`, `amount`, `payment_method`, `payment_reference`, `status`, `notes`, `confirmed_at`, `notification_sent_at`, `confirmed_by`, `payment_details`, `transaction_id`, `gateway_response`, `gateway_fee`, `created_at`, `updated_at`) VALUES
(16, 43, NULL, 'full_payment', 3, 250000.00, 'maya', 'TREIS-1764928249-43', 'confirmed', NULL, '2025-12-05 17:55:55', NULL, NULL, '{\"checkout_id\": \"c364ebe0-7599-4037-98da-8089d51b8771\", \"environment\": \"sandbox\", \"payment_description\": \"Full Project Payment\"}', 'c364ebe0-7599-4037-98da-8089d51b8771', '{\"id\":\"c364ebe0-7599-4037-98da-8089d51b8771\",\"items\":[{\"name\":\"EdgeRelay - Decentralized Content Delivery Network for Small-Scale Creators - Full Project Payment\",\"quantity\":\"1\",\"code\":\"SERVICE-43\",\"description\":\"Building a peer-assisted CDN platform that allows independent content creators, educators, and small\",\"amount\":{\"value\":250000,\"details\":{\"discount\":\"0\",\"serviceCharge\":\"0\",\"shippingFee\":\"0\",\"tax\":\"0\",\"subtotal\":\"250000\"}},\"totalAmount\":{\"value\":250000,\"details\":{\"discount\":\"0\",\"serviceCharge\":\"0\",\"shippingFee\":\"0\",\"tax\":\"0\",\"subtotal\":\"250000\"}}}],\"metadata\":null,\"requestReferenceNumber\":\"TREIS-1764928249-43\",\"receiptNumber\":\"aaf6683b7308\",\"createdAt\":\"2025-12-05T09:50:50.598Z\",\"updatedAt\":\"2025-12-05T09:53:00.775Z\",\"paymentScheme\":\"visa\",\"expressCheckout\":true,\"refundedAmount\":\"0\",\"canPayPal\":false,\"expiredAt\":\"2025-12-05T10:50:50.597Z\",\"status\":\"COMPLETED\",\"paymentStatus\":\"PAYMENT_SUCCESS\",\"paymentDetails\":{\"responses\":{\"efs\":{\"paymentTransactionReferenceNo\":\"bb17fe10-36fb-4873-8190-aaf6683b7308\",\"createdAt\":\"2025-12-05T09:52:59.473Z\",\"status\":\"SUCCESS\",\"receipt\":{\"transactionId\":\"127cecb8-f73e-4727-b2f1-4350d38d49ad\",\"approvalCode\":\"00001234\",\"receiptNo\":\"aaf6683b7308\",\"approval_code\":\"00001234\"},\"payer\":{\"fundingInstrument\":{\"card\":{\"cardNumber\":\"483442******2474\",\"expiryMonth\":\"12\",\"expiryYear\":\"2030\"}}},\"amount\":{\"total\":{\"currency\":\"PHP\",\"value\":250000}}}},\"paymentAt\":\"2025-12-05T09:53:00.774Z\",\"3ds\":false},\"buyer\":{\"contact\":{\"email\":\"john.smith@treisadiutor.com\",\"phone\":\"09614736286\"},\"firstName\":\"John\",\"lastName\":\"Smith\",\"billingAddress\":{\"line1\":\"87\",\"line2\":\"Qui consectetur comm\",\"city\":\"Temporibus non id e\",\"zipCode\":\"20316\",\"countryCode\":\"PH\"},\"shippingAddress\":{\"firstName\":\"John\",\"lastName\":\"Smith\",\"line1\":\"87\",\"line2\":\"Qui consectetur comm\",\"city\":\"Temporibus non id e\",\"zipCode\":\"20316\",\"countryCode\":\"PH\",\"phone\":\"09614736286\",\"email\":\"john.smith@treisadiutor.com\"}},\"merchant\":{\"currency\":\"PHP\",\"email\":null,\"locale\":\"en\",\"homepageUrl\":\"https:\\/\\/d8924522-8000.asse.devtunnels.ms\\/\",\"isEmailToMerchantEnabled\":false,\"isEmailToBuyerEnabled\":false,\"isPaymentFacilitator\":false,\"isPageCustomized\":false,\"supportedSchemes\":[\"Visa\",\"Mastercard\"],\"canPayPal\":false,\"payPalEmail\":null,\"payPalWebExperienceId\":null,\"expressCheckout\":true,\"name\":\"Treis Adiutor\"},\"totalAmount\":{\"amount\":\"250000\",\"currency\":\"PHP\",\"details\":null},\"redirectUrl\":{\"success\":\"http:\\/\\/localhost:8000\\/client\\/maya\\/success?ref=TREIS-1764928249-43\",\"failure\":\"http:\\/\\/localhost:8000\\/client\\/maya\\/failure?ref=TREIS-1764928249-43\",\"cancel\":\"http:\\/\\/localhost:8000\\/client\\/maya\\/cancel?ref=TREIS-1764928249-43\"},\"transactionReferenceNumber\":\"bb17fe10-36fb-4873-8190-aaf6683b7308\"}', NULL, '2025-12-05 17:50:50', '2025-12-05 17:55:55'),
(17, 45, NULL, 'full_payment', 3, 400000.00, 'maya', 'TREIS-1764934140-45', 'confirmed', NULL, '2025-12-05 19:30:00', NULL, NULL, '{\"checkout_id\": \"795099c2-3e20-4ee3-9135-a4d1c1a60b8d\", \"environment\": \"sandbox\", \"payment_description\": \"Full Project Payment\"}', '795099c2-3e20-4ee3-9135-a4d1c1a60b8d', '{\"id\":\"795099c2-3e20-4ee3-9135-a4d1c1a60b8d\",\"items\":[{\"name\":\"SynthAI - Autonomous Code Review and Technical Debt Analyzer - Full Project Payment\",\"quantity\":\"1\",\"code\":\"SERVICE-45\",\"description\":\"Building an intelligent code review automation platform that integrates directly into Git workflows \",\"amount\":{\"value\":400000,\"details\":{\"discount\":\"0\",\"serviceCharge\":\"0\",\"shippingFee\":\"0\",\"tax\":\"0\",\"subtotal\":\"400000\"}},\"totalAmount\":{\"value\":400000,\"details\":{\"discount\":\"0\",\"serviceCharge\":\"0\",\"shippingFee\":\"0\",\"tax\":\"0\",\"subtotal\":\"400000\"}}}],\"metadata\":null,\"requestReferenceNumber\":\"TREIS-1764934140-45\",\"receiptNumber\":\"9c8b12a3a5b1\",\"createdAt\":\"2025-12-05T11:29:02.660Z\",\"updatedAt\":\"2025-12-05T11:29:39.913Z\",\"paymentScheme\":\"visa\",\"expressCheckout\":true,\"refundedAmount\":\"0\",\"canPayPal\":false,\"expiredAt\":\"2025-12-05T12:29:02.659Z\",\"status\":\"COMPLETED\",\"paymentStatus\":\"PAYMENT_SUCCESS\",\"paymentDetails\":{\"responses\":{\"efs\":{\"paymentTransactionReferenceNo\":\"dd1f7070-9325-4c85-9285-9c8b12a3a5b1\",\"createdAt\":\"2025-12-05T11:29:38.909Z\",\"status\":\"SUCCESS\",\"receipt\":{\"transactionId\":\"18824de8-5be3-4be3-99ae-a36717fa4b9e\",\"approvalCode\":\"00001234\",\"receiptNo\":\"9c8b12a3a5b1\",\"approval_code\":\"00001234\"},\"payer\":{\"fundingInstrument\":{\"card\":{\"cardNumber\":\"483442******2474\",\"expiryMonth\":\"12\",\"expiryYear\":\"2030\"}}},\"amount\":{\"total\":{\"currency\":\"PHP\",\"value\":400000}}}},\"paymentAt\":\"2025-12-05T11:29:39.912Z\",\"3ds\":false},\"buyer\":{\"contact\":{\"email\":\"john.smith@treisadiutor.com\",\"phone\":\"09614736286\"},\"firstName\":\"John\",\"lastName\":\"Smith\",\"billingAddress\":{\"line1\":\"999\",\"line2\":\"Et dolores dolores r\",\"city\":\"Eu amet quaerat sed\",\"zipCode\":\"15918\",\"countryCode\":\"PH\"},\"shippingAddress\":{\"firstName\":\"John\",\"lastName\":\"Smith\",\"line1\":\"999\",\"line2\":\"Et dolores dolores r\",\"city\":\"Eu amet quaerat sed\",\"zipCode\":\"15918\",\"countryCode\":\"PH\",\"phone\":\"09614736286\",\"email\":\"john.smith@treisadiutor.com\"}},\"merchant\":{\"currency\":\"PHP\",\"email\":null,\"locale\":\"en\",\"homepageUrl\":\"https:\\/\\/d8924522-8000.asse.devtunnels.ms\\/\",\"isEmailToMerchantEnabled\":false,\"isEmailToBuyerEnabled\":false,\"isPaymentFacilitator\":false,\"isPageCustomized\":false,\"supportedSchemes\":[\"Visa\",\"Mastercard\"],\"canPayPal\":false,\"payPalEmail\":null,\"payPalWebExperienceId\":null,\"expressCheckout\":true,\"name\":\"Treis Adiutor\"},\"totalAmount\":{\"amount\":\"400000\",\"currency\":\"PHP\",\"details\":null},\"redirectUrl\":{\"success\":\"http:\\/\\/localhost:8000\\/client\\/maya\\/success?ref=TREIS-1764934140-45\",\"failure\":\"http:\\/\\/localhost:8000\\/client\\/maya\\/failure?ref=TREIS-1764934140-45\",\"cancel\":\"http:\\/\\/localhost:8000\\/client\\/maya\\/cancel?ref=TREIS-1764934140-45\"},\"transactionReferenceNumber\":\"dd1f7070-9325-4c85-9285-9c8b12a3a5b1\"}', NULL, '2025-12-05 19:29:03', '2025-12-05 19:30:00');

-- --------------------------------------------------------

--
-- Table structure for table `payouts`
--

CREATE TABLE `payouts` (
  `id` bigint UNSIGNED NOT NULL,
  `payout_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `adiutor_id` bigint UNSIGNED NOT NULL,
  `processed_by` bigint UNSIGNED DEFAULT NULL,
  `amount` decimal(10,2) NOT NULL,
  `currency` varchar(3) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'PHP',
  `status` enum('pending','processing','completed','cancelled','failed') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `payout_method` enum('bank_transfer','paypal','gcash','paymaya','cash','check','other') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `period_start` date DEFAULT NULL,
  `period_end` date DEFAULT NULL,
  `payout_details` json DEFAULT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `adiutor_notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `reference_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `proof_of_payment` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `requested_at` timestamp NULL DEFAULT NULL,
  `processed_at` timestamp NULL DEFAULT NULL,
  `completed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payout_items`
--

CREATE TABLE `payout_items` (
  `id` bigint UNSIGNED NOT NULL,
  `payout_id` bigint UNSIGNED NOT NULL,
  `time_entry_id` bigint UNSIGNED DEFAULT NULL,
  `task_id` bigint UNSIGNED DEFAULT NULL,
  `project_id` bigint UNSIGNED DEFAULT NULL,
  `item_type` enum('time_entry','fixed_task','bonus','adjustment','other') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'time_entry',
  `description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `hours` decimal(8,2) DEFAULT NULL,
  `rate` decimal(8,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `projects`
--

CREATE TABLE `projects` (
  `id` bigint UNSIGNED NOT NULL,
  `service_request_id` bigint UNSIGNED NOT NULL,
  `client_id` bigint UNSIGNED NOT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `requirements` json DEFAULT NULL,
  `skills_required` json DEFAULT NULL,
  `budget` decimal(10,2) DEFAULT NULL,
  `budget_type` enum('fixed','hourly') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'fixed',
  `deadline` date DEFAULT NULL,
  `started_at` timestamp NULL DEFAULT NULL,
  `completed_at` timestamp NULL DEFAULT NULL,
  `priority` enum('low','medium','high','urgent') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'medium',
  `status` enum('active','in_progress','review','completed','cancelled') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `attachments` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `projects`
--

INSERT INTO `projects` (`id`, `service_request_id`, `client_id`, `title`, `description`, `requirements`, `skills_required`, `budget`, `budget_type`, `deadline`, `started_at`, `completed_at`, `priority`, `status`, `attachments`, `created_at`, `updated_at`) VALUES
(1, 2, 4, 'Complete Brand Identity Package', 'Complete rebrand for our design studio including new logo, color palette, typography, business cards, letterhead, and brand guidelines. We want a modern, creative identity that reflects our innovative approach to design.', '{\"letterhead\": true, \"color_palette\": true, \"business_cards\": true, \"logo_variations\": true, \"brand_guidelines\": true, \"typography_guide\": true, \"social_media_templates\": true}', NULL, 3250.00, 'fixed', '2025-11-24', '2025-10-23 06:38:18', NULL, 'medium', 'active', NULL, '2025-10-24 06:38:18', '2025-10-25 06:38:18'),
(2, 4, 6, 'Nonprofit Website with Donation System', 'WordPress website for our nonprofit with donation functionality, volunteer registration, event management, and blog. Need to integrate with popular donation platforms and make it easy for visitors to get involved.', '{\"wordpress_cms\": true, \"event_calendar\": true, \"event_management\": true, \"donation_platform\": true, \"newsletter_signup\": true, \"blog_functionality\": true, \"volunteer_registration\": true}', NULL, 4000.00, 'fixed', '2025-12-09', '2025-10-20 06:38:18', NULL, 'medium', 'in_progress', NULL, '2025-10-24 18:38:18', '2025-10-25 06:38:18'),
(3, 7, 5, 'E-commerce Integration API', 'REST API to integrate our e-commerce platform with various third-party services including inventory management, shipping providers, and accounting software. Need comprehensive documentation and testing.', '{\"rate_limiting\": true, \"testing_suite\": true, \"authentication\": true, \"restful_design\": true, \"webhook_support\": true, \"comprehensive_docs\": true, \"shipping_integration\": true, \"inventory_integration\": true, \"accounting_integration\": true}', NULL, 10000.00, 'fixed', '2026-01-23', '2025-10-20 06:38:18', NULL, 'medium', 'active', NULL, '2025-10-23 06:38:18', '2025-10-25 06:38:18'),
(4, 10, 5, 'Ongoing Website Maintenance', 'Monthly maintenance service for our corporate website including security updates, performance monitoring, content updates, and backup management.', '{\"24_7_monitoring\": true, \"content_updates\": true, \"monthly_reports\": true, \"security_updates\": true, \"backup_management\": true, \"emergency_support\": true, \"content_management\": true, \"performance_monitoring\": true}', NULL, 650.00, 'fixed', '2025-11-24', '2025-10-23 06:38:18', NULL, 'low', 'completed', NULL, '2025-10-25 00:38:18', '2025-10-25 06:38:18'),
(5, 11, 12, 'Digital Strategy Consulting', 'I\'m interested in: Digital Strategy Consulting\r\n\r\nStrategic consulting for digital transformation, online presence optimization, and growth planning.\r\n\r\nAdditional details:', NULL, NULL, 500000.00, 'fixed', '2026-04-30', NULL, NULL, 'medium', 'completed', NULL, '2025-10-25 06:45:43', '2025-10-25 07:13:47'),
(6, 22, 23, 'Digital Strategy Consulting', 'I\'m interested in: Digital Strategy Consulting\r\n\r\nStrategic consulting for digital transformation, online presence optimization, and growth planning.\r\n\r\nAdditional details:', NULL, NULL, 500000.00, 'fixed', '2026-03-05', NULL, NULL, 'medium', 'active', NULL, '2025-10-28 23:48:36', '2025-10-28 23:48:36'),
(7, 23, 25, 'Digital Strategy Consulting', 'I\'m interested in: Digital Strategy Consulting\r\n\r\nStrategic consulting for digital transformation, online presence optimization, and growth planning.\r\n\r\nAdditional details:', NULL, NULL, 500000.00, 'fixed', NULL, NULL, NULL, 'medium', 'active', NULL, '2025-10-29 08:40:48', '2025-10-29 08:40:48'),
(8, 29, 13, 'Tarik Michael', 'Quis ratione autem s', NULL, NULL, 500000.00, 'fixed', '2026-11-25', NULL, NULL, 'medium', 'active', NULL, '2025-10-29 14:31:47', '2025-10-29 14:31:47'),
(10, 1, 3, 'TechStartup Corporate Website', 'We need a modern, professional website for our AI startup that showcases our products, team, and company culture. The site should be responsive, fast-loading, and include a contact form, blog section, and integration with our CRM system.', NULL, NULL, 50000.00, 'fixed', '2025-12-24', NULL, NULL, 'high', 'active', NULL, '2025-11-10 11:34:45', '2025-11-10 11:34:45'),
(11, 5, 3, 'AI Assistant Mobile App', 'Cross-platform mobile app for our AI assistant service. Users should be able to interact with AI, save conversations, manage settings, and sync across devices. Integration with our existing API is required.', NULL, NULL, 50000.00, 'fixed', '2026-03-24', NULL, NULL, 'high', 'active', NULL, '2025-11-10 11:35:26', '2025-11-10 11:35:26'),
(12, 33, 3, 'Ecommerce 2', 'Just make it pretty', NULL, NULL, 50000.00, 'fixed', NULL, NULL, NULL, 'medium', 'active', NULL, '2025-11-10 11:35:53', '2025-11-10 11:35:53'),
(13, 9, 3, 'Website SEO Optimization', 'Complete SEO optimization for our existing website to improve search rankings and organic traffic. Need keyword research, on-page optimization, and ongoing monitoring.', NULL, NULL, 50000.00, 'fixed', '2025-12-24', NULL, NULL, 'high', 'completed', NULL, '2025-11-10 11:36:46', '2025-11-10 12:52:25'),
(14, 34, 3, 'Ecommerce 3', 'kjvksjf', NULL, NULL, 50000.00, 'fixed', '2025-12-06', NULL, NULL, 'medium', 'completed', NULL, '2025-11-10 11:37:12', '2025-11-10 12:05:16'),
(16, 36, 32, 'Game Mobile Application', 'Modern design', NULL, NULL, 500000.00, 'fixed', '2025-11-30', NULL, NULL, 'medium', 'active', NULL, '2025-11-14 11:30:45', '2025-11-14 11:30:45'),
(17, 38, 32, 'Graphics Design', 'Branding Item', NULL, NULL, 280000.00, 'fixed', '2025-11-20', NULL, NULL, 'medium', 'active', NULL, '2025-11-14 11:36:00', '2025-11-14 12:07:39'),
(18, 35, 3, 'Ecommerce 3', 'aifqe', NULL, NULL, 400000.00, 'fixed', '2025-12-06', NULL, NULL, 'medium', 'active', NULL, '2025-11-14 12:04:27', '2025-11-14 12:07:39'),
(19, 40, 18, 'UNIMERCE – Full E-Commerce Platform Development & Optimization', 'I am developing an end-to-end e-commerce platform named UNIMERCE, intended to support multiple user roles (Super Admin, Sellers, and Buyers). The project involves creating a dynamic and responsive website using HTML, CSS, JavaScript, Python (Flask), and MySQL.\r\n\r\nThe system requires key modules including:\r\n\r\nUser registration, login, and role management\r\n\r\nDynamic homepage with banners, categories, subcategories, and product listings\r\n\r\nSeller dashboard with product management and analytics\r\n\r\nBuyer interface for product browsing, cart, checkout, and order tracking\r\n\r\nReal-time promotions such as “Deals of the Week” with countdown timers\r\n\r\nDatabase design and integration using SQLYog\r\n\r\nResponsive UI and improvements in layout, design, and user experience\r\n\r\nIntegration with Docker for local development (optional)', NULL, NULL, 500000.00, 'fixed', '2026-11-25', NULL, NULL, 'medium', 'completed', NULL, '2025-12-01 11:20:52', '2025-12-01 14:23:29'),
(20, 41, 18, 'Multi-Platform Study Cards System (Web + Android)', 'I am working on a Study Cards System that will be developed for both Android (Java + Firebase) and Web (Flask / Java). The project includes features such as creating study sets, viewing study cards, dynamic dashboards, account management, and syncing data between platforms.\r\n\r\nThe goals include:\r\n\r\nBuilding the web application version of the existing Android app\r\n\r\nDesigning a user-friendly interface for creating and managing study sets\r\n\r\nSetting up Firebase and MySQL/Flask integration depending on the module\r\n\r\nImplementing dynamic content loading (dashboard cards, categories, user data)\r\n\r\nEnsuring responsive layout and clean UI\r\n\r\nImproving performance and fixing existing bugs\r\n\r\nAssisting with database structure and flow diagrams\r\n\r\nThe timeline is ongoing, and I need help finalizing core features, connecting backend logic, and polishing the UI/UX.', NULL, NULL, 500000.00, 'fixed', NULL, NULL, NULL, 'medium', 'completed', NULL, '2025-12-01 12:13:22', '2025-12-01 12:19:49'),
(21, 42, 3, 'NovaSync Vendor Intelligence Dashboard', 'This project is a web-based analytics and automation dashboard that plugs into an existing multi-vendor e-commerce platform (like Nova) to give vendors real-time insights into product performance, operational health, and customer behavior. The goal is to centralize metrics from orders, inventory, refunds, delivery SLAs, and marketing events into a single, vendor-facing control panel with role-based access and granular permissions.\r\n\r\nThe system will include a Laravel-based backend API, a modern SPA frontend (React or Vue), and integrations with third-party services such as payment gateways, shipment tracking APIs, and email providers. Key features include configurable KPIs, anomaly alerts (e.g., sudden spike in returns for a specific SKU), seller tier scoring based on green-certified products, and a lightweight recommendation engine that suggests catalog or pricing optimizations.', NULL, NULL, 500000.00, 'fixed', NULL, NULL, NULL, 'medium', 'active', NULL, '2025-12-05 17:17:57', '2025-12-05 17:17:57'),
(22, 43, 3, 'EdgeRelay - Decentralized Content Delivery Network for Small-Scale Creators', 'Building a peer-assisted CDN platform that allows independent content creators, educators, and small media publishers to distribute video, audio, and large files without relying on expensive enterprise CDN services. The system uses a hybrid model: origin servers for reliability plus voluntary edge nodes contributed by supporters who earn micro-rewards (tokens or credits) for bandwidth sharing.\r\n\r\nThe backend needs a node orchestration service (Golang or Rust preferred for performance), a content routing algorithm that selects optimal peers based on geographic proximity and availability, and a blockchain-anchored ledger for transparent bandwidth accounting. The admin panel (Python/Django or Node.js) will handle creator accounts, content uploads, usage analytics, payout calculations, and node health monitoring.\r\n\r\nKey technical challenges: ensuring content integrity via cryptographic checksums, implementing adaptive bitrate streaming for video, handling node churn gracefully, and building a lightweight desktop client for edge participants that runs quietly in the background without hogging resources.', NULL, NULL, 250000.00, 'fixed', NULL, NULL, NULL, 'medium', 'active', NULL, '2025-12-05 17:47:39', '2025-12-05 17:47:39'),
(23, 44, 3, 'QuantumShield - Zero-Trust Security Orchestration Platform', 'Developing an enterprise-grade zero-trust security orchestration platform that dynamically manages access policies across cloud infrastructure, SaaS applications, and on-premises systems using real-time risk scoring and behavioral analytics. The system continuously evaluates device posture, user identity signals, network context, and anomalous activity patterns to grant or revoke access without traditional perimeter-based security models.\r\n\r\nThe backend (Node.js or Go microservices) ingests telemetry from endpoints, identity providers (Okta, Azure AD), SIEM systems, and network sensors. A rules engine correlates signals to compute real-time risk scores; when thresholds are exceeded, the platform automatically triggers conditional access policies (MFA challenges, session termination, geo-fencing). The frontend provides security teams with a real-time threat dashboard, policy builder UI with drag-and-drop conditions, and detailed audit trails for compliance reporting (SOC 2, ISO 27001, HIPAA).\r\n\r\nTechnical components include a message queue (Kafka/RabbitMQ) for high-throughput event streaming, a time-series database (InfluxDB or TimescaleDB) for performance metrics, and integration adapters for AWS IAM, Okta, Slack, PagerDuty, and Splunk.', NULL, NULL, 400000.00, 'fixed', NULL, NULL, NULL, 'medium', 'active', NULL, '2025-12-05 19:25:13', '2025-12-05 19:25:13'),
(24, 45, 3, 'SynthAI - Autonomous Code Review and Technical Debt Analyzer', 'Building an intelligent code review automation platform that integrates directly into Git workflows (GitHub, GitLab, Bitbucket) to analyze pull requests for code quality, security vulnerabilities, performance bottlenecks, and technical debt accumulation. Unlike static linters, SynthAI uses multi-modal AI models to understand architectural patterns, suggest refactoring strategies aligned with team conventions, and predict which modules are becoming maintenance liabilities.\r\n\r\nThe backend (Python FastAPI + PostgreSQL) processes incoming webhook events from version control platforms, clones repositories, performs deep code analysis using custom AST parsers and LLVM-based performance profiling, and stores results in a queryable database. The platform learns from team feedback—when developers accept or reject suggestions, the models retrain to better match the team\'s coding style and priorities. A React/TypeScript frontend displays interactive diff annotations, debt trend charts, and team velocity metrics correlated with code quality scores.\r\n\r\nIntegration points include Slack for notifications, Jira for auto-creating technical debt tickets, and SonarQube/CodeClimate for complementary metrics. The system supports multiple languages (Python, JavaScript, Go, Java, Rust) with pluggable analyzers and custom rule definitions via YAML configuration.', NULL, NULL, 400000.00, 'fixed', NULL, NULL, NULL, 'medium', 'active', NULL, '2025-12-05 19:27:36', '2025-12-05 19:27:36');

-- --------------------------------------------------------

--
-- Table structure for table `project_assignments`
--

CREATE TABLE `project_assignments` (
  `id` bigint UNSIGNED NOT NULL,
  `project_id` bigint UNSIGNED NOT NULL,
  `adiutor_id` bigint UNSIGNED NOT NULL,
  `agreed_rate` decimal(8,2) DEFAULT NULL,
  `max_hours` decimal(8,2) DEFAULT NULL COMMENT 'Maximum billable hours for this adiutor on this project',
  `total_hours_logged` decimal(8,2) NOT NULL DEFAULT '0.00' COMMENT 'Cached total hours logged by this adiutor',
  `total_billable_hours` decimal(8,2) NOT NULL DEFAULT '0.00' COMMENT 'Cached total billable hours (respects max_hours cap)',
  `payment_type` enum('fixed_rate','hourly_rate') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'hourly_rate' COMMENT 'Defines how this adiutor earns: fixed_rate (one payment) or hourly_rate (time-tracked)',
  `fixed_rate_approved` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Admin has approved the fixed rate payment for this adiutor',
  `fixed_rate_approved_at` timestamp NULL DEFAULT NULL COMMENT 'When admin approved the fixed rate payment',
  `fixed_rate_approved_by` bigint UNSIGNED DEFAULT NULL,
  `fixed_rate_paid` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Whether the fixed rate payment has been included in a payout',
  `fixed_rate_payout_id` bigint UNSIGNED DEFAULT NULL,
  `hourly_rate` decimal(8,2) DEFAULT NULL,
  `requires_time_tracking` tinyint(1) NOT NULL DEFAULT '0',
  `total_earnings` decimal(10,2) NOT NULL DEFAULT '0.00',
  `total_hours_worked` decimal(8,2) NOT NULL DEFAULT '0.00',
  `start_date` date DEFAULT NULL,
  `expected_completion` date DEFAULT NULL,
  `status` enum('assigned','active','completed','removed') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'assigned',
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `progress_percentage` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `project_assignments`
--

INSERT INTO `project_assignments` (`id`, `project_id`, `adiutor_id`, `agreed_rate`, `max_hours`, `total_hours_logged`, `total_billable_hours`, `payment_type`, `fixed_rate_approved`, `fixed_rate_approved_at`, `fixed_rate_approved_by`, `fixed_rate_paid`, `fixed_rate_payout_id`, `hourly_rate`, `requires_time_tracking`, `total_earnings`, `total_hours_worked`, `start_date`, `expected_completion`, `status`, `notes`, `progress_percentage`, `created_at`, `updated_at`) VALUES
(1, 1, 7, 75.75, NULL, 0.00, 0.00, 'hourly_rate', 0, NULL, NULL, 0, NULL, NULL, 0, 0.00, 0.00, '2025-10-23', '2025-11-24', 'active', 'Assigned based on skills match and availability', 77, '2025-10-25 06:38:18', '2025-10-25 06:38:18'),
(2, 1, 9, 91.20, NULL, 0.00, 0.00, 'hourly_rate', 0, NULL, NULL, 0, NULL, NULL, 0, 0.00, 0.00, '2025-10-23', '2025-11-24', 'active', 'Assigned based on skills match and availability', 80, '2025-10-25 06:38:18', '2025-10-25 06:38:18'),
(3, 2, 7, 81.00, NULL, 0.00, 0.00, 'hourly_rate', 0, NULL, NULL, 0, NULL, NULL, 0, 0.00, 0.00, '2025-10-20', '2025-12-09', 'active', 'Assigned based on skills match and availability', 56, '2025-10-25 06:38:18', '2025-10-25 06:38:18'),
(4, 2, 8, 70.20, NULL, 0.00, 0.00, 'hourly_rate', 0, NULL, NULL, 0, NULL, NULL, 0, 0.00, 0.00, '2025-10-20', '2025-12-09', 'active', 'Assigned based on skills match and availability', 81, '2025-10-25 06:38:18', '2025-10-25 06:38:18'),
(5, 2, 10, 61.80, NULL, 0.00, 0.00, 'hourly_rate', 0, NULL, NULL, 0, NULL, NULL, 0, 0.00, 0.00, '2025-10-20', '2025-12-09', 'active', 'Assigned based on skills match and availability', 13, '2025-10-25 06:38:18', '2025-10-25 06:38:18'),
(6, 3, 7, 78.75, NULL, 0.00, 0.00, 'hourly_rate', 0, NULL, NULL, 0, NULL, NULL, 0, 0.00, 0.00, '2025-10-20', '2026-01-23', 'active', 'Assigned based on skills match and availability', 21, '2025-10-25 06:38:18', '2025-10-25 06:38:18'),
(7, 3, 10, 54.60, NULL, 0.00, 0.00, 'hourly_rate', 0, NULL, NULL, 0, NULL, NULL, 0, 0.00, 0.00, '2025-10-20', '2026-01-23', 'active', 'Assigned based on skills match and availability', 95, '2025-10-25 06:38:18', '2025-10-25 06:38:18'),
(8, 4, 9, 74.40, NULL, 0.00, 0.00, 'hourly_rate', 0, NULL, NULL, 0, NULL, NULL, 0, 0.00, 0.00, '2025-10-23', '2025-11-24', 'active', 'Assigned based on skills match and availability', 12, '2025-10-25 06:38:18', '2025-10-25 06:38:18'),
(9, 4, 11, 73.50, NULL, 0.00, 0.00, 'hourly_rate', 0, NULL, NULL, 0, NULL, NULL, 0, 0.00, 0.00, '2025-10-23', '2025-11-24', 'active', 'Assigned based on skills match and availability', 61, '2025-10-25 06:38:18', '2025-10-25 06:38:18'),
(10, 5, 7, 1000.00, NULL, 0.00, 0.00, 'hourly_rate', 0, NULL, NULL, 0, NULL, NULL, 0, 0.00, 0.00, '2025-10-25', NULL, 'assigned', NULL, 0, '2025-10-25 06:49:03', '2025-10-25 06:49:03'),
(11, 5, 8, NULL, NULL, 0.00, 0.00, 'hourly_rate', 0, NULL, NULL, 0, NULL, NULL, 0, 0.00, 0.00, '2025-10-25', NULL, 'assigned', NULL, 0, '2025-10-25 06:49:11', '2025-10-25 06:49:11'),
(12, 6, 7, 5000.00, NULL, 0.00, 0.00, 'hourly_rate', 0, NULL, NULL, 0, NULL, NULL, 0, 0.00, 0.00, '2025-10-28', NULL, 'assigned', NULL, 0, '2025-10-28 23:59:50', '2025-10-28 23:59:50'),
(13, 6, 8, 1000.00, NULL, 0.00, 0.00, 'hourly_rate', 0, NULL, NULL, 0, NULL, NULL, 0, 0.00, 0.00, '2025-10-29', NULL, 'assigned', NULL, 0, '2025-10-29 00:00:10', '2025-10-29 00:00:10'),
(14, 6, 9, 3000.00, NULL, 0.00, 0.00, 'hourly_rate', 0, NULL, NULL, 0, NULL, NULL, 0, 0.00, 0.00, '2025-10-29', NULL, 'assigned', NULL, 0, '2025-10-29 00:00:21', '2025-10-29 00:00:21'),
(15, 7, 7, 1000.00, NULL, 0.00, 0.00, 'hourly_rate', 0, NULL, NULL, 0, NULL, NULL, 0, 0.00, 0.00, '2025-10-29', NULL, 'assigned', NULL, 0, '2025-10-29 08:48:08', '2025-10-29 08:48:08'),
(16, 13, 8, 5000.00, NULL, 0.00, 0.00, 'hourly_rate', 0, NULL, NULL, 0, NULL, NULL, 0, 0.00, 0.00, '2025-11-10', '2025-11-19', 'assigned', NULL, 0, '2025-11-10 12:22:09', '2025-11-10 12:22:09'),
(17, 18, 34, 5000.00, NULL, 0.00, 0.00, 'hourly_rate', 0, NULL, NULL, 0, NULL, 300.00, 0, 0.00, 0.00, '2025-11-16', '2025-11-20', 'removed', NULL, 0, '2025-11-16 11:08:26', '2025-12-01 18:13:03'),
(18, 18, 8, NULL, NULL, 0.00, 0.00, 'hourly_rate', 0, NULL, NULL, 0, NULL, 65.00, 0, 0.00, 0.00, '2025-12-01', '2025-12-06', 'assigned', NULL, 0, '2025-11-16 11:15:48', '2025-12-01 18:16:28'),
(19, 16, 34, 5000.00, NULL, 0.00, 0.00, 'hourly_rate', 0, NULL, NULL, 0, NULL, 500.00, 0, 0.00, 0.00, '2025-11-17', '2025-12-20', 'assigned', NULL, 0, '2025-11-17 10:25:40', '2025-11-17 10:25:40'),
(20, 17, 34, 5000.00, NULL, 0.00, 0.00, 'hourly_rate', 0, NULL, NULL, 0, NULL, NULL, 0, 0.00, 0.00, '2025-11-18', '2025-11-30', 'assigned', NULL, 0, '2025-11-18 14:43:00', '2025-11-18 14:43:00'),
(21, 20, 10, 500.00, NULL, 0.00, 0.00, 'hourly_rate', 0, NULL, NULL, 0, NULL, 400.00, 0, 0.00, 0.00, '2025-12-01', NULL, 'assigned', NULL, 0, '2025-12-01 12:17:42', '2025-12-01 12:17:42'),
(22, 20, 8, 5000.00, NULL, 0.00, 0.00, 'hourly_rate', 0, NULL, NULL, 0, NULL, 65.00, 0, 0.00, 0.00, '2025-12-01', NULL, 'assigned', NULL, 0, '2025-12-01 12:17:56', '2025-12-01 12:17:56'),
(23, 19, 34, 5000.00, NULL, 0.00, 0.00, 'hourly_rate', 0, NULL, NULL, 0, NULL, 400.00, 0, 0.00, 0.00, '2025-12-01', '2026-11-25', 'assigned', NULL, 0, '2025-12-01 12:50:22', '2025-12-01 12:50:22'),
(24, 19, 7, 5000.00, NULL, 0.00, 0.00, 'hourly_rate', 0, NULL, NULL, 0, NULL, 500.00, 0, 0.00, 0.00, '2025-12-01', '2026-11-25', 'assigned', NULL, 0, '2025-12-01 12:50:35', '2025-12-01 12:50:35'),
(25, 12, 8, NULL, NULL, 0.00, 0.00, 'hourly_rate', 0, NULL, NULL, 0, NULL, 65.00, 0, 0.00, 0.00, '2025-12-01', NULL, 'assigned', NULL, 0, '2025-12-01 18:18:19', '2025-12-01 18:21:44'),
(26, 18, 7, 150000.00, NULL, 0.00, 0.00, 'fixed_rate', 1, '2025-12-05 09:05:27', 1, 0, NULL, NULL, 0, 0.00, 0.00, '2025-12-02', '2025-12-06', 'assigned', NULL, 0, '2025-12-02 11:45:17', '2025-12-05 09:05:27'),
(27, 24, 7, NULL, 150.00, 1.58, 1.57, 'hourly_rate', 0, NULL, NULL, 0, NULL, 500.00, 1, 0.00, 0.00, '2025-12-06', NULL, 'assigned', NULL, 80, '2025-12-06 06:09:17', '2025-12-06 10:31:44'),
(28, 24, 8, 50000.00, NULL, 0.00, 0.00, 'fixed_rate', 1, '2025-12-06 08:40:48', 1, 0, NULL, NULL, 0, 0.00, 0.00, '2025-12-06', NULL, 'assigned', NULL, 80, '2025-12-06 06:10:36', '2025-12-06 10:31:44');

-- --------------------------------------------------------

--
-- Table structure for table `project_feedback`
--

CREATE TABLE `project_feedback` (
  `id` bigint UNSIGNED NOT NULL,
  `project_id` bigint UNSIGNED NOT NULL,
  `client_id` bigint UNSIGNED NOT NULL,
  `adiutor_id` bigint UNSIGNED DEFAULT NULL,
  `rating` int DEFAULT NULL,
  `feedback` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `feedback_type` enum('project','adiutor','overall') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'project',
  `is_public` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `project_milestones`
--

CREATE TABLE `project_milestones` (
  `id` bigint UNSIGNED NOT NULL,
  `project_id` bigint UNSIGNED NOT NULL,
  `phase_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `phase_description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `phase_order` int NOT NULL DEFAULT '1',
  `milestone_number` int NOT NULL DEFAULT '1',
  `percentage` decimal(5,2) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `start_date` date DEFAULT NULL,
  `due_date` date DEFAULT NULL,
  `completed_date` date DEFAULT NULL,
  `status` enum('pending','in_progress','completed','paid') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `is_paid` tinyint(1) NOT NULL DEFAULT '0',
  `paid_at` timestamp NULL DEFAULT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `deliverables` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `project_milestones`
--

INSERT INTO `project_milestones` (`id`, `project_id`, `phase_name`, `phase_description`, `phase_order`, `milestone_number`, `percentage`, `amount`, `start_date`, `due_date`, `completed_date`, `status`, `is_paid`, `paid_at`, `notes`, `deliverables`, `created_at`, `updated_at`) VALUES
(1, 5, 'Phase 1', NULL, 1, 1, 30.00, 150000.00, NULL, NULL, NULL, 'pending', 1, '2025-10-25 06:46:54', NULL, NULL, '2025-10-25 06:45:43', '2025-10-25 06:46:54'),
(2, 5, 'Phase 2', NULL, 2, 2, 40.00, 200000.00, NULL, NULL, NULL, 'pending', 1, '2025-10-25 07:07:04', NULL, NULL, '2025-10-25 06:45:43', '2025-10-25 07:07:04'),
(3, 5, 'Phase 3', NULL, 3, 3, 30.00, 150000.00, NULL, NULL, NULL, 'pending', 1, '2025-10-25 07:08:11', NULL, NULL, '2025-10-25 06:45:43', '2025-10-25 07:08:11'),
(4, 6, 'Phase 1', NULL, 1, 1, 30.00, 150000.00, NULL, NULL, NULL, 'pending', 1, '2025-10-28 23:51:30', NULL, NULL, '2025-10-28 23:48:36', '2025-10-28 23:51:30'),
(5, 6, 'Phase 2', NULL, 2, 2, 40.00, 200000.00, NULL, NULL, NULL, 'pending', 1, '2025-10-28 23:52:58', NULL, NULL, '2025-10-28 23:48:36', '2025-10-28 23:52:58'),
(6, 6, 'Phase 3', NULL, 3, 3, 30.00, 150000.00, NULL, NULL, NULL, 'pending', 1, '2025-10-28 23:55:11', NULL, NULL, '2025-10-28 23:48:36', '2025-10-28 23:55:11'),
(7, 7, 'Phase 1', NULL, 1, 1, 30.00, 150000.00, NULL, NULL, NULL, 'pending', 1, '2025-10-29 08:44:31', NULL, NULL, '2025-10-29 08:40:49', '2025-10-29 08:44:31'),
(8, 7, 'Phase 2', NULL, 2, 2, 40.00, 200000.00, NULL, NULL, NULL, 'pending', 0, NULL, NULL, NULL, '2025-10-29 08:40:49', '2025-10-29 08:40:49'),
(9, 7, 'Phase 3', NULL, 3, 3, 30.00, 150000.00, NULL, NULL, NULL, 'pending', 0, NULL, NULL, NULL, '2025-10-29 08:40:49', '2025-10-29 08:40:49'),
(10, 8, 'Phase 1', NULL, 1, 1, 30.00, 150000.00, NULL, NULL, NULL, 'pending', 1, '2025-10-29 14:34:32', NULL, NULL, '2025-10-29 14:31:47', '2025-10-29 14:34:32'),
(11, 8, 'Phase 2', NULL, 2, 2, 40.00, 200000.00, NULL, NULL, NULL, 'pending', 0, NULL, NULL, NULL, '2025-10-29 14:31:47', '2025-10-29 14:31:47'),
(12, 8, 'Phase 3', NULL, 3, 3, 30.00, 150000.00, NULL, NULL, NULL, 'pending', 0, NULL, NULL, NULL, '2025-10-29 14:31:47', '2025-10-29 14:31:47');

-- --------------------------------------------------------

--
-- Table structure for table `project_templates`
--

CREATE TABLE `project_templates` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `category` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'general',
  `default_tasks` json DEFAULT NULL,
  `skills_required` json DEFAULT NULL,
  `milestones_template` json DEFAULT NULL,
  `estimated_budget_min` decimal(10,2) DEFAULT NULL,
  `estimated_budget_max` decimal(10,2) DEFAULT NULL,
  `estimated_duration_days` int DEFAULT NULL,
  `budget_type` enum('fixed','hourly') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'fixed',
  `payment_type` enum('full_payment','milestone_payment','downpayment') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'milestone_payment',
  `requirements_template` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `project_templates`
--

INSERT INTO `project_templates` (`id`, `name`, `description`, `category`, `default_tasks`, `skills_required`, `milestones_template`, `estimated_budget_min`, `estimated_budget_max`, `estimated_duration_days`, `budget_type`, `payment_type`, `requirements_template`, `is_active`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 'E-commerce Website', 'Complete e-commerce solution with payment integration, product catalog, and admin panel', 'web-development', '[{\"title\": \"Requirements Analysis & Planning\", \"priority\": \"high\", \"description\": \"Gather requirements, create wireframes and technical specifications\", \"estimated_hours\": 16}, {\"title\": \"UI/UX Design\", \"priority\": \"high\", \"description\": \"Design product pages, checkout flow, and admin dashboard\", \"estimated_hours\": 24}, {\"title\": \"Frontend Development\", \"priority\": \"high\", \"description\": \"Implement responsive design with React/Vue or vanilla JS\", \"estimated_hours\": 40}, {\"title\": \"Backend Development\", \"priority\": \"high\", \"description\": \"API development, database design, user authentication\", \"estimated_hours\": 48}, {\"title\": \"Payment Integration\", \"priority\": \"medium\", \"description\": \"Integrate payment gateways (Stripe, PayPal, etc.)\", \"estimated_hours\": 16}, {\"title\": \"Testing & QA\", \"priority\": \"medium\", \"description\": \"Unit testing, integration testing, user acceptance testing\", \"estimated_hours\": 20}, {\"title\": \"Deployment & Launch\", \"priority\": \"medium\", \"description\": \"Server setup, SSL configuration, go-live\", \"estimated_hours\": 8}]', '[\"PHP\", \"Laravel\", \"JavaScript\", \"HTML/CSS\", \"MySQL\", \"Payment Gateways\"]', '[{\"percentage\": 20, \"phase_name\": \"Design & Planning\", \"description\": \"Requirements, wireframes, and design completion\"}, {\"percentage\": 30, \"phase_name\": \"Frontend Development\", \"description\": \"User interface implementation\"}, {\"percentage\": 35, \"phase_name\": \"Backend Development\", \"description\": \"API and database implementation\"}, {\"percentage\": 15, \"phase_name\": \"Testing & Launch\", \"description\": \"QA testing and deployment\"}]', 5000.00, 12000.00, 45, 'fixed', 'milestone_payment', 'Please provide: Product catalog requirements, Payment methods needed, Shipping requirements, Admin panel features, Design preferences, Target audience information', 1, NULL, '2025-10-29 18:01:45', '2025-10-29 18:01:45'),
(2, 'Mobile App Development (iOS/Android)', 'Cross-platform mobile application with native features and backend integration', 'mobile-development', '[{\"title\": \"App Concept & Strategy\", \"priority\": \"high\", \"description\": \"Define app goals, target audience, and feature set\", \"estimated_hours\": 12}, {\"title\": \"UI/UX Design\", \"priority\": \"high\", \"description\": \"Create app wireframes, user flow, and visual design\", \"estimated_hours\": 32}, {\"title\": \"Backend API Development\", \"priority\": \"high\", \"description\": \"Build RESTful APIs and database architecture\", \"estimated_hours\": 40}, {\"title\": \"Frontend App Development\", \"priority\": \"high\", \"description\": \"Develop React Native or Flutter app\", \"estimated_hours\": 60}, {\"title\": \"Native Features Integration\", \"priority\": \"medium\", \"description\": \"Camera, GPS, push notifications, device APIs\", \"estimated_hours\": 20}, {\"title\": \"Testing & Optimization\", \"priority\": \"medium\", \"description\": \"Device testing, performance optimization, bug fixes\", \"estimated_hours\": 24}, {\"title\": \"App Store Deployment\", \"priority\": \"medium\", \"description\": \"App store submission and approval process\", \"estimated_hours\": 8}]', '[\"React Native\", \"Flutter\", \"Node.js\", \"API Development\", \"Mobile UI/UX\", \"App Store Optimization\"]', '[{\"percentage\": 25, \"phase_name\": \"Design & Planning\", \"description\": \"App concept, wireframes, and design\"}, {\"percentage\": 30, \"phase_name\": \"Backend Development\", \"description\": \"API and database setup\"}, {\"percentage\": 35, \"phase_name\": \"Mobile App Development\", \"description\": \"App development and native features\"}, {\"percentage\": 10, \"phase_name\": \"Testing & Launch\", \"description\": \"Testing and app store submission\"}]', 8000.00, 25000.00, 60, 'fixed', 'milestone_payment', 'Please provide: App concept and goals, Target platforms (iOS/Android), Required features, Design preferences, Third-party integrations needed, Expected user base', 1, NULL, '2025-10-29 18:01:46', '2025-10-29 18:01:46'),
(3, 'Brand Identity & Logo Design', 'Complete brand identity package including logo, color palette, and brand guidelines', 'design', '[{\"title\": \"Brand Discovery & Research\", \"priority\": \"high\", \"description\": \"Understand brand values, target audience, and competitors\", \"estimated_hours\": 8}, {\"title\": \"Concept Development\", \"priority\": \"high\", \"description\": \"Create initial logo concepts and mood boards\", \"estimated_hours\": 16}, {\"title\": \"Logo Design Refinement\", \"priority\": \"high\", \"description\": \"Refine selected concepts based on feedback\", \"estimated_hours\": 12}, {\"title\": \"Brand Guidelines Creation\", \"priority\": \"medium\", \"description\": \"Color palette, typography, usage guidelines\", \"estimated_hours\": 8}, {\"title\": \"Brand Collateral Design\", \"priority\": \"medium\", \"description\": \"Business cards, letterhead, social media templates\", \"estimated_hours\": 12}, {\"title\": \"File Preparation & Delivery\", \"priority\": \"low\", \"description\": \"Prepare all file formats and organize final deliverables\", \"estimated_hours\": 4}]', '[\"Graphic Design\", \"Adobe Creative Suite\", \"Brand Strategy\", \"Typography\", \"Color Theory\"]', '[{\"percentage\": 40, \"phase_name\": \"Discovery & Concepts\", \"description\": \"Research and initial concepts\"}, {\"percentage\": 30, \"phase_name\": \"Logo Refinement\", \"description\": \"Logo finalization\"}, {\"percentage\": 20, \"phase_name\": \"Brand Guidelines\", \"description\": \"Complete brand identity system\"}, {\"percentage\": 10, \"phase_name\": \"Final Delivery\", \"description\": \"All files and documentation\"}]', 1500.00, 5000.00, 14, 'fixed', 'milestone_payment', 'Please provide: Company/brand information, Target audience, Brand values and personality, Design preferences, Competitor examples, Intended use cases for logo', 1, NULL, '2025-10-29 18:01:46', '2025-10-29 18:01:46'),
(4, 'WordPress Website', 'Custom WordPress website with theme development and content management', 'web-development', '[{\"title\": \"Requirements & Planning\", \"priority\": \"high\", \"description\": \"Site structure, features, and content planning\", \"estimated_hours\": 8}, {\"title\": \"Design & Wireframing\", \"priority\": \"high\", \"description\": \"Custom theme design and page layouts\", \"estimated_hours\": 20}, {\"title\": \"WordPress Setup\", \"priority\": \"high\", \"description\": \"WordPress installation, hosting setup, security\", \"estimated_hours\": 4}, {\"title\": \"Theme Development\", \"priority\": \"high\", \"description\": \"Custom WordPress theme development\", \"estimated_hours\": 32}, {\"title\": \"Plugin Integration\", \"priority\": \"medium\", \"description\": \"Install and configure necessary plugins\", \"estimated_hours\": 8}, {\"title\": \"Content Migration\", \"priority\": \"medium\", \"description\": \"Content entry and SEO optimization\", \"estimated_hours\": 12}, {\"title\": \"Testing & Launch\", \"priority\": \"medium\", \"description\": \"Cross-browser testing and go-live\", \"estimated_hours\": 6}]', '[\"WordPress\", \"PHP\", \"HTML/CSS\", \"JavaScript\", \"SEO\", \"Responsive Design\"]', '[{\"percentage\": 30, \"phase_name\": \"Design & Setup\", \"description\": \"Design approval and WordPress setup\"}, {\"percentage\": 50, \"phase_name\": \"Theme Development\", \"description\": \"Custom theme implementation\"}, {\"percentage\": 20, \"phase_name\": \"Content & Launch\", \"description\": \"Content migration and launch\"}]', 2000.00, 6000.00, 21, 'fixed', 'milestone_payment', 'Please provide: Site purpose and goals, Number of pages needed, Design references, Content (text and images), Required plugins/features, Hosting preferences', 1, NULL, '2025-10-29 18:01:46', '2025-10-29 18:01:46'),
(5, 'SEO Optimization Package', 'Comprehensive SEO audit and optimization for improved search rankings', 'marketing', '[{\"title\": \"SEO Audit & Analysis\", \"priority\": \"high\", \"description\": \"Complete technical and content SEO audit\", \"estimated_hours\": 12}, {\"title\": \"Keyword Research\", \"priority\": \"high\", \"description\": \"Target keyword identification and competition analysis\", \"estimated_hours\": 8}, {\"title\": \"On-Page Optimization\", \"priority\": \"high\", \"description\": \"Meta tags, headers, content optimization\", \"estimated_hours\": 16}, {\"title\": \"Technical SEO Fixes\", \"priority\": \"medium\", \"description\": \"Site speed, mobile optimization, schema markup\", \"estimated_hours\": 12}, {\"title\": \"Content Strategy\", \"priority\": \"medium\", \"description\": \"Content calendar and optimization recommendations\", \"estimated_hours\": 8}, {\"title\": \"Reporting & Monitoring\", \"priority\": \"low\", \"description\": \"Setup analytics and monthly reporting\", \"estimated_hours\": 4}]', '[\"SEO\", \"Google Analytics\", \"Technical SEO\", \"Content Marketing\", \"Keyword Research\"]', '[{\"percentage\": 35, \"phase_name\": \"Audit & Strategy\", \"description\": \"SEO audit and keyword research\"}, {\"percentage\": 50, \"phase_name\": \"Implementation\", \"description\": \"On-page and technical optimizations\"}, {\"percentage\": 15, \"phase_name\": \"Monitoring Setup\", \"description\": \"Analytics and reporting setup\"}]', 1000.00, 3000.00, 30, 'fixed', 'downpayment', 'Please provide: Website URL, Target keywords (if known), Current analytics access, Business goals, Target audience, Geographic targeting needs', 1, NULL, '2025-10-29 18:01:46', '2025-10-29 18:01:46'),
(6, 'Content Management System', 'Custom CMS development for content-heavy websites and applications', 'web-development', '[{\"title\": \"Requirements Analysis\", \"priority\": \"high\", \"description\": \"Content types, user roles, workflow requirements\", \"estimated_hours\": 12}, {\"title\": \"Database Design\", \"priority\": \"high\", \"description\": \"Content structure, relationships, and optimization\", \"estimated_hours\": 16}, {\"title\": \"Admin Panel Development\", \"priority\": \"high\", \"description\": \"Content management interface and user controls\", \"estimated_hours\": 36}, {\"title\": \"Frontend Integration\", \"priority\": \"high\", \"description\": \"Public-facing content display and templates\", \"estimated_hours\": 28}, {\"title\": \"User Management System\", \"priority\": \"medium\", \"description\": \"Authentication, authorization, role management\", \"estimated_hours\": 20}, {\"title\": \"API Development\", \"priority\": \"medium\", \"description\": \"RESTful APIs for content access and management\", \"estimated_hours\": 16}, {\"title\": \"Testing & Documentation\", \"priority\": \"medium\", \"description\": \"User testing, documentation, and training\", \"estimated_hours\": 12}]', '[\"PHP\", \"Laravel\", \"MySQL\", \"JavaScript\", \"API Development\", \"System Architecture\"]', '[{\"percentage\": 25, \"phase_name\": \"Planning & Database\", \"description\": \"Requirements and database design\"}, {\"percentage\": 40, \"phase_name\": \"Admin Development\", \"description\": \"CMS admin panel creation\"}, {\"percentage\": 25, \"phase_name\": \"Frontend & API\", \"description\": \"Public interface and API\"}, {\"percentage\": 10, \"phase_name\": \"Testing & Launch\", \"description\": \"Testing and deployment\"}]', 6000.00, 15000.00, 50, 'fixed', 'milestone_payment', 'Please provide: Content types needed, User roles and permissions, Workflow requirements, Integration needs, Performance requirements, Hosting environment', 1, NULL, '2025-10-29 18:01:47', '2025-10-29 18:01:47'),
(13, 'Social Media Marketing Campaign', 'Comprehensive social media strategy and content creation for brand growth', 'marketing', '[{\"title\": \"Brand Analysis & Strategy\", \"priority\": \"high\", \"description\": \"Brand positioning, competitor analysis, goal setting\", \"estimated_hours\": 10}, {\"title\": \"Content Calendar Planning\", \"priority\": \"high\", \"description\": \"Monthly content strategy and posting schedule\", \"estimated_hours\": 12}, {\"title\": \"Visual Content Creation\", \"priority\": \"high\", \"description\": \"Graphics, videos, and branded content assets\", \"estimated_hours\": 24}, {\"title\": \"Copywriting & Content\", \"priority\": \"medium\", \"description\": \"Engaging captions, blog posts, and marketing copy\", \"estimated_hours\": 16}, {\"title\": \"Community Management\", \"priority\": \"medium\", \"description\": \"Daily posting, engagement, and community building\", \"estimated_hours\": 30}, {\"title\": \"Analytics & Reporting\", \"priority\": \"medium\", \"description\": \"Performance tracking and monthly reports\", \"estimated_hours\": 8}]', '[\"Social Media Marketing\", \"Content Creation\", \"Graphic Design\", \"Copywriting\", \"Analytics\"]', '[{\"percentage\": 20, \"phase_name\": \"Strategy & Planning\", \"description\": \"Social media strategy development\"}, {\"percentage\": 40, \"phase_name\": \"Content Creation\", \"description\": \"Visual and written content\"}, {\"percentage\": 30, \"phase_name\": \"Campaign Launch\", \"description\": \"Active campaign management\"}, {\"percentage\": 10, \"phase_name\": \"Optimization\", \"description\": \"Performance analysis and optimization\"}]', 2000.00, 5000.00, 30, 'hourly', 'milestone_payment', 'Please provide: Brand information, Target audience, Current social presence, Marketing goals, Budget for ads (if any), Preferred platforms', 1, NULL, '2025-10-29 18:03:21', '2025-10-29 18:03:21');

-- --------------------------------------------------------

--
-- Table structure for table `referrals`
--

CREATE TABLE `referrals` (
  `id` bigint UNSIGNED NOT NULL,
  `referrer_id` bigint UNSIGNED NOT NULL,
  `referred_id` bigint UNSIGNED NOT NULL,
  `referral_code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('pending','completed','rewarded') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `referrer_points_pending` int NOT NULL DEFAULT '0',
  `referrer_points_earned` int NOT NULL DEFAULT '0',
  `referrer_coupon_id` bigint UNSIGNED DEFAULT NULL,
  `referrer_benefit_type` enum('coupon','credits') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'credits',
  `referrer_credits_earned` decimal(10,2) NOT NULL DEFAULT '0.00',
  `referrer_discount_percentage` decimal(5,2) NOT NULL DEFAULT '0.00',
  `referred_points_earned` int NOT NULL DEFAULT '0',
  `referred_coupon_id` bigint UNSIGNED DEFAULT NULL,
  `referred_benefit_type` enum('coupon','credits') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'coupon',
  `referred_credits_earned` decimal(10,2) NOT NULL DEFAULT '0.00',
  `referred_discount_percentage` decimal(5,2) NOT NULL DEFAULT '0.00',
  `first_payment_id` bigint UNSIGNED DEFAULT NULL,
  `qualifying_payment_amount` decimal(10,2) DEFAULT NULL,
  `completed_at` timestamp NULL DEFAULT NULL,
  `rewarded_at` timestamp NULL DEFAULT NULL,
  `ip_address` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `metadata` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `referral_campaigns`
--

CREATE TABLE `referral_campaigns` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `points_multiplier` double NOT NULL DEFAULT '1',
  `bonus_points_referrer` int NOT NULL DEFAULT '0',
  `bonus_points_referred` int NOT NULL DEFAULT '0',
  `auto_generate_coupon` tinyint(1) NOT NULL DEFAULT '0',
  `coupon_prefix` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `coupon_discount_type` enum('percentage','fixed_amount') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `coupon_discount_value` decimal(10,2) DEFAULT NULL,
  `starts_at` timestamp NULL DEFAULT NULL,
  `ends_at` timestamp NULL DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `max_referrals` int DEFAULT NULL,
  `max_per_user` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `referral_codes`
--

CREATE TABLE `referral_codes` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `code` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_referrals` int NOT NULL DEFAULT '0',
  `pending_referrals` int NOT NULL DEFAULT '0',
  `successful_referrals` int NOT NULL DEFAULT '0',
  `lifetime_earnings_points` int NOT NULL DEFAULT '0',
  `last_used_at` timestamp NULL DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `referral_codes`
--

INSERT INTO `referral_codes` (`id`, `user_id`, `code`, `total_referrals`, `pending_referrals`, `successful_referrals`, `lifetime_earnings_points`, `last_used_at`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 33, 'MARK202501D', 0, 0, 0, 0, NULL, 1, '2025-11-14 10:38:45', '2025-11-14 10:38:45'),
(2, 18, 'NISS2025282', 0, 0, 0, 0, NULL, 1, '2025-12-01 15:02:52', '2025-12-01 15:02:52'),
(3, 14, 'MARK2025F1C', 0, 0, 0, 0, NULL, 1, '2025-12-03 20:24:49', '2025-12-03 20:24:49');

-- --------------------------------------------------------

--
-- Table structure for table `referral_credit_transactions`
--

CREATE TABLE `referral_credit_transactions` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `transaction_type` enum('earned','withdrawn','refunded','adjusted') COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `balance_before` decimal(10,2) NOT NULL,
  `balance_after` decimal(10,2) NOT NULL,
  `source` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `referral_id` bigint UNSIGNED DEFAULT NULL,
  `withdrawal_id` bigint UNSIGNED DEFAULT NULL,
  `performed_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `referral_credit_withdrawals`
--

CREATE TABLE `referral_credit_withdrawals` (
  `id` bigint UNSIGNED NOT NULL,
  `withdrawal_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `status` enum('pending','processing','completed','rejected','cancelled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `withdrawal_method` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `withdrawal_details` json DEFAULT NULL,
  `processed_by` bigint UNSIGNED DEFAULT NULL,
  `requested_at` timestamp NULL DEFAULT NULL,
  `processed_at` timestamp NULL DEFAULT NULL,
  `completed_at` timestamp NULL DEFAULT NULL,
  `reference_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `proof_of_payment` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_notes` text COLLATE utf8mb4_unicode_ci,
  `admin_notes` text COLLATE utf8mb4_unicode_ci,
  `rejection_reason` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `request_attachments`
--

CREATE TABLE `request_attachments` (
  `id` bigint UNSIGNED NOT NULL,
  `service_request_id` bigint UNSIGNED NOT NULL,
  `original_filename` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `stored_filename` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_url` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mime_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_size` bigint NOT NULL,
  `file_hash` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `revision_requests`
--

CREATE TABLE `revision_requests` (
  `id` bigint UNSIGNED NOT NULL,
  `document_id` bigint UNSIGNED DEFAULT NULL,
  `requested_by` bigint UNSIGNED NOT NULL,
  `reason` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `requested_due_date` date DEFAULT NULL,
  `revision_number` int NOT NULL DEFAULT '1',
  `status` enum('pending','approved','rejected','completed','cancelled') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `priority` enum('normal','high','urgent') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'normal',
  `allows_new_tasks` tinyint(1) NOT NULL DEFAULT '0',
  `reopened_task_ids` json DEFAULT NULL,
  `reviewed_by` bigint UNSIGNED DEFAULT NULL,
  `admin_notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `reviewed_at` timestamp NULL DEFAULT NULL,
  `task_id` bigint UNSIGNED DEFAULT NULL,
  `service_request_id` bigint UNSIGNED DEFAULT NULL,
  `project_id` bigint UNSIGNED DEFAULT NULL,
  `source_type` enum('task','project') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `assigned_adiutor_id` bigint UNSIGNED DEFAULT NULL,
  `completed_at` timestamp NULL DEFAULT NULL,
  `completed_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `revision_requests`
--

INSERT INTO `revision_requests` (`id`, `document_id`, `requested_by`, `reason`, `requested_due_date`, `revision_number`, `status`, `priority`, `allows_new_tasks`, `reopened_task_ids`, `reviewed_by`, `admin_notes`, `reviewed_at`, `task_id`, `service_request_id`, `project_id`, `source_type`, `assigned_adiutor_id`, `completed_at`, `completed_by`, `created_at`, `updated_at`) VALUES
(1, NULL, 12, 'asdcmkasdcmaklsmcklasmcklmaskcmasklcmkalcmkasmc', NULL, 1, 'pending', 'normal', 0, NULL, NULL, NULL, NULL, NULL, 11, 5, 'project', NULL, NULL, NULL, '2025-10-25 07:40:32', '2025-10-25 07:40:32'),
(4, NULL, 3, '[2025-11-14 12:17:24] development.INFO: Revision request received {\"project_id\":\"13\",\"user_id\":3,\"request_data\":{\"_token\":\"U7DOmPf8aqXcW2NeLCoCYMnNboTsD9io9kvMu6e1\",\"revision_scope\":\"project\",\"reason\":\"FAFASFASMMMMMMMMMMMMMM\",\"requested_due_date\":\"2025-11-30\",\"priority\":\"normal\"}} \r\n[2025-11-14 12:17:24] development.INFO: Validation passed {\"validated_data\":{\"revision_scope\":\"project\",\"reason\":\"FAFASFASMMMMMMMMMMMMMM\",\"requested_due_date\":\"2025-11-30\",\"priority\":\"normal\"}} \r\n[2025-11-14 12:17:25] development.INFO: Mail instance created {\"mail_class\":\"App\\\\Mail\\\\RevisionRequestSubmitted\",\"sender_type\":\"projects\",\"queue\":\"default\"} \r\n[2025-11-14 12:17:25] development.INFO: Mail built successfully {\"mail_class\":\"App\\\\Mail\\\\RevisionRequestSubmitted\",\"sender_type\":\"projects\",\"to\":[],\"cc\":[],\"bcc\":[],\"subject\":null} \r\n[2025-11-14 12:17:25] development.INFO: Mail envelope created successfully {\"mail_class\":\"App\\\\Mail\\\\RevisionRequestSubmitted\",\"sender_type\":\"projects\",\"from_address\":\"projects@treisadiutor.com\",\"from_name\":\"Treis Adiutor - Projects\",\"subject\":\"New Revision Request - Project: Website SEO Optimization\"} \r\n[2025-11-14 12:17:26] development.ERROR: Failed to create project revision request {\"project_id\":\"13\",\"error\":\"An email must have a \\\"To\\\", \\\"Cc\\\", or \\\"Bcc\\\" header.\",\"trace\":\"#0 C:\\\\Users\\\\marka\\\\Projects\\\\cms\\\\vendor\\\\symfony\\\\mime\\\\Email.php(399): Symfony\\\\Component\\\\Mime\\\\Message->ensureValidity()', '2025-11-30', 1, 'rejected', 'normal', 0, NULL, 1, '[2025-11-14 12:24:29] development.ERROR: Failed to approve revision request {\"revision_id\":\"4\",\"error\":\"SQLSTATE[42S22]: Column not found: 1054 Unknown column \'projectID\' in \'where clause\' (Connection: azure_mysql, SQL: select * from `tasks` where `projectID` = 13 and `status` = completed)\"} \r\n[2025-11-14 13:26:45] development.ERROR: Failed to reject revision request {\"revision_id\":\"4\",\"error\":\"Attempt to read property \\\"fileName\\\" on null\"}', '2025-11-14 13:29:40', NULL, 9, 13, 'project', NULL, NULL, NULL, '2025-11-14 12:22:49', '2025-11-14 13:29:40'),
(5, NULL, 1, 'Need revisions on some tasks', NULL, 1, 'approved', 'normal', 1, '[\"1\"]', 1, NULL, '2025-12-01 12:57:06', NULL, NULL, 1, 'project', 7, NULL, NULL, '2025-12-01 04:55:48', '2025-12-01 12:57:06'),
(6, NULL, 18, 'The design doesn\'t match the approved mockups. Need adjustments to color scheme.', '2025-12-03', 1, 'pending', 'high', 0, NULL, NULL, NULL, NULL, 22, 40, 19, 'task', 7, NULL, NULL, '2025-12-01 13:20:37', '2025-12-01 13:20:37'),
(7, NULL, 3, 'We need Vue.js-specific linting beyond standard JavaScript analysis. Our team uses Vue 3 composition API extensively, and we want checks for reactive state management anti-patterns, lifecycle hook misuse, and template binding errors. Current scope doesn\'t cover this.\r\n\r\nMost of our codebase is React, but the task description only mentions it for accessibility. We need React Hook validation (missing dependencies, misuse of useState/useEffect), component re-render optimization detection, and JSX best practices. This is critical for our team.', NULL, 1, 'approved', 'normal', 0, NULL, 1, NULL, '2025-12-06 11:02:13', 28, 45, 24, 'task', 7, NULL, NULL, '2025-12-06 10:59:27', '2025-12-06 11:02:13');

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `base_price` decimal(10,2) DEFAULT NULL,
  `category` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `features` json DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `icon` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `estimated_duration_days` int DEFAULT NULL,
  `required_skills` json DEFAULT NULL,
  `requirements` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `services`
--

INSERT INTO `services` (`id`, `name`, `description`, `base_price`, `category`, `features`, `is_active`, `icon`, `estimated_duration_days`, `required_skills`, `requirements`, `created_at`, `updated_at`) VALUES
(1, 'Custom Website Development', 'Full-stack custom website development with modern technologies like React, Laravel, and responsive design.', 5000.00, 'Web Development', '[\"Custom Design\", \"Responsive Layout\", \"Content Management System\", \"SEO Optimization\", \"Performance Optimization\", \"3 Months Support\"]', 1, 'fas fa-code', 45, '[\"HTML/CSS\", \"JavaScript\", \"React\", \"Laravel\", \"MySQL\"]', 'Detailed project requirements, content, branding materials, and hosting preferences.', '2025-10-25 06:38:18', '2025-10-25 06:38:18'),
(2, 'E-commerce Platform', 'Complete e-commerce solution with payment integration, inventory management, and admin dashboard.', 12000.00, 'Web Development', '[\"Shopping Cart\", \"Payment Gateway Integration\", \"Inventory Management\", \"Order Management\", \"Admin Dashboard\", \"Customer Accounts\", \"Email Notifications\", \"6 Months Support\"]', 1, 'fas fa-shopping-cart', 90, '[\"PHP\", \"Laravel\", \"MySQL\", \"Payment APIs\", \"JavaScript\", \"Security\"]', 'Product catalog, payment preferences, shipping requirements, and business logic specifications.', '2025-10-25 06:38:18', '2025-10-25 06:38:18'),
(3, 'Web Application Development', 'Custom web application development for business processes, dashboards, and data management.', 8000.00, 'Web Development', '[\"Custom Functionality\", \"User Authentication\", \"Database Integration\", \"API Development\", \"Admin Panel\", \"Reporting System\", \"4 Months Support\"]', 1, 'fas fa-laptop-code', 60, '[\"Full-Stack Development\", \"Database Design\", \"API Development\", \"Security\"]', 'Functional requirements, user workflows, data structure, and integration needs.', '2025-10-25 06:38:18', '2025-10-25 06:38:18'),
(4, 'WordPress Development', 'Custom WordPress website with themes, plugins, and content management capabilities.', 3000.00, 'Web Development', '[\"Custom Theme\", \"Content Management\", \"Plugin Integration\", \"SEO Setup\", \"Responsive Design\", \"Training Included\", \"2 Months Support\"]', 1, 'fab fa-wordpress', 30, '[\"WordPress\", \"PHP\", \"HTML/CSS\", \"JavaScript\"]', 'Content, design preferences, required functionality, and hosting information.', '2025-10-25 06:38:18', '2025-10-25 06:38:18'),
(5, 'Mobile App Development', 'Cross-platform mobile application development using React Native or Flutter.', 15000.00, 'Mobile Development', '[\"iOS & Android Compatible\", \"Native Performance\", \"Push Notifications\", \"App Store Deployment\", \"User Authentication\", \"Offline Capability\", \"6 Months Support\"]', 1, 'fas fa-mobile-alt', 120, '[\"React Native\", \"Flutter\", \"Mobile UI/UX\", \"API Integration\"]', 'App specifications, target platforms, design mockups, and required integrations.', '2025-10-25 06:38:18', '2025-10-25 06:38:18'),
(6, 'Progressive Web App (PWA)', 'Progressive Web Application that works across all devices with app-like experience.', 7000.00, 'Mobile Development', '[\"Offline Functionality\", \"Push Notifications\", \"App-like Experience\", \"Cross-Platform\", \"Fast Loading\", \"Installable\", \"4 Months Support\"]', 1, 'fas fa-tablet-alt', 50, '[\"JavaScript\", \"PWA Technologies\", \"Service Workers\", \"Web APIs\"]', 'Feature requirements, offline capabilities needed, and target user experience.', '2025-10-25 06:38:18', '2025-10-25 06:38:18'),
(7, 'UI/UX Design', 'Complete user interface and user experience design including wireframes, prototypes, and final designs.', 4000.00, 'Design', '[\"User Research\", \"Wireframes\", \"Interactive Prototypes\", \"Visual Design\", \"Design System\", \"Usability Testing\", \"Design Handoff\"]', 1, 'fas fa-paint-brush', 35, '[\"UI Design\", \"UX Research\", \"Figma\", \"Prototyping\", \"User Testing\"]', 'Project goals, target audience, brand guidelines, and platform specifications.', '2025-10-25 06:38:18', '2025-10-25 06:38:18'),
(8, 'Brand Identity Design', 'Complete brand identity package including logo, color palette, typography, and brand guidelines.', 2500.00, 'Design', '[\"Logo Design\", \"Color Palette\", \"Typography Selection\", \"Brand Guidelines\", \"Business Card Design\", \"Letterhead Design\", \"Social Media Templates\"]', 1, 'fas fa-palette', 21, '[\"Brand Design\", \"Adobe Creative Suite\", \"Logo Design\", \"Typography\"]', 'Brand vision, target audience, industry information, and style preferences.', '2025-10-25 06:38:18', '2025-10-25 06:38:18'),
(9, 'Website Redesign', 'Complete website redesign to improve user experience, modernize appearance, and enhance functionality.', 6000.00, 'Design', '[\"Current Site Analysis\", \"New Design Concepts\", \"Improved User Experience\", \"Modern Visual Design\", \"Mobile Optimization\", \"Performance Improvements\", \"3 Months Support\"]', 1, 'fas fa-magic', 40, '[\"Web Design\", \"UX Analysis\", \"Frontend Development\", \"Performance Optimization\"]', 'Current website, business goals, target audience, and desired improvements.', '2025-10-25 06:38:18', '2025-10-25 06:38:18'),
(10, 'API Development', 'Custom REST API development for mobile apps, web applications, and third-party integrations.', 4500.00, 'Backend Development', '[\"RESTful API Design\", \"Authentication & Authorization\", \"Database Integration\", \"API Documentation\", \"Rate Limiting\", \"Error Handling\", \"Testing Suite\"]', 1, 'fas fa-plug', 35, '[\"API Development\", \"Backend Development\", \"Database Design\", \"Security\"]', 'API specifications, data requirements, authentication needs, and integration points.', '2025-10-25 06:38:18', '2025-10-25 06:38:18'),
(11, 'Third-party Integration', 'Integration with external services, payment gateways, CRMs, and other third-party systems.', 2500.00, 'Integration', '[\"Service Integration\", \"Data Synchronization\", \"Error Handling\", \"Testing & Validation\", \"Documentation\", \"Monitoring Setup\", \"2 Months Support\"]', 1, 'fas fa-link', 20, '[\"API Integration\", \"Data Processing\", \"System Integration\", \"Testing\"]', 'Integration requirements, API documentation, access credentials, and data mapping needs.', '2025-10-25 06:38:18', '2025-10-25 06:38:18'),
(12, 'Technical Consulting', 'Expert technical consultation for architecture decisions, technology stack selection, and project planning.', 150.00, 'Consulting', '[\"Technical Architecture Review\", \"Technology Stack Recommendation\", \"Performance Analysis\", \"Security Assessment\", \"Scalability Planning\", \"Code Review\", \"Documentation\"]', 1, 'fas fa-user-tie', 7, '[\"System Architecture\", \"Multiple Technologies\", \"Performance Optimization\", \"Security\"]', 'Current system information, business requirements, and specific consultation needs.', '2025-10-25 06:38:18', '2025-10-25 06:38:18'),
(13, 'Digital Strategy Consulting', 'Strategic consulting for digital transformation, online presence optimization, and growth planning.', 3500.00, 'Consulting', '[\"Digital Strategy Development\", \"Market Analysis\", \"Competitive Research\", \"Technology Roadmap\", \"ROI Planning\", \"Implementation Guide\", \"Follow-up Sessions\"]', 1, 'fas fa-chart-line', 14, '[\"Business Analysis\", \"Digital Marketing\", \"Strategy Planning\", \"Market Research\"]', 'Business information, current digital presence, goals, and target market details.', '2025-10-25 06:38:18', '2025-10-25 06:38:18'),
(14, 'Website Maintenance', 'Ongoing website maintenance, updates, security monitoring, and performance optimization.', 500.00, 'Maintenance', '[\"Regular Updates\", \"Security Monitoring\", \"Performance Optimization\", \"Backup Management\", \"Content Updates\", \"Bug Fixes\", \"Monthly Reports\"]', 1, 'fas fa-tools', 30, '[\"Web Development\", \"Security\", \"Performance Optimization\", \"Monitoring\"]', 'Website access, hosting information, and maintenance requirements.', '2025-10-25 06:38:18', '2025-10-25 06:38:18'),
(15, 'SEO Optimization', 'Search engine optimization to improve website visibility and organic search rankings.', 2000.00, 'Marketing', '[\"SEO Analysis\", \"Keyword Research\", \"On-page Optimization\", \"Technical SEO\", \"Content Optimization\", \"Link Building Strategy\", \"Monthly Reporting\"]', 1, 'fas fa-search', 30, '[\"SEO\", \"Content Marketing\", \"Analytics\", \"Technical SEO\"]', 'Website access, target keywords, business goals, and current SEO status.', '2025-10-25 06:38:18', '2025-10-25 06:38:18');

-- --------------------------------------------------------

--
-- Table structure for table `service_requests`
--

CREATE TABLE `service_requests` (
  `id` bigint UNSIGNED NOT NULL,
  `client_id` bigint UNSIGNED NOT NULL,
  `contact_method` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `contact_details` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `service_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `template_service_id` bigint UNSIGNED DEFAULT NULL,
  `project_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `request_description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `deadline` date DEFAULT NULL,
  `expectations` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `additional_notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `requirements` json DEFAULT NULL,
  `requested_features` json DEFAULT NULL,
  `requested_skills` json DEFAULT NULL,
  `template_features` json DEFAULT NULL,
  `template_skills` json DEFAULT NULL,
  `has_customizations` tinyint(1) NOT NULL DEFAULT '0',
  `estimated_duration_days` int DEFAULT NULL,
  `template_base_price` decimal(10,2) DEFAULT NULL,
  `status` enum('pending','approved','rejected','pending_payment','paid','in_progress','completed') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `priority` enum('low','medium','high','urgent') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'medium',
  `estimated_budget` decimal(10,2) DEFAULT NULL,
  `approved_budget` decimal(10,2) DEFAULT NULL,
  `applied_coupon_id` bigint UNSIGNED DEFAULT NULL,
  `original_approved_budget` decimal(10,2) DEFAULT NULL,
  `coupon_discount_amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `coupon_applied_at` timestamp NULL DEFAULT NULL,
  `coupon_auto_applied` tinyint(1) NOT NULL DEFAULT '0',
  `loyalty_points_used` int NOT NULL DEFAULT '0',
  `loyalty_discount_amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `loyalty_applied_at` timestamp NULL DEFAULT NULL,
  `loyalty_points_earned` int NOT NULL DEFAULT '0',
  `loyalty_points_awarded` tinyint(1) NOT NULL DEFAULT '0',
  `loyalty_points_awarded_at` timestamp NULL DEFAULT NULL,
  `total_discount_amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `discount_percentage` decimal(5,2) NOT NULL DEFAULT '0.00',
  `coupon_code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `original_budget` decimal(12,2) DEFAULT NULL,
  `discount_applied` decimal(12,2) NOT NULL DEFAULT '0.00',
  `payment_type` enum('full_payment','milestone_payment','downpayment') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `downpayment_percentage` decimal(5,2) DEFAULT NULL,
  `downpayment_amount` decimal(10,2) DEFAULT NULL,
  `remaining_balance` decimal(10,2) DEFAULT NULL,
  `downpayment_paid` tinyint(1) NOT NULL DEFAULT '0',
  `downpayment_paid_at` timestamp NULL DEFAULT NULL,
  `remaining_balance_paid` tinyint(1) NOT NULL DEFAULT '0',
  `remaining_balance_paid_at` timestamp NULL DEFAULT NULL,
  `total_milestones` int DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `approved_by` bigint UNSIGNED DEFAULT NULL,
  `reviewed_at` timestamp NULL DEFAULT NULL,
  `payment_method` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_due_date` timestamp NULL DEFAULT NULL,
  `payment_confirmed_at` timestamp NULL DEFAULT NULL,
  `payment_reference` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_instructions` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `admin_notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `rejection_reason` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `service_requests`
--

INSERT INTO `service_requests` (`id`, `client_id`, `contact_method`, `contact_details`, `service_type`, `template_service_id`, `project_name`, `request_description`, `deadline`, `expectations`, `additional_notes`, `requirements`, `requested_features`, `requested_skills`, `template_features`, `template_skills`, `has_customizations`, `estimated_duration_days`, `template_base_price`, `status`, `priority`, `estimated_budget`, `approved_budget`, `applied_coupon_id`, `original_approved_budget`, `coupon_discount_amount`, `coupon_applied_at`, `coupon_auto_applied`, `loyalty_points_used`, `loyalty_discount_amount`, `loyalty_applied_at`, `loyalty_points_earned`, `loyalty_points_awarded`, `loyalty_points_awarded_at`, `total_discount_amount`, `discount_percentage`, `coupon_code`, `original_budget`, `discount_applied`, `payment_type`, `downpayment_percentage`, `downpayment_amount`, `remaining_balance`, `downpayment_paid`, `downpayment_paid_at`, `remaining_balance_paid`, `remaining_balance_paid_at`, `total_milestones`, `approved_at`, `approved_by`, `reviewed_at`, `payment_method`, `payment_due_date`, `payment_confirmed_at`, `payment_reference`, `payment_instructions`, `admin_notes`, `rejection_reason`, `created_at`, `updated_at`) VALUES
(1, 3, 'email', 'john.smith@techstartup.com', 'Custom Website Development', NULL, 'TechStartup Corporate Website', 'We need a modern, professional website for our AI startup that showcases our products, team, and company culture. The site should be responsive, fast-loading, and include a contact form, blog section, and integration with our CRM system.', '2025-12-24', 'Professional website with CRM integration, responsive design, and analytics dashboard', 'Target audience: Small business owners, tech entrepreneurs, and potential investors', '\"{\\\"hubspot_integration\\\":true,\\\"lead_capture_forms\\\":true,\\\"analytics_dashboard\\\":true,\\\"responsive_design\\\":true,\\\"blog_section\\\":true}\"', NULL, NULL, NULL, NULL, 0, NULL, NULL, 'approved', 'high', 6500.00, 50000.00, NULL, NULL, 0.00, NULL, 0, 0, 0.00, NULL, 0, 0, NULL, 0.00, 0.00, NULL, NULL, 0.00, 'full_payment', NULL, NULL, NULL, 0, NULL, 0, NULL, NULL, '2025-11-10 11:34:45', 1, '2025-11-10 11:34:45', 'email', '2025-11-17 00:00:00', NULL, NULL, NULL, NULL, NULL, '2025-10-23 06:38:18', '2025-11-10 11:34:45'),
(2, 4, 'email', 'maria@designstudio.com', 'Brand Identity Design', NULL, 'Complete Brand Identity Package', 'Complete rebrand for our design studio including new logo, color palette, typography, business cards, letterhead, and brand guidelines. We want a modern, creative identity that reflects our innovative approach to design.', '2025-11-24', 'Modern, creative brand identity package with comprehensive style guide', 'Target audience: Creative professionals, small businesses, and marketing agencies', '\"{\\\"logo_variations\\\":true,\\\"color_palette\\\":true,\\\"typography_guide\\\":true,\\\"business_cards\\\":true,\\\"letterhead\\\":true,\\\"social_media_templates\\\":true,\\\"brand_guidelines\\\":true}\"', NULL, NULL, NULL, NULL, 0, NULL, NULL, 'approved', 'medium', 3250.00, 3250.00, NULL, NULL, 0.00, NULL, 0, 0, 0.00, NULL, 0, 0, NULL, 0.00, 0.00, NULL, NULL, 0.00, NULL, NULL, NULL, NULL, 0, NULL, 0, NULL, NULL, '2025-10-24 06:38:18', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-20 06:38:18', '2025-10-25 06:38:18'),
(3, 5, 'email', 'david@ecommerceco.com', 'E-commerce Platform Development', NULL, 'Multi-vendor E-commerce Platform', 'Custom e-commerce platform that allows multiple vendors to sell their products. Features needed include vendor registration, product management, order processing, payment integration (Stripe/PayPal), inventory tracking, and admin dashboard.', '2026-02-22', 'Full-featured multi-vendor e-commerce platform with advanced reporting and mobile-responsive design', 'Target audience: Multiple vendors and their customers across various product categories', '\"{\\\"multi_vendor\\\":true,\\\"vendor_registration\\\":true,\\\"product_management\\\":true,\\\"order_processing\\\":true,\\\"payment_integration\\\":[\\\"stripe\\\",\\\"paypal\\\"],\\\"inventory_tracking\\\":true,\\\"admin_dashboard\\\":true,\\\"commission_tracking\\\":true,\\\"automated_payouts\\\":true,\\\"advanced_reporting\\\":true,\\\"mobile_responsive\\\":true}\"', NULL, NULL, NULL, NULL, 0, NULL, NULL, 'pending', 'high', 20000.00, NULL, NULL, NULL, 0.00, NULL, 0, 0, 0.00, NULL, 0, 0, NULL, 0.00, 0.00, NULL, NULL, 0.00, NULL, NULL, NULL, NULL, 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-18 06:38:18', '2025-10-25 06:38:18'),
(4, 6, 'email', 'lisa@nonprofithelp.org', 'WordPress Development', NULL, 'Nonprofit Website with Donation System', 'WordPress website for our nonprofit with donation functionality, volunteer registration, event management, and blog. Need to integrate with popular donation platforms and make it easy for visitors to get involved.', '2025-12-09', 'Complete WordPress website with donation system, volunteer management, and event calendar', 'Target audience: Community members, potential donors, volunteers, and grant organizations', '\"{\\\"donation_platform\\\":true,\\\"volunteer_registration\\\":true,\\\"event_management\\\":true,\\\"blog_functionality\\\":true,\\\"event_calendar\\\":true,\\\"newsletter_signup\\\":true,\\\"wordpress_cms\\\":true}\"', NULL, NULL, NULL, NULL, 0, NULL, NULL, 'approved', 'medium', 4000.00, 4000.00, NULL, NULL, 0.00, NULL, 0, 0, 0.00, NULL, 0, 0, NULL, 0.00, 0.00, NULL, NULL, 0.00, NULL, NULL, NULL, NULL, 0, NULL, 0, NULL, NULL, '2025-10-24 18:38:18', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-22 06:38:18', '2025-10-25 06:38:18'),
(5, 3, 'email', 'john.smith@techstartup.com', 'Mobile App Development', NULL, 'AI Assistant Mobile App', 'Cross-platform mobile app for our AI assistant service. Users should be able to interact with AI, save conversations, manage settings, and sync across devices. Integration with our existing API is required.', '2026-03-24', 'Full-featured mobile app with AI integration, real-time chat, and cross-device sync', 'Target audience: Tech-savvy professionals and students who need AI assistance', '\"{\\\"cross_platform\\\":true,\\\"ai_integration\\\":true,\\\"real_time_chat\\\":true,\\\"voice_input_output\\\":true,\\\"offline_capability\\\":true,\\\"push_notifications\\\":true,\\\"api_integration\\\":true,\\\"conversation_sync\\\":true,\\\"user_settings\\\":true}\"', NULL, NULL, NULL, NULL, 0, NULL, NULL, 'approved', 'high', 21500.00, 50000.00, NULL, NULL, 0.00, NULL, 0, 0, 0.00, NULL, 0, 0, NULL, 0.00, 0.00, NULL, NULL, 0.00, 'full_payment', NULL, NULL, NULL, 0, NULL, 0, NULL, NULL, '2025-11-10 11:35:26', 1, '2025-11-10 11:35:26', 'email', '2025-11-17 00:00:00', NULL, NULL, NULL, NULL, NULL, '2025-10-24 06:38:18', '2025-11-10 11:35:26'),
(6, 4, 'email', 'maria@designstudio.com', 'UI/UX Design', NULL, 'Design Portfolio Web App', 'Modern web application to showcase our design portfolio with advanced filtering, project case studies, client testimonials, and contact forms. Should have smooth animations and excellent user experience.', '2026-01-08', 'Modern portfolio web app with interactive gallery and excellent UX', 'Target audience: Potential clients, design community, and business partners', '\"{\\\"portfolio_gallery\\\":true,\\\"advanced_filtering\\\":true,\\\"case_studies\\\":true,\\\"client_testimonials\\\":true,\\\"contact_forms\\\":true,\\\"smooth_animations\\\":true,\\\"responsive_design\\\":true,\\\"project_brief_system\\\":true}\"', NULL, NULL, NULL, NULL, 0, NULL, NULL, 'rejected', 'medium', 8000.00, NULL, NULL, NULL, 0.00, NULL, 0, 0, 0.00, NULL, 0, 0, NULL, 0.00, 0.00, NULL, NULL, 0.00, NULL, NULL, NULL, NULL, 0, NULL, 0, NULL, NULL, NULL, NULL, '2025-10-24 22:38:18', NULL, NULL, NULL, NULL, NULL, NULL, 'Budget constraints - client requested to resubmit with revised scope', '2025-10-15 06:38:18', '2025-10-25 06:38:18'),
(7, 5, 'email', 'david@ecommerceco.com', 'API Development', NULL, 'E-commerce Integration API', 'REST API to integrate our e-commerce platform with various third-party services including inventory management, shipping providers, and accounting software. Need comprehensive documentation and testing.', '2026-01-23', 'Complete REST API with comprehensive documentation and testing', 'Target audience: Internal development team and third-party service providers', '\"{\\\"restful_design\\\":true,\\\"comprehensive_docs\\\":true,\\\"rate_limiting\\\":true,\\\"authentication\\\":true,\\\"webhook_support\\\":true,\\\"inventory_integration\\\":true,\\\"shipping_integration\\\":true,\\\"accounting_integration\\\":true,\\\"testing_suite\\\":true}\"', NULL, NULL, NULL, NULL, 0, NULL, NULL, 'approved', 'medium', 10000.00, 10000.00, NULL, NULL, 0.00, NULL, 0, 0, 0.00, NULL, 0, 0, NULL, 0.00, 0.00, NULL, NULL, 0.00, NULL, NULL, NULL, NULL, 0, NULL, 0, NULL, NULL, '2025-10-23 06:38:18', 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-17 06:38:18', '2025-10-25 06:38:18'),
(8, 6, 'email', 'lisa@nonprofithelp.org', 'Technical Consulting', NULL, 'Technology Strategy Consultation', 'Need expert consultation on selecting the right technology stack for our organization. Want to modernize our systems and improve efficiency while staying within nonprofit budget constraints.', '2025-11-15', 'Technology strategy recommendations with budget-conscious implementation roadmap', 'Target audience: Internal staff and board members', '\"{\\\"budget_conscious\\\":true,\\\"technology_recommendations\\\":true,\\\"staff_training_plan\\\":true,\\\"implementation_roadmap\\\":true,\\\"efficiency_improvements\\\":true,\\\"nonprofit_focused\\\":true}\"', NULL, NULL, NULL, NULL, 0, NULL, NULL, 'pending', 'low', 2250.00, NULL, NULL, NULL, 0.00, NULL, 0, 0, 0.00, NULL, 0, 0, NULL, 0.00, 0.00, NULL, NULL, 0.00, NULL, NULL, NULL, NULL, 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-21 06:38:18', '2025-10-25 06:38:18'),
(9, 3, 'email', 'john.smith@techstartup.com', 'SEO Optimization', NULL, 'Website SEO Optimization', 'Complete SEO optimization for our existing website to improve search rankings and organic traffic. Need keyword research, on-page optimization, and ongoing monitoring.', '2025-12-24', 'Improved search rankings and organic traffic with ongoing monitoring', 'Target audience: Potential customers searching for AI solutions', '\"{\\\"keyword_research\\\":true,\\\"on_page_optimization\\\":true,\\\"competitor_analysis\\\":true,\\\"monthly_reporting\\\":true,\\\"ai_tech_focus\\\":true,\\\"organic_traffic_improvement\\\":true,\\\"search_ranking_improvement\\\":true}\"', NULL, NULL, NULL, NULL, 0, NULL, NULL, 'paid', 'medium', 2750.00, 50000.00, NULL, NULL, 0.00, NULL, 0, 0, 0.00, NULL, 0, 0, NULL, 0.00, 0.00, NULL, NULL, 0.00, 'full_payment', NULL, NULL, NULL, 0, NULL, 0, NULL, NULL, '2025-11-10 11:36:46', 1, '2025-11-10 11:36:46', 'email', '2025-11-17 00:00:00', '2025-11-14 12:12:42', 'TREIS-1763093533-9', NULL, NULL, NULL, '2025-10-24 12:38:18', '2025-11-14 12:12:42'),
(10, 5, 'email', 'david@ecommerceco.com', 'Website Maintenance', NULL, 'Ongoing Website Maintenance', 'Monthly maintenance service for our corporate website including security updates, performance monitoring, content updates, and backup management.', '2025-11-24', 'Monthly website maintenance with monitoring and emergency support', 'Target audience: Website visitors and internal team', '\"{\\\"security_updates\\\":true,\\\"performance_monitoring\\\":true,\\\"content_updates\\\":true,\\\"backup_management\\\":true,\\\"monthly_reports\\\":true,\\\"24_7_monitoring\\\":true,\\\"emergency_support\\\":true,\\\"content_management\\\":true}\"', NULL, NULL, NULL, NULL, 0, NULL, NULL, 'approved', 'low', 650.00, 650.00, NULL, NULL, 0.00, NULL, 0, 0, 0.00, NULL, 0, 0, NULL, 0.00, 0.00, NULL, NULL, 0.00, NULL, NULL, NULL, NULL, 0, NULL, 0, NULL, NULL, '2025-10-25 00:38:18', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-24 06:38:18', '2025-10-25 06:38:18'),
(11, 12, 'email', 'markandrewsoliman@outlook.com', 'Consulting', NULL, 'Digital Strategy Consulting', 'I\'m interested in: Digital Strategy Consulting\r\n\r\nStrategic consulting for digital transformation, online presence optimization, and growth planning.\r\n\r\nAdditional details:', '2026-04-30', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 'paid', 'medium', NULL, 500000.00, NULL, NULL, 0.00, NULL, 0, 0, 0.00, NULL, 0, 0, NULL, 0.00, 0.00, NULL, NULL, 0.00, 'milestone_payment', NULL, NULL, NULL, 0, NULL, 0, NULL, 3, '2025-10-25 06:45:43', 1, '2025-10-25 06:45:43', 'email', '2025-10-31 16:00:00', '2025-10-25 07:08:11', 'TREIS-1761404864-11', NULL, NULL, NULL, '2025-10-25 06:40:16', '2025-10-25 07:08:11'),
(12, 14, 'phone', '+639614736286', 'Consulting', NULL, 'Digital Strategy Consulting', 'I\'m interested in: Digital Strategy Consulting\r\n\r\nStrategic consulting for digital transformation, online presence optimization, and growth planning.\r\n\r\nAdditional details:', '2026-03-25', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 'pending', 'medium', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, 0.00, NULL, 0, 0, NULL, 0.00, 0.00, NULL, NULL, 0.00, NULL, NULL, NULL, NULL, 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-28 03:05:46', '2025-10-28 03:05:46'),
(13, 15, 'email', 'punutez@treisadiutor.com', 'Mobile Development', NULL, 'Serina Cote', 'Possimus ea omnis a', '2026-01-24', 'Amet sit saepe labo', 'Voluptatem voluptate', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 'pending', 'medium', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, 0.00, NULL, 0, 0, NULL, 0.00, 0.00, NULL, NULL, 0.00, NULL, NULL, NULL, NULL, 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-28 03:36:26', '2025-10-28 03:36:26'),
(14, 16, 'messenger', 'Sunt id in optio su', 'Maintenance', NULL, 'Drake Maynard', 'Ratione praesentium', '2026-04-23', 'Nisi est saepe ulla', 'Explicabo Tenetur c', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 'pending', 'medium', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, 0.00, NULL, 0, 0, NULL, 0.00, 0.00, NULL, NULL, 0.00, NULL, NULL, NULL, NULL, 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-28 03:48:59', '2025-10-28 03:48:59'),
(15, 17, 'phone', '+1 (265) 112-7798', 'Integration', NULL, 'Dexter Drake', 'Ratione irure et dic', '2026-11-04', 'Numquam non assumend', 'Cum voluptatem Mole', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 'pending', 'medium', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, 0.00, NULL, 0, 0, NULL, 0.00, 0.00, NULL, NULL, 0.00, NULL, NULL, NULL, NULL, 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-28 03:50:04', '2025-10-28 03:50:04'),
(16, 18, 'messenger', 'Voluptatum aute mini', 'Backend Development', NULL, 'Jordan Huber', 'Tempore ullamco id', '2026-08-21', 'Possimus voluptate', 'Autem id voluptate e', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 'pending', 'medium', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, 0.00, NULL, 0, 0, NULL, 0.00, 0.00, NULL, NULL, 0.00, NULL, NULL, NULL, NULL, 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-28 03:50:45', '2025-10-28 03:50:45'),
(17, 19, 'phone', '+1 (956) 873-1851', 'Web Development', NULL, 'Wanda Bartlett', 'Omnis aliquam volupt', '2026-02-19', 'Ipsum id officia ex', 'Fugit autem dolore', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 'pending', 'medium', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, 0.00, NULL, 0, 0, NULL, 0.00, 0.00, NULL, NULL, 0.00, NULL, NULL, NULL, NULL, 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-28 04:00:30', '2025-10-28 04:00:30'),
(18, 20, 'phone', 'Modi do dolore susci', 'Backend Development', NULL, 'Linus Christian', 'Optio cupiditate ul', '2026-01-10', 'Dolor dolor id volup', 'Minus similique nisi', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 'pending', 'medium', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, 0.00, NULL, 0, 0, NULL, 0.00, 0.00, NULL, NULL, 0.00, NULL, NULL, NULL, NULL, 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-28 04:08:47', '2025-10-28 04:08:47'),
(19, 21, 'phone', 'Quia officia laborio', 'Backend Development', NULL, 'Nayda Harvey', 'Officia quibusdam ex', '2026-01-27', 'In enim qui ullamco', 'Adipisci corporis el', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 'pending', 'medium', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, 0.00, NULL, 0, 0, NULL, 0.00, 0.00, NULL, NULL, 0.00, NULL, NULL, NULL, NULL, 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-28 04:14:59', '2025-10-28 04:14:59'),
(20, 21, 'phone', 'Quia officia laborio', 'Backend Development', NULL, 'Nayda Harvey', 'Officia quibusdam ex', '2026-01-27', 'In enim qui ullamco', 'Adipisci corporis el', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 'pending', 'medium', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, 0.00, NULL, 0, 0, NULL, 0.00, 0.00, NULL, NULL, 0.00, NULL, NULL, NULL, NULL, 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-28 04:18:00', '2025-10-28 04:18:00'),
(21, 22, 'messenger', 'Asperiores sit quasi', 'Maintenance', NULL, 'Eugenia Caldwell', 'Et duis molestiae su', '2026-12-26', 'Deleniti laboriosam', 'Hic libero aliquid n', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 'pending', 'medium', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, 0.00, NULL, 0, 0, NULL, 0.00, 0.00, NULL, NULL, 0.00, NULL, NULL, NULL, NULL, 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-28 04:19:48', '2025-10-28 04:19:48'),
(22, 23, 'email', 'samdm@treisadiutor.com', 'Consulting', NULL, 'Digital Strategy Consulting', 'I\'m interested in: Digital Strategy Consulting\r\n\r\nStrategic consulting for digital transformation, online presence optimization, and growth planning.\r\n\r\nAdditional details:', '2026-03-05', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 'paid', 'medium', NULL, 500000.00, NULL, NULL, 0.00, NULL, 0, 0, 0.00, NULL, 0, 0, NULL, 0.00, 0.00, NULL, NULL, 0.00, 'milestone_payment', NULL, NULL, NULL, 0, NULL, 0, NULL, 3, '2025-10-28 23:48:36', 1, '2025-10-28 23:48:36', 'email', '2025-11-04 00:00:00', '2025-10-28 23:55:11', 'TREIS-1761666865-22', NULL, NULL, NULL, '2025-10-28 22:22:15', '2025-10-28 23:55:11'),
(23, 25, 'email', 'hesyv92aa@treisadiutor.com', 'Consulting', NULL, 'Digital Strategy Consulting', 'I\'m interested in: Digital Strategy Consulting\r\n\r\nStrategic consulting for digital transformation, online presence optimization, and growth planning.\r\n\r\nAdditional details:', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 'in_progress', 'medium', NULL, 500000.00, NULL, NULL, 0.00, NULL, 0, 0, 0.00, NULL, 0, 0, NULL, 0.00, 0.00, NULL, NULL, 0.00, 'milestone_payment', NULL, NULL, NULL, 0, NULL, 0, NULL, 3, '2025-10-29 08:40:48', 1, '2025-10-29 08:40:48', 'email', '2025-11-05 00:00:00', NULL, 'TREIS-1761698615-23', NULL, NULL, NULL, '2025-10-29 08:38:36', '2025-10-29 08:44:31'),
(24, 13, 'email', 'markandrewsoliman.personal@gmail.com', 'Programming', NULL, 'Jaquelyn Burke', 'Mollitia nesciunt l', '2026-08-15', 'Duis excepteur et am', 'Earum dolor laboris', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 'pending', 'medium', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, 0.00, NULL, 0, 0, NULL, 0.00, 0.00, NULL, NULL, 0.00, NULL, NULL, NULL, NULL, 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-29 13:50:48', '2025-10-29 13:50:48'),
(25, 13, 'email', 'markandrewsoliman.personal@gmail.com', 'Programming', NULL, 'Jaquelyn Burke', 'Mollitia nesciunt l', '2026-08-15', 'Duis excepteur et am', 'Earum dolor laboris', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 'pending', 'medium', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, 0.00, NULL, 0, 0, NULL, 0.00, 0.00, NULL, NULL, 0.00, NULL, NULL, NULL, NULL, 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-29 13:51:55', '2025-10-29 13:51:55'),
(26, 13, 'email', 'markandrewsoliman.personal@gmail.com', 'Programming', NULL, 'Jaquelyn Burke', 'Mollitia nesciunt l', '2026-08-15', 'Duis excepteur et am', 'Earum dolor laboris', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 'pending', 'medium', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, 0.00, NULL, 0, 0, NULL, 0.00, 0.00, NULL, NULL, 0.00, NULL, NULL, NULL, NULL, 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-29 13:52:19', '2025-10-29 13:52:19'),
(27, 13, 'email', 'markandrewsoliman.personal@gmail.com', 'Programming', NULL, 'Jaquelyn Burke', 'Mollitia nesciunt l', '2026-08-15', 'Duis excepteur et am', 'Earum dolor laboris', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 'pending', 'medium', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, 0.00, NULL, 0, 0, NULL, 0.00, 0.00, NULL, NULL, 0.00, NULL, NULL, NULL, NULL, 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-29 13:54:13', '2025-10-29 13:54:13'),
(28, 13, 'messenger', 'Exercitationem nihil', 'Writing', NULL, 'Sigourney Lynn', 'Repellendus Sunt es', '2026-12-22', 'Sit beatae nulla asp', 'Et et omnis distinct', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 'pending', 'medium', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, 0.00, NULL, 0, 0, NULL, 0.00, 0.00, NULL, NULL, 0.00, NULL, NULL, NULL, NULL, 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-29 14:28:53', '2025-10-29 14:28:53'),
(29, 13, 'messenger', 'Reprehenderit est ut', 'Programming', NULL, 'Tarik Michael', 'Quis ratione autem s', '2026-11-25', 'Aspernatur amet ani', 'Maxime fuga Et cons', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 'in_progress', 'medium', NULL, 500000.00, NULL, NULL, 0.00, NULL, 0, 0, 0.00, NULL, 0, 0, NULL, 0.00, 0.00, NULL, NULL, 0.00, 'milestone_payment', NULL, NULL, NULL, 0, NULL, 0, NULL, 3, '2025-10-29 14:31:47', 1, '2025-10-29 14:31:47', 'messenger', '2025-11-05 00:00:00', NULL, 'TREIS-1761719569-29', NULL, NULL, NULL, '2025-10-29 14:29:32', '2025-10-29 14:34:32'),
(32, 29, 'email', 'lenatheresequizon04@gmail.com', 'Mobile Development', NULL, 'Mobile App Development', 'I\'m interested in: Mobile App Development\r\n\r\nCross-platform mobile application development using React Native or Flutter.\r\n\r\nAdditional details:', '2025-11-10', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 'pending', 'medium', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, 0.00, NULL, 0, 0, NULL, 0.00, 0.00, NULL, NULL, 0.00, NULL, NULL, NULL, NULL, 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-29 16:49:48', '2025-10-29 16:49:48'),
(33, 3, 'messenger', 'john.smith@techstartup.com', 'Programming', NULL, 'Ecommerce 2', 'Just make it pretty', NULL, NULL, 'Nothing', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 'approved', 'medium', NULL, 50000.00, NULL, NULL, 0.00, NULL, 0, 0, 0.00, NULL, 0, 0, NULL, 0.00, 0.00, NULL, NULL, 0.00, 'full_payment', NULL, NULL, NULL, 0, NULL, 0, NULL, NULL, '2025-11-10 11:35:53', 1, '2025-11-10 11:35:53', 'messenger', '2025-11-17 00:00:00', NULL, NULL, NULL, NULL, NULL, '2025-11-10 11:28:22', '2025-11-10 11:35:53'),
(34, 3, 'messenger', 'john.smith@techstartup.com', 'Editing & Arts', NULL, 'Ecommerce 3', 'kjvksjf', '2025-12-06', 'dsfjkhsdjkf', 'sfjhjdf', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 'approved', 'medium', NULL, 50000.00, NULL, NULL, 0.00, NULL, 0, 0, 0.00, NULL, 0, 0, NULL, 0.00, 0.00, NULL, NULL, 0.00, 'full_payment', NULL, NULL, NULL, 0, NULL, 0, NULL, NULL, '2025-11-10 11:37:11', 1, '2025-11-10 11:37:11', 'messenger', '2025-11-17 00:00:00', NULL, NULL, NULL, NULL, NULL, '2025-11-10 11:29:05', '2025-11-10 11:37:11'),
(35, 3, 'email', 'john.smith@techstartup.com', 'Programming', NULL, 'Ecommerce 3', 'aifqe', '2025-12-06', 'fhhf', NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 'paid', 'medium', NULL, 400000.00, 2, 500000.00, 100000.00, '2025-11-14 12:05:01', 0, 0, 0.00, NULL, 0, 0, NULL, 100000.00, 0.00, NULL, NULL, 0.00, 'full_payment', NULL, NULL, NULL, 0, NULL, 0, NULL, NULL, '2025-11-14 12:04:31', 1, '2025-11-14 12:04:31', 'email', '2025-11-21 00:00:00', '2025-11-14 12:05:56', 'TREIS-1763093127-35', NULL, NULL, NULL, '2025-11-10 11:29:38', '2025-11-14 12:05:56'),
(36, 32, 'email', 'yuicutie1975@gmail.com', 'Programming', NULL, 'Game Mobile Application', 'Modern design', '2025-11-30', 'I expect this would be useful especially for the teens', 'None', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 'approved', 'medium', NULL, 500000.00, NULL, NULL, 0.00, NULL, 0, 0, 0.00, NULL, 0, 0, NULL, 0.00, 0.00, NULL, NULL, 0.00, 'full_payment', NULL, NULL, NULL, 0, NULL, 0, NULL, NULL, '2025-11-14 11:30:45', 1, '2025-11-14 11:30:45', 'email', '2025-11-21 00:00:00', NULL, NULL, NULL, NULL, NULL, '2025-11-12 01:39:18', '2025-11-14 11:30:45'),
(37, 32, 'email', 'yuicutie1975@gmail.com', 'Programming', NULL, 'Game Mobile Application', 'Modern design', '2025-11-30', 'I expect this would be useful especially for the teens', 'None', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 'approved', 'medium', NULL, 20000.00, NULL, NULL, 0.00, NULL, 0, 0, 0.00, NULL, 0, 0, NULL, 0.00, 0.00, NULL, NULL, 0.00, 'downpayment', 5.00, 1000.00, 19000.00, 0, NULL, 0, NULL, NULL, '2025-11-12 01:51:15', 1, '2025-11-12 01:51:15', 'email', '2025-11-20 00:00:00', NULL, NULL, 'Online transaction', 'hahaha', NULL, '2025-11-12 01:46:34', '2025-11-12 01:51:15'),
(38, 32, 'email', 'yuicutie1975@gmail.com', 'Editing & Arts', NULL, 'Graphics Design', 'Branding Item', '2025-11-20', 'Good', 'No', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 'paid', 'medium', NULL, 280000.00, 2, 350000.00, 70000.00, '2025-11-14 11:44:34', 0, 0, 0.00, NULL, 0, 0, NULL, 70000.00, 0.00, NULL, NULL, 0.00, 'full_payment', NULL, NULL, NULL, 0, NULL, 0, NULL, NULL, '2025-11-14 11:36:00', 1, '2025-11-14 11:36:00', 'email', '2025-11-21 00:00:00', '2025-11-14 11:48:46', 'TREIS-1763091906-38', NULL, NULL, NULL, '2025-11-12 02:01:51', '2025-11-14 11:48:46'),
(39, 29, 'email', 'lenatheresequizon04@gmail.com', 'Programming', NULL, 'Website Redesign', 'eme', '2025-11-16', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 'pending', 'medium', NULL, NULL, NULL, NULL, 0.00, NULL, 0, 0, 0.00, NULL, 0, 0, NULL, 0.00, 0.00, NULL, NULL, 0.00, NULL, NULL, NULL, NULL, 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-12 08:08:58', '2025-11-12 08:08:58'),
(40, 18, 'email', 'markandrew.soliman@lspu.edu.ph', 'Programming', NULL, 'UNIMERCE – Full E-Commerce Platform Development & Optimization', 'I am developing an end-to-end e-commerce platform named UNIMERCE, intended to support multiple user roles (Super Admin, Sellers, and Buyers). The project involves creating a dynamic and responsive website using HTML, CSS, JavaScript, Python (Flask), and MySQL.\r\n\r\nThe system requires key modules including:\r\n\r\nUser registration, login, and role management\r\n\r\nDynamic homepage with banners, categories, subcategories, and product listings\r\n\r\nSeller dashboard with product management and analytics\r\n\r\nBuyer interface for product browsing, cart, checkout, and order tracking\r\n\r\nReal-time promotions such as “Deals of the Week” with countdown timers\r\n\r\nDatabase design and integration using SQLYog\r\n\r\nResponsive UI and improvements in layout, design, and user experience\r\n\r\nIntegration with Docker for local development (optional)', '2026-11-25', 'Clean, maintainable, and scalable code\r\n\r\nConsistent, responsive UI/UX\r\n\r\nAccurate database integration\r\n\r\nClear documentation for future improvements\r\n\r\nReliable communication and timely updates', 'This project is part of an academic requirement and must follow proper project structure, clean coding standards, and best practices for web development. Assistance with debugging, deployment, and optimizing the architecture is also helpful.', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 'paid', 'medium', NULL, 500000.00, NULL, 500000.00, 0.00, NULL, 0, 0, 0.00, NULL, 0, 0, NULL, 0.00, 0.00, NULL, NULL, 0.00, 'full_payment', NULL, NULL, NULL, 0, NULL, 0, NULL, NULL, '2025-12-01 11:20:52', 1, '2025-12-01 11:20:52', 'email', '2025-12-08 00:00:00', '2025-12-01 11:35:03', 'TREIS-1764560070-40', NULL, NULL, NULL, '2025-12-01 11:19:32', '2025-12-01 11:36:32'),
(41, 18, 'email', 'markandrew.soliman@lspu.edu.ph', 'Programming', NULL, 'Multi-Platform Study Cards System (Web + Android)', 'I am working on a Study Cards System that will be developed for both Android (Java + Firebase) and Web (Flask / Java). The project includes features such as creating study sets, viewing study cards, dynamic dashboards, account management, and syncing data between platforms.\r\n\r\nThe goals include:\r\n\r\nBuilding the web application version of the existing Android app\r\n\r\nDesigning a user-friendly interface for creating and managing study sets\r\n\r\nSetting up Firebase and MySQL/Flask integration depending on the module\r\n\r\nImplementing dynamic content loading (dashboard cards, categories, user data)\r\n\r\nEnsuring responsive layout and clean UI\r\n\r\nImproving performance and fixing existing bugs\r\n\r\nAssisting with database structure and flow diagrams\r\n\r\nThe timeline is ongoing, and I need help finalizing core features, connecting backend logic, and polishing the UI/UX.', NULL, 'Clean, modular code\r\n\r\nWell-designed database structure\r\n\r\nSmooth, responsive UI across devices\r\n\r\nProper error handling and optimization\r\n\r\nClear communication and timely progress updates', 'This project is part of an academic requirement, so best practices, proper documentation, and solid architecture are important. Additional support for testing, debugging, and deployment is appreciated.', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 'paid', 'medium', NULL, 499000.00, 3, 500000.00, 1000.00, '2025-12-01 12:13:54', 0, 0, 0.00, NULL, 0, 0, NULL, 1000.00, 0.00, NULL, NULL, 0.00, 'full_payment', NULL, NULL, NULL, 0, NULL, 0, NULL, NULL, '2025-12-01 12:13:22', 1, '2025-12-01 12:13:22', 'email', '2025-12-08 00:00:00', '2025-12-01 12:14:35', 'TREIS-1764562439-41', NULL, '[2025-12-05 10:32:33 - Mark Admin]\nThis request requires modifications. Contracts is off platform.\n\n', NULL, '2025-12-01 12:12:57', '2025-12-05 10:32:33'),
(42, 3, 'email', 'john.smith@treisadiutor.com', 'Web Development', NULL, 'NovaSync Vendor Intelligence Dashboard', 'This project is a web-based analytics and automation dashboard that plugs into an existing multi-vendor e-commerce platform (like Nova) to give vendors real-time insights into product performance, operational health, and customer behavior. The goal is to centralize metrics from orders, inventory, refunds, delivery SLAs, and marketing events into a single, vendor-facing control panel with role-based access and granular permissions.\r\n\r\nThe system will include a Laravel-based backend API, a modern SPA frontend (React or Vue), and integrations with third-party services such as payment gateways, shipment tracking APIs, and email providers. Key features include configurable KPIs, anomaly alerts (e.g., sudden spike in returns for a specific SKU), seller tier scoring based on green-certified products, and a lightweight recommendation engine that suggests catalog or pricing optimizations.', NULL, 'Clean, well-documented codebase using Laravel best practices, SOLID principles, and PSR standards.\r\n\r\nModular architecture with clear separation between core analytics logic, vendor management, and third-party integrations.\r\n\r\nFully containerized environment (Docker) with staging and production setups, plus CI/CD pipelines (GitHub Actions or GitLab CI).\r\n\r\nAutomated test coverage for critical business logic (PHPUnit, feature tests, basic load testing for key endpoints).\r\n\r\nIntuitive UI with a professional, modern design that strictly adheres to an existing brand color palette and supports dark mode.\r\n\r\nClear technical documentation (API docs, ERD, deployment guide) and basic user documentation for vendor onboarding.', 'The solution should be multi-tenant aware so that multiple marketplaces can be onboarded in the future without major refactors.\r\n\r\nRole-based access control is required: owner, manager, analyst, and readonly roles for each vendor account.\r\n\r\nThe system should be designed to later plug in an ML service for anomaly detection and lead-scoring without changing the public API.\r\n\r\nPreference for Postgres or MySQL with clear indexing strategy for large order and event tables.\r\n\r\nFrontend must be responsive and optimized for both desktop dashboards and tablet use for on-the-go vendor managers.', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 'paid', 'medium', NULL, 425000.00, 13, 500000.00, 75000.00, '2025-12-05 17:38:21', 0, 0, 0.00, NULL, 0, 0, NULL, 75000.00, 0.00, NULL, NULL, 0.00, 'full_payment', NULL, NULL, NULL, 0, NULL, 0, NULL, NULL, '2025-12-05 17:17:57', 1, '2025-12-05 17:17:57', 'email', '2025-12-12 00:00:00', '2025-12-05 17:52:20', 'TREIS-1764928068-42', NULL, NULL, NULL, '2025-12-05 16:22:59', '2025-12-05 17:52:20'),
(43, 3, 'email', 'john.smith@treisadiutor.com', 'Web Development', NULL, 'EdgeRelay - Decentralized Content Delivery Network for Small-Scale Creators', 'Building a peer-assisted CDN platform that allows independent content creators, educators, and small media publishers to distribute video, audio, and large files without relying on expensive enterprise CDN services. The system uses a hybrid model: origin servers for reliability plus voluntary edge nodes contributed by supporters who earn micro-rewards (tokens or credits) for bandwidth sharing.\r\n\r\nThe backend needs a node orchestration service (Golang or Rust preferred for performance), a content routing algorithm that selects optimal peers based on geographic proximity and availability, and a blockchain-anchored ledger for transparent bandwidth accounting. The admin panel (Python/Django or Node.js) will handle creator accounts, content uploads, usage analytics, payout calculations, and node health monitoring.\r\n\r\nKey technical challenges: ensuring content integrity via cryptographic checksums, implementing adaptive bitrate streaming for video, handling node churn gracefully, and building a lightweight desktop client for edge participants that runs quietly in the background without hogging resources.', NULL, 'Horizontally scalable architecture capable of handling 10,000+ simultaneous streams during pilot phase.\r\n\r\nCryptographic verification for all distributed chunks to prevent tampering or data corruption.\r\n\r\nReal-time monitoring dashboard showing node health, bandwidth contribution heatmaps, and content delivery success rates.\r\n\r\nRESTful API with comprehensive documentation for third-party integrations (WordPress plugins, OBS streaming tools, etc.).\r\n\r\nCross-platform edge client (Windows, macOS, Linux) with minimal CPU/memory footprint and one-click setup.\r\n\r\nDetailed technical whitepaper explaining the incentive mechanism, security model, and performance benchmarks.', 'Must comply with DMCA safe harbor provisions and include automated takedown workflows for copyright disputes.\r\n\r\nThe platform should support private/unlisted content with token-gated access for premium subscribers or course participants.\r\n\r\nConsider WebRTC for peer discovery and WebTorrent compatibility as a fallback distribution method.\r\n\r\nInfrastructure should be cloud-agnostic with Terraform/Pulumi IaC scripts for deployment on AWS, GCP, or self-hosted Kubernetes clusters.\r\n\r\nLong-term vision includes smart contract integration for automated creator payouts based on consumption metrics.', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 'paid', 'medium', NULL, 250000.00, 4, 500000.00, 250000.00, '2025-12-05 17:47:38', 1, 0, 0.00, NULL, 0, 0, NULL, 250000.00, 0.00, NULL, NULL, 0.00, 'full_payment', NULL, NULL, NULL, 0, NULL, 0, NULL, NULL, '2025-12-05 17:47:38', 1, '2025-12-05 17:47:38', 'email', '2025-12-12 00:00:00', '2025-12-05 17:55:55', 'TREIS-1764928249-43', NULL, NULL, NULL, '2025-12-05 17:44:15', '2025-12-05 17:55:55'),
(44, 3, 'email', 'john.smith@treisadiutor.com', 'Web Development', NULL, 'QuantumShield - Zero-Trust Security Orchestration Platform', 'Developing an enterprise-grade zero-trust security orchestration platform that dynamically manages access policies across cloud infrastructure, SaaS applications, and on-premises systems using real-time risk scoring and behavioral analytics. The system continuously evaluates device posture, user identity signals, network context, and anomalous activity patterns to grant or revoke access without traditional perimeter-based security models.\r\n\r\nThe backend (Node.js or Go microservices) ingests telemetry from endpoints, identity providers (Okta, Azure AD), SIEM systems, and network sensors. A rules engine correlates signals to compute real-time risk scores; when thresholds are exceeded, the platform automatically triggers conditional access policies (MFA challenges, session termination, geo-fencing). The frontend provides security teams with a real-time threat dashboard, policy builder UI with drag-and-drop conditions, and detailed audit trails for compliance reporting (SOC 2, ISO 27001, HIPAA).\r\n\r\nTechnical components include a message queue (Kafka/RabbitMQ) for high-throughput event streaming, a time-series database (InfluxDB or TimescaleDB) for performance metrics, and integration adapters for AWS IAM, Okta, Slack, PagerDuty, and Splunk.', NULL, 'Sub-100ms policy decision latency under load (10,000 policy evaluations/second).\r\n\r\nEnterprise-grade high availability with multi-region failover and 99.99% SLA.\r\n\r\nCompliance-ready audit logging with immutable records and tamper-proof timestamping.\r\n\r\nExtensible plugin architecture allowing custom integrations without core codebase changes.\r\n\r\nComprehensive API documentation with SDKs for Python, Go, and JavaScript.\r\n\r\nRole-based access control with fine-grained permissions (admin, analyst, auditor, readonly).\r\n\r\nAutomated penetration testing and regular security assessments by third-party firm.', 'Must support on-premises deployment via Helm charts (Kubernetes) and Docker Compose for smaller organizations.\r\n\r\nFuture roadmap includes machine learning models for predictive threat detection and insider risk scoring.\r\n\r\nConsider hardware security module (HSM) support for cryptographic key management.\r\n\r\nAPI rate limiting and request signing to prevent unauthorized access to sensitive policy endpoints.\r\n\r\nDashboard should support custom metric widgets and exportable reports for executive briefings.', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 'approved', 'medium', NULL, 400000.00, 2, 500000.00, 100000.00, '2025-12-05 19:25:13', 1, 0, 0.00, NULL, 0, 0, NULL, 100000.00, 0.00, NULL, NULL, 0.00, 'full_payment', NULL, NULL, NULL, 0, NULL, 0, NULL, NULL, '2025-12-05 19:25:13', 1, '2025-12-05 19:25:13', 'email', '2025-12-12 00:00:00', NULL, NULL, NULL, NULL, NULL, '2025-12-05 17:56:53', '2025-12-05 19:25:13'),
(45, 3, 'email', 'john.smith@treisadiutor.com', 'Web Development', NULL, 'SynthAI - Autonomous Code Review and Technical Debt Analyzer', 'Building an intelligent code review automation platform that integrates directly into Git workflows (GitHub, GitLab, Bitbucket) to analyze pull requests for code quality, security vulnerabilities, performance bottlenecks, and technical debt accumulation. Unlike static linters, SynthAI uses multi-modal AI models to understand architectural patterns, suggest refactoring strategies aligned with team conventions, and predict which modules are becoming maintenance liabilities.\r\n\r\nThe backend (Python FastAPI + PostgreSQL) processes incoming webhook events from version control platforms, clones repositories, performs deep code analysis using custom AST parsers and LLVM-based performance profiling, and stores results in a queryable database. The platform learns from team feedback—when developers accept or reject suggestions, the models retrain to better match the team\'s coding style and priorities. A React/TypeScript frontend displays interactive diff annotations, debt trend charts, and team velocity metrics correlated with code quality scores.\r\n\r\nIntegration points include Slack for notifications, Jira for auto-creating technical debt tickets, and SonarQube/CodeClimate for complementary metrics. The system supports multiple languages (Python, JavaScript, Go, Java, Rust) with pluggable analyzers and custom rule definitions via YAML configuration.', NULL, 'Analysis latency under 2 minutes for typical pull requests (up to 500 lines of code).\r\n\r\nMachine learning model accuracy >85% for identifying genuine issues vs. false positives.\r\n\r\nSupport for monorepo analysis with path-based filtering and incremental scanning.\r\n\r\nDetailed explanatory comments on code suggestions (not just flagging problems—explaining why and offering solutions).\r\n\r\nDashboard showing team-wide metrics: code quality trends, velocity impact of debt, code review cycle time.\r\n\r\nRole-based permissions: admin (settings), reviewer (approve/reject suggestions), developer (view feedback), viewer (read-only dashboard).\r\n\r\nFull audit trail and compliance reporting for SOC 2 and regulated industries.', 'Must respect .gitignore and custom exclusion rules to avoid scanning irrelevant files.\r\n\r\nSupport for private/self-hosted repositories with SSH key authentication and IP whitelisting.\r\n\r\nFuture roadmap: automated code generation for boilerplate, AI-powered commit message suggestions, and predictive issue detection before code is even written.\r\n\r\nConsider containerized deployment model (Docker) for organizations with strict data residency requirements.\r\n\r\nWebhook security via HMAC-SHA256 signing; all API communication over TLS 1.3.\r\n\r\nGraceful handling of rate limits on third-party APIs (GitHub API quotas, etc.).', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 'paid', 'medium', NULL, 400000.00, 2, 500000.00, 100000.00, '2025-12-05 19:27:36', 1, 0, 0.00, NULL, 0, 0, NULL, 100000.00, 0.00, NULL, NULL, 0.00, 'full_payment', NULL, NULL, NULL, 0, NULL, 0, NULL, NULL, '2025-12-05 19:27:36', 1, '2025-12-05 19:27:36', 'email', '2025-12-12 00:00:00', '2025-12-05 19:30:00', 'TREIS-1764934140-45', NULL, NULL, NULL, '2025-12-05 19:22:59', '2025-12-05 19:30:00');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('BcudLKHZv4zPJgjkHIemgRlQgoPcQ3IpQSb2L0RK', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiVFVIbzFpblY0blB1QlBnOWk5T1F1TXdLbUcwd2IyR3VTMXNQR2l3eSI7czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTtzOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1764886225),
('bZcm5YkgTB0pka5IZOZ8HpIJNBW1ceSUGa5RPvwS', NULL, '127.0.0.1', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Mobile Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiUWlZaEEyQ2lIMGdGNE9pMFZkemtGdHlDUHJQd0hNb2R1bzBBVldBeSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1764891317),
('cgrhVDQsqkFew2s2HfjUL65xTuxxF7DKsjWW7ZW8', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiQndDMFlkWWNzSUJVMjhjT01EVFViU29ZcTRxS1B2ZWlHNGpyMExQZCI7czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTtzOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1764886225),
('NyzOkhktG3bYlBaxnrjX3eK61oPDLiCaWnpQIsAn', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiWGc5VmxDQzFYQk50VVNjOUlpdzdTMXNQc2RGcDJ0dkJGVTNsQzdFSiI7czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTtzOjk6Il9wcmV2aW91cyI7YToyOntzOjM6InVybCI7czozMzoiaHR0cDovL2xvY2FsaG9zdDo4MDAwL2FkbWluL3Rhc2tzIjtzOjU6InJvdXRlIjtzOjE3OiJhZG1pbi50YXNrcy5pbmRleCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1764891388),
('vRZAU9IfJH55vqCLfl27br5lMwVZoYuCsrGmdgFe', 7, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiS3REQWpJUGczTlVwN1BpQVpuMGtXRUVWcUcxUXZQR3dtbTZTdjVHaiI7czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6NztzOjk6Il9wcmV2aW91cyI7YToyOntzOjM6InVybCI7czo0NzoiaHR0cDovL2xvY2FsaG9zdDo4MDAwL2FwaS9tZXNzYWdlcy91bnJlYWQtY291bnQiO3M6NToicm91dGUiO3M6MjU6ImFwaS5tZXNzYWdlcy51bnJlYWQtY291bnQiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1764891201);

-- --------------------------------------------------------

--
-- Table structure for table `showcases`
--

CREATE TABLE `showcases` (
  `id` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `project_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `category` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `short_description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `full_description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `features` json DEFAULT NULL,
  `tools_used` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `thumbnail` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `login_details` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `live_link` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `github_link` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `client_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `duration` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('completed','ongoing','deprecated') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'completed',
  `attachments` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `showcases`
--

INSERT INTO `showcases` (`id`, `project_name`, `category`, `slug`, `short_description`, `full_description`, `features`, `tools_used`, `thumbnail`, `login_details`, `live_link`, `github_link`, `client_name`, `duration`, `status`, `attachments`, `created_at`, `updated_at`) VALUES
('3d912c47-f3d1-4b7e-9e12-80f540dd5b0b', 'StudyCards', 'Mobile Application', 'studycards', 'An Android app for creating, managing, and studying digital flashcards with tests and progress tracking.', 'StudyCards is an Android application designed to help users create, manage, and study digital flashcards and study sets. The app supports learning through flashcards, tests, and progress tracking, making it ideal for students and self-learners.', '[\"User Authentication\", \"Create/Edit Study Sets\", \"Flashcard Learning\", \"Spaced Repitition\", \"Test Mode\", \"Progress Tracking\", \"Public & Private Sets\"]', 'Java, Android Studio', 'https://res.cloudinary.com/do9pjlmm7/image/upload/v1753777533/47_tfejbn.png', NULL, 'private', 'private', 'Personal Project', '2 months', 'completed', NULL, '2024-11-22 00:00:00', '2024-11-22 00:00:00'),
('672916bb-04ad-4076-84d6-04974015ede3', 'T.V.A Express Logistics Inc.', 'Web Application', 'tva-express-logistics', 'A comprehensive truck rental and logistics management system with multi-role portals for admin, drivers, and customers.', 'T.V.A Express Logistics Inc. is a full-featured PHP and MySQL web application that manages truck rental operations, logistics, and fleet management. It supports multi-role access, secure authentication, rental request handling, GPS tracking, emergency reporting, payment processing, and a robust dashboard system for administrators. Built using Tailwind CSS, Alpine.js, and modern PHP development practices, the system ensures scalable logistics management for growing businesses.', '[\"Multi-role user authentication (Admin, Driver, Customer)\", \"Fleet and delivery management\", \"Real-time GPS tracking and status updates\", \"Rental request and approval system\", \"Emergency issue reporting\", \"Admin dashboard with analytics\", \"Secure payment system\", \"Customer order tracking and history\"]', 'PHP, MySQL, Tailwind CSS, Alpine.js, XAMPP', 'https://res.cloudinary.com/do9pjlmm7/image/upload/v1753444972/11_sjgg5t.png', 'Run via XAMPP/Apache. Configure db.php for local connection.', 'Private', 'Private', 'Private', '3 weeks', 'completed', NULL, '2025-02-09 12:03:04', '2025-02-09 12:03:04'),
('6b1c822e-64c1-4be0-9a2b-91f8f9cd1d4b', 'PC LEAGUE Management System', 'Web Application', 'pc-league-quotation-inventory-system', 'PC LEAGUE Management System is a web-based system for managing PC parts inventory, generating quotations and tracking expenses.', 'PC LEAGUE Management System is a full-featured inventory and quotation management platform for a PC parts business. It supports admins and employees with role-based access, real-time quotation generation with dynamic inventory selection, expense tracking, and PDF generation. The system is built using Tailwind CSS and Node.js serverless functions.', '[\"User Authentication\", \"Role-Based Access\", \"Quotation Creation and Export\", \"Inventory Tracking with Stock Alerts\", \"Sales Management\", \"Expense Management\", \"PDF Export\"]', 'HTML, Tailwind CSS, JavaScript, Node.js (Serverless), Supabase, Puppeteer, Google Cloud Run', 'https://res.cloudinary.com/do9pjlmm7/image/upload/v1753545430/TREIS_ADIUTOR_4_oul0md.png', 'Admin: /login.html → /admin/dashboard.html\r\nEmployee: /login.html → /general/dashboard.html', NULL, NULL, 'PC LEAGUE', '1 month', 'completed', NULL, '2025-07-29 16:03:28', '2025-07-29 16:03:28'),
('8985287c-714b-445f-8729-11f0adfe918f', 'KKB - Split Bills with Friends', 'Mobile Application', 'kkb-app', 'Split Group Expenses and Save Money Together', 'KKB is the ultimate app for tracking group finances, simplifying payment settlements, and staying on top of who owes what—whether you\'re living with roommates, traveling with friends, organizing events, or just sharing everyday costs.\r\n\r\nWith the help of KKB, it eliminates the stress and confusion that often comes with splitting bills. No more awkward follow-ups, forgotten expenses, or endless spreadsheets. With KKB, everyone stays in sync—and no one gets left out.\r\n\r\nWhy Use KKB?\r\n\r\nKKB is built for people who frequently spend together from friends, partners, housemates, travel companions, classmates, co-workers, to families. From splitting rent and utilities to managing travel budgets and group activities, KKB ensures all expenses are recorded and transparently divided.\r\n\r\nKey Features\r\n\r\n► Track Group Expenses in Real-Time\r\nCreate or join a group, add shared expenses, and instantly track how much each member owes. Whether it\'s for meals, transportation, or bills, every entry is automatically calculated and reflected across all members.\r\n\r\n► Settlement Overview for Clarity and Fairness\r\nAvoid confusion with a real-time balance overview that shows exactly who owes what to whom. The summary view helps reduce misunderstandings and makes settling up simple and fair.\r\n\r\n► Save Together with Group Goals\r\nEnable financial cooperation by setting shared savings goals. Whether you’re saving for a trip, a party, or a big group purchase, KKB makes it easier to reach your goals together.\r\n\r\n► Attach Receipts and Media to Expenses\r\nKeep your records complete and transparent. Add photos, receipts, or other attachments to each expense entry for better accountability and clarity.\r\n\r\n► Flexible Customization Options\r\nTailor the app to suit your preferences. Choose between different cute, modern, and beautiful themes, adjust layout views, and personalize your experience for better comfort and usability.\r\n\r\n► Seamless Group Management\r\nEasily create or join groups using unique invite codes. Share the code with your friends and manage all your shared expenses in one centralized space.\r\n\r\n► No account? No problem.\r\nAdd temporary guest users to groups so you can still split expenses without requiring everyone to sign up. Great for one-off trips or mixed crowds.\r\n\r\n► Custom Exchange Rates\r\nTraveling internationally or splitting with friends from other countries? Set your own exchange rates to reflect real-world agreements and avoid unexpected conversions.\r\n\r\n► Engage with your Friends:\r\nEach group or expense has a built-in comment thread. Share updates, clarifications, and even memories — complete with Images, GIFs. You can also show how how you feel using Like, Love, Celebrate, Support, Impressive, and Helpful!\r\n\r\n► Cloud-Based Data Sync\r\nAll your information is securely stored in the cloud. Access your data anytime, anywhere, across multiple devices without worrying about losing your records.\r\n\r\nWho Is KKB For?\r\n\r\nKKB is ideal for:\r\n- Roommates sharing rent, utilities, and groceries, Friends traveling together\r\n- Couples splitting regular or occasional expenses\r\n- Teams planning events or team-building activities\r\n- Families managing shared household finances\r\n- Students managing class projects or group orders\r\n- Anyone who frequently splits costs with others\r\n\r\nWhy KKB Stands Out\r\nThere are many apps for personal budgeting but few that truly focus on group financial harmony. KKB is purpose-built for groups, with features designed specifically to reduce friction, miscommunication, and financial stress in shared contexts. Its simplicity, clarity, and customization options make it a go-to app for group money management.\r\n\r\nReady to Simplify Your Group Expenses?\r\nDownload KKB now and take control of shared spending. Whether you’re managing day-to-day costs or planning something big, KKB helps you stay organized, clear, and connected.', '[\"Track Group Expenses in Real-Time\", \"Settlement Overview\", \"Save Together with Group Goals\", \"Custom Exchange Rates\", \"Engage with your Friends\"]', 'Flutter, Supabase', 'https://res.cloudinary.com/do9pjlmm7/image/upload/v1757675426/Cream_Simple_Get_App_Instagram_Story_1024_x_768_px_py66qg.png', NULL, 'https://play.google.com/store/apps/details?id=com.kkb.splitapp&pcampaignid=web_share', NULL, NULL, '3 Months', 'completed', NULL, '2025-08-20 11:11:21', '2025-09-12 11:11:21'),
('a28eca3b-92c7-4cc1-8d00-325e6afe04fa', 'ScheDue', 'Mobile Application', 'schedu', 'ScheDue is your ultimate scheduling planner and task reminder app, designed to make your life more organized and stress-free.', 'ScheDue is your ultimate scheduling planner and task reminder app, designed to make your life more organized and stress-free. \r\n\r\nWhether you\'re a student, a professional, or anyone looking to boost productivity, ScheDue is the perfect companion to help you stay focused and achieve your goals. Never miss a deadline again—plan smarter with ScheDue!', '[\"Manage daily tasks and appointments\", \"Set deadlines and reminders\", \"Clean and user-friendly interface\"]', 'Java, XML, Android Studio', 'https://res.cloudinary.com/do9pjlmm7/image/upload/v1753453868/31_zkgnlh.png', 'Email: user@example.com\r\nPassword: user123456', 'Private', 'https://github.com/jnieshaaa/SchedulingAppointmentPlanner', 'Laguna University', '3 weeks', 'completed', NULL, '2024-10-15 16:00:00', '2024-12-10 16:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `showcase_screenshots`
--

CREATE TABLE `showcase_screenshots` (
  `id` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `showcase_id` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `image_url` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `caption` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `sort_order` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `showcase_screenshots`
--

INSERT INTO `showcase_screenshots` (`id`, `showcase_id`, `image_url`, `caption`, `sort_order`, `created_at`, `updated_at`) VALUES
('0917f055-df76-4af0-bb25-ce98901f39af', 'a28eca3b-92c7-4cc1-8d00-325e6afe04fa', 'https://res.cloudinary.com/do9pjlmm7/image/upload/v1753453868/30_f3qkbh.png', NULL, 0, '2025-07-25 14:35:00', NULL),
('128872d6-6008-4363-b22a-966866af9d23', '8985287c-714b-445f-8729-11f0adfe918f', 'https://res.cloudinary.com/do9pjlmm7/image/upload/v1757675928/3_oikp0r.png', NULL, 2, '2025-09-12 11:14:07', NULL),
('1f09f92e-7d35-4236-a3ea-b8c70d0c714e', '8985287c-714b-445f-8729-11f0adfe918f', 'https://res.cloudinary.com/do9pjlmm7/image/upload/v1757675930/4_bh9osn.png', NULL, 3, '2025-09-12 11:14:23', NULL),
('39f8e4a0-ef7f-47ea-a662-ea5950f9a8e6', '672916bb-04ad-4076-84d6-04974015ede3', 'https://res.cloudinary.com/do9pjlmm7/image/upload/v1753444972/11_sjgg5t.png', 'Landing Page', 1, '2025-07-25 12:06:04', NULL),
('431a31d3-d740-4153-b12d-5006a0aaaa08', '672916bb-04ad-4076-84d6-04974015ede3', 'https://res.cloudinary.com/do9pjlmm7/image/upload/v1753444975/18_rfk3f7.png', 'Calendar', 8, '2025-07-25 12:06:04', NULL),
('4d9a7a96-f23c-4ed4-963f-aafdb560aed8', '672916bb-04ad-4076-84d6-04974015ede3', 'https://res.cloudinary.com/do9pjlmm7/image/upload/v1753444972/13_seyjvq.png', 'Driver Dashboard', 6, '2025-07-25 12:06:04', NULL),
('50a55d61-d9bf-4fcf-a3fd-cf3db0c11d78', '8985287c-714b-445f-8729-11f0adfe918f', 'https://res.cloudinary.com/do9pjlmm7/image/upload/v1757675926/2_om3pri.png', NULL, 1, '2025-09-12 11:13:40', NULL),
('55b04144-4213-4d38-8950-4f0c9d5e57e1', '672916bb-04ad-4076-84d6-04974015ede3', 'https://res.cloudinary.com/do9pjlmm7/image/upload/v1753444974/14_imqgke.png', 'Delivery Details', 5, '2025-07-25 12:06:04', NULL),
('55fa0b64-24ee-4a07-a85d-64cc04c7167f', '672916bb-04ad-4076-84d6-04974015ede3', 'https://res.cloudinary.com/do9pjlmm7/image/upload/v1753444973/16_zwfk1i.png', 'Payment Details', 3, '2025-07-25 12:06:04', NULL),
('68cfc465-4157-44ec-b5f3-ea0e3bbb5d5b', '672916bb-04ad-4076-84d6-04974015ede3', 'https://res.cloudinary.com/do9pjlmm7/image/upload/v1753444973/17_i0xp9c.png', 'Emergency Report', 4, '2025-07-25 12:06:04', NULL),
('81297fef-699e-48df-b530-b3e1090ea403', '672916bb-04ad-4076-84d6-04974015ede3', 'https://res.cloudinary.com/do9pjlmm7/image/upload/v1753444974/15_tvbxzv.png', 'Truck List', 7, '2025-07-25 12:06:04', NULL),
('8eb07e3d-cd53-4ab7-8f27-26f5c7263a85', '672916bb-04ad-4076-84d6-04974015ede3', 'https://res.cloudinary.com/do9pjlmm7/image/upload/v1753444972/12_w0tbmn.png', 'Customer Dashboard', 2, '2025-07-25 12:06:04', NULL),
('e2ae9ce8-4337-4a96-a30e-801bb1ae9b99', 'a28eca3b-92c7-4cc1-8d00-325e6afe04fa', 'https://res.cloudinary.com/do9pjlmm7/image/upload/v1753453942/32_jzkuqe.png', NULL, 0, '2025-07-25 14:35:00', NULL),
('f15f1e16-1e4b-46f0-bf95-b239e51780a5', 'a28eca3b-92c7-4cc1-8d00-325e6afe04fa', 'https://res.cloudinary.com/do9pjlmm7/image/upload/v1753453868/31_zkgnlh.png', NULL, 0, '2025-07-25 14:35:00', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `skills`
--

CREATE TABLE `skills` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `category` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `skills`
--

INSERT INTO `skills` (`id`, `name`, `description`, `category`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'PHP', NULL, 'Programming', 1, '2025-10-25 06:38:12', '2025-10-25 06:38:12'),
(2, 'JavaScript', NULL, 'Programming', 1, '2025-10-25 06:38:12', '2025-10-25 06:38:12'),
(3, 'Python', NULL, 'Programming', 1, '2025-10-25 06:38:12', '2025-10-25 06:38:12'),
(4, 'Java', NULL, 'Programming', 1, '2025-10-25 06:38:12', '2025-10-25 06:38:12'),
(5, 'C#', NULL, 'Programming', 1, '2025-10-25 06:38:12', '2025-10-25 06:38:12'),
(6, 'Ruby', NULL, 'Programming', 1, '2025-10-25 06:38:12', '2025-10-25 06:38:12'),
(7, 'Swift', NULL, 'Programming', 1, '2025-10-25 06:38:12', '2025-10-25 06:38:12'),
(8, 'TypeScript', NULL, 'Programming', 1, '2025-10-25 06:38:12', '2025-10-25 06:38:12'),
(9, 'React', NULL, 'Programming', 1, '2025-10-25 06:38:12', '2025-10-25 06:38:12'),
(10, 'Vue.js', NULL, 'Programming', 1, '2025-10-25 06:38:12', '2025-10-25 06:38:12'),
(11, 'Angular', NULL, 'Programming', 1, '2025-10-25 06:38:12', '2025-10-25 06:38:12'),
(12, 'Node.js', NULL, 'Programming', 1, '2025-10-25 06:38:12', '2025-10-25 06:38:12'),
(13, 'Laravel', NULL, 'Programming', 1, '2025-10-25 06:38:12', '2025-10-25 06:38:12'),
(14, 'MySQL', NULL, 'Database', 1, '2025-10-25 06:38:12', '2025-10-25 06:38:12'),
(15, 'PostgreSQL', NULL, 'Database', 1, '2025-10-25 06:38:12', '2025-10-25 06:38:12'),
(16, 'MongoDB', NULL, 'Database', 1, '2025-10-25 06:38:12', '2025-10-25 06:38:12'),
(17, 'SQLite', NULL, 'Database', 1, '2025-10-25 06:38:12', '2025-10-25 06:38:12'),
(18, 'Oracle', NULL, 'Database', 1, '2025-10-25 06:38:12', '2025-10-25 06:38:12'),
(19, 'SQL_Server', NULL, 'Database', 1, '2025-10-25 06:38:12', '2025-10-25 06:38:12'),
(20, 'UI/UX Design', NULL, 'Design', 1, '2025-10-25 06:38:12', '2025-10-25 06:38:12'),
(21, 'Graphic Design', NULL, 'Design', 1, '2025-10-25 06:38:12', '2025-10-25 06:38:12'),
(22, 'Web Design', NULL, 'Design', 1, '2025-10-25 06:38:12', '2025-10-25 06:38:12'),
(23, 'Logo Design', NULL, 'Design', 1, '2025-10-25 06:38:12', '2025-10-25 06:38:12'),
(24, 'Adobe Photoshop', NULL, 'Design', 1, '2025-10-25 06:38:12', '2025-10-25 06:38:12'),
(25, 'Adobe Illustrator', NULL, 'Design', 1, '2025-10-25 06:38:12', '2025-10-25 06:38:12'),
(26, 'Figma', NULL, 'Design', 1, '2025-10-25 06:38:12', '2025-10-25 06:38:12'),
(27, 'Docker', NULL, 'DevOps', 1, '2025-10-25 06:38:12', '2025-10-25 06:38:12'),
(28, 'Kubernetes', NULL, 'DevOps', 1, '2025-10-25 06:38:12', '2025-10-25 06:38:12'),
(29, 'AWS', NULL, 'DevOps', 1, '2025-10-25 06:38:12', '2025-10-25 06:38:12'),
(30, 'Azure', NULL, 'DevOps', 1, '2025-10-25 06:38:12', '2025-10-25 06:38:12'),
(31, 'Google Cloud', NULL, 'DevOps', 1, '2025-10-25 06:38:12', '2025-10-25 06:38:12'),
(32, 'Linux', NULL, 'DevOps', 1, '2025-10-25 06:38:12', '2025-10-25 06:38:12'),
(33, 'Git', NULL, 'DevOps', 1, '2025-10-25 06:38:12', '2025-10-25 06:38:12'),
(34, 'SEO', NULL, 'Marketing', 1, '2025-10-25 06:38:12', '2025-10-25 06:38:12'),
(35, 'Social Media Marketing', NULL, 'Marketing', 1, '2025-10-25 06:38:12', '2025-10-25 06:38:12'),
(36, 'Content Marketing', NULL, 'Marketing', 1, '2025-10-25 06:38:12', '2025-10-25 06:38:12'),
(37, 'Email Marketing', NULL, 'Marketing', 1, '2025-10-25 06:38:12', '2025-10-25 06:38:12'),
(38, 'Google Ads', NULL, 'Marketing', 1, '2025-10-25 06:38:12', '2025-10-25 06:38:12'),
(39, 'Analytics', NULL, 'Marketing', 1, '2025-10-25 06:38:12', '2025-10-25 06:38:12'),
(40, 'Copywriting', NULL, 'Content', 1, '2025-10-25 06:38:12', '2025-10-25 06:38:12'),
(41, 'Content Writing', NULL, 'Content', 1, '2025-10-25 06:38:12', '2025-10-25 06:38:12'),
(42, 'Technical Writing', NULL, 'Content', 1, '2025-10-25 06:38:12', '2025-10-25 06:38:12'),
(43, 'Editing', NULL, 'Content', 1, '2025-10-25 06:38:12', '2025-10-25 06:38:12'),
(44, 'Proofreading', NULL, 'Content', 1, '2025-10-25 06:38:12', '2025-10-25 06:38:12'),
(45, 'SEO Writing', NULL, 'Content', 1, '2025-10-25 06:38:12', '2025-10-25 06:38:12'),
(46, 'Project Management', NULL, 'Business', 1, '2025-10-25 06:38:12', '2025-10-25 06:38:12'),
(47, 'Business Analysis', NULL, 'Business', 1, '2025-10-25 06:38:12', '2025-10-25 06:38:12'),
(48, 'Agile', NULL, 'Business', 1, '2025-10-25 06:38:12', '2025-10-25 06:38:12'),
(49, 'Scrum', NULL, 'Business', 1, '2025-10-25 06:38:12', '2025-10-25 06:38:12'),
(50, 'JIRA', NULL, 'Business', 1, '2025-10-25 06:38:12', '2025-10-25 06:38:12'),
(51, 'Product Management', NULL, 'Business', 1, '2025-10-25 06:38:12', '2025-10-25 06:38:12'),
(52, 'UI Design', NULL, NULL, 1, '2025-10-25 06:38:18', '2025-10-25 06:38:18'),
(53, 'UX Research', NULL, NULL, 1, '2025-10-25 06:38:18', '2025-10-25 06:38:18'),
(54, 'Adobe Creative Suite', NULL, NULL, 1, '2025-10-25 06:38:18', '2025-10-25 06:38:18'),
(55, 'Prototyping', NULL, NULL, 1, '2025-10-25 06:38:18', '2025-10-25 06:38:18'),
(56, 'Django', NULL, NULL, 1, '2025-10-25 06:38:18', '2025-10-25 06:38:18'),
(57, 'API Development', NULL, NULL, 1, '2025-10-25 06:38:18', '2025-10-25 06:38:18'),
(58, 'HTML/CSS', NULL, NULL, 1, '2025-10-25 06:38:18', '2025-10-25 06:38:18'),
(59, 'Tailwind CSS', NULL, NULL, 1, '2025-10-25 06:38:18', '2025-10-25 06:38:18'),
(60, 'Responsive Design', NULL, NULL, 1, '2025-10-25 06:38:18', '2025-10-25 06:38:18'),
(61, 'React Native', NULL, NULL, 1, '2025-10-25 06:38:18', '2025-10-25 06:38:18'),
(62, 'Flutter', NULL, NULL, 1, '2025-10-25 06:38:18', '2025-10-25 06:38:18'),
(63, 'iOS Development', NULL, NULL, 1, '2025-10-25 06:38:18', '2025-10-25 06:38:18'),
(64, 'Android Development', NULL, NULL, 1, '2025-10-25 06:38:18', '2025-10-25 06:38:18'),
(65, 'Mobile UI/UX', NULL, NULL, 1, '2025-10-25 06:38:18', '2025-10-25 06:38:18');

-- --------------------------------------------------------

--
-- Table structure for table `subtasks`
--

CREATE TABLE `subtasks` (
  `id` bigint UNSIGNED NOT NULL,
  `task_id` bigint UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `is_completed` tinyint(1) NOT NULL DEFAULT '0',
  `completed_at` timestamp NULL DEFAULT NULL,
  `completed_by` bigint UNSIGNED DEFAULT NULL,
  `assigned_to` bigint UNSIGNED DEFAULT NULL,
  `due_date` date DEFAULT NULL,
  `sort_order` int NOT NULL DEFAULT '0',
  `created_by` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `subtasks`
--

INSERT INTO `subtasks` (`id`, `task_id`, `title`, `description`, `is_completed`, `completed_at`, `completed_by`, `assigned_to`, `due_date`, `sort_order`, `created_by`, `created_at`, `updated_at`, `deleted_at`) VALUES
(23, 25, 'Initialize FastAPI project with dependency management (Poetry/pip) and project structure', NULL, 0, NULL, NULL, NULL, NULL, 0, 1, '2025-12-06 05:38:04', '2025-12-06 05:38:04', NULL),
(24, 25, 'Configure PostgreSQL database with connection pooling (psycopg2/asyncpg)', NULL, 0, NULL, NULL, NULL, NULL, 1, 1, '2025-12-06 05:38:04', '2025-12-06 05:38:04', NULL),
(25, 25, 'Design and implement database schema for storing PR metadata, analysis results, and user feedback', NULL, 1, '2025-12-06 05:43:07', 1, NULL, NULL, 2, 1, '2025-12-06 05:38:04', '2025-12-06 05:43:07', NULL),
(26, 25, 'Implement JWT-based authentication and role-based access control (admin, reviewer, developer, viewer)', NULL, 0, NULL, NULL, NULL, NULL, 3, 1, '2025-12-06 05:38:04', '2025-12-06 05:38:04', NULL),
(27, 25, 'Create base API endpoints for health checks, version info, and status monitoring', NULL, 1, '2025-12-06 05:43:02', 1, NULL, NULL, 4, 1, '2025-12-06 05:38:04', '2025-12-06 05:43:02', NULL),
(28, 25, 'Set up Docker and docker-compose for local development environment', NULL, 1, '2025-12-06 05:43:01', 1, NULL, NULL, 5, 1, '2025-12-06 05:38:04', '2025-12-06 05:43:01', NULL),
(29, 25, 'Configure logging, error handling, and exception middleware', NULL, 1, '2025-12-06 05:42:59', 1, NULL, NULL, 6, 1, '2025-12-06 05:38:04', '2025-12-06 05:42:59', NULL),
(30, 25, 'Implement request validation using Pydantic models', NULL, 1, '2025-12-06 05:42:56', 1, NULL, NULL, 7, 1, '2025-12-06 05:38:04', '2025-12-06 05:42:56', NULL),
(31, 25, 'Set up basic rate limiting and request throttling', NULL, 1, '2025-12-06 05:41:09', 1, NULL, NULL, 8, 1, '2025-12-06 05:38:04', '2025-12-06 05:43:22', '2025-12-06 05:43:22'),
(32, 25, 'Create CI/CD pipeline stub (GitHub Actions) for automated testing and deployment', NULL, 1, '2025-12-06 05:38:32', 1, NULL, NULL, 9, 1, '2025-12-06 05:38:04', '2025-12-06 05:43:11', '2025-12-06 05:43:11'),
(33, 25, 'Set up basic rate limiting and request throttling', NULL, 0, NULL, NULL, NULL, NULL, 8, 1, '2025-12-06 05:43:44', '2025-12-06 05:43:44', NULL),
(34, 25, 'afamaksdma', NULL, 0, NULL, NULL, NULL, NULL, 9, 1, '2025-12-06 05:47:43', '2025-12-06 05:47:54', '2025-12-06 05:47:54'),
(35, 25, 'dasndaksdnak', NULL, 0, NULL, NULL, NULL, NULL, 10, 1, '2025-12-06 05:47:45', '2025-12-06 05:47:55', '2025-12-06 05:47:55'),
(36, 26, 'Implement HMAC-SHA256 webhook signature validation', NULL, 1, '2025-12-06 07:38:25', 8, NULL, NULL, 0, 1, '2025-12-06 07:33:32', '2025-12-06 07:38:25', NULL),
(37, 26, 'Create webhook endpoint to receive GitHub PR events (opened, synchronize, reopened)', NULL, 1, '2025-12-06 07:38:28', 8, NULL, NULL, 1, 1, '2025-12-06 07:33:32', '2025-12-06 07:38:28', NULL),
(38, 26, 'Implement GitHub OAuth 2.0 for user authentication and authorization', NULL, 1, '2025-12-06 07:39:10', 8, NULL, NULL, 2, 1, '2025-12-06 07:33:32', '2025-12-06 07:39:10', NULL),
(39, 26, 'Build GitHub API client wrapper with rate limit handling and exponential backoff', NULL, 1, '2025-12-06 07:38:39', 8, NULL, NULL, 3, 1, '2025-12-06 07:33:32', '2025-12-06 07:38:39', NULL),
(40, 26, 'Store GitHub installation tokens securely in database', NULL, 1, '2025-12-06 07:38:42', 8, NULL, NULL, 4, 1, '2025-12-06 07:33:32', '2025-12-06 07:38:42', NULL),
(41, 26, 'Implement PR cloning logic to fetch repository code and metadata', NULL, 1, '2025-12-06 07:38:45', 8, NULL, NULL, 5, 1, '2025-12-06 07:33:32', '2025-12-06 07:38:45', NULL),
(42, 26, 'Handle branch protection and permissions validation', NULL, 1, '2025-12-06 07:38:51', 8, NULL, NULL, 6, 1, '2025-12-06 07:33:32', '2025-12-06 07:38:51', NULL),
(43, 26, 'Create queue mechanism (Celery/RQ) for async PR analysis jobs', NULL, 1, '2025-12-06 07:38:54', 8, NULL, NULL, 7, 1, '2025-12-06 07:33:32', '2025-12-06 07:38:54', NULL),
(44, 26, 'Implement webhook retry logic and dead-letter queue for failed events', NULL, 1, '2025-12-06 07:38:56', 8, NULL, NULL, 8, 1, '2025-12-06 07:33:32', '2025-12-06 07:38:56', NULL),
(45, 26, 'Add support for GitHub check runs and status updates to PRs', NULL, 1, '2025-12-06 07:39:02', 8, NULL, NULL, 9, 1, '2025-12-06 07:33:32', '2025-12-06 07:39:02', NULL),
(46, 27, 'Implement Python AST parser for syntax tree analysis', NULL, 1, '2025-12-06 10:00:11', 8, NULL, NULL, 0, 1, '2025-12-06 07:45:46', '2025-12-06 10:00:11', NULL),
(47, 27, 'Create module for detecting common Python anti-patterns (e.g., mutable default arguments, bare excepts)', NULL, 1, '2025-12-06 10:00:15', 8, NULL, NULL, 1, 1, '2025-12-06 07:45:46', '2025-12-06 10:00:15', NULL),
(48, 27, 'Build complexity calculator (cyclomatic complexity, cognitive complexity)', NULL, 1, '2025-12-06 10:00:18', 8, NULL, NULL, 2, 1, '2025-12-06 07:45:46', '2025-12-06 10:00:18', NULL),
(49, 27, 'Create dead code detector (unused imports, unused functions, unreachable code)', NULL, 1, '2025-12-06 10:05:11', 8, NULL, NULL, 3, 1, '2025-12-06 07:45:46', '2025-12-06 10:05:11', NULL),
(50, 27, 'Implement security vulnerability scanner (SQL injection, hardcoded secrets, unsafe deserialization)', NULL, 1, '2025-12-06 10:05:13', 8, NULL, NULL, 4, 1, '2025-12-06 07:45:46', '2025-12-06 10:05:13', NULL),
(51, 27, 'Build type hint analyzer to suggest missing type annotations', NULL, 1, '2025-12-06 10:05:16', 8, NULL, NULL, 5, 1, '2025-12-06 07:45:46', '2025-12-06 10:05:16', NULL),
(52, 27, 'Implement dependency analyzer to track import cycles and unused dependencies', NULL, 1, '2025-12-06 10:05:18', 8, NULL, NULL, 6, 1, '2025-12-06 07:45:46', '2025-12-06 10:05:18', NULL),
(53, 27, 'Create performance profiler using cProfile integration for hotspot detection', NULL, 1, '2025-12-06 10:05:20', 8, NULL, NULL, 7, 1, '2025-12-06 07:45:46', '2025-12-06 10:05:20', NULL),
(54, 27, 'Build docstring quality checker (coverage, format compliance)', NULL, 1, '2025-12-06 10:05:22', 8, NULL, NULL, 8, 1, '2025-12-06 07:45:46', '2025-12-06 10:05:22', NULL),
(55, 27, 'Implement refactoring suggestion engine for common Python patterns', NULL, 1, '2025-12-06 10:11:54', 8, NULL, NULL, 9, 1, '2025-12-06 07:45:46', '2025-12-06 10:11:54', NULL),
(56, 28, 'Implement JavaScript/TypeScript AST parser using Babel or TypeScript compiler API', NULL, 1, '2025-12-06 10:26:07', 7, NULL, NULL, 0, 1, '2025-12-06 09:50:34', '2025-12-06 10:26:07', NULL),
(57, 28, 'Create module for detecting common JS/TS anti-patterns (var usage, implicit type coercions, etc.)', NULL, 1, '2025-12-06 10:26:08', 7, NULL, NULL, 1, 1, '2025-12-06 09:50:34', '2025-12-06 10:26:08', NULL),
(58, 28, 'Build complexity calculator compatible with ES6+ syntax', NULL, 1, '2025-12-06 10:26:11', 7, NULL, NULL, 2, 1, '2025-12-06 09:50:34', '2025-12-06 10:26:11', NULL),
(59, 28, 'Implement security scanner (XSS vulnerabilities, insecure dependencies, prototype pollution)', NULL, 1, '2025-12-06 10:26:12', 7, NULL, NULL, 3, 1, '2025-12-06 09:50:34', '2025-12-06 10:26:12', NULL),
(60, 28, 'Create unused code detector (tree-shaking compatible)', NULL, 1, '2025-12-06 10:26:16', 7, NULL, NULL, 4, 1, '2025-12-06 09:50:34', '2025-12-06 10:26:16', NULL),
(61, 28, 'Build type safety analyzer for TypeScript type annotations coverage', NULL, 1, '2025-12-06 10:26:17', 7, NULL, NULL, 5, 1, '2025-12-06 09:50:34', '2025-12-06 10:26:17', NULL),
(62, 28, 'Implement performance issue detector (bundle size impact, memory leaks)', NULL, 1, '2025-12-06 10:26:18', 7, NULL, NULL, 6, 1, '2025-12-06 09:50:34', '2025-12-06 10:26:18', NULL),
(63, 28, 'Create accessibility checker for React/Vue components', NULL, 1, '2025-12-06 10:26:19', 7, NULL, NULL, 7, 1, '2025-12-06 09:50:34', '2025-12-06 10:26:19', NULL),
(64, 28, 'Build async/await and Promise chain analyzer', NULL, 1, '2025-12-06 10:26:20', 7, NULL, NULL, 8, 1, '2025-12-06 09:50:34', '2025-12-06 10:26:20', NULL),
(65, 28, 'Implement linting integration with ESLint and custom rule support', NULL, 1, '2025-12-06 10:26:22', 7, NULL, NULL, 9, 1, '2025-12-06 09:50:34', '2025-12-06 10:26:22', NULL),
(66, 29, 'Create issue taxonomy (bug risk, performance, maintainability, security, style)', NULL, 0, NULL, NULL, NULL, NULL, 0, 1, '2025-12-06 10:24:57', '2025-12-06 10:24:57', NULL),
(67, 29, 'Build severity classifier (critical, high, medium, low, info)', NULL, 0, NULL, NULL, NULL, NULL, 1, 1, '2025-12-06 10:24:57', '2025-12-06 10:24:57', NULL),
(68, 29, 'Implement explanation template system for different issue types', NULL, 0, NULL, NULL, NULL, NULL, 2, 1, '2025-12-06 10:24:57', '2025-12-06 10:24:57', NULL),
(69, 29, 'Create suggestion generator with code examples and before/after comparisons', NULL, 0, NULL, NULL, NULL, NULL, 3, 1, '2025-12-06 10:24:57', '2025-12-06 10:24:57', NULL),
(70, 29, 'Build link generator to documentation and learning resources', NULL, 0, NULL, NULL, NULL, NULL, 4, 1, '2025-12-06 10:24:57', '2025-12-06 10:24:57', NULL),
(71, 29, 'Implement confidence scoring for each suggestion', NULL, 0, NULL, NULL, NULL, NULL, 5, 1, '2025-12-06 10:24:57', '2025-12-06 10:24:57', NULL),
(72, 29, 'Create issue deduplication logic to avoid duplicate feedback', NULL, 0, NULL, NULL, NULL, NULL, 6, 1, '2025-12-06 10:24:57', '2025-12-06 10:24:57', NULL),
(73, 29, 'Build custom rule support via YAML configuration', NULL, 0, NULL, NULL, NULL, NULL, 7, 1, '2025-12-06 10:24:57', '2025-12-06 10:24:57', NULL),
(74, 29, 'Implement team-specific rule customization', NULL, 0, NULL, NULL, NULL, NULL, 8, 1, '2025-12-06 10:24:57', '2025-12-06 10:24:57', NULL),
(75, 29, 'Create feedback loop mechanism to track which suggestions were accepted/rejected', NULL, 0, NULL, NULL, NULL, NULL, 9, 1, '2025-12-06 10:24:57', '2025-12-06 10:24:57', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tasks`
--

CREATE TABLE `tasks` (
  `taskID` bigint UNSIGNED NOT NULL,
  `project_id` bigint UNSIGNED NOT NULL,
  `phase_id` bigint UNSIGNED DEFAULT NULL,
  `assignedTo` bigint UNSIGNED DEFAULT NULL,
  `createdBy` bigint UNSIGNED NOT NULL,
  `client_id` bigint UNSIGNED NOT NULL,
  `taskTitle` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `taskDescription` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('pending','in_progress','completed','cancelled','pending_approval') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `is_scheduled` tinyint(1) NOT NULL DEFAULT '0',
  `priority` enum('low','medium','high','urgent') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'medium',
  `sort_order` int UNSIGNED NOT NULL DEFAULT '0' COMMENT 'Display order for task reordering within a project',
  `deadline` timestamp NULL DEFAULT NULL,
  `dateAssigned` timestamp NOT NULL DEFAULT '2025-10-29 13:19:05',
  `completedAt` timestamp NULL DEFAULT NULL,
  `allocated_budget` decimal(10,2) DEFAULT NULL,
  `estimated_hours` int DEFAULT NULL,
  `hourly_rate` decimal(8,2) DEFAULT NULL,
  `requires_time_tracking` tinyint(1) NOT NULL DEFAULT '0',
  `total_hours_tracked` decimal(8,2) NOT NULL DEFAULT '0.00',
  `calculated_earnings` decimal(10,2) NOT NULL DEFAULT '0.00',
  `use_fixed_budget` tinyint(1) NOT NULL DEFAULT '0',
  `actual_cost` decimal(10,2) DEFAULT NULL,
  `progress_percentage` int NOT NULL DEFAULT '0',
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `completion_notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `service_request_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tasks`
--

INSERT INTO `tasks` (`taskID`, `project_id`, `phase_id`, `assignedTo`, `createdBy`, `client_id`, `taskTitle`, `taskDescription`, `status`, `is_scheduled`, `priority`, `sort_order`, `deadline`, `dateAssigned`, `completedAt`, `allocated_budget`, `estimated_hours`, `hourly_rate`, `requires_time_tracking`, `total_hours_tracked`, `calculated_earnings`, `use_fixed_budget`, `actual_cost`, `progress_percentage`, `notes`, `completion_notes`, `service_request_id`, `created_at`, `updated_at`) VALUES
(1, 1, NULL, 9, 2, 4, 'Project Requirements Analysis', 'Analyze and document project requirements in detail, including stakeholder interviews, scope definition, and technical specifications.', 'completed', 0, 'high', 1, '2025-10-30 06:38:18', '2025-10-23 06:38:18', '2025-10-21 06:38:18', 650.00, NULL, NULL, 0, 0.00, 0.00, 0, 643.50, 100, 'Requirements documentation completed with stakeholder approval', NULL, 2, '2025-10-24 07:38:18', '2025-10-25 06:38:18'),
(2, 1, NULL, 7, 2, 4, 'Implementation & Development', 'Main development work for the project including coding, feature implementation, and integration with required systems.', 'in_progress', 0, 'high', 2, '2025-11-24 06:38:18', '2025-10-23 06:38:18', NULL, 1950.00, NULL, NULL, 0, 0.00, 0.00, 0, 1267.50, 74, 'Core development in progress with regular client updates', NULL, 2, '2025-10-25 12:38:18', '2025-10-25 06:38:18'),
(3, 1, NULL, 7, 1, 4, 'Testing & Quality Assurance', 'Comprehensive testing including unit tests, integration tests, and end-to-end quality assurance to ensure project meets requirements.', 'in_progress', 0, 'medium', 3, '2025-11-29 06:38:18', '2025-10-23 06:38:18', NULL, 650.00, NULL, NULL, 0, 0.00, 0.00, 0, 0.00, 0, 'QA testing planned after development completion', NULL, 2, '2025-10-25 09:38:18', '2025-10-25 06:38:18'),
(4, 2, NULL, 10, 2, 6, 'Project Requirements Analysis', 'Analyze and document project requirements in detail, including stakeholder interviews, scope definition, and technical specifications.', 'completed', 0, 'high', 1, '2025-10-30 06:38:18', '2025-10-20 06:38:18', '2025-10-24 06:38:18', 800.00, NULL, NULL, 0, 0.00, 0.00, 0, 792.00, 100, 'Requirements documentation completed with stakeholder approval', NULL, 4, '2025-10-27 14:38:18', '2025-10-25 06:38:18'),
(5, 2, NULL, 7, 2, 6, 'Implementation & Development', 'Main development work for the project including coding, feature implementation, and integration with required systems.', 'in_progress', 0, 'high', 2, '2025-11-24 06:38:18', '2025-10-20 06:38:18', NULL, 2400.00, NULL, NULL, 0, 0.00, 0.00, 0, 1248.00, 67, 'Core development in progress with regular client updates', NULL, 4, '2025-10-27 04:38:18', '2025-10-25 06:38:18'),
(6, 2, NULL, 8, 2, 6, 'Testing & Quality Assurance', 'Comprehensive testing including unit tests, integration tests, and end-to-end quality assurance to ensure project meets requirements.', 'in_progress', 0, 'medium', 3, '2025-11-29 06:38:18', '2025-10-20 06:38:18', NULL, 800.00, NULL, NULL, 0, 0.00, 0.00, 0, 160.00, 25, 'QA testing planned after development completion', NULL, 4, '2025-10-25 14:38:18', '2025-10-25 06:38:18'),
(7, 3, NULL, 10, 2, 5, 'Project Requirements Analysis', 'Analyze and document project requirements in detail, including stakeholder interviews, scope definition, and technical specifications.', 'completed', 0, 'high', 1, '2025-10-30 06:38:18', '2025-10-20 06:38:18', '2025-10-22 06:38:18', 2000.00, NULL, NULL, 0, 0.00, 0.00, 0, 1940.00, 100, 'Requirements documentation completed with stakeholder approval', NULL, 7, '2025-10-26 05:38:18', '2025-10-25 06:38:18'),
(8, 3, NULL, 10, 1, 5, 'Implementation & Development', 'Main development work for the project including coding, feature implementation, and integration with required systems.', 'completed', 0, 'high', 2, '2025-11-24 06:38:18', '2025-10-20 06:38:18', '2025-10-21 06:38:18', 6000.00, NULL, NULL, 0, 0.00, 0.00, 0, 4200.00, 65, 'Core development in progress with regular client updates', NULL, 7, '2025-10-24 04:38:18', '2025-10-25 06:38:18'),
(9, 3, NULL, 7, 2, 5, 'Testing & Quality Assurance', 'Comprehensive testing including unit tests, integration tests, and end-to-end quality assurance to ensure project meets requirements.', 'in_progress', 0, 'medium', 3, '2025-11-29 06:38:18', '2025-10-20 06:38:18', NULL, 2000.00, NULL, NULL, 0, 0.00, 0.00, 0, 0.00, 85, 'QA testing planned after development completion', NULL, 7, '2025-10-25 06:38:18', '2025-10-29 18:30:01'),
(10, 4, NULL, 11, 1, 5, 'Project Requirements Analysis', 'Analyze and document project requirements in detail, including stakeholder interviews, scope definition, and technical specifications.', 'completed', 0, 'high', 1, '2025-10-30 06:38:18', '2025-10-23 06:38:18', '2025-10-23 06:38:18', 130.00, NULL, NULL, 0, 0.00, 0.00, 0, 137.80, 100, 'Requirements documentation completed with stakeholder approval', NULL, 10, '2025-10-25 03:38:18', '2025-10-25 06:38:18'),
(11, 4, NULL, 9, 2, 5, 'Implementation & Development', 'Main development work for the project including coding, feature implementation, and integration with required systems.', 'in_progress', 0, 'high', 2, '2025-11-24 06:38:18', '2025-10-23 06:38:18', NULL, 390.00, NULL, NULL, 0, 0.00, 0.00, 0, 413.40, 51, 'Core development in progress with regular client updates', NULL, 10, '2025-10-25 23:38:18', '2025-10-25 06:38:18'),
(12, 4, NULL, 9, 2, 5, 'Testing & Quality Assurance', 'Comprehensive testing including unit tests, integration tests, and end-to-end quality assurance to ensure project meets requirements.', 'pending', 0, 'medium', 3, '2025-11-29 06:38:18', '2025-10-23 06:38:18', NULL, 130.00, NULL, NULL, 0, 0.00, 0.00, 0, 0.00, 45, 'QA testing planned after development completion', NULL, 10, '2025-10-25 08:38:18', '2025-10-25 06:38:18'),
(13, 5, 2, 7, 1, 12, 'TASK 1', 'TASK 1', 'completed', 0, 'medium', 1, NULL, '2025-10-25 06:49:50', '2025-10-25 07:08:49', 5000.00, NULL, NULL, 0, 0.00, 0.00, 0, NULL, 0, NULL, NULL, NULL, '2025-10-25 06:49:50', '2025-10-25 07:08:49'),
(14, 5, 2, 7, 1, 12, 'TASK 2', 'TASK 2', 'completed', 0, 'medium', 2, NULL, '2025-10-25 07:09:45', '2025-10-25 07:13:26', 25000.00, NULL, NULL, 0, 0.00, 0.00, 0, NULL, 0, NULL, NULL, NULL, '2025-10-25 07:09:45', '2025-10-25 07:13:26'),
(15, 6, 4, 7, 1, 23, 'Task 1', 'Task 1 description', 'completed', 0, 'medium', 1, '2025-10-31 00:00:00', '2025-10-29 00:01:02', '2025-10-29 00:08:20', NULL, NULL, NULL, 0, 0.00, 0.00, 0, NULL, 0, NULL, NULL, NULL, '2025-10-29 00:01:02', '2025-10-29 00:08:20'),
(16, 7, 8, 7, 1, 25, 'Task 1', 'Task 1 desc', 'completed', 0, 'medium', 1, '2025-12-31 00:00:00', '2025-10-29 08:48:46', '2025-10-29 08:50:45', NULL, NULL, NULL, 0, 0.00, 0.00, 0, NULL, 0, NULL, NULL, NULL, '2025-10-29 08:48:46', '2025-10-29 08:50:45'),
(17, 18, NULL, 34, 1, 3, 'Develop Ecommerce', 'Make a good ecommerce website', 'pending', 0, 'medium', 1, '2025-11-20 00:00:00', '2025-11-16 11:17:05', NULL, 5000.00, NULL, NULL, 0, 0.00, 0.00, 1, NULL, 0, NULL, NULL, NULL, '2025-11-16 11:17:05', '2025-11-16 11:17:05'),
(18, 16, NULL, 34, 1, 32, 'Mobile Game Elements', 'Create the surrounding elements for the mobile game', 'pending', 0, 'medium', 1, '2025-11-27 00:00:00', '2025-11-17 10:27:25', NULL, 5000.00, NULL, NULL, 0, 0.00, 0.00, 1, NULL, 0, NULL, NULL, NULL, '2025-11-17 10:27:25', '2025-11-17 10:27:25'),
(19, 17, NULL, 34, 1, 32, 'Graphics Designing', 'Design', 'pending', 0, 'medium', 1, '2025-11-20 00:00:00', '2025-11-18 14:50:06', NULL, 2000.00, NULL, NULL, 1, 0.00, 0.00, 0, NULL, 0, NULL, NULL, NULL, '2025-11-18 14:50:06', '2025-11-18 14:50:06'),
(20, 20, NULL, 10, 1, 18, 'Aperiam nisi repelle', 'Nam est magna quis', 'completed', 0, 'high', 1, NULL, '2025-12-01 12:18:38', NULL, 500.00, NULL, NULL, 0, 0.00, 0.00, 0, NULL, 0, 'Irure exercitationem', NULL, NULL, '2025-12-01 12:18:38', '2025-12-01 12:18:38'),
(21, 19, NULL, 34, 1, 18, 'Expedita labore sit', 'Sed fugiat voluptate', 'pending', 0, 'high', 1, '2026-09-19 00:00:00', '2025-12-01 12:51:49', NULL, 55.00, NULL, NULL, 0, 0.00, 0.00, 0, NULL, 0, 'Rerum reiciendis cul', NULL, NULL, '2025-12-01 12:51:49', '2025-12-01 12:51:49'),
(22, 19, NULL, 7, 1, 18, 'Laboriosam similiqu', 'Dolor cum reprehende', 'completed', 0, 'urgent', 2, '2025-12-23 00:00:00', '2025-12-01 12:53:38', NULL, 500.00, NULL, NULL, 0, 0.00, 0.00, 0, NULL, 0, 'Voluptatum tenetur t', NULL, NULL, '2025-12-01 12:53:38', '2025-12-01 12:53:38'),
(25, 24, NULL, 7, 1, 3, 'Backend Infrastructure & API Foundation', 'Set up core FastAPI application, database schema, authentication, and API scaffolding. Establish project structure, environment configuration, and deployment pipeline basics', 'completed', 0, 'medium', 0, NULL, '2025-12-06 06:56:51', '2025-12-06 00:00:00', NULL, NULL, NULL, 0, 0.00, 0.00, 0, NULL, 56, NULL, NULL, NULL, '2025-12-06 05:38:04', '2025-12-06 10:25:18'),
(26, 24, NULL, 8, 1, 3, 'GitHub Integration & Webhook Handler', 'Build webhook receiver to handle GitHub PR events, implement secure webhook validation, and establish two-way communication with GitHub API.', 'completed', 0, 'medium', 0, '2025-12-15 00:00:00', '2025-12-06 07:33:32', '2025-12-06 07:39:11', 5000.00, NULL, NULL, 0, 0.00, 0.00, 0, NULL, 100, NULL, NULL, NULL, '2025-12-06 07:33:32', '2025-12-06 07:39:11'),
(27, 24, NULL, 8, 1, 3, 'Code Analysis Engine - Python Support', 'Build Python-specific code analyzer using AST parsing to detect code quality issues, vulnerabilities, and architectural patterns.', 'completed', 0, 'medium', 0, NULL, '2025-12-06 07:45:46', '2025-12-06 10:11:54', 8000.00, NULL, NULL, 0, 0.00, 0.00, 0, NULL, 100, NULL, NULL, NULL, '2025-12-06 07:45:46', '2025-12-06 10:11:54'),
(28, 24, NULL, 7, 1, 3, 'Code Analysis Engine - JavaScript/TypeScript Support', 'Build JavaScript/TypeScript code analyzer using AST and ESLint integration for detecting issues and best practice violations.', 'in_progress', 0, 'medium', 0, NULL, '2025-12-06 09:50:34', '2025-12-06 10:31:44', NULL, NULL, NULL, 0, 0.00, 0.00, 0, NULL, 100, NULL, NULL, NULL, '2025-12-06 09:50:34', '2025-12-06 11:02:13'),
(29, 24, NULL, 8, 1, 3, 'Issue Classification & Explanation Generation', 'Categorize detected issues by severity/type and generate human-readable explanations with actionable suggestions.', 'pending', 0, 'medium', 0, NULL, '2025-12-06 10:24:57', NULL, NULL, NULL, NULL, 0, 0.00, 0.00, 0, NULL, 0, NULL, NULL, NULL, '2025-12-06 10:24:57', '2025-12-06 10:24:57');

-- --------------------------------------------------------

--
-- Table structure for table `task_deliverables`
--

CREATE TABLE `task_deliverables` (
  `id` bigint UNSIGNED NOT NULL,
  `task_id` bigint UNSIGNED NOT NULL,
  `type` enum('file','image','link') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'file',
  `document_id` bigint UNSIGNED DEFAULT NULL,
  `link_url` varchar(2048) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `is_approved` tinyint(1) NOT NULL DEFAULT '0',
  `approved_by` bigint UNSIGNED DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `uploaded_by` bigint UNSIGNED NOT NULL,
  `sort_order` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `task_schedules`
--

CREATE TABLE `task_schedules` (
  `id` bigint UNSIGNED NOT NULL,
  `task_id` bigint UNSIGNED NOT NULL,
  `adiutor_id` bigint UNSIGNED NOT NULL,
  `scheduled_start` datetime NOT NULL,
  `scheduled_end` datetime NOT NULL,
  `estimated_duration_minutes` int NOT NULL,
  `schedule_type` enum('manual','auto') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'manual',
  `google_calendar_event_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `calendar_synced_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `task_schedules`
--

INSERT INTO `task_schedules` (`id`, `task_id`, `adiutor_id`, `scheduled_start`, `scheduled_end`, `estimated_duration_minutes`, `schedule_type`, `google_calendar_event_id`, `calendar_synced_at`, `created_at`, `updated_at`) VALUES
(1, 17, 34, '2025-11-20 09:00:00', '2025-11-20 17:00:00', 480, 'manual', NULL, NULL, '2025-11-17 10:11:26', '2025-11-17 10:39:42'),
(2, 18, 34, '2025-11-27 09:00:00', '2025-11-27 17:00:00', 3360, 'manual', 'c1cq6ok3bm5scjcjuuhqlk6cf4', NULL, '2025-11-17 10:28:06', '2025-11-17 10:39:42'),
(3, 19, 34, '2025-11-20 09:00:00', '2025-11-21 17:00:00', 1920, 'manual', 'ad49rqoq74shnknv5682knn7e0', NULL, '2025-11-18 14:52:00', '2025-11-18 14:52:02'),
(4, 5, 7, '2025-11-24 09:00:00', '2025-11-24 17:00:00', 480, 'auto', NULL, NULL, '2025-11-21 10:07:30', '2025-11-21 10:07:30'),
(5, 6, 8, '2025-11-29 09:00:00', '2025-11-29 17:00:00', 480, 'auto', NULL, NULL, '2025-11-21 10:07:30', '2025-11-21 10:07:30'),
(6, 4, 10, '2025-10-30 09:00:00', '2025-10-30 17:00:00', 480, 'auto', NULL, NULL, '2025-11-21 10:07:31', '2025-11-21 10:07:31'),
(7, 15, 7, '2025-10-31 09:00:00', '2025-10-31 17:00:00', 480, 'auto', NULL, NULL, '2025-11-21 10:27:28', '2025-11-21 10:27:28');

-- --------------------------------------------------------

--
-- Table structure for table `tech_stack`
--

CREATE TABLE `tech_stack` (
  `id` bigint UNSIGNED NOT NULL,
  `category` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `item_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `domain` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image_url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tech_stack`
--

INSERT INTO `tech_stack` (`id`, `category`, `item_name`, `domain`, `image_url`, `created_at`, `updated_at`) VALUES
(205, 'Programming Languages', 'C', '', 'https://upload.wikimedia.org/wikipedia/commons/1/19/C_Logo.png?20201023095457', '2025-10-29 22:47:25', '2025-10-29 22:47:25'),
(206, 'Programming Languages', 'C#', '', 'https://upload.wikimedia.org/wikipedia/commons/thumb/4/4f/Csharp_Logo.png/640px-Csharp_Logo.png', '2025-10-29 22:47:25', '2025-10-29 22:47:25'),
(207, 'Programming Languages', 'C++', '', 'https://qzdtlrbpjudrvffrnory.supabase.co/storage/v1/object/public/Treis%20Adiutor/LOGOS/c++lang.png', '2025-10-29 22:47:25', '2025-10-29 22:47:25'),
(208, 'Programming Languages', 'Python', 'python.org', 'https://qzdtlrbpjudrvffrnory.supabase.co/storage/v1/object/public/Treis%20Adiutor/LOGOS/pythonlang.png', '2025-10-29 22:47:25', '2025-10-29 22:47:25'),
(209, 'Programming Languages', 'Java', 'java.com', 'https://qzdtlrbpjudrvffrnory.supabase.co/storage/v1/object/public/Treis%20Adiutor/LOGOS/java.png', '2025-10-29 22:47:25', '2025-10-29 22:47:25'),
(210, 'Programming Languages', 'PHP', 'php.net', 'https://qzdtlrbpjudrvffrnory.supabase.co/storage/v1/object/public/Treis%20Adiutor/LOGOS/php.png', '2025-10-29 22:47:25', '2025-10-29 22:47:25'),
(211, 'Programming Languages', 'JavaScript', '', 'https://upload.wikimedia.org/wikipedia/commons/thumb/b/b9/JavaScript_shield_logo_%28no_text%29.svg/640px-JavaScript_shield_logo_%28no_text%29.svg.png', '2025-10-29 22:47:26', '2025-10-29 22:47:26'),
(212, 'Programming Languages', 'TypeScript', 'typescriptlang.org', 'https://qzdtlrbpjudrvffrnory.supabase.co/storage/v1/object/public/Treis%20Adiutor/LOGOS/typescript.png', '2025-10-29 22:47:26', '2025-10-29 22:47:26'),
(213, 'Frontend Frameworks & Tools', 'HTML', '', 'https://www.w3.org/html/logo/img/mark-only-icon.png', '2025-10-29 22:47:26', '2025-10-29 22:47:26'),
(214, 'Frontend Frameworks & Tools', 'CSS', '', 'https://upload.wikimedia.org/wikipedia/commons/thumb/a/ab/Official_CSS_Logo.svg/960px-Official_CSS_Logo.svg.png?20250115194431', '2025-10-29 22:47:26', '2025-10-29 22:47:26'),
(215, 'Frontend Frameworks & Tools', 'React.js', 'react.dev', 'https://qzdtlrbpjudrvffrnory.supabase.co/storage/v1/object/public/Treis%20Adiutor/LOGOS/react.png', '2025-10-29 22:47:26', '2025-10-29 22:47:26'),
(216, 'Frontend Frameworks & Tools', 'Vue.js', 'vuejs.org', 'https://qzdtlrbpjudrvffrnory.supabase.co/storage/v1/object/public/Treis%20Adiutor/LOGOS/vuejs.png', '2025-10-29 22:47:26', '2025-10-29 22:47:26'),
(217, 'Frontend Frameworks & Tools', 'Bootstrap', 'getbootstrap.com', 'https://qzdtlrbpjudrvffrnory.supabase.co/storage/v1/object/public/Treis%20Adiutor/LOGOS/bootstrap.png', '2025-10-29 22:47:26', '2025-10-29 22:47:26'),
(218, 'Frontend Frameworks & Tools', 'Tailwind CSS', 'tailwindcss.com', 'https://qzdtlrbpjudrvffrnory.supabase.co/storage/v1/object/public/Treis%20Adiutor/LOGOS/tailwind.png', '2025-10-29 22:47:26', '2025-10-29 22:47:26'),
(219, 'Backend Frameworks & Platforms', 'Flask', 'flask.palletsprojects.com', 'https://qzdtlrbpjudrvffrnory.supabase.co/storage/v1/object/public/Treis%20Adiutor/LOGOS/flaskpy.png', '2025-10-29 22:47:27', '2025-10-29 22:47:27'),
(220, 'Backend Frameworks & Platforms', 'Django', 'djangoproject.com', 'https://qzdtlrbpjudrvffrnory.supabase.co/storage/v1/object/public/Treis%20Adiutor/LOGOS/django.png', '2025-10-29 22:47:27', '2025-10-29 22:47:27'),
(221, 'Backend Frameworks & Platforms', 'Express.js', 'expressjs.com', 'https://qzdtlrbpjudrvffrnory.supabase.co/storage/v1/object/public/Treis%20Adiutor/LOGOS/express.png', '2025-10-29 22:47:27', '2025-10-29 22:47:27'),
(222, 'Backend Frameworks & Platforms', 'Next.js', 'nextjs.org', 'https://qzdtlrbpjudrvffrnory.supabase.co/storage/v1/object/public/Treis%20Adiutor/LOGOS/nextjs.png', '2025-10-29 22:47:27', '2025-10-29 22:47:27'),
(223, 'Backend Frameworks & Platforms', 'Node.js', 'nodejs.org', 'https://qzdtlrbpjudrvffrnory.supabase.co/storage/v1/object/public/Treis%20Adiutor/LOGOS/nodejs.png', '2025-10-29 22:47:27', '2025-10-29 22:47:27'),
(224, 'Backend Frameworks & Platforms', 'Laravel', 'laravel.com', 'https://qzdtlrbpjudrvffrnory.supabase.co/storage/v1/object/public/Treis%20Adiutor/LOGOS/laravel.png', '2025-10-29 22:47:27', '2025-10-29 22:47:27'),
(226, 'Databases & Database Management', 'MySQL', 'mysql.com', 'https://qzdtlrbpjudrvffrnory.supabase.co/storage/v1/object/public/Treis%20Adiutor/LOGOS/mysql.png', '2025-10-29 22:47:27', '2025-10-29 22:47:27'),
(227, 'Databases & Database Management', 'PostgreSQL', 'postgresql.org', 'https://qzdtlrbpjudrvffrnory.supabase.co/storage/v1/object/public/Treis%20Adiutor/LOGOS/postgresql.png', '2025-10-29 22:47:27', '2025-10-29 22:47:27'),
(228, 'Databases & Database Management', 'MongoDB', 'mongodb.com', 'https://qzdtlrbpjudrvffrnory.supabase.co/storage/v1/object/public/Treis%20Adiutor/LOGOS/mongodb.png', '2025-10-29 22:47:28', '2025-10-29 22:47:28'),
(229, 'Databases & Database Management', 'Firebase', '', 'https://upload.wikimedia.org/wikipedia/commons/thumb/8/85/Firebase.svg/640px-Firebase.svg.png', '2025-10-29 22:47:28', '2025-10-29 22:47:28'),
(230, 'Databases & Database Management', 'SQLite', 'sqlite.org', 'https://qzdtlrbpjudrvffrnory.supabase.co/storage/v1/object/public/Treis%20Adiutor/LOGOS/sqlite.png', '2025-10-29 22:47:28', '2025-10-29 22:47:28'),
(231, 'Version Control & Collaboration', 'Git', 'git-scm.com', 'https://qzdtlrbpjudrvffrnory.supabase.co/storage/v1/object/public/Treis%20Adiutor/LOGOS/git.png', '2025-10-29 22:47:28', '2025-10-29 22:47:28'),
(232, 'Version Control & Collaboration', 'GitHub', 'github.com', 'https://qzdtlrbpjudrvffrnory.supabase.co/storage/v1/object/public/Treis%20Adiutor/LOGOS/github.png', '2025-10-29 22:47:28', '2025-10-29 22:47:28'),
(233, 'Version Control & Collaboration', 'GitLab', 'gitlab.com', 'https://qzdtlrbpjudrvffrnory.supabase.co/storage/v1/object/public/Treis%20Adiutor/LOGOS/gitlab.png', '2025-10-29 22:47:28', '2025-10-29 22:47:28'),
(234, 'DevOps & Cloud', 'Docker', 'docker.com', 'https://qzdtlrbpjudrvffrnory.supabase.co/storage/v1/object/public/Treis%20Adiutor/LOGOS/docker.png', '2025-10-29 22:47:29', '2025-10-29 22:47:29'),
(235, 'DevOps & Cloud', 'AWS (Amazon Web Services)', 'aws.amazon.com', 'https://qzdtlrbpjudrvffrnory.supabase.co/storage/v1/object/public/Treis%20Adiutor/LOGOS/aws.png', '2025-10-29 22:47:29', '2025-10-29 22:47:29'),
(236, 'DevOps & Cloud', 'GCP (Google Cloud Platform)', 'cloud.google.com', 'https://qzdtlrbpjudrvffrnory.supabase.co/storage/v1/object/public/Treis%20Adiutor/LOGOS/gcp.png', '2025-10-29 22:47:29', '2025-10-29 22:47:29'),
(237, 'DevOps & Cloud', 'Azure (Microsoft Cloud Platform)', 'azure.microsoft.com', 'https://qzdtlrbpjudrvffrnory.supabase.co/storage/v1/object/public/Treis%20Adiutor/LOGOS/azure.png', '2025-10-29 22:47:29', '2025-10-29 22:47:29'),
(238, 'Testing & CI/CD', 'Cypress', 'cypress.io', 'https://qzdtlrbpjudrvffrnory.supabase.co/storage/v1/object/public/Treis%20Adiutor/LOGOS/cypress.png', '2025-10-29 22:47:29', '2025-10-29 22:47:29'),
(239, 'Testing & CI/CD', 'Selenium', 'selenium.dev', 'https://qzdtlrbpjudrvffrnory.supabase.co/storage/v1/object/public/Treis%20Adiutor/LOGOS/selenium.png', '2025-10-29 22:47:29', '2025-10-29 22:47:29'),
(240, 'Testing & CI/CD', 'Jenkins', 'jenkins.io', 'https://qzdtlrbpjudrvffrnory.supabase.co/storage/v1/object/public/Treis%20Adiutor/LOGOS/jenkins.png', '2025-10-29 22:47:29', '2025-10-29 22:47:29'),
(241, 'Testing & CI/CD', 'GitHub Actions', 'github.com', 'https://qzdtlrbpjudrvffrnory.supabase.co/storage/v1/object/public/Treis%20Adiutor/LOGOS/github-actions.png', '2025-10-29 22:47:29', '2025-10-29 22:47:29'),
(242, 'Productivity Tools', 'Microsoft Word', '', 'https://upload.wikimedia.org/wikipedia/commons/thumb/e/e8/Microsoft_Office_Word_%282025%E2%80%93present%29.svg/486px-Microsoft_Office_Word_%282025%E2%80%93present%29.svg.png?20251005112124', '2025-10-29 22:47:30', '2025-10-29 22:47:30'),
(243, 'Productivity Tools', 'Google Docs', '', 'https://upload.wikimedia.org/wikipedia/commons/thumb/c/c2/Google_Forms_logo_%282014-2020%29.svg/48px-Google_Forms_logo_%282014-2020%29.svg.png?20201024102621', '2025-10-29 22:47:30', '2025-10-29 22:47:30'),
(244, 'Productivity Tools', 'Microsoft 365', 'microsoft.com', 'https://qzdtlrbpjudrvffrnory.supabase.co/storage/v1/object/public/Treis%20Adiutor/LOGOS/excel.png', '2025-10-29 22:47:30', '2025-10-29 22:47:30'),
(245, 'Productivity Tools', 'Google Sheets', 'id6O2oGzv-', 'https://qzdtlrbpjudrvffrnory.supabase.co/storage/v1/object/public/Treis%20Adiutor/LOGOS/docs.png', '2025-10-29 22:47:30', '2025-10-29 22:47:30'),
(246, 'Productivity Tools', 'Microsoft Powerpoint', '', 'https://upload.wikimedia.org/wikipedia/commons/thumb/3/3b/Microsoft_PowerPoint_Logo.png/600px-Microsoft_PowerPoint_Logo.png?20190416103326', '2025-10-29 22:47:30', '2025-10-29 22:47:30'),
(247, 'Design & Multimedia Tools', 'Figma', 'figma.com', 'https://qzdtlrbpjudrvffrnory.supabase.co/storage/v1/object/public/Treis%20Adiutor/LOGOS/figma.png', '2025-10-29 22:47:30', '2025-10-29 22:47:30'),
(248, 'Design & Multimedia Tools', 'Canva', 'canva.com', 'https://qzdtlrbpjudrvffrnory.supabase.co/storage/v1/object/public/Treis%20Adiutor/LOGOS/canva.png', '2025-10-29 22:47:30', '2025-10-29 22:47:30'),
(249, 'Design & Multimedia Tools', 'Adobe Fuse', '', 'https://upload.wikimedia.org/wikipedia/commons/thumb/9/93/Adobe_Fuse_Logo.svg/512px-Adobe_Fuse_Logo.svg.png?20200111020727', '2025-10-29 22:47:30', '2025-10-29 22:47:30'),
(250, 'Design & Multimedia Tools', 'Adobe Photoshop', '', 'https://upload.wikimedia.org/wikipedia/commons/thumb/a/af/Adobe_Photoshop_CC_icon.svg/512px-Adobe_Photoshop_CC_icon.svg.png?20200616073617', '2025-10-29 22:47:30', '2025-10-29 22:47:30'),
(251, 'Design & Multimedia Tools', 'Adobe Premiere Pro', '', 'https://upload.wikimedia.org/wikipedia/commons/thumb/1/17/Adobe_premiere_logo_vector.svg/149px-Adobe_premiere_logo_vector.svg.png?20140728021844', '2025-10-29 22:47:31', '2025-10-29 22:47:31'),
(252, 'Design & Multimedia Tools', 'Adobe After Effects', '', 'https://qzdtlrbpjudrvffrnory.supabase.co/storage/v1/object/public/Treis%20Adiutor/LOGOS/after-effects.png', '2025-10-29 22:47:31', '2025-10-29 22:47:31'),
(253, 'Design & Multimedia Tools', 'Adobe InDesign', '', 'https://qzdtlrbpjudrvffrnory.supabase.co/storage/v1/object/public/Treis%20Adiutor/LOGOS/indesign.png', '2025-10-29 22:47:31', '2025-10-29 22:47:31'),
(254, 'Design & Multimedia Tools', 'Adobe Lightroom', '', 'https://upload.wikimedia.org/wikipedia/commons/thumb/b/b6/Adobe_Photoshop_Lightroom_CC_logo.svg/512px-Adobe_Photoshop_Lightroom_CC_logo.svg.png?20200616120137', '2025-10-29 22:47:31', '2025-10-29 22:47:31'),
(255, 'Design & Multimedia Tools', 'Adobe XD', NULL, 'https://upload.wikimedia.org/wikipedia/commons/thumb/c/c2/Adobe_XD_CC_icon.svg/512px-Adobe_XD_CC_icon.svg.png?20210729021535', NULL, NULL),
(256, 'Design & Multimedia Tools', 'Adobe Media Encoder', NULL, 'https://upload.wikimedia.org/wikipedia/commons/thumb/5/5a/Adobe_Media_Encoder_Icon.svg/512px-Adobe_Media_Encoder_Icon.svg.png?20200618113117', NULL, NULL),
(257, 'Project Management & Productivity', 'Trello', 'trello.com', NULL, NULL, NULL),
(258, 'Project Management & Productivity', 'Asana', 'asana.com', NULL, NULL, NULL),
(259, 'Project Management & Productivity', 'Monday.com', 'monday.com', NULL, NULL, NULL),
(260, 'Project Management & Productivity', 'Notion', 'notion.so', NULL, NULL, NULL),
(261, 'Project Management & Productivity', 'ClickUp', 'clickup.com', NULL, NULL, NULL),
(262, 'Project Management & Productivity', 'Slack', 'slack.com', NULL, NULL, NULL),
(263, 'Project Management & Productivity', 'Jira', 'atlassian.com', NULL, NULL, NULL),
(264, 'Hosting & Deployment', 'cPanel', 'cpanel.net', NULL, NULL, NULL),
(265, 'Hosting & Deployment', 'Hostinger', 'hostinger.com', NULL, NULL, NULL),
(266, 'Hosting & Deployment', 'Vercel', 'vercel.com', NULL, NULL, NULL),
(267, 'Hosting & Deployment', 'Heroku', 'heroku.com', NULL, NULL, NULL),
(268, 'Hosting & Deployment', 'Render', 'render.com', NULL, NULL, NULL),
(269, 'Hosting & Deployment', 'Cloudfare', 'cloudfare.com', NULL, NULL, NULL),
(270, 'Security & Authentication', 'Auth0', 'auth0.com', NULL, NULL, NULL),
(271, 'Security & Authentication', 'Firebase Authentication', '', 'https://upload.wikimedia.org/wikipedia/commons/thumb/8/85/Firebase.svg/640px-Firebase.svg.png', NULL, NULL),
(272, 'Security & Authentication', '0Auth 2.0 / JWT', 'jwt.io', NULL, NULL, NULL),
(273, 'Payment Integration', 'Stripe', 'stripe.com', NULL, NULL, NULL),
(274, 'Payment Integration', 'Paypal', 'paypal.com', NULL, NULL, NULL),
(275, 'Payment Integration', 'RazorPay', 'razorpay.com', NULL, NULL, NULL),
(276, 'Payment Integration', 'Dragonpay', 'dragonpay.ph', NULL, NULL, NULL),
(277, 'Payment Integration', 'Paymongo', 'paymongo.com', NULL, NULL, NULL),
(278, 'Payment Integration', 'Gcash', 'gcash.com', NULL, NULL, NULL),
(279, 'Payment Integration', 'Maya Business', 'maya.ph', NULL, NULL, NULL),
(280, 'Programming Languages', 'Ruby', 'ruby-lang.org', NULL, NULL, NULL),
(281, 'Programming Languages', 'Swift', 'swift.org', NULL, NULL, NULL),
(282, 'Programming Languages', 'Dart', 'dart.dev', NULL, NULL, NULL),
(283, 'Programming Languages', 'Go', 'go.dev', NULL, NULL, NULL),
(284, 'Frontend Frameworks & Tools', 'Svelte', 'svelte.dev', NULL, NULL, NULL),
(285, 'Frontend Frameworks & Tools', 'jQuery', 'jquery.com', NULL, NULL, NULL),
(286, 'Programming Languages', 'Kotlin', 'kotlinlang.org', NULL, NULL, NULL),
(287, 'Programming Languages', 'Rust', 'rust-lang.org', NULL, NULL, NULL),
(288, 'Programming Languages', 'Scala', 'scala-lang.org', NULL, NULL, NULL),
(289, 'Programming Languages', 'Perl', 'perl.org', NULL, NULL, NULL),
(290, 'Frontend Frameworks & Tools', 'Angular', 'angular.io', NULL, NULL, NULL),
(291, 'Frontend Frameworks & Tools', 'Ember.js', 'emberjs.com', NULL, NULL, NULL),
(293, 'Frontend Frameworks & Tools', 'Astro', 'astro.build', NULL, NULL, NULL),
(294, 'Frontend Frameworks & Tools', 'Solid.js', 'solidjs.com', NULL, NULL, NULL),
(295, 'Backend Frameworks & Platforms', 'Spring Boot', 'spring.io', NULL, NULL, NULL),
(296, 'Backend Frameworks & Platforms', 'Ruby on Rails', 'rubyonrails.org', NULL, NULL, NULL),
(297, 'Backend Frameworks & Platforms', 'FastAPI', 'fastapi.tiangolo.com', NULL, NULL, NULL),
(298, 'Backend Frameworks & Platforms', 'NestJS', 'nestjs.com', NULL, NULL, NULL),
(299, 'Backend Frameworks & Platforms', 'ASP.NET Core', 'dotnet.microsoft.com', NULL, NULL, NULL),
(300, 'Databases & Database Management', 'Redis', 'redis.io', NULL, NULL, NULL),
(301, 'Databases & Database Management', 'Supabase', 'supabase.com', NULL, NULL, NULL),
(302, 'Databases & Database Management', 'Oracle Database', 'oracle.com', NULL, NULL, NULL),
(303, 'Databases & Database Management', 'MariaDB', 'mariadb.org', NULL, NULL, NULL),
(304, 'Databases & Database Management', 'Cassandra', 'cassandra.apache.org', NULL, NULL, NULL),
(305, 'DevOps & Cloud', 'Kubernetes', 'kubernetes.io', NULL, NULL, NULL),
(307, 'DevOps & Cloud', 'Terraform', 'terraform.io', NULL, NULL, NULL),
(308, 'DevOps & Cloud', 'GitHub Copilot', 'github.com', NULL, NULL, NULL),
(309, 'DevOps & Cloud', 'OpenShift', 'openshift.com', NULL, NULL, NULL),
(310, 'Testing & CI/CD', 'Mocha', 'mochajs.org', NULL, NULL, NULL),
(311, 'Testing & CI/CD', 'Jest', 'jestjs.io', NULL, NULL, NULL),
(312, 'Testing & CI/CD', 'Playwright', 'playwright.dev', NULL, NULL, NULL),
(313, 'Testing & CI/CD', 'Postman', 'postman.com', NULL, NULL, NULL),
(316, 'Project Management & Productivity', 'Evernote', 'evernote.com', NULL, NULL, NULL),
(317, 'Hosting & Deployment', 'Netlify', 'netlify.com', NULL, NULL, NULL),
(318, 'Hosting & Deployment', 'GitHub Pages', 'github.com', NULL, NULL, NULL),
(320, 'Security & Authentication', 'Keycloak', 'keycloak.org', NULL, NULL, NULL),
(321, 'Security & Authentication', 'Amazon Cognito', 'aws.amazon.com/cognito', NULL, NULL, NULL),
(322, 'Security & Authentication', 'Okta', 'okta.com', NULL, NULL, NULL),
(323, 'Payment Integration', 'Coinbase Commerce', 'commerce.coinbase.com', NULL, NULL, NULL),
(324, 'Payment Integration', 'Square', 'squareup.com', NULL, NULL, NULL),
(325, 'Payment Integration', 'Braintree', 'braintreepayments.com', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `time_entries`
--

CREATE TABLE `time_entries` (
  `id` bigint UNSIGNED NOT NULL,
  `task_id` bigint UNSIGNED NOT NULL,
  `project_id` bigint UNSIGNED NOT NULL,
  `adiutor_id` bigint UNSIGNED NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `start_time` timestamp NOT NULL,
  `end_time` timestamp NULL DEFAULT NULL,
  `duration_minutes` int DEFAULT NULL,
  `original_duration_minutes` int DEFAULT NULL COMMENT 'Original duration before admin adjustment',
  `billable_minutes` int DEFAULT NULL COMMENT 'Billable portion of duration (may be less than duration if max_hours reached)',
  `non_billable_minutes` int DEFAULT NULL COMMENT 'Non-billable portion when exceeding max_hours',
  `is_capped` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Whether this entry was capped due to max_hours limit',
  `hourly_rate` decimal(8,2) DEFAULT NULL,
  `amount` decimal(10,2) DEFAULT NULL,
  `calculated_amount` decimal(10,2) DEFAULT NULL,
  `original_calculated_amount` decimal(10,2) DEFAULT NULL COMMENT 'Original amount before admin adjustment',
  `is_billable` tinyint(1) NOT NULL DEFAULT '1',
  `is_approved` tinyint(1) NOT NULL DEFAULT '0',
  `admin_adjusted` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Whether this entry was adjusted by admin',
  `adjustment_reason` text COLLATE utf8mb4_unicode_ci COMMENT 'Reason for admin adjustment',
  `adjusted_by` bigint UNSIGNED DEFAULT NULL,
  `adjusted_at` timestamp NULL DEFAULT NULL COMMENT 'When the adjustment was made',
  `is_paid` tinyint(1) NOT NULL DEFAULT '0',
  `approved_by` bigint UNSIGNED DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `payout_id` bigint UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `time_entries`
--

INSERT INTO `time_entries` (`id`, `task_id`, `project_id`, `adiutor_id`, `description`, `start_time`, `end_time`, `duration_minutes`, `original_duration_minutes`, `billable_minutes`, `non_billable_minutes`, `is_capped`, `hourly_rate`, `amount`, `calculated_amount`, `original_calculated_amount`, `is_billable`, `is_approved`, `admin_adjusted`, `adjustment_reason`, `adjusted_by`, `adjusted_at`, `is_paid`, `approved_by`, `approved_at`, `notes`, `created_at`, `updated_at`, `payout_id`) VALUES
(2, 18, 16, 34, NULL, '2025-11-18 14:33:58', NULL, NULL, NULL, NULL, NULL, 0, 500.00, NULL, NULL, NULL, 1, 0, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, '2025-11-18 14:33:58', '2025-11-18 14:33:58', NULL),
(4, 25, 24, 7, NULL, '2025-12-06 07:28:28', '2025-12-06 09:03:06', 95, NULL, 94, 0, 0, 500.00, NULL, 783.33, NULL, 1, 1, 0, NULL, NULL, NULL, 0, 1, '2025-12-06 09:33:28', NULL, '2025-12-06 07:28:28', '2025-12-06 09:33:28', NULL),
(5, 28, 24, 7, NULL, '2025-12-06 09:51:59', NULL, NULL, NULL, NULL, NULL, 0, 500.00, NULL, NULL, NULL, 1, 0, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, '2025-12-06 09:51:59', '2025-12-06 09:51:59', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `fullName` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `firebase_uid` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `auth_provider` enum('local','firebase') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'local',
  `firebase_profile` json DEFAULT NULL,
  `last_firebase_sync` timestamp NULL DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` enum('admin','client','adiutor') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `referred_by_user_id` bigint UNSIGNED DEFAULT NULL,
  `referred_by_code` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `referral_credits` decimal(10,2) NOT NULL DEFAULT '0.00',
  `referral_credits_pending` decimal(10,2) NOT NULL DEFAULT '0.00',
  `referral_credits_withdrawn` decimal(10,2) NOT NULL DEFAULT '0.00',
  `work_earnings_balance` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT 'Available work earnings balance',
  `work_earnings_pending` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT 'Work earnings pending payout',
  `work_earnings_withdrawn` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT 'Total work earnings withdrawn',
  `referral_registered_at` timestamp NULL DEFAULT NULL,
  `is_referral_eligible` tinyint(1) NOT NULL DEFAULT '1',
  `phoneNumber` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `profilePic` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('active','inactive') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `dateCreated` timestamp NOT NULL DEFAULT '2025-10-29 13:18:53',
  `remember_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fcm_token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fcm_token_updated_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `fullName`, `email`, `firebase_uid`, `auth_provider`, `firebase_profile`, `last_firebase_sync`, `email_verified_at`, `password`, `role`, `referred_by_user_id`, `referred_by_code`, `referral_credits`, `referral_credits_pending`, `referral_credits_withdrawn`, `work_earnings_balance`, `work_earnings_pending`, `work_earnings_withdrawn`, `referral_registered_at`, `is_referral_eligible`, `phoneNumber`, `profilePic`, `status`, `dateCreated`, `remember_token`, `fcm_token`, `fcm_token_updated_at`, `created_at`, `updated_at`) VALUES
(1, 'Mark Admin', 'admin@treisadiutor.com', NULL, 'local', NULL, NULL, NULL, '$2y$12$BYrSiTID5MShzT6PA6tc1ONh2LN5Au688U5kT5y09GCcSSyEzpIFO', 'admin', NULL, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, NULL, 1, '+1-555-0101', NULL, 'active', '2025-10-25 06:38:05', '9AtfmTOgyu8wNVNwGV7yYaxsZWR2GjDuJBtX9RCQlKWd0FYguMb0wimI1kSn', NULL, NULL, '2025-10-25 06:38:12', '2025-12-05 09:22:33'),
(2, 'Sarah Manager', 'manager@treisadiutor.com', NULL, 'local', NULL, NULL, NULL, '$2y$12$BYrSiTID5MShzT6PA6tc1ONh2LN5Au688U5kT5y09GCcSSyEzpIFO', 'admin', NULL, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, NULL, 1, '+1-555-0102', NULL, 'active', '2025-10-25 06:38:05', NULL, NULL, NULL, '2025-10-25 06:38:13', '2025-12-01 18:36:39'),
(3, 'John Smith', 'john.smith@treisadiutor.com', NULL, 'local', NULL, NULL, NULL, '$2y$12$BYrSiTID5MShzT6PA6tc1ONh2LN5Au688U5kT5y09GCcSSyEzpIFO', 'client', NULL, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, NULL, 1, '+1-555-0201', NULL, 'active', '2025-10-25 06:38:05', 'ULwSVbsawyaVIUvFh0CWk6Ugokc2vXk5jOKJAJsQv0quKHdl5bfHT1Hdj4Jm', 'e3VKRpfsij8oUYkPJdQESF:APA91bGSzBlkNtkHRT8QyttiTw5wDn0gJsdcseqTjjVu504mSPDT1DNCxcgGQ-pR5pXd7qV1M_tZL3K3w-2oSkHJTtR4WnuFRWARrNIlIP-aF5jR1oqewyY', '2025-12-06 05:35:14', '2025-10-25 06:38:15', '2025-12-05 16:01:26'),
(4, 'Maria Rodriguez', 'maria@designstudio.com', NULL, 'local', NULL, NULL, NULL, '$2y$12$BYrSiTID5MShzT6PA6tc1ONh2LN5Au688U5kT5y09GCcSSyEzpIFO', 'client', NULL, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, NULL, 1, '+1-555-0202', NULL, 'active', '2025-10-25 06:38:05', NULL, NULL, NULL, '2025-10-25 06:38:15', '2025-10-25 06:38:15'),
(5, 'David Chen', 'david@ecommerceco.com', NULL, 'local', NULL, NULL, NULL, '$2y$12$BYrSiTID5MShzT6PA6tc1ONh2LN5Au688U5kT5y09GCcSSyEzpIFO', 'client', NULL, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, NULL, 1, '+1-555-0203', NULL, 'active', '2025-10-24 06:38:05', NULL, NULL, NULL, '2025-10-25 06:38:15', '2025-10-25 06:38:15'),
(6, 'Lisa Thompson', 'lisa@nonprofithelp.org', NULL, 'local', NULL, NULL, NULL, '$2y$12$BYrSiTID5MShzT6PA6tc1ONh2LN5Au688U5kT5y09GCcSSyEzpIFO', 'client', NULL, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, NULL, 1, '+1-555-0204', NULL, 'active', '2025-10-24 06:38:05', NULL, NULL, NULL, '2025-10-25 06:38:15', '2025-10-25 06:38:15'),
(7, 'Mark Andrew Soliman', 'mark@treisadiutor.com', NULL, 'local', NULL, NULL, NULL, '$2y$12$BYrSiTID5MShzT6PA6tc1ONh2LN5Au688U5kT5y09GCcSSyEzpIFO', 'adiutor', NULL, NULL, 0.00, 0.00, 0.00, 150783.33, 0.00, 0.00, NULL, 1, '+1-555-0301', NULL, 'active', '2025-10-25 06:38:05', 'GbJm6MdFYKzkrYQfVeIO1FpbzmKK4G1Xx25GBwX0xtD2VEJEcNPErO3k7zd3', 'dVDrxvxzM2_PfhOTs9xc0M:APA91bEBl764MAuDcZ_psMnqy_GamQvsB7S9OROTzAbeMdwYPFI6wzGdfTaKXjv7Zr2e9ud6wQAxB73GrrwrNXBKnxUa_nNVvlTI3aPPhbYNV9ZtpIpPv9c', '2025-12-05 23:00:04', '2025-10-25 06:38:18', '2025-12-06 09:33:28'),
(8, 'Princess Anne Azucena', 'shann@treisadiutor.com', NULL, 'local', NULL, NULL, NULL, '$2y$12$BYrSiTID5MShzT6PA6tc1ONh2LN5Au688U5kT5y09GCcSSyEzpIFO', 'adiutor', NULL, NULL, 0.00, 0.00, 0.00, 50000.00, 0.00, 0.00, NULL, 1, '+1-555-0302', NULL, 'active', '2025-10-25 06:38:05', 'O7kLgub6teb1TR3c8Mgp9JZB9VBUvV0ehJwWDJ2LwsQcTvPPylkcIsRTIEMU', NULL, NULL, '2025-10-25 06:38:18', '2025-12-06 08:40:48'),
(9, 'Lena Therese Quizon', 'lena@treisadiutor.com', NULL, 'local', NULL, NULL, NULL, '$2y$12$BYrSiTID5MShzT6PA6tc1ONh2LN5Au688U5kT5y09GCcSSyEzpIFO', 'adiutor', NULL, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, NULL, 1, '+1-555-0303', NULL, 'active', '2025-10-16 06:38:05', NULL, NULL, NULL, '2025-10-25 06:38:18', '2025-10-25 06:38:18'),
(10, 'Sofia Lorraine Gonzaga', 'sofia@treisadiutor.com', NULL, 'local', NULL, NULL, NULL, '$2y$12$BYrSiTID5MShzT6PA6tc1ONh2LN5Au688U5kT5y09GCcSSyEzpIFO', 'adiutor', NULL, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, NULL, 1, '+1-555-0304', NULL, 'active', '2025-10-23 06:38:05', NULL, NULL, NULL, '2025-10-25 06:38:18', '2025-10-25 06:38:18'),
(11, 'Carlos Mobile', 'carlos@treisadiutor.com', NULL, 'local', NULL, NULL, NULL, '$2y$12$BYrSiTID5MShzT6PA6tc1ONh2LN5Au688U5kT5y09GCcSSyEzpIFO', 'adiutor', NULL, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, NULL, 1, '+1-555-0305', NULL, 'active', '2025-10-25 06:38:05', NULL, NULL, NULL, '2025-10-25 06:38:18', '2025-10-25 06:38:18'),
(12, 'Mark Andrew Soliman', 'markandrewsoliman@outlook.com', NULL, 'local', NULL, NULL, NULL, '$2y$12$BYrSiTID5MShzT6PA6tc1ONh2LN5Au688U5kT5y09GCcSSyEzpIFO', 'client', NULL, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, NULL, 1, NULL, NULL, 'active', '2025-10-25 06:38:05', 'QWcMFc4AsocHVj2akzKfjS1ArApR5LT5uklPYrob7yKy623kTe69PVJSip7l', NULL, NULL, '2025-10-25 06:40:16', '2025-10-25 06:40:16'),
(13, 'Mark Andrew Soliman', 'markandrewsoliman.personal@gmail.com', 'google-oauth2|107267810620803448753', 'firebase', '{\"sub\": \"google-oauth2|107267810620803448753\", \"name\": \"Mark Andrew Soliman\", \"email\": \"markandrewsoliman.personal@gmail.com\", \"picture\": \"https://lh3.googleusercontent.com/a/ACg8ocKaH_za_1Pf_5mC4P1o47eKGZNJ-BZYwLTBX6SBULGC5b_qZRtP=s96-c\", \"nickname\": \"markandrewsoliman.personal\", \"given_name\": \"Mark Andrew\", \"updated_at\": \"2025-10-28T10:29:06.688Z\", \"family_name\": \"Soliman\", \"email_verified\": true}', '2025-10-29 13:50:18', NULL, '$2y$12$BYrSiTID5MShzT6PA6tc1ONh2LN5Au688U5kT5y09GCcSSyEzpIFO', 'client', NULL, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, NULL, 1, NULL, NULL, 'active', '2025-10-25 06:38:05', 'vk3CwuL0VBVE8aSVnlofprxB2mCJ2cY3J8n3aVq9XNiEe1aWIXG2nvkfScJk', NULL, NULL, '2025-10-28 02:29:09', '2025-10-29 13:50:19'),
(14, 'Mark Andrew Zapatero Soliman', 'maki@treisadiutor.com', NULL, 'local', NULL, NULL, NULL, '$2y$12$BYrSiTID5MShzT6PA6tc1ONh2LN5Au688U5kT5y09GCcSSyEzpIFO', 'client', NULL, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, NULL, 1, NULL, NULL, 'active', '2025-10-25 06:38:05', 'GPC7oF77ajTtn5ybPQEeJ7sQeJVXyrjF89bCnsdAjsuqtqu5IGW1Baejv5EH', NULL, NULL, '2025-10-28 03:05:46', '2025-12-03 15:07:24'),
(15, 'Cooper Case', 'punutez@treisadiutor.com', NULL, 'local', NULL, NULL, NULL, '$2y$12$BYrSiTID5MShzT6PA6tc1ONh2LN5Au688U5kT5y09GCcSSyEzpIFO', 'client', NULL, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, NULL, 1, NULL, NULL, 'active', '2025-10-25 06:38:05', NULL, NULL, NULL, '2025-10-28 03:36:26', '2025-10-28 03:36:26'),
(16, 'Francis Lancaster', 'wuquxaqa@treisadiutor.com', NULL, 'local', NULL, NULL, NULL, '$2y$12$BYrSiTID5MShzT6PA6tc1ONh2LN5Au688U5kT5y09GCcSSyEzpIFO', 'client', NULL, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, NULL, 1, NULL, NULL, 'active', '2025-10-25 06:38:05', NULL, NULL, NULL, '2025-10-28 03:48:59', '2025-10-28 03:48:59'),
(17, 'Victoria Mckee', 'nuwot@treisadiutor.com', NULL, 'local', NULL, NULL, NULL, '$2y$12$BYrSiTID5MShzT6PA6tc1ONh2LN5Au688U5kT5y09GCcSSyEzpIFO', 'client', NULL, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, NULL, 1, NULL, NULL, 'active', '2025-10-25 06:38:05', NULL, NULL, NULL, '2025-10-28 03:50:04', '2025-10-28 03:50:04'),
(18, 'Nissim Dalton', 'markandrew.soliman@lspu.edu.ph', 'UuzvgJAXK5Wp6IijcAc9PUK52mW2', 'firebase', '{\"uid\": \"UuzvgJAXK5Wp6IijcAc9PUK52mW2\", \"email\": \"markandrew.soliman@lspu.edu.ph\", \"photo_url\": \"https://lh3.googleusercontent.com/a/ACg8ocIy9ebr5CSlxDAihNJwV6OHBTWJsrgte-dV4IVGRmZmvm4rJWs=s96-c\", \"provider_id\": \"google\", \"display_name\": \"Mark Andrew Soliman\", \"phone_number\": null, \"provider_data\": [{\"uid\": \"108028214905869182713\", \"email\": \"markandrew.soliman@lspu.edu.ph\", \"photoUrl\": \"https://lh3.googleusercontent.com/a/ACg8ocIy9ebr5CSlxDAihNJwV6OHBTWJsrgte-dV4IVGRmZmvm4rJWs=s96-c\", \"providerId\": \"google.com\", \"displayName\": \"Mark Andrew Soliman\", \"phoneNumber\": null}], \"email_verified\": true}', '2025-12-01 11:14:58', NULL, '$2y$12$BYrSiTID5MShzT6PA6tc1ONh2LN5Au688U5kT5y09GCcSSyEzpIFO', 'client', NULL, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, NULL, 1, NULL, NULL, 'active', '2025-10-25 06:38:05', '5wlqPSrM2YMWTky72JceeaQ5GkkhyuW83dscWWrxAGPTiYlbs5S76oKsQOhe', NULL, NULL, '2025-10-28 03:50:45', '2025-12-01 11:14:58'),
(19, 'Hope Browning', 'gykiri@treisadiutor.com', NULL, 'local', NULL, NULL, NULL, '$2y$12$BYrSiTID5MShzT6PA6tc1ONh2LN5Au688U5kT5y09GCcSSyEzpIFO', 'client', NULL, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, NULL, 1, NULL, NULL, 'active', '2025-10-25 06:38:05', NULL, NULL, NULL, '2025-10-28 04:00:30', '2025-10-28 04:00:30'),
(20, 'Simon Watkins', 'mimy@treisadiutor.com', NULL, 'local', NULL, NULL, NULL, '$2y$12$BYrSiTID5MShzT6PA6tc1ONh2LN5Au688U5kT5y09GCcSSyEzpIFO', 'client', NULL, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, NULL, 1, NULL, NULL, 'active', '2025-10-25 06:38:05', NULL, NULL, NULL, '2025-10-28 04:08:47', '2025-10-28 04:08:47'),
(21, 'Rose Finch', 'mahigebazu@treisadiutor.com', NULL, 'local', NULL, NULL, NULL, '$2y$12$BYrSiTID5MShzT6PA6tc1ONh2LN5Au688U5kT5y09GCcSSyEzpIFO', 'client', NULL, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, NULL, 1, NULL, NULL, 'active', '2025-10-25 06:38:05', NULL, NULL, NULL, '2025-10-28 04:14:59', '2025-10-28 04:14:59'),
(22, 'Emerson Jacobson', 'resewopeg@treisadiutor.com', NULL, 'local', NULL, NULL, NULL, '$2y$12$BYrSiTID5MShzT6PA6tc1ONh2LN5Au688U5kT5y09GCcSSyEzpIFO', 'client', NULL, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, NULL, 1, NULL, NULL, 'active', '2025-10-25 06:38:05', NULL, NULL, NULL, '2025-10-28 04:19:48', '2025-10-28 04:19:48'),
(23, 'Mark Andrew Soliman', 'markandrewsoliman@treisadiutor.com', NULL, 'local', NULL, NULL, NULL, '$2y$12$BYrSiTID5MShzT6PA6tc1ONh2LN5Au688U5kT5y09GCcSSyEzpIFO', 'client', NULL, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, NULL, 1, NULL, NULL, 'active', '2025-10-25 06:38:05', NULL, NULL, NULL, '2025-10-28 22:22:15', '2025-10-29 00:54:44'),
(24, 'Apple User', 'apple_001112_9efac3ba28ff49a7ad352a1055f81950_0015@app.private', 'apple|001112.9efac3ba28ff49a7ad352a1055f81950.0015', 'firebase', '{\"sub\": \"apple|001112.9efac3ba28ff49a7ad352a1055f81950.0015\", \"name\": \"\", \"picture\": \"https://cdn.auth0.com/avatars/default.png\", \"nickname\": \"\", \"updated_at\": \"2025-10-29T00:15:50.977Z\"}', '2025-10-29 08:23:23', NULL, '$2y$12$BYrSiTID5MShzT6PA6tc1ONh2LN5Au688U5kT5y09GCcSSyEzpIFO', 'client', NULL, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, NULL, 1, NULL, NULL, 'active', '2025-10-25 06:38:05', 'ayOIyEoJFI42ab9VGRF1KIrB4WNUZiJ6HXEYRMF6LR1Lip9rMseDXNCl1hKg', NULL, NULL, '2025-10-29 08:23:23', '2025-10-29 08:23:23'),
(25, 'Mark Andrew Zapatero Soliman', 'hesyv92aa@treisadiutor.com', NULL, 'local', NULL, NULL, NULL, '$2y$12$BYrSiTID5MShzT6PA6tc1ONh2LN5Au688U5kT5y09GCcSSyEzpIFO', 'client', NULL, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, NULL, 1, NULL, NULL, 'active', '2025-10-25 06:38:05', NULL, NULL, NULL, '2025-10-29 08:38:36', '2025-10-29 08:38:36'),
(26, 'Mark Andrew Soliman', 'markandrewsoliman.ccs@gmail.com', 'google-oauth2|115454832754521949490', 'firebase', '{\"sub\": \"google-oauth2|115454832754521949490\", \"name\": \"Mark Andrew Soliman\", \"email\": \"markandrewsoliman.ccs@gmail.com\", \"picture\": \"https://lh3.googleusercontent.com/a/ACg8ocKiU0SPuVePIIrwOrJUOp1pZcJcVt_gfSVTa6KVgUn-nUHOUUE=s96-c\", \"nickname\": \"markandrewsoliman.ccs\", \"given_name\": \"Mark Andrew\", \"updated_at\": \"2025-10-29T00:53:46.447Z\", \"family_name\": \"Soliman\", \"email_verified\": true}', '2025-10-29 08:53:50', NULL, '$2y$12$BYrSiTID5MShzT6PA6tc1ONh2LN5Au688U5kT5y09GCcSSyEzpIFO', 'client', NULL, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, NULL, 1, NULL, NULL, 'active', '2025-10-25 06:38:05', '9IMpSf2ZvXzdrMBzSd37qGXOemZZOmtpcqhk9D7tFqRmSqpUOHjzvk4NgaY1', NULL, NULL, '2025-10-29 08:53:50', '2025-10-29 08:53:50'),
(29, 'Lena Quizon', 'lenatheresequizon04@gmail.com', NULL, 'local', NULL, NULL, NULL, '$2y$12$BYrSiTID5MShzT6PA6tc1ONh2LN5Au688U5kT5y09GCcSSyEzpIFO', 'client', NULL, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, NULL, 1, NULL, NULL, 'active', '2025-10-29 13:18:53', NULL, NULL, NULL, '2025-10-29 16:49:48', '2025-10-29 16:49:48'),
(30, 'Mark Andrew Soliman', 'markandrewsoliman.studies@gmail.com', 'am0fd7nl0Wf3ibFBJKblVyB9hZ72', 'firebase', '{\"uid\": \"am0fd7nl0Wf3ibFBJKblVyB9hZ72\", \"email\": \"markandrewsoliman.studies@gmail.com\", \"photo_url\": \"https://lh3.googleusercontent.com/a/ACg8ocLqW3-NxiuKaf_ukxHUZ3oSDngAqagvPoLV2Y3nGFhkpEFAo9sC=s96-c\", \"provider_id\": \"google\", \"display_name\": \"Mark Andrew Soliman\", \"phone_number\": null, \"provider_data\": [{\"uid\": \"118398349893700731662\", \"email\": \"markandrewsoliman.studies@gmail.com\", \"photoUrl\": \"https://lh3.googleusercontent.com/a/ACg8ocLqW3-NxiuKaf_ukxHUZ3oSDngAqagvPoLV2Y3nGFhkpEFAo9sC=s96-c\", \"providerId\": \"google.com\", \"displayName\": \"Mark Andrew Soliman\", \"phoneNumber\": null}], \"email_verified\": true}', '2025-10-30 20:05:30', NULL, '$2y$12$BYrSiTID5MShzT6PA6tc1ONh2LN5Au688U5kT5y09GCcSSyEzpIFO', 'client', NULL, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, NULL, 1, NULL, NULL, 'active', '2025-10-29 13:18:53', 'QkLSzjNghsMYfEhYUUNDF4qEnARdI851d8SIT7IJZ01tKHkcmG2dE26w3Lcq', NULL, NULL, '2025-10-30 19:41:06', '2025-10-30 20:05:30'),
(31, 'via', 'andrewsoliman97@gmail.com', 'ENHXhlIYnEfcYR9ithcMmQGIb8j2', 'firebase', '{\"uid\": \"ENHXhlIYnEfcYR9ithcMmQGIb8j2\", \"email\": \"andrewsoliman97@gmail.com\", \"photo_url\": \"https://pbs.twimg.com/profile_images/1531850884329615361/mLdN_dPY_normal.jpg\", \"provider_id\": \"twitter\", \"display_name\": \"via\", \"phone_number\": null, \"provider_data\": [{\"uid\": \"1332752732688703491\", \"email\": \"andrewsoliman97@gmail.com\", \"photoUrl\": \"https://pbs.twimg.com/profile_images/1531850884329615361/mLdN_dPY_normal.jpg\", \"providerId\": \"twitter.com\", \"displayName\": \"via\", \"phoneNumber\": null}], \"email_verified\": false}', '2025-10-30 19:44:05', NULL, '$2y$12$BYrSiTID5MShzT6PA6tc1ONh2LN5Au688U5kT5y09GCcSSyEzpIFO', 'client', NULL, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, NULL, 1, NULL, NULL, 'active', '2025-10-29 13:18:53', 'YhcJ2e8SOFiPoxIUHmKnu6ZaHtjARGMVFj9t0YwDGdFGz9ZvNtIwj27ENJSv', NULL, NULL, '2025-10-30 19:44:05', '2025-10-30 19:44:05'),
(32, 'Sofia Lorraine', 'yuicutie1975@gmail.com', NULL, 'local', NULL, NULL, NULL, '$2y$12$BYrSiTID5MShzT6PA6tc1ONh2LN5Au688U5kT5y09GCcSSyEzpIFO', 'client', NULL, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, NULL, 1, '+63 912 345 6789', NULL, 'active', '2025-10-29 13:18:53', NULL, NULL, NULL, '2025-11-12 01:36:32', '2025-12-02 12:24:28'),
(33, 'Mark Andrew Soliman', 'bywihiwed@novammerce.shop', NULL, 'local', NULL, NULL, NULL, '$2y$12$BYrSiTID5MShzT6PA6tc1ONh2LN5Au688U5kT5y09GCcSSyEzpIFO', 'client', NULL, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, NULL, 1, '+63 +19 915 2679', NULL, 'active', '2025-10-29 13:18:53', NULL, NULL, NULL, '2025-11-14 10:16:47', '2025-11-14 10:16:47'),
(34, 'Lena Quizon', 'calli.ri0904@gmail.com', NULL, 'local', NULL, NULL, NULL, '$2y$12$BYrSiTID5MShzT6PA6tc1ONh2LN5Au688U5kT5y09GCcSSyEzpIFO', 'adiutor', NULL, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, NULL, 1, '+63 949 104 6704', NULL, 'active', '2025-10-29 13:18:53', NULL, NULL, NULL, '2025-11-16 10:22:13', '2025-11-16 10:22:13'),
(35, 'Mark Andrew Soliman', 'markandrewsoliman@novammerce.shop', NULL, 'local', NULL, NULL, NULL, '$2y$12$BYrSiTID5MShzT6PA6tc1ONh2LN5Au688U5kT5y09GCcSSyEzpIFO', 'client', NULL, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, NULL, 1, '+639761022819', NULL, 'active', '2025-10-29 13:18:53', NULL, NULL, NULL, '2025-12-03 20:57:35', '2025-12-05 09:48:45'),
(36, 'Cynthia Warner', 'gylesif@novammerce.shop', NULL, 'local', NULL, NULL, NULL, '$2y$12$BYrSiTID5MShzT6PA6tc1ONh2LN5Au688U5kT5y09GCcSSyEzpIFO', 'client', NULL, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, NULL, 1, '+63 781 505 8771', NULL, 'inactive', '2025-10-29 13:18:53', NULL, NULL, NULL, '2025-12-05 09:44:56', '2025-12-05 09:44:56');

-- --------------------------------------------------------

--
-- Table structure for table `wallet_transactions`
--

CREATE TABLE `wallet_transactions` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `transaction_type` enum('work_earned','referral_earned','withdrawn','adjusted','refunded','pending','cancelled') COLLATE utf8mb4_unicode_ci NOT NULL,
  `source_type` enum('time_entry','project_fixed_rate','referral_completion','withdrawal_request','admin_adjustment','reversal') COLLATE utf8mb4_unicode_ci NOT NULL,
  `source_id` bigint UNSIGNED DEFAULT NULL COMMENT 'ID of the source record (time_entries.id, project_assignments.id, etc.)',
  `amount` decimal(10,2) NOT NULL COMMENT 'Transaction amount (positive for credit, negative for debit)',
  `balance_before` decimal(10,2) NOT NULL COMMENT 'Balance before this transaction',
  `balance_after` decimal(10,2) NOT NULL COMMENT 'Balance after this transaction',
  `wallet_type` enum('work_earnings','referral_credits') COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Which wallet this transaction affects',
  `description` text COLLATE utf8mb4_unicode_ci COMMENT 'Human-readable description of the transaction',
  `metadata` json DEFAULT NULL COMMENT 'Additional structured data (project name, task name, etc.)',
  `performed_by` bigint UNSIGNED DEFAULT NULL,
  `payout_id` bigint UNSIGNED DEFAULT NULL COMMENT 'Link to payout record if this is a withdrawal transaction',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `wallet_transactions`
--

INSERT INTO `wallet_transactions` (`id`, `user_id`, `transaction_type`, `source_type`, `source_id`, `amount`, `balance_before`, `balance_after`, `wallet_type`, `description`, `metadata`, `performed_by`, `payout_id`, `created_at`, `updated_at`) VALUES
(1, 7, 'work_earned', 'project_fixed_rate', 26, 150000.00, 0.00, 150000.00, 'work_earnings', 'Fixed rate payment for project: Ecommerce 3', '{\"project_id\": 18, \"agreed_rate\": \"150000.00\"}', 1, NULL, '2025-12-05 09:05:27', '2025-12-05 09:05:27'),
(2, 8, 'work_earned', 'project_fixed_rate', 28, 50000.00, 0.00, 50000.00, 'work_earnings', 'Fixed rate payment for project: SynthAI - Autonomous Code Review and Technical Debt Analyzer', '{\"project_id\": 24, \"agreed_rate\": \"50000.00\"}', 1, NULL, '2025-12-06 08:40:48', '2025-12-06 08:40:48'),
(3, 7, 'work_earned', 'time_entry', 4, 783.33, 150000.00, 150783.33, 'work_earnings', 'Time entry approved: Work on Task 1: Backend Infrastructure & API Foundation', '{\"rate\": \"500.00\", \"hours\": 1.58, \"task_id\": 25, \"project_id\": 24}', 1, NULL, '2025-12-06 09:33:28', '2025-12-06 09:33:28');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `adiutor_calendar_integrations`
--
ALTER TABLE `adiutor_calendar_integrations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `adiutor_calendar_integrations_adiutor_id_unique` (`adiutor_id`),
  ADD KEY `adiutor_calendar_integrations_is_connected_index` (`is_connected`);

--
-- Indexes for table `adiutor_profiles`
--
ALTER TABLE `adiutor_profiles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `adiutor_profiles_user_id_foreign` (`user_id`),
  ADD KEY `adiutor_profiles_status_is_verified_index` (`status`,`is_verified`),
  ADD KEY `adiutor_profiles_rating_index` (`rating`);

--
-- Indexes for table `adiutor_skills`
--
ALTER TABLE `adiutor_skills`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `adiutor_skills_adiutor_id_skill_id_unique` (`adiutor_id`,`skill_id`),
  ADD KEY `adiutor_skills_skill_id_foreign` (`skill_id`),
  ADD KEY `adiutor_skills_proficiency_level_index` (`proficiency_level`);

--
-- Indexes for table `adiutor_work_schedules`
--
ALTER TABLE `adiutor_work_schedules`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `adiutor_work_schedules_adiutor_id_day_of_week_unique` (`adiutor_id`,`day_of_week`);

--
-- Indexes for table `announcements`
--
ALTER TABLE `announcements`
  ADD PRIMARY KEY (`id`),
  ADD KEY `announcements_created_by_foreign` (`created_by`),
  ADD KEY `announcements_status_expires_at_index` (`status`,`expires_at`),
  ADD KEY `announcements_updated_by_foreign` (`updated_by`),
  ADD KEY `idx_announcements_status_dates` (`status`,`starts_at`,`expires_at`),
  ADD KEY `idx_announcements_active` (`status`,`target_audience`,`expires_at`);

--
-- Indexes for table `audit_logs`
--
ALTER TABLE `audit_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `audit_logs_auditable_type_auditable_id_index` (`auditable_type`,`auditable_id`),
  ADD KEY `audit_logs_user_id_created_at_index` (`user_id`,`created_at`),
  ADD KEY `audit_logs_action_created_at_index` (`action`,`created_at`),
  ADD KEY `audit_logs_event_type_created_at_index` (`event_type`,`created_at`);

--
-- Indexes for table `budget_change_requests`
--
ALTER TABLE `budget_change_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `budget_change_requests_task_id_foreign` (`task_id`),
  ADD KEY `budget_change_requests_reviewed_by_foreign` (`reviewed_by`),
  ADD KEY `budget_change_requests_status_created_at_index` (`status`,`created_at`),
  ADD KEY `budget_change_requests_adiutor_id_index` (`adiutor_id`),
  ADD KEY `idx_budget_requests_status` (`status`),
  ADD KEY `idx_budget_requests_adiutor_status` (`adiutor_id`,`status`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `client_profiles`
--
ALTER TABLE `client_profiles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `client_profiles_user_id_foreign` (`user_id`),
  ADD KEY `client_profiles_client_type_is_verified_index` (`client_type`,`is_verified`),
  ADD KEY `client_profiles_industry_index` (`industry`);

--
-- Indexes for table `conversations`
--
ALTER TABLE `conversations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `conversations_conversation_id_unique` (`conversation_id`),
  ADD KEY `conversations_last_message_id_foreign` (`last_message_id`),
  ADD KEY `conversations_project_id_is_archived_index` (`project_id`,`is_archived`),
  ADD KEY `conversations_client_id_last_message_at_index` (`client_id`,`last_message_at`);

--
-- Indexes for table `coupons`
--
ALTER TABLE `coupons`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `coupons_code_unique` (`code`),
  ADD KEY `coupons_specific_user_id_foreign` (`specific_user_id`),
  ADD KEY `coupons_specific_request_id_foreign` (`specific_request_id`),
  ADD KEY `coupons_code_status_index` (`code`,`status`),
  ADD KEY `coupons_coupon_type_status_index` (`coupon_type`,`status`),
  ADD KEY `coupons_valid_from_valid_until_index` (`valid_from`,`valid_until`),
  ADD KEY `coupons_created_by_index` (`created_by`);

--
-- Indexes for table `coupon_usages`
--
ALTER TABLE `coupon_usages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `coupon_usages_coupon_id_foreign` (`coupon_id`),
  ADD KEY `coupon_usages_user_id_coupon_id_index` (`user_id`,`coupon_id`),
  ADD KEY `coupon_usages_service_request_id_index` (`service_request_id`),
  ADD KEY `coupon_usages_payment_id_index` (`payment_id`),
  ADD KEY `coupon_usages_payment_status_index` (`payment_status`),
  ADD KEY `coupon_usages_used_at_index` (`used_at`);

--
-- Indexes for table `custom_report_templates`
--
ALTER TABLE `custom_report_templates`
  ADD PRIMARY KEY (`id`),
  ADD KEY `custom_report_templates_created_by_type_index` (`created_by`,`type`);

--
-- Indexes for table `documents`
--
ALTER TABLE `documents`
  ADD PRIMARY KEY (`documentID`),
  ADD KEY `documents_documentable_type_documentable_id_index` (`documentable_type`,`documentable_id`),
  ADD KEY `documents_taskid_foreign` (`taskID`),
  ADD KEY `documents_service_request_id_foreign` (`service_request_id`),
  ADD KEY `documents_project_id_foreign` (`project_id`),
  ADD KEY `documents_client_id_foreign` (`client_id`),
  ADD KEY `documents_uploaded_by_foreign` (`uploaded_by`),
  ADD KEY `documents_verified_by_foreign` (`verified_by`),
  ADD KEY `documents_parent_document_id_foreign` (`parent_document_id`),
  ADD KEY `documents_original_document_id_version_index` (`original_document_id`,`version`),
  ADD KEY `documents_approved_by_foreign` (`approved_by`),
  ADD KEY `documents_is_deliverable_index` (`is_deliverable`),
  ADD KEY `documents_is_approved_index` (`is_approved`),
  ADD KEY `documents_taskid_is_approved_index` (`taskID`,`is_approved`),
  ADD KEY `documents_taskid_is_deliverable_index` (`taskID`,`is_deliverable`),
  ADD KEY `idx_documents_task_archived` (`taskID`,`is_archived`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `feedbacks`
--
ALTER TABLE `feedbacks`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `feedbacks_project_client_unique` (`project_id`,`client_id`),
  ADD KEY `feedbacks_task_id_foreign` (`task_id`),
  ADD KEY `feedbacks_project_id_foreign` (`project_id`),
  ADD KEY `feedbacks_responded_by_foreign` (`responded_by`),
  ADD KEY `feedbacks_resolved_by_foreign` (`resolved_by`),
  ADD KEY `feedbacks_client_id_index` (`client_id`),
  ADD KEY `feedbacks_adiutor_id_index` (`adiutor_id`),
  ADD KEY `feedbacks_status_index` (`status`),
  ADD KEY `feedbacks_rating_index` (`rating`),
  ADD KEY `feedbacks_created_at_index` (`created_at`),
  ADD KEY `feedbacks_type_index` (`type`),
  ADD KEY `idx_feedbacks_project_rating` (`project_id`,`rating`);

--
-- Indexes for table `group_chats`
--
ALTER TABLE `group_chats`
  ADD PRIMARY KEY (`id`),
  ADD KEY `group_chats_archived_by_foreign` (`archived_by`),
  ADD KEY `group_chats_project_id_status_index` (`project_id`,`status`),
  ADD KEY `group_chats_status_last_message_at_index` (`status`,`last_message_at`),
  ADD KEY `group_chats_last_message_id_foreign` (`last_message_id`);

--
-- Indexes for table `group_chat_members`
--
ALTER TABLE `group_chat_members`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `group_chat_members_group_chat_id_user_id_unique` (`group_chat_id`,`user_id`),
  ADD KEY `group_chat_members_user_id_unread_count_index` (`user_id`,`unread_count`),
  ADD KEY `idx_gcm_user_chat` (`user_id`,`group_chat_id`);

--
-- Indexes for table `hour_increase_requests`
--
ALTER TABLE `hour_increase_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `hour_increase_requests_reviewed_by_foreign` (`reviewed_by`),
  ADD KEY `idx_adiutor_status` (`adiutor_id`,`status`),
  ADD KEY `idx_project_status` (`project_id`,`status`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `hour_increase_requests_task_id_foreign` (`task_id`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `loyalty_coupons`
--
ALTER TABLE `loyalty_coupons`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `loyalty_coupons_code_unique` (`code`),
  ADD KEY `loyalty_coupons_client_id_foreign` (`client_id`),
  ADD KEY `loyalty_coupons_used_on_project_id_foreign` (`used_on_project_id`);

--
-- Indexes for table `loyalty_points`
--
ALTER TABLE `loyalty_points`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `loyalty_points_user_id_unique` (`user_id`),
  ADD KEY `loyalty_points_tier_index` (`tier`),
  ADD KEY `loyalty_points_lifetime_earned_index` (`lifetime_earned`),
  ADD KEY `loyalty_points_user_id_index` (`user_id`),
  ADD KEY `loyalty_points_last_earned_at_index` (`last_earned_at`);

--
-- Indexes for table `loyalty_tiers`
--
ALTER TABLE `loyalty_tiers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `loyalty_tiers_tier_unique` (`tier`),
  ADD KEY `loyalty_tiers_order_index` (`order`),
  ADD KEY `loyalty_tiers_tier_index` (`tier`),
  ADD KEY `loyalty_tiers_points_required_index` (`points_required`);

--
-- Indexes for table `loyalty_transactions`
--
ALTER TABLE `loyalty_transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `loyalty_transactions_service_request_id_foreign` (`service_request_id`),
  ADD KEY `loyalty_transactions_payment_id_foreign` (`payment_id`),
  ADD KEY `loyalty_transactions_coupon_id_foreign` (`coupon_id`),
  ADD KEY `loyalty_transactions_performed_by_foreign` (`performed_by`),
  ADD KEY `loyalty_transactions_user_id_transaction_type_index` (`user_id`,`transaction_type`),
  ADD KEY `loyalty_transactions_user_id_created_at_index` (`user_id`,`created_at`),
  ADD KEY `loyalty_transactions_expires_at_expired_index` (`expires_at`,`expired`),
  ADD KEY `loyalty_transactions_source_index` (`source`),
  ADD KEY `loyalty_transactions_expires_at_expiry_warning_sent_index` (`expires_at`,`expiry_warning_sent`),
  ADD KEY `loyalty_transactions_expired_at_index` (`expired_at`),
  ADD KEY `loyalty_transactions_user_id_index` (`user_id`),
  ADD KEY `loyalty_transactions_transaction_type_index` (`transaction_type`),
  ADD KEY `loyalty_transactions_created_at_index` (`created_at`),
  ADD KEY `loyalty_transactions_user_date_index` (`user_id`,`created_at`),
  ADD KEY `loyalty_transactions_user_type_index` (`user_id`,`transaction_type`);

--
-- Indexes for table `meetings`
--
ALTER TABLE `meetings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `meetings_admin_id_foreign` (`admin_id`),
  ADD KEY `meetings_project_id_index` (`project_id`),
  ADD KEY `meetings_client_id_index` (`client_id`),
  ADD KEY `meetings_status_index` (`status`),
  ADD KEY `meetings_scheduled_date_index` (`scheduled_date`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `messages_task_id_foreign` (`task_id`),
  ADD KEY `messages_recipient_id_is_read_index` (`recipient_id`,`is_read`),
  ADD KEY `messages_sender_id_created_at_index` (`sender_id`,`created_at`),
  ADD KEY `messages_project_id_message_type_index` (`project_id`,`message_type`),
  ADD KEY `messages_conversation_id_index` (`conversation_id`),
  ADD KEY `messages_project_id_created_at_index` (`project_id`,`created_at`),
  ADD KEY `messages_status_index` (`status`),
  ADD KEY `messages_group_chat_id_created_at_index` (`group_chat_id`,`created_at`),
  ADD KEY `idx_messages_project_created` (`project_id`,`created_at`),
  ADD KEY `idx_messages_conversation_created` (`conversation_id`,`created_at`),
  ADD KEY `idx_messages_group_chat_created` (`group_chat_id`,`created_at`),
  ADD KEY `idx_messages_recipient_unread` (`recipient_id`,`is_read`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `milestone_payments`
--
ALTER TABLE `milestone_payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `milestone_payments_payment_id_foreign` (`payment_id`),
  ADD KEY `milestone_payments_client_id_foreign` (`client_id`),
  ADD KEY `milestone_payments_confirmed_by_foreign` (`confirmed_by`),
  ADD KEY `milestone_payments_milestone_id_status_index` (`milestone_id`,`status`),
  ADD KEY `milestone_payments_service_request_id_status_index` (`service_request_id`,`status`),
  ADD KEY `milestone_payments_status_index` (`status`);

--
-- Indexes for table `notes`
--
ALTER TABLE `notes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `notes_client_id_foreign` (`client_id`),
  ADD KEY `notes_added_by_foreign` (`added_by`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `notifications_notifiable_type_notifiable_id_index` (`notifiable_type`,`notifiable_id`),
  ADD KEY `idx_notifications_user_unread` (`notifiable_type`,`notifiable_id`,`read_at`),
  ADD KEY `idx_notifications_user_created` (`notifiable_type`,`notifiable_id`,`created_at`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `payments_client_id_foreign` (`client_id`),
  ADD KEY `payments_confirmed_by_foreign` (`confirmed_by`),
  ADD KEY `payments_service_request_id_status_index` (`service_request_id`,`status`),
  ADD KEY `payments_status_confirmed_at_index` (`status`,`confirmed_at`),
  ADD KEY `payments_payment_reference_index` (`payment_reference`),
  ADD KEY `payments_milestone_id_status_index` (`milestone_id`,`status`),
  ADD KEY `payments_payment_type_status_index` (`payment_type`,`status`),
  ADD KEY `payments_service_request_id_payment_type_index` (`service_request_id`,`payment_type`),
  ADD KEY `idx_payments_client_id` (`client_id`),
  ADD KEY `idx_payments_status` (`status`),
  ADD KEY `idx_payments_client_status` (`client_id`,`status`),
  ADD KEY `idx_payments_confirmed_at` (`confirmed_at`);

--
-- Indexes for table `payouts`
--
ALTER TABLE `payouts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `payouts_payout_number_unique` (`payout_number`),
  ADD KEY `payouts_processed_by_foreign` (`processed_by`),
  ADD KEY `payouts_adiutor_id_status_index` (`adiutor_id`,`status`),
  ADD KEY `payouts_status_created_at_index` (`status`,`created_at`),
  ADD KEY `payouts_payout_number_index` (`payout_number`),
  ADD KEY `idx_payouts_status` (`status`),
  ADD KEY `idx_payouts_adiutor_status` (`adiutor_id`,`status`);

--
-- Indexes for table `payout_items`
--
ALTER TABLE `payout_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `payout_items_task_id_foreign` (`task_id`),
  ADD KEY `payout_items_project_id_foreign` (`project_id`),
  ADD KEY `payout_items_payout_id_index` (`payout_id`),
  ADD KEY `payout_items_time_entry_id_index` (`time_entry_id`);

--
-- Indexes for table `projects`
--
ALTER TABLE `projects`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `projects_service_request_id_unique` (`service_request_id`),
  ADD KEY `projects_status_priority_index` (`status`,`priority`),
  ADD KEY `projects_client_id_status_index` (`client_id`,`status`),
  ADD KEY `projects_deadline_index` (`deadline`),
  ADD KEY `idx_projects_created_at` (`created_at`),
  ADD KEY `idx_projects_completed_at` (`completed_at`),
  ADD KEY `idx_projects_budget_type_status` (`budget_type`,`status`),
  ADD KEY `idx_projects_client_status` (`client_id`,`status`);

--
-- Indexes for table `project_assignments`
--
ALTER TABLE `project_assignments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `project_assignments_project_id_adiutor_id_unique` (`project_id`,`adiutor_id`),
  ADD KEY `project_assignments_adiutor_id_status_index` (`adiutor_id`,`status`),
  ADD KEY `project_assignments_fixed_rate_approved_by_foreign` (`fixed_rate_approved_by`),
  ADD KEY `project_assignments_fixed_rate_payout_id_foreign` (`fixed_rate_payout_id`),
  ADD KEY `idx_project_adiutor_status` (`project_id`,`adiutor_id`,`status`),
  ADD KEY `idx_project_assignments_payment_type` (`payment_type`),
  ADD KEY `idx_assignments_fixed_rate_workflow` (`fixed_rate_approved`,`fixed_rate_paid`),
  ADD KEY `idx_project_assignments_created_at` (`created_at`),
  ADD KEY `idx_assignments_adiutor_status` (`adiutor_id`,`status`),
  ADD KEY `idx_assignments_project_payment` (`project_id`,`payment_type`,`fixed_rate_approved`),
  ADD KEY `idx_assignments_project_adiutor` (`project_id`,`adiutor_id`,`status`);

--
-- Indexes for table `project_feedback`
--
ALTER TABLE `project_feedback`
  ADD PRIMARY KEY (`id`),
  ADD KEY `project_feedback_client_id_foreign` (`client_id`),
  ADD KEY `project_feedback_adiutor_id_foreign` (`adiutor_id`),
  ADD KEY `project_feedback_project_id_feedback_type_index` (`project_id`,`feedback_type`),
  ADD KEY `project_feedback_rating_index` (`rating`);

--
-- Indexes for table `project_milestones`
--
ALTER TABLE `project_milestones`
  ADD PRIMARY KEY (`id`),
  ADD KEY `project_milestones_project_id_phase_order_index` (`project_id`,`phase_order`),
  ADD KEY `project_milestones_project_id_is_paid_index` (`project_id`,`is_paid`),
  ADD KEY `project_milestones_status_index` (`status`),
  ADD KEY `project_milestones_milestone_number_index` (`milestone_number`);

--
-- Indexes for table `project_templates`
--
ALTER TABLE `project_templates`
  ADD PRIMARY KEY (`id`),
  ADD KEY `project_templates_category_is_active_index` (`category`,`is_active`),
  ADD KEY `project_templates_created_by_index` (`created_by`),
  ADD KEY `idx_templates_active_category` (`is_active`,`category`);

--
-- Indexes for table `referrals`
--
ALTER TABLE `referrals`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `referrals_referrer_id_referred_id_unique` (`referrer_id`,`referred_id`),
  ADD KEY `referrals_referrer_coupon_id_foreign` (`referrer_coupon_id`),
  ADD KEY `referrals_referred_coupon_id_foreign` (`referred_coupon_id`),
  ADD KEY `referrals_first_payment_id_foreign` (`first_payment_id`),
  ADD KEY `referrals_referrer_id_status_index` (`referrer_id`,`status`),
  ADD KEY `referrals_referred_id_status_index` (`referred_id`,`status`),
  ADD KEY `referrals_status_index` (`status`),
  ADD KEY `referrals_referral_code_index` (`referral_code`);

--
-- Indexes for table `referral_campaigns`
--
ALTER TABLE `referral_campaigns`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `referral_campaigns_code_unique` (`code`);

--
-- Indexes for table `referral_codes`
--
ALTER TABLE `referral_codes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `referral_codes_code_unique` (`code`),
  ADD KEY `referral_codes_code_is_active_index` (`code`,`is_active`),
  ADD KEY `referral_codes_user_id_index` (`user_id`);

--
-- Indexes for table `referral_credit_transactions`
--
ALTER TABLE `referral_credit_transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `referral_credit_transactions_referral_id_foreign` (`referral_id`),
  ADD KEY `referral_credit_transactions_withdrawal_id_foreign` (`withdrawal_id`),
  ADD KEY `referral_credit_transactions_performed_by_foreign` (`performed_by`),
  ADD KEY `referral_credit_transactions_user_id_transaction_type_index` (`user_id`,`transaction_type`),
  ADD KEY `referral_credit_transactions_user_id_created_at_index` (`user_id`,`created_at`),
  ADD KEY `referral_credit_transactions_source_index` (`source`);

--
-- Indexes for table `referral_credit_withdrawals`
--
ALTER TABLE `referral_credit_withdrawals`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `referral_credit_withdrawals_withdrawal_number_unique` (`withdrawal_number`),
  ADD KEY `referral_credit_withdrawals_processed_by_foreign` (`processed_by`),
  ADD KEY `referral_credit_withdrawals_user_id_status_index` (`user_id`,`status`),
  ADD KEY `referral_credit_withdrawals_status_index` (`status`),
  ADD KEY `referral_credit_withdrawals_withdrawal_number_index` (`withdrawal_number`);

--
-- Indexes for table `request_attachments`
--
ALTER TABLE `request_attachments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `request_attachments_service_request_id_foreign` (`service_request_id`),
  ADD KEY `request_attachments_mime_type_index` (`mime_type`),
  ADD KEY `request_attachments_file_hash_index` (`file_hash`);

--
-- Indexes for table `revision_requests`
--
ALTER TABLE `revision_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `revision_requests_requested_by_foreign` (`requested_by`),
  ADD KEY `revision_requests_reviewed_by_foreign` (`reviewed_by`),
  ADD KEY `revision_requests_task_id_foreign` (`task_id`),
  ADD KEY `revision_requests_service_request_id_foreign` (`service_request_id`),
  ADD KEY `revision_requests_project_id_foreign` (`project_id`),
  ADD KEY `revision_requests_completed_by_foreign` (`completed_by`),
  ADD KEY `revision_requests_status_index` (`status`),
  ADD KEY `revision_requests_source_type_index` (`source_type`),
  ADD KEY `revision_requests_document_id_status_index` (`document_id`,`status`),
  ADD KEY `revision_requests_assigned_adiutor_id_status_index` (`assigned_adiutor_id`,`status`),
  ADD KEY `idx_revision_requests_adiutor_status` (`assigned_adiutor_id`,`status`);

--
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`id`),
  ADD KEY `services_category_is_active_index` (`category`,`is_active`),
  ADD KEY `services_is_active_index` (`is_active`);

--
-- Indexes for table `service_requests`
--
ALTER TABLE `service_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `service_requests_approved_by_foreign` (`approved_by`),
  ADD KEY `service_requests_status_priority_index` (`status`,`priority`),
  ADD KEY `service_requests_client_id_status_index` (`client_id`,`status`),
  ADD KEY `service_requests_deadline_index` (`deadline`),
  ADD KEY `service_requests_payment_type_index` (`payment_type`),
  ADD KEY `service_requests_applied_coupon_id_index` (`applied_coupon_id`),
  ADD KEY `service_requests_loyalty_points_awarded_status_index` (`loyalty_points_awarded`,`status`),
  ADD KEY `idx_service_requests_approved_at` (`approved_at`),
  ADD KEY `idx_service_requests_payment_confirmed_at` (`payment_confirmed_at`),
  ADD KEY `idx_service_requests_created_at` (`created_at`),
  ADD KEY `service_requests_template_service_id_foreign` (`template_service_id`),
  ADD KEY `idx_service_requests_client_status` (`client_id`,`status`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`),
  ADD KEY `sessions_id_index` (`id`);

--
-- Indexes for table `showcases`
--
ALTER TABLE `showcases`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `showcases_slug_unique` (`slug`),
  ADD KEY `showcases_status_index` (`status`),
  ADD KEY `showcases_category_index` (`category`),
  ADD KEY `showcases_created_at_index` (`created_at`);

--
-- Indexes for table `showcase_screenshots`
--
ALTER TABLE `showcase_screenshots`
  ADD PRIMARY KEY (`id`),
  ADD KEY `showcase_screenshots_showcase_id_index` (`showcase_id`),
  ADD KEY `showcase_screenshots_sort_order_index` (`sort_order`);

--
-- Indexes for table `skills`
--
ALTER TABLE `skills`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `skills_name_unique` (`name`),
  ADD KEY `skills_category_is_active_index` (`category`,`is_active`);

--
-- Indexes for table `subtasks`
--
ALTER TABLE `subtasks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `subtasks_completed_by_foreign` (`completed_by`),
  ADD KEY `subtasks_assigned_to_foreign` (`assigned_to`),
  ADD KEY `subtasks_created_by_foreign` (`created_by`),
  ADD KEY `subtasks_task_id_index` (`task_id`),
  ADD KEY `subtasks_is_completed_index` (`is_completed`),
  ADD KEY `subtasks_sort_order_index` (`sort_order`);

--
-- Indexes for table `tasks`
--
ALTER TABLE `tasks`
  ADD PRIMARY KEY (`taskID`),
  ADD KEY `tasks_createdby_foreign` (`createdBy`),
  ADD KEY `tasks_service_request_id_foreign` (`service_request_id`),
  ADD KEY `tasks_status_priority_deadline_index` (`status`,`priority`,`deadline`),
  ADD KEY `tasks_assignedto_status_index` (`assignedTo`,`status`),
  ADD KEY `tasks_project_id_status_index` (`project_id`,`status`),
  ADD KEY `tasks_client_id_status_index` (`client_id`,`status`),
  ADD KEY `tasks_project_id_phase_id_index` (`project_id`,`phase_id`),
  ADD KEY `tasks_phase_id_status_index` (`phase_id`,`status`),
  ADD KEY `tasks_status_priority_index` (`status`,`priority`),
  ADD KEY `idx_tasks_completed_at` (`completedAt`),
  ADD KEY `idx_tasks_date_assigned` (`dateAssigned`),
  ADD KEY `idx_tasks_scheduled_status` (`is_scheduled`,`status`),
  ADD KEY `idx_tasks_project_sort_order` (`project_id`,`sort_order`),
  ADD KEY `idx_tasks_project_status` (`project_id`,`status`),
  ADD KEY `idx_tasks_assigned_status` (`assignedTo`,`status`),
  ADD KEY `idx_tasks_deadline_status` (`deadline`,`status`),
  ADD KEY `idx_tasks_assigned_deadline` (`assignedTo`,`deadline`,`status`);

--
-- Indexes for table `task_deliverables`
--
ALTER TABLE `task_deliverables`
  ADD PRIMARY KEY (`id`),
  ADD KEY `task_deliverables_document_id_foreign` (`document_id`),
  ADD KEY `task_deliverables_uploaded_by_foreign` (`uploaded_by`),
  ADD KEY `task_deliverables_approved_by_foreign` (`approved_by`),
  ADD KEY `task_deliverables_task_id_index` (`task_id`),
  ADD KEY `task_deliverables_type_index` (`type`),
  ADD KEY `task_deliverables_is_approved_index` (`is_approved`);

--
-- Indexes for table `task_schedules`
--
ALTER TABLE `task_schedules`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `task_schedules_task_id_unique` (`task_id`),
  ADD KEY `task_schedules_adiutor_id_scheduled_start_index` (`adiutor_id`,`scheduled_start`);

--
-- Indexes for table `tech_stack`
--
ALTER TABLE `tech_stack`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tech_stack_category_index` (`category`);

--
-- Indexes for table `time_entries`
--
ALTER TABLE `time_entries`
  ADD PRIMARY KEY (`id`),
  ADD KEY `time_entries_approved_by_foreign` (`approved_by`),
  ADD KEY `time_entries_task_id_adiutor_id_index` (`task_id`,`adiutor_id`),
  ADD KEY `time_entries_project_id_adiutor_id_index` (`project_id`,`adiutor_id`),
  ADD KEY `time_entries_adiutor_id_started_at_index` (`adiutor_id`,`start_time`),
  ADD KEY `time_entries_is_billable_is_approved_index` (`is_billable`,`is_approved`),
  ADD KEY `time_entries_adiutor_started_index` (`adiutor_id`,`start_time`),
  ADD KEY `time_entries_adiutor_ended_index` (`adiutor_id`,`end_time`),
  ADD KEY `time_entries_payout_id_foreign` (`payout_id`),
  ADD KEY `time_entries_adjusted_by_foreign` (`adjusted_by`),
  ADD KEY `idx_time_entries_is_approved` (`is_approved`),
  ADD KEY `idx_time_entries_is_paid` (`is_paid`),
  ADD KEY `idx_time_entries_approval_payment` (`is_approved`,`is_paid`),
  ADD KEY `idx_time_entries_adiutor_approved` (`adiutor_id`,`is_approved`),
  ADD KEY `idx_time_entries_project_approved` (`project_id`,`is_approved`),
  ADD KEY `idx_time_entries_task_approved` (`task_id`,`is_approved`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD UNIQUE KEY `users_auth0_id_unique` (`firebase_uid`),
  ADD UNIQUE KEY `users_firebase_uid_unique` (`firebase_uid`),
  ADD KEY `users_role_status_index` (`role`,`status`),
  ADD KEY `users_email_index` (`email`),
  ADD KEY `users_referred_by_user_id_index` (`referred_by_user_id`),
  ADD KEY `users_referred_by_code_index` (`referred_by_code`),
  ADD KEY `users_auth_provider_index` (`auth_provider`),
  ADD KEY `idx_users_role_created` (`role`,`created_at`),
  ADD KEY `idx_users_status` (`status`);

--
-- Indexes for table `wallet_transactions`
--
ALTER TABLE `wallet_transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `wallet_transactions_performed_by_foreign` (`performed_by`),
  ADD KEY `idx_user_wallet` (`user_id`,`wallet_type`),
  ADD KEY `idx_transaction_type` (`transaction_type`),
  ADD KEY `idx_created` (`created_at`),
  ADD KEY `idx_source` (`source_type`,`source_id`),
  ADD KEY `idx_wallet_transactions_wallet_type` (`wallet_type`),
  ADD KEY `idx_wallet_transactions_user_wallet` (`user_id`,`wallet_type`),
  ADD KEY `idx_wallet_transactions_type` (`transaction_type`),
  ADD KEY `idx_wallet_transactions_created_at` (`created_at`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `adiutor_calendar_integrations`
--
ALTER TABLE `adiutor_calendar_integrations`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `adiutor_profiles`
--
ALTER TABLE `adiutor_profiles`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `adiutor_skills`
--
ALTER TABLE `adiutor_skills`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `adiutor_work_schedules`
--
ALTER TABLE `adiutor_work_schedules`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `announcements`
--
ALTER TABLE `announcements`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `audit_logs`
--
ALTER TABLE `audit_logs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=128;

--
-- AUTO_INCREMENT for table `budget_change_requests`
--
ALTER TABLE `budget_change_requests`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `client_profiles`
--
ALTER TABLE `client_profiles`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `conversations`
--
ALTER TABLE `conversations`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `coupons`
--
ALTER TABLE `coupons`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `coupon_usages`
--
ALTER TABLE `coupon_usages`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `custom_report_templates`
--
ALTER TABLE `custom_report_templates`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `documents`
--
ALTER TABLE `documents`
  MODIFY `documentID` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `feedbacks`
--
ALTER TABLE `feedbacks`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `group_chats`
--
ALTER TABLE `group_chats`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `group_chat_members`
--
ALTER TABLE `group_chat_members`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT for table `hour_increase_requests`
--
ALTER TABLE `hour_increase_requests`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=64;

--
-- AUTO_INCREMENT for table `loyalty_coupons`
--
ALTER TABLE `loyalty_coupons`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `loyalty_points`
--
ALTER TABLE `loyalty_points`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `loyalty_tiers`
--
ALTER TABLE `loyalty_tiers`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `loyalty_transactions`
--
ALTER TABLE `loyalty_transactions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `meetings`
--
ALTER TABLE `meetings`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=90;

--
-- AUTO_INCREMENT for table `milestone_payments`
--
ALTER TABLE `milestone_payments`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `notes`
--
ALTER TABLE `notes`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `payouts`
--
ALTER TABLE `payouts`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payout_items`
--
ALTER TABLE `payout_items`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `projects`
--
ALTER TABLE `projects`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `project_assignments`
--
ALTER TABLE `project_assignments`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `project_feedback`
--
ALTER TABLE `project_feedback`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `project_milestones`
--
ALTER TABLE `project_milestones`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `project_templates`
--
ALTER TABLE `project_templates`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `referrals`
--
ALTER TABLE `referrals`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `referral_campaigns`
--
ALTER TABLE `referral_campaigns`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `referral_codes`
--
ALTER TABLE `referral_codes`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `referral_credit_transactions`
--
ALTER TABLE `referral_credit_transactions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `referral_credit_withdrawals`
--
ALTER TABLE `referral_credit_withdrawals`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `request_attachments`
--
ALTER TABLE `request_attachments`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `revision_requests`
--
ALTER TABLE `revision_requests`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `service_requests`
--
ALTER TABLE `service_requests`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT for table `skills`
--
ALTER TABLE `skills`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=66;

--
-- AUTO_INCREMENT for table `subtasks`
--
ALTER TABLE `subtasks`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=76;

--
-- AUTO_INCREMENT for table `tasks`
--
ALTER TABLE `tasks`
  MODIFY `taskID` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `task_deliverables`
--
ALTER TABLE `task_deliverables`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `task_schedules`
--
ALTER TABLE `task_schedules`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `tech_stack`
--
ALTER TABLE `tech_stack`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=326;

--
-- AUTO_INCREMENT for table `time_entries`
--
ALTER TABLE `time_entries`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `wallet_transactions`
--
ALTER TABLE `wallet_transactions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `adiutor_calendar_integrations`
--
ALTER TABLE `adiutor_calendar_integrations`
  ADD CONSTRAINT `adiutor_calendar_integrations_adiutor_id_foreign` FOREIGN KEY (`adiutor_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `adiutor_profiles`
--
ALTER TABLE `adiutor_profiles`
  ADD CONSTRAINT `adiutor_profiles_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `adiutor_skills`
--
ALTER TABLE `adiutor_skills`
  ADD CONSTRAINT `adiutor_skills_adiutor_id_foreign` FOREIGN KEY (`adiutor_id`) REFERENCES `adiutor_profiles` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `adiutor_skills_skill_id_foreign` FOREIGN KEY (`skill_id`) REFERENCES `skills` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `adiutor_work_schedules`
--
ALTER TABLE `adiutor_work_schedules`
  ADD CONSTRAINT `adiutor_work_schedules_adiutor_id_foreign` FOREIGN KEY (`adiutor_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `announcements`
--
ALTER TABLE `announcements`
  ADD CONSTRAINT `announcements_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `announcements_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `audit_logs`
--
ALTER TABLE `audit_logs`
  ADD CONSTRAINT `audit_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `budget_change_requests`
--
ALTER TABLE `budget_change_requests`
  ADD CONSTRAINT `budget_change_requests_adiutor_id_foreign` FOREIGN KEY (`adiutor_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `budget_change_requests_reviewed_by_foreign` FOREIGN KEY (`reviewed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `budget_change_requests_task_id_foreign` FOREIGN KEY (`task_id`) REFERENCES `tasks` (`taskID`) ON DELETE CASCADE;

--
-- Constraints for table `client_profiles`
--
ALTER TABLE `client_profiles`
  ADD CONSTRAINT `client_profiles_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `conversations`
--
ALTER TABLE `conversations`
  ADD CONSTRAINT `conversations_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `conversations_last_message_id_foreign` FOREIGN KEY (`last_message_id`) REFERENCES `messages` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `conversations_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `coupons`
--
ALTER TABLE `coupons`
  ADD CONSTRAINT `coupons_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `coupons_specific_request_id_foreign` FOREIGN KEY (`specific_request_id`) REFERENCES `service_requests` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `coupons_specific_user_id_foreign` FOREIGN KEY (`specific_user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `coupon_usages`
--
ALTER TABLE `coupon_usages`
  ADD CONSTRAINT `coupon_usages_coupon_id_foreign` FOREIGN KEY (`coupon_id`) REFERENCES `coupons` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `coupon_usages_payment_id_foreign` FOREIGN KEY (`payment_id`) REFERENCES `payments` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `coupon_usages_service_request_id_foreign` FOREIGN KEY (`service_request_id`) REFERENCES `service_requests` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `coupon_usages_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `custom_report_templates`
--
ALTER TABLE `custom_report_templates`
  ADD CONSTRAINT `custom_report_templates_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `documents`
--
ALTER TABLE `documents`
  ADD CONSTRAINT `documents_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `documents_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `documents_original_document_id_foreign` FOREIGN KEY (`original_document_id`) REFERENCES `documents` (`documentID`) ON DELETE SET NULL,
  ADD CONSTRAINT `documents_parent_document_id_foreign` FOREIGN KEY (`parent_document_id`) REFERENCES `documents` (`documentID`) ON DELETE SET NULL,
  ADD CONSTRAINT `documents_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `documents_service_request_id_foreign` FOREIGN KEY (`service_request_id`) REFERENCES `service_requests` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `documents_taskid_foreign` FOREIGN KEY (`taskID`) REFERENCES `tasks` (`taskID`) ON DELETE CASCADE,
  ADD CONSTRAINT `documents_uploaded_by_foreign` FOREIGN KEY (`uploaded_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `documents_verified_by_foreign` FOREIGN KEY (`verified_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `feedbacks`
--
ALTER TABLE `feedbacks`
  ADD CONSTRAINT `feedbacks_adiutor_id_foreign` FOREIGN KEY (`adiutor_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `feedbacks_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `feedbacks_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `feedbacks_resolved_by_foreign` FOREIGN KEY (`resolved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `feedbacks_responded_by_foreign` FOREIGN KEY (`responded_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `feedbacks_task_id_foreign` FOREIGN KEY (`task_id`) REFERENCES `tasks` (`taskID`) ON DELETE SET NULL;

--
-- Constraints for table `group_chats`
--
ALTER TABLE `group_chats`
  ADD CONSTRAINT `group_chats_archived_by_foreign` FOREIGN KEY (`archived_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `group_chats_last_message_id_foreign` FOREIGN KEY (`last_message_id`) REFERENCES `messages` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `group_chats_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `group_chat_members`
--
ALTER TABLE `group_chat_members`
  ADD CONSTRAINT `group_chat_members_group_chat_id_foreign` FOREIGN KEY (`group_chat_id`) REFERENCES `group_chats` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `group_chat_members_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `hour_increase_requests`
--
ALTER TABLE `hour_increase_requests`
  ADD CONSTRAINT `hour_increase_requests_adiutor_id_foreign` FOREIGN KEY (`adiutor_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `hour_increase_requests_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `hour_increase_requests_reviewed_by_foreign` FOREIGN KEY (`reviewed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `hour_increase_requests_task_id_foreign` FOREIGN KEY (`task_id`) REFERENCES `tasks` (`taskID`) ON DELETE CASCADE;

--
-- Constraints for table `loyalty_coupons`
--
ALTER TABLE `loyalty_coupons`
  ADD CONSTRAINT `loyalty_coupons_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `loyalty_coupons_used_on_project_id_foreign` FOREIGN KEY (`used_on_project_id`) REFERENCES `projects` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `loyalty_points`
--
ALTER TABLE `loyalty_points`
  ADD CONSTRAINT `loyalty_points_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `loyalty_transactions`
--
ALTER TABLE `loyalty_transactions`
  ADD CONSTRAINT `loyalty_transactions_coupon_id_foreign` FOREIGN KEY (`coupon_id`) REFERENCES `coupons` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `loyalty_transactions_payment_id_foreign` FOREIGN KEY (`payment_id`) REFERENCES `payments` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `loyalty_transactions_performed_by_foreign` FOREIGN KEY (`performed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `loyalty_transactions_service_request_id_foreign` FOREIGN KEY (`service_request_id`) REFERENCES `service_requests` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `loyalty_transactions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `meetings`
--
ALTER TABLE `meetings`
  ADD CONSTRAINT `meetings_admin_id_foreign` FOREIGN KEY (`admin_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `meetings_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `meetings_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `messages_group_chat_id_foreign` FOREIGN KEY (`group_chat_id`) REFERENCES `group_chats` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `messages_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `messages_recipient_id_foreign` FOREIGN KEY (`recipient_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `messages_sender_id_foreign` FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `messages_task_id_foreign` FOREIGN KEY (`task_id`) REFERENCES `tasks` (`taskID`) ON DELETE SET NULL;

--
-- Constraints for table `milestone_payments`
--
ALTER TABLE `milestone_payments`
  ADD CONSTRAINT `milestone_payments_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `milestone_payments_confirmed_by_foreign` FOREIGN KEY (`confirmed_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `milestone_payments_milestone_id_foreign` FOREIGN KEY (`milestone_id`) REFERENCES `project_milestones` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `milestone_payments_payment_id_foreign` FOREIGN KEY (`payment_id`) REFERENCES `payments` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `milestone_payments_service_request_id_foreign` FOREIGN KEY (`service_request_id`) REFERENCES `service_requests` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `notes`
--
ALTER TABLE `notes`
  ADD CONSTRAINT `notes_added_by_foreign` FOREIGN KEY (`added_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `notes_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `payments_confirmed_by_foreign` FOREIGN KEY (`confirmed_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `payments_milestone_id_foreign` FOREIGN KEY (`milestone_id`) REFERENCES `project_milestones` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `payments_service_request_id_foreign` FOREIGN KEY (`service_request_id`) REFERENCES `service_requests` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `payouts`
--
ALTER TABLE `payouts`
  ADD CONSTRAINT `payouts_adiutor_id_foreign` FOREIGN KEY (`adiutor_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `payouts_processed_by_foreign` FOREIGN KEY (`processed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `payout_items`
--
ALTER TABLE `payout_items`
  ADD CONSTRAINT `payout_items_payout_id_foreign` FOREIGN KEY (`payout_id`) REFERENCES `payouts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `payout_items_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `payout_items_task_id_foreign` FOREIGN KEY (`task_id`) REFERENCES `tasks` (`taskID`) ON DELETE SET NULL,
  ADD CONSTRAINT `payout_items_time_entry_id_foreign` FOREIGN KEY (`time_entry_id`) REFERENCES `time_entries` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `projects`
--
ALTER TABLE `projects`
  ADD CONSTRAINT `projects_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `projects_service_request_id_foreign` FOREIGN KEY (`service_request_id`) REFERENCES `service_requests` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `project_assignments`
--
ALTER TABLE `project_assignments`
  ADD CONSTRAINT `project_assignments_adiutor_id_foreign` FOREIGN KEY (`adiutor_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `project_assignments_fixed_rate_approved_by_foreign` FOREIGN KEY (`fixed_rate_approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `project_assignments_fixed_rate_payout_id_foreign` FOREIGN KEY (`fixed_rate_payout_id`) REFERENCES `payouts` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `project_assignments_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `project_feedback`
--
ALTER TABLE `project_feedback`
  ADD CONSTRAINT `project_feedback_adiutor_id_foreign` FOREIGN KEY (`adiutor_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `project_feedback_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `project_feedback_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `project_milestones`
--
ALTER TABLE `project_milestones`
  ADD CONSTRAINT `project_milestones_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `project_templates`
--
ALTER TABLE `project_templates`
  ADD CONSTRAINT `project_templates_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `referrals`
--
ALTER TABLE `referrals`
  ADD CONSTRAINT `referrals_first_payment_id_foreign` FOREIGN KEY (`first_payment_id`) REFERENCES `payments` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `referrals_referred_coupon_id_foreign` FOREIGN KEY (`referred_coupon_id`) REFERENCES `coupons` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `referrals_referred_id_foreign` FOREIGN KEY (`referred_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `referrals_referrer_coupon_id_foreign` FOREIGN KEY (`referrer_coupon_id`) REFERENCES `coupons` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `referrals_referrer_id_foreign` FOREIGN KEY (`referrer_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `referral_codes`
--
ALTER TABLE `referral_codes`
  ADD CONSTRAINT `referral_codes_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `referral_credit_transactions`
--
ALTER TABLE `referral_credit_transactions`
  ADD CONSTRAINT `referral_credit_transactions_performed_by_foreign` FOREIGN KEY (`performed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `referral_credit_transactions_referral_id_foreign` FOREIGN KEY (`referral_id`) REFERENCES `referrals` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `referral_credit_transactions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `referral_credit_transactions_withdrawal_id_foreign` FOREIGN KEY (`withdrawal_id`) REFERENCES `referral_credit_withdrawals` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `referral_credit_withdrawals`
--
ALTER TABLE `referral_credit_withdrawals`
  ADD CONSTRAINT `referral_credit_withdrawals_processed_by_foreign` FOREIGN KEY (`processed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `referral_credit_withdrawals_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `request_attachments`
--
ALTER TABLE `request_attachments`
  ADD CONSTRAINT `request_attachments_service_request_id_foreign` FOREIGN KEY (`service_request_id`) REFERENCES `service_requests` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `revision_requests`
--
ALTER TABLE `revision_requests`
  ADD CONSTRAINT `revision_requests_assigned_adiutor_id_foreign` FOREIGN KEY (`assigned_adiutor_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `revision_requests_completed_by_foreign` FOREIGN KEY (`completed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `revision_requests_document_id_foreign` FOREIGN KEY (`document_id`) REFERENCES `documents` (`documentID`) ON DELETE CASCADE,
  ADD CONSTRAINT `revision_requests_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `revision_requests_requested_by_foreign` FOREIGN KEY (`requested_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `revision_requests_reviewed_by_foreign` FOREIGN KEY (`reviewed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `revision_requests_service_request_id_foreign` FOREIGN KEY (`service_request_id`) REFERENCES `service_requests` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `revision_requests_task_id_foreign` FOREIGN KEY (`task_id`) REFERENCES `tasks` (`taskID`) ON DELETE SET NULL;

--
-- Constraints for table `service_requests`
--
ALTER TABLE `service_requests`
  ADD CONSTRAINT `service_requests_applied_coupon_id_foreign` FOREIGN KEY (`applied_coupon_id`) REFERENCES `coupons` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `service_requests_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `service_requests_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `service_requests_template_service_id_foreign` FOREIGN KEY (`template_service_id`) REFERENCES `services` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `showcase_screenshots`
--
ALTER TABLE `showcase_screenshots`
  ADD CONSTRAINT `showcase_screenshots_showcase_id_foreign` FOREIGN KEY (`showcase_id`) REFERENCES `showcases` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `subtasks`
--
ALTER TABLE `subtasks`
  ADD CONSTRAINT `subtasks_assigned_to_foreign` FOREIGN KEY (`assigned_to`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `subtasks_completed_by_foreign` FOREIGN KEY (`completed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `subtasks_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `subtasks_task_id_foreign` FOREIGN KEY (`task_id`) REFERENCES `tasks` (`taskID`) ON DELETE CASCADE;

--
-- Constraints for table `tasks`
--
ALTER TABLE `tasks`
  ADD CONSTRAINT `tasks_assignedto_foreign` FOREIGN KEY (`assignedTo`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `tasks_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `tasks_createdby_foreign` FOREIGN KEY (`createdBy`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `tasks_phase_id_foreign` FOREIGN KEY (`phase_id`) REFERENCES `project_milestones` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `tasks_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `tasks_service_request_id_foreign` FOREIGN KEY (`service_request_id`) REFERENCES `service_requests` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `task_deliverables`
--
ALTER TABLE `task_deliverables`
  ADD CONSTRAINT `task_deliverables_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `task_deliverables_document_id_foreign` FOREIGN KEY (`document_id`) REFERENCES `documents` (`documentID`) ON DELETE SET NULL,
  ADD CONSTRAINT `task_deliverables_task_id_foreign` FOREIGN KEY (`task_id`) REFERENCES `tasks` (`taskID`) ON DELETE CASCADE,
  ADD CONSTRAINT `task_deliverables_uploaded_by_foreign` FOREIGN KEY (`uploaded_by`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `task_schedules`
--
ALTER TABLE `task_schedules`
  ADD CONSTRAINT `task_schedules_adiutor_id_foreign` FOREIGN KEY (`adiutor_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `task_schedules_task_id_foreign` FOREIGN KEY (`task_id`) REFERENCES `tasks` (`taskID`) ON DELETE CASCADE;

--
-- Constraints for table `time_entries`
--
ALTER TABLE `time_entries`
  ADD CONSTRAINT `time_entries_adiutor_id_foreign` FOREIGN KEY (`adiutor_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `time_entries_adjusted_by_foreign` FOREIGN KEY (`adjusted_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `time_entries_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `time_entries_payout_id_foreign` FOREIGN KEY (`payout_id`) REFERENCES `payouts` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `time_entries_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `time_entries_task_id_foreign` FOREIGN KEY (`task_id`) REFERENCES `tasks` (`taskID`) ON DELETE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_referred_by_user_id_foreign` FOREIGN KEY (`referred_by_user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `wallet_transactions`
--
ALTER TABLE `wallet_transactions`
  ADD CONSTRAINT `wallet_transactions_performed_by_foreign` FOREIGN KEY (`performed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `wallet_transactions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
