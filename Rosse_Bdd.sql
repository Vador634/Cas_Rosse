-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost
-- Généré le : sam. 02 mai 2026 à 12:46
-- Version du serveur : 10.3.39-MariaDB-0+deb10u1
-- Version de PHP : 7.3.31-1~deb10u5

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `Hyperborea_Bdd`
--

-- --------------------------------------------------------

--
-- Structure de la table `Borne`
--

CREATE TABLE `Borne` (
  `idBorne` int(11) NOT NULL,
  `dateDerniereRevision` date NOT NULL,
  `indiceCompteurUnites` int(11) NOT NULL,
  `codeTypeBorne` varchar(10) NOT NULL,
  `idStation` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `Station`
--

CREATE TABLE `Station` (
  `idStation` int(11) NOT NULL,
  `libelleEmplacement` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `Technicien`
--

CREATE TABLE `Technicien` (
  `matricule` int(11) NOT NULL,
  `nom` varchar(50) NOT NULL,
  `prenom` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `Technicien_Visite`
--

CREATE TABLE `Technicien_Visite` (
  `matricule` int(11) NOT NULL,
  `idVisite` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `TypeBorne`
--

CREATE TABLE `TypeBorne` (
  `codeTypeBorne` varchar(10) NOT NULL,
  `dureeRevision` int(11) NOT NULL,
  `nbJoursEntreRevisions` int(11) NOT NULL,
  `nbUnitesEntreRevisions` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `Visite`
--

CREATE TABLE `Visite` (
  `idVisite` int(11) NOT NULL,
  `etat` char(1) NOT NULL CHECK (`etat` in ('p','a','r')),
  `dureeTotale` int(11) NOT NULL,
  `idStation` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `Visite_Borne`
--

CREATE TABLE `Visite_Borne` (
  `idVisite` int(11) NOT NULL,
  `idBorne` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `Borne`
--
ALTER TABLE `Borne`
  ADD PRIMARY KEY (`idBorne`),
  ADD KEY `codeTypeBorne` (`codeTypeBorne`),
  ADD KEY `idStation` (`idStation`);

--
-- Index pour la table `Station`
--
ALTER TABLE `Station`
  ADD PRIMARY KEY (`idStation`);

--
-- Index pour la table `Technicien`
--
ALTER TABLE `Technicien`
  ADD PRIMARY KEY (`matricule`);

--
-- Index pour la table `Technicien_Visite`
--
ALTER TABLE `Technicien_Visite`
  ADD PRIMARY KEY (`matricule`,`idVisite`),
  ADD KEY `idVisite` (`idVisite`);

--
-- Index pour la table `TypeBorne`
--
ALTER TABLE `TypeBorne`
  ADD PRIMARY KEY (`codeTypeBorne`);

--
-- Index pour la table `Visite`
--
ALTER TABLE `Visite`
  ADD PRIMARY KEY (`idVisite`),
  ADD KEY `idStation` (`idStation`);

--
-- Index pour la table `Visite_Borne`
--
ALTER TABLE `Visite_Borne`
  ADD PRIMARY KEY (`idVisite`,`idBorne`),
  ADD KEY `idBorne` (`idBorne`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `Borne`
--
ALTER TABLE `Borne`
  MODIFY `idBorne` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `Station`
--
ALTER TABLE `Station`
  MODIFY `idStation` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `Visite`
--
ALTER TABLE `Visite`
  MODIFY `idVisite` int(11) NOT NULL AUTO_INCREMENT;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `Borne`
--
ALTER TABLE `Borne`
  ADD CONSTRAINT `Borne_ibfk_1` FOREIGN KEY (`codeTypeBorne`) REFERENCES `TypeBorne` (`codeTypeBorne`),
  ADD CONSTRAINT `Borne_ibfk_2` FOREIGN KEY (`idStation`) REFERENCES `Station` (`idStation`);

--
-- Contraintes pour la table `Technicien_Visite`
--
ALTER TABLE `Technicien_Visite`
  ADD CONSTRAINT `Technicien_Visite_ibfk_1` FOREIGN KEY (`matricule`) REFERENCES `Technicien` (`matricule`),
  ADD CONSTRAINT `Technicien_Visite_ibfk_2` FOREIGN KEY (`idVisite`) REFERENCES `Visite` (`idVisite`);

--
-- Contraintes pour la table `Visite`
--
ALTER TABLE `Visite`
  ADD CONSTRAINT `Visite_ibfk_1` FOREIGN KEY (`idStation`) REFERENCES `Station` (`idStation`);

--
-- Contraintes pour la table `Visite_Borne`
--
ALTER TABLE `Visite_Borne`
  ADD CONSTRAINT `Visite_Borne_ibfk_1` FOREIGN KEY (`idVisite`) REFERENCES `Visite` (`idVisite`),
  ADD CONSTRAINT `Visite_Borne_ibfk_2` FOREIGN KEY (`idBorne`) REFERENCES `Borne` (`idBorne`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
