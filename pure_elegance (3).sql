-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 12, 2026 at 06:58 PM
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
-- Database: `pure_elegance`
--

-- --------------------------------------------------------

--
-- Table structure for table `activity_logs`
--

CREATE TABLE `activity_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `action` varchar(255) NOT NULL,
  `model_type` varchar(255) DEFAULT NULL,
  `model_id` bigint(20) UNSIGNED DEFAULT NULL,
  `changes` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`changes`)),
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `banners`
--

CREATE TABLE `banners` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `subtitle` varchar(255) DEFAULT NULL,
  `cta_text` varchar(255) DEFAULT NULL,
  `cta_link` varchar(255) DEFAULT NULL,
  `image_path` varchar(255) NOT NULL,
  `position` enum('hero','category','promotional') NOT NULL DEFAULT 'hero',
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `starts_at` timestamp NULL DEFAULT NULL,
  `ends_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `carts`
--

CREATE TABLE `carts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `session_id` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `carts`
--

INSERT INTO `carts` (`id`, `user_id`, `session_id`, `created_at`, `updated_at`) VALUES
(1, NULL, 'bkWAMNhTw4KfKLMYyoU7Vn2XP8yqTpYkFrg4NjkA', '2026-06-19 21:05:12', '2026-06-19 21:05:12'),
(2, NULL, 'FszJ8MbUGcLSZgTvXkUH0ajcVCCszWNPg43VzeTP', '2026-06-20 04:57:52', '2026-06-20 04:57:52'),
(3, NULL, 'vtM8w81CJqxQtdyFIe1OPoGfi2ZihBz5GQ1KmmJo', '2026-06-20 05:06:58', '2026-06-20 05:06:58'),
(4, 4, 'v4Vx1cB2GfIxxqDLoG5H6pOxSuzXKJBHxy9E7iZ6', '2026-06-20 05:27:56', '2026-06-20 05:27:56'),
(5, NULL, 'zwgSl27U5ryjhcmlgGdJXGf3bm5bOfgfvvXItWzM', '2026-06-20 09:23:21', '2026-06-20 09:23:21'),
(6, NULL, 'LoLQa4cQ84Ton9AM7jcftFdKkLod4FKZie0MEVdk', '2026-06-20 09:23:49', '2026-06-20 09:23:49'),
(7, NULL, '1xa2OowwvAgaGRJTr6BbN1mXYPHuq8GTtqeTNSSI', '2026-06-20 09:40:40', '2026-06-20 09:40:40'),
(8, 5, 'HIjUxigmK8HIL90OBSzfAJgeubmIFMAIGs2mqT2G', '2026-06-20 09:45:09', '2026-06-20 09:45:09'),
(9, NULL, 'UvfolzhsWjc83bXEyUisq4fq7EoS8jY6yu1ZYj2y', '2026-06-20 13:08:24', '2026-06-20 13:08:24'),
(10, NULL, 'isYaPpwilBQwLYXqvQK1EHDZ2YGYrkxXy9VM01QQ', '2026-06-21 00:34:56', '2026-06-21 00:34:56'),
(11, NULL, '7AzUEM9OV1sguZE2CCLht9TkkRFwDKQJs3ubA3ci', '2026-06-21 00:39:22', '2026-06-21 00:39:22'),
(12, NULL, 'Tq8ZR3qjWnygxh1bs31djg7VlhfP5GN1673ZnEmB', '2026-06-21 03:14:44', '2026-06-21 03:14:44'),
(13, 1, 'I81Vt9m6gFXokJrYfjgRvy7qgMWnC6fyu5hoSgOi', '2026-06-21 08:05:42', '2026-06-21 08:05:42'),
(14, NULL, 'f8qh1FgCfUN5ajluZw6Tpk2O0fSY1T2g8UkjPPnE', '2026-07-04 02:04:21', '2026-07-04 02:04:21'),
(15, NULL, '7Sj3EDD7DzoyVqoSj8cj8BuDR9U23tdpSHzNRCum', '2026-07-04 02:27:47', '2026-07-04 02:27:47'),
(16, NULL, 'FEIWMVQyoAxuSuHR0bRiDJhDqT3vX8qM0w3BwIeW', '2026-07-04 02:40:09', '2026-07-04 02:40:09'),
(17, NULL, 'Bd9OcGQXr6SRh8AK27hAOgqXGXCv8C1usOxp4z0H', '2026-07-04 04:29:00', '2026-07-04 04:29:00'),
(18, NULL, 'jO7TZ3DOkKq1jMaqbscDzGbxRBbAydMElYr7xaaK', '2026-07-04 07:31:30', '2026-07-04 07:31:30'),
(19, NULL, 'YoDCtj6qpZAaIwUlgi9tfeHiCWYk4LxJWx16zMaG', '2026-07-04 07:38:27', '2026-07-04 07:38:27'),
(20, NULL, 'DbU8eZoolyZlcS5DkmDSt1EvWbSfsJ1j2fSecPk3', '2026-07-04 12:02:16', '2026-07-04 12:02:16'),
(21, NULL, 'Th1JXDslULygTolbWBMHTiFsWFivfmQKb6ZlUxtE', '2026-07-04 12:02:21', '2026-07-04 12:02:21'),
(22, NULL, 'UzaBSti5hXtbfxNCXeWM9Patcjh1ObpjbD3MaqNT', '2026-07-04 12:02:23', '2026-07-04 12:02:23'),
(23, NULL, 'qkVtyGxKyLgGjiThY1QsOhrto06XjfmJl3LCxiO0', '2026-07-04 12:02:23', '2026-07-04 12:02:23'),
(24, NULL, 'XDaj8Z6t7HSCpLGemwOR0bhwsIAijrpXkHM4IonA', '2026-07-07 12:38:50', '2026-07-07 12:38:50'),
(25, NULL, 'QjRD3HqFLrxmnEF0vW9RQ1kavqEHpozhUkKuaEpx', '2026-07-07 13:17:34', '2026-07-07 13:17:34'),
(26, NULL, 'mnayvsWHg8EUTncU6lrXXxZVCqXpPocndSvfpzNa', '2026-07-07 13:19:56', '2026-07-07 13:19:56'),
(27, NULL, 'bq3Uo4KMqJjnRRTEediMQtIDfKBOrhugRyV1S6Fi', '2026-07-07 20:46:30', '2026-07-07 20:46:30'),
(28, NULL, 'Yw5cbCq4aCi95yNUGqUs07Y0s7rWTGUoNXgdnbvH', '2026-07-07 20:53:05', '2026-07-07 20:53:05'),
(29, NULL, 'bRC0eiPSn48T9WMhXPr15ep4Ksb6lFeGc1BFibYG', '2026-07-07 20:54:15', '2026-07-07 20:54:15'),
(30, 3, 'qTGotxNA2pDuO6IzgMdsUEsqSVMB99SvElTpVeUp', '2026-07-07 20:54:36', '2026-07-07 20:54:36'),
(31, NULL, 'HzoMii7gfB7IqAc3hgEx1y6kgOFfsEvus9njOOTQ', '2026-07-07 20:54:53', '2026-07-07 20:54:53'),
(32, NULL, 'jHUNrcKofVyeNztYas7Ynf1HQhH6xJXkYC5vGA81', '2026-07-08 10:55:55', '2026-07-08 10:55:55'),
(33, NULL, 'NBigpnNJOGOZ4RMFIwidFyTptht18lWwUajV0s6V', '2026-07-08 10:56:01', '2026-07-08 10:56:01'),
(34, NULL, 'DWAlhcGaK2MmRb7t90DtDRwfiyXfB5fYShNrI5TX', '2026-07-08 11:34:26', '2026-07-08 11:34:26'),
(35, NULL, 'w6e5oIrCAQ8uF7cItVu9Ev6BqfdKrjm8LmjeBCbh', '2026-07-08 11:54:51', '2026-07-08 11:54:51'),
(36, NULL, 'N9iV0Vlpb97SrGMhAeHRUgsOwtXg1KCBYMTh7Hlh', '2026-07-12 10:19:41', '2026-07-12 10:19:41'),
(37, NULL, '8N6pG7C7htOg8jEsaIbi7xmXQpxAQwt1y9lRkf2G', '2026-07-12 10:54:19', '2026-07-12 10:54:19');

-- --------------------------------------------------------

--
-- Table structure for table `cart_items`
--

CREATE TABLE `cart_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `cart_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `variant_id` bigint(20) UNSIGNED DEFAULT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `price` decimal(12,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cart_items`
--

