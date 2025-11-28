-- Création de la table description_cours compatible avec MySQL/MariaDB anciens
CREATE TABLE IF NOT EXISTS `description_cours` (
                                                   `id` int NOT NULL AUTO_INCREMENT,
                                                   `code_cours` varchar(50) DEFAULT NULL,
    `enseignant` varchar(255) DEFAULT NULL,
    `objectif` text,
    `contenu` text,
    `methodes` text,
    `moyens` text,
    `evaluation` text,
    `references_biblio` text,
    `date_soumission` datetime DEFAULT NULL,
    `statut` varchar(50) DEFAULT NULL,
    PRIMARY KEY (`id`)
    ) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Insertion des données
INSERT INTO `description_cours`
(`code_cours`, `enseignant`, `objectif`, `contenu`, `methodes`, `moyens`, `evaluation`, `references_biblio`, `date_soumission`, `statut`)
VALUES
    ('IG1', 'E0001', 'Comprendre les bases de l\'informatique générale', '1. Introduction à l\'informatique\n2. Les composants d\'un ordinateur\n3. Systèmes d\'exploitation\n4. Bureautique\n5. Internet et réseaux', 'Cours magistraux, Travaux pratiques, Études de cas', 'Ordinateurs, Vidéo projecteur, Documents PDF', 'Devoirs surveillés (40%), Examen final (60%)', '1. Initiation à l\'informatique - Andrew S. Tanenbaum\n2. Bureautique avancée - Microsoft Office Specialist\n3. Réseaux informatiques - Cisco Press', '2025-11-20 09:30:00', 'Approuvé'),
('AL1', 'E0001', 'Apprendre les fondements de l\'algorithmique', '1. Notions d\'algorithmes\n2. Variables et types de données\n3. Structures conditionnelles\n4. Boucles\n5. Fonctions\n6. Tableaux et structures', 'Cours interactifs, Programmation en direct, Projets pratiques', 'PC avec compilateur, Tableau blanc, Supports numériques', 'TP notés (30%), Projet intermédiaire (30%), Examen final (40%)', '1. Algorithmique - Thomas H. Cormen\n2. Structures de données - Alfred V. Aho\n3. Initiation à l\'algorithmique - Guy Cousineau', '2025-11-22 14:15:00', 'Soumis'),
    ('FR1', 'E0002', 'Maîtriser le français écrit et oral', '1. Grammaire avancée\n2. Conjugaison\n3. Orthographe\n4. Expression écrite\n5. Communication orale\n6. Lecture et compréhension', 'Ateliers d\'écriture, Discussions en groupe, Présentations orales', 'Livres, Articles de presse, Audiovisuels, Tableau', 'Participation en classe (20%), Devoirs (30%), Exposé oral (20%), Examen final (30%)', '1. Le Bon Usage - Maurice Grevisse\n2. Grammaire française - Henriette Walter\n3. Expression écrite - Jean-Pierre Martin', '2025-11-25 11:45:00', 'Rejeté');
