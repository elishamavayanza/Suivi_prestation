-- Script pour mettre à jour la table entetefiche avec les champs manquants
-- Ajout des colonnes statut, datecreation, date_approbation et date_envoi

ALTER TABLE `entetefiche` 
ADD COLUMN `statut` varchar(20) DEFAULT 'en_attente',
ADD COLUMN `datecreation` datetime DEFAULT CURRENT_TIMESTAMP,
ADD COLUMN `date_approbation` datetime DEFAULT NULL,
ADD COLUMN `date_envoi` datetime DEFAULT NULL;

-- Mettre à jour les enregistrements existants avec la date de création
UPDATE `entetefiche` SET `datecreation` = NOW() WHERE `datecreation` IS NULL;