INSERT INTO `cart_items` (`id`, `cart_id`, `product_id`, `variant_id`, `quantity`, `price`, `created_at`, `updated_at`) VALUES
(1, 1, 1, NULL, 2, 4990.00, '2026-06-19 21:08:35', '2026-06-19 21:08:42'),
(2, 7, 1, NULL, 1, 4990.00, '2026-06-20 09:41:40', '2026-06-20 09:41:40'),
(5, 9, 5, NULL, 1, 9990.00, '2026-06-20 13:08:34', '2026-06-20 13:08:34'),
(7, 10, 4, NULL, 2, 1990.00, '2026-06-21 00:39:19', '2026-06-21 00:39:20'),
(10, 14, 2, NULL, 1, 5990.00, '2026-07-04 04:28:04', '2026-07-04 04:28:04'),
(11, 18, 18, NULL, 1, 5990.00, '2026-07-04 07:32:09', '2026-07-04 07:32:09'),
(13, 8, 7, NULL, 2, 12990.00, '2026-07-04 09:27:37', '2026-07-04 09:43:50');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `parent_id` bigint(20) UNSIGNED DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `icon` varchar(255) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `gender` enum('men','women','unisex') NOT NULL DEFAULT 'unisex',
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `description` text DEFAULT NULL,
  `meta_title` varchar(255) DEFAULT NULL,
  `meta_description` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `parent_id`, `name`, `slug`, `icon`, `image`, `gender`, `sort_order`, `is_active`, `description`, `meta_title`, `meta_description`, `created_at`, `updated_at`) VALUES
(1, NULL, 'Men', 'men', 'fa-male', NULL, 'men', 1, 1, NULL, NULL, NULL, '2026-06-19 20:38:36', '2026-06-19 20:38:36'),
(2, 1, 'Clothing', 'men-clothing', 'fa-tshirt', NULL, 'men', 1, 1, NULL, NULL, NULL, '2026-06-19 20:38:36', '2026-06-19 20:38:36'),
(3, 2, 'T-Shirts & Polos', 'men-tshirts-polos', NULL, NULL, 'men', 1, 1, NULL, NULL, NULL, '2026-06-19 20:38:36', '2026-06-19 20:38:36'),
(4, 2, 'Bermuda', 'men-bermuda', NULL, NULL, 'men', 1, 1, NULL, NULL, NULL, '2026-06-19 20:38:36', '2026-06-19 20:38:36'),
(5, 2, 'Shirts', 'men-shirts', NULL, NULL, 'men', 1, 1, NULL, NULL, NULL, '2026-06-19 20:38:36', '2026-06-19 20:38:36'),
(6, 1, 'Footwear', 'men-footwear', 'fa-shoe-prints', NULL, 'men', 2, 1, NULL, NULL, NULL, '2026-06-19 20:38:36', '2026-06-19 20:38:36'),
(7, 6, 'Sneakers', 'men-sneakers', NULL, NULL, 'men', 1, 1, NULL, NULL, NULL, '2026-06-19 20:38:36', '2026-06-19 20:38:36'),
(8, 6, 'Loafers', 'men-loafers', NULL, NULL, 'men', 1, 1, NULL, NULL, NULL, '2026-06-19 20:38:36', '2026-06-19 20:38:36'),
(9, 1, 'Watches', 'men-watches', 'fa-clock', NULL, 'men', 3, 1, NULL, NULL, NULL, '2026-06-19 20:38:36', '2026-06-19 20:38:36'),
(10, 1, 'Accessories', 'men-accessories', 'fa-glasses', NULL, 'men', 4, 1, NULL, NULL, NULL, '2026-06-19 20:38:36', '2026-06-19 20:38:36'),
(11, 10, 'Wallets', 'men-wallets', NULL, NULL, 'men', 1, 1, NULL, NULL, NULL, '2026-06-19 20:38:36', '2026-06-19 20:38:36'),
(12, 10, 'Belts', 'men-belts', NULL, NULL, 'men', 1, 1, NULL, NULL, NULL, '2026-06-19 20:38:36', '2026-06-19 20:38:36'),
(13, 10, 'Caps', 'men-caps', NULL, NULL, 'men', 1, 1, NULL, NULL, NULL, '2026-06-19 20:38:36', '2026-06-19 20:38:36'),
(14, 10, 'Socks', 'men-socks', NULL, NULL, 'men', 1, 1, NULL, NULL, NULL, '2026-06-19 20:38:36', '2026-06-19 20:38:36'),
(15, 10, 'Sunglasses', 'men-sunglasses', NULL, NULL, 'men', 1, 1, NULL, NULL, NULL, '2026-06-19 20:38:36', '2026-06-19 20:38:36'),
(16, 10, 'Bags', 'men-bags', NULL, NULL, 'men', 1, 1, NULL, NULL, NULL, '2026-06-19 20:38:36', '2026-06-19 20:38:36'),
(17, 10, 'Fragrances', 'men-fragrances', NULL, NULL, 'men', 1, 1, NULL, NULL, NULL, '2026-06-19 20:38:36', '2026-06-19 20:38:36'),
(18, 1, 'Tech', 'men-tech', 'fa-headphones', NULL, 'men', 5, 1, NULL, NULL, NULL, '2026-06-19 20:38:36', '2026-06-19 20:38:36'),
(19, NULL, 'Women', 'women', 'fa-female', NULL, 'women', 2, 1, NULL, NULL, NULL, '2026-06-19 20:38:36', '2026-06-19 20:38:36'),
(20, 19, 'Clothing', 'women-clothing', 'fa-tshirt', NULL, 'women', 1, 1, NULL, NULL, NULL, '2026-06-19 20:38:36', '2026-06-19 20:38:36'),
(21, 20, 'T-Shirts & Tops', 'women-tshirts-tops', NULL, NULL, 'women', 1, 1, NULL, NULL, NULL, '2026-06-19 20:38:36', '2026-06-19 20:38:36'),
(22, 20, 'Shirts & Blouses', 'women-shirts-blouses', NULL, NULL, 'women', 1, 1, NULL, NULL, NULL, '2026-06-19 20:38:36', '2026-06-19 20:38:36'),
(23, 20, 'Dresses', 'women-dresses', NULL, NULL, 'women', 1, 1, NULL, NULL, NULL, '2026-06-19 20:38:36', '2026-06-19 20:38:36'),
(24, 19, 'Bags', 'women-bags', 'fa-shopping-bag', NULL, 'women', 2, 1, NULL, NULL, NULL, '2026-06-19 20:38:36', '2026-06-19 20:38:36'),
(25, 19, 'Watches', 'women-watches', 'fa-clock', NULL, 'women', 3, 1, NULL, NULL, NULL, '2026-06-19 20:38:36', '2026-06-19 20:38:36'),
(26, 19, 'Accessories', 'women-accessories', 'fa-gem', NULL, 'women', 4, 1, NULL, NULL, NULL, '2026-06-19 20:38:36', '2026-06-19 20:38:36'),
(27, 26, 'Belts', 'women-belts', NULL, NULL, 'women', 1, 1, NULL, NULL, NULL, '2026-06-19 20:38:36', '2026-06-19 20:38:36'),
(28, 26, 'Sunglasses', 'women-sunglasses', NULL, NULL, 'women', 1, 1, NULL, NULL, NULL, '2026-06-19 20:38:36', '2026-06-19 20:38:36'),
(29, 26, 'Fragrances', 'women-fragrances', NULL, NULL, 'women', 1, 1, NULL, NULL, NULL, '2026-06-19 20:38:36', '2026-06-19 20:38:36'),
(30, 19, 'Beauty Store', 'women-beauty', 'fa-spa', NULL, 'women', 5, 1, NULL, NULL, NULL, '2026-06-19 20:38:36', '2026-06-19 20:38:36'),
(31, 30, 'Skin Care', 'women-skincare', NULL, NULL, 'women', 1, 1, NULL, NULL, NULL, '2026-06-19 20:38:36', '2026-06-19 20:38:36'),
(32, 30, 'Makeup', 'women-makeup', NULL, NULL, 'women', 1, 1, NULL, NULL, NULL, '2026-06-19 20:38:36', '2026-06-19 20:38:36'),
(33, 19, 'Tech', 'women-tech', 'fa-headphones', NULL, 'women', 6, 1, NULL, NULL, NULL, '2026-06-19 20:38:36', '2026-06-19 20:38:36'),
(34, NULL, 'Sale', 'sale', 'fa-tags', NULL, 'unisex', 3, 1, 'Up to 60% off on selected items', NULL, NULL, '2026-06-19 20:38:36', '2026-06-19 20:38:36');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2025_01_01_000010_create_categories_table', 1),
(5, '2025_01_01_000020_create_products_table', 1),
(6, '2025_01_01_000030_create_product_images_table', 1),
(7, '2025_01_01_000040_create_product_variants_table', 1),
(8, '2025_01_01_000050_add_profile_fields_to_users_table', 1),
(9, '2025_01_01_000060_create_wishlists_table', 1),
(10, '2025_01_01_000070_create_carts_table', 1),
(11, '2025_01_01_000080_create_orders_table', 1),
(12, '2025_01_01_000090_create_banners_table', 1),
(13, '2025_01_01_000100_create_promotions_table', 1),
(14, '2025_01_01_000110_create_site_settings_table', 1),
(15, '2025_01_01_000120_create_reviews_table', 1),
(16, '2025_01_01_000130_create_page_contents_table', 1),
(17, '2025_01_01_000140_create_activity_logs_table', 1),
(18, '2026_06_20_020317_create_permission_tables', 1);

