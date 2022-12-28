-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : mer. 28 déc. 2022 à 08:46
-- Version du serveur : 10.4.27-MariaDB
-- Version de PHP : 8.1.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `conciergerie`
--

-- --------------------------------------------------------

--
-- Structure de la table `client`
--

CREATE TABLE `client` (
  `id_client` bigint(20) UNSIGNED NOT NULL,
  `id_commande` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(20) NOT NULL,
  `surname` varchar(20) NOT NULL,
  `code` varchar(15) NOT NULL,
  `postal_address` varchar(50) NOT NULL,
  `facebook_username` varchar(20) NOT NULL,
  `instagram_username` varchar(20) NOT NULL,
  `email` varchar(25) NOT NULL,
  `membership` varchar(15) NOT NULL,
  `next_discount` tinyint(3) UNSIGNED NOT NULL,
  `arrival_date` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `commande`
--

CREATE TABLE `commande` (
  `id_commande` bigint(20) UNSIGNED NOT NULL,
  `id_points` bigint(20) UNSIGNED NOT NULL,
  `id_client` bigint(20) UNSIGNED NOT NULL,
  `numero` varchar(15) NOT NULL,
  `cmd_date` date NOT NULL,
  `cmd_arrival_date` date NOT NULL,
  `status` enum('To buy','Bought','Packed','Shipped','Arrived','Delivered','Done') NOT NULL,
  `delivery_price` int(11) NOT NULL,
  `service_price` int(11) NOT NULL,
  `note` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `correspondance_fournisseur_produit`
--

CREATE TABLE `correspondance_fournisseur_produit` (
  `id_produit` bigint(20) UNSIGNED NOT NULL,
  `id_fournisseur` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `facture`
--

CREATE TABLE `facture` (
  `id_facture` bigint(20) UNSIGNED NOT NULL,
  `id_commande` bigint(20) UNSIGNED NOT NULL,
  `facture_date` date NOT NULL,
  `facture_date_maj` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `fournisseur`
--

CREATE TABLE `fournisseur` (
  `id_fournisseur` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(20) NOT NULL,
  `surname` varchar(20) NOT NULL,
  `email` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `historique`
--

CREATE TABLE `historique` (
  `id_commande` bigint(20) UNSIGNED NOT NULL,
  `id_produit` bigint(20) UNSIGNED NOT NULL,
  `quantity` int(10) UNSIGNED NOT NULL,
  `sold_price` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `liste_paiement_commande`
--

CREATE TABLE `liste_paiement_commande` (
  `id_commande` bigint(20) UNSIGNED NOT NULL,
  `id_paiement` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `mode_paiement`
--

CREATE TABLE `mode_paiement` (
  `id_mode_paiement` bigint(20) UNSIGNED NOT NULL,
  `mode` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `paiement`
--

CREATE TABLE `paiement` (
  `id_paiement` bigint(20) UNSIGNED NOT NULL,
  `id_mode_paiement` bigint(20) UNSIGNED NOT NULL,
  `montant` int(10) UNSIGNED NOT NULL,
  `payment_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `points`
--

CREATE TABLE `points` (
  `id_points` bigint(20) UNSIGNED NOT NULL,
  `id_client` bigint(20) UNSIGNED NOT NULL,
  `nb_points` int(11) NOT NULL,
  `exp_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `produit`
--

CREATE TABLE `produit` (
  `id_produit` bigint(20) UNSIGNED NOT NULL,
  `product_name` varchar(50) NOT NULL,
  `img_preview` text DEFAULT NULL,
  `unit_price` double NOT NULL,
  `status` enum('In stock','Availaible','Not availaible','Out of stock','Free gift','Packed','Dispatched','Arrived','Delivered','Other') NOT NULL,
  `nb_dispo` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `telephone`
--

CREATE TABLE `telephone` (
  `id_telephone` bigint(20) UNSIGNED NOT NULL,
  `id_client` bigint(20) UNSIGNED NOT NULL,
  `numero` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `client`
--
ALTER TABLE `client`
  ADD PRIMARY KEY (`id_client`);

--
-- Index pour la table `commande`
--
ALTER TABLE `commande`
  ADD PRIMARY KEY (`id_commande`);

--
-- Index pour la table `correspondance_fournisseur_produit`
--
ALTER TABLE `correspondance_fournisseur_produit`
  ADD KEY `fk_id_produit_on_correspondance_fournisseur_produit` (`id_produit`),
  ADD KEY `fk_id_fournisseur_on_correspondance_fournisseur_produit` (`id_fournisseur`);

--
-- Index pour la table `facture`
--
ALTER TABLE `facture`
  ADD PRIMARY KEY (`id_facture`),
  ADD KEY `fk_id_commande_on_facture` (`id_commande`) USING BTREE;

--
-- Index pour la table `fournisseur`
--
ALTER TABLE `fournisseur`
  ADD PRIMARY KEY (`id_fournisseur`);

--
-- Index pour la table `historique`
--
ALTER TABLE `historique`
  ADD KEY `fk_id_commande_on_historique` (`id_commande`),
  ADD KEY `fk_id_produit_on_historique` (`id_produit`);

--
-- Index pour la table `liste_paiement_commande`
--
ALTER TABLE `liste_paiement_commande`
  ADD KEY `fk_id_commande_on_liste_paiement_commande` (`id_commande`),
  ADD KEY `fk_id_paiement_on_liste_paiement_commande` (`id_paiement`);

--
-- Index pour la table `mode_paiement`
--
ALTER TABLE `mode_paiement`
  ADD PRIMARY KEY (`id_mode_paiement`);

--
-- Index pour la table `paiement`
--
ALTER TABLE `paiement`
  ADD PRIMARY KEY (`id_paiement`),
  ADD KEY `fk_id_mode_paiement_on_paiement` (`id_mode_paiement`);

--
-- Index pour la table `points`
--
ALTER TABLE `points`
  ADD PRIMARY KEY (`id_points`),
  ADD KEY `fk_id_client_on_points` (`id_client`) USING BTREE;

--
-- Index pour la table `produit`
--
ALTER TABLE `produit`
  ADD PRIMARY KEY (`id_produit`);

--
-- Index pour la table `telephone`
--
ALTER TABLE `telephone`
  ADD PRIMARY KEY (`id_telephone`),
  ADD KEY `fk_id_client_on_telephone` (`id_client`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `client`
--
ALTER TABLE `client`
  MODIFY `id_client` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `commande`
--
ALTER TABLE `commande`
  MODIFY `id_commande` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `facture`
--
ALTER TABLE `facture`
  MODIFY `id_facture` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `fournisseur`
--
ALTER TABLE `fournisseur`
  MODIFY `id_fournisseur` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `mode_paiement`
--
ALTER TABLE `mode_paiement`
  MODIFY `id_mode_paiement` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `paiement`
--
ALTER TABLE `paiement`
  MODIFY `id_paiement` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `points`
--
ALTER TABLE `points`
  MODIFY `id_points` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `produit`
--
ALTER TABLE `produit`
  MODIFY `id_produit` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `telephone`
--
ALTER TABLE `telephone`
  MODIFY `id_telephone` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `correspondance_fournisseur_produit`
--
ALTER TABLE `correspondance_fournisseur_produit`
  ADD CONSTRAINT `fk_id_fournisseur_on_correspondance_fournisseur_produit` FOREIGN KEY (`id_fournisseur`) REFERENCES `fournisseur` (`id_fournisseur`),
  ADD CONSTRAINT `fk_id_produit_on_correspondance_fournisseur_produit` FOREIGN KEY (`id_produit`) REFERENCES `produit` (`id_produit`);

--
-- Contraintes pour la table `facture`
--
ALTER TABLE `facture`
  ADD CONSTRAINT `fk_foreign_key_name` FOREIGN KEY (`id_commande`) REFERENCES `commande` (`id_commande`);

--
-- Contraintes pour la table `historique`
--
ALTER TABLE `historique`
  ADD CONSTRAINT `fk_id_commande_on_historique` FOREIGN KEY (`id_commande`) REFERENCES `commande` (`id_commande`),
  ADD CONSTRAINT `fk_id_produit_on_historique` FOREIGN KEY (`id_produit`) REFERENCES `produit` (`id_produit`);

--
-- Contraintes pour la table `liste_paiement_commande`
--
ALTER TABLE `liste_paiement_commande`
  ADD CONSTRAINT `fk_id_commande_on_liste_paiement_commande` FOREIGN KEY (`id_commande`) REFERENCES `commande` (`id_commande`),
  ADD CONSTRAINT `fk_id_paiement_on_liste_paiement_commande` FOREIGN KEY (`id_paiement`) REFERENCES `paiement` (`id_paiement`);

--
-- Contraintes pour la table `paiement`
--
ALTER TABLE `paiement`
  ADD CONSTRAINT `fk_id_mode_paiement_on_paiement` FOREIGN KEY (`id_mode_paiement`) REFERENCES `mode_paiement` (`id_mode_paiement`);

--
-- Contraintes pour la table `points`
--
ALTER TABLE `points`
  ADD CONSTRAINT `fk_id_client` FOREIGN KEY (`id_client`) REFERENCES `client` (`id_client`);

--
-- Contraintes pour la table `telephone`
--
ALTER TABLE `telephone`
  ADD CONSTRAINT `fk_id_client_on_telephone` FOREIGN KEY (`id_client`) REFERENCES `client` (`id_client`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
