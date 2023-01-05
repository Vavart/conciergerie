-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : jeu. 05 jan. 2023 à 18:32
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
-- Structure de la table `cadre_depense_points`
--

CREATE TABLE `cadre_depense_points` (
  `id_cadre_depense_points` bigint(20) UNSIGNED NOT NULL,
  `id_historique_points` bigint(20) UNSIGNED NOT NULL,
  `motif_utilisation` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `cadre_depense_points`
--

INSERT INTO `cadre_depense_points` (`id_cadre_depense_points`, `id_historique_points`, `motif_utilisation`) VALUES
(1, 1, 'cadeau offert');

-- --------------------------------------------------------

--
-- Structure de la table `client`
--

CREATE TABLE `client` (
  `id_client` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(200) NOT NULL,
  `surname` varchar(200) NOT NULL,
  `code` varchar(15) NOT NULL,
  `postal_address` varchar(200) NOT NULL,
  `facebook_username` varchar(200) NOT NULL,
  `instagram_username` varchar(200) NOT NULL,
  `email` varchar(200) NOT NULL,
  `membership` varchar(15) NOT NULL,
  `is_ultimate` tinyint(4) NOT NULL,
  `next_discount` tinyint(3) UNSIGNED NOT NULL,
  `arrival_date` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `client`
--

INSERT INTO `client` (`id_client`, `name`, `surname`, `code`, `postal_address`, `facebook_username`, `instagram_username`, `email`, `membership`, `is_ultimate`, `next_discount`, `arrival_date`) VALUES
(6, 'Kévin', 'De la Gare', '22-SPR-0001', '6 Rue de la Pastaga', 'facebook', 'insta', 'mail@yahoo.fr', 'Silver', 0, 0, '2022-12-30'),
(14, 'Jean Michel', 'de la gare', '22-SPR-0002', '6 Rue de la pastagaaaaaaaaaaaaa', 'fb', 'insta', 'mail@yahoo.fr', 'Silver', 0, 0, '2022-12-30'),
(15, 'Michel', 'Dupont', '22-SPR-0003', 'Rue des Mimosas 75000 Paris', 'michou', 'dudu', 'dupont.michel@yahoo.fr', 'Silver', 0, 0, '2022-12-30'),
(16, 'Jean', 'Némar', '23-SPR-0004', '17 Rue des Clébards', 'JN', 'JNN (Jean Neural Network)', 'jeannemar@gmail.com', 'Silver', 1, 0, '2023-01-05');

-- --------------------------------------------------------

--
-- Structure de la table `commande`
--

CREATE TABLE `commande` (
  `id_commande` bigint(20) UNSIGNED NOT NULL,
  `id_points` bigint(20) UNSIGNED NOT NULL,
  `numero` varchar(15) NOT NULL,
  `cmd_date` date NOT NULL,
  `cmd_arrival_date` date NOT NULL,
  `status` varchar(15) NOT NULL,
  `delivery_price` int(11) NOT NULL,
  `service_price` int(11) NOT NULL,
  `note` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `commande`
--

INSERT INTO `commande` (`id_commande`, `id_points`, `numero`, `cmd_date`, `cmd_arrival_date`, `status`, `delivery_price`, `service_price`, `note`) VALUES
(14, 27, '050123-CMD-C001', '2023-01-05', '0000-00-00', 'to_buy', 0, 0, 'nonote');

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
-- Structure de la table `historique`
--

CREATE TABLE `historique` (
  `id_commande` bigint(20) UNSIGNED NOT NULL,
  `id_produit` bigint(20) UNSIGNED NOT NULL,
  `quantity` int(10) NOT NULL,
  `sold_price` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `historique`
--

INSERT INTO `historique` (`id_commande`, `id_produit`, `quantity`, `sold_price`) VALUES
(14, 1, 1, 10);

-- --------------------------------------------------------

--
-- Structure de la table `historique_points`
--

CREATE TABLE `historique_points` (
  `id_historique_points` bigint(20) UNSIGNED NOT NULL,
  `id_client` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `historique_points`
--

INSERT INTO `historique_points` (`id_historique_points`, `id_client`) VALUES
(1, 16);

-- --------------------------------------------------------

--
-- Structure de la table `liste_client_commande`
--

CREATE TABLE `liste_client_commande` (
  `id_client` bigint(20) UNSIGNED NOT NULL,
  `id_commande` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `liste_client_commande`
--

INSERT INTO `liste_client_commande` (`id_client`, `id_commande`) VALUES
(6, 14);

-- --------------------------------------------------------

--
-- Structure de la table `liste_paiement_commande`
--

CREATE TABLE `liste_paiement_commande` (
  `id_paiement` bigint(20) UNSIGNED NOT NULL,
  `id_commande` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `liste_paiement_commande`
--

INSERT INTO `liste_paiement_commande` (`id_paiement`, `id_commande`) VALUES
(21, 14);

-- --------------------------------------------------------

--
-- Structure de la table `mode_paiement`
--

CREATE TABLE `mode_paiement` (
  `id_mode_paiement` bigint(20) UNSIGNED NOT NULL,
  `mode` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `mode_paiement`
--

INSERT INTO `mode_paiement` (`id_mode_paiement`, `mode`) VALUES
(6, 'carte'),
(7, 'cheque');

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

--
-- Déchargement des données de la table `paiement`
--

INSERT INTO `paiement` (`id_paiement`, `id_mode_paiement`, `montant`, `payment_date`) VALUES
(21, 6, 5, '2023-01-05');

-- --------------------------------------------------------

--
-- Structure de la table `points`
--

CREATE TABLE `points` (
  `id_points` bigint(20) UNSIGNED NOT NULL,
  `id_client` bigint(20) UNSIGNED NOT NULL,
  `nb_points` int(11) NOT NULL,
  `exp_date` date NOT NULL,
  `id_cadre_depense_points` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `points`
--

INSERT INTO `points` (`id_points`, `id_client`, `nb_points`, `exp_date`, `id_cadre_depense_points`) VALUES
(27, 16, 100, '2024-01-05', NULL),
(28, 16, 150, '2024-01-05', 1),
(29, 16, 75, '2023-01-15', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `produit`
--

CREATE TABLE `produit` (
  `id_produit` bigint(20) UNSIGNED NOT NULL,
  `product_name` varchar(50) NOT NULL,
  `unit_price` double NOT NULL,
  `status` varchar(50) NOT NULL,
  `nb_dispo` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `produit`
--

INSERT INTO `produit` (`id_produit`, `product_name`, `unit_price`, `status`, `nb_dispo`) VALUES
(1, 'Un bon produit', 10, 'stock', 2),
(2, 'Extrait de roche de fou malade', 150, 'out_of_stock', 0);

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
-- Déchargement des données de la table `telephone`
--

INSERT INTO `telephone` (`id_telephone`, `id_client`, `numero`) VALUES
(1, 6, '332564917255'),
(5, 14, '3364585956271'),
(6, 14, '332'),
(7, 6, '33245'),
(8, 15, '3364585956271'),
(9, 15, '33245'),
(10, 16, '33245856595'),
(11, 16, '33674846424');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `cadre_depense_points`
--
ALTER TABLE `cadre_depense_points`
  ADD PRIMARY KEY (`id_cadre_depense_points`),
  ADD KEY `fk_id_historique_points_on_cadre_depense_points` (`id_historique_points`);

--
-- Index pour la table `client`
--
ALTER TABLE `client`
  ADD PRIMARY KEY (`id_client`);

--
-- Index pour la table `commande`
--
ALTER TABLE `commande`
  ADD PRIMARY KEY (`id_commande`),
  ADD KEY `fk_id_points_on_commande` (`id_points`);

--
-- Index pour la table `facture`
--
ALTER TABLE `facture`
  ADD PRIMARY KEY (`id_facture`),
  ADD KEY `fk_id_commande_on_facture` (`id_commande`) USING BTREE;

--
-- Index pour la table `historique`
--
ALTER TABLE `historique`
  ADD PRIMARY KEY (`id_produit`,`id_commande`),
  ADD KEY `fk_id_commande_on_historique` (`id_commande`);

--
-- Index pour la table `historique_points`
--
ALTER TABLE `historique_points`
  ADD PRIMARY KEY (`id_historique_points`),
  ADD KEY `fk_id_client_on_historique_points` (`id_client`);

--
-- Index pour la table `liste_client_commande`
--
ALTER TABLE `liste_client_commande`
  ADD PRIMARY KEY (`id_client`,`id_commande`),
  ADD KEY `fk_id_commande_on_liste_client_commande` (`id_commande`);

--
-- Index pour la table `liste_paiement_commande`
--
ALTER TABLE `liste_paiement_commande`
  ADD PRIMARY KEY (`id_paiement`,`id_commande`),
  ADD KEY `fk_id_commande_on_liste_paiement_commande` (`id_commande`);

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
  ADD KEY `fk_id_client_on_points` (`id_client`) USING BTREE,
  ADD KEY `fk_id_cadre_depense_points_on_points` (`id_cadre_depense_points`);

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
-- AUTO_INCREMENT pour la table `cadre_depense_points`
--
ALTER TABLE `cadre_depense_points`
  MODIFY `id_cadre_depense_points` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `client`
--
ALTER TABLE `client`
  MODIFY `id_client` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT pour la table `commande`
--
ALTER TABLE `commande`
  MODIFY `id_commande` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT pour la table `facture`
--
ALTER TABLE `facture`
  MODIFY `id_facture` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `historique_points`
--
ALTER TABLE `historique_points`
  MODIFY `id_historique_points` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `mode_paiement`
--
ALTER TABLE `mode_paiement`
  MODIFY `id_mode_paiement` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT pour la table `paiement`
--
ALTER TABLE `paiement`
  MODIFY `id_paiement` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT pour la table `points`
--
ALTER TABLE `points`
  MODIFY `id_points` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT pour la table `produit`
--
ALTER TABLE `produit`
  MODIFY `id_produit` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `telephone`
--
ALTER TABLE `telephone`
  MODIFY `id_telephone` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `cadre_depense_points`
--
ALTER TABLE `cadre_depense_points`
  ADD CONSTRAINT `fk_id_historique_points_on_cadre_depense_points` FOREIGN KEY (`id_historique_points`) REFERENCES `historique_points` (`id_historique_points`);

--
-- Contraintes pour la table `commande`
--
ALTER TABLE `commande`
  ADD CONSTRAINT `fk_id_points_on_commande` FOREIGN KEY (`id_points`) REFERENCES `points` (`id_points`);

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
-- Contraintes pour la table `historique_points`
--
ALTER TABLE `historique_points`
  ADD CONSTRAINT `fk_id_client_on_historique_points` FOREIGN KEY (`id_client`) REFERENCES `client` (`id_client`);

--
-- Contraintes pour la table `liste_client_commande`
--
ALTER TABLE `liste_client_commande`
  ADD CONSTRAINT `fk_id_client_on_liste_client_commande` FOREIGN KEY (`id_client`) REFERENCES `client` (`id_client`),
  ADD CONSTRAINT `fk_id_commande_on_liste_client_commande` FOREIGN KEY (`id_commande`) REFERENCES `commande` (`id_commande`);

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
  ADD CONSTRAINT `fk_id_cadre_depense_points_on_points` FOREIGN KEY (`id_cadre_depense_points`) REFERENCES `cadre_depense_points` (`id_cadre_depense_points`),
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