-- --------------------------------------------------------

--
-- Table structure for table `model_has_permissions`
--

CREATE TABLE `model_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `model_has_roles`
--

CREATE TABLE `model_has_roles` (
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `model_has_roles`
--

INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
(1, 'App\\Models\\User', 1),
(1, 'App\\Models\\User', 3),
(2, 'App\\Models\\User', 4);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `order_number` varchar(20) NOT NULL,
  `status` enum('pending','whatsapp_sent','confirmed','processing','shipped','delivered','cancelled','refunded') NOT NULL DEFAULT 'pending',
  `subtotal` decimal(12,2) NOT NULL,
  `discount_amount` decimal(12,2) NOT NULL DEFAULT 0.00,
  `delivery_fee` decimal(12,2) NOT NULL DEFAULT 0.00,
  `total` decimal(12,2) NOT NULL,
  `customer_name` varchar(255) NOT NULL,
  `customer_email` varchar(255) NOT NULL,
  `customer_phone` varchar(20) NOT NULL,
  `delivery_address` text NOT NULL,
  `city` varchar(255) DEFAULT NULL,
  `postal_code` varchar(10) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `promo_code` varchar(255) DEFAULT NULL,
  `whatsapp_sent_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `order_number`, `status`, `subtotal`, `discount_amount`, `delivery_fee`, `total`, `customer_name`, `customer_email`, `customer_phone`, `delivery_address`, `city`, `postal_code`, `notes`, `promo_code`, `whatsapp_sent_at`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, NULL, 'PE-FF830B09', 'whatsapp_sent', 9990.00, 0.00, 350.00, 10340.00, 'Dannai Tlaco', 'dannaitlaco@gmail.com', '0771234567', 'EZ', 'ES', '60022', 'NA', NULL, '2026-06-21 00:38:56', '2026-06-21 00:38:56', '2026-06-21 00:38:56', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED DEFAULT NULL,
  `variant_id` bigint(20) UNSIGNED DEFAULT NULL,
  `product_name` varchar(255) NOT NULL,
  `variant_info` varchar(255) DEFAULT NULL,
  `quantity` int(11) NOT NULL,
  `unit_price` decimal(12,2) NOT NULL,
  `total_price` decimal(12,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `variant_id`, `product_name`, `variant_info`, `quantity`, `unit_price`, `total_price`, `created_at`, `updated_at`) VALUES
(1, 1, 5, NULL, 'Minimal White Sneakers', NULL, 1, 9990.00, 9990.00, '2026-06-21 00:38:56', '2026-06-21 00:38:56');

-- --------------------------------------------------------

--
-- Table structure for table `page_contents`
--

