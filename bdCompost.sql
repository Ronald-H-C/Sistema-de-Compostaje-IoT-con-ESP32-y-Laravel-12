-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: mysql-compos.alwaysdata.net
-- Generation Time: Nov 12, 2025 at 09:05 PM
-- Server version: 10.11.14-MariaDB
-- PHP Version: 7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `compos_iottt`
--
-- --------------------------------------------------------

--
-- Table structure for table `contact_messages`
--

CREATE TABLE `contact_messages` (
  `id` int(10) UNSIGNED NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `subject` varchar(150) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `state` tinyint(4) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--

-- Table structure for table `fertilizers`
--

CREATE TABLE `fertilizers` (
  `id` smallint(5) UNSIGNED NOT NULL,
  `idUser` smallint(5) UNSIGNED NOT NULL,
  `title` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `type` enum('composta','humus','abono_organico','otro') NOT NULL,
  `amount` smallint(6) NOT NULL COMMENT 'En kilogramos',
  `stock` smallint(6) NOT NULL,
  `price` decimal(10,2) NOT NULL COMMENT 'Precio por kilogramo',
  `image` varchar(255) DEFAULT NULL,
  `state` tinyint(4) NOT NULL DEFAULT 1,
  `featured` tinyint(4) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL,
  `updated_by` smallint(6) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--

--
-- Table structure for table `locations`
--

CREATE TABLE `locations` (
  `id` int(10) UNSIGNED NOT NULL,
  `idFertilizer` smallint(5) UNSIGNED NOT NULL,
  `latitude` decimal(10,8) NOT NULL,
  `longitude` decimal(11,8) NOT NULL,
  `address` text DEFAULT NULL,
  `link_google_maps` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--

--
-- Table structure for table `materials`
--

CREATE TABLE `materials` (
  `id` tinyint(3) UNSIGNED NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `image` varchar(90) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `clasification` enum('verde','marron','no_compostable') NOT NULL,
  `aptitude` enum('casero','industrial','no_recomendado') NOT NULL,
  `type_category` enum('alimentos','jardin','papel_carton','otros') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL,
  `updated_by` smallint(6) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--


--
-- Table structure for table `payment_products`
--

CREATE TABLE `payment_products` (
  `idSale` smallint(5) UNSIGNED NOT NULL,
  `idFertilizer` smallint(5) UNSIGNED NOT NULL,
  `amount` tinyint(4) NOT NULL,
  `price` decimal(7,2) NOT NULL,
  `subtotal` decimal(7,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `updated_by` smallint(5) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--

--
-- Table structure for table `plans`
--

CREATE TABLE `plans` (
  `id` tinyint(3) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `cost` decimal(10,2) NOT NULL,
  `duration` int(11) NOT NULL,
  `state` tinyint(4) NOT NULL DEFAULT 1,
  `post_limit` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_by` smallint(6) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--

--
-- Table structure for table `plan_change_requests`
--

CREATE TABLE `plan_change_requests` (
  `id` int(10) UNSIGNED NOT NULL,
  `idUser` smallint(5) UNSIGNED NOT NULL,
  `idPlan` tinyint(3) UNSIGNED NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `state` tinyint(4) NOT NULL DEFAULT 1 COMMENT '0-Rechazado//1-Espera//2-Aprobado',
  `observations` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_by` smallint(6) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--

--
-- Table structure for table `prototypes`
--

CREATE TABLE `prototypes` (
  `id` tinyint(4) NOT NULL,
  `idUser` smallint(5) UNSIGNED NOT NULL,
  `name` varchar(45) NOT NULL,
  `code` varchar(45) NOT NULL,
  `state` tinyint(4) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL,
  `updated_by` smallint(6) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `readings`
--

CREATE TABLE `readings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `idUser` smallint(5) UNSIGNED NOT NULL,
  `temperature` float NOT NULL,
  `humidity` varchar(50) NOT NULL,
  `status` varchar(45) NOT NULL,
  `ds18b20_temp` float NOT NULL,
  `soil_moisture` smallint(6) NOT NULL,
  `mq135` int(11) NOT NULL,
  `air_quality_status` varchar(50) NOT NULL,
  `ammonia` float NOT NULL,
  `co2` float NOT NULL,
  `co` float NOT NULL,
  `benzene` float NOT NULL,
  `alcohol` float NOT NULL,
  `smoke` float NOT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
--
-- Table structure for table `reports`
--

CREATE TABLE `reports` (
  `id` int(10) UNSIGNED NOT NULL,
  `type` enum('ventas','lecturas') DEFAULT NULL,
  `registrationDate` timestamp NOT NULL DEFAULT current_timestamp(),
  `file_route` text DEFAULT NULL,
  `generate_for` smallint(5) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--

--
-- Table structure for table `sales`
--

CREATE TABLE `sales` (
  `id` smallint(5) UNSIGNED NOT NULL,
  `idClient` smallint(5) UNSIGNED NOT NULL,
  `idUser` smallint(5) UNSIGNED NOT NULL,
  `date` timestamp NULL DEFAULT current_timestamp(),
  `total` decimal(10,2) NOT NULL,
  `pay` enum('efectivo','qr','otro') DEFAULT 'efectivo',
  `image` varchar(100) NOT NULL,
  `state` tinyint(4) NOT NULL DEFAULT 1 COMMENT '0-Espera//1-Aprobado//2-Rechazado',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL,
  `updated_by` smallint(6) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` smallint(5) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `firstLastName` varchar(100) NOT NULL,
  `secondLastName` varchar(100) DEFAULT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(255) NOT NULL,
  `state` tinyint(4) NOT NULL DEFAULT 1,
  `type_alert` tinyint(4) DEFAULT NULL,
  `readings_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL,
  `updated_by` smallint(6) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--

--

CREATE TABLE `user_plans` (
  `id` int(10) UNSIGNED NOT NULL,
  `idUser` smallint(5) UNSIGNED NOT NULL,
  `idPlan` tinyint(3) UNSIGNED NOT NULL,
  `started_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `expires_at` timestamp NULL DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 1 COMMENT '1: activo, 0: inactivo',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL,
  `updated_by` smallint(6) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--

--
-- Table structure for table `user_references`
--

CREATE TABLE `user_references` (
  `id` smallint(5) UNSIGNED NOT NULL,
  `idUser` smallint(5) UNSIGNED NOT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `contact_email` varchar(100) DEFAULT NULL,
  `whatsapp_link` varchar(255) DEFAULT NULL,
  `facebook_link` varchar(255) DEFAULT NULL,
  `instagram_link` varchar(255) DEFAULT NULL,
  `youtube_link` varchar(255) DEFAULT NULL,
  `tiktok_link` varchar(255) DEFAULT NULL,
  `qr_image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_by` tinyint(3) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--

-- Indexes for dumped tables
--

--
-- Indexes for table `contact_messages`
--
ALTER TABLE `contact_messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fertilizers`
--
ALTER TABLE `fertilizers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_fertilizers_user2_idx` (`idUser`);

--
-- Indexes for table `locations`
--
ALTER TABLE `locations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ubicaciones_abono_ibfk_2_idx` (`idFertilizer`);

--
-- Indexes for table `materials`
--
ALTER TABLE `materials`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payment_products`
--
ALTER TABLE `payment_products`
  ADD KEY `fk_payment_products_sales1_idx` (`idSale`),
  ADD KEY `fk_payment_products_fertilizers2_idx` (`idFertilizer`);

--
-- Indexes for table `plans`
--
ALTER TABLE `plans`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `plan_change_requests`
--
ALTER TABLE `plan_change_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `plan_change_requests_ibfk_1_idx` (`idPlan`),
  ADD KEY `plan_change_requests_ibfk_2_idx` (`idUser`);

--
-- Indexes for table `prototypes`
--
ALTER TABLE `prototypes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_prototype_plans1_idx` (`idUser`),
  ADD KEY `fk_prototypes_users1_idx` (`idUser`);

--
-- Indexes for table `readings`
--
ALTER TABLE `readings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_lecturas_usuarios2_idx` (`idUser`);

--
-- Indexes for table `reports`
--
ALTER TABLE `reports`
  ADD PRIMARY KEY (`id`),
  ADD KEY `generado_por` (`generate_for`);

--
-- Indexes for table `sales`
--
ALTER TABLE `sales`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_sales_users2_idx` (`idClient`),
  ADD KEY `ventas_ibfk_2_idx` (`idUser`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email_UNIQUE` (`email`),
  ADD UNIQUE KEY `username_UNIQUE` (`username`);

--
-- Indexes for table `user_plans`
--
ALTER TABLE `user_plans`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_user_plans_plans2_idx` (`idPlan`),
  ADD KEY `fk_user_plans_users2_idx` (`idUser`);

--
-- Indexes for table `user_references`
--
ALTER TABLE `user_references`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `phone_UNIQUE` (`phone`),
  ADD UNIQUE KEY `contact_email_UNIQUE` (`contact_email`),
  ADD UNIQUE KEY `whatsapp_link_UNIQUE` (`whatsapp_link`),
  ADD UNIQUE KEY `facebook_link_UNIQUE` (`facebook_link`),
  ADD UNIQUE KEY `instagram_link_UNIQUE` (`instagram_link`),
  ADD UNIQUE KEY `youtube_link_UNIQUE` (`youtube_link`),
  ADD UNIQUE KEY `tiktok_link_UNIQUE` (`tiktok_link`),
  ADD UNIQUE KEY `qr_image_UNIQUE` (`qr_image`),
  ADD KEY `fk_user_references_users1_idx` (`idUser`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `contact_messages`
--
ALTER TABLE `contact_messages`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `fertilizers`
--
ALTER TABLE `fertilizers`
  MODIFY `id` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `locations`
--
ALTER TABLE `locations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `materials`
--
ALTER TABLE `materials`
  MODIFY `id` tinyint(3) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `plans`
--
ALTER TABLE `plans`
  MODIFY `id` tinyint(3) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `plan_change_requests`
--
ALTER TABLE `plan_change_requests`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `prototypes`
--
ALTER TABLE `prototypes`
  MODIFY `id` tinyint(4) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `readings`
--
ALTER TABLE `readings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4806;

--
-- AUTO_INCREMENT for table `reports`
--
ALTER TABLE `reports`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `sales`
--
ALTER TABLE `sales`
  MODIFY `id` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `user_plans`
--
ALTER TABLE `user_plans`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `user_references`
--
ALTER TABLE `user_references`
  MODIFY `id` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `fertilizers`
--
ALTER TABLE `fertilizers`
  ADD CONSTRAINT `fk_fertilizers_user2` FOREIGN KEY (`idUser`) REFERENCES `users` (`id`);

--
-- Constraints for table `locations`
--
ALTER TABLE `locations`
  ADD CONSTRAINT `ubicaciones_abono_ibfk_2` FOREIGN KEY (`idFertilizer`) REFERENCES `fertilizers` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `payment_products`
--
ALTER TABLE `payment_products`
  ADD CONSTRAINT `fk_payment_products_fertilizers2` FOREIGN KEY (`idFertilizer`) REFERENCES `fertilizers` (`id`),
  ADD CONSTRAINT `fk_payment_products_sales2` FOREIGN KEY (`idSale`) REFERENCES `sales` (`id`);

--
-- Constraints for table `plan_change_requests`
--
ALTER TABLE `plan_change_requests`
  ADD CONSTRAINT `plan_change_requests_ibfk_3` FOREIGN KEY (`idPlan`) REFERENCES `plans` (`id`),
  ADD CONSTRAINT `plan_change_requests_ibfk_4` FOREIGN KEY (`idUser`) REFERENCES `users` (`id`);

--
-- Constraints for table `prototypes`
--
ALTER TABLE `prototypes`
  ADD CONSTRAINT `fk_prototypes_users2` FOREIGN KEY (`idUser`) REFERENCES `users` (`id`);

--
-- Constraints for table `readings`
--
ALTER TABLE `readings`
  ADD CONSTRAINT `fk_lecturas_usuarios2` FOREIGN KEY (`idUser`) REFERENCES `users` (`id`);

--
-- Constraints for table `reports`
--
ALTER TABLE `reports`
  ADD CONSTRAINT `reportes_generados_ibfk_1` FOREIGN KEY (`generate_for`) REFERENCES `compos_iott`.`users` (`id`);

--
-- Constraints for table `sales`
--
ALTER TABLE `sales`
  ADD CONSTRAINT `fk_sales_users2` FOREIGN KEY (`idClient`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `ventas_ibfk_2` FOREIGN KEY (`idUser`) REFERENCES `users` (`id`);

--
-- Constraints for table `user_plans`
--
ALTER TABLE `user_plans`
  ADD CONSTRAINT `fk_user_plans_plans2` FOREIGN KEY (`idPlan`) REFERENCES `plans` (`id`),
  ADD CONSTRAINT `fk_user_plans_users2` FOREIGN KEY (`idUser`) REFERENCES `users` (`id`);

--
-- Constraints for table `user_references`
--
ALTER TABLE `user_references`
  ADD CONSTRAINT `fk_user_references_users2` FOREIGN KEY (`idUser`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
