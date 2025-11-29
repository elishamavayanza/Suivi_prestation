-- Script pour mettre à jour la table entetefiche avec les champs manquants
-- Ajout des colonnes statut, datecreation, date_approbation et date_envoi

ALTER TABLE `entetefiche` 
ADD COLUMN `statut` varchar(20) DEFAULT 'en_attente',
ADD COLUMN `datecreation` datetime DEFAULT CURRENT_TIMESTAMP,
ADD COLUMN `date_approbation` datetime DEFAULT NULL,
ADD COLUMN `date_envoi` datetime DEFAULT NULL;

-- Ajout des colonnes supplémentaires pour améliorer la fiche de prestation
ALTER TABLE entetefiche 
ADD COLUMN IF NOT EXISTS date_debut_prevue DATE DEFAULT NULL,
ADD COLUMN IF NOT EXISTS date_fin_prevue DATE DEFAULT NULL,
ADD COLUMN IF NOT EXISTS objectifs TEXT DEFAULT NULL;

-- Mettre à jour les enregistrements existants avec la date de création
UPDATE `entetefiche` SET `datecreation` = NOW() WHERE `datecreation` IS NULL;