CREATE TABLE `page_contents` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `slug` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` longtext DEFAULT NULL,
  `meta_title` varchar(255) DEFAULT NULL,
  `meta_description` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `sku` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `short_description` text DEFAULT NULL,
  `price` decimal(12,2) NOT NULL,
  `sale_price` decimal(12,2) DEFAULT NULL,
  `cost_price` decimal(12,2) DEFAULT NULL,
  `category_id` bigint(20) UNSIGNED NOT NULL,
  `gender` enum('men','women','unisex') NOT NULL DEFAULT 'unisex',
  `brand` varchar(255) DEFAULT NULL,
  `stock_quantity` int(11) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `is_featured` tinyint(1) NOT NULL DEFAULT 0,
  `is_new_arrival` tinyint(1) NOT NULL DEFAULT 0,
  `meta_title` varchar(255) DEFAULT NULL,
  `meta_description` varchar(255) DEFAULT NULL,
  `view_count` bigint(20) UNSIGNED NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `slug`, `sku`, `description`, `short_description`, `price`, `sale_price`, `cost_price`, `category_id`, `gender`, `brand`, `stock_quantity`, `is_active`, `is_featured`, `is_new_arrival`, `meta_title`, `meta_description`, `view_count`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Premium Cotton Polo', 'premium-cotton-polo', 'PE-B4E809E4', 'Premium cotton pique polo shirt with a classic fit. Features ribbed collar and cuffs with mother-of-pearl buttons. Perfect for casual and semi-formal occasions.', 'Classic fit cotton pique polo', 4990.00, NULL, NULL, 3, 'men', 'Pure Elegance', 9, 1, 0, 1, NULL, NULL, 20, '2026-06-19 20:38:36', '2026-07-04 08:31:06', NULL),
(2, 'Classic Oxford Button-Down Shirt', 'classic-oxford-button-down-shirt', 'PE-2EDF1EDC', 'Timeless Oxford button-down shirt crafted from premium long-staple cotton. Features a tailored fit with adjustable barrel cuffs.', 'Tailored Oxford button-down', 5990.00, NULL, NULL, 5, 'men', 'Pure Elegance', 36, 1, 0, 0, NULL, NULL, 2, '2026-06-19 20:38:36', '2026-07-04 04:28:05', NULL),
(3, 'Slim Fit Bermuda Shorts', 'slim-fit-bermuda-shorts', 'PE-1C3E68BF', 'Modern slim-fit bermuda shorts in lightweight stretch cotton. Features slant pockets and a clean tailored look.', 'Lightweight stretch cotton bermudas', 3990.00, NULL, NULL, 4, 'men', 'Pure Elegance', 14, 1, 0, 0, NULL, NULL, 0, '2026-06-19 20:38:36', '2026-06-19 20:38:36', NULL),
(4, 'Striped V-Neck T-Shirt', 'striped-v-neck-t-shirt', 'PE-423E9432', 'Soft cotton V-neck tee with horizontal contrast stripes. Relaxed fit for everyday comfort.', 'Casual striped V-neck tee', 2990.00, 1990.00, NULL, 3, 'men', 'Pure Elegance', 12, 1, 0, 0, NULL, NULL, 1, '2026-06-19 20:38:36', '2026-06-21 00:39:05', NULL),
(5, 'Minimal White Sneakers', 'minimal-white-sneakers', 'PE-3722380F', 'Clean and minimalist white leather sneakers with a cushioned insole and durable rubber outsole. Versatile enough for any outfit.', 'Clean leather white sneakers', 9990.00, NULL, NULL, 7, 'men', 'Pure Elegance', 40, 1, 0, 1, NULL, NULL, 4, '2026-06-19 20:38:36', '2026-07-04 12:03:07', NULL),
(6, 'Premium Leather Loafers', 'premium-leather-loafers', 'PE-84249E4E', 'Handcrafted leather penny loafers with blake-stitched construction. Features a leather lining and cushioned footbed.', 'Handcrafted leather penny loafers', 12990.00, 8990.00, NULL, 8, 'men', 'Pure Elegance', 16, 1, 0, 0, NULL, NULL, 0, '2026-06-19 20:38:36', '2026-06-19 20:38:36', NULL),
(7, 'Classic Black Watch', 'classic-black-watch', 'PE-857ACC71', 'Sophisticated black dial watch with Japanese quartz movement. Features a genuine leather strap and mineral crystal glass. Water resistant to 30 meters.', 'Black dial Japanese quartz watch', 12990.00, NULL, NULL, 9, 'men', 'Maison', 19, 1, 1, 1, NULL, NULL, 3, '2026-06-19 20:38:36', '2026-07-04 09:27:37', NULL),
(8, 'Silver Chronograph Watch', 'silver-chronograph-watch', 'PE-A615A1B8', 'Premium chronograph watch with stainless steel case and bracelet. Features three sub-dials and date window.', 'Stainless steel chronograph', 18990.00, NULL, NULL, 9, 'men', 'Maison', 33, 1, 0, 0, NULL, NULL, 0, '2026-06-19 20:38:36', '2026-06-19 20:38:36', NULL),
(9, 'Italian Leather Wallet', 'italian-leather-wallet', 'PE-46B1E7E1', 'Slim bifold wallet in full-grain Italian leather. Features RFID blocking technology, multiple card slots and a bill compartment.', 'RFID-blocking Italian leather wallet', 4990.00, NULL, NULL, 11, 'men', 'Pure Elegance', 13, 1, 0, 0, NULL, NULL, 0, '2026-06-19 20:38:36', '2026-06-19 20:38:36', NULL),
(10, 'Classic Leather Belt', 'classic-leather-belt', 'PE-0458312D', 'Premium full-grain leather belt with brushed steel buckle. Features a clean, minimalist design suitable for both formal and casual wear.', 'Full-grain leather with steel buckle', 3490.00, NULL, NULL, 12, 'men', 'Pure Elegance', 7, 1, 0, 0, NULL, NULL, 0, '2026-06-19 20:38:36', '2026-06-19 20:38:36', NULL),
(11, 'Aviator Sunglasses', 'aviator-sunglasses', 'PE-00809267', 'Classic aviator sunglasses with polarized lenses and gold-tone metal frame. UV400 protection for superior eye protection.', 'Polarized aviator with UV400', 6990.00, 4990.00, NULL, 15, 'men', 'Pure Elegance', 45, 1, 0, 0, NULL, NULL, 1, '2026-06-19 20:38:36', '2026-06-19 21:07:53', NULL),
(12, 'Oud Noir Eau de Parfum', 'oud-noir-eau-de-parfum', 'PE-7F3DAAA8', 'A luxurious oriental fragrance featuring rich oud wood, warm amber, and smoky incense notes. Long-lasting projection for 8+ hours.', 'Luxurious oriental oud fragrance', 15990.00, NULL, NULL, 17, 'men', 'Oud Noir', 49, 1, 1, 1, NULL, NULL, 3, '2026-06-19 20:38:36', '2026-07-04 02:13:49', NULL),
(13, 'Silk Blend Wrap Dress', 'silk-blend-wrap-dress', 'PE-C8F2E566', 'Elegant wrap dress in a luxurious silk blend fabric. Features a flattering V-neckline, 3/4 sleeves, and a self-tie belt.', 'Elegant silk blend wrap dress', 8990.00, NULL, NULL, 23, 'women', 'Pure Elegance', 46, 1, 1, 0, NULL, NULL, 0, '2026-06-19 20:38:36', '2026-06-19 20:38:36', NULL),
(14, 'Oversized Linen Blouse', 'oversized-linen-blouse', 'PE-A5D26915', 'Relaxed-fit linen blouse with dropped shoulders and a boxy silhouette. Features mother-of-pearl buttons and a mandarin collar.', 'Relaxed linen with mandarin collar', 4990.00, NULL, NULL, 22, 'women', 'Pure Elegance', 45, 1, 0, 0, NULL, NULL, 2, '2026-06-19 20:38:36', '2026-07-04 07:31:59', NULL),
(15, 'Essential White T-Shirt', 'essential-white-t-shirt', 'PE-11729F6D', 'Premium Pima cotton crew-neck t-shirt. Perfect everyday essential with a relaxed yet refined fit.', 'Pima cotton crew-neck essential', 2490.00, NULL, NULL, 21, 'women', 'Pure Elegance', 42, 1, 0, 0, NULL, NULL, 0, '2026-06-19 20:38:36', '2026-06-19 20:38:36', NULL),
(16, 'Quilted Leather Handbag', 'quilted-leather-handbag', 'PE-7C14E7E0', 'Luxurious quilted leather handbag with gold-tone chain strap. Features multiple interior compartments and a magnetic closure.', 'Quilted leather with gold chain', 18990.00, 14990.00, NULL, 24, 'women', 'Pure Elegance', 32, 1, 1, 0, NULL, NULL, 0, '2026-06-19 20:38:36', '2026-06-19 20:38:36', NULL),
(17, 'Rose Gold Ladies Watch', 'rose-gold-ladies-watch', 'PE-55D286D5', 'Delicate rose gold ladies watch with a mother-of-pearl dial. Features a slim mesh bracelet and Swiss quartz movement.', 'Rose gold with mother-of-pearl dial', 14990.00, NULL, NULL, 25, 'women', 'Maison', 47, 1, 0, 0, NULL, NULL, 0, '2026-06-19 20:38:36', '2026-06-19 20:38:36', NULL),
(18, 'Cat-Eye Sunglasses', 'cat-eye-sunglasses', 'PE-2DAC830B', 'Chic cat-eye sunglasses with gradient lenses and acetate frame. UV400 protection with a vintage-inspired design.', 'Gradient lens cat-eye frames', 5990.00, NULL, NULL, 28, 'women', 'Pure Elegance', 32, 1, 0, 0, NULL, NULL, 2, '2026-06-19 20:38:36', '2026-07-04 07:32:10', NULL),
(19, 'Rose Petal Face Serum', 'rose-petal-face-serum', 'PE-513B9CFE', 'Luxurious face serum infused with real rose petal extract and hyaluronic acid. Deeply hydrates and brightens for a radiant glow.', 'Rose extract hydrating serum', 7990.00, NULL, NULL, 31, 'women', 'Bloom', 50, 1, 0, 1, NULL, NULL, 3, '2026-06-19 20:38:36', '2026-07-04 07:51:28', NULL),
(20, 'Premium Wireless Earbuds', 'premium-wireless-earbuds', 'PE-DC00CE66', 'True wireless earbuds with active noise cancellation, 30-hour battery life, and premium sound quality. IPX5 water resistant.', 'ANC earbuds with 30h battery', 8990.00, 6990.00, NULL, 18, 'unisex', 'SoundPure', 29, 1, 0, 0, NULL, NULL, 0, '2026-06-19 20:38:36', '2026-06-19 20:38:36', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `product_images`
--

CREATE TABLE `product_images` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `image_path` varchar(255) NOT NULL,
  `alt_text` varchar(255) DEFAULT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `is_primary` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_images`
