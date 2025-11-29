-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : mar. 25 nov. 2025 à 12:19
-- Version du serveur : 8.2.0
-- Version de PHP : 8.2.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `suivi_prestation`
--

-- --------------------------------------------------------

--
-- Structure de la table `annee`
--

DROP TABLE IF EXISTS `annee`;
CREATE TABLE IF NOT EXISTS `annee` (
                                       `code_annee` int NOT NULL AUTO_INCREMENT,
                                       `dt_debut` date NOT NULL,
                                       `dt_fin` date NOT NULL,
                                       `description` varchar(255) NOT NULL,
    PRIMARY KEY (`code_annee`)
    ) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `permissions`
--

DROP TABLE IF EXISTS `permissions`;
CREATE TABLE IF NOT EXISTS `permissions` (
                                             `id` int NOT NULL AUTO_INCREMENT,
                                             `role` varchar(50) NOT NULL,
    `permission` varchar(100) NOT NULL,
    `description` text,
    PRIMARY KEY (`id`),
    UNIQUE KEY `unique_role_permission` (`role`,`permission`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Contenu de la table `permissions`
--

INSERT INTO `permissions` (`role`, `permission`, `description`) VALUES
                                                                    ('admin', 'manage_users', 'Gérer les utilisateurs'),
                                                                    ('admin', 'manage_courses', 'Gérer les cours'),
                                                                    ('admin', 'manage_teachers', 'Gérer les enseignants'),
                                                                    ('admin', 'manage_students', 'Gérer les étudiants'),
                                                                    ('admin', 'view_reports', 'Voir tous les rapports'),
                                                                    ('admin', 'manage_settings', 'Gérer les paramètres du système'),
                                                                    ('enseignant', 'view_assigned_courses', 'Voir les cours assignés'),
                                                                    ('enseignant', 'manage_class_schedule', 'Gérer l\'horaire de classe'),
('enseignant', 'record_attendance', 'Enregistrer la présence'),
('enseignant', 'enter_grades', 'Saisir les notes'),
('enseignant', 'view_student_list', 'Voir la liste des étudiants'),
('enseignant', 'manage_course_materials', 'Gérer les documents de cours'),
('etudiant', 'view_personal_schedule', 'Voir l\'horaire personnel'),
                                                                    ('etudiant', 'view_grades', 'Voir les notes'),
                                                                    ('etudiant', 'view_course_materials', 'Voir les documents de cours'),
                                                                    ('etudiant', 'submit_assignments', 'Soumettre les devoirs'),
                                                                    ('etudiant', 'register_for_courses', 'S\'inscrire aux cours'),
('cp', 'manage_prestations', 'Gérer les prestations'),
('cp', 'validate_hours', 'Valider les heures de cours'),
('cp', 'generate_reports', 'Générer des rapports'),
('cp', 'manage_hour_records', 'Gérer les enregistrements d\'heures'),
                                                                    ('secretaire', 'manage_student_registration', 'Gérer l\'inscription des étudiants'),
('secretaire', 'manage_documents', 'Gérer les documents'),
('secretaire', 'handle_inquiries', 'Traiter les demandes'),
('chefsection', 'manage_section_courses', 'Gérer les cours de la section'),
('chefsection', 'assign_teachers', 'Assigner les enseignants'),
('chefsection', 'approve_schedules', 'Approuver les horaires'),
('chefsection', 'generate_section_reports', 'Générer des rapports de section'),
('ab', 'manage_payments', 'Gérer les paiements'),
('ab', 'generate_financial_reports', 'Générer des rapports financiers'),
('ab', 'manage_honoraries', 'Gérer les honoraires');

-- --------------------------------------------------------

--
-- Structure de la table `chargehoraire`
--

DROP TABLE IF EXISTS `chargehoraire`;
CREATE TABLE IF NOT EXISTS `chargehoraire` (
  `id` int NOT NULL AUTO_INCREMENT,
  `matricule` varchar(50) DEFAULT NULL,
  `codecours` varchar(50) DEFAULT NULL,
  `codepromotion` varchar(10) DEFAULT NULL,
  `observation` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_matricule` (`matricule`),
  KEY `idx_codecours` (`codecours`),
  KEY `idx_codepromotion` (`codepromotion`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `chargehoraire`
--

INSERT INTO `chargehoraire` (`id`, `matricule`, `codecours`, `codepromotion`, `observation`) VALUES
(1, 'E0001', 'IG1', 'L1GI', 'ok'),
(2, 'E0001', 'AL1', 'L1GI', 'ok');

-- --------------------------------------------------------

--
-- Structure de la table `contenufiche`
--

DROP TABLE IF EXISTS `contenufiche`;
CREATE TABLE IF NOT EXISTS `contenufiche` (
  `id` int NOT NULL AUTO_INCREMENT,
  `identetefiche` int DEFAULT NULL,
  `datejoure` date DEFAULT NULL,
  `contenu` text,
  `heureEntree` time DEFAULT NULL,
  `heureSortie` time DEFAULT NULL,
  `nbreH` double DEFAULT NULL,
  `signatureCP` varchar(4) DEFAULT NULL,
  `signatureEnseignant` varchar(8) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_identetefiche` (`identetefiche`),
  KEY `idx_datejoure` (`datejoure`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `contenufiche`
--

INSERT INTO `contenufiche` (`id`, `identetefiche`, `datejoure`, `contenu`, `heureEntree`, `heureSortie`, `nbreH`, `signatureCP`, `signatureEnseignant`) VALUES
(1, 1, '2025-11-20', 'Introduction du cours', '14:00:00', '17:30:00', 3, 'ok', 'ok'),
(2, 1, '2025-11-20', 'Ordinateur avec ses composant', '14:00:00', '17:30:00', 3, 'ok', 'ok');

-- --------------------------------------------------------

--
-- Structure de la table `cours`
--

DROP TABLE IF EXISTS `cours`;
CREATE TABLE IF NOT EXISTS `cours` (
  `id` int NOT NULL AUTO_INCREMENT,
  `code_cours` varchar(50) NOT NULL,
  `nomComplet` varchar(255) NOT NULL,
  `nbreHeure` int NOT NULL,
  `ponderation` int NOT NULL,
  `code_mention` int NOT NULL,
  `code_section` int DEFAULT NULL,
  `dtsave` datetime DEFAULT CURRENT_TIMESTAMP,
  `promotion` varchar(50) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `code_cours` (`code_cours`),
  KEY `idx_code_mention` (`code_mention`),
  KEY `idx_code_section` (`code_section`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `cours`
--

INSERT INTO `cours` (`id`, `code_cours`, `nomComplet`, `nbreHeure`, `ponderation`, `code_mention`, `code_section`, `dtsave`, `promotion`, `description`) VALUES
(2, 'IG1', 'Informatic Generale', 90, 120, 2, 2, '2025-11-18 12:35:02', '5', 'Informatic Generale'),
(3, 'AL1', 'Algorithmic', 90, 120, 2, 2, '2025-11-18 12:38:41', '5', 'Algorithmic introduction a la programmation'),
(4, 'FR1', 'Francais', 75, 100, 2, 2, '2025-11-18 12:41:10', '5', 'Francais generale');

-- --------------------------------------------------------

--
-- Structure de la table `coursfini`
--

DROP TABLE IF EXISTS `coursfini`;
CREATE TABLE IF NOT EXISTS `coursfini` (
  `id` int NOT NULL AUTO_INCREMENT,
  `code_section` int DEFAULT NULL,
  `code_mention` int DEFAULT NULL,
  `code_promotion` varchar(50) DEFAULT NULL,
  `code_cours` varchar(50) DEFAULT NULL,
  `matricule_enseignant` varchar(50) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `datesave` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `descriptionfiche`
--

DROP TABLE IF EXISTS `descriptionfiche`;
CREATE TABLE IF NOT EXISTS `descriptionfiche` (
  `id` int NOT NULL AUTO_INCREMENT,
  `code_section` int DEFAULT NULL,
  `code_mention` int DEFAULT NULL,
  `code_promotion` varchar(50) DEFAULT NULL,
  `code_cours` varchar(10) DEFAULT NULL,
  `matricule_enseignant` varchar(50) DEFAULT NULL,
  `dt` date DEFAULT NULL,
  `objectif` varchar(255) DEFAULT NULL,
  `contenu` text,
  `methode` varchar(255) DEFAULT NULL,
  `ressource` varchar(255) DEFAULT NULL,
  `nature` varchar(255) DEFAULT NULL,
  `evaluation` varchar(255) DEFAULT NULL,
  `bibliographie` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `descriptionfiche`
--

INSERT INTO `descriptionfiche` (`id`, `code_section`, `code_mention`, `code_promotion`, `code_cours`, `matricule_enseignant`, `dt`, `objectif`, `contenu`, `methode`, `ressource`, `nature`, `evaluation`, `bibliographie`) VALUES
(1, 2, 2, '5', '2', 'E0001', '0000-00-00', '', '', '', '', '', '', '');

-- --------------------------------------------------------

--
-- Structure de la table `enseignant`
--

DROP TABLE IF EXISTS `enseignant`;
CREATE TABLE IF NOT EXISTS `enseignant` (
  `matriculeEnseignant` varchar(50) NOT NULL,
  `nom` varchar(50) NOT NULL,
  `postnom` varchar(50) NOT NULL,
  `prenom` varchar(50) NOT NULL,
  `genre` varchar(10) NOT NULL,
  `dtnaissance` date NOT NULL,
  `nationalite` varchar(50) NOT NULL,
  `etatCivil` varchar(50) NOT NULL,
  `adresse` varchar(255) NOT NULL,
  `adresseMail` varchar(255) NOT NULL,
  `telephone` varchar(50) NOT NULL,
  `grade` varchar(255) NOT NULL,
  `domainEnseignant` varchar(50) NOT NULL,
  `dtsave` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`matriculeEnseignant`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `enseignant`
--

INSERT INTO `enseignant` (`matriculeEnseignant`, `nom`, `postnom`, `prenom`, `genre`, `dtnaissance`, `nationalite`, `etatCivil`, `adresse`, `adresseMail`, `telephone`, `grade`, `domainEnseignant`, `dtsave`) VALUES
('E0001', 'Kakule', 'Somo', 'Samy', 'masculin', '2025-10-30', 'Congolaise', 'Marie', 'Ngule', 'samysomo@gmal.com', '0987654321', 'Ass', 'Informatique', '2025-11-13 13:34:29'),
('E0002', 'Kasereka', 'Menomavuya', 'Dieumerci', 'masculin', '1996-10-20', 'Congolaise', 'Celibataire', 'Kisingiri', 'meno@gmail.com', '0974868655', 'Licencie', 'Informatic', '2025-11-20 10:01:36');

-- --------------------------------------------------------

--
-- Structure de la table `entetefiche`
--

DROP TABLE IF EXISTS `entetefiche`;
CREATE TABLE IF NOT EXISTS `entetefiche` (
  `id` int NOT NULL AUTO_INCREMENT,
  `code_section` int DEFAULT NULL,
  `code_mention` int DEFAULT NULL,
  `code_promotion` varchar(50) DEFAULT NULL,
  `code_cours` varchar(50) DEFAULT NULL,
  `matricule_enseignant` varchar(50) DEFAULT NULL,
  `statut` varchar(20) DEFAULT 'en_attente',
  `datecreation` datetime DEFAULT CURRENT_TIMESTAMP,
  `date_approbation` datetime DEFAULT NULL,
  `date_envoi` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_matricule_enseignant` (`matricule_enseignant`),
  KEY `idx_code_cours` (`code_cours`),
  KEY `idx_codes` (`code_section`, `code_mention`, `code_promotion`),
  KEY `idx_statut` (`statut`),
  KEY `idx_datecreation` (`datecreation`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `entetefiche`
--

INSERT INTO `entetefiche` (`id`, `code_section`, `code_mention`, `code_promotion`, `code_cours`, `matricule_enseignant`) VALUES
(1, 2, 2, 'L1GI', 'IG1', 'E0001'),
(2, 2, 2, 'L1GI', 'IG1', 'E0001');

-- --------------------------------------------------------

--
-- Structure de la table `etudiant`
--

DROP TABLE IF EXISTS `etudiant`;
CREATE TABLE IF NOT EXISTS `etudiant` (
  `matriculeEtudiant` varchar(50) NOT NULL,
  `nom` varchar(50) NOT NULL,
  `postnom` varchar(50) NOT NULL,
  `prenom` varchar(50) NOT NULL,
  `genre` varchar(10) NOT NULL,
  `dtnaissance` date NOT NULL,
  `nationalite` varchar(50) NOT NULL,
  `etatCivil` varchar(50) NOT NULL,
  `adresse` varchar(255) NOT NULL,
  `adresseMail` varchar(255) NOT NULL,
  `telephone` varchar(50) NOT NULL,
  `dt_save` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`matriculeEtudiant`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `etudiant`
--

INSERT INTO `etudiant` (`matriculeEtudiant`, `nom`, `postnom`, `prenom`, `genre`, `dtnaissance`, `nationalite`, `etatCivil`, `adresse`, `adresseMail`, `telephone`, `dt_save`) VALUES
('A', 'k', 'l', 'l', 'masculin', '2000-08-09', 'u', 'u', 'u', 'uiy', '09', '2025-08-29 15:04:33'),
('A23', 'i', 'I', 'o', 'masculin', '2000-02-02', 'j', 'j', 'j', 'iou', '098', '2025-08-29 15:05:22'),
('AAA1', 'Kate', 'SiKwa', 'Chris', 'masculin', '2025-08-23', 'congo', 'Celi', 'Saba', 'chrimeaux@gmail.com', '098766', '2025-08-29 14:53:56'),
('AAA2', 'j', 'j', 'j', 'masculin', '2025-08-16', 'k', 'p', 'k', 'iioi', '0098', '2025-08-29 15:01:21'),
('Aaab', 'j', 'k', 'k', 'masculin', '2000-07-07', 'v', 'h', 'g', 'hjg', '098', '2025-08-30 07:53:11'),
('WER34', 'hj', 'jkk', 'kk', 'masculin', '1999-08-02', 'c', 'c', 's', 'rf', '0987', '2025-08-31 01:40:31');

-- --------------------------------------------------------

--
-- Structure de la table `evaluations`
--

DROP TABLE IF EXISTS `evaluations`;
CREATE TABLE IF NOT EXISTS `evaluations` (
  `id` int NOT NULL AUTO_INCREMENT,
  `matriculeEtudiant` varchar(50) DEFAULT NULL,
  `enseignant` varchar(255) DEFAULT NULL,
  `cours` varchar(255) DEFAULT NULL,
  `r1` int DEFAULT NULL,
  `r2` int DEFAULT NULL,
  `r3` int DEFAULT NULL,
  `r4` int DEFAULT NULL,
  `r5` int DEFAULT NULL,
  `r6` int DEFAULT NULL,
  `r7` int DEFAULT NULL,
  `r8` int DEFAULT NULL,
  `r9` int DEFAULT NULL,
  `r10` int DEFAULT NULL,
  `r11` int DEFAULT NULL,
  `r12` int DEFAULT NULL,
  `r13` int DEFAULT NULL,
  `r14` int DEFAULT NULL,
  `r15` int DEFAULT NULL,
  `r16` int DEFAULT NULL,
  `r17` int DEFAULT NULL,
  `r18` int DEFAULT NULL,
  `r19` int DEFAULT NULL,
  `r20` int DEFAULT NULL,
  `commentaire` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `honoraire`
--

DROP TABLE IF EXISTS `honoraire`;
CREATE TABLE IF NOT EXISTS `honoraire` (
  `id` int NOT NULL AUTO_INCREMENT,
  `code_section` int DEFAULT NULL,
  `code_mention` int DEFAULT NULL,
  `code_promotion` varchar(50) DEFAULT NULL,
  `code_cours` varchar(50) DEFAULT NULL,
  `matricule_enseignant` varchar(50) DEFAULT NULL,
  `montant` double DEFAULT NULL,
  `devise` varchar(4) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `datesve` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `horaire`
--

DROP TABLE IF EXISTS `horaire`;
CREATE TABLE IF NOT EXISTS `horaire` (
  `idhoraire` int NOT NULL AUTO_INCREMENT,
  `idcours` varchar(50) NOT NULL,
  `id` int NOT NULL,
  `datejour` datetime DEFAULT CURRENT_TIMESTAMP,
  `jourheure` varchar(50) DEFAULT NULL,
  `codemention` varchar(50) DEFAULT NULL,
  `codepromotion` varchar(50) DEFAULT NULL,
  `enseignant` varchar(50) DEFAULT NULL,
  `site` varchar(50) DEFAULT NULL,
  `periode` varchar(50) DEFAULT NULL,
  `observation` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`idhoraire`),
  KEY `idcours` (`idcours`,`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `horaire`
--

INSERT INTO `horaire` (`idhoraire`, `idcours`, `id`, `datejour`, `jourheure`, `codemention`, `codepromotion`, `enseignant`, `site`, `periode`, `observation`) VALUES
(1, 'UML', 0, '2024-09-27 00:00:00', 'VENDREDI-SAMEDI', 'IG', 'L2', 'ASS MUMBERE', 'LYCEE MWANDU', 'AM', ''),
(2, 'FIAG1', 0, '2024-09-27 00:00:00', 'VENDRE-SAMEDI\r\n8H:OO A 16H30', 'IG', 'L1', 'CT MAPENDO', 'LYCEE MWANDU', 'AM', 'outil ordinateur est necessaire'),
(3, 'IG1', 0, '0000-00-00 00:00:00', '', '2', 'L1GI', 'E0001', '', 'AM', ''),
(4, 'AL1', 0, '2025-11-12 00:00:00', 'Lundi - Samedi', '2', 'L1GI', 'E0001', 'Vutetse', 'PM', 'bien');

-- --------------------------------------------------------

--
-- Structure de la table `inscription`
--

DROP TABLE IF EXISTS `inscription`;
CREATE TABLE IF NOT EXISTS `inscription` (
  `code_inscription` int NOT NULL AUTO_INCREMENT,
  `matriculeEtudiant` varchar(50) NOT NULL,
  `codepromotion` varchar(50) NOT NULL,
  `code_mention` int NOT NULL,
  `code_section` int NOT NULL,
  `date_inscription` date NOT NULL,
  `description` varchar(50) NOT NULL,
  PRIMARY KEY (`code_inscription`),
  KEY `matriculeEtudiant` (`matriculeEtudiant`),
  KEY `codepromotion` (`codepromotion`),
  KEY `code_mention` (`code_mention`),
  KEY `code_section` (`code_section`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `isp`
--

DROP TABLE IF EXISTS `isp`;
CREATE TABLE IF NOT EXISTS `isp` (
  `code_isp` varchar(50) NOT NULL,
  `dt_creation` date NOT NULL,
  `sigle` varchar(50) NOT NULL,
  `nomComplet` varchar(255) NOT NULL,
  `numArreterminister` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `boitepostal` varchar(50) NOT NULL,
  PRIMARY KEY (`code_isp`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `isp`
--

INSERT INTO `isp` (`code_isp`, `dt_creation`, `sigle`, `nomComplet`, `numArreterminister`, `description`, `boitepostal`) VALUES
('Min-ESU/CAB-234', '2005-11-20', 'ISP-M-B', 'Institut Superieur pedagogic de Muhangi a Butembo', 'Min-ESU/CAB-234', 'Institut Superieur pedagogic de Muhangi a Butembo', 'B.P. 234');

-- --------------------------------------------------------

--
-- Structure de la table `mention`
--

DROP TABLE IF EXISTS `mention`;
CREATE TABLE IF NOT EXISTS `mention` (
  `code_mention` int NOT NULL AUTO_INCREMENT,
  `code_section` int NOT NULL,
  `sigle` varchar(50) NOT NULL,
  `nomComplet` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `dtcreation` date DEFAULT NULL,
  PRIMARY KEY (`code_mention`),
  KEY `code_section` (`code_section`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `mention`
--

INSERT INTO `mention` (`code_mention`, `code_section`, `sigle`, `nomComplet`, `description`, `dtcreation`) VALUES
(2, 2, 'GI', 'Gestion Informatic', 'Gestion Informatic', '2010-11-18');

-- --------------------------------------------------------

--
-- Structure de la table `participeraucours`
--

DROP TABLE IF EXISTS `participeraucours`;
CREATE TABLE IF NOT EXISTS `participeraucours` (
  `code` int NOT NULL AUTO_INCREMENT,
  `code_cours` varchar(50) NOT NULL,
  `codepromotion` varchar(50) NOT NULL,
  `matriculeEtudiant` varchar(50) NOT NULL,
  `matriculeEnseignant` varchar(50) NOT NULL,
  `presenceInPercent` double DEFAULT NULL,
  `dtsave` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`code`),
  KEY `matriculeEtudiant` (`matriculeEtudiant`),
  KEY `codepromotion` (`codepromotion`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `prestationcours`
--

DROP TABLE IF EXISTS `prestationcours`;
CREATE TABLE IF NOT EXISTS `prestationcours` (
  `id` int NOT NULL AUTO_INCREMENT,
  `codecours` varchar(10) DEFAULT NULL,
  `codeens` varchar(50) DEFAULT NULL,
  `codepromotion` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `promotion`
--

DROP TABLE IF EXISTS `promotion`;
CREATE TABLE IF NOT EXISTS `promotion` (
  `id` int NOT NULL AUTO_INCREMENT,
  `sigle_promotion` varchar(50) NOT NULL,
  `nomComplet` varchar(255) NOT NULL,
  `code_mention` int NOT NULL,
  `description` varchar(255) NOT NULL,
  `dtcreation` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `sigle_promotion` (`sigle_promotion`),
  KEY `code_mention` (`code_mention`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `promotion`
--

INSERT INTO `promotion` (`id`, `sigle_promotion`, `nomComplet`, `code_mention`, `description`, `dtcreation`) VALUES
(5, 'L1GI', 'Premiere annee de Licence en gesition informatic', 2, 'Premiere annee de Licence en gesition informatic', '2010-11-18');

-- --------------------------------------------------------

--
-- Structure de la table `section`
--

DROP TABLE IF EXISTS `section`;
CREATE TABLE IF NOT EXISTS `section` (
  `code_section` int NOT NULL AUTO_INCREMENT,
  `dt_creation` date NOT NULL,
  `sigle` varchar(50) NOT NULL,
  `nomComplet` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `code_isp` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`code_section`),
  KEY `code_isp` (`code_isp`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `section`
--

INSERT INTO `section` (`code_section`, `dt_creation`, `sigle`, `nomComplet`, `description`, `code_isp`) VALUES
(2, '2010-11-20', 'IG', 'Informatic de gestion', 'Informatic de gestion', 'Min-ESU/CAB-234');

-- --------------------------------------------------------

--
-- Structure de la table `utilisateur`
--

DROP TABLE IF EXISTS `utilisateur`;
CREATE TABLE IF NOT EXISTS `utilisateur` (
  `id` int NOT NULL AUTO_INCREMENT,
  `matricule` varchar(50) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` text NOT NULL,
  `role` varchar(100) DEFAULT NULL,
  `active` tinyint(1) DEFAULT '1',
  `last_login` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `access_level` int DEFAULT '1' COMMENT '1=normal, 2=elevé, 3=admin',
  PRIMARY KEY (`id`),
  KEY `matricule` (`matricule`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `utilisateur`
--

INSERT INTO `utilisateur` (`id`, `matricule`, `username`, `password`, `role`) VALUES
(2, 'AAA1', 'christien', '12345678', 'etudiant'),
(3, 'AAA1', 'Christien', '1234567890', 'Etudiant'),
(4, 'AAA1', 'Christien', '1234', 'Chefpromotion'),
(9, 'E0001', 'Somo', '12345', 'Enseignant'),
(10, 'E0002', 'Meno', '123456', 'Chefdesection');

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `inscription`
--
ALTER TABLE `inscription`
  ADD CONSTRAINT `inscription_ibfk_1` FOREIGN KEY (`matriculeEtudiant`) REFERENCES `etudiant` (`matriculeEtudiant`),
  ADD CONSTRAINT `inscription_ibfk_2` FOREIGN KEY (`codepromotion`) REFERENCES `promotion` (`sigle_promotion`),
  ADD CONSTRAINT `inscription_ibfk_3` FOREIGN KEY (`code_mention`) REFERENCES `mention` (`code_mention`),
  ADD CONSTRAINT `inscription_ibfk_4` FOREIGN KEY (`code_section`) REFERENCES `section` (`code_section`);

--
-- Contraintes pour la table `mention`
--
ALTER TABLE `mention`
  ADD CONSTRAINT `mention_ibfk_1` FOREIGN KEY (`code_section`) REFERENCES `section` (`code_section`);

--
-- Contraintes pour la table `participeraucours`
--
ALTER TABLE `participeraucours`
  ADD CONSTRAINT `participeraucours_ibfk_1` FOREIGN KEY (`matriculeEtudiant`) REFERENCES `etudiant` (`matriculeEtudiant`),
  ADD CONSTRAINT `participeraucours_ibfk_2` FOREIGN KEY (`codepromotion`) REFERENCES `promotion` (`sigle_promotion`);

--
-- Contraintes pour la table `promotion`
--
ALTER TABLE `promotion`
  ADD CONSTRAINT `promotion_ibfk_1` FOREIGN KEY (`code_mention`) REFERENCES `mention` (`code_mention`);

--
-- Contraintes pour la table `section`
--
ALTER TABLE `section`
  ADD CONSTRAINT `section_ibfk_1` FOREIGN KEY (`code_isp`) REFERENCES `isp` (`code_isp`);

--
-- Contraintes pour la table `chargehoraire`
--
ALTER TABLE `chargehoraire`
  ADD CONSTRAINT `chargehoraire_ibfk_1` FOREIGN KEY (`matricule`) REFERENCES `enseignant` (`matriculeEnseignant`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `chargehoraire_ibfk_2` FOREIGN KEY (`codecours`) REFERENCES `cours` (`code_cours`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `chargehoraire_ibfk_3` FOREIGN KEY (`codepromotion`) REFERENCES `promotion` (`sigle_promotion`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `entetefiche`
--
ALTER TABLE `entetefiche`
  ADD CONSTRAINT `entetefiche_ibfk_1` FOREIGN KEY (`matricule_enseignant`) REFERENCES `enseignant` (`matriculeEnseignant`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `entetefiche_ibfk_2` FOREIGN KEY (`code_cours`) REFERENCES `cours` (`code_cours`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `entetefiche_ibfk_3` FOREIGN KEY (`code_section`) REFERENCES `section` (`code_section`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `entetefiche_ibfk_4` FOREIGN KEY (`code_mention`) REFERENCES `mention` (`code_mention`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `entetefiche_ibfk_5` FOREIGN KEY (`code_promotion`) REFERENCES `promotion` (`sigle_promotion`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `contenufiche`
--
ALTER TABLE `contenufiche`
  ADD CONSTRAINT `contenufiche_ibfk_1` FOREIGN KEY (`identetefiche`) REFERENCES `entetefiche` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

--
-- Procédure pour vérifier les permissions
--
DELIMITER //
CREATE PROCEDURE IF NOT EXISTS CheckUserPermission(
    IN user_role VARCHAR(50),
    IN permission_name VARCHAR(100)
)
BEGIN
    SELECT COUNT(*) as has_permission
    FROM permissions
    WHERE role = user_role AND permission = permission_name;
END //
DELIMITER ;