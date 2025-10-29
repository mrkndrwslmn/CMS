/*
SQLyog Ultimate v10.00 Beta1
MySQL - 8.0.42-azure : Database - cms
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`cms` /*!40100 DEFAULT CHARACTER SET utf8mb3 */ /*!80016 DEFAULT ENCRYPTION='N' */;

USE `cms`;

/*Table structure for table `adiutor_profiles` */

DROP TABLE IF EXISTS `adiutor_profiles`;

CREATE TABLE `adiutor_profiles` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `bio` text COLLATE utf8mb4_unicode_ci,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `hourly_rate` decimal(8,2) DEFAULT NULL,
  `availability` json DEFAULT NULL,
  `portfolio_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `linkedin_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `github_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `experience` text COLLATE utf8mb4_unicode_ci,
  `languages` json DEFAULT NULL,
  `location` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_verified` tinyint(1) NOT NULL DEFAULT '0',
  `rating` decimal(3,2) NOT NULL DEFAULT '0.00',
  `total_projects` int NOT NULL DEFAULT '0',
  `status` enum('active','inactive','busy') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `adiutor_profiles_user_id_foreign` (`user_id`),
  KEY `adiutor_profiles_status_is_verified_index` (`status`,`is_verified`),
  KEY `adiutor_profiles_rating_index` (`rating`),
  CONSTRAINT `adiutor_profiles_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `adiutor_skills` */

DROP TABLE IF EXISTS `adiutor_skills`;

CREATE TABLE `adiutor_skills` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `adiutor_id` bigint unsigned NOT NULL,
  `skill_id` bigint unsigned NOT NULL,
  `proficiency_level` enum('beginner','intermediate','advanced','expert') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'intermediate',
  `years_experience` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `adiutor_skills_adiutor_id_skill_id_unique` (`adiutor_id`,`skill_id`),
  KEY `adiutor_skills_skill_id_foreign` (`skill_id`),
  KEY `adiutor_skills_proficiency_level_index` (`proficiency_level`),
  CONSTRAINT `adiutor_skills_adiutor_id_foreign` FOREIGN KEY (`adiutor_id`) REFERENCES `adiutor_profiles` (`id`) ON DELETE CASCADE,
  CONSTRAINT `adiutor_skills_skill_id_foreign` FOREIGN KEY (`skill_id`) REFERENCES `skills` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `announcements` */

DROP TABLE IF EXISTS `announcements`;

CREATE TABLE `announcements` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `priority` enum('low','medium','high') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'medium',
  `status` enum('active','scheduled','draft','expired') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `starts_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned NOT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `announcements_created_by_foreign` (`created_by`),
  KEY `announcements_status_expires_at_index` (`status`,`expires_at`),
  KEY `announcements_updated_by_foreign` (`updated_by`),
  CONSTRAINT `announcements_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `announcements_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `audit_logs` */

DROP TABLE IF EXISTS `audit_logs`;

CREATE TABLE `audit_logs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `auditable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `auditable_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `action` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `event_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'model_change',
  `old_values` json DEFAULT NULL,
  `new_values` json DEFAULT NULL,
  `metadata` json DEFAULT NULL,
  `ip_address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `audit_logs_auditable_type_auditable_id_index` (`auditable_type`,`auditable_id`),
  KEY `audit_logs_user_id_created_at_index` (`user_id`,`created_at`),
  KEY `audit_logs_action_created_at_index` (`action`,`created_at`),
  KEY `audit_logs_event_type_created_at_index` (`event_type`,`created_at`),
  CONSTRAINT `audit_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `budget_change_requests` */

DROP TABLE IF EXISTS `budget_change_requests`;