--

INSERT INTO `product_images` (`id`, `product_id`, `image_path`, `alt_text`, `sort_order`, `is_primary`, `created_at`, `updated_at`) VALUES
(1, 1, 'products/polo-shirt.png', 'Cotton Pique Polo Shirt', 0, 1, '2026-06-19 20:38:36', '2026-06-21 01:04:54'),
(2, 2, 'products/placeholder.jpg', 'Classic Oxford Button-Down Shirt', 0, 1, '2026-06-19 20:38:36', '2026-06-19 20:38:36'),
(3, 3, 'products/placeholder.jpg', 'Slim Fit Bermuda Shorts', 0, 1, '2026-06-19 20:38:36', '2026-06-19 20:38:36'),
(4, 4, 'products/placeholder.jpg', 'Striped V-Neck T-Shirt', 0, 1, '2026-06-19 20:38:36', '2026-06-19 20:38:36'),
(5, 5, 'products/white-sneakers.png', 'Minimal White Sneakers', 0, 1, '2026-06-19 20:38:36', '2026-06-21 01:04:54'),
(6, 6, 'products/placeholder.jpg', 'Premium Leather Loafers', 0, 1, '2026-06-19 20:38:36', '2026-06-19 20:38:36'),
(7, 7, 'products/black-watch.png', 'Classic Black Watch', 0, 1, '2026-06-19 20:38:36', '2026-06-21 01:04:54'),
(8, 8, 'products/placeholder.jpg', 'Silver Chronograph Watch', 0, 1, '2026-06-19 20:38:36', '2026-06-19 20:38:36'),
(9, 9, 'products/placeholder.jpg', 'Italian Leather Wallet', 0, 1, '2026-06-19 20:38:36', '2026-06-19 20:38:36'),
(10, 10, 'products/placeholder.jpg', 'Classic Leather Belt', 0, 1, '2026-06-19 20:38:36', '2026-06-19 20:38:36'),
(11, 11, 'products/placeholder.jpg', 'Aviator Sunglasses', 0, 1, '2026-06-19 20:38:36', '2026-06-19 20:38:36'),
(12, 12, 'products/parfum.png', 'Oud Noir Eau de Parfum', 0, 1, '2026-06-19 20:38:36', '2026-06-21 01:04:54'),
(13, 13, 'products/placeholder.jpg', 'Silk Blend Wrap Dress', 0, 1, '2026-06-19 20:38:36', '2026-06-19 20:38:36'),
(14, 14, 'products/placeholder.jpg', 'Oversized Linen Blouse', 0, 1, '2026-06-19 20:38:36', '2026-06-19 20:38:36'),
(15, 15, 'products/placeholder.jpg', 'Essential White T-Shirt', 0, 1, '2026-06-19 20:38:36', '2026-06-19 20:38:36'),
(16, 16, 'products/placeholder.jpg', 'Quilted Leather Handbag', 0, 1, '2026-06-19 20:38:36', '2026-06-19 20:38:36'),
(17, 17, 'products/placeholder.jpg', 'Rose Gold Ladies Watch', 0, 1, '2026-06-19 20:38:36', '2026-06-19 20:38:36'),
(18, 18, 'products/placeholder.jpg', 'Cat-Eye Sunglasses', 0, 1, '2026-06-19 20:38:36', '2026-06-19 20:38:36'),
(19, 19, 'products/face-serum.png', 'Rose Petal Face Serum', 0, 1, '2026-06-19 20:38:36', '2026-06-21 01:07:39'),
(20, 20, 'products/placeholder.jpg', 'Premium Wireless Earbuds', 0, 1, '2026-06-19 20:38:36', '2026-06-19 20:38:36');

-- --------------------------------------------------------

--
-- Table structure for table `product_variants`
--

CREATE TABLE `product_variants` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `size` varchar(255) DEFAULT NULL,
  `color` varchar(255) DEFAULT NULL,
  `color_code` varchar(255) DEFAULT NULL,
  `sku` varchar(255) DEFAULT NULL,
  `stock_quantity` int(11) NOT NULL DEFAULT 0,
  `price_adjustment` decimal(12,2) NOT NULL DEFAULT 0.00,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `promotions`
--

