-- Ajout de la colonne semestre à la table cours
ALTER TABLE cours ADD COLUMN semestre VARCHAR(50) DEFAULT NULL AFTER description;