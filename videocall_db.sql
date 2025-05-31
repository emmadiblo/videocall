-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : sam. 31 mai 2025 à 09:06
-- Version du serveur : 10.4.32-MariaDB
-- Version de PHP : 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `videocall_db`
--

-- --------------------------------------------------------

--
-- Structure de la table `rooms`
--

CREATE TABLE `rooms` (
  `id` int(11) NOT NULL,
  `code` varchar(10) NOT NULL,
  `creator_id` int(11) NOT NULL,
  `status` enum('active','closed') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `expires_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `rooms`
--

INSERT INTO `rooms` (`id`, `code`, `creator_id`, `status`, `created_at`, `expires_at`) VALUES
(1, '1BAPVC', 1, 'active', '2025-05-30 15:09:33', '2025-05-30 18:09:33'),
(2, 'C9TXUM', 1, 'active', '2025-05-30 15:09:50', '2025-05-30 18:09:50'),
(3, 'KBZCGJ', 1, 'active', '2025-05-30 15:09:57', '2025-05-30 18:09:57'),
(4, '279PBM', 1, 'active', '2025-05-30 15:09:58', '2025-05-30 18:09:58'),
(5, 'JF7GA9', 1, 'active', '2025-05-30 15:09:59', '2025-05-30 18:09:59'),
(6, 'NM8YQ0', 1, 'active', '2025-05-30 15:13:29', '2025-05-30 18:13:29'),
(7, '9ZAFHQ', 1, 'active', '2025-05-30 15:16:12', '2025-05-30 18:16:12'),
(8, 'VUY34J', 1, 'active', '2025-05-30 15:26:39', '2025-05-30 18:26:39'),
(9, '8JH52C', 1, 'active', '2025-05-30 15:28:18', '2025-05-30 18:28:18'),
(10, '10ZM38', 1, 'active', '2025-05-30 15:53:10', '2025-05-30 18:53:10'),
(11, 'SKG561', 1, 'active', '2025-05-30 15:55:57', '2025-05-30 18:55:57'),
(12, '1DI3GT', 1, 'active', '2025-05-30 16:18:06', '2025-05-30 19:18:06'),
(13, 'ELAW2G', 2, 'active', '2025-05-30 16:21:35', '2025-05-30 19:21:35'),
(14, 'C6S2TM', 2, 'active', '2025-05-30 16:41:16', '2025-05-30 19:41:16'),
(15, '9KGRE3', 3, 'active', '2025-05-30 16:51:30', '2025-05-30 19:51:30'),
(16, 'JSXWNF', 3, 'active', '2025-05-30 16:51:59', '2025-05-30 19:51:59'),
(17, '3Z8KD2', 2, 'active', '2025-05-30 16:55:52', '2025-05-30 19:55:52'),
(18, 'DBNJEA', 1, 'closed', '2025-05-30 17:32:49', '2025-05-30 20:32:49'),
(19, 'ZFSAWJ', 2, 'active', '2025-05-30 17:35:50', '2025-05-30 20:35:50'),
(20, '9AV3RL', 2, 'closed', '2025-05-30 17:47:59', '2025-05-30 20:47:59'),
(21, '7WO8XQ', 2, 'active', '2025-05-30 17:58:13', '2025-05-30 20:58:13'),
(22, 'X3L6OH', 2, 'active', '2025-05-30 19:28:48', '2025-05-30 22:28:48'),
(23, 'UR09P2', 1, 'active', '2025-05-30 20:50:11', '2025-05-30 23:50:11');

-- --------------------------------------------------------

--
-- Structure de la table `room_participants`
--

CREATE TABLE `room_participants` (
  `id` int(11) NOT NULL,
  `room_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `joined_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `room_participants`
--

INSERT INTO `room_participants` (`id`, `room_id`, `user_id`, `joined_at`) VALUES
(1, 12, 2, '2025-05-30 16:39:04'),
(2, 14, 1, '2025-05-30 16:41:40'),
(3, 14, 3, '2025-05-30 16:47:03'),
(15, 17, 3, '2025-05-30 16:56:29'),
(16, 17, 1, '2025-05-30 16:56:55'),
(20, 19, 1, '2025-05-30 17:36:02'),
(22, 21, 1, '2025-05-30 17:58:36'),
(23, 21, 4, '2025-05-30 18:13:47'),
(24, 23, 4, '2025-05-30 20:51:23');

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `created_at`) VALUES
(1, 'emmadiblo', 'emma@gmail.com', '$2y$10$jaPK7zGOf5f3ZJsj7eWjk..391GlsIFQFunzImm4KtfJTxEX5Tb4q', '2025-05-30 15:03:46'),
(2, 'Uwizeyimana', 'uwizeyimana@gmail.com', '$2y$10$dR.8DExB3I6R9u6xQ59HIuLm.9NboJ4r/qCqfiOvHFPMtw309MOtS', '2025-05-30 16:19:32'),
(3, 'Diblo', 'emmadiblouwizeyimana@gmail.com', '$2y$10$unPns7zuDKqBJRy.pj4TMOcHh.1fBfgleR7R8elbxD3NVveUZ1vU6', '2025-05-30 16:46:11'),
(4, 'ismael iradukunda', 'ismaeliradukunda01@gmail.com', '$2y$10$X.pGkWtr9AwvmZsht35qDe3gQjJjJaefC.H0tdLXaiPklB5c2I/2y', '2025-05-30 18:13:33');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `rooms`
--
ALTER TABLE `rooms`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`),
  ADD KEY `creator_id` (`creator_id`);

--
-- Index pour la table `room_participants`
--
ALTER TABLE `room_participants`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `room_id` (`room_id`,`user_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `rooms`
--
ALTER TABLE `rooms`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT pour la table `room_participants`
--
ALTER TABLE `room_participants`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `rooms`
--
ALTER TABLE `rooms`
  ADD CONSTRAINT `rooms_ibfk_1` FOREIGN KEY (`creator_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `room_participants`
--
ALTER TABLE `room_participants`
  ADD CONSTRAINT `room_participants_ibfk_1` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `room_participants_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