CREATE TABLE `promotions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `code` varchar(255) NOT NULL,
  `type` enum('percentage','fixed') NOT NULL DEFAULT 'percentage',
  `value` decimal(12,2) NOT NULL,
  `min_order_amount` decimal(12,2) DEFAULT NULL,
  `max_discount_amount` decimal(12,2) DEFAULT NULL,
  `usage_limit` int(11) DEFAULT NULL,
  `used_count` int(11) NOT NULL DEFAULT 0,
  `starts_at` timestamp NULL DEFAULT NULL,
  `ends_at` timestamp NULL DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `rating` tinyint(3) UNSIGNED NOT NULL,
  `comment` text DEFAULT NULL,
  `is_approved` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'web', '2026-06-19 20:38:35', '2026-06-19 20:38:35'),
(2, 'customer', 'web', '2026-06-19 20:38:35', '2026-06-19 20:38:35');

-- --------------------------------------------------------

--
-- Table structure for table `role_has_permissions`
--

CREATE TABLE `role_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('Bd9OcGQXr6SRh8AK27hAOgqXGXCv8C1usOxp4z0H', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoidXNDajRoNXJ2c2dveHRFUU5Gc2VXempDV3lZSk44U0gyVzBNZkZ6MSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1783159140),
('bRC0eiPSn48T9WMhXPr15ep4Ksb6lFeGc1BFibYG', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiQnZMbTRXdkIwbVZlc29VYjZmb3paQ0ZZS1V2S3pWMXZ1VmFsY3BaWCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7czo1OiJyb3V0ZSI7czo0OiJob21lIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1783477463),
('DbU8eZoolyZlcS5DkmDSt1EvWbSfsJ1j2fSecPk3', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiTlBxbEE5cFAzUTUyaXI3NTE1c2U3VjBZWloxb3BuZEMyVkNNSTE3dCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjY6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9jYXJ0IjtzOjU6InJvdXRlIjtzOjEwOiJjYXJ0LmluZGV4Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1783186341),
('f8qh1FgCfUN5ajluZw6Tpk2O0fSY1T2g8UkjPPnE', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoidEJkdmQ4UzZiNEtITkFhUGRkUzJaWmsxSG51cGxuNXNJVjF5NXhDVyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjY6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9jYXJ0IjtzOjU6InJvdXRlIjtzOjEwOiJjYXJ0LmluZGV4Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1783159150),
('FEIWMVQyoAxuSuHR0bRiDJhDqT3vX8qM0w3BwIeW', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoielBUWHJyTFdlUXU5NnFqdU8xdjdYcjVFS0QxeDhHeTgzUEVKNkpNTiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1783159130),
('HzoMii7gfB7IqAc3hgEx1y6kgOFfsEvus9njOOTQ', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiRjR6NExTRURkZWhsbFRWaGtDZ082Y210VkR1UkNzRVk5b1Vja0tEdyI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMCI7czo1OiJyb3V0ZSI7czo0OiJob21lIjt9fQ==', 1783477493),
('I81Vt9m6gFXokJrYfjgRvy7qgMWnC6fyu5hoSgOi', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoid1QyUlNZcVBRSWNoVEVTTUxBeFprdTQycE94d3BLdFVuSjRTTU4zMiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzI6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9jYXRlZ29yaWVzIjtzOjU6InJvdXRlIjtzOjEwOiJjYXRlZ29yaWVzIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTt9', 1782051669),
('isYaPpwilBQwLYXqvQK1EHDZ2YGYrkxXy9VM01QQ', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiN0NVcDlZV0RzSzFPRXVVOWZCZ3dhOEtydDZJbTBodXZBdWg4dGpOcSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjY6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9jYXJ0IjtzOjU6InJvdXRlIjtzOjEwOiJjYXJ0LmluZGV4Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1782022162),
('jHUNrcKofVyeNztYas7Ynf1HQhH6xJXkYC5vGA81', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoicUNEdkQ4UFpKV1d6S0F6eGpUbFRYY1JtT0pNQWlWeVRselJIZDA4bCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1783528032),
('jm1INoPGQYnaVECXP8k4VGdRqz0DsBpaRY22eEAz', 3, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiQzYyMWg5ajNPYjI4R0RRcFRVWUtQTmJwNHpjb3Fvc2xjZUVTNGxNciI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mzk6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi9jdXN0b21lcnMvNCI7czo1OiJyb3V0ZSI7czoyMDoiYWRtaW4uY3VzdG9tZXJzLnNob3ciO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjM6InVybCI7YTowOnt9czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6Mzt9', 1783875451),
('jO7TZ3DOkKq1jMaqbscDzGbxRBbAydMElYr7xaaK', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoieU5Dc1pmVlNCNjZ5VGN5U1hRZ1ZnT2o1WG1VOFJXNkV0RG1UeUhLUyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjY6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9jYXJ0IjtzOjU6InJvdXRlIjtzOjEwOiJjYXJ0LmluZGV4Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1783175147),
('LXoNcP37tEjuqedlZqhTEQAbKEO7UvVxN9W3vSPX', 3, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoibk05R1VENWs4NlNSWFFNNjJZdkdHTXVSNXFDMG04RkdNWW9MVVFVNCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbiI7czo1OiJyb3V0ZSI7czoxNToiYWRtaW4uZGFzaGJvYXJkIjt9czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6Mzt9', 1783450175),
('N9iV0Vlpb97SrGMhAeHRUgsOwtXg1KCBYMTh7Hlh', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiYlVCMmZjWk1SN1JKZVl2RHVub3h2Mklib25jRVpHTHVocGpUc2ZJMSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDY6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9jYXRlZ29yaWVzP2NhdGVnb3J5PXNhbGUiO3M6NToicm91dGUiO3M6MTA6ImNhdGVnb3JpZXMiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1783871389),
('NOZrG3zFVyEve1RnsRLNTAxWkpVQYQd14fO03VGT', 3, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiOHJwYTc5Rmg2cGRzQWw3VW5kWUp5cnVLNVlkOFAycXVtMmlVdmxzYSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbiI7czo1OiJyb3V0ZSI7czoxNToiYWRtaW4uZGFzaGJvYXJkIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6Mzt9', 1783450229),
('PumbxsuYz4NZuptvN2IwWRJjQniUQZALzRZSOMCH', 5, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiaUh1YWZadjJZSzlzOGVGa1V4UzhQbm5kV05mMmpDcVJRTW91NDg4aiI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMCI7czo1OiJyb3V0ZSI7czo0OiJob21lIjt9czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6NTt9', 1783530280),
('Qf9nn72NUbSqEDjPTEV7VKrGMjGz8FCnoXCXYcFr', 5, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiZEgyQ0pkb2g2UnJhSkpTczVmaXdsN2NWSncxQkdoRDVMY25yZUJXeCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjY6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9jYXJ0IjtzOjU6InJvdXRlIjtzOjEwOiJjYXJ0LmluZGV4Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6NTt9', 1783157458),
('QjRD3HqFLrxmnEF0vW9RQ1kavqEHpozhUkKuaEpx', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoibkJKS01TbUE5MlFzZ1VwVVJKRHJUeGx6VXcxblZDWEl1TVpTUEdnRyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1783450105),
('Th1JXDslULygTolbWBMHTiFsWFivfmQKb6ZlUxtE', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiYURoVWgwUGJndm5FYnNCVWJRUjZzVUU2TmtwbHgxQ281MUUySm84YyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDU6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9jYXRlZ29yaWVzP2NhdGVnb3J5PW1lbiI7czo1OiJyb3V0ZSI7czoxMDoiY2F0ZWdvcmllcyI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1783188418),
('UV5tSkrqbtv2OyVldMNz6kwrxjWHs767GZaCcdtr', 3, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiYzVaV3U1N290UE05Wm1ibng4TDNIckFMeHBRVWxtbDRKcElNR3Y4NyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjM7fQ==', 1783448186),
('UvfolzhsWjc83bXEyUisq4fq7EoS8jY6yu1ZYj2y', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiSU9MT2VUVUxqcVQ3cUxDT04zb0tSa2g5bDN1d3MwWms1QmZiS0w2RiI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzI6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9jYXRlZ29yaWVzIjtzOjU6InJvdXRlIjtzOjEwOiJjYXRlZ29yaWVzIjt9fQ==', 1781980742),
('UzaBSti5hXtbfxNCXeWM9Patcjh1ObpjbD3MaqNT', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoib3BqWW5DODdoMUdFTnlLRE5xeWg0ZFU3ZDlZYmxxSld0bUF6YkdSTCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMCI7czo1OiJyb3V0ZSI7czo0OiJob21lIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1783186343),
('wkFAFieBIfSJum4tj7qXin269a8wUAcw6J8PP3qw', 5, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiT1BiZUg3dlZJN01KZXptSEtPNXV1bndScnc1T3U1ZjdDUkVVb0VFTyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMCI7czo1OiJyb3V0ZSI7czo0OiJob21lIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6NTt9', 1783191453),
('yoFRBjkJgfd3KZkxiN3SdSybM1OLTez1QvMX9ZV6', 3, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoia2lIb1FSU3BzbUxIa0tncXRuMDI2N21nUDhyNHN0czR4cUhkTDM3WSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbiI7czo1OiJyb3V0ZSI7czoxNToiYWRtaW4uZGFzaGJvYXJkIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6Mzt9', 1783531590),
('ZIk03vw5jvdkztVNgxV79HqZUJv3s1Ws0TLSIvQq', 5, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiV3ZOQmZBalNjeHZSM3pIaFVFZ2ZqNHkxQkVGdVhLakVFU21YSE50UCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMCI7czo1OiJyb3V0ZSI7czo0OiJob21lIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6NTt9', 1783178352),
('zjuCLTWwnRimK5tiCSkxqFY4WzKRsAizhiB4H3cj', 5, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoieGVTSmwyVUVVYUNSQXh2dlRLbExxcjNZM0ZvTDZET0VMR1lqMXBaZyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMCI7czo1OiJyb3V0ZSI7czo0OiJob21lIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6NTt9', 1782032762);

-- --------------------------------------------------------

--
-- Table structure for table `site_settings`
--

CREATE TABLE `site_settings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `key` varchar(255) NOT NULL,
  `value` text DEFAULT NULL,
  `group` varchar(255) NOT NULL DEFAULT 'general',
  `type` varchar(255) NOT NULL DEFAULT 'text',
  `label` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `site_settings`
--

INSERT INTO `site_settings` (`id`, `key`, `value`, `group`, `type`, `label`, `created_at`, `updated_at`) VALUES
(1, 'site_name', 'Pure Elegance', 'general', 'text', 'Site Name', '2026-06-19 20:38:36', '2026-06-19 20:38:36'),
(2, 'site_tagline', 'Timeless Fashion. Pure Elegance.', 'general', 'text', 'Tagline', '2026-06-19 20:38:36', '2026-06-19 20:38:36'),
(3, 'site_email', 'info@pureelegance.lk', 'general', 'email', 'Contact Email', '2026-06-19 20:38:36', '2026-06-19 20:38:36'),
(4, 'site_phone', '+94 77 123 4567', 'general', 'text', 'Contact Phone', '2026-06-19 20:38:36', '2026-06-19 20:38:36'),
(5, 'currency_symbol', 'LKR', 'general', 'text', 'Currency Symbol', '2026-06-19 20:38:36', '2026-06-19 20:38:36'),
(6, 'whatsapp_number', '94771234567', 'whatsapp', 'text', 'WhatsApp Number (with country code, no +)', '2026-06-19 20:38:36', '2026-06-19 20:38:36'),
(7, 'whatsapp_enabled', '1', 'whatsapp', 'boolean', 'WhatsApp Checkout Enabled', '2026-06-19 20:38:36', '2026-06-19 20:38:36'),
(8, 'delivery_fee', '350', 'delivery', 'number', 'Standard Delivery Fee (LKR)', '2026-06-19 20:38:36', '2026-06-19 20:38:36'),
(9, 'free_delivery_threshold', '10000', 'delivery', 'number', 'Free Delivery Threshold (LKR)', '2026-06-19 20:38:36', '2026-06-19 20:38:36'),
(10, 'delivery_note', 'Islandwide Delivery', 'delivery', 'text', 'Delivery Note', '2026-06-19 20:38:36', '2026-06-19 20:38:36'),
(11, 'announcement_bar_text', 'FREE DELIVERY ON ORDERS OVER', 'announcement', 'text', 'Announcement Text', '2026-06-19 20:38:36', '2026-06-19 20:38:36'),
(12, 'announcement_bar_highlight', 'LKR 10,000', 'announcement', 'text', 'Announcement Highlight', '2026-06-19 20:38:36', '2026-06-19 20:38:36'),
(13, 'announcement_bar_enabled', '1', 'announcement', 'boolean', 'Show Announcement Bar', '2026-06-19 20:38:36', '2026-06-19 20:38:36'),
(14, 'social_facebook', '#', 'social', 'url', 'Facebook URL', '2026-06-19 20:38:36', '2026-06-19 20:38:36'),
(15, 'social_instagram', '#', 'social', 'url', 'Instagram URL', '2026-06-19 20:38:36', '2026-06-19 20:38:36'),
(16, 'social_tiktok', '#', 'social', 'url', 'TikTok URL', '2026-06-19 20:38:36', '2026-06-19 20:38:36'),
(17, 'social_whatsapp', '#', 'social', 'url', 'WhatsApp URL', '2026-06-19 20:38:36', '2026-06-19 20:38:36'),
(18, 'newsletter_title', 'GET 10% OFF YOUR FIRST ORDER', 'newsletter', 'text', 'Newsletter Title', '2026-06-19 20:38:36', '2026-06-19 20:38:36'),
(19, 'newsletter_subtitle', 'Join our community and enjoy exclusive offers.', 'newsletter', 'text', 'Newsletter Subtitle', '2026-06-19 20:38:36', '2026-06-19 20:38:36'),
(20, 'feature_1_title', 'CASH ON DELIVERY', 'features', 'text', 'Feature 1 Title', '2026-06-19 20:38:36', '2026-06-19 20:38:36'),
(21, 'feature_1_subtitle', 'Islandwide Delivery', 'features', 'text', 'Feature 1 Subtitle', '2026-06-19 20:38:36', '2026-06-19 20:38:36'),
(22, 'feature_2_title', '100% ORIGINAL', 'features', 'text', 'Feature 2 Title', '2026-06-19 20:38:36', '2026-06-19 20:38:36'),
(23, 'feature_2_subtitle', 'Branded Products', 'features', 'text', 'Feature 2 Subtitle', '2026-06-19 20:38:36', '2026-06-19 20:38:36'),
(24, 'feature_3_title', 'EASY RETURNS', 'features', 'text', 'Feature 3 Title', '2026-06-19 20:38:36', '2026-06-19 20:38:36'),
(25, 'feature_3_subtitle', '7 Days Return Policy', 'features', 'text', 'Feature 3 Subtitle', '2026-06-19 20:38:36', '2026-06-19 20:38:36'),
(26, 'feature_4_title', 'SECURE PAYMENT', 'features', 'text', 'Feature 4 Title', '2026-06-19 20:38:36', '2026-06-19 20:38:36'),
(27, 'feature_4_subtitle', 'Safe & Secure Checkout', 'features', 'text', 'Feature 4 Subtitle', '2026-06-19 20:38:36', '2026-06-19 20:38:36');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `first_name` varchar(255) DEFAULT NULL,
  `last_name` varchar(255) DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `address_line1` varchar(255) DEFAULT NULL,
  `address_line2` varchar(255) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `postal_code` varchar(10) DEFAULT NULL,
  `country` varchar(255) NOT NULL DEFAULT 'Sri Lanka',
  `avatar` varchar(255) DEFAULT NULL,
  `is_admin` tinyint(1) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `last_login_at` timestamp NULL DEFAULT NULL,
  `last_login_ip` varchar(255) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `phone`, `first_name`, `last_name`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`, `address_line1`, `address_line2`, `city`, `postal_code`, `country`, `avatar`, `is_admin`, `is_active`, `last_login_at`, `last_login_ip`, `deleted_at`) VALUES
(1, 'Admin', 'admin@pureelegance.lk', '+94771234567', 'System', 'Administrator', '2026-06-19 20:38:35', '$2y$12$9bHlR5u12XESqmxOxf5FLOf/66Y8UqMErucGw.hDszp5meVVEE2tW', 'Q1sVcro4ZTuqg7ya2SLUSNTYx9XJmAtaKgZbcAYxXg7GBWxPECNJl4UQfmxG', '2026-06-19 20:38:35', '2026-06-21 06:33:47', NULL, NULL, NULL, NULL, 'Sri Lanka', NULL, 1, 1, NULL, NULL, NULL),
(2, 'Sample User', 'sampleuser@gmail.com', NULL, NULL, NULL, NULL, '$2y$12$vZsDh9gZ0AEus8yqvnFe1O/WSg4O6UP44ge2wA4LbD7Cjw9nZ7y6q', NULL, '2026-06-19 21:09:35', '2026-06-19 21:09:35', NULL, NULL, NULL, NULL, 'Sri Lanka', NULL, 0, 1, NULL, NULL, NULL),
(3, 'Store Admin', 'admin@pureelegance.com', NULL, 'Store', 'Admin', '2026-06-20 04:58:59', '$2y$12$ehTsq2xO2R6dphYBKG72ber/d0pG5K/BT2VtD4m31.l0OVXx2zuyC', NULL, '2026-06-20 04:58:59', '2026-07-12 10:57:19', NULL, NULL, NULL, NULL, 'Sri Lanka', NULL, 1, 1, '2026-07-12 10:57:19', '127.0.0.1', NULL),
(4, 'Test Customer', 'customer@example.com', NULL, 'Test', 'Customer', '2026-06-20 04:58:59', '$2y$12$ts7Pikj6.dXv3xPy.Yh/IuyVlJDF.C9XPaTiaC04eUFLit753pQHq', NULL, '2026-06-20 04:58:59', '2026-06-20 04:58:59', NULL, NULL, NULL, NULL, 'Sri Lanka', NULL, 0, 1, NULL, NULL, NULL),
(5, 'John Doe', 'john.doe2@example.com', NULL, NULL, NULL, NULL, '$2y$12$XCgqxhTZK4TGd2IWB064vejv7mgGBYrb/pGA6DoOSnCXmDwwt9TZe', NULL, '2026-06-20 09:45:08', '2026-07-08 11:34:39', NULL, NULL, NULL, NULL, 'Sri Lanka', NULL, 0, 1, '2026-07-08 11:34:39', '127.0.0.1', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `wishlists`
--

CREATE TABLE `wishlists` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `wishlists`
--

INSERT INTO `wishlists` (`id`, `user_id`, `product_id`, `created_at`, `updated_at`) VALUES
(3, 5, 1, '2026-06-21 03:35:53', '2026-06-21 03:35:53');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `activity_logs_model_type_model_id_index` (`model_type`,`model_id`),
  ADD KEY `activity_logs_user_id_index` (`user_id`),
  ADD KEY `activity_logs_created_at_index` (`created_at`);

--
-- Indexes for table `banners`
--
ALTER TABLE `banners`
  ADD PRIMARY KEY (`id`),
  ADD KEY `banners_position_is_active_sort_order_index` (`position`,`is_active`,`sort_order`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_expiration_index` (`expiration`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_locks_expiration_index` (`expiration`);

--
-- Indexes for table `carts`
--
ALTER TABLE `carts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `carts_user_id_index` (`user_id`),
  ADD KEY `carts_session_id_index` (`session_id`);

--
-- Indexes for table `cart_items`
--
ALTER TABLE `cart_items`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `cart_items_cart_id_product_id_variant_id_unique` (`cart_id`,`product_id`,`variant_id`),
  ADD KEY `cart_items_product_id_foreign` (`product_id`),
  ADD KEY `cart_items_variant_id_foreign` (`variant_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `categories_slug_unique` (`slug`),
  ADD KEY `categories_parent_id_is_active_index` (`parent_id`,`is_active`),
  ADD KEY `categories_gender_is_active_index` (`gender`,`is_active`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

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
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  ADD KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  ADD KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `orders_order_number_unique` (`order_number`),
  ADD KEY `orders_user_id_foreign` (`user_id`),
  ADD KEY `orders_status_index` (`status`),
  ADD KEY `orders_order_number_index` (`order_number`),
  ADD KEY `orders_created_at_index` (`created_at`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_items_order_id_foreign` (`order_id`),
  ADD KEY `order_items_product_id_foreign` (`product_id`),
  ADD KEY `order_items_variant_id_foreign` (`variant_id`);

--
-- Indexes for table `page_contents`
--
ALTER TABLE `page_contents`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `page_contents_slug_unique` (`slug`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `products_slug_unique` (`slug`),
  ADD UNIQUE KEY `products_sku_unique` (`sku`),
  ADD KEY `products_category_id_is_active_index` (`category_id`,`is_active`),
  ADD KEY `products_gender_is_active_index` (`gender`,`is_active`),
  ADD KEY `products_is_featured_is_active_index` (`is_featured`,`is_active`),
  ADD KEY `products_is_new_arrival_is_active_index` (`is_new_arrival`,`is_active`),
  ADD KEY `products_brand_index` (`brand`),
  ADD KEY `products_sale_price_index` (`sale_price`);

--
-- Indexes for table `product_images`
--
ALTER TABLE `product_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_images_product_id_sort_order_index` (`product_id`,`sort_order`);

--
-- Indexes for table `product_variants`
--
ALTER TABLE `product_variants`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `product_variants_product_id_size_color_unique` (`product_id`,`size`,`color`),
  ADD KEY `product_variants_product_id_is_active_index` (`product_id`,`is_active`);

--
-- Indexes for table `promotions`
--
ALTER TABLE `promotions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `promotions_code_unique` (`code`),
  ADD KEY `promotions_code_is_active_index` (`code`,`is_active`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `reviews_user_id_product_id_unique` (`user_id`,`product_id`),
  ADD KEY `reviews_product_id_is_approved_index` (`product_id`,`is_approved`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indexes for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`role_id`),
  ADD KEY `role_has_permissions_role_id_foreign` (`role_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `site_settings`
--
ALTER TABLE `site_settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `site_settings_key_unique` (`key`),
  ADD KEY `site_settings_group_index` (`group`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Indexes for table `wishlists`
--
ALTER TABLE `wishlists`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `wishlists_user_id_product_id_unique` (`user_id`,`product_id`),
  ADD KEY `wishlists_product_id_foreign` (`product_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activity_logs`
--
ALTER TABLE `activity_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `banners`
--
ALTER TABLE `banners`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `carts`
--
ALTER TABLE `carts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `cart_items`
--
ALTER TABLE `cart_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `page_contents`
--
ALTER TABLE `page_contents`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `product_images`
--
ALTER TABLE `product_images`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `product_variants`
--
ALTER TABLE `product_variants`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `promotions`
--
ALTER TABLE `promotions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `site_settings`
--
ALTER TABLE `site_settings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `wishlists`
--
ALTER TABLE `wishlists`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD CONSTRAINT `activity_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `carts`
--
ALTER TABLE `carts`
  ADD CONSTRAINT `carts_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `cart_items`
--
ALTER TABLE `cart_items`
  ADD CONSTRAINT `cart_items_cart_id_foreign` FOREIGN KEY (`cart_id`) REFERENCES `carts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cart_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cart_items_variant_id_foreign` FOREIGN KEY (`variant_id`) REFERENCES `product_variants` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `categories`
--
ALTER TABLE `categories`
  ADD CONSTRAINT `categories_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `order_items_variant_id_foreign` FOREIGN KEY (`variant_id`) REFERENCES `product_variants` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `product_images`
--
ALTER TABLE `product_images`
  ADD CONSTRAINT `product_images_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `product_variants`
--
ALTER TABLE `product_variants`
  ADD CONSTRAINT `product_variants_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reviews_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `wishlists`
--
ALTER TABLE `wishlists`
  ADD CONSTRAINT `wishlists_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `wishlists_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