CREATE TABLE `budget_change_requests` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `task_id` bigint unsigned NOT NULL,
  `adiutor_id` bigint unsigned NOT NULL,
  `current_budget` decimal(10,2) NOT NULL,
  `requested_budget` decimal(10,2) NOT NULL,
  `reason` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('pending','approved','rejected') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `reviewed_by` bigint unsigned DEFAULT NULL,
  `reviewed_at` timestamp NULL DEFAULT NULL,
  `review_notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `budget_change_requests_task_id_foreign` (`task_id`),
  KEY `budget_change_requests_reviewed_by_foreign` (`reviewed_by`),
  KEY `budget_change_requests_status_created_at_index` (`status`,`created_at`),
  KEY `budget_change_requests_adiutor_id_index` (`adiutor_id`),
  CONSTRAINT `budget_change_requests_adiutor_id_foreign` FOREIGN KEY (`adiutor_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `budget_change_requests_reviewed_by_foreign` FOREIGN KEY (`reviewed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `budget_change_requests_task_id_foreign` FOREIGN KEY (`task_id`) REFERENCES `tasks` (`taskID`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `cache` */

DROP TABLE IF EXISTS `cache`;

CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `cache_locks` */

DROP TABLE IF EXISTS `cache_locks`;

CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `client_profiles` */

DROP TABLE IF EXISTS `client_profiles`;

CREATE TABLE `client_profiles` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `company_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `company_description` text COLLATE utf8mb4_unicode_ci,
  `industry` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `company_size` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text COLLATE utf8mb4_unicode_ci,
  `bio` text COLLATE utf8mb4_unicode_ci,
  `website` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `linkedin` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `twitter` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `preferred_contact_methods` json DEFAULT NULL,
  `timezone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `business_hours` json DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `is_verified` tinyint(1) NOT NULL DEFAULT '0',
  `total_projects` int NOT NULL DEFAULT '0',
  `total_spent` decimal(12,2) NOT NULL DEFAULT '0.00',
  `client_type` enum('individual','small_business','enterprise') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'individual',
  `contact_person` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contact_email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contact_phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `billing_address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tax_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `client_profiles_user_id_foreign` (`user_id`),
  KEY `client_profiles_client_type_is_verified_index` (`client_type`,`is_verified`),
  KEY `client_profiles_industry_index` (`industry`),
  CONSTRAINT `client_profiles_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `conversations` */

DROP TABLE IF EXISTS `conversations`;

CREATE TABLE `conversations` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `conversation_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `project_id` bigint unsigned NOT NULL,
  `client_id` bigint unsigned NOT NULL,
  `last_message_id` bigint unsigned DEFAULT NULL,
  `last_message_at` timestamp NULL DEFAULT NULL,
  `unread_count_client` int NOT NULL DEFAULT '0',
  `unread_count_admin` int NOT NULL DEFAULT '0',
  `is_archived` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `conversations_conversation_id_unique` (`conversation_id`),
  KEY `conversations_last_message_id_foreign` (`last_message_id`),
  KEY `conversations_project_id_is_archived_index` (`project_id`,`is_archived`),
  KEY `conversations_client_id_last_message_at_index` (`client_id`,`last_message_at`),
  CONSTRAINT `conversations_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `conversations_last_message_id_foreign` FOREIGN KEY (`last_message_id`) REFERENCES `messages` (`id`) ON DELETE SET NULL,
  CONSTRAINT `conversations_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `documents` */

DROP TABLE IF EXISTS `documents`;

CREATE TABLE `documents` (
  `documentID` bigint unsigned NOT NULL AUTO_INCREMENT,
  `documentable_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `documentable_id` bigint unsigned DEFAULT NULL,
  `taskID` bigint unsigned DEFAULT NULL,
  `service_request_id` bigint unsigned DEFAULT NULL,
  `project_id` bigint unsigned DEFAULT NULL,
  `client_id` bigint unsigned DEFAULT NULL,
  `fileName` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `filePath` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fileType` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fileSize` bigint unsigned NOT NULL DEFAULT '0',
  `document_type` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `is_public` tinyint(1) NOT NULL DEFAULT '0',
  `is_archived` tinyint(1) NOT NULL DEFAULT '0',
  `uploaded_by` bigint unsigned DEFAULT NULL,
  `uploadedAt` timestamp NULL DEFAULT NULL,
  `verified_by` bigint unsigned DEFAULT NULL,
  `verified_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`documentID`),
  KEY `documents_documentable_type_documentable_id_index` (`documentable_type`,`documentable_id`),
  KEY `documents_taskid_foreign` (`taskID`),
  KEY `documents_service_request_id_foreign` (`service_request_id`),
  KEY `documents_project_id_foreign` (`project_id`),
  KEY `documents_client_id_foreign` (`client_id`),
  KEY `documents_uploaded_by_foreign` (`uploaded_by`),
  KEY `documents_verified_by_foreign` (`verified_by`),
  CONSTRAINT `documents_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `documents_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE,
  CONSTRAINT `documents_service_request_id_foreign` FOREIGN KEY (`service_request_id`) REFERENCES `service_requests` (`id`) ON DELETE CASCADE,
  CONSTRAINT `documents_taskid_foreign` FOREIGN KEY (`taskID`) REFERENCES `tasks` (`taskID`) ON DELETE CASCADE,
  CONSTRAINT `documents_uploaded_by_foreign` FOREIGN KEY (`uploaded_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `documents_verified_by_foreign` FOREIGN KEY (`verified_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `failed_jobs` */

DROP TABLE IF EXISTS `failed_jobs`;

CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `feedbacks` */

DROP TABLE IF EXISTS `feedbacks`;

CREATE TABLE `feedbacks` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `client_id` bigint unsigned NOT NULL,
  `adiutor_id` bigint unsigned DEFAULT NULL,
  `task_id` bigint unsigned DEFAULT NULL,
  `project_id` bigint unsigned DEFAULT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `rating` int DEFAULT NULL,
  `type` enum('general','service','technical','complaint','suggestion') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'general',
  `status` enum('pending','reviewed','in_progress','resolved','closed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `priority` enum('low','medium','high','urgent') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'medium',
  `admin_response` text COLLATE utf8mb4_unicode_ci,
  `responded_by` bigint unsigned DEFAULT NULL,
  `responded_at` timestamp NULL DEFAULT NULL,
  `resolved_by` bigint unsigned DEFAULT NULL,
  `resolved_at` timestamp NULL DEFAULT NULL,
  `internal_notes` text COLLATE utf8mb4_unicode_ci,
  `category` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tags` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `feedbacks_task_id_foreign` (`task_id`),
  KEY `feedbacks_project_id_foreign` (`project_id`),
  KEY `feedbacks_responded_by_foreign` (`responded_by`),
  KEY `feedbacks_resolved_by_foreign` (`resolved_by`),
  KEY `feedbacks_client_id_index` (`client_id`),
  KEY `feedbacks_adiutor_id_index` (`adiutor_id`),
  KEY `feedbacks_status_index` (`status`),
  KEY `feedbacks_rating_index` (`rating`),
  KEY `feedbacks_created_at_index` (`created_at`),
  CONSTRAINT `feedbacks_adiutor_id_foreign` FOREIGN KEY (`adiutor_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `feedbacks_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `feedbacks_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE SET NULL,
  CONSTRAINT `feedbacks_resolved_by_foreign` FOREIGN KEY (`resolved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `feedbacks_responded_by_foreign` FOREIGN KEY (`responded_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `feedbacks_task_id_foreign` FOREIGN KEY (`task_id`) REFERENCES `tasks` (`taskID`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `job_batches` */

DROP TABLE IF EXISTS `job_batches`;

CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `jobs` */

DROP TABLE IF EXISTS `jobs`;

CREATE TABLE `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `meetings` */

DROP TABLE IF EXISTS `meetings`;

CREATE TABLE `meetings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `project_id` bigint unsigned NOT NULL,
  `client_id` bigint unsigned NOT NULL,
  `admin_id` bigint unsigned DEFAULT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `requested_date` date NOT NULL,
  `requested_time` time NOT NULL,
  `rescheduled_date` date DEFAULT NULL,
  `rescheduled_time` time DEFAULT NULL,
  `scheduled_date` date DEFAULT NULL,
  `scheduled_time` time DEFAULT NULL,
  `status` enum('pending','approved','rescheduled','rejected','completed','cancelled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `zoom_meeting_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `zoom_join_url` text COLLATE utf8mb4_unicode_ci,
  `zoom_start_url` text COLLATE utf8mb4_unicode_ci,
  `zoom_password` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `admin_notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `meetings_admin_id_foreign` (`admin_id`),
  KEY `meetings_project_id_index` (`project_id`),
  KEY `meetings_client_id_index` (`client_id`),
  KEY `meetings_status_index` (`status`),
  KEY `meetings_scheduled_date_index` (`scheduled_date`),
  CONSTRAINT `meetings_admin_id_foreign` FOREIGN KEY (`admin_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `meetings_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `meetings_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `messages` */

DROP TABLE IF EXISTS `messages`;

CREATE TABLE `messages` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `conversation_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sender_id` bigint unsigned NOT NULL,
  `recipient_id` bigint unsigned NOT NULL,
  `subject` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `attachments` json DEFAULT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT '0',
  `status` enum('sent','delivered','read') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'sent',
  `message_type` enum('general','project','task','system') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'general',
  `project_id` bigint unsigned DEFAULT NULL,
  `task_id` bigint unsigned DEFAULT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `delivered_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `messages_task_id_foreign` (`task_id`),
  KEY `messages_recipient_id_is_read_index` (`recipient_id`,`is_read`),
  KEY `messages_sender_id_created_at_index` (`sender_id`,`created_at`),
  KEY `messages_project_id_message_type_index` (`project_id`,`message_type`),
  KEY `messages_conversation_id_index` (`conversation_id`),
  KEY `messages_project_id_created_at_index` (`project_id`,`created_at`),
  KEY `messages_status_index` (`status`),
  CONSTRAINT `messages_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE SET NULL,
  CONSTRAINT `messages_recipient_id_foreign` FOREIGN KEY (`recipient_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `messages_sender_id_foreign` FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `messages_task_id_foreign` FOREIGN KEY (`task_id`) REFERENCES `tasks` (`taskID`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `migrations` */

DROP TABLE IF EXISTS `migrations`;

CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `milestone_payments` */

DROP TABLE IF EXISTS `milestone_payments`;

CREATE TABLE `milestone_payments` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `milestone_id` bigint unsigned NOT NULL,
  `payment_id` bigint unsigned DEFAULT NULL,
  `service_request_id` bigint unsigned NOT NULL,
  `client_id` bigint unsigned NOT NULL,
  `amount_due` decimal(10,2) NOT NULL,
  `amount_paid` decimal(10,2) NOT NULL DEFAULT '0.00',
  `status` enum('pending','partial','paid','overdue','cancelled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `due_date` date DEFAULT NULL,
  `paid_at` timestamp NULL DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `confirmed_by` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `milestone_payments_payment_id_foreign` (`payment_id`),
  KEY `milestone_payments_client_id_foreign` (`client_id`),
  KEY `milestone_payments_confirmed_by_foreign` (`confirmed_by`),
  KEY `milestone_payments_milestone_id_status_index` (`milestone_id`,`status`),
  KEY `milestone_payments_service_request_id_status_index` (`service_request_id`,`status`),
  KEY `milestone_payments_status_index` (`status`),
  CONSTRAINT `milestone_payments_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `milestone_payments_confirmed_by_foreign` FOREIGN KEY (`confirmed_by`) REFERENCES `users` (`id`),
  CONSTRAINT `milestone_payments_milestone_id_foreign` FOREIGN KEY (`milestone_id`) REFERENCES `project_milestones` (`id`) ON DELETE CASCADE,
  CONSTRAINT `milestone_payments_payment_id_foreign` FOREIGN KEY (`payment_id`) REFERENCES `payments` (`id`) ON DELETE SET NULL,
  CONSTRAINT `milestone_payments_service_request_id_foreign` FOREIGN KEY (`service_request_id`) REFERENCES `service_requests` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `notifications` */

DROP TABLE IF EXISTS `notifications`;

CREATE TABLE `notifications` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notifiable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notifiable_id` bigint unsigned NOT NULL,
  `data` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `notifications_notifiable_type_notifiable_id_index` (`notifiable_type`,`notifiable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `password_reset_tokens` */

DROP TABLE IF EXISTS `password_reset_tokens`;

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `payments` */

DROP TABLE IF EXISTS `payments`;

CREATE TABLE `payments` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `service_request_id` bigint unsigned NOT NULL,
  `milestone_id` bigint unsigned DEFAULT NULL,
  `payment_type` enum('full_payment','milestone_payment','downpayment','remaining_balance') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `client_id` bigint unsigned NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `payment_method` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payment_reference` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('pending','confirmed','failed','refunded','cancelled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `confirmed_at` timestamp NULL DEFAULT NULL,
  `confirmed_by` bigint unsigned DEFAULT NULL,
  `payment_details` json DEFAULT NULL,
  `transaction_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gateway_response` text COLLATE utf8mb4_unicode_ci,
  `gateway_fee` decimal(8,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `payments_client_id_foreign` (`client_id`),
  KEY `payments_confirmed_by_foreign` (`confirmed_by`),
  KEY `payments_service_request_id_status_index` (`service_request_id`,`status`),
  KEY `payments_status_confirmed_at_index` (`status`,`confirmed_at`),
  KEY `payments_payment_reference_index` (`payment_reference`),
  KEY `payments_milestone_id_status_index` (`milestone_id`,`status`),
  KEY `payments_payment_type_status_index` (`payment_type`,`status`),
  KEY `payments_service_request_id_payment_type_index` (`service_request_id`,`payment_type`),
  CONSTRAINT `payments_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `payments_confirmed_by_foreign` FOREIGN KEY (`confirmed_by`) REFERENCES `users` (`id`),
  CONSTRAINT `payments_milestone_id_foreign` FOREIGN KEY (`milestone_id`) REFERENCES `project_milestones` (`id`) ON DELETE SET NULL,
  CONSTRAINT `payments_service_request_id_foreign` FOREIGN KEY (`service_request_id`) REFERENCES `service_requests` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `project_assignments` */

DROP TABLE IF EXISTS `project_assignments`;

CREATE TABLE `project_assignments` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `project_id` bigint unsigned NOT NULL,
  `adiutor_id` bigint unsigned NOT NULL,
  `agreed_rate` decimal(8,2) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `expected_completion` date DEFAULT NULL,
  `status` enum('assigned','active','completed','removed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'assigned',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `progress_percentage` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `project_assignments_project_id_adiutor_id_unique` (`project_id`,`adiutor_id`),
  KEY `project_assignments_adiutor_id_status_index` (`adiutor_id`,`status`),
  CONSTRAINT `project_assignments_adiutor_id_foreign` FOREIGN KEY (`adiutor_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `project_assignments_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `project_feedback` */

DROP TABLE IF EXISTS `project_feedback`;

CREATE TABLE `project_feedback` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `project_id` bigint unsigned NOT NULL,
  `client_id` bigint unsigned NOT NULL,
  `adiutor_id` bigint unsigned DEFAULT NULL,
  `rating` int DEFAULT NULL,
  `feedback` text COLLATE utf8mb4_unicode_ci,
  `feedback_type` enum('project','adiutor','overall') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'project',
  `is_public` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `project_feedback_client_id_foreign` (`client_id`),
  KEY `project_feedback_adiutor_id_foreign` (`adiutor_id`),
  KEY `project_feedback_project_id_feedback_type_index` (`project_id`,`feedback_type`),
  KEY `project_feedback_rating_index` (`rating`),
  CONSTRAINT `project_feedback_adiutor_id_foreign` FOREIGN KEY (`adiutor_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `project_feedback_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `project_feedback_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `project_milestones` */

DROP TABLE IF EXISTS `project_milestones`;

CREATE TABLE `project_milestones` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `project_id` bigint unsigned NOT NULL,
  `phase_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phase_description` text COLLATE utf8mb4_unicode_ci,
  `phase_order` int NOT NULL DEFAULT '1',
  `milestone_number` int NOT NULL DEFAULT '1',
  `percentage` decimal(5,2) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `start_date` date DEFAULT NULL,
  `due_date` date DEFAULT NULL,
  `completed_date` date DEFAULT NULL,
  `status` enum('pending','in_progress','completed','paid') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `is_paid` tinyint(1) NOT NULL DEFAULT '0',
  `paid_at` timestamp NULL DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `deliverables` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `project_milestones_project_id_phase_order_index` (`project_id`,`phase_order`),
  KEY `project_milestones_project_id_is_paid_index` (`project_id`,`is_paid`),
  KEY `project_milestones_status_index` (`status`),
  KEY `project_milestones_milestone_number_index` (`milestone_number`),
  CONSTRAINT `project_milestones_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `project_templates` */

DROP TABLE IF EXISTS `project_templates`;

CREATE TABLE `project_templates` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `category` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'general',
  `default_tasks` json DEFAULT NULL,
  `skills_required` json DEFAULT NULL,
  `milestones_template` json DEFAULT NULL,
  `estimated_budget_min` decimal(10,2) DEFAULT NULL,
  `estimated_budget_max` decimal(10,2) DEFAULT NULL,
  `estimated_duration_days` int DEFAULT NULL,
  `budget_type` enum('fixed','hourly') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'fixed',
  `payment_type` enum('full_payment','milestone_payment','downpayment') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'milestone_payment',
  `requirements_template` text COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_by` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `project_templates_category_is_active_index` (`category`,`is_active`),
  KEY `project_templates_created_by_index` (`created_by`),
  CONSTRAINT `project_templates_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `projects` */

DROP TABLE IF EXISTS `projects`;

CREATE TABLE `projects` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `service_request_id` bigint unsigned NOT NULL,
  `client_id` bigint unsigned NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `requirements` json DEFAULT NULL,
  `skills_required` json DEFAULT NULL,
  `budget` decimal(10,2) DEFAULT NULL,
  `budget_type` enum('fixed','hourly') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'fixed',
  `deadline` date DEFAULT NULL,
  `started_at` timestamp NULL DEFAULT NULL,
  `completed_at` timestamp NULL DEFAULT NULL,
  `priority` enum('low','medium','high','urgent') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'medium',
  `status` enum('active','in_progress','review','completed','cancelled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `attachments` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `projects_service_request_id_unique` (`service_request_id`),
  KEY `projects_status_priority_index` (`status`,`priority`),
  KEY `projects_client_id_status_index` (`client_id`,`status`),
  KEY `projects_deadline_index` (`deadline`),
  CONSTRAINT `projects_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `projects_service_request_id_foreign` FOREIGN KEY (`service_request_id`) REFERENCES `service_requests` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `request_attachments` */

DROP TABLE IF EXISTS `request_attachments`;

CREATE TABLE `request_attachments` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `service_request_id` bigint unsigned NOT NULL,
  `original_filename` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `stored_filename` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_url` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mime_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_size` bigint NOT NULL,
  `file_hash` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `request_attachments_service_request_id_foreign` (`service_request_id`),
  KEY `request_attachments_mime_type_index` (`mime_type`),
  KEY `request_attachments_file_hash_index` (`file_hash`),
  CONSTRAINT `request_attachments_service_request_id_foreign` FOREIGN KEY (`service_request_id`) REFERENCES `service_requests` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `revision_requests` */

DROP TABLE IF EXISTS `revision_requests`;

CREATE TABLE `revision_requests` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `document_id` bigint unsigned DEFAULT NULL,
  `requested_by` bigint unsigned NOT NULL,
  `reason` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `requested_due_date` date DEFAULT NULL,
  `revision_number` int NOT NULL DEFAULT '1',
  `status` enum('pending','approved','rejected','completed','cancelled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `priority` enum('normal','high','urgent') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'normal',
  `reviewed_by` bigint unsigned DEFAULT NULL,
  `admin_notes` text COLLATE utf8mb4_unicode_ci,
  `reviewed_at` timestamp NULL DEFAULT NULL,
  `task_id` bigint unsigned DEFAULT NULL,
  `service_request_id` bigint unsigned DEFAULT NULL,
  `project_id` bigint unsigned DEFAULT NULL,
  `source_type` enum('task','project') COLLATE utf8mb4_unicode_ci NOT NULL,
  `assigned_adiutor_id` bigint unsigned DEFAULT NULL,
  `completed_at` timestamp NULL DEFAULT NULL,
  `completed_by` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `revision_requests_requested_by_foreign` (`requested_by`),
  KEY `revision_requests_reviewed_by_foreign` (`reviewed_by`),
  KEY `revision_requests_task_id_foreign` (`task_id`),
  KEY `revision_requests_service_request_id_foreign` (`service_request_id`),
  KEY `revision_requests_project_id_foreign` (`project_id`),
  KEY `revision_requests_completed_by_foreign` (`completed_by`),
  KEY `revision_requests_status_index` (`status`),
  KEY `revision_requests_source_type_index` (`source_type`),
  KEY `revision_requests_document_id_status_index` (`document_id`,`status`),
  KEY `revision_requests_assigned_adiutor_id_status_index` (`assigned_adiutor_id`,`status`),
  CONSTRAINT `revision_requests_assigned_adiutor_id_foreign` FOREIGN KEY (`assigned_adiutor_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `revision_requests_completed_by_foreign` FOREIGN KEY (`completed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `revision_requests_document_id_foreign` FOREIGN KEY (`document_id`) REFERENCES `documents` (`documentID`) ON DELETE CASCADE,
  CONSTRAINT `revision_requests_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE,
  CONSTRAINT `revision_requests_requested_by_foreign` FOREIGN KEY (`requested_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `revision_requests_reviewed_by_foreign` FOREIGN KEY (`reviewed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `revision_requests_service_request_id_foreign` FOREIGN KEY (`service_request_id`) REFERENCES `service_requests` (`id`) ON DELETE CASCADE,
  CONSTRAINT `revision_requests_task_id_foreign` FOREIGN KEY (`task_id`) REFERENCES `tasks` (`taskID`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `service_requests` */

DROP TABLE IF EXISTS `service_requests`;

CREATE TABLE `service_requests` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `client_id` bigint unsigned NOT NULL,
  `contact_method` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `contact_details` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `service_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `project_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `request_description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `deadline` date DEFAULT NULL,
  `expectations` text COLLATE utf8mb4_unicode_ci,
  `additional_notes` text COLLATE utf8mb4_unicode_ci,
  `requirements` json DEFAULT NULL,
  `status` enum('pending','approved','rejected','pending_payment','paid','in_progress','completed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `priority` enum('low','medium','high','urgent') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'medium',
  `estimated_budget` decimal(10,2) DEFAULT NULL,
  `approved_budget` decimal(10,2) DEFAULT NULL,
  `payment_type` enum('full_payment','milestone_payment','downpayment') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `downpayment_percentage` decimal(5,2) DEFAULT NULL,
  `downpayment_amount` decimal(10,2) DEFAULT NULL,
  `remaining_balance` decimal(10,2) DEFAULT NULL,
  `downpayment_paid` tinyint(1) NOT NULL DEFAULT '0',
  `downpayment_paid_at` timestamp NULL DEFAULT NULL,
  `remaining_balance_paid` tinyint(1) NOT NULL DEFAULT '0',
  `remaining_balance_paid_at` timestamp NULL DEFAULT NULL,
  `total_milestones` int DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `approved_by` bigint unsigned DEFAULT NULL,
  `reviewed_at` timestamp NULL DEFAULT NULL,
  `payment_method` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_due_date` timestamp NULL DEFAULT NULL,
  `payment_confirmed_at` timestamp NULL DEFAULT NULL,
  `payment_reference` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_instructions` text COLLATE utf8mb4_unicode_ci,
  `admin_notes` text COLLATE utf8mb4_unicode_ci,
  `rejection_reason` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `service_requests_approved_by_foreign` (`approved_by`),
  KEY `service_requests_status_priority_index` (`status`,`priority`),
  KEY `service_requests_client_id_status_index` (`client_id`,`status`),
  KEY `service_requests_deadline_index` (`deadline`),
  KEY `service_requests_payment_type_index` (`payment_type`),
  CONSTRAINT `service_requests_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`),
  CONSTRAINT `service_requests_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `services` */

DROP TABLE IF EXISTS `services`;

CREATE TABLE `services` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `base_price` decimal(10,2) DEFAULT NULL,
  `category` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `features` json DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `icon` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `estimated_duration_days` int DEFAULT NULL,
  `required_skills` json DEFAULT NULL,
  `requirements` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `services_category_is_active_index` (`category`,`is_active`),
  KEY `services_is_active_index` (`is_active`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `sessions` */

DROP TABLE IF EXISTS `sessions`;

CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`),
  KEY `sessions_id_index` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `skills` */

DROP TABLE IF EXISTS `skills`;

CREATE TABLE `skills` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `category` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `skills_name_unique` (`name`),
  KEY `skills_category_is_active_index` (`category`,`is_active`)
) ENGINE=InnoDB AUTO_INCREMENT=66 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `tasks` */

DROP TABLE IF EXISTS `tasks`;

CREATE TABLE `tasks` (
  `taskID` bigint unsigned NOT NULL AUTO_INCREMENT,
  `project_id` bigint unsigned NOT NULL,
  `phase_id` bigint unsigned DEFAULT NULL,
  `assignedTo` bigint unsigned DEFAULT NULL,
  `createdBy` bigint unsigned NOT NULL,
  `client_id` bigint unsigned NOT NULL,
  `taskTitle` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `taskDescription` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('pending','in_progress','completed','cancelled','pending_approval') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `priority` enum('low','medium','high','urgent') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'medium',
  `deadline` timestamp NULL DEFAULT NULL,
  `dateAssigned` timestamp NOT NULL DEFAULT '2025-10-29 13:19:05',
  `completedAt` timestamp NULL DEFAULT NULL,
  `allocated_budget` decimal(10,2) DEFAULT NULL,
  `actual_cost` decimal(10,2) DEFAULT NULL,
  `progress_percentage` int NOT NULL DEFAULT '0',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `completion_notes` text COLLATE utf8mb4_unicode_ci,
  `service_request_id` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`taskID`),
  KEY `tasks_createdby_foreign` (`createdBy`),
  KEY `tasks_service_request_id_foreign` (`service_request_id`),
  KEY `tasks_status_priority_deadline_index` (`status`,`priority`,`deadline`),
  KEY `tasks_assignedto_status_index` (`assignedTo`,`status`),
  KEY `tasks_project_id_status_index` (`project_id`,`status`),
  KEY `tasks_client_id_status_index` (`client_id`,`status`),
  KEY `tasks_project_id_phase_id_index` (`project_id`,`phase_id`),
  KEY `tasks_phase_id_status_index` (`phase_id`,`status`),
  KEY `tasks_status_priority_index` (`status`,`priority`),
  CONSTRAINT `tasks_assignedto_foreign` FOREIGN KEY (`assignedTo`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `tasks_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `tasks_createdby_foreign` FOREIGN KEY (`createdBy`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `tasks_phase_id_foreign` FOREIGN KEY (`phase_id`) REFERENCES `project_milestones` (`id`) ON DELETE SET NULL,
  CONSTRAINT `tasks_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE,
  CONSTRAINT `tasks_service_request_id_foreign` FOREIGN KEY (`service_request_id`) REFERENCES `service_requests` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `time_entries` */

DROP TABLE IF EXISTS `time_entries`;

CREATE TABLE `time_entries` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `task_id` bigint unsigned NOT NULL,
  `project_id` bigint unsigned NOT NULL,
  `adiutor_id` bigint unsigned NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `start_time` timestamp NOT NULL,
  `end_time` timestamp NULL DEFAULT NULL,
  `duration_minutes` int DEFAULT NULL,
  `hourly_rate` decimal(8,2) DEFAULT NULL,
  `amount` decimal(10,2) DEFAULT NULL,
  `is_billable` tinyint(1) NOT NULL DEFAULT '1',
  `is_approved` tinyint(1) NOT NULL DEFAULT '0',
  `approved_by` bigint unsigned DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `time_entries_approved_by_foreign` (`approved_by`),
  KEY `time_entries_task_id_adiutor_id_index` (`task_id`,`adiutor_id`),
  KEY `time_entries_project_id_adiutor_id_index` (`project_id`,`adiutor_id`),
  KEY `time_entries_adiutor_id_started_at_index` (`adiutor_id`,`start_time`),
  KEY `time_entries_is_billable_is_approved_index` (`is_billable`,`is_approved`),
  KEY `time_entries_adiutor_started_index` (`adiutor_id`,`start_time`),
  KEY `time_entries_adiutor_ended_index` (`adiutor_id`,`end_time`),
  CONSTRAINT `time_entries_adiutor_id_foreign` FOREIGN KEY (`adiutor_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `time_entries_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `time_entries_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE,
  CONSTRAINT `time_entries_task_id_foreign` FOREIGN KEY (`task_id`) REFERENCES `tasks` (`taskID`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `users` */

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `fullName` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `auth0_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `auth_provider` enum('local','auth0') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'local',
  `auth0_profile` json DEFAULT NULL,
  `last_auth0_sync` timestamp NULL DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` enum('admin','client','adiutor') COLLATE utf8mb4_unicode_ci NOT NULL,
  `phoneNumber` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `profilePic` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('active','inactive') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `dateCreated` timestamp NOT NULL DEFAULT '2025-10-29 13:18:53',
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fcm_token` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fcm_token_updated_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  UNIQUE KEY `users_auth0_id_unique` (`auth0_id`),
  KEY `users_role_status_index` (`role`,`status`),
  KEY `users_email_index` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
