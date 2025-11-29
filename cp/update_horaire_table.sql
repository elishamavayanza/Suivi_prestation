-- Script pour corriger les relations dans la table horaire

-- Vérifier les contraintes existantes
-- ALTER TABLE `horaire` 
-- DROP FOREIGN KEY IF EXISTS `horaire_ibfk_1`;

-- Ajouter les bonnes contraintes
ALTER TABLE `horaire`
ADD CONSTRAINT `horaire_cours_fk` FOREIGN KEY (`idcours`) REFERENCES `cours` (`code_cours`) ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT `horaire_promotion_fk` FOREIGN KEY (`codepromotion`) REFERENCES `promotion` (`sigle_promotion`) ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT `horaire_mention_fk` FOREIGN KEY (`codemention`) REFERENCES `mention` (`code_mention`) ON DELETE CASCADE ON UPDATE CASCADE;

-- Si l'enseignant est stocké différemment, adapter selon le besoin
-- ALTER TABLE `horaire`
-- ADD CONSTRAINT `horaire_enseignant_fk` FOREIGN KEY (`enseignant`) REFERENCES `enseignant` (`matriculeEnseignant`) ON DELETE CASCADE ON UPDATE CASCADE;