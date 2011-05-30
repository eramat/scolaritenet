-- IMPORTANT : 
-- avant chaque nom de table il faut inscrire PREFIX afin de suporter la gestion des préfices des tables. Le mot PREFIX est réservé
-- et ne doit pas etre utilisé autrement.
-- phpMyAdmin SQL Dump
-- version 2.6.1
-- http://www.phpmyadmin.net
-- 
-- Serveur: localhost
-- Généré le : Mardi 02 Mai 2006 à 09:19
-- Version du serveur: 4.1.9
-- Version de PHP: 4.3.10
-- 
-- Base de données: `scolaritenet`
-- 

-- --------------------------------------------------------

-- 
-- Structure de la table `PREFIXcalendrier`
-- 

DROP TABLE IF EXISTS `PREFIXcalendrier`;
CREATE TABLE `PREFIXcalendrier` (
  `id` int(10) NOT NULL auto_increment,
  `libelle` varchar(50) NOT NULL default '',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=3 ;

-- 
-- Contenu de la table `PREFIXcalendrier`
-- 

INSERT INTO `PREFIXcalendrier` VALUES (1, 'Standard');

-- --------------------------------------------------------

-- 
-- Structure de la table `PREFIXcalendrier_ferie`
-- 

DROP TABLE IF EXISTS `PREFIXcalendrier_ferie`;
CREATE TABLE `PREFIXcalendrier_ferie` (
  `id_calendrier` int(10) NOT NULL default '0',
  `id_periode` int(10) NOT NULL default '0'
) TYPE=MyISAM;

-- 
-- Contenu de la table `PREFIXcalendrier_ferie`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `PREFIXcalendrier_travail`
-- 

DROP TABLE IF EXISTS `PREFIXcalendrier_travail`;
CREATE TABLE `PREFIXcalendrier_travail` (
  `id_calendrier` int(10) NOT NULL default '0',
  `id_periode` int(10) NOT NULL default '0'
) TYPE=MyISAM;

-- 
-- Contenu de la table `PREFIXcalendrier_travail`
-- 

INSERT INTO `PREFIXcalendrier_travail` VALUES (1, 1);
INSERT INTO `PREFIXcalendrier_travail` VALUES (1, 2);
INSERT INTO `PREFIXcalendrier_travail` VALUES (0, 5);
INSERT INTO `PREFIXcalendrier_travail` VALUES (0, 6);
INSERT INTO `PREFIXcalendrier_travail` VALUES (0, 7);

-- --------------------------------------------------------

-- 
-- Structure de la table `PREFIXdepartement`
-- 

DROP TABLE IF EXISTS `PREFIXdepartement`;
CREATE TABLE `PREFIXdepartement` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `libelle` varchar(50) NOT NULL default '',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=12 ;

-- 
-- Contenu de la table `PREFIXdepartement`
-- 

INSERT INTO `PREFIXdepartement` VALUES (1, 'Informatique');
INSERT INTO `PREFIXdepartement` VALUES (2, 'Langues');
INSERT INTO `PREFIXdepartement` VALUES (3, 'Math&eacute;matiques');
INSERT INTO `PREFIXdepartement` VALUES (4, 'Physique');
INSERT INTO `PREFIXdepartement` VALUES (5, 'Droit');
INSERT INTO `PREFIXdepartement` VALUES (6, 'Eco-gestion');
INSERT INTO `PREFIXdepartement` VALUES (7, 'EEA');
INSERT INTO `PREFIXdepartement` VALUES (8, 'Biologie');
INSERT INTO `PREFIXdepartement` VALUES (9, 'STAPS');
INSERT INTO `PREFIXdepartement` VALUES (10, 'G&eacute;ographie');
INSERT INTO `PREFIXdepartement` VALUES (11, 'Histoire');

-- --------------------------------------------------------

-- 
-- Structure de la table `PREFIXdepartement_directeur`
-- 

DROP TABLE IF EXISTS `PREFIXdepartement_directeur`;
CREATE TABLE `PREFIXdepartement_directeur` (
  `id_departement` int(10) NOT NULL default '0',
  `id_enseignant` int(10) NOT NULL default '0'
) TYPE=MyISAM;

-- 
-- Contenu de la table `PREFIXdepartement_directeur`
-- 

INSERT INTO `PREFIXdepartement_directeur` VALUES (1, 2);

-- --------------------------------------------------------


-- 
-- Structure de la table `PREFIXdiplome`
-- 

DROP TABLE IF EXISTS `PREFIXdiplome`;
CREATE TABLE `PREFIXdiplome` (
  `id_diplome` int(10) unsigned NOT NULL auto_increment,
  `annee` tinyint(3) unsigned NOT NULL default '0',
  `sigle` varchar(10) NOT NULL default '',
  `sigle_complet` varchar(255) NOT NULL default '',
  `id_president_jury` int(10) unsigned NOT NULL default '0',
  `id_directeur_etudes` int(10) unsigned NOT NULL default '0',
  `id_niveau` int(10) unsigned NOT NULL default '0',
  `id_domaine` int(10) unsigned NOT NULL default '0',
  `id_mention` int(10) unsigned NOT NULL default '0',
  `id_specialite` int(10) unsigned NOT NULL default '0',
  `intitule_parcours` varchar(32) NOT NULL default '',
  `id_pole` int(10) unsigned NOT NULL default '0',
  `id_calendrier` int(10) NOT NULL default '0',
  `id_departement` int(10) NOT NULL default '0',
  `id_projet` int(10) NULL,
  PRIMARY KEY  (`id_diplome`)
) TYPE=MyISAM AUTO_INCREMENT=11 ;



-- 
-- Contenu de la table `PREFIXdiplome`
-- 

INSERT INTO `PREFIXdiplome` VALUES (1, 2, 'GMI2', 'L2 ST Informatique \r\nGMI', 2, 35, 1, 1, 1, 0, 'GMI', 1, 1, 1, NULL);
INSERT INTO `PREFIXdiplome` VALUES (2, 3, 'GMI3', 'L3 ST Informatique \r\nGMI', 2, 30, 1, 1, 1, 0, 'GMI', 1, 1, 1, NULL);
INSERT INTO `PREFIXdiplome` VALUES (3, 1, 'ISIDIS1', 'M1 ST MSPI ISIDIS', 2, 2, 2, 1, 3, 2, '', 1, 1, 1, NULL);
INSERT INTO `PREFIXdiplome` VALUES (8, 1, 'BIO1', 'L1 ST Biologie', 21, 21, 1, 1, 4, 0, '', 1, 0, 0, NULL);
INSERT INTO `PREFIXdiplome` VALUES (4, 3, 'L3 INFO', 'L3 ST Informatique \r\net\r\nSciences', 2, 5, 1, 1, 1, 0, 'Informatique et Sciences', 1, 1, 1, NULL);
INSERT INTO `PREFIXdiplome` VALUES (9, 1, 'L1 MATH', 'L1 ST \r\nMath?matiques', 24, 24, 1, 1, 2, 0, '', 3, 0, 3, NULL);
INSERT INTO `PREFIXdiplome` VALUES (5, 1, 'L1 INFO', 'L1 ST \r\nInformatique', 2, 13, 1, 1, 1, 0, '', 1, 1, 1, NULL);
INSERT INTO `PREFIXdiplome` VALUES (6, 1, 'L1 MATH', 'L1 ST \r\nMath?matiques', 23, 23, 1, 1, 2, 0, '', 1, 1, 3, NULL);
INSERT INTO `PREFIXdiplome` VALUES (7, 2, 'ISIDIS2', 'M2 ST MPSI ISIDIS', 31, 2, 2, 1, 3, 2, '', 1, 1, 1, NULL);
INSERT INTO `PREFIXdiplome` VALUES (10, 2, 'M2CCI', 'M2 ST MSPI CCI', 20, 20, 2, 1, 3, 4, '', 1, 2, 1, NULL);

-- --------------------------------------------------------

-- 
-- Structure de la table `PREFIXdomaine`
-- 

DROP TABLE IF EXISTS `PREFIXdomaine`;
CREATE TABLE `PREFIXdomaine` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `libelle` varchar(50) NOT NULL default '',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=6 ;

-- 
-- Contenu de la table `PREFIXdomaine`
-- 

INSERT INTO `PREFIXdomaine` VALUES (1, 'Sciences et Technologies');
INSERT INTO `PREFIXdomaine` VALUES (2, 'Droit');
INSERT INTO `PREFIXdomaine` VALUES (3, 'Lettres et langues');
INSERT INTO `PREFIXdomaine` VALUES (4, 'Sciences Humaines et Sociales');
INSERT INTO `PREFIXdomaine` VALUES (5, 'Sciences &eacute;co et gestion');

-- --------------------------------------------------------

-- 
-- Structure de la table `PREFIXenseignant`
-- 

DROP TABLE IF EXISTS `PREFIXenseignant`;
CREATE TABLE `PREFIXenseignant` (
  `id_enseignant` int(10) unsigned NOT NULL auto_increment,
  `nom` varchar(32) binary NOT NULL default '',
  `prenom` varchar(32) binary NOT NULL default '',
  `initiales` varchar(5) binary NOT NULL default '',
  `id_grade` int(10) unsigned NOT NULL default '0',
  `id_departement` int(10) unsigned NOT NULL default '0',
  `cnu` varchar(32) binary NOT NULL default '',
  `titulaire` char(1) binary NOT NULL default '',
  `pedr` char(1) binary NOT NULL default '',
  `id_pole` int(10) unsigned NOT NULL default '0',
  `adresse` varchar(80) binary NOT NULL default '',
  `code_postal` varchar(5) binary NOT NULL default '',
  `ville` varchar(32) binary NOT NULL default '',
  `email` varchar(80) binary NOT NULL default '',
  `telephone` varchar(20) binary NOT NULL default '',
  PRIMARY KEY  (`id_enseignant`)
) TYPE=MyISAM AUTO_INCREMENT=38 ;

-- 
-- Contenu de la table `PREFIXenseignant`
-- 

INSERT INTO `PREFIXenseignant` VALUES (2, 0x52414d4154, 0x45726963, 0x4552, 9, 1, 0x3237, 0x6f, 0x6f, 1, 0x696369, 0x3632313030, 0x43616c616973, 0x6572616d6174406c696c2e756e69762d6c6974746f72616c2e6672, 0x30313233343536373839);
INSERT INTO `PREFIXenseignant` VALUES (24, 0x4c45424c4f4e44, 0x4d696368656c, 0x4d4c, 4, 1, 0x3237, 0x6f, 0x6e, 1, 0x696369, 0x3632313030, 0x63616c616973, 0x6c65626c6f6e64406c696c2e756e69762d6c6974746f72616c2e6672, 0x30333231343634363436);
INSERT INTO `PREFIXenseignant` VALUES (25, 0x424153534f4e, 0x48656e7269, 0x4842, 8, 1, 0x3237, 0x6f, 0x6e, 1, 0x78, 0x78, 0x78, 0x78, 0x78);
INSERT INTO `PREFIXenseignant` VALUES (26, 0x515545534e454c, 0x4761757468696572, 0x4751, 1, 1, 0x3237, 0x6f, 0x6e, 1, 0x78, 0x78, 0x78, 0x78, 0x78);
INSERT INTO `PREFIXenseignant` VALUES (27, 0x534f554c4945, 0x4a65616e2d6368726973746f706865, 0x4a4353, 4, 1, 0x3237, 0x6f, 0x6e, 1, 0x78, 0x78, 0x78, 0x78, 0x78);
INSERT INTO `PREFIXenseignant` VALUES (28, 0x5345474f4e44, 0x4d617263, 0x4d53, 1, 1, 0x3237, 0x6f, 0x6e, 1, 0x78, 0x78, 0x78, 0x78, 0x78);
INSERT INTO `PREFIXenseignant` VALUES (29, 0x44454c454d4552, 0x54686f6d6173, 0x5444, 11, 1, '', 0x6e, 0x6e, 1, 0x78, 0x78, 0x78, 0x78, 0x78);
INSERT INTO `PREFIXenseignant` VALUES (30, 0x424f55524755494e, 0x4772c383c692c382c2a9676f7279, 0x4742, 4, 1, 0x3237, 0x6f, 0x6e, 1, 0x78, 0x78, 0x78, 0x78, 0x78);
INSERT INTO `PREFIXenseignant` VALUES (31, 0x424f554e45464641, 0x4d6f75726164, 0x4d42, 4, 1, 0x3237, 0x6f, 0x6e, 1, 0x78, 0x78, 0x78, 0x78, 0x78);
INSERT INTO `PREFIXenseignant` VALUES (32, 0x434f4c4c4554, 0x506965727265, 0x5043, 4, 1, 0x3237, 0x6f, 0x6f, 1, 0x78, 0x78, 0x78, 0x78, 0x78);
INSERT INTO `PREFIXenseignant` VALUES (33, 0x524f42494c4c49415244, 0x44656e6973, 0x4452, 4, 1, 0x3237, 0x6f, 0x6f, 1, 0x78, 0x78, 0x78, 0x78, 0x78);
INSERT INTO `PREFIXenseignant` VALUES (34, 0x464f4e4c555054, 0x437972696c, 0x4346, 9, 1, 0x3237, 0x6f, 0x6f, 1, 0x78, 0x78, 0x78, 0x78, 0x78);
INSERT INTO `PREFIXenseignant` VALUES (35, 0x4d4152494f4e2d504f5459, 0x56697267696e6965, 0x564d50, 4, 1, 0x3237, 0x6f, 0x6e, 1, 0x78, 0x78, 0x78, 0x78, 0x78);
INSERT INTO `PREFIXenseignant` VALUES (36, 0x4455564956494552, 0x4461766964, 0x4444, 4, 1, 0x3237, 0x6f, 0x6e, 1, 0x78, 0x78, 0x78, 0x78, 0x78);
INSERT INTO `PREFIXenseignant` VALUES (37, 0x44455255454c4c45, 0x4c617572656e74, 0x4c44, 4, 1, 0x3237, 0x6f, 0x6e, 1, 0x78, 0x78, 0x78, 0x78, 0x78);

-- --------------------------------------------------------

-- 
-- Structure de la table `PREFIXetudiant`
-- 

DROP TABLE IF EXISTS `PREFIXetudiant`;
CREATE TABLE `PREFIXetudiant` (
  `id_etudiant` int(10) unsigned NOT NULL auto_increment,
  `ine` varchar(15) NOT NULL default '',
  `nom` varchar(32) NOT NULL default '',
  `prenom` varchar(32) NOT NULL default '',
  `adresse` varchar(80) NOT NULL default '',
  `code_postal` varchar(5) NOT NULL default '',
  `ville` varchar(32) NOT NULL default '',
  `email` varchar(80) NOT NULL default '',
  `tel` varchar(20) NOT NULL default '',
  `photo` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`id_etudiant`)
) TYPE=MyISAM AUTO_INCREMENT=16 ;

-- 
-- Contenu de la table `PREFIXetudiant`
-- 

INSERT INTO `PREFIXetudiant` VALUES (1, '123456', 'BETA', 'Alpha', 'ici', '555', 'Paris', 'mon@email.fr', '123456789', 0);
INSERT INTO `PREFIXetudiant` VALUES (2, '5678', 'TOTO', 'G?g', 'quelque part je ne sais plus ou', '123', 'Boulogne', 'em@il', '12345', 1);
INSERT INTO `PREFIXetudiant` VALUES (3, '789', 'LAGAFFE', 'Gaston', 'boum', '456', 'Arras', 'boum@email.com', '4561237', 0);
INSERT INTO `PREFIXetudiant` VALUES (14, '1223', 'TITI', 'Titi', 'dsflksdj', '65362', 'kjqsfhkl', 'klsjf', 'flksjd', 1);
INSERT INTO `PREFIXetudiant` VALUES (15, '123', 'ONIO', 'Tonio', 'aaa', '111', 'Calais', 'aa', '44', 1);

-- --------------------------------------------------------

-- 
-- Structure de la table `PREFIXetudiant_appartient_groupe`
-- 

DROP TABLE IF EXISTS `PREFIXetudiant_appartient_groupe`;
CREATE TABLE `PREFIXetudiant_appartient_groupe` (
  `id_etudiant` int(10) unsigned NOT NULL default '0',
  `id_groupe` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id_etudiant`,`id_groupe`)
) TYPE=MyISAM;

-- 
-- Contenu de la table `PREFIXetudiant_appartient_groupe`
-- 

INSERT INTO `PREFIXetudiant_appartient_groupe` VALUES (1, 3);
INSERT INTO `PREFIXetudiant_appartient_groupe` VALUES (3, 2);
INSERT INTO `PREFIXetudiant_appartient_groupe` VALUES (14, 3);
INSERT INTO `PREFIXetudiant_appartient_groupe` VALUES (14, 26);
INSERT INTO `PREFIXetudiant_appartient_groupe` VALUES (15, 26);

-- --------------------------------------------------------

-- 
-- Structure de la table `PREFIXetudiant_appartient_option`
-- 

DROP TABLE IF EXISTS `PREFIXetudiant_appartient_option`;
CREATE TABLE `PREFIXetudiant_appartient_option` (
  `id_etudiant` int(10) unsigned NOT NULL default '0',
  `id_option` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id_etudiant`,`id_option`)
) TYPE=MyISAM;

-- 
-- Contenu de la table `PREFIXetudiant_appartient_option`
-- 

INSERT INTO `PREFIXetudiant_appartient_option` VALUES (14, 13);
INSERT INTO `PREFIXetudiant_appartient_option` VALUES (15, 12);

-- --------------------------------------------------------

-- 
-- Structure de la table `PREFIXgrade`
-- 

DROP TABLE IF EXISTS `PREFIXgrade`;
CREATE TABLE `PREFIXgrade` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `libelle` varchar(50) NOT NULL default '',
  `nombre_heures` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=14 ;

-- 
-- Contenu de la table `PREFIXgrade`
-- 

INSERT INTO `PREFIXgrade` VALUES (1, 'ATER', 192);
INSERT INTO `PREFIXgrade` VALUES (2, 'Etudiant', 96);
INSERT INTO `PREFIXgrade` VALUES (3, 'IATOS', 20);
INSERT INTO `PREFIXgrade` VALUES (4, 'Ma&icirc;tre de conf&eacute;rence', 192);
INSERT INTO `PREFIXgrade` VALUES (5, 'Ma&icirc;tre de conf&eacute;rence \r\nhors\r\nclasse', 192);
INSERT INTO `PREFIXgrade` VALUES (6, 'Moniteur CIES', 64);
INSERT INTO `PREFIXgrade` VALUES (7, 'PRAG', 384);
INSERT INTO `PREFIXgrade` VALUES (8, 'Professeur 1&egrave;re classe', 192);
INSERT INTO `PREFIXgrade` VALUES (9, 'Professeur 2&egrave;me classe', 192);
INSERT INTO `PREFIXgrade` VALUES (10, 'Professeur hors classe', 192);
INSERT INTO `PREFIXgrade` VALUES (11, 'Vacataire', 160);
INSERT INTO `PREFIXgrade` VALUES (12, '1/2 ATER', 96);
INSERT INTO `PREFIXgrade` VALUES (13, 'PRCE', 384);

-- --------------------------------------------------------

-- 
-- Structure de la table `PREFIXgroupe`
-- 

DROP TABLE IF EXISTS `PREFIXgroupe`;
CREATE TABLE `PREFIXgroupe` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `nom` varchar(50) NOT NULL default '',
  `id_diplome` int(10) unsigned default '0',
  `id_option` int(10) unsigned default '0',
  PRIMARY KEY  (`id`),
  KEY `id_diplome` (`id_diplome`),
  KEY `id_groupe` (`id_option`)
) TYPE=MyISAM AUTO_INCREMENT=29 ;

-- 
-- Contenu de la table `PREFIXgroupe`
-- 

INSERT INTO `PREFIXgroupe` VALUES (21, 'a', 5, 0);
INSERT INTO `PREFIXgroupe` VALUES (3, 'b', 2, NULL);
INSERT INTO `PREFIXgroupe` VALUES (2, 'a', 2, NULL);
INSERT INTO `PREFIXgroupe` VALUES (15, 'a', 0, 1);
INSERT INTO `PREFIXgroupe` VALUES (16, 'b', 0, 1);
INSERT INTO `PREFIXgroupe` VALUES (19, 'a', 0, 8);
INSERT INTO `PREFIXgroupe` VALUES (20, 'b', 0, 8);
INSERT INTO `PREFIXgroupe` VALUES (22, 'b', 5, 0);
INSERT INTO `PREFIXgroupe` VALUES (23, 'a', 1, 0);
INSERT INTO `PREFIXgroupe` VALUES (24, 'b', 1, 0);
INSERT INTO `PREFIXgroupe` VALUES (25, 'a', 3, 0);
INSERT INTO `PREFIXgroupe` VALUES (26, 'b', 3, 0);
INSERT INTO `PREFIXgroupe` VALUES (27, 'a', 7, 0);
INSERT INTO `PREFIXgroupe` VALUES (28, 'b', 7, 0);

-- --------------------------------------------------------

-- 
-- Structure de la table `PREFIXgroupe_type`
-- 

DROP TABLE IF EXISTS `PREFIXgroupe_type`;
CREATE TABLE `PREFIXgroupe_type` (
  `id_groupe` int(10) unsigned NOT NULL default '0',
  `id_type` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id_groupe`,`id_type`)
) TYPE=MyISAM;

-- 
-- Contenu de la table `PREFIXgroupe_type`
-- 

INSERT INTO `PREFIXgroupe_type` VALUES (2, 2);
INSERT INTO `PREFIXgroupe_type` VALUES (3, 2);
INSERT INTO `PREFIXgroupe_type` VALUES (15, 2);
INSERT INTO `PREFIXgroupe_type` VALUES (16, 2);
INSERT INTO `PREFIXgroupe_type` VALUES (19, 2);
INSERT INTO `PREFIXgroupe_type` VALUES (20, 2);
INSERT INTO `PREFIXgroupe_type` VALUES (21, 2);
INSERT INTO `PREFIXgroupe_type` VALUES (21, 3);
INSERT INTO `PREFIXgroupe_type` VALUES (22, 2);
INSERT INTO `PREFIXgroupe_type` VALUES (22, 3);
INSERT INTO `PREFIXgroupe_type` VALUES (23, 3);
INSERT INTO `PREFIXgroupe_type` VALUES (24, 3);
INSERT INTO `PREFIXgroupe_type` VALUES (25, 2);
INSERT INTO `PREFIXgroupe_type` VALUES (25, 3);
INSERT INTO `PREFIXgroupe_type` VALUES (26, 2);
INSERT INTO `PREFIXgroupe_type` VALUES (26, 3);
INSERT INTO `PREFIXgroupe_type` VALUES (27, 3);
INSERT INTO `PREFIXgroupe_type` VALUES (28, 3);

-- --------------------------------------------------------

-- 
-- Structure de la table `PREFIXgroupe_virtuel`
-- 

DROP TABLE IF EXISTS `PREFIXgroupe_virtuel`;
CREATE TABLE `PREFIXgroupe_virtuel` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `nom` varchar(50) NOT NULL default '',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=8 ;

-- 
-- Contenu de la table `PREFIXgroupe_virtuel`
-- 

INSERT INTO `PREFIXgroupe_virtuel` VALUES (1, 'Latex1');
INSERT INTO `PREFIXgroupe_virtuel` VALUES (2, 'Latex2');
INSERT INTO `PREFIXgroupe_virtuel` VALUES (3, 'Latex3');
INSERT INTO `PREFIXgroupe_virtuel` VALUES (4, 'Latex4');
INSERT INTO `PREFIXgroupe_virtuel` VALUES (5, 'Latex5');
INSERT INTO `PREFIXgroupe_virtuel` VALUES (6, 'Essai');
INSERT INTO `PREFIXgroupe_virtuel` VALUES (7, 'Essai');

-- --------------------------------------------------------

-- 
-- Structure de la table `PREFIXgroupe_virtuel_compose_diplome`
-- 

DROP TABLE IF EXISTS `PREFIXgroupe_virtuel_compose_diplome`;
CREATE TABLE `PREFIXgroupe_virtuel_compose_diplome` (
  `id_groupe_virtuel` int(10) unsigned NOT NULL default '0',
  `id_diplome` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id_groupe_virtuel`,`id_diplome`)
) TYPE=MyISAM;

-- 
-- Contenu de la table `PREFIXgroupe_virtuel_compose_diplome`
-- 

INSERT INTO `PREFIXgroupe_virtuel_compose_diplome` VALUES (2, 2);
INSERT INTO `PREFIXgroupe_virtuel_compose_diplome` VALUES (7, 4);

-- --------------------------------------------------------

-- 
-- Structure de la table `PREFIXgroupe_virtuel_compose_etudiant`
-- 

DROP TABLE IF EXISTS `PREFIXgroupe_virtuel_compose_etudiant`;
CREATE TABLE `PREFIXgroupe_virtuel_compose_etudiant` (
  `id_groupe_virtuel` int(10) unsigned NOT NULL default '0',
  `id_etudiant` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id_groupe_virtuel`,`id_etudiant`)
) TYPE=MyISAM;

-- 
-- Contenu de la table `PREFIXgroupe_virtuel_compose_etudiant`
-- 

INSERT INTO `PREFIXgroupe_virtuel_compose_etudiant` VALUES (5, 1);
INSERT INTO `PREFIXgroupe_virtuel_compose_etudiant` VALUES (7, 1);

-- --------------------------------------------------------

-- 
-- Structure de la table `PREFIXgroupe_virtuel_compose_groupe`
-- 

DROP TABLE IF EXISTS `PREFIXgroupe_virtuel_compose_groupe`;
CREATE TABLE `PREFIXgroupe_virtuel_compose_groupe` (
  `id_groupe_virtuel` int(10) unsigned NOT NULL default '0',
  `id_groupe` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id_groupe_virtuel`,`id_groupe`)
) TYPE=MyISAM;

-- 
-- Contenu de la table `PREFIXgroupe_virtuel_compose_groupe`
-- 

INSERT INTO `PREFIXgroupe_virtuel_compose_groupe` VALUES (1, 2);
INSERT INTO `PREFIXgroupe_virtuel_compose_groupe` VALUES (7, 19);

-- --------------------------------------------------------

-- 
-- Structure de la table `PREFIXgroupe_virtuel_compose_option`
-- 

DROP TABLE IF EXISTS `PREFIXgroupe_virtuel_compose_option`;
CREATE TABLE `PREFIXgroupe_virtuel_compose_option` (
  `id_groupe_virtuel` int(10) unsigned NOT NULL default '0',
  `id_option` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id_groupe_virtuel`,`id_option`)
) TYPE=MyISAM;

-- 
-- Contenu de la table `PREFIXgroupe_virtuel_compose_option`
-- 

INSERT INTO `PREFIXgroupe_virtuel_compose_option` VALUES (2, 8);

-- --------------------------------------------------------

-- 
-- Structure de la table `PREFIXinscrit_diplome`
-- 

DROP TABLE IF EXISTS `PREFIXinscrit_diplome`;
CREATE TABLE `PREFIXinscrit_diplome` (
  `id_diplome` int(10) unsigned NOT NULL default '0',
  `id_etudiant` int(10) unsigned NOT NULL default '0',
  `principal` tinyint(2) NOT NULL default '0',
  KEY `id_diplome` (`id_diplome`),
  KEY `id_etudiant` (`id_etudiant`)
) TYPE=MyISAM;

-- 
-- Contenu de la table `PREFIXinscrit_diplome`
-- 

INSERT INTO `PREFIXinscrit_diplome` VALUES (2, 1, 1);
INSERT INTO `PREFIXinscrit_diplome` VALUES (2, 3, 1);
INSERT INTO `PREFIXinscrit_diplome` VALUES (10, 2, 0);
INSERT INTO `PREFIXinscrit_diplome` VALUES (3, 14, 1);
INSERT INTO `PREFIXinscrit_diplome` VALUES (3, 15, 1);
INSERT INTO `PREFIXinscrit_diplome` VALUES (3, 2, 1);

-- --------------------------------------------------------

-- 
-- Structure de la table `PREFIXmention`
-- 

DROP TABLE IF EXISTS `PREFIXmention`;
CREATE TABLE `PREFIXmention` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `libelle` varchar(50) NOT NULL default '',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=23 ;

-- 
-- Contenu de la table `PREFIXmention`
-- 

INSERT INTO `PREFIXmention` VALUES (1, 'Informatique');
INSERT INTO `PREFIXmention` VALUES (2, 'Math?matiques');
INSERT INTO `PREFIXmention` VALUES (3, 'MSPI');
INSERT INTO `PREFIXmention` VALUES (4, 'Biologie');
INSERT INTO `PREFIXmention` VALUES (5, 'Chimie - Physique');
INSERT INTO `PREFIXmention` VALUES (6, 'EEA');
INSERT INTO `PREFIXmention` VALUES (7, 'GSI - CMA');
INSERT INTO `PREFIXmention` VALUES (8, 'STAPS');
INSERT INTO `PREFIXmention` VALUES (9, 'Economie appliqu');
INSERT INTO `PREFIXmention` VALUES (10, 'Gestion');
INSERT INTO `PREFIXmention` VALUES (11, 'Sciences ?conomiques et sociales\r\nappliqu?es');
INSERT INTO `PREFIXmention` VALUES (13, 'Administration publique');
INSERT INTO `PREFIXmention` VALUES (14, 'Droit');
INSERT INTO `PREFIXmention` VALUES (15, 'LCE - Allemand');
INSERT INTO `PREFIXmention` VALUES (16, 'LCE - Espagnol');
INSERT INTO `PREFIXmention` VALUES (17, 'LCE - Anglais');
INSERT INTO `PREFIXmention` VALUES (18, 'LEA');
INSERT INTO `PREFIXmention` VALUES (19, 'Lettres modernes');
INSERT INTO `PREFIXmention` VALUES (20, 'Culture et m?dias');
INSERT INTO `PREFIXmention` VALUES (21, 'G?ographie');
INSERT INTO `PREFIXmention` VALUES (22, 'Histoire');

-- --------------------------------------------------------

-- 
-- Structure de la table `PREFIXmenu_cat`
-- 

DROP TABLE IF EXISTS `PREFIXmenu_cat`;
CREATE TABLE `PREFIXmenu_cat` (
  `id` int(2) NOT NULL auto_increment,
  `libelle` varchar(128) NOT NULL default '',
  `id_type_user` int(3) NOT NULL default '0',
  `ordre` int(2) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=41 ;

-- 
-- Contenu de la table `PREFIXmenu_cat`
-- 

INSERT INTO `PREFIXmenu_cat` VALUES (1, 'G&eacute;n&eacute;ral', 1, 1);
INSERT INTO `PREFIXmenu_cat` VALUES (2, 'Promotion', 1, 2);
INSERT INTO `PREFIXmenu_cat` VALUES (3, 'Emploi du temps', 2, 2);
INSERT INTO `PREFIXmenu_cat` VALUES (4, 'Module', 1, 3);
INSERT INTO `PREFIXmenu_cat` VALUES (5, 'G&eacute;n&eacute;ral', 2, 1);
INSERT INTO `PREFIXmenu_cat` VALUES (16, 'Secr&eacute;taire', 1, 4);
INSERT INTO `PREFIXmenu_cat` VALUES (17, 'Superviseur', 1, 5);
INSERT INTO `PREFIXmenu_cat` VALUES (18, 'Bilans', 1, 6);
INSERT INTO `PREFIXmenu_cat` VALUES (19, 'Promotion', 2, 3);
INSERT INTO `PREFIXmenu_cat` VALUES (20, 'Bilans', 2, 4);
INSERT INTO `PREFIXmenu_cat` VALUES (21, 'G&eacute;n&eacute;ral', 5, 1);
INSERT INTO `PREFIXmenu_cat` VALUES (22, 'Promotion', 5, 2);
INSERT INTO `PREFIXmenu_cat` VALUES (23, 'G&eacute;n&eacute;ral', 3, 1);
INSERT INTO `PREFIXmenu_cat` VALUES (24, 'Cursus', 3, 2);
INSERT INTO `PREFIXmenu_cat` VALUES (25, 'Emploi du temps', 3, 3);
INSERT INTO `PREFIXmenu_cat` VALUES (26, 'G&eacute;n&eacute;ral', 4, 1);
INSERT INTO `PREFIXmenu_cat` VALUES (27, 'Promotion', 4, 2);
INSERT INTO `PREFIXmenu_cat` VALUES (28, 'Emploi du temps', 4, 3);
INSERT INTO `PREFIXmenu_cat` VALUES (29, 'dfsq', 0, 0);
INSERT INTO `PREFIXmenu_cat` VALUES (30, 'Administration', 1, 7);
INSERT INTO `PREFIXmenu_cat` VALUES (31, 'G&eacute;n&eacute;ral', 6, 1);
INSERT INTO `PREFIXmenu_cat` VALUES (32, 'Dipl&ocirc;me', 6, 2);
INSERT INTO `PREFIXmenu_cat` VALUES (33, 'Module', 6, 3);
INSERT INTO `PREFIXmenu_cat` VALUES (34, 'Bilans', 6, 4);
INSERT INTO `PREFIXmenu_cat` VALUES (35, 'G&eacute;n&eacute;ral', 7, 1);
INSERT INTO `PREFIXmenu_cat` VALUES (36, 'Emploi du temps', 7, 2);
INSERT INTO `PREFIXmenu_cat` VALUES (37, 'Promotion', 7, 3);
INSERT INTO `PREFIXmenu_cat` VALUES (38, 'G&eacute;n&eacute;ral', 8, 1);
INSERT INTO `PREFIXmenu_cat` VALUES (39, 'Enseignant', 8, 2);
INSERT INTO `PREFIXmenu_cat` VALUES (40, 'Bilans', 8, 3);

-- --------------------------------------------------------

-- 
-- Structure de la table `PREFIXmenu_data`
-- 

DROP TABLE IF EXISTS `PREFIXmenu_data`;
CREATE TABLE `PREFIXmenu_data` (
  `id` int(2) NOT NULL auto_increment,
  `id_categorie` int(2) NOT NULL default '0',
  `id_type_user` tinyint(3) NOT NULL default '0',
  `libelle` varchar(128) NOT NULL default '',
  `param` varchar(128) NOT NULL default '',
  `id_lien` smallint(9) unsigned NOT NULL default '0',
  `ordre` int(2) NOT NULL default '0',
  PRIMARY KEY  (`id`,`id_categorie`,`id_type_user`)
) TYPE=MyISAM AUTO_INCREMENT=103 ;

-- 
-- Contenu de la table `PREFIXmenu_data`
-- 

INSERT INTO `PREFIXmenu_data` VALUES (23, 1, 1, 'D&eacute;connexion', 'login', 36, 6);
INSERT INTO `PREFIXmenu_data` VALUES (2, 2, 1, 'Dipl&ocirc;me', 'diplome', 7, 1);
INSERT INTO `PREFIXmenu_data` VALUES (3, 1, 1, 'Libell&eacute;s', 'libelles', 1, 1);
INSERT INTO `PREFIXmenu_data` VALUES (22, 1, 1, 'Changer son mot de passe', 'motdepasse', 5, 5);
INSERT INTO `PREFIXmenu_data` VALUES (6, 2, 1, 'Option', 'option', 8, 2);
INSERT INTO `PREFIXmenu_data` VALUES (8, 1, 1, 'Calendrier', 'calendrier', 2, 2);
INSERT INTO `PREFIXmenu_data` VALUES (20, 1, 1, 'Enseignant', 'enseignant', 3, 3);
INSERT INTO `PREFIXmenu_data` VALUES (14, 4, 1, 'Module', 'module', 15, 1);
INSERT INTO `PREFIXmenu_data` VALUES (17, 2, 1, 'Groupe', 'groupe', 9, 3);
INSERT INTO `PREFIXmenu_data` VALUES (21, 1, 1, 'Gestion des utilisateurs', 'userlist', 4, 4);
INSERT INTO `PREFIXmenu_data` VALUES (24, 2, 1, 'Constitution des groupes', 'constitution', 10, 4);
INSERT INTO `PREFIXmenu_data` VALUES (25, 2, 1, 'Groupes virtuels', 'groupe_virtuel', 14, 5);
INSERT INTO `PREFIXmenu_data` VALUES (26, 2, 1, '&Eacute;tudiant', 'etudiant', 11, 6);
INSERT INTO `PREFIXmenu_data` VALUES (27, 4, 1, 'Volume horaire des modules', 'diviser', 16, 2);
INSERT INTO `PREFIXmenu_data` VALUES (28, 4, 1, 'R&eacute;partition des modules', 'assurer', 17, 3);
INSERT INTO `PREFIXmenu_data` VALUES (29, 16, 1, 'Secr&eacute;taire', 'secretaire', 25, 1);
INSERT INTO `PREFIXmenu_data` VALUES (30, 16, 1, 'Dipl&ocirc;mes g&eacute;r&eacute;s', 'secretaire_gere', 26, 2);
INSERT INTO `PREFIXmenu_data` VALUES (31, 17, 1, 'Superviseur', 'superviseur', 27, 1);
INSERT INTO `PREFIXmenu_data` VALUES (32, 18, 1, 'Charges', 'charges', 28, 1);
INSERT INTO `PREFIXmenu_data` VALUES (33, 5, 2, 'Changer son mot de passe', 'motdepasse', 5, 1);
INSERT INTO `PREFIXmenu_data` VALUES (34, 5, 2, 'D&eacute;connexion', 'login', 36, 2);
INSERT INTO `PREFIXmenu_data` VALUES (35, 3, 2, 'Mes modules', 'mes_modules', 22, 1);
INSERT INTO `PREFIXmenu_data` VALUES (36, 3, 2, 'Mon emplois du temps', 'PREFIXdiplome', 18, 2);
INSERT INTO `PREFIXmenu_data` VALUES (37, 19, 2, 'Trombinoscope', 'trombinoscope', 12, 1);
INSERT INTO `PREFIXmenu_data` VALUES (38, 20, 2, 'Suivi', 'suivi', 31, 1);
INSERT INTO `PREFIXmenu_data` VALUES (39, 20, 2, 'Mon service', 'mon_service', 29, 2);
INSERT INTO `PREFIXmenu_data` VALUES (40, 21, 5, 'Changer son mot de passe', 'motdepasse', 5, 1);
INSERT INTO `PREFIXmenu_data` VALUES (41, 21, 5, 'D&eacute;connexion', 'login', 36, 2);
INSERT INTO `PREFIXmenu_data` VALUES (42, 22, 5, 'Liste des modules', 'liste_module', 23, 1);
INSERT INTO `PREFIXmenu_data` VALUES (43, 22, 5, 'Trombinoscope', 'trombinoscope', 12, 2);
INSERT INTO `PREFIXmenu_data` VALUES (44, 23, 3, 'Mes informations', 'vue_etudiant', 6, 1);
INSERT INTO `PREFIXmenu_data` VALUES (45, 23, 3, 'Changer son mot de passe', 'motdepasse', 5, 2);
INSERT INTO `PREFIXmenu_data` VALUES (46, 24, 3, 'Mon cursus', 'liste_module', 23, 1);
INSERT INTO `PREFIXmenu_data` VALUES (47, 25, 3, 'Mon emplois du temps', 'PREFIXetudiant', 19, 1);
INSERT INTO `PREFIXmenu_data` VALUES (48, 26, 4, 'Changer son mot de passe', 'motdepasse', 5, 1);
INSERT INTO `PREFIXmenu_data` VALUES (49, 26, 4, 'D&eacute;connexion', 'login', 36, 2);
INSERT INTO `PREFIXmenu_data` VALUES (50, 27, 4, 'Constitution des groupes', 'constitution', 10, 1);
INSERT INTO `PREFIXmenu_data` VALUES (51, 27, 4, 'Trombinoscope', 'trombinoscope', 12, 2);
INSERT INTO `PREFIXmenu_data` VALUES (52, 28, 4, 'Liste des modules', 'liste_module', 23, 1);
INSERT INTO `PREFIXmenu_data` VALUES (53, 28, 4, 'Planification hebdomadaire', 'repartir', 20, 2);
INSERT INTO `PREFIXmenu_data` VALUES (54, 28, 4, 'Planification journali&egrave;re', 'planifier', 21, 3);
INSERT INTO `PREFIXmenu_data` VALUES (55, 28, 4, 'Validation', 'PREFIXdiplome', 18, 4);
INSERT INTO `PREFIXmenu_data` VALUES (57, 30, 1, 'Gestion Menu', 'gestion_menu', 37, 1);
INSERT INTO `PREFIXmenu_data` VALUES (59, 23, 3, 'D&eacute;connexion', 'login', 36, 3);
INSERT INTO `PREFIXmenu_data` VALUES (60, 0, 1, '', 'menu', 39, 1);
INSERT INTO `PREFIXmenu_data` VALUES (61, 31, 6, 'Changer son mot de passe', 'motdepasse', 5, 1);
INSERT INTO `PREFIXmenu_data` VALUES (62, 31, 6, 'D&eacute;connexion', 'login', 36, 2);
INSERT INTO `PREFIXmenu_data` VALUES (63, 32, 6, 'Mes dipl&ocirc;me', 'mes_diplomes', 32, 1);
INSERT INTO `PREFIXmenu_data` VALUES (64, 32, 6, 'Charges par dipl&ocirc;me', 'liste_module', 23, 2);
INSERT INTO `PREFIXmenu_data` VALUES (65, 33, 6, 'Module', 'module', 15, 1);
INSERT INTO `PREFIXmenu_data` VALUES (66, 33, 6, 'Volume horaire des modules', 'diviser', 16, 2);
INSERT INTO `PREFIXmenu_data` VALUES (67, 33, 6, 'R&eacute;partition des modules', 'assurer', 17, 3);
INSERT INTO `PREFIXmenu_data` VALUES (68, 34, 6, 'Charges', 'charges', 28, 1);
INSERT INTO `PREFIXmenu_data` VALUES (69, 35, 7, 'Changer son mot de passe', 'motdepasse', 5, 1);
INSERT INTO `PREFIXmenu_data` VALUES (70, 35, 7, 'D&eacute;connexion', 'login', 36, 2);
INSERT INTO `PREFIXmenu_data` VALUES (71, 36, 7, 'Listes des modules', 'liste_module', 23, 1);
INSERT INTO `PREFIXmenu_data` VALUES (72, 36, 7, 'Planification journali&egrave;re', 'planifier', 21, 2);
INSERT INTO `PREFIXmenu_data` VALUES (73, 36, 7, 'Affectation des salles', 'affecter', 24, 3);
INSERT INTO `PREFIXmenu_data` VALUES (74, 37, 7, '&Eacute;tudiant', 'etudiant', 11, 1);
INSERT INTO `PREFIXmenu_data` VALUES (75, 37, 7, 'Trombinoscope', 'trombinoscope', 12, 2);
INSERT INTO `PREFIXmenu_data` VALUES (76, 38, 8, 'Changer son mot de passe', 'motdepasse', 5, 1);
INSERT INTO `PREFIXmenu_data` VALUES (77, 38, 8, 'D&eacute;connexion', 'login', 36, 2);
INSERT INTO `PREFIXmenu_data` VALUES (78, 39, 8, 'Suivis enseignant', 'suivi', 31, 1);
INSERT INTO `PREFIXmenu_data` VALUES (79, 39, 8, 'Services enseignant', 'mon_service', 29, 2);
INSERT INTO `PREFIXmenu_data` VALUES (80, 39, 8, 'Tous les services enseignant', 'tous_les_services', 30, 3);
INSERT INTO `PREFIXmenu_data` VALUES (81, 40, 8, 'Charges par dipl&ocirc;me', 'liste_module', 23, 1);
INSERT INTO `PREFIXmenu_data` VALUES (82, 40, 8, 'Charges par d&eacute;partement', 'charges', 28, 2);
INSERT INTO `PREFIXmenu_data` VALUES (88, 0, 2, '', 'menu', 39, 1);
INSERT INTO `PREFIXmenu_data` VALUES (87, 0, 5, '', 'menu', 39, 1);
INSERT INTO `PREFIXmenu_data` VALUES (89, 0, 3, '', 'menu', 39, 1);
INSERT INTO `PREFIXmenu_data` VALUES (90, 0, 4, '', 'menu', 39, 1);
INSERT INTO `PREFIXmenu_data` VALUES (91, 0, 6, '', 'menu', 39, 1);
INSERT INTO `PREFIXmenu_data` VALUES (92, 0, 7, '', 'menu', 39, 1);
INSERT INTO `PREFIXmenu_data` VALUES (93, 0, 8, '', 'menu', 39, 1);
INSERT INTO `PREFIXmenu_data` VALUES (96, 0, 1, '', 'PREFIXdiplome', 18, 2);
INSERT INTO `PREFIXmenu_data` VALUES (97, 0, 3, '', 'PREFIXdiplome', 18, 2);
INSERT INTO `PREFIXmenu_data` VALUES (98, 0, 2, '', 'etudiant_visu', 13, 2);
INSERT INTO `PREFIXmenu_data` VALUES (99, 0, 4, '', 'etudiant_visu', 13, 2);
INSERT INTO `PREFIXmenu_data` VALUES (100, 0, 5, '', 'etudiant_visu', 13, 2);
INSERT INTO `PREFIXmenu_data` VALUES (101, 0, 7, '', 'etudiant_visu', 13, 2);
INSERT INTO `PREFIXmenu_data` VALUES (102, 0, 7, '', 'PREFIXsalle', 33, 3);
INSERT INTO `PREFIXmenu_data` VALUES (103, 5, 2, 'Informations', 'vue_enseignant', 40, 1);
INSERT INTO `PREFIXmenu_data` VALUES (104, 19, 2, 'Projet', 'projet_ens', 41, 2);
INSERT INTO `PREFIXmenu_data` VALUES (105, 24, 3, 'Projet', 'projet_etu', 42, 2);
INSERT INTO `PREFIXmenu_data` VALUES (106, 27, 4, 'Projet', 'projet_init', 45, 3);
INSERT INTO `PREFIXmenu_data` VALUES (107, 37, 7, 'Projet', 'projet_init', 45, 2);
INSERT INTO `PREFIXmenu_data` VALUES (108, 0, 7, '', 'Projet_creation_modification', 44, 4);
INSERT INTO `PREFIXmenu_data` VALUES (109, 0, 7, '', 'Projet', 43, 5);
INSERT INTO `PREFIXmenu_data` VALUES (110, 0, 4, '', 'Projet', 43, 3);
INSERT INTO `PREFIXmenu_data` VALUES (111, 0, 4, '', 'Projet_creation_modification', 44, 4);

-- --------------------------------------------------------

-- 
-- Structure de la table `PREFIXmenu_lien`
-- 

DROP TABLE IF EXISTS `PREFIXmenu_lien`;
CREATE TABLE `PREFIXmenu_lien` (
  `id` smallint(5) unsigned NOT NULL auto_increment,
  `libelle` varchar(128) NOT NULL default '',
  `suppressible` tinyint(1) default '1',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=40 ;

-- 
-- Contenu de la table `PREFIXmenu_lien`
-- 

INSERT INTO `PREFIXmenu_lien` VALUES (1, 'gestion_libelles.php', 1);
INSERT INTO `PREFIXmenu_lien` VALUES (2, 'calendrier.php', 1);
INSERT INTO `PREFIXmenu_lien` VALUES (3, 'enseignant.php', 1);
INSERT INTO `PREFIXmenu_lien` VALUES (4, 'userlist.php', 1);
INSERT INTO `PREFIXmenu_lien` VALUES (5, 'motdepasse.php', 1);
INSERT INTO `PREFIXmenu_lien` VALUES (6, 'etudiant/vue_etudiant.php', 1);
INSERT INTO `PREFIXmenu_lien` VALUES (7, 'diplome.php', 1);
INSERT INTO `PREFIXmenu_lien` VALUES (8, 'option.php', 1);
INSERT INTO `PREFIXmenu_lien` VALUES (9, 'groupe.php', 1);
INSERT INTO `PREFIXmenu_lien` VALUES (10, 'constitution_groupe.php', 1);
INSERT INTO `PREFIXmenu_lien` VALUES (11, 'etudiant.php', 1);
INSERT INTO `PREFIXmenu_lien` VALUES (12, 'etudiant/trombinoscope.php', 1);
INSERT INTO `PREFIXmenu_lien` VALUES (13, 'etudiant/etudiant_visu.php', 1);
INSERT INTO `PREFIXmenu_lien` VALUES (14, 'groupe_virtuel.php', 1);
INSERT INTO `PREFIXmenu_lien` VALUES (15, 'module.php', 1);
INSERT INTO `PREFIXmenu_lien` VALUES (16, 'diviser.php', 1);
INSERT INTO `PREFIXmenu_lien` VALUES (17, 'assurer.php', 1);
INSERT INTO `PREFIXmenu_lien` VALUES (18, 'edt/diplome.php', 1);
INSERT INTO `PREFIXmenu_lien` VALUES (19, 'edt/etudiant.php', 1);
INSERT INTO `PREFIXmenu_lien` VALUES (20, 'repartir.php', 1);
INSERT INTO `PREFIXmenu_lien` VALUES (21, 'planifier.php', 1);
INSERT INTO `PREFIXmenu_lien` VALUES (22, 'enseignant/module.php', 1);
INSERT INTO `PREFIXmenu_lien` VALUES (23, 'bilan/liste_module.php', 1);
INSERT INTO `PREFIXmenu_lien` VALUES (24, 'salle/affecter.php', 1);
INSERT INTO `PREFIXmenu_lien` VALUES (25, 'secretaire/secretaire.php', 1);
INSERT INTO `PREFIXmenu_lien` VALUES (26, 'secretaire/gestion_diplomes.php', 1);
INSERT INTO `PREFIXmenu_lien` VALUES (27, 'superviseur/superviseur.php', 1);
INSERT INTO `PREFIXmenu_lien` VALUES (28, 'bilan/charges.php', 1);
INSERT INTO `PREFIXmenu_lien` VALUES (29, 'enseignant/service.php', 1);
INSERT INTO `PREFIXmenu_lien` VALUES (30, 'enseignant/services.php', 1);
INSERT INTO `PREFIXmenu_lien` VALUES (31, 'bilan/suivi.php', 1);
INSERT INTO `PREFIXmenu_lien` VALUES (32, 'directeur/mes_diplomes.php', 1);
INSERT INTO `PREFIXmenu_lien` VALUES (33, 'edt/salle.php', 1);
INSERT INTO `PREFIXmenu_lien` VALUES (36, 'login.php', 0);
INSERT INTO `PREFIXmenu_lien` VALUES (37, 'administration/gestion_menu.php', 0);
INSERT INTO `PREFIXmenu_lien` VALUES (39, 'menu.php', 0);
INSERT INTO `PREFIXmenu_lien` VALUES (40, 'enseignant/vue_enseignant.php', '1');
INSERT INTO `PREFIXmenu_lien` VALUES (41, 'enseignant/projet_ens.php', '1');
INSERT INTO `PREFIXmenu_lien` VALUES (42, 'etudiant/projet_etu.php', '1');
INSERT INTO `PREFIXmenu_lien` VALUES (43, 'projet.php', '1');
INSERT INTO `PREFIXmenu_lien` VALUES (44, 'projet_creation_modification.php', '1');
INSERT INTO `PREFIXmenu_lien` VALUES (45, 'projet_init.php', '1');

-- --------------------------------------------------------

-- 
-- Structure de la table `PREFIXmodule`
-- 

DROP TABLE IF EXISTS `PREFIXmodule`;
CREATE TABLE `PREFIXmodule` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `nom` varchar(64) binary NOT NULL default '',
  `sigle` varchar(10) NOT NULL default '',
  `credits` int(11) NOT NULL default '0',
  `num_periode` int(1) NOT NULL default '0',
  `id_departement` int(10) unsigned NOT NULL default '0',
  `descriptif` text NOT NULL,
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=109 ;

-- 
-- Contenu de la table `PREFIXmodule`
-- 

INSERT INTO `PREFIXmodule` VALUES (1, 0x4c616e6761676520432b2b, 'C++', 4, 2, 1, '');
INSERT INTO `PREFIXmodule` VALUES (2, 0x4c564949202d20416c6c656d616e64, 'All', 2, 1, 2, '');
INSERT INTO `PREFIXmodule` VALUES (3, 0x4c616e67616765204a617661, 'Java', 4, 1, 1, '');
INSERT INTO `PREFIXmodule` VALUES (4, 0x50726f626162696c6974c383c692c382c2a97320657420737461746973746971756573, 'PrSt', 2, 1, 3, '');
INSERT INTO `PREFIXmodule` VALUES (5, 0x47c383c692c382c2a96f6dc383c692c382c2a9747269652032442d3344, 'G', 2, 1, 3, '');
INSERT INTO `PREFIXmodule` VALUES (6, 0x4c616e67616765204a617661, 'Java', 4, 1, 1, '');
INSERT INTO `PREFIXmodule` VALUES (7, 0x537973743f6d652064206578706c6f69746174696f6e, 'SE', 2, 1, 1, '');
INSERT INTO `PREFIXmodule` VALUES (36, 0x4d6174683f6d6174697175657320666f6e64616d656e74616c6573, 'Math', 7, 1, 3, '');
INSERT INTO `PREFIXmodule` VALUES (9, 0x426173657320646520646f6e6e3f657320494949, 'BD3', 4, 1, 1, '');
INSERT INTO `PREFIXmodule` VALUES (10, 0x4d6f643f6c69736174696f6e20657420636f6e63657074696f6e206f7269656e743f65206f626a657473, 'MCOO', 5, 2, 1, '');
INSERT INTO `PREFIXmodule` VALUES (35, 0x45737061676e6f6c, 'Esp', 2, 1, 2, '');
INSERT INTO `PREFIXmodule` VALUES (12, 0x54683f6f726965206465732067726170686573, 'TG', 2, 2, 1, '');
INSERT INTO `PREFIXmodule` VALUES (13, 0x416e616c797365206e756d3f7269717565, 'AN', 2, 2, 3, '');
INSERT INTO `PREFIXmodule` VALUES (14, 0x473f6e6965204c6f67696369656c, 'GL', 2, 2, 1, '');
INSERT INTO `PREFIXmodule` VALUES (15, 0x41646d696e697374726174696f6e20537973743f6d6573202620523f7365617578, 'ASR', 2, 2, 1, '');
INSERT INTO `PREFIXmodule` VALUES (51, 0x416c673f627265206c696e3f61697265, 'Alg. Lin.', 3, 1, 3, '');
INSERT INTO `PREFIXmodule` VALUES (31, 0x416c6c656d616e64, 'All', 2, 1, 2, '');
INSERT INTO `PREFIXmodule` VALUES (18, 0x537973743f6d6520657420617263686974656374757265, 'SysArchi', 5, 1, 1, '');
INSERT INTO `PREFIXmodule` VALUES (19, 0x426173657320646520646f6e6e3f65732072656c6174696f6e6e656c6c6573, 'BD', 5, 1, 1, '');
INSERT INTO `PREFIXmodule` VALUES (20, 0x416c676f726974686d69717565206574206c616e676167652043, 'Algo', 6, 1, 1, '');
INSERT INTO `PREFIXmodule` VALUES (21, 0x523f7365617578, 'R?seaux', 5, 1, 1, '');
INSERT INTO `PREFIXmodule` VALUES (22, 0x416e676c616973, 'Anglais', 5, 1, 2, 'PGJyIC8+DQo=');
INSERT INTO `PREFIXmodule` VALUES (24, 0x566965206420656e7472657072697365, 'VE', 4, 1, 1, 'DQow');
INSERT INTO `PREFIXmodule` VALUES (25, 0x496e673f6e69657269652064657320737973743f6d6573206420696e666f726d6174696f6e, 'ISI', 4, 2, 1, 'PGJyIC8+DQo=');
INSERT INTO `PREFIXmodule` VALUES (26, 0x473f6e6965206c6f67696369656c, 'GL', 2, 2, 1, '');
INSERT INTO `PREFIXmodule` VALUES (27, 0x443f76656c6f7070656d656e7420576562, 'Web', 4, 2, 1, '');
INSERT INTO `PREFIXmodule` VALUES (28, 0x4c616e67616765203f206f626a657473202d204a617661, 'Java', 5, 2, 1, '');
INSERT INTO `PREFIXmodule` VALUES (29, 0x4d6f643f6c69736174696f6e20657420636f6e63657074696f6e206f7269656e743f6573206f626a657473, 'MCOO', 4, 2, 1, '');
INSERT INTO `PREFIXmodule` VALUES (30, 0x50726f6a6574, 'Projet', 4, 2, 1, '');
INSERT INTO `PREFIXmodule` VALUES (32, 0x496e666f726d61746971756520677261706869717565, 'IG', 5, 2, 1, '');
INSERT INTO `PREFIXmodule` VALUES (34, 0x523f7365617578, 'R?so', 4, 1, 1, '');
INSERT INTO `PREFIXmodule` VALUES (37, 0x496e666f726d617469717565, 'Info', 4, 1, 1, '');
INSERT INTO `PREFIXmodule` VALUES (38, 0x454541, 'EEA', 4, 1, 4, '');
INSERT INTO `PREFIXmodule` VALUES (39, 0x416e676c616973, 'Ang', 2, 1, 2, '');
INSERT INTO `PREFIXmodule` VALUES (40, 0x4275726561757469717565, 'Bur.', 1, 1, 1, '');
INSERT INTO `PREFIXmodule` VALUES (41, 0x45454f, 'EEO', 2, 1, 2, '');
INSERT INTO `PREFIXmodule` VALUES (42, 0x50726f6a65742070726f66657373696f6e6e656c, 'Projet \r\np', 2, 1, 1, '');
INSERT INTO `PREFIXmodule` VALUES (43, 0x416c673f627265206c696e3f61697265, 'Alg. Lin.', 5, 2, 3, '');
INSERT INTO `PREFIXmodule` VALUES (44, 0x416c676f72697468696d69717565, 'Algo', 4, 2, 1, '');
INSERT INTO `PREFIXmodule` VALUES (45, 0x4261736520646520646f6e6e3f6573, 'BD', 4, 2, 1, '');
INSERT INTO `PREFIXmodule` VALUES (46, 0x417373656d626c657572, 'Asm', 4, 2, 1, '');
INSERT INTO `PREFIXmodule` VALUES (47, 0x416e676c616973, 'Ang', 2, 2, 2, '');
INSERT INTO `PREFIXmodule` VALUES (48, 0x4275726561757469717565, 'Bur.', 1, 2, 1, '');
INSERT INTO `PREFIXmodule` VALUES (49, 0x45454f, 'EEO', 1, 2, 2, '');
INSERT INTO `PREFIXmodule` VALUES (50, 0x486973746f6972652064657320736369656e636573, 'Hist. \r\nSc', 1, 2, 3, '');
INSERT INTO `PREFIXmodule` VALUES (52, 0x53444141, 'SDAA', 3, 1, 1, '');
INSERT INTO `PREFIXmodule` VALUES (53, 0x4c616e676167652043, 'C', 3, 1, 1, '');
INSERT INTO `PREFIXmodule` VALUES (54, 0x4261736520646520646f6e6e3f6573, 'BD', 3, 1, 1, '');
INSERT INTO `PREFIXmodule` VALUES (55, 0x48544d4c, 'HTML', 2, 1, 1, '');
INSERT INTO `PREFIXmodule` VALUES (56, 0x417263686974656374757265, 'Archi', 2, 1, 1, '');
INSERT INTO `PREFIXmodule` VALUES (57, 0x416e676c616973, 'Ang.', 2, 1, 2, '');
INSERT INTO `PREFIXmodule` VALUES (58, 0x436f6d6d756e69636174696f6e, 'Com', 2, 1, 2, '');
INSERT INTO `PREFIXmodule` VALUES (59, 0x416c6c656d616e64, 'All', 2, 1, 2, '');
INSERT INTO `PREFIXmodule` VALUES (60, 0x416c6c656d616e64, 'All', 2, 2, 2, '');
INSERT INTO `PREFIXmodule` VALUES (61, 0x45737061676e6f6c, 'Esp', 2, 1, 2, '');
INSERT INTO `PREFIXmodule` VALUES (62, 0x45737061676e6f6c, 'Esp', 2, 2, 2, '');
INSERT INTO `PREFIXmodule` VALUES (63, 0x416e676c616973, 'Ang.', 2, 1, 2, '');
INSERT INTO `PREFIXmodule` VALUES (64, 0x416e676c616973, 'Ang.', 2, 2, 2, '');
INSERT INTO `PREFIXmodule` VALUES (65, 0x416e616c797365, 'Analyse', 3, 2, 3, '');
INSERT INTO `PREFIXmodule` VALUES (66, 0x43616c63756c20666f726d656c, 'Cal. form.', 2, 2, 3, '');
INSERT INTO `PREFIXmodule` VALUES (67, 0x4d6572697365, 'Merise', 3, 2, 1, '');
INSERT INTO `PREFIXmodule` VALUES (68, 0x537973743f6d652064276578706c6f69746174696f6e, 'SE', 5, 2, 1, '');
INSERT INTO `PREFIXmodule` VALUES (69, 0x4d756c74696d3f646961, 'Multim?di', 2, 2, 1, '');
INSERT INTO `PREFIXmodule` VALUES (70, 0x443f76656c6f7070656d656e7420576562, 'D?v. Web', 4, 2, 1, '');
INSERT INTO `PREFIXmodule` VALUES (71, 0x416e676c616973, 'Ang.', 2, 2, 2, '');
INSERT INTO `PREFIXmodule` VALUES (72, 0x4d616e6167656d656e74, 'Managmt', 2, 2, 2, '');
INSERT INTO `PREFIXmodule` VALUES (73, 0x4d616e6167656d656e74, 'Managmt', 2, 1, 2, '');
INSERT INTO `PREFIXmodule` VALUES (74, 0x44726f6974206475207472617661696c, 'Droit', 1, 1, 2, '');
INSERT INTO `PREFIXmodule` VALUES (75, 0x436f6d70746162696c6974, 'Compta', 2, 1, 2, '');
INSERT INTO `PREFIXmodule` VALUES (76, 0x436f6d70746162696c6974, 'Compta', 2, 2, 2, '');
INSERT INTO `PREFIXmodule` VALUES (77, 0x47657374696f6e2064652070726f6a657473, 'Gest. \r\nPr', 2, 2, 2, '');
INSERT INTO `PREFIXmodule` VALUES (78, 0x526563686572636865206f70266561637574653b726174696f6e6e656c6c65, 'RO', 4, 1, 1, '');
INSERT INTO `PREFIXmodule` VALUES (79, 0x47266561637574653b6e6965206c6f67696369656c, 'GL', 4, 1, 1, '');
INSERT INTO `PREFIXmodule` VALUES (80, 0x53796e7468266567726176653b7365206420696d61676573, 'SI', 4, 1, 1, '');
INSERT INTO `PREFIXmodule` VALUES (81, 0x426173657320646520646f6e6e266561637574653b6573200d0a6f7269656e74266561637574653b65730d0a6f626a657473, 'BDOO', 4, 1, 1, '');
INSERT INTO `PREFIXmodule` VALUES (82, 0x53797374266567726176653b6d652074656d7073200d0a72266561637574653b656c, 'STR', 4, 1, 1, '');
INSERT INTO `PREFIXmodule` VALUES (83, 0x416e676c616973, 'Ang.', 2, 1, 2, '');
INSERT INTO `PREFIXmodule` VALUES (84, 0x5175616c6974266561637574653b, 'Qualit&eac', 2, 1, 2, '');
INSERT INTO `PREFIXmodule` VALUES (85, 0x456e7472657469656e206427656d626175636865, 'Entret.', 1, 1, 2, '');
INSERT INTO `PREFIXmodule` VALUES (86, 0x496e74656c6c6967656e6365206172746966696369656c6c65, 'IA', 4, 2, 1, '');
INSERT INTO `PREFIXmodule` VALUES (87, 0x496e666f726d617469717565207468266561637574653b6f7269717565, 'IT', 4, 2, 1, '');
INSERT INTO `PREFIXmodule` VALUES (88, 0x436f6d70696c6174696f6e, 'Compil', 4, 2, 1, '');
INSERT INTO `PREFIXmodule` VALUES (89, 0x53797374266567726176653b6d65206469737472696275266561637574653b, 'SD', 4, 2, 1, '');
INSERT INTO `PREFIXmodule` VALUES (91, 0x416e676c616973, 'Ang.', 2, 2, 2, '');
INSERT INTO `PREFIXmodule` VALUES (92, 0x496e73657274696f6e2070726f66657373696f6e6e656c6c65, 'Insert.', 1, 2, 2, '');
INSERT INTO `PREFIXmodule` VALUES (93, 0x4d61726b6574696e67, 'Marketing', 2, 2, 2, '');
INSERT INTO `PREFIXmodule` VALUES (94, 0x416c6c656d616e64, 'All', 1, 1, 2, '');
INSERT INTO `PREFIXmodule` VALUES (95, 0x416c6c656d616e64, 'All', 1, 2, 2, '');
INSERT INTO `PREFIXmodule` VALUES (96, 0x45737061676e6f6c, 'Esp', 1, 1, 2, '');
INSERT INTO `PREFIXmodule` VALUES (97, 0x45737061676e6f6c, 'Esp', 1, 2, 2, '');
INSERT INTO `PREFIXmodule` VALUES (98, 0x537973743f6d65206469737472696275, 'SD', 4, 1, 1, '');
INSERT INTO `PREFIXmodule` VALUES (99, 0x523f7365617578, 'R?so', 4, 1, 1, '');
INSERT INTO `PREFIXmodule` VALUES (100, 0x47657374696f6e2064652070726f6a657473, 'Gest. \r\nPr', 2, 1, 1, '');
INSERT INTO `PREFIXmodule` VALUES (101, 0x504f4f206176616e63, 'POO', 4, 1, 1, '');
INSERT INTO `PREFIXmodule` VALUES (102, 0x473f6e6965206c6f67696369656c, 'GL', 4, 1, 1, '');
INSERT INTO `PREFIXmodule` VALUES (103, 0x416e676c616973, 'Ang.', 2, 1, 2, '');
INSERT INTO `PREFIXmodule` VALUES (104, 0x566965206427656e7472657072697365, 'VE', 3, 1, 2, '');
INSERT INTO `PREFIXmodule` VALUES (105, 0x4261736520646520646f6e6e3f657320723f70617274696573, 'BDR', 4, 2, 1, '');
INSERT INTO `PREFIXmodule` VALUES (106, 0x47726f757057617265, 'GW', 3, 2, 1, '');
INSERT INTO `PREFIXmodule` VALUES (107, 0x466f75696c6c6520646520646f6e6e3f6573, 'FD', 3, 2, 1, '');
INSERT INTO `PREFIXmodule` VALUES (108, 0x4461746157617265686f757365, 'DW', 3, 2, 1, '');

-- --------------------------------------------------------

-- 
-- Structure de la table `PREFIXmodule_assure`
-- 

DROP TABLE IF EXISTS `PREFIXmodule_assure`;
CREATE TABLE `PREFIXmodule_assure` (
  `id_enseignant` int(11) NOT NULL default '0',
  `id_module` int(11) NOT NULL default '0',
  `id_groupe` int(11) default NULL,
  `id_type_seance` int(11) NOT NULL default '0',
  `nombre_heures` float NOT NULL default '0'
) TYPE=MyISAM;

-- 
-- Contenu de la table `PREFIXmodule_assure`
-- 

INSERT INTO `PREFIXmodule_assure` VALUES (2, 1, 0, 4, 2);
INSERT INTO `PREFIXmodule_assure` VALUES (2, 1, 0, 1, 10);
INSERT INTO `PREFIXmodule_assure` VALUES (2, 34, 0, 1, 15);
INSERT INTO `PREFIXmodule_assure` VALUES (2, 34, 0, 4, 2);
INSERT INTO `PREFIXmodule_assure` VALUES (30, 6, 2, 2, 36);
INSERT INTO `PREFIXmodule_assure` VALUES (30, 6, 3, 2, 36);
INSERT INTO `PREFIXmodule_assure` VALUES (26, 1, 2, 2, 12);
INSERT INTO `PREFIXmodule_assure` VALUES (2, 1, 2, 2, 18);
INSERT INTO `PREFIXmodule_assure` VALUES (26, 1, 3, 2, 12);
INSERT INTO `PREFIXmodule_assure` VALUES (26, 34, 2, 2, 12);
INSERT INTO `PREFIXmodule_assure` VALUES (2, 1, 3, 2, 18);
INSERT INTO `PREFIXmodule_assure` VALUES (26, 34, 3, 2, 12);
INSERT INTO `PREFIXmodule_assure` VALUES (29, 34, 2, 2, 18);
INSERT INTO `PREFIXmodule_assure` VALUES (29, 34, 3, 2, 18);
INSERT INTO `PREFIXmodule_assure` VALUES (24, 12, 3, 2, 10);
INSERT INTO `PREFIXmodule_assure` VALUES (24, 12, 2, 2, 10);
INSERT INTO `PREFIXmodule_assure` VALUES (27, 10, 3, 2, 33);
INSERT INTO `PREFIXmodule_assure` VALUES (27, 10, 2, 2, 33);
INSERT INTO `PREFIXmodule_assure` VALUES (27, 15, 2, 2, 15);
INSERT INTO `PREFIXmodule_assure` VALUES (27, 15, 3, 2, 15);
INSERT INTO `PREFIXmodule_assure` VALUES (28, 15, 2, 2, 15);
INSERT INTO `PREFIXmodule_assure` VALUES (28, 15, 3, 2, 15);
INSERT INTO `PREFIXmodule_assure` VALUES (27, 10, 0, 1, 12);
INSERT INTO `PREFIXmodule_assure` VALUES (27, 10, 0, 4, 2);
INSERT INTO `PREFIXmodule_assure` VALUES (24, 12, 0, 1, 10);
INSERT INTO `PREFIXmodule_assure` VALUES (24, 12, 0, 4, 2);
INSERT INTO `PREFIXmodule_assure` VALUES (28, 34, 3, 2, 15);
INSERT INTO `PREFIXmodule_assure` VALUES (28, 34, 2, 2, 15);
INSERT INTO `PREFIXmodule_assure` VALUES (30, 6, 0, 1, 18);
INSERT INTO `PREFIXmodule_assure` VALUES (30, 6, 0, 4, 2);
INSERT INTO `PREFIXmodule_assure` VALUES (25, 14, 0, 1, 15);
INSERT INTO `PREFIXmodule_assure` VALUES (25, 14, 0, 4, 2);
INSERT INTO `PREFIXmodule_assure` VALUES (32, 7, 2, 2, 21);
INSERT INTO `PREFIXmodule_assure` VALUES (25, 14, 3, 2, 10);
INSERT INTO `PREFIXmodule_assure` VALUES (25, 14, 2, 2, 10);
INSERT INTO `PREFIXmodule_assure` VALUES (26, 7, 3, 2, 15);
INSERT INTO `PREFIXmodule_assure` VALUES (32, 7, 3, 2, 21);
INSERT INTO `PREFIXmodule_assure` VALUES (26, 7, 2, 2, 15);
INSERT INTO `PREFIXmodule_assure` VALUES (27, 15, 0, 4, 2);
INSERT INTO `PREFIXmodule_assure` VALUES (24, 78, 0, 1, 9);
INSERT INTO `PREFIXmodule_assure` VALUES (24, 78, 0, 4, 2);
INSERT INTO `PREFIXmodule_assure` VALUES (33, 78, 0, 1, 9);
INSERT INTO `PREFIXmodule_assure` VALUES (33, 78, 0, 4, 2);
INSERT INTO `PREFIXmodule_assure` VALUES (25, 79, 0, 4, 2);
INSERT INTO `PREFIXmodule_assure` VALUES (25, 79, 0, 1, 15);
INSERT INTO `PREFIXmodule_assure` VALUES (33, 78, 25, 2, 9);
INSERT INTO `PREFIXmodule_assure` VALUES (33, 78, 26, 2, 9);
INSERT INTO `PREFIXmodule_assure` VALUES (33, 78, 26, 3, 12);
INSERT INTO `PREFIXmodule_assure` VALUES (33, 78, 25, 3, 12);
INSERT INTO `PREFIXmodule_assure` VALUES (24, 78, 26, 2, 9);
INSERT INTO `PREFIXmodule_assure` VALUES (24, 78, 25, 2, 9);
INSERT INTO `PREFIXmodule_assure` VALUES (24, 78, 26, 3, 12);
INSERT INTO `PREFIXmodule_assure` VALUES (24, 78, 25, 3, 12);
INSERT INTO `PREFIXmodule_assure` VALUES (25, 79, 26, 2, 15);
INSERT INTO `PREFIXmodule_assure` VALUES (25, 79, 25, 2, 15);
INSERT INTO `PREFIXmodule_assure` VALUES (25, 79, 26, 3, 12);
INSERT INTO `PREFIXmodule_assure` VALUES (25, 79, 25, 3, 12);
INSERT INTO `PREFIXmodule_assure` VALUES (25, 82, 26, 2, 12);
INSERT INTO `PREFIXmodule_assure` VALUES (25, 82, 25, 2, 12);
INSERT INTO `PREFIXmodule_assure` VALUES (25, 82, 26, 3, 18);
INSERT INTO `PREFIXmodule_assure` VALUES (25, 82, 25, 3, 18);
INSERT INTO `PREFIXmodule_assure` VALUES (25, 82, 0, 4, 2);
INSERT INTO `PREFIXmodule_assure` VALUES (25, 82, 0, 1, 12);
INSERT INTO `PREFIXmodule_assure` VALUES (2, 86, 0, 1, 9);
INSERT INTO `PREFIXmodule_assure` VALUES (2, 86, 0, 4, 2);
INSERT INTO `PREFIXmodule_assure` VALUES (33, 86, 0, 1, 9);
INSERT INTO `PREFIXmodule_assure` VALUES (34, 88, 25, 2, 12);
INSERT INTO `PREFIXmodule_assure` VALUES (34, 88, 26, 2, 12);
INSERT INTO `PREFIXmodule_assure` VALUES (34, 88, 25, 3, 24);
INSERT INTO `PREFIXmodule_assure` VALUES (34, 88, 26, 3, 24);
INSERT INTO `PREFIXmodule_assure` VALUES (33, 87, 0, 1, 18);
INSERT INTO `PREFIXmodule_assure` VALUES (33, 87, 0, 4, 2);
INSERT INTO `PREFIXmodule_assure` VALUES (34, 88, 0, 4, 2);
INSERT INTO `PREFIXmodule_assure` VALUES (34, 88, 0, 1, 12);
INSERT INTO `PREFIXmodule_assure` VALUES (37, 89, 0, 1, 15);
INSERT INTO `PREFIXmodule_assure` VALUES (37, 89, 0, 4, 2);
INSERT INTO `PREFIXmodule_assure` VALUES (31, 81, 0, 1, 12);
INSERT INTO `PREFIXmodule_assure` VALUES (31, 81, 0, 4, 2);
INSERT INTO `PREFIXmodule_assure` VALUES (2, 86, 0, 2, 0);
INSERT INTO `PREFIXmodule_assure` VALUES (33, 86, 0, 2, 0);
INSERT INTO `PREFIXmodule_assure` VALUES (24, 78, 0, 2, 0);
INSERT INTO `PREFIXmodule_assure` VALUES (32, 98, 0, 2, 1);
INSERT INTO `PREFIXmodule_assure` VALUES (32, 98, 0, 1, 1);
INSERT INTO `PREFIXmodule_assure` VALUES (32, 98, 0, 4, 2);
INSERT INTO `PREFIXmodule_assure` VALUES (32, 98, 0, 3, 0);
INSERT INTO `PREFIXmodule_assure` VALUES (32, 98, 0, 1, 11);
INSERT INTO `PREFIXmodule_assure` VALUES (32, 98, 0, 2, 11);

-- --------------------------------------------------------

-- 
-- Structure de la table `PREFIXmodule_divise`
-- 

DROP TABLE IF EXISTS `PREFIXmodule_divise`;
CREATE TABLE `PREFIXmodule_divise` (
  `id_module` int(10) unsigned NOT NULL default '0',
  `id_type_seance` int(10) unsigned NOT NULL default '0',
  `nombre_heures` float NOT NULL default '0',
  PRIMARY KEY  (`id_module`,`id_type_seance`)
) TYPE=MyISAM;

-- 
-- Contenu de la table `PREFIXmodule_divise`
-- 

INSERT INTO `PREFIXmodule_divise` VALUES (4, 4, 2);
INSERT INTO `PREFIXmodule_divise` VALUES (4, 1, 14);
INSERT INTO `PREFIXmodule_divise` VALUES (4, 2, 21);
INSERT INTO `PREFIXmodule_divise` VALUES (5, 1, 14);
INSERT INTO `PREFIXmodule_divise` VALUES (5, 2, 21);
INSERT INTO `PREFIXmodule_divise` VALUES (5, 4, 2);
INSERT INTO `PREFIXmodule_divise` VALUES (6, 1, 18);
INSERT INTO `PREFIXmodule_divise` VALUES (6, 2, 36);
INSERT INTO `PREFIXmodule_divise` VALUES (31, 2, 20);
INSERT INTO `PREFIXmodule_divise` VALUES (7, 2, 36);
INSERT INTO `PREFIXmodule_divise` VALUES (8, 1, 16);
INSERT INTO `PREFIXmodule_divise` VALUES (8, 2, 16);
INSERT INTO `PREFIXmodule_divise` VALUES (8, 3, 33);
INSERT INTO `PREFIXmodule_divise` VALUES (6, 4, 2);
INSERT INTO `PREFIXmodule_divise` VALUES (8, 4, 2);
INSERT INTO `PREFIXmodule_divise` VALUES (9, 1, 15);
INSERT INTO `PREFIXmodule_divise` VALUES (9, 2, 39);
INSERT INTO `PREFIXmodule_divise` VALUES (1, 1, 10);
INSERT INTO `PREFIXmodule_divise` VALUES (9, 4, 2);
INSERT INTO `PREFIXmodule_divise` VALUES (34, 1, 15);
INSERT INTO `PREFIXmodule_divise` VALUES (34, 2, 45);
INSERT INTO `PREFIXmodule_divise` VALUES (1, 2, 30);
INSERT INTO `PREFIXmodule_divise` VALUES (34, 4, 2);
INSERT INTO `PREFIXmodule_divise` VALUES (1, 4, 2);
INSERT INTO `PREFIXmodule_divise` VALUES (10, 1, 12);
INSERT INTO `PREFIXmodule_divise` VALUES (10, 2, 33);
INSERT INTO `PREFIXmodule_divise` VALUES (12, 1, 10);
INSERT INTO `PREFIXmodule_divise` VALUES (12, 2, 10);
INSERT INTO `PREFIXmodule_divise` VALUES (13, 1, 14);
INSERT INTO `PREFIXmodule_divise` VALUES (13, 2, 21);
INSERT INTO `PREFIXmodule_divise` VALUES (14, 1, 15);
INSERT INTO `PREFIXmodule_divise` VALUES (14, 2, 10);
INSERT INTO `PREFIXmodule_divise` VALUES (10, 4, 2);
INSERT INTO `PREFIXmodule_divise` VALUES (12, 4, 2);
INSERT INTO `PREFIXmodule_divise` VALUES (13, 4, 2);
INSERT INTO `PREFIXmodule_divise` VALUES (14, 4, 2);
INSERT INTO `PREFIXmodule_divise` VALUES (15, 2, 30);
INSERT INTO `PREFIXmodule_divise` VALUES (15, 4, 2);
INSERT INTO `PREFIXmodule_divise` VALUES (32, 1, 16);
INSERT INTO `PREFIXmodule_divise` VALUES (32, 2, 49);
INSERT INTO `PREFIXmodule_divise` VALUES (32, 4, 2);
INSERT INTO `PREFIXmodule_divise` VALUES (35, 2, 20);
INSERT INTO `PREFIXmodule_divise` VALUES (16, 2, 20);
INSERT INTO `PREFIXmodule_divise` VALUES (36, 1, 46);
INSERT INTO `PREFIXmodule_divise` VALUES (36, 2, 54);
INSERT INTO `PREFIXmodule_divise` VALUES (36, 4, 2);
INSERT INTO `PREFIXmodule_divise` VALUES (43, 1, 28);
INSERT INTO `PREFIXmodule_divise` VALUES (43, 2, 38);
INSERT INTO `PREFIXmodule_divise` VALUES (37, 4, 2);
INSERT INTO `PREFIXmodule_divise` VALUES (44, 1, 10);
INSERT INTO `PREFIXmodule_divise` VALUES (44, 2, 27);
INSERT INTO `PREFIXmodule_divise` VALUES (37, 2, 25.5);
INSERT INTO `PREFIXmodule_divise` VALUES (37, 1, 17.5);
INSERT INTO `PREFIXmodule_divise` VALUES (38, 1, 20);
INSERT INTO `PREFIXmodule_divise` VALUES (38, 2, 20);
INSERT INTO `PREFIXmodule_divise` VALUES (39, 2, 20);
INSERT INTO `PREFIXmodule_divise` VALUES (47, 2, 20);
INSERT INTO `PREFIXmodule_divise` VALUES (46, 1, 9);
INSERT INTO `PREFIXmodule_divise` VALUES (46, 2, 10);
INSERT INTO `PREFIXmodule_divise` VALUES (46, 3, 21);
INSERT INTO `PREFIXmodule_divise` VALUES (46, 4, 2);
INSERT INTO `PREFIXmodule_divise` VALUES (45, 1, 14);
INSERT INTO `PREFIXmodule_divise` VALUES (45, 2, 26);
INSERT INTO `PREFIXmodule_divise` VALUES (40, 2, 14);
INSERT INTO `PREFIXmodule_divise` VALUES (41, 2, 14);
INSERT INTO `PREFIXmodule_divise` VALUES (42, 2, 14);
INSERT INTO `PREFIXmodule_divise` VALUES (48, 2, 14);
INSERT INTO `PREFIXmodule_divise` VALUES (49, 2, 14);
INSERT INTO `PREFIXmodule_divise` VALUES (50, 1, 14);
INSERT INTO `PREFIXmodule_divise` VALUES (38, 4, 2);
INSERT INTO `PREFIXmodule_divise` VALUES (39, 4, 2);
INSERT INTO `PREFIXmodule_divise` VALUES (40, 4, 1);
INSERT INTO `PREFIXmodule_divise` VALUES (41, 4, 2);
INSERT INTO `PREFIXmodule_divise` VALUES (42, 4, 1);
INSERT INTO `PREFIXmodule_divise` VALUES (43, 4, 2);
INSERT INTO `PREFIXmodule_divise` VALUES (44, 4, 2);
INSERT INTO `PREFIXmodule_divise` VALUES (45, 4, 2);
INSERT INTO `PREFIXmodule_divise` VALUES (47, 4, 2);
INSERT INTO `PREFIXmodule_divise` VALUES (48, 4, 1);
INSERT INTO `PREFIXmodule_divise` VALUES (49, 4, 1);
INSERT INTO `PREFIXmodule_divise` VALUES (50, 4, 2);
INSERT INTO `PREFIXmodule_divise` VALUES (63, 2, 25);
INSERT INTO `PREFIXmodule_divise` VALUES (64, 2, 30);
INSERT INTO `PREFIXmodule_divise` VALUES (78, 1, 18);
INSERT INTO `PREFIXmodule_divise` VALUES (78, 2, 18);
INSERT INTO `PREFIXmodule_divise` VALUES (78, 3, 24);
INSERT INTO `PREFIXmodule_divise` VALUES (78, 4, 2);
INSERT INTO `PREFIXmodule_divise` VALUES (79, 1, 15);
INSERT INTO `PREFIXmodule_divise` VALUES (79, 2, 15);
INSERT INTO `PREFIXmodule_divise` VALUES (79, 3, 12);
INSERT INTO `PREFIXmodule_divise` VALUES (79, 4, 2);
INSERT INTO `PREFIXmodule_divise` VALUES (80, 1, 8);
INSERT INTO `PREFIXmodule_divise` VALUES (80, 4, 2);
INSERT INTO `PREFIXmodule_divise` VALUES (81, 1, 12);
INSERT INTO `PREFIXmodule_divise` VALUES (81, 2, 12);
INSERT INTO `PREFIXmodule_divise` VALUES (81, 3, 18);
INSERT INTO `PREFIXmodule_divise` VALUES (81, 4, 2);
INSERT INTO `PREFIXmodule_divise` VALUES (82, 1, 12);
INSERT INTO `PREFIXmodule_divise` VALUES (82, 2, 12);
INSERT INTO `PREFIXmodule_divise` VALUES (82, 3, 18);
INSERT INTO `PREFIXmodule_divise` VALUES (82, 4, 2);
INSERT INTO `PREFIXmodule_divise` VALUES (83, 2, 25);
INSERT INTO `PREFIXmodule_divise` VALUES (98, 1, 12);
INSERT INTO `PREFIXmodule_divise` VALUES (84, 1, 2);
INSERT INTO `PREFIXmodule_divise` VALUES (84, 2, 14);
INSERT INTO `PREFIXmodule_divise` VALUES (84, 4, 2);
INSERT INTO `PREFIXmodule_divise` VALUES (83, 4, 2);
INSERT INTO `PREFIXmodule_divise` VALUES (85, 2, 1);
INSERT INTO `PREFIXmodule_divise` VALUES (86, 1, 18);
INSERT INTO `PREFIXmodule_divise` VALUES (86, 2, 18);
INSERT INTO `PREFIXmodule_divise` VALUES (86, 3, 24);
INSERT INTO `PREFIXmodule_divise` VALUES (86, 4, 2);
INSERT INTO `PREFIXmodule_divise` VALUES (87, 1, 18);
INSERT INTO `PREFIXmodule_divise` VALUES (87, 2, 18);
INSERT INTO `PREFIXmodule_divise` VALUES (87, 4, 2);
INSERT INTO `PREFIXmodule_divise` VALUES (88, 1, 12);
INSERT INTO `PREFIXmodule_divise` VALUES (88, 2, 12);
INSERT INTO `PREFIXmodule_divise` VALUES (88, 3, 24);
INSERT INTO `PREFIXmodule_divise` VALUES (88, 4, 2);
INSERT INTO `PREFIXmodule_divise` VALUES (89, 1, 15);
INSERT INTO `PREFIXmodule_divise` VALUES (89, 2, 15);
INSERT INTO `PREFIXmodule_divise` VALUES (89, 3, 21);
INSERT INTO `PREFIXmodule_divise` VALUES (89, 4, 2);
INSERT INTO `PREFIXmodule_divise` VALUES (92, 2, 12);
INSERT INTO `PREFIXmodule_divise` VALUES (93, 1, 14);
INSERT INTO `PREFIXmodule_divise` VALUES (93, 4, 2);
INSERT INTO `PREFIXmodule_divise` VALUES (91, 2, 25);
INSERT INTO `PREFIXmodule_divise` VALUES (98, 2, 12);
INSERT INTO `PREFIXmodule_divise` VALUES (98, 3, 18);
INSERT INTO `PREFIXmodule_divise` VALUES (98, 4, 2);
INSERT INTO `PREFIXmodule_divise` VALUES (99, 1, 9);
INSERT INTO `PREFIXmodule_divise` VALUES (99, 2, 9);
INSERT INTO `PREFIXmodule_divise` VALUES (99, 3, 24);
INSERT INTO `PREFIXmodule_divise` VALUES (99, 4, 2);
INSERT INTO `PREFIXmodule_divise` VALUES (100, 1, 10);
INSERT INTO `PREFIXmodule_divise` VALUES (100, 3, 20);
INSERT INTO `PREFIXmodule_divise` VALUES (100, 4, 2);
INSERT INTO `PREFIXmodule_divise` VALUES (101, 1, 5);
INSERT INTO `PREFIXmodule_divise` VALUES (101, 2, 5);
INSERT INTO `PREFIXmodule_divise` VALUES (101, 3, 32);
INSERT INTO `PREFIXmodule_divise` VALUES (101, 4, 2);
INSERT INTO `PREFIXmodule_divise` VALUES (102, 1, 12);
INSERT INTO `PREFIXmodule_divise` VALUES (102, 2, 9);
INSERT INTO `PREFIXmodule_divise` VALUES (102, 3, 21);
INSERT INTO `PREFIXmodule_divise` VALUES (102, 4, 2);
INSERT INTO `PREFIXmodule_divise` VALUES (103, 2, 30);
INSERT INTO `PREFIXmodule_divise` VALUES (103, 4, 2);
INSERT INTO `PREFIXmodule_divise` VALUES (104, 2, 50);
INSERT INTO `PREFIXmodule_divise` VALUES (105, 1, 12);
INSERT INTO `PREFIXmodule_divise` VALUES (105, 2, 12);
INSERT INTO `PREFIXmodule_divise` VALUES (105, 3, 26);
INSERT INTO `PREFIXmodule_divise` VALUES (105, 4, 2);
INSERT INTO `PREFIXmodule_divise` VALUES (106, 1, 8);
INSERT INTO `PREFIXmodule_divise` VALUES (106, 2, 8);
INSERT INTO `PREFIXmodule_divise` VALUES (106, 3, 9);
INSERT INTO `PREFIXmodule_divise` VALUES (106, 4, 2);
INSERT INTO `PREFIXmodule_divise` VALUES (107, 1, 10);
INSERT INTO `PREFIXmodule_divise` VALUES (107, 2, 15);
INSERT INTO `PREFIXmodule_divise` VALUES (107, 4, 2);
INSERT INTO `PREFIXmodule_divise` VALUES (108, 1, 8);
INSERT INTO `PREFIXmodule_divise` VALUES (108, 2, 8);
INSERT INTO `PREFIXmodule_divise` VALUES (108, 3, 9);
INSERT INTO `PREFIXmodule_divise` VALUES (108, 4, 2);
INSERT INTO `PREFIXmodule_divise` VALUES (94, 2, 20);
INSERT INTO `PREFIXmodule_divise` VALUES (95, 2, 20);
INSERT INTO `PREFIXmodule_divise` VALUES (96, 2, 20);
INSERT INTO `PREFIXmodule_divise` VALUES (97, 2, 20);
INSERT INTO `PREFIXmodule_divise` VALUES (32, 3, 2);
INSERT INTO `PREFIXmodule_divise` VALUES (83, 1, 20);

-- --------------------------------------------------------

-- 
-- Structure de la table `PREFIXmodule_planifie`
-- 

DROP TABLE IF EXISTS `PREFIXmodule_planifie`;
CREATE TABLE `PREFIXmodule_planifie` (
  `id_planifie` int(10) NOT NULL auto_increment,
  `id_module` int(10) NOT NULL default '0',
  `id_type_seance` int(10) NOT NULL default '0',
  `id_enseignant` int(10) NOT NULL default '0',
  `id_salle` int(10) NOT NULL default '0',
  `semaine` int(2) NOT NULL default '0',
  `jour_semaine` int(1) NOT NULL default '0',
  `heure_debut` time NOT NULL default '00:00:00',
  `heure_fin` time NOT NULL default '00:00:00',
  `valid_enseignant` tinyint(1) NOT NULL default '0',
  `valid_de` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`id_planifie`)
) TYPE=MyISAM AUTO_INCREMENT=267 ;

-- 
-- Contenu de la table `PREFIXmodule_planifie`
-- 

INSERT INTO `PREFIXmodule_planifie` VALUES (122, 78, 1, 33, 1, 41, 1, '09:00:00', '11:00:00', 0, 0);
INSERT INTO `PREFIXmodule_planifie` VALUES (6, 0, 0, 0, -1, 38, 0, '00:00:00', '00:00:00', 0, 0);
INSERT INTO `PREFIXmodule_planifie` VALUES (121, 78, 1, 33, -1, 40, 1, '09:00:00', '11:00:00', 0, 1);
INSERT INTO `PREFIXmodule_planifie` VALUES (123, 78, 1, 33, -1, 42, 1, '09:00:00', '11:00:00', 0, 0);
INSERT INTO `PREFIXmodule_planifie` VALUES (120, 78, 1, 33, -1, 39, 1, '09:00:00', '11:00:00', 0, 1);
INSERT INTO `PREFIXmodule_planifie` VALUES (118, 6, 2, 30, -1, 49, 1, '09:00:00', '10:00:00', 0, 0);
INSERT INTO `PREFIXmodule_planifie` VALUES (119, 78, 1, 33, -1, 38, 1, '09:00:00', '11:00:00', 0, 0);
INSERT INTO `PREFIXmodule_planifie` VALUES (124, 78, 1, 33, -1, 43, 1, '09:00:00', '11:00:00', 0, 0);
INSERT INTO `PREFIXmodule_planifie` VALUES (125, 78, 1, 33, -1, 44, 1, '09:00:00', '11:00:00', 0, 0);
INSERT INTO `PREFIXmodule_planifie` VALUES (126, 78, 1, 33, -1, 45, 1, '09:00:00', '11:00:00', 0, 0);
INSERT INTO `PREFIXmodule_planifie` VALUES (127, 78, 1, 33, -1, 46, 1, '09:00:00', '11:00:00', 0, 0);
INSERT INTO `PREFIXmodule_planifie` VALUES (128, 82, 1, 25, -1, 38, 2, '13:00:00', '15:00:00', 0, 0);
INSERT INTO `PREFIXmodule_planifie` VALUES (129, 82, 1, 25, -1, 39, 2, '13:00:00', '15:00:00', 0, 1);
INSERT INTO `PREFIXmodule_planifie` VALUES (130, 82, 1, 25, -1, 40, 2, '13:00:00', '15:00:00', 1, 0);
INSERT INTO `PREFIXmodule_planifie` VALUES (131, 82, 1, 25, 1, 41, 2, '13:00:00', '15:00:00', 0, 0);
INSERT INTO `PREFIXmodule_planifie` VALUES (132, 82, 1, 25, -1, 42, 2, '13:00:00', '15:00:00', 0, 0);
INSERT INTO `PREFIXmodule_planifie` VALUES (133, 82, 1, 25, -1, 43, 2, '13:00:00', '15:00:00', 0, 0);
INSERT INTO `PREFIXmodule_planifie` VALUES (134, 78, 3, 33, -1, 39, 2, '09:00:00', '12:00:00', 0, 1);
INSERT INTO `PREFIXmodule_planifie` VALUES (135, 78, 3, 33, -1, 40, 2, '09:00:00', '12:00:00', 0, 1);
INSERT INTO `PREFIXmodule_planifie` VALUES (136, 78, 3, 33, -1, 41, 2, '09:00:00', '12:00:00', 0, 0);
INSERT INTO `PREFIXmodule_planifie` VALUES (137, 78, 3, 33, -1, 42, 2, '09:00:00', '12:00:00', 0, 0);
INSERT INTO `PREFIXmodule_planifie` VALUES (138, 78, 3, 33, -1, 43, 2, '09:00:00', '12:00:00', 0, 0);
INSERT INTO `PREFIXmodule_planifie` VALUES (139, 78, 3, 33, -1, 44, 2, '09:00:00', '12:00:00', 0, 0);
INSERT INTO `PREFIXmodule_planifie` VALUES (140, 78, 3, 33, -1, 45, 2, '09:00:00', '12:00:00', 0, 0);
INSERT INTO `PREFIXmodule_planifie` VALUES (141, 78, 3, 33, -1, 46, 2, '09:00:00', '12:00:00', 0, 0);
INSERT INTO `PREFIXmodule_planifie` VALUES (142, 81, 3, 37, -1, 39, 2, '09:00:00', '12:00:00', 0, 1);
INSERT INTO `PREFIXmodule_planifie` VALUES (143, 81, 3, 37, -1, 40, 2, '09:00:00', '12:00:00', 0, 0);
INSERT INTO `PREFIXmodule_planifie` VALUES (144, 81, 3, 37, -1, 41, 2, '09:00:00', '12:00:00', 0, 0);
INSERT INTO `PREFIXmodule_planifie` VALUES (145, 81, 3, 37, -1, 42, 2, '09:00:00', '12:00:00', 0, 0);
INSERT INTO `PREFIXmodule_planifie` VALUES (146, 81, 3, 37, -1, 43, 2, '09:00:00', '12:00:00', 0, 0);
INSERT INTO `PREFIXmodule_planifie` VALUES (147, 81, 3, 37, -1, 44, 2, '09:00:00', '12:00:00', 0, 0);
INSERT INTO `PREFIXmodule_planifie` VALUES (148, 81, 3, 37, -1, 39, 5, '16:00:00', '19:00:00', 0, 0);
INSERT INTO `PREFIXmodule_planifie` VALUES (149, 81, 3, 37, 1, 40, 5, '16:00:00', '19:00:00', 0, 0);
INSERT INTO `PREFIXmodule_planifie` VALUES (150, 81, 3, 37, -1, 41, 5, '16:00:00', '19:00:00', 0, 0);
INSERT INTO `PREFIXmodule_planifie` VALUES (151, 81, 3, 37, -1, 42, 5, '16:00:00', '19:00:00', 0, 0);
INSERT INTO `PREFIXmodule_planifie` VALUES (152, 81, 3, 37, -1, 43, 5, '16:00:00', '19:00:00', 0, 0);
INSERT INTO `PREFIXmodule_planifie` VALUES (153, 81, 3, 37, -1, 44, 5, '16:00:00', '19:00:00', 0, 0);
INSERT INTO `PREFIXmodule_planifie` VALUES (165, 83, 2, -1, -1, 39, 4, '08:30:00', '10:30:00', 0, 1);
INSERT INTO `PREFIXmodule_planifie` VALUES (155, 83, 2, -1, -1, 40, 4, '09:00:00', '11:00:00', 0, 0);
INSERT INTO `PREFIXmodule_planifie` VALUES (156, 83, 2, -1, -1, 41, 4, '09:00:00', '11:00:00', 0, 0);
INSERT INTO `PREFIXmodule_planifie` VALUES (157, 83, 2, -1, -1, 42, 4, '09:00:00', '11:00:00', 0, 0);
INSERT INTO `PREFIXmodule_planifie` VALUES (158, 83, 2, -1, -1, 43, 4, '09:00:00', '11:00:00', 0, 0);
INSERT INTO `PREFIXmodule_planifie` VALUES (159, 83, 2, -1, -1, 44, 4, '09:00:00', '11:00:00', 0, 0);
INSERT INTO `PREFIXmodule_planifie` VALUES (160, 83, 2, -1, -1, 45, 4, '09:00:00', '11:00:00', 0, 0);
INSERT INTO `PREFIXmodule_planifie` VALUES (161, 83, 2, -1, -1, 46, 4, '09:00:00', '11:00:00', 0, 0);
INSERT INTO `PREFIXmodule_planifie` VALUES (162, 83, 2, -1, -1, 47, 4, '09:00:00', '11:00:00', 0, 0);
INSERT INTO `PREFIXmodule_planifie` VALUES (163, 83, 2, -1, -1, 48, 4, '09:00:00', '11:00:00', 0, 0);
INSERT INTO `PREFIXmodule_planifie` VALUES (167, 83, 2, -1, -1, 38, 4, '10:30:00', '12:30:00', 0, 0);
INSERT INTO `PREFIXmodule_planifie` VALUES (168, 83, 2, -1, -1, 39, 4, '10:30:00', '12:30:00', 0, 1);
INSERT INTO `PREFIXmodule_planifie` VALUES (169, 83, 2, -1, -1, 38, 4, '08:30:00', '10:30:00', 0, 0);
INSERT INTO `PREFIXmodule_planifie` VALUES (170, 82, 2, 25, -1, 38, 4, '10:30:00', '12:30:00', 0, 0);
INSERT INTO `PREFIXmodule_planifie` VALUES (171, 82, 2, 25, -1, 39, 4, '10:30:00', '12:30:00', 0, 1);
INSERT INTO `PREFIXmodule_planifie` VALUES (172, 82, 2, 25, -1, 38, 4, '08:30:00', '10:30:00', 0, 0);
INSERT INTO `PREFIXmodule_planifie` VALUES (173, 82, 2, 25, -1, 39, 4, '08:30:00', '10:30:00', 0, 1);
INSERT INTO `PREFIXmodule_planifie` VALUES (174, 82, 2, 25, -1, 40, 4, '08:30:00', '10:30:00', 1, 0);
INSERT INTO `PREFIXmodule_planifie` VALUES (175, 82, 2, 25, -1, 41, 4, '08:30:00', '10:30:00', 0, 0);
INSERT INTO `PREFIXmodule_planifie` VALUES (176, 82, 2, 25, -1, 42, 4, '08:30:00', '10:30:00', 0, 0);
INSERT INTO `PREFIXmodule_planifie` VALUES (177, 82, 2, 25, -1, 43, 4, '08:30:00', '10:30:00', 0, 0);
INSERT INTO `PREFIXmodule_planifie` VALUES (178, 94, 2, -1, -1, 37, 5, '09:00:00', '11:00:00', 0, 0);
INSERT INTO `PREFIXmodule_planifie` VALUES (179, 94, 2, -1, -1, 38, 5, '09:00:00', '11:00:00', 0, 0);
INSERT INTO `PREFIXmodule_planifie` VALUES (180, 94, 2, -1, -1, 39, 5, '09:00:00', '11:00:00', 0, 0);
INSERT INTO `PREFIXmodule_planifie` VALUES (181, 94, 2, -1, -1, 40, 5, '09:00:00', '11:00:00', 0, 0);
INSERT INTO `PREFIXmodule_planifie` VALUES (182, 94, 2, -1, -1, 41, 5, '09:00:00', '11:00:00', 0, 0);
INSERT INTO `PREFIXmodule_planifie` VALUES (183, 94, 2, -1, -1, 42, 5, '09:00:00', '11:00:00', 0, 0);
INSERT INTO `PREFIXmodule_planifie` VALUES (184, 94, 2, -1, -1, 43, 5, '09:00:00', '11:00:00', 0, 0);
INSERT INTO `PREFIXmodule_planifie` VALUES (185, 94, 2, -1, -1, 44, 5, '09:00:00', '11:00:00', 0, 0);
INSERT INTO `PREFIXmodule_planifie` VALUES (186, 94, 2, -1, -1, 45, 5, '09:00:00', '11:00:00', 0, 0);
INSERT INTO `PREFIXmodule_planifie` VALUES (187, 94, 2, -1, -1, 46, 5, '09:00:00', '11:00:00', 0, 0);
INSERT INTO `PREFIXmodule_planifie` VALUES (189, 79, 1, 25, -1, 38, 1, '14:00:00', '16:00:00', 0, 0);
INSERT INTO `PREFIXmodule_planifie` VALUES (190, 79, 1, 25, -1, 39, 1, '14:00:00', '16:00:00', 0, 1);
INSERT INTO `PREFIXmodule_planifie` VALUES (191, 79, 1, 25, -1, 40, 1, '14:00:00', '16:00:00', 1, 1);
INSERT INTO `PREFIXmodule_planifie` VALUES (192, 79, 1, 25, 1, 41, 1, '14:00:00', '16:00:00', 0, 0);
INSERT INTO `PREFIXmodule_planifie` VALUES (193, 79, 1, 25, -1, 42, 1, '14:00:00', '16:00:00', 0, 0);
INSERT INTO `PREFIXmodule_planifie` VALUES (194, 79, 1, 25, -1, 43, 1, '14:00:00', '16:00:00', 0, 0);
INSERT INTO `PREFIXmodule_planifie` VALUES (195, 79, 1, 25, -1, 44, 1, '14:00:00', '16:00:00', 0, 0);
INSERT INTO `PREFIXmodule_planifie` VALUES (196, 80, 1, -1, -1, 38, 2, '09:00:00', '11:00:00', 0, 0);
INSERT INTO `PREFIXmodule_planifie` VALUES (197, 80, 1, -1, -1, 39, 2, '09:00:00', '11:00:00', 0, 1);
INSERT INTO `PREFIXmodule_planifie` VALUES (198, 80, 1, -1, -1, 44, 2, '09:00:00', '11:00:00', 0, 0);
INSERT INTO `PREFIXmodule_planifie` VALUES (199, 80, 1, -1, -1, 45, 2, '09:00:00', '11:00:00', 0, 0);
INSERT INTO `PREFIXmodule_planifie` VALUES (200, 81, 1, 31, -1, 38, 3, '09:00:00', '11:00:00', 0, 0);
INSERT INTO `PREFIXmodule_planifie` VALUES (202, 81, 1, 31, -1, 40, 3, '09:00:00', '11:00:00', 0, 1);
INSERT INTO `PREFIXmodule_planifie` VALUES (203, 81, 1, 31, -1, 41, 3, '09:00:00', '11:00:00', 0, 0);
INSERT INTO `PREFIXmodule_planifie` VALUES (204, 81, 1, 31, -1, 42, 3, '09:00:00', '11:00:00', 0, 0);
INSERT INTO `PREFIXmodule_planifie` VALUES (205, 81, 1, 31, -1, 43, 3, '09:00:00', '11:00:00', 0, 0);
INSERT INTO `PREFIXmodule_planifie` VALUES (206, 78, 2, -1, -1, 38, 1, '16:00:00', '18:00:00', 0, 0);
INSERT INTO `PREFIXmodule_planifie` VALUES (207, 78, 2, -1, -1, 39, 1, '16:00:00', '18:00:00', 0, 1);
INSERT INTO `PREFIXmodule_planifie` VALUES (208, 78, 2, -1, -1, 40, 1, '16:00:00', '18:00:00', 0, 0);
INSERT INTO `PREFIXmodule_planifie` VALUES (209, 78, 2, -1, -1, 41, 1, '16:00:00', '18:00:00', 0, 0);
INSERT INTO `PREFIXmodule_planifie` VALUES (210, 78, 2, -1, -1, 42, 1, '16:00:00', '18:00:00', 0, 0);
INSERT INTO `PREFIXmodule_planifie` VALUES (211, 78, 2, -1, -1, 43, 1, '16:00:00', '18:00:00', 0, 0);
INSERT INTO `PREFIXmodule_planifie` VALUES (212, 78, 2, -1, -1, 44, 1, '16:00:00', '18:00:00', 0, 0);
INSERT INTO `PREFIXmodule_planifie` VALUES (213, 78, 2, -1, -1, 45, 1, '16:00:00', '18:00:00', 0, 0);
INSERT INTO `PREFIXmodule_planifie` VALUES (214, 78, 2, -1, -1, 46, 1, '16:00:00', '18:00:00', 0, 0);
INSERT INTO `PREFIXmodule_planifie` VALUES (215, 79, 2, 25, -1, 38, 2, '15:00:00', '17:00:00', 0, 0);
INSERT INTO `PREFIXmodule_planifie` VALUES (216, 79, 2, 25, -1, 39, 2, '15:00:00', '17:00:00', 0, 0);
INSERT INTO `PREFIXmodule_planifie` VALUES (217, 79, 2, 25, -1, 40, 2, '15:00:00', '17:00:00', 0, 0);
INSERT INTO `PREFIXmodule_planifie` VALUES (218, 79, 2, 25, -1, 41, 2, '15:00:00', '17:00:00', 0, 0);
INSERT INTO `PREFIXmodule_planifie` VALUES (219, 79, 2, 25, -1, 42, 2, '15:00:00', '17:00:00', 0, 0);
INSERT INTO `PREFIXmodule_planifie` VALUES (220, 79, 2, 25, -1, 43, 2, '15:00:00', '17:00:00', 0, 0);
INSERT INTO `PREFIXmodule_planifie` VALUES (221, 79, 2, 25, -1, 44, 2, '15:00:00', '17:00:00', 0, 0);
INSERT INTO `PREFIXmodule_planifie` VALUES (222, 81, 2, -1, -1, 38, 2, '17:00:00', '19:00:00', 0, 0);
INSERT INTO `PREFIXmodule_planifie` VALUES (223, 81, 2, -1, -1, 39, 2, '17:00:00', '19:00:00', 0, 0);
INSERT INTO `PREFIXmodule_planifie` VALUES (224, 81, 2, -1, -1, 40, 2, '17:00:00', '19:00:00', 0, 0);
INSERT INTO `PREFIXmodule_planifie` VALUES (225, 81, 2, -1, -1, 41, 2, '17:00:00', '19:00:00', 0, 0);
INSERT INTO `PREFIXmodule_planifie` VALUES (226, 81, 2, -1, -1, 42, 2, '17:00:00', '19:00:00', 0, 0);
INSERT INTO `PREFIXmodule_planifie` VALUES (227, 81, 2, -1, -1, 43, 2, '17:00:00', '19:00:00', 0, 0);
INSERT INTO `PREFIXmodule_planifie` VALUES (228, 79, 2, 25, -1, 38, 1, '16:00:00', '18:00:00', 0, 0);
INSERT INTO `PREFIXmodule_planifie` VALUES (229, 79, 2, 25, -1, 39, 1, '16:00:00', '18:00:00', 0, 1);
INSERT INTO `PREFIXmodule_planifie` VALUES (230, 79, 2, 25, -1, 40, 1, '16:00:00', '18:00:00', 0, 0);
INSERT INTO `PREFIXmodule_planifie` VALUES (231, 79, 2, 25, -1, 41, 1, '16:00:00', '18:00:00', 0, 0);
INSERT INTO `PREFIXmodule_planifie` VALUES (232, 79, 2, 25, -1, 42, 1, '16:00:00', '18:00:00', 0, 0);
INSERT INTO `PREFIXmodule_planifie` VALUES (233, 79, 2, 25, -1, 43, 1, '16:00:00', '18:00:00', 0, 0);
INSERT INTO `PREFIXmodule_planifie` VALUES (234, 79, 2, 25, -1, 44, 1, '16:00:00', '18:00:00', 0, 0);
INSERT INTO `PREFIXmodule_planifie` VALUES (235, 78, 2, -1, -1, 38, 2, '15:00:00', '17:00:00', 0, 0);
INSERT INTO `PREFIXmodule_planifie` VALUES (236, 78, 2, -1, -1, 39, 2, '15:00:00', '17:00:00', 0, 0);
INSERT INTO `PREFIXmodule_planifie` VALUES (237, 78, 2, -1, -1, 40, 2, '15:00:00', '17:00:00', 0, 0);
INSERT INTO `PREFIXmodule_planifie` VALUES (238, 78, 2, -1, -1, 41, 2, '15:00:00', '17:00:00', 0, 0);
INSERT INTO `PREFIXmodule_planifie` VALUES (239, 78, 2, -1, -1, 42, 2, '15:00:00', '17:00:00', 0, 0);
INSERT INTO `PREFIXmodule_planifie` VALUES (240, 78, 2, -1, -1, 43, 2, '15:00:00', '17:00:00', 0, 0);
INSERT INTO `PREFIXmodule_planifie` VALUES (241, 78, 2, -1, -1, 44, 2, '15:00:00', '17:00:00', 0, 0);
INSERT INTO `PREFIXmodule_planifie` VALUES (242, 78, 2, -1, -1, 45, 2, '15:00:00', '17:00:00', 0, 0);
INSERT INTO `PREFIXmodule_planifie` VALUES (243, 78, 2, -1, -1, 46, 2, '15:00:00', '17:00:00', 0, 0);
INSERT INTO `PREFIXmodule_planifie` VALUES (244, 81, 2, -1, -1, 38, 3, '14:00:00', '16:00:00', 0, 0);
INSERT INTO `PREFIXmodule_planifie` VALUES (245, 81, 2, -1, -1, 39, 3, '14:00:00', '16:00:00', 0, 1);
INSERT INTO `PREFIXmodule_planifie` VALUES (246, 81, 2, -1, -1, 40, 3, '14:00:00', '16:00:00', 0, 0);
INSERT INTO `PREFIXmodule_planifie` VALUES (247, 81, 2, -1, -1, 41, 3, '14:00:00', '16:00:00', 0, 0);
INSERT INTO `PREFIXmodule_planifie` VALUES (248, 81, 2, -1, -1, 42, 3, '14:00:00', '16:00:00', 0, 0);
INSERT INTO `PREFIXmodule_planifie` VALUES (249, 81, 2, -1, -1, 43, 3, '14:00:00', '16:00:00', 0, 0);
INSERT INTO `PREFIXmodule_planifie` VALUES (250, 78, 3, -1, -1, 44, 2, '17:00:00', '20:00:00', 0, 0);
INSERT INTO `PREFIXmodule_planifie` VALUES (251, 78, 3, -1, -1, 45, 2, '17:00:00', '20:00:00', 0, 0);
INSERT INTO `PREFIXmodule_planifie` VALUES (252, 78, 3, -1, -1, 46, 2, '17:00:00', '20:00:00', 0, 0);
INSERT INTO `PREFIXmodule_planifie` VALUES (253, 78, 3, -1, -1, 39, 3, '16:00:00', '19:00:00', 0, 0);
INSERT INTO `PREFIXmodule_planifie` VALUES (254, 78, 3, -1, -1, 40, 3, '16:00:00', '19:00:00', 0, 0);
INSERT INTO `PREFIXmodule_planifie` VALUES (255, 78, 3, -1, -1, 41, 3, '16:00:00', '19:00:00', 0, 0);
INSERT INTO `PREFIXmodule_planifie` VALUES (256, 78, 3, -1, -1, 42, 3, '16:00:00', '19:00:00', 0, 0);
INSERT INTO `PREFIXmodule_planifie` VALUES (257, 78, 3, -1, -1, 43, 3, '16:00:00', '19:00:00', 0, 0);
INSERT INTO `PREFIXmodule_planifie` VALUES (258, 78, 3, -1, -1, 44, 3, '16:00:00', '19:00:00', 0, 0);
INSERT INTO `PREFIXmodule_planifie` VALUES (259, 78, 3, -1, -1, 45, 3, '16:00:00', '19:00:00', 0, 0);
INSERT INTO `PREFIXmodule_planifie` VALUES (260, 78, 3, -1, -1, 46, 3, '16:00:00', '19:00:00', 0, 0);
INSERT INTO `PREFIXmodule_planifie` VALUES (261, 82, 3, 25, -1, 39, 3, '16:00:00', '19:00:00', 0, 1);
INSERT INTO `PREFIXmodule_planifie` VALUES (262, 82, 3, 25, -1, 40, 3, '16:00:00', '19:00:00', 0, 0);
INSERT INTO `PREFIXmodule_planifie` VALUES (263, 82, 3, 25, 1, 41, 3, '16:00:00', '19:00:00', 0, 0);
INSERT INTO `PREFIXmodule_planifie` VALUES (264, 82, 3, 25, -1, 42, 3, '16:00:00', '19:00:00', 0, 0);
INSERT INTO `PREFIXmodule_planifie` VALUES (265, 82, 3, 25, -1, 43, 3, '16:00:00', '19:00:00', 0, 0);
INSERT INTO `PREFIXmodule_planifie` VALUES (266, 82, 3, 25, -1, 44, 3, '16:00:00', '19:00:00', 0, 0);

-- --------------------------------------------------------

-- 
-- Structure de la table `PREFIXmodule_planifie_diplome`
-- 

DROP TABLE IF EXISTS `PREFIXmodule_planifie_diplome`;
CREATE TABLE `PREFIXmodule_planifie_diplome` (
  `id_planifie` int(10) NOT NULL default '0',
  `id_diplome` int(10) NOT NULL default '0'
) TYPE=MyISAM;

-- 
-- Contenu de la table `PREFIXmodule_planifie_diplome`
-- 

INSERT INTO `PREFIXmodule_planifie_diplome` VALUES (122, 3);
INSERT INTO `PREFIXmodule_planifie_diplome` VALUES (123, 3);
INSERT INTO `PREFIXmodule_planifie_diplome` VALUES (124, 3);
INSERT INTO `PREFIXmodule_planifie_diplome` VALUES (121, 3);
INSERT INTO `PREFIXmodule_planifie_diplome` VALUES (119, 3);
INSERT INTO `PREFIXmodule_planifie_diplome` VALUES (120, 3);
INSERT INTO `PREFIXmodule_planifie_diplome` VALUES (125, 3);
INSERT INTO `PREFIXmodule_planifie_diplome` VALUES (126, 3);
INSERT INTO `PREFIXmodule_planifie_diplome` VALUES (127, 3);
INSERT INTO `PREFIXmodule_planifie_diplome` VALUES (128, 3);
INSERT INTO `PREFIXmodule_planifie_diplome` VALUES (129, 3);
INSERT INTO `PREFIXmodule_planifie_diplome` VALUES (130, 3);
INSERT INTO `PREFIXmodule_planifie_diplome` VALUES (131, 3);
INSERT INTO `PREFIXmodule_planifie_diplome` VALUES (132, 3);
INSERT INTO `PREFIXmodule_planifie_diplome` VALUES (133, 3);
INSERT INTO `PREFIXmodule_planifie_diplome` VALUES (189, 3);
INSERT INTO `PREFIXmodule_planifie_diplome` VALUES (190, 3);
INSERT INTO `PREFIXmodule_planifie_diplome` VALUES (191, 3);
INSERT INTO `PREFIXmodule_planifie_diplome` VALUES (192, 3);
INSERT INTO `PREFIXmodule_planifie_diplome` VALUES (193, 3);
INSERT INTO `PREFIXmodule_planifie_diplome` VALUES (194, 3);
INSERT INTO `PREFIXmodule_planifie_diplome` VALUES (195, 3);
INSERT INTO `PREFIXmodule_planifie_diplome` VALUES (196, 3);
INSERT INTO `PREFIXmodule_planifie_diplome` VALUES (197, 3);
INSERT INTO `PREFIXmodule_planifie_diplome` VALUES (198, 3);
INSERT INTO `PREFIXmodule_planifie_diplome` VALUES (199, 3);
INSERT INTO `PREFIXmodule_planifie_diplome` VALUES (200, 3);
INSERT INTO `PREFIXmodule_planifie_diplome` VALUES (202, 3);
INSERT INTO `PREFIXmodule_planifie_diplome` VALUES (203, 3);
INSERT INTO `PREFIXmodule_planifie_diplome` VALUES (204, 3);
INSERT INTO `PREFIXmodule_planifie_diplome` VALUES (205, 3);

-- --------------------------------------------------------

-- 
-- Structure de la table `PREFIXmodule_planifie_groupe`
-- 

DROP TABLE IF EXISTS `PREFIXmodule_planifie_groupe`;
CREATE TABLE `PREFIXmodule_planifie_groupe` (
  `id_planifie` int(10) NOT NULL default '0',
  `id_groupe` int(10) NOT NULL default '0'
) TYPE=MyISAM;

-- 
-- Contenu de la table `PREFIXmodule_planifie_groupe`
-- 

INSERT INTO `PREFIXmodule_planifie_groupe` VALUES (118, 3);
INSERT INTO `PREFIXmodule_planifie_groupe` VALUES (134, 25);
INSERT INTO `PREFIXmodule_planifie_groupe` VALUES (135, 25);
INSERT INTO `PREFIXmodule_planifie_groupe` VALUES (136, 25);
INSERT INTO `PREFIXmodule_planifie_groupe` VALUES (137, 25);
INSERT INTO `PREFIXmodule_planifie_groupe` VALUES (138, 25);
INSERT INTO `PREFIXmodule_planifie_groupe` VALUES (139, 25);
INSERT INTO `PREFIXmodule_planifie_groupe` VALUES (140, 25);
INSERT INTO `PREFIXmodule_planifie_groupe` VALUES (141, 25);
INSERT INTO `PREFIXmodule_planifie_groupe` VALUES (142, 26);
INSERT INTO `PREFIXmodule_planifie_groupe` VALUES (143, 26);
INSERT INTO `PREFIXmodule_planifie_groupe` VALUES (144, 26);
INSERT INTO `PREFIXmodule_planifie_groupe` VALUES (145, 26);
INSERT INTO `PREFIXmodule_planifie_groupe` VALUES (146, 26);
INSERT INTO `PREFIXmodule_planifie_groupe` VALUES (147, 26);
INSERT INTO `PREFIXmodule_planifie_groupe` VALUES (148, 25);
INSERT INTO `PREFIXmodule_planifie_groupe` VALUES (149, 25);
INSERT INTO `PREFIXmodule_planifie_groupe` VALUES (150, 25);
INSERT INTO `PREFIXmodule_planifie_groupe` VALUES (151, 25);
INSERT INTO `PREFIXmodule_planifie_groupe` VALUES (152, 25);
INSERT INTO `PREFIXmodule_planifie_groupe` VALUES (153, 25);
INSERT INTO `PREFIXmodule_planifie_groupe` VALUES (165, 25);
INSERT INTO `PREFIXmodule_planifie_groupe` VALUES (155, 25);
INSERT INTO `PREFIXmodule_planifie_groupe` VALUES (156, 25);
INSERT INTO `PREFIXmodule_planifie_groupe` VALUES (157, 25);
INSERT INTO `PREFIXmodule_planifie_groupe` VALUES (158, 25);
INSERT INTO `PREFIXmodule_planifie_groupe` VALUES (159, 25);
INSERT INTO `PREFIXmodule_planifie_groupe` VALUES (160, 25);
INSERT INTO `PREFIXmodule_planifie_groupe` VALUES (161, 25);
INSERT INTO `PREFIXmodule_planifie_groupe` VALUES (162, 25);
INSERT INTO `PREFIXmodule_planifie_groupe` VALUES (163, 25);
INSERT INTO `PREFIXmodule_planifie_groupe` VALUES (167, 26);
INSERT INTO `PREFIXmodule_planifie_groupe` VALUES (168, 26);
INSERT INTO `PREFIXmodule_planifie_groupe` VALUES (169, 25);
INSERT INTO `PREFIXmodule_planifie_groupe` VALUES (170, 25);
INSERT INTO `PREFIXmodule_planifie_groupe` VALUES (171, 25);
INSERT INTO `PREFIXmodule_planifie_groupe` VALUES (172, 26);
INSERT INTO `PREFIXmodule_planifie_groupe` VALUES (173, 26);
INSERT INTO `PREFIXmodule_planifie_groupe` VALUES (174, 26);
INSERT INTO `PREFIXmodule_planifie_groupe` VALUES (175, 26);
INSERT INTO `PREFIXmodule_planifie_groupe` VALUES (176, 26);
INSERT INTO `PREFIXmodule_planifie_groupe` VALUES (177, 26);
INSERT INTO `PREFIXmodule_planifie_groupe` VALUES (206, 25);
INSERT INTO `PREFIXmodule_planifie_groupe` VALUES (207, 25);
INSERT INTO `PREFIXmodule_planifie_groupe` VALUES (208, 25);
INSERT INTO `PREFIXmodule_planifie_groupe` VALUES (209, 25);
INSERT INTO `PREFIXmodule_planifie_groupe` VALUES (210, 25);
INSERT INTO `PREFIXmodule_planifie_groupe` VALUES (211, 25);
INSERT INTO `PREFIXmodule_planifie_groupe` VALUES (212, 25);
INSERT INTO `PREFIXmodule_planifie_groupe` VALUES (213, 25);
INSERT INTO `PREFIXmodule_planifie_groupe` VALUES (214, 25);
INSERT INTO `PREFIXmodule_planifie_groupe` VALUES (215, 25);
INSERT INTO `PREFIXmodule_planifie_groupe` VALUES (216, 25);
INSERT INTO `PREFIXmodule_planifie_groupe` VALUES (217, 25);
INSERT INTO `PREFIXmodule_planifie_groupe` VALUES (218, 25);
INSERT INTO `PREFIXmodule_planifie_groupe` VALUES (219, 25);
INSERT INTO `PREFIXmodule_planifie_groupe` VALUES (220, 25);
INSERT INTO `PREFIXmodule_planifie_groupe` VALUES (221, 25);
INSERT INTO `PREFIXmodule_planifie_groupe` VALUES (222, 25);
INSERT INTO `PREFIXmodule_planifie_groupe` VALUES (223, 25);
INSERT INTO `PREFIXmodule_planifie_groupe` VALUES (224, 25);
INSERT INTO `PREFIXmodule_planifie_groupe` VALUES (225, 25);
INSERT INTO `PREFIXmodule_planifie_groupe` VALUES (226, 25);
INSERT INTO `PREFIXmodule_planifie_groupe` VALUES (227, 25);
INSERT INTO `PREFIXmodule_planifie_groupe` VALUES (228, 26);
INSERT INTO `PREFIXmodule_planifie_groupe` VALUES (229, 26);
INSERT INTO `PREFIXmodule_planifie_groupe` VALUES (230, 26);
INSERT INTO `PREFIXmodule_planifie_groupe` VALUES (231, 26);
INSERT INTO `PREFIXmodule_planifie_groupe` VALUES (232, 26);
INSERT INTO `PREFIXmodule_planifie_groupe` VALUES (233, 26);
INSERT INTO `PREFIXmodule_planifie_groupe` VALUES (234, 26);
INSERT INTO `PREFIXmodule_planifie_groupe` VALUES (235, 26);
INSERT INTO `PREFIXmodule_planifie_groupe` VALUES (236, 26);
INSERT INTO `PREFIXmodule_planifie_groupe` VALUES (237, 26);
INSERT INTO `PREFIXmodule_planifie_groupe` VALUES (238, 26);
INSERT INTO `PREFIXmodule_planifie_groupe` VALUES (239, 26);
INSERT INTO `PREFIXmodule_planifie_groupe` VALUES (240, 26);
INSERT INTO `PREFIXmodule_planifie_groupe` VALUES (241, 26);
INSERT INTO `PREFIXmodule_planifie_groupe` VALUES (242, 26);
INSERT INTO `PREFIXmodule_planifie_groupe` VALUES (243, 26);
INSERT INTO `PREFIXmodule_planifie_groupe` VALUES (244, 26);
INSERT INTO `PREFIXmodule_planifie_groupe` VALUES (245, 26);
INSERT INTO `PREFIXmodule_planifie_groupe` VALUES (246, 26);
INSERT INTO `PREFIXmodule_planifie_groupe` VALUES (247, 26);
INSERT INTO `PREFIXmodule_planifie_groupe` VALUES (248, 26);
INSERT INTO `PREFIXmodule_planifie_groupe` VALUES (249, 26);
INSERT INTO `PREFIXmodule_planifie_groupe` VALUES (250, 26);
INSERT INTO `PREFIXmodule_planifie_groupe` VALUES (251, 26);
INSERT INTO `PREFIXmodule_planifie_groupe` VALUES (252, 26);
INSERT INTO `PREFIXmodule_planifie_groupe` VALUES (253, 26);
INSERT INTO `PREFIXmodule_planifie_groupe` VALUES (254, 26);
INSERT INTO `PREFIXmodule_planifie_groupe` VALUES (255, 26);
INSERT INTO `PREFIXmodule_planifie_groupe` VALUES (256, 26);
INSERT INTO `PREFIXmodule_planifie_groupe` VALUES (257, 26);
INSERT INTO `PREFIXmodule_planifie_groupe` VALUES (258, 26);
INSERT INTO `PREFIXmodule_planifie_groupe` VALUES (259, 26);
INSERT INTO `PREFIXmodule_planifie_groupe` VALUES (260, 26);
INSERT INTO `PREFIXmodule_planifie_groupe` VALUES (261, 25);
INSERT INTO `PREFIXmodule_planifie_groupe` VALUES (262, 25);
INSERT INTO `PREFIXmodule_planifie_groupe` VALUES (263, 25);
INSERT INTO `PREFIXmodule_planifie_groupe` VALUES (264, 25);
INSERT INTO `PREFIXmodule_planifie_groupe` VALUES (265, 25);
INSERT INTO `PREFIXmodule_planifie_groupe` VALUES (266, 25);

-- --------------------------------------------------------

-- 
-- Structure de la table `PREFIXmodule_planifie_option`
-- 

DROP TABLE IF EXISTS `PREFIXmodule_planifie_option`;
CREATE TABLE `PREFIXmodule_planifie_option` (
  `id_planifie` int(10) NOT NULL default '0',
  `id_option` int(10) NOT NULL default '0'
) TYPE=MyISAM;

-- 
-- Contenu de la table `PREFIXmodule_planifie_option`
-- 

INSERT INTO `PREFIXmodule_planifie_option` VALUES (178, 13);
INSERT INTO `PREFIXmodule_planifie_option` VALUES (179, 13);
INSERT INTO `PREFIXmodule_planifie_option` VALUES (180, 13);
INSERT INTO `PREFIXmodule_planifie_option` VALUES (181, 13);
INSERT INTO `PREFIXmodule_planifie_option` VALUES (182, 13);
INSERT INTO `PREFIXmodule_planifie_option` VALUES (183, 13);
INSERT INTO `PREFIXmodule_planifie_option` VALUES (184, 13);
INSERT INTO `PREFIXmodule_planifie_option` VALUES (185, 13);
INSERT INTO `PREFIXmodule_planifie_option` VALUES (186, 13);
INSERT INTO `PREFIXmodule_planifie_option` VALUES (187, 13);

-- --------------------------------------------------------

-- 
-- Structure de la table `PREFIXmodule_reparti`
-- 

DROP TABLE IF EXISTS `PREFIXmodule_reparti`;
CREATE TABLE `PREFIXmodule_reparti` (
  `id` int(10) NOT NULL auto_increment,
  `semaine` int(2) NOT NULL default '0',
  `nombre_heures` float NOT NULL default '0',
  `id_type_seance` int(10) NOT NULL default '0',
  `id_module` int(10) NOT NULL default '0',
  `id_groupe` int(10) default NULL,
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=233 ;

-- 
-- Contenu de la table `PREFIXmodule_reparti`
-- 

INSERT INTO `PREFIXmodule_reparti` VALUES (1, 38, 2, 1, 78, 0);
INSERT INTO `PREFIXmodule_reparti` VALUES (6, 39, 2, 1, 78, 0);
INSERT INTO `PREFIXmodule_reparti` VALUES (5, 38, 2, 1, 79, 0);
INSERT INTO `PREFIXmodule_reparti` VALUES (7, 40, 2, 1, 78, 0);
INSERT INTO `PREFIXmodule_reparti` VALUES (8, 41, 2, 1, 78, 0);
INSERT INTO `PREFIXmodule_reparti` VALUES (9, 39, 2, 1, 79, 0);
INSERT INTO `PREFIXmodule_reparti` VALUES (10, 40, 2, 1, 79, 0);
INSERT INTO `PREFIXmodule_reparti` VALUES (11, 41, 2, 1, 79, 0);
INSERT INTO `PREFIXmodule_reparti` VALUES (12, 42, 2, 1, 79, 0);
INSERT INTO `PREFIXmodule_reparti` VALUES (13, 43, 2, 1, 79, 0);
INSERT INTO `PREFIXmodule_reparti` VALUES (14, 44, 3, 1, 79, 0);
INSERT INTO `PREFIXmodule_reparti` VALUES (17, 42, 2, 1, 78, 0);
INSERT INTO `PREFIXmodule_reparti` VALUES (18, 43, 2, 1, 78, 0);
INSERT INTO `PREFIXmodule_reparti` VALUES (19, 44, 2, 1, 78, 0);
INSERT INTO `PREFIXmodule_reparti` VALUES (20, 45, 2, 1, 78, 0);
INSERT INTO `PREFIXmodule_reparti` VALUES (21, 46, 2, 1, 78, 0);
INSERT INTO `PREFIXmodule_reparti` VALUES (22, 38, 2, 1, 80, 0);
INSERT INTO `PREFIXmodule_reparti` VALUES (23, 39, 2, 1, 80, 0);
INSERT INTO `PREFIXmodule_reparti` VALUES (28, 45, 2, 1, 80, 0);
INSERT INTO `PREFIXmodule_reparti` VALUES (27, 44, 2, 1, 80, 0);
INSERT INTO `PREFIXmodule_reparti` VALUES (29, 38, 4, 1, 81, 0);
INSERT INTO `PREFIXmodule_reparti` VALUES (31, 40, 2, 1, 81, 0);
INSERT INTO `PREFIXmodule_reparti` VALUES (32, 41, 2, 1, 81, 0);
INSERT INTO `PREFIXmodule_reparti` VALUES (33, 42, 2, 1, 81, 0);
INSERT INTO `PREFIXmodule_reparti` VALUES (34, 43, 2, 1, 81, 0);
INSERT INTO `PREFIXmodule_reparti` VALUES (35, 38, 2, 1, 82, 0);
INSERT INTO `PREFIXmodule_reparti` VALUES (36, 39, 2, 1, 82, 0);
INSERT INTO `PREFIXmodule_reparti` VALUES (37, 40, 2, 1, 82, 0);
INSERT INTO `PREFIXmodule_reparti` VALUES (38, 41, 2, 1, 82, 0);
INSERT INTO `PREFIXmodule_reparti` VALUES (39, 42, 2, 1, 82, 0);
INSERT INTO `PREFIXmodule_reparti` VALUES (40, 43, 2, 1, 82, 0);
INSERT INTO `PREFIXmodule_reparti` VALUES (41, 38, 2, 1, 84, 0);
INSERT INTO `PREFIXmodule_reparti` VALUES (53, 38, 2, 2, 78, 25);
INSERT INTO `PREFIXmodule_reparti` VALUES (54, 38, 2, 2, 78, 26);
INSERT INTO `PREFIXmodule_reparti` VALUES (88, 46, 3, 3, 78, 26);
INSERT INTO `PREFIXmodule_reparti` VALUES (87, 46, 3, 3, 78, 25);
INSERT INTO `PREFIXmodule_reparti` VALUES (57, 39, 2, 2, 78, 25);
INSERT INTO `PREFIXmodule_reparti` VALUES (58, 39, 2, 2, 78, 26);
INSERT INTO `PREFIXmodule_reparti` VALUES (59, 39, 3, 3, 78, 25);
INSERT INTO `PREFIXmodule_reparti` VALUES (60, 39, 3, 3, 78, 26);
INSERT INTO `PREFIXmodule_reparti` VALUES (61, 40, 2, 2, 78, 25);
INSERT INTO `PREFIXmodule_reparti` VALUES (63, 40, 3, 3, 78, 25);
INSERT INTO `PREFIXmodule_reparti` VALUES (62, 40, 2, 2, 78, 26);
INSERT INTO `PREFIXmodule_reparti` VALUES (64, 40, 3, 3, 78, 26);
INSERT INTO `PREFIXmodule_reparti` VALUES (65, 41, 2, 2, 78, 25);
INSERT INTO `PREFIXmodule_reparti` VALUES (66, 41, 2, 2, 78, 26);
INSERT INTO `PREFIXmodule_reparti` VALUES (67, 41, 3, 3, 78, 25);
INSERT INTO `PREFIXmodule_reparti` VALUES (68, 41, 3, 3, 78, 26);
INSERT INTO `PREFIXmodule_reparti` VALUES (69, 42, 2, 2, 78, 25);
INSERT INTO `PREFIXmodule_reparti` VALUES (70, 42, 2, 2, 78, 26);
INSERT INTO `PREFIXmodule_reparti` VALUES (71, 42, 3, 3, 78, 25);
INSERT INTO `PREFIXmodule_reparti` VALUES (72, 42, 3, 3, 78, 26);
INSERT INTO `PREFIXmodule_reparti` VALUES (73, 43, 2, 2, 78, 25);
INSERT INTO `PREFIXmodule_reparti` VALUES (74, 43, 2, 2, 78, 26);
INSERT INTO `PREFIXmodule_reparti` VALUES (75, 43, 3, 3, 78, 25);
INSERT INTO `PREFIXmodule_reparti` VALUES (76, 43, 3, 3, 78, 26);
INSERT INTO `PREFIXmodule_reparti` VALUES (77, 44, 2, 2, 78, 25);
INSERT INTO `PREFIXmodule_reparti` VALUES (78, 44, 2, 2, 78, 26);
INSERT INTO `PREFIXmodule_reparti` VALUES (79, 44, 3, 3, 78, 25);
INSERT INTO `PREFIXmodule_reparti` VALUES (80, 44, 3, 3, 78, 26);
INSERT INTO `PREFIXmodule_reparti` VALUES (81, 45, 2, 2, 78, 25);
INSERT INTO `PREFIXmodule_reparti` VALUES (82, 45, 2, 2, 78, 26);
INSERT INTO `PREFIXmodule_reparti` VALUES (83, 45, 3, 3, 78, 25);
INSERT INTO `PREFIXmodule_reparti` VALUES (84, 45, 3, 3, 78, 26);
INSERT INTO `PREFIXmodule_reparti` VALUES (85, 46, 2, 2, 78, 25);
INSERT INTO `PREFIXmodule_reparti` VALUES (86, 46, 2, 2, 78, 26);
INSERT INTO `PREFIXmodule_reparti` VALUES (89, 38, 2, 2, 79, 25);
INSERT INTO `PREFIXmodule_reparti` VALUES (90, 38, 2, 2, 79, 26);
INSERT INTO `PREFIXmodule_reparti` VALUES (120, 45, 3, 3, 79, 26);
INSERT INTO `PREFIXmodule_reparti` VALUES (119, 45, 3, 3, 79, 25);
INSERT INTO `PREFIXmodule_reparti` VALUES (93, 39, 2, 2, 79, 25);
INSERT INTO `PREFIXmodule_reparti` VALUES (94, 39, 2, 2, 79, 26);
INSERT INTO `PREFIXmodule_reparti` VALUES (118, 44, 3, 3, 79, 26);
INSERT INTO `PREFIXmodule_reparti` VALUES (117, 44, 3, 3, 79, 25);
INSERT INTO `PREFIXmodule_reparti` VALUES (97, 40, 2, 2, 79, 25);
INSERT INTO `PREFIXmodule_reparti` VALUES (98, 40, 2, 2, 79, 26);
INSERT INTO `PREFIXmodule_reparti` VALUES (99, 41, 2, 2, 79, 25);
INSERT INTO `PREFIXmodule_reparti` VALUES (100, 41, 2, 2, 79, 26);
INSERT INTO `PREFIXmodule_reparti` VALUES (116, 43, 3, 3, 79, 26);
INSERT INTO `PREFIXmodule_reparti` VALUES (115, 43, 3, 3, 79, 25);
INSERT INTO `PREFIXmodule_reparti` VALUES (103, 42, 2, 2, 79, 25);
INSERT INTO `PREFIXmodule_reparti` VALUES (104, 42, 2, 2, 79, 26);
INSERT INTO `PREFIXmodule_reparti` VALUES (122, 46, 3, 3, 79, 26);
INSERT INTO `PREFIXmodule_reparti` VALUES (121, 46, 3, 3, 79, 25);
INSERT INTO `PREFIXmodule_reparti` VALUES (107, 43, 2, 2, 79, 25);
INSERT INTO `PREFIXmodule_reparti` VALUES (108, 43, 2, 2, 79, 26);
INSERT INTO `PREFIXmodule_reparti` VALUES (109, 44, 3, 2, 79, 25);
INSERT INTO `PREFIXmodule_reparti` VALUES (110, 44, 3, 2, 79, 26);
INSERT INTO `PREFIXmodule_reparti` VALUES (124, 38, 2, 2, 81, 26);
INSERT INTO `PREFIXmodule_reparti` VALUES (123, 38, 2, 2, 81, 25);
INSERT INTO `PREFIXmodule_reparti` VALUES (126, 39, 2, 2, 81, 26);
INSERT INTO `PREFIXmodule_reparti` VALUES (125, 39, 2, 2, 81, 25);
INSERT INTO `PREFIXmodule_reparti` VALUES (127, 40, 2, 2, 81, 25);
INSERT INTO `PREFIXmodule_reparti` VALUES (128, 40, 2, 2, 81, 26);
INSERT INTO `PREFIXmodule_reparti` VALUES (129, 41, 2, 2, 81, 25);
INSERT INTO `PREFIXmodule_reparti` VALUES (130, 41, 2, 2, 81, 26);
INSERT INTO `PREFIXmodule_reparti` VALUES (131, 42, 2, 2, 81, 25);
INSERT INTO `PREFIXmodule_reparti` VALUES (132, 42, 2, 2, 81, 26);
INSERT INTO `PREFIXmodule_reparti` VALUES (133, 43, 2, 2, 81, 25);
INSERT INTO `PREFIXmodule_reparti` VALUES (134, 43, 2, 2, 81, 26);
INSERT INTO `PREFIXmodule_reparti` VALUES (135, 39, 3, 3, 81, 25);
INSERT INTO `PREFIXmodule_reparti` VALUES (136, 39, 3, 3, 81, 26);
INSERT INTO `PREFIXmodule_reparti` VALUES (137, 40, 3, 3, 81, 25);
INSERT INTO `PREFIXmodule_reparti` VALUES (138, 40, 3, 3, 81, 26);
INSERT INTO `PREFIXmodule_reparti` VALUES (139, 41, 3, 3, 81, 25);
INSERT INTO `PREFIXmodule_reparti` VALUES (140, 41, 3, 3, 81, 26);
INSERT INTO `PREFIXmodule_reparti` VALUES (141, 42, 3, 3, 81, 25);
INSERT INTO `PREFIXmodule_reparti` VALUES (142, 42, 3, 3, 81, 26);
INSERT INTO `PREFIXmodule_reparti` VALUES (143, 43, 3, 3, 81, 25);
INSERT INTO `PREFIXmodule_reparti` VALUES (144, 43, 3, 3, 81, 26);
INSERT INTO `PREFIXmodule_reparti` VALUES (145, 44, 3, 3, 81, 25);
INSERT INTO `PREFIXmodule_reparti` VALUES (146, 44, 3, 3, 81, 26);
INSERT INTO `PREFIXmodule_reparti` VALUES (147, 38, 2, 2, 82, 25);
INSERT INTO `PREFIXmodule_reparti` VALUES (148, 38, 2, 2, 82, 26);
INSERT INTO `PREFIXmodule_reparti` VALUES (149, 39, 2, 2, 82, 25);
INSERT INTO `PREFIXmodule_reparti` VALUES (150, 39, 2, 2, 82, 26);
INSERT INTO `PREFIXmodule_reparti` VALUES (151, 40, 2, 2, 82, 25);
INSERT INTO `PREFIXmodule_reparti` VALUES (152, 40, 2, 2, 82, 26);
INSERT INTO `PREFIXmodule_reparti` VALUES (153, 41, 2, 2, 82, 25);
INSERT INTO `PREFIXmodule_reparti` VALUES (154, 41, 2, 2, 82, 26);
INSERT INTO `PREFIXmodule_reparti` VALUES (155, 42, 2, 2, 82, 25);
INSERT INTO `PREFIXmodule_reparti` VALUES (156, 42, 2, 2, 82, 26);
INSERT INTO `PREFIXmodule_reparti` VALUES (157, 43, 2, 2, 82, 25);
INSERT INTO `PREFIXmodule_reparti` VALUES (158, 43, 2, 2, 82, 26);
INSERT INTO `PREFIXmodule_reparti` VALUES (159, 39, 3, 3, 82, 25);
INSERT INTO `PREFIXmodule_reparti` VALUES (160, 39, 3, 3, 82, 26);
INSERT INTO `PREFIXmodule_reparti` VALUES (161, 40, 3, 3, 82, 25);
INSERT INTO `PREFIXmodule_reparti` VALUES (162, 40, 3, 3, 82, 26);
INSERT INTO `PREFIXmodule_reparti` VALUES (163, 41, 3, 3, 82, 25);
INSERT INTO `PREFIXmodule_reparti` VALUES (164, 41, 3, 3, 82, 26);
INSERT INTO `PREFIXmodule_reparti` VALUES (165, 42, 3, 3, 82, 25);
INSERT INTO `PREFIXmodule_reparti` VALUES (166, 42, 3, 3, 82, 26);
INSERT INTO `PREFIXmodule_reparti` VALUES (167, 43, 3, 3, 82, 25);
INSERT INTO `PREFIXmodule_reparti` VALUES (168, 43, 3, 3, 82, 26);
INSERT INTO `PREFIXmodule_reparti` VALUES (169, 44, 3, 3, 82, 25);
INSERT INTO `PREFIXmodule_reparti` VALUES (170, 44, 3, 3, 82, 26);
INSERT INTO `PREFIXmodule_reparti` VALUES (171, 38, 2, 2, 83, 25);
INSERT INTO `PREFIXmodule_reparti` VALUES (172, 38, 2, 2, 83, 26);
INSERT INTO `PREFIXmodule_reparti` VALUES (173, 37, 2, 2, 83, 25);
INSERT INTO `PREFIXmodule_reparti` VALUES (174, 37, 2, 2, 83, 26);
INSERT INTO `PREFIXmodule_reparti` VALUES (175, 39, 2, 2, 83, 25);
INSERT INTO `PREFIXmodule_reparti` VALUES (176, 39, 2, 2, 83, 26);
INSERT INTO `PREFIXmodule_reparti` VALUES (177, 40, 2, 2, 83, 25);
INSERT INTO `PREFIXmodule_reparti` VALUES (178, 40, 2, 2, 83, 26);
INSERT INTO `PREFIXmodule_reparti` VALUES (179, 41, 2, 2, 83, 25);
INSERT INTO `PREFIXmodule_reparti` VALUES (180, 41, 2, 2, 83, 26);
INSERT INTO `PREFIXmodule_reparti` VALUES (181, 42, 2, 2, 83, 25);
INSERT INTO `PREFIXmodule_reparti` VALUES (182, 42, 2, 2, 83, 26);
INSERT INTO `PREFIXmodule_reparti` VALUES (183, 43, 2, 2, 83, 25);
INSERT INTO `PREFIXmodule_reparti` VALUES (184, 43, 2, 2, 83, 26);
INSERT INTO `PREFIXmodule_reparti` VALUES (185, 44, 2, 2, 83, 25);
INSERT INTO `PREFIXmodule_reparti` VALUES (186, 44, 2, 2, 83, 26);
INSERT INTO `PREFIXmodule_reparti` VALUES (187, 45, 2, 2, 83, 25);
INSERT INTO `PREFIXmodule_reparti` VALUES (188, 45, 2, 2, 83, 26);
INSERT INTO `PREFIXmodule_reparti` VALUES (189, 46, 2, 2, 83, 25);
INSERT INTO `PREFIXmodule_reparti` VALUES (190, 46, 2, 2, 83, 26);
INSERT INTO `PREFIXmodule_reparti` VALUES (191, 47, 2, 2, 83, 25);
INSERT INTO `PREFIXmodule_reparti` VALUES (192, 47, 2, 2, 83, 26);
INSERT INTO `PREFIXmodule_reparti` VALUES (193, 48, 3, 2, 83, 25);
INSERT INTO `PREFIXmodule_reparti` VALUES (194, 48, 3, 2, 83, 26);
INSERT INTO `PREFIXmodule_reparti` VALUES (200, 38, 2, 2, 84, 26);
INSERT INTO `PREFIXmodule_reparti` VALUES (199, 38, 2, 2, 84, 25);
INSERT INTO `PREFIXmodule_reparti` VALUES (202, 39, 2, 2, 84, 26);
INSERT INTO `PREFIXmodule_reparti` VALUES (201, 39, 2, 2, 84, 25);
INSERT INTO `PREFIXmodule_reparti` VALUES (203, 40, 2, 2, 84, 25);
INSERT INTO `PREFIXmodule_reparti` VALUES (204, 40, 2, 2, 84, 26);
INSERT INTO `PREFIXmodule_reparti` VALUES (205, 41, 2, 2, 84, 25);
INSERT INTO `PREFIXmodule_reparti` VALUES (206, 41, 2, 2, 84, 26);
INSERT INTO `PREFIXmodule_reparti` VALUES (207, 42, 2, 2, 84, 25);
INSERT INTO `PREFIXmodule_reparti` VALUES (208, 42, 2, 2, 84, 26);
INSERT INTO `PREFIXmodule_reparti` VALUES (209, 43, 2, 2, 84, 25);
INSERT INTO `PREFIXmodule_reparti` VALUES (210, 43, 2, 2, 84, 26);
INSERT INTO `PREFIXmodule_reparti` VALUES (211, 44, 2, 2, 84, 25);
INSERT INTO `PREFIXmodule_reparti` VALUES (212, 44, 2, 2, 84, 26);
INSERT INTO `PREFIXmodule_reparti` VALUES (216, 50, 2, 4, 79, 0);
INSERT INTO `PREFIXmodule_reparti` VALUES (215, 50, 2, 4, 78, 0);
INSERT INTO `PREFIXmodule_reparti` VALUES (217, 50, 2, 4, 80, 0);
INSERT INTO `PREFIXmodule_reparti` VALUES (218, 50, 2, 4, 81, 0);
INSERT INTO `PREFIXmodule_reparti` VALUES (219, 50, 2, 4, 82, 0);
INSERT INTO `PREFIXmodule_reparti` VALUES (220, 50, 2, 4, 83, 0);
INSERT INTO `PREFIXmodule_reparti` VALUES (221, 50, 2, 4, 84, 0);
INSERT INTO `PREFIXmodule_reparti` VALUES (222, 38, 2, 2, 94, 0);
INSERT INTO `PREFIXmodule_reparti` VALUES (223, 39, 2, 2, 94, 0);
INSERT INTO `PREFIXmodule_reparti` VALUES (224, 40, 2, 2, 94, 0);
INSERT INTO `PREFIXmodule_reparti` VALUES (225, 41, 2, 2, 94, 0);
INSERT INTO `PREFIXmodule_reparti` VALUES (226, 42, 2, 2, 94, 0);
INSERT INTO `PREFIXmodule_reparti` VALUES (227, 43, 2, 2, 94, 0);
INSERT INTO `PREFIXmodule_reparti` VALUES (228, 44, 2, 2, 94, 0);
INSERT INTO `PREFIXmodule_reparti` VALUES (229, 45, 2, 2, 94, 0);
INSERT INTO `PREFIXmodule_reparti` VALUES (230, 46, 2, 2, 94, 0);
INSERT INTO `PREFIXmodule_reparti` VALUES (231, 37, 2, 2, 94, 0);
INSERT INTO `PREFIXmodule_reparti` VALUES (232, 38, 1, 4, 78, 0);

-- --------------------------------------------------------

-- 
-- Structure de la table `PREFIXmodule_suivi_diplome`
-- 

DROP TABLE IF EXISTS `PREFIXmodule_suivi_diplome`;
CREATE TABLE `PREFIXmodule_suivi_diplome` (
  `id_module` int(10) unsigned NOT NULL default '0',
  `id_diplome` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id_module`,`id_diplome`)
) TYPE=MyISAM;

-- 
-- Contenu de la table `PREFIXmodule_suivi_diplome`
-- 

INSERT INTO `PREFIXmodule_suivi_diplome` VALUES (0, 2);
INSERT INTO `PREFIXmodule_suivi_diplome` VALUES (1, 2);
INSERT INTO `PREFIXmodule_suivi_diplome` VALUES (4, 2);
INSERT INTO `PREFIXmodule_suivi_diplome` VALUES (5, 2);
INSERT INTO `PREFIXmodule_suivi_diplome` VALUES (6, 2);
INSERT INTO `PREFIXmodule_suivi_diplome` VALUES (7, 2);
INSERT INTO `PREFIXmodule_suivi_diplome` VALUES (8, 2);
INSERT INTO `PREFIXmodule_suivi_diplome` VALUES (9, 2);
INSERT INTO `PREFIXmodule_suivi_diplome` VALUES (10, 2);
INSERT INTO `PREFIXmodule_suivi_diplome` VALUES (11, 2);
INSERT INTO `PREFIXmodule_suivi_diplome` VALUES (12, 2);
INSERT INTO `PREFIXmodule_suivi_diplome` VALUES (13, 2);
INSERT INTO `PREFIXmodule_suivi_diplome` VALUES (14, 2);
INSERT INTO `PREFIXmodule_suivi_diplome` VALUES (15, 2);
INSERT INTO `PREFIXmodule_suivi_diplome` VALUES (16, 1);
INSERT INTO `PREFIXmodule_suivi_diplome` VALUES (17, 2);
INSERT INTO `PREFIXmodule_suivi_diplome` VALUES (18, 10);
INSERT INTO `PREFIXmodule_suivi_diplome` VALUES (19, 10);
INSERT INTO `PREFIXmodule_suivi_diplome` VALUES (20, 10);
INSERT INTO `PREFIXmodule_suivi_diplome` VALUES (21, 10);
INSERT INTO `PREFIXmodule_suivi_diplome` VALUES (22, 10);
INSERT INTO `PREFIXmodule_suivi_diplome` VALUES (24, 10);
INSERT INTO `PREFIXmodule_suivi_diplome` VALUES (25, 10);
INSERT INTO `PREFIXmodule_suivi_diplome` VALUES (26, 10);
INSERT INTO `PREFIXmodule_suivi_diplome` VALUES (27, 10);
INSERT INTO `PREFIXmodule_suivi_diplome` VALUES (28, 10);
INSERT INTO `PREFIXmodule_suivi_diplome` VALUES (29, 10);
INSERT INTO `PREFIXmodule_suivi_diplome` VALUES (30, 10);
INSERT INTO `PREFIXmodule_suivi_diplome` VALUES (32, 2);
INSERT INTO `PREFIXmodule_suivi_diplome` VALUES (33, 2);
INSERT INTO `PREFIXmodule_suivi_diplome` VALUES (34, 2);
INSERT INTO `PREFIXmodule_suivi_diplome` VALUES (36, 5);
INSERT INTO `PREFIXmodule_suivi_diplome` VALUES (37, 5);
INSERT INTO `PREFIXmodule_suivi_diplome` VALUES (38, 5);
INSERT INTO `PREFIXmodule_suivi_diplome` VALUES (39, 5);
INSERT INTO `PREFIXmodule_suivi_diplome` VALUES (40, 5);
INSERT INTO `PREFIXmodule_suivi_diplome` VALUES (41, 5);
INSERT INTO `PREFIXmodule_suivi_diplome` VALUES (42, 5);
INSERT INTO `PREFIXmodule_suivi_diplome` VALUES (43, 5);
INSERT INTO `PREFIXmodule_suivi_diplome` VALUES (44, 5);
INSERT INTO `PREFIXmodule_suivi_diplome` VALUES (45, 5);
INSERT INTO `PREFIXmodule_suivi_diplome` VALUES (46, 5);
INSERT INTO `PREFIXmodule_suivi_diplome` VALUES (47, 5);
INSERT INTO `PREFIXmodule_suivi_diplome` VALUES (48, 5);
INSERT INTO `PREFIXmodule_suivi_diplome` VALUES (49, 5);
INSERT INTO `PREFIXmodule_suivi_diplome` VALUES (50, 5);
INSERT INTO `PREFIXmodule_suivi_diplome` VALUES (51, 1);
INSERT INTO `PREFIXmodule_suivi_diplome` VALUES (52, 1);
INSERT INTO `PREFIXmodule_suivi_diplome` VALUES (53, 1);
INSERT INTO `PREFIXmodule_suivi_diplome` VALUES (54, 1);
INSERT INTO `PREFIXmodule_suivi_diplome` VALUES (55, 1);
INSERT INTO `PREFIXmodule_suivi_diplome` VALUES (56, 1);
INSERT INTO `PREFIXmodule_suivi_diplome` VALUES (57, 1);
INSERT INTO `PREFIXmodule_suivi_diplome` VALUES (58, 1);
INSERT INTO `PREFIXmodule_suivi_diplome` VALUES (63, 2);
INSERT INTO `PREFIXmodule_suivi_diplome` VALUES (64, 2);
INSERT INTO `PREFIXmodule_suivi_diplome` VALUES (65, 1);
INSERT INTO `PREFIXmodule_suivi_diplome` VALUES (66, 1);
INSERT INTO `PREFIXmodule_suivi_diplome` VALUES (67, 1);
INSERT INTO `PREFIXmodule_suivi_diplome` VALUES (68, 1);
INSERT INTO `PREFIXmodule_suivi_diplome` VALUES (69, 1);
INSERT INTO `PREFIXmodule_suivi_diplome` VALUES (70, 1);
INSERT INTO `PREFIXmodule_suivi_diplome` VALUES (71, 1);
INSERT INTO `PREFIXmodule_suivi_diplome` VALUES (72, 1);
INSERT INTO `PREFIXmodule_suivi_diplome` VALUES (73, 2);
INSERT INTO `PREFIXmodule_suivi_diplome` VALUES (74, 2);
INSERT INTO `PREFIXmodule_suivi_diplome` VALUES (75, 2);
INSERT INTO `PREFIXmodule_suivi_diplome` VALUES (76, 2);
INSERT INTO `PREFIXmodule_suivi_diplome` VALUES (77, 2);
INSERT INTO `PREFIXmodule_suivi_diplome` VALUES (78, 3);
INSERT INTO `PREFIXmodule_suivi_diplome` VALUES (79, 3);
INSERT INTO `PREFIXmodule_suivi_diplome` VALUES (80, 3);
INSERT INTO `PREFIXmodule_suivi_diplome` VALUES (81, 3);
INSERT INTO `PREFIXmodule_suivi_diplome` VALUES (82, 3);
INSERT INTO `PREFIXmodule_suivi_diplome` VALUES (83, 3);
INSERT INTO `PREFIXmodule_suivi_diplome` VALUES (84, 3);
INSERT INTO `PREFIXmodule_suivi_diplome` VALUES (85, 3);
INSERT INTO `PREFIXmodule_suivi_diplome` VALUES (86, 3);
INSERT INTO `PREFIXmodule_suivi_diplome` VALUES (87, 3);
INSERT INTO `PREFIXmodule_suivi_diplome` VALUES (88, 3);
INSERT INTO `PREFIXmodule_suivi_diplome` VALUES (89, 3);
INSERT INTO `PREFIXmodule_suivi_diplome` VALUES (90, 3);
INSERT INTO `PREFIXmodule_suivi_diplome` VALUES (91, 3);
INSERT INTO `PREFIXmodule_suivi_diplome` VALUES (92, 3);
INSERT INTO `PREFIXmodule_suivi_diplome` VALUES (93, 3);
INSERT INTO `PREFIXmodule_suivi_diplome` VALUES (98, 7);
INSERT INTO `PREFIXmodule_suivi_diplome` VALUES (99, 7);
INSERT INTO `PREFIXmodule_suivi_diplome` VALUES (100, 7);
INSERT INTO `PREFIXmodule_suivi_diplome` VALUES (101, 7);
INSERT INTO `PREFIXmodule_suivi_diplome` VALUES (102, 7);
INSERT INTO `PREFIXmodule_suivi_diplome` VALUES (103, 7);
INSERT INTO `PREFIXmodule_suivi_diplome` VALUES (104, 7);
INSERT INTO `PREFIXmodule_suivi_diplome` VALUES (105, 7);
INSERT INTO `PREFIXmodule_suivi_diplome` VALUES (106, 7);
INSERT INTO `PREFIXmodule_suivi_diplome` VALUES (107, 7);
INSERT INTO `PREFIXmodule_suivi_diplome` VALUES (108, 7);

-- --------------------------------------------------------

-- 
-- Structure de la table `PREFIXmodule_suivi_groupe_virtuel`
-- 

DROP TABLE IF EXISTS `PREFIXmodule_suivi_groupe_virtuel`;
CREATE TABLE `PREFIXmodule_suivi_groupe_virtuel` (
  `id_module` int(10) unsigned NOT NULL default '0',
  `id_groupe_virtuel` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id_module`,`id_groupe_virtuel`)
) TYPE=MyISAM;

-- 
-- Contenu de la table `PREFIXmodule_suivi_groupe_virtuel`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `PREFIXmodule_suivi_option`
-- 

DROP TABLE IF EXISTS `PREFIXmodule_suivi_option`;
CREATE TABLE `PREFIXmodule_suivi_option` (
  `id_module` int(10) unsigned NOT NULL default '0',
  `id_option` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id_module`,`id_option`)
) TYPE=MyISAM;

-- 
-- Contenu de la table `PREFIXmodule_suivi_option`
-- 

INSERT INTO `PREFIXmodule_suivi_option` VALUES (17, 2);
INSERT INTO `PREFIXmodule_suivi_option` VALUES (31, 8);
INSERT INTO `PREFIXmodule_suivi_option` VALUES (35, 9);
INSERT INTO `PREFIXmodule_suivi_option` VALUES (59, 11);
INSERT INTO `PREFIXmodule_suivi_option` VALUES (60, 11);
INSERT INTO `PREFIXmodule_suivi_option` VALUES (61, 10);
INSERT INTO `PREFIXmodule_suivi_option` VALUES (62, 10);
INSERT INTO `PREFIXmodule_suivi_option` VALUES (94, 13);
INSERT INTO `PREFIXmodule_suivi_option` VALUES (95, 13);
INSERT INTO `PREFIXmodule_suivi_option` VALUES (96, 12);
INSERT INTO `PREFIXmodule_suivi_option` VALUES (97, 12);

-- --------------------------------------------------------

-- 
-- Structure de la table `PREFIXniveau`
-- 

DROP TABLE IF EXISTS `PREFIXniveau`;
CREATE TABLE `PREFIXniveau` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `libelle` varchar(50) NOT NULL default '',
  `nombre_annees` tinyint(2) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=5 ;

-- 
-- Contenu de la table `PREFIXniveau`
-- 

INSERT INTO `PREFIXniveau` VALUES (1, 'Licence', 3);
INSERT INTO `PREFIXniveau` VALUES (3, 'Doctorat', 3);
INSERT INTO `PREFIXniveau` VALUES (2, 'Master', 2);

-- --------------------------------------------------------

-- 
-- Structure de la table `PREFIXoption`
-- 

DROP TABLE IF EXISTS `PREFIXoption`;
CREATE TABLE `PREFIXoption` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `nom` varchar(50) NOT NULL default '',
  `id_diplome` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `id_diplome` (`id_diplome`)
) TYPE=MyISAM AUTO_INCREMENT=14 ;

-- 
-- Contenu de la table `PREFIXoption`
-- 

INSERT INTO `PREFIXoption` VALUES (11, 'Allemand', 1);
INSERT INTO `PREFIXoption` VALUES (10, 'Espagnol', 1);
INSERT INTO `PREFIXoption` VALUES (8, 'Allemand', 2);
INSERT INTO `PREFIXoption` VALUES (9, 'Espagnol', 2);
INSERT INTO `PREFIXoption` VALUES (12, 'Espagnol', 3);
INSERT INTO `PREFIXoption` VALUES (13, 'Allemand', 3);

-- --------------------------------------------------------

-- 
-- Structure de la table `PREFIXperiode_ferie`
-- 

DROP TABLE IF EXISTS `PREFIXperiode_ferie`;
CREATE TABLE `PREFIXperiode_ferie` (
  `id_periode` int(10) NOT NULL auto_increment,
  `nom` varchar(50) default NULL,
  `date_debut` date NOT NULL default '0000-00-00',
  `date_fin` date NOT NULL default '0000-00-00',
  PRIMARY KEY  (`id_periode`)
) TYPE=MyISAM AUTO_INCREMENT=14 ;

-- 
-- Contenu de la table `PREFIXperiode_ferie`
-- 

INSERT INTO `PREFIXperiode_ferie` VALUES (1, 'Vacances de Noel', '2005-12-17', '2006-01-02');
INSERT INTO `PREFIXperiode_ferie` VALUES (2, 'Vacances de Fevrier', '2006-02-17', '2006-02-25');
INSERT INTO `PREFIXperiode_ferie` VALUES (5, '', '2006-04-04', '2006-04-05');
INSERT INTO `PREFIXperiode_ferie` VALUES (6, '', '2006-04-04', '2006-04-05');
INSERT INTO `PREFIXperiode_ferie` VALUES (7, '', '2006-04-04', '2006-04-05');
INSERT INTO `PREFIXperiode_ferie` VALUES (8, '', '2006-04-04', '2006-04-05');
INSERT INTO `PREFIXperiode_ferie` VALUES (9, '', '2006-04-04', '2006-04-05');
INSERT INTO `PREFIXperiode_ferie` VALUES (10, '', '2006-04-04', '2006-04-05');
INSERT INTO `PREFIXperiode_ferie` VALUES (11, '', '2006-04-04', '2006-04-05');
INSERT INTO `PREFIXperiode_ferie` VALUES (12, '', '2006-04-04', '2006-04-05');
INSERT INTO `PREFIXperiode_ferie` VALUES (13, '', '2006-04-04', '2006-04-05');

-- --------------------------------------------------------

-- 
-- Structure de la table `PREFIXperiode_travail`
-- 

DROP TABLE IF EXISTS `PREFIXperiode_travail`;
CREATE TABLE `PREFIXperiode_travail` (
  `id_periode` int(10) NOT NULL auto_increment,
  `numero` int(1) NOT NULL default '0',
  `date_debut` date NOT NULL default '0000-00-00',
  `date_fin` date NOT NULL default '0000-00-00',
  PRIMARY KEY  (`id_periode`)
) TYPE=MyISAM AUTO_INCREMENT=11 ;

-- 
-- Contenu de la table `PREFIXperiode_travail`
-- 

INSERT INTO `PREFIXperiode_travail` VALUES (1, 1, '2005-09-12', '2005-12-17');
INSERT INTO `PREFIXperiode_travail` VALUES (2, 2, '2006-01-01', '2006-06-03');
INSERT INTO `PREFIXperiode_travail` VALUES (5, 1, '2006-03-28', '2006-03-29');
INSERT INTO `PREFIXperiode_travail` VALUES (6, 1, '2006-03-28', '2006-03-29');
INSERT INTO `PREFIXperiode_travail` VALUES (7, 1, '2006-03-28', '2006-03-29');

-- --------------------------------------------------------

-- 
-- Structure de la table `PREFIXpole`
-- 

DROP TABLE IF EXISTS `PREFIXpole`;
CREATE TABLE `PREFIXpole` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `libelle` varchar(50) NOT NULL default '',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=5 ;

-- 
-- Contenu de la table `PREFIXpole`
-- 

INSERT INTO `PREFIXpole` VALUES (1, 'Calais');
INSERT INTO `PREFIXpole` VALUES (2, 'Boulogne sur mer');
INSERT INTO `PREFIXpole` VALUES (3, 'Dunkerque');
INSERT INTO `PREFIXpole` VALUES (4, 'Saint Omer');

-- --------------------------------------------------------

-- 
-- Structure de la table `PREFIXpool`
-- 

DROP TABLE IF EXISTS `PREFIXpool`;
CREATE TABLE `PREFIXpool` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `libelle` varchar(50) NOT NULL default '',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=2 ;

-- 
-- Contenu de la table `PREFIXpool`
-- 

INSERT INTO `PREFIXpool` VALUES (1, 'Amphi - Calais');

-- --------------------------------------------------------

-- 
-- Structure de la table `PREFIXprojet`
-- 

DROP TABLE IF EXISTS `PREFIXprojet`;
CREATE TABLE `PREFIXprojet` (
  `id_projet` INT( 10 ) NOT NULL AUTO_INCREMENT,
  `nom` VARCHAR( 50 ) NOT NULL ,
  `description` TEXT NULL,
  `id_diplome` INT( 10 ) NULL,
  PRIMARY KEY ( `id_projet` ) 
);

-- 
-- Contenu de la table `PREFIXprojet`
-- 

INSERT INTO `PREFIXprojet` VALUES (1, 'projet1','cest le projet1',3);
INSERT INTO `PREFIXprojet` VALUES (2, 'projet2','cest bien le projet2',4);
INSERT INTO `PREFIXprojet` VALUES (3, 'projet bon','cest le bon projet non?',2);

-- --------------------------------------------------------

-- 
-- Structure de la table `PREFIXprojet_enseignant`
-- 

DROP TABLE IF EXISTS `PREFIXprojet_enseignant`;
CREATE TABLE `PREFIXprojet_enseignant` (
  `id_projet` INT( 10 ) NOT NULL ,
  `id_enseignant` INT( 10 ) NOT NULL 
);

-- 
-- Contenu de la table `PREFIXprojet_enseignant`
-- 

INSERT INTO `PREFIXprojet_enseignant` VALUES (1, 2);
INSERT INTO `PREFIXprojet_enseignant` VALUES (2, 2);

-- --------------------------------------------------------

-- 
-- Structure de la table `PREFIXprojet_etudiant`
-- 

DROP TABLE IF EXISTS `PREFIXprojet_etudiant`;
CREATE TABLE `PREFIXprojet_etudiant` (
  `id_projet` INT( 10 ) NOT NULL ,
  `id_etudiant` INT( 10 ) NOT NULL 
);


-- 
-- Contenu de la table `PREFIXprojet_etudiant`
-- 

INSERT INTO `PREFIXprojet_enseignant` VALUES (1, 14);
INSERT INTO `PREFIXprojet_enseignant` VALUES (3, 1);

-- --------------------------------------------------------

-- 
-- Structure de la table `PREFIXsalle`
-- 

DROP TABLE IF EXISTS `PREFIXsalle`;
CREATE TABLE `PREFIXsalle` (
  `id_salle` int(11) NOT NULL auto_increment,
  `nom` varchar(50) NOT NULL default '',
  `capacite` int(11) NOT NULL default '0',
  `id_type_salle` int(11) NOT NULL default '0',
  `id_pool` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id_salle`),
  KEY `id_type_salle` (`id_type_salle`),
  KEY `id_pool` (`id_pool`)
) TYPE=MyISAM AUTO_INCREMENT=2 ;

-- 
-- Contenu de la table `PREFIXsalle`
-- 

INSERT INTO `PREFIXsalle` VALUES (1, 'C022', 120, 1, 1);

-- --------------------------------------------------------

-- 
-- Structure de la table `PREFIXsecretaire`
-- 

DROP TABLE IF EXISTS `PREFIXsecretaire`;
CREATE TABLE `PREFIXsecretaire` (
  `id_secretaire` int(10) NOT NULL auto_increment,
  `nom` varchar(32) NOT NULL default '',
  `prenom` varchar(32) NOT NULL default '',
  `tel` varchar(16) NOT NULL default '',
  `fax` varchar(16) NOT NULL default '',
  `email` varchar(50) NOT NULL default '',
  `id_pole` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id_secretaire`)
) TYPE=MyISAM AUTO_INCREMENT=25 ;

-- 
-- Contenu de la table `PREFIXsecretaire`
-- 

INSERT INTO `PREFIXsecretaire` VALUES (1, 'TATA', 'Olivia', '06.21.46.36.92', '06.21.46.06.83', 'tata@univ-littoral.fr', 0);
INSERT INTO `PREFIXsecretaire` VALUES (2, 'TUTU', 'Brigitte', '06.21.46.36.16', '06.21.46.36.16', 'tutu@univ-littoral.fr', 1);

-- --------------------------------------------------------

-- 
-- Structure de la table `PREFIXsecretaire_occupe_diplome`
-- 

DROP TABLE IF EXISTS `PREFIXsecretaire_occupe_diplome`;
CREATE TABLE `PREFIXsecretaire_occupe_diplome` (
  `id_secretaire` int(10) NOT NULL default '0',
  `id_diplome` int(10) default NULL,
  `id_option` int(10) default NULL
) TYPE=MyISAM;

-- 
-- Contenu de la table `PREFIXsecretaire_occupe_diplome`
-- 

INSERT INTO `PREFIXsecretaire_occupe_diplome` VALUES (1, 2, 0);
INSERT INTO `PREFIXsecretaire_occupe_diplome` VALUES (2, 10, 0);
INSERT INTO `PREFIXsecretaire_occupe_diplome` VALUES (1, 4, 0);
INSERT INTO `PREFIXsecretaire_occupe_diplome` VALUES (1, 5, 0);
INSERT INTO `PREFIXsecretaire_occupe_diplome` VALUES (1, 3, 0);

-- --------------------------------------------------------

-- 
-- Structure de la table `PREFIXsecretaire_occupe_pool`
-- 

DROP TABLE IF EXISTS `PREFIXsecretaire_occupe_pool`;
CREATE TABLE `PREFIXsecretaire_occupe_pool` (
  `id_secretaire` int(10) unsigned NOT NULL default '0',
  `id_pool` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id_secretaire`,`id_pool`)
) TYPE=MyISAM;

-- 
-- Contenu de la table `PREFIXsecretaire_occupe_pool`
-- 

INSERT INTO `PREFIXsecretaire_occupe_pool` VALUES (1, 1);

-- --------------------------------------------------------

-- 
-- Structure de la table `PREFIXspecialite`
-- 

DROP TABLE IF EXISTS `PREFIXspecialite`;
CREATE TABLE `PREFIXspecialite` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `libelle` varchar(50) NOT NULL default '',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=9 ;

-- 
-- Contenu de la table `PREFIXspecialite`
-- 

INSERT INTO `PREFIXspecialite` VALUES (2, 'ISIDIS');
INSERT INTO `PREFIXspecialite` VALUES (4, 'CCI');
INSERT INTO `PREFIXspecialite` VALUES (5, 'T3i');
INSERT INTO `PREFIXspecialite` VALUES (6, 'EIM');
INSERT INTO `PREFIXspecialite` VALUES (7, 'MOSC');
INSERT INTO `PREFIXspecialite` VALUES (8, 'IMTS');

-- --------------------------------------------------------

-- 
-- Structure de la table `PREFIXsuperviseur`
-- 

DROP TABLE IF EXISTS `PREFIXsuperviseur`;
CREATE TABLE `PREFIXsuperviseur` (
  `id` int(11) NOT NULL auto_increment,
  `nom` varchar(64) NOT NULL default '',
  `prenom` varchar(64) NOT NULL default '',
  `tel` varchar(15) NOT NULL default '',
  `fax` varchar(15) NOT NULL default '',
  `email` varchar(64) NOT NULL default '',
  `id_enseignant` int(11) default NULL,
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=7 ;

-- 
-- Contenu de la table `PREFIXsuperviseur`
-- 

INSERT INTO `PREFIXsuperviseur` VALUES (1, 'POULET', 'Laurence', 'x', 'xx', 'x', 0);
INSERT INTO `PREFIXsuperviseur` VALUES (6, '', '', '', '', '', 2);

-- --------------------------------------------------------

-- 
-- Structure de la table `PREFIXtype_salle`
-- 

DROP TABLE IF EXISTS `PREFIXtype_salle`;
CREATE TABLE `PREFIXtype_salle` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `libelle` varchar(50) NOT NULL default '',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=3 ;

-- 
-- Contenu de la table `PREFIXtype_salle`
-- 

INSERT INTO `PREFIXtype_salle` VALUES (1, 'Amphi');
INSERT INTO `PREFIXtype_salle` VALUES (2, 'TD');

-- --------------------------------------------------------

-- 
-- Structure de la table `PREFIXtype_sceance`
-- 

DROP TABLE IF EXISTS `PREFIXtype_sceance`;
CREATE TABLE `PREFIXtype_sceance` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `libelle` varchar(50) NOT NULL default '',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=7 ;

-- 
-- Contenu de la table `PREFIXtype_sceance`
-- 

INSERT INTO `PREFIXtype_sceance` VALUES (1, 'CM');
INSERT INTO `PREFIXtype_sceance` VALUES (2, 'TD');
INSERT INTO `PREFIXtype_sceance` VALUES (3, 'TP');
INSERT INTO `PREFIXtype_sceance` VALUES (4, 'Examen');

-- --------------------------------------------------------

-- 
-- Structure de la table `PREFIXuser_est_de_type`
-- 

DROP TABLE IF EXISTS `PREFIXuser_est_de_type`;
CREATE TABLE `PREFIXuser_est_de_type` (
  `id_user` int(10) unsigned NOT NULL default '0',
  `id_type` tinyint(3) unsigned NOT NULL default '0',
  `id` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id_user`,`id_type`)
) TYPE=MyISAM;

-- 
-- Contenu de la table `PREFIXuser_est_de_type`
-- 

INSERT INTO `PREFIXuser_est_de_type` VALUES (1, 1, 0);
INSERT INTO `PREFIXuser_est_de_type` VALUES (2, 5, 2);
INSERT INTO `PREFIXuser_est_de_type` VALUES (2, 6, 2);
INSERT INTO `PREFIXuser_est_de_type` VALUES (2, 2, 2);
INSERT INTO `PREFIXuser_est_de_type` VALUES (3, 7, 1);
INSERT INTO `PREFIXuser_est_de_type` VALUES (4, 3, 2);
INSERT INTO `PREFIXuser_est_de_type` VALUES (5, 4, 5);
INSERT INTO `PREFIXuser_est_de_type` VALUES (5, 2, 5);
INSERT INTO `PREFIXuser_est_de_type` VALUES (6, 2, 20);
INSERT INTO `PREFIXuser_est_de_type` VALUES (6, 4, 20);
INSERT INTO `PREFIXuser_est_de_type` VALUES (6, 5, 20);
INSERT INTO `PREFIXuser_est_de_type` VALUES (7, 2, 21);
INSERT INTO `PREFIXuser_est_de_type` VALUES (8, 2, 22);
INSERT INTO `PREFIXuser_est_de_type` VALUES (9, 2, 23);
INSERT INTO `PREFIXuser_est_de_type` VALUES (10, 7, 2);
INSERT INTO `PREFIXuser_est_de_type` VALUES (11, 2, 4);
INSERT INTO `PREFIXuser_est_de_type` VALUES (12, 2, 6);
INSERT INTO `PREFIXuser_est_de_type` VALUES (13, 2, 7);
INSERT INTO `PREFIXuser_est_de_type` VALUES (14, 2, 8);
INSERT INTO `PREFIXuser_est_de_type` VALUES (15, 2, 10);
INSERT INTO `PREFIXuser_est_de_type` VALUES (16, 2, 11);
INSERT INTO `PREFIXuser_est_de_type` VALUES (17, 2, 12);
INSERT INTO `PREFIXuser_est_de_type` VALUES (18, 2, 13);
INSERT INTO `PREFIXuser_est_de_type` VALUES (19, 2, 14);
INSERT INTO `PREFIXuser_est_de_type` VALUES (20, 2, 15);
INSERT INTO `PREFIXuser_est_de_type` VALUES (21, 2, 16);
INSERT INTO `PREFIXuser_est_de_type` VALUES (22, 2, 17);
INSERT INTO `PREFIXuser_est_de_type` VALUES (23, 2, 18);
INSERT INTO `PREFIXuser_est_de_type` VALUES (24, 2, 19);
INSERT INTO `PREFIXuser_est_de_type` VALUES (25, 3, 4);
INSERT INTO `PREFIXuser_est_de_type` VALUES (26, 3, 5);
INSERT INTO `PREFIXuser_est_de_type` VALUES (27, 3, 6);
INSERT INTO `PREFIXuser_est_de_type` VALUES (28, 3, 7);
INSERT INTO `PREFIXuser_est_de_type` VALUES (29, 3, 8);
INSERT INTO `PREFIXuser_est_de_type` VALUES (30, 3, 9);
INSERT INTO `PREFIXuser_est_de_type` VALUES (31, 3, 10);
INSERT INTO `PREFIXuser_est_de_type` VALUES (32, 3, 11);
INSERT INTO `PREFIXuser_est_de_type` VALUES (33, 3, 12);
INSERT INTO `PREFIXuser_est_de_type` VALUES (34, 3, 13);
INSERT INTO `PREFIXuser_est_de_type` VALUES (42, 4, 30);
INSERT INTO `PREFIXuser_est_de_type` VALUES (35, 2, 24);
INSERT INTO `PREFIXuser_est_de_type` VALUES (36, 3, 14);
INSERT INTO `PREFIXuser_est_de_type` VALUES (37, 2, 25);
INSERT INTO `PREFIXuser_est_de_type` VALUES (38, 2, 26);
INSERT INTO `PREFIXuser_est_de_type` VALUES (39, 2, 27);
INSERT INTO `PREFIXuser_est_de_type` VALUES (40, 2, 28);
INSERT INTO `PREFIXuser_est_de_type` VALUES (41, 2, 29);
INSERT INTO `PREFIXuser_est_de_type` VALUES (42, 2, 30);
INSERT INTO `PREFIXuser_est_de_type` VALUES (43, 2, 31);
INSERT INTO `PREFIXuser_est_de_type` VALUES (44, 2, 32);
INSERT INTO `PREFIXuser_est_de_type` VALUES (45, 2, 33);
INSERT INTO `PREFIXuser_est_de_type` VALUES (46, 2, 34);
INSERT INTO `PREFIXuser_est_de_type` VALUES (47, 2, 35);
INSERT INTO `PREFIXuser_est_de_type` VALUES (48, 2, 36);
INSERT INTO `PREFIXuser_est_de_type` VALUES (49, 2, 37);
INSERT INTO `PREFIXuser_est_de_type` VALUES (2, 4, 2);
INSERT INTO `PREFIXuser_est_de_type` VALUES (43, 5, 31);
INSERT INTO `PREFIXuser_est_de_type` VALUES (47, 4, 35);
INSERT INTO `PREFIXuser_est_de_type` VALUES (50, 8, 1);
INSERT INTO `PREFIXuser_est_de_type` VALUES (2, 8, 2);
INSERT INTO `PREFIXuser_est_de_type` VALUES (51, 3, 15);
INSERT INTO `PREFIXuser_est_de_type` VALUES (2, 7, 1);
INSERT INTO `PREFIXuser_est_de_type` VALUES (2, 1, 0);
INSERT INTO `PREFIXuser_est_de_type` VALUES (52, 3, 0);
INSERT INTO `PREFIXuser_est_de_type` VALUES (53, 7, 3);
INSERT INTO `PREFIXuser_est_de_type` VALUES (54, 7, 4);
INSERT INTO `PREFIXuser_est_de_type` VALUES (55, 7, 5);
INSERT INTO `PREFIXuser_est_de_type` VALUES (56, 7, 6);
INSERT INTO `PREFIXuser_est_de_type` VALUES (57, 7, 7);
INSERT INTO `PREFIXuser_est_de_type` VALUES (58, 7, 8);
INSERT INTO `PREFIXuser_est_de_type` VALUES (59, 7, 9);
INSERT INTO `PREFIXuser_est_de_type` VALUES (60, 7, 10);
INSERT INTO `PREFIXuser_est_de_type` VALUES (61, 7, 11);
INSERT INTO `PREFIXuser_est_de_type` VALUES (62, 7, 12);
INSERT INTO `PREFIXuser_est_de_type` VALUES (63, 7, 13);
INSERT INTO `PREFIXuser_est_de_type` VALUES (64, 7, 14);
INSERT INTO `PREFIXuser_est_de_type` VALUES (65, 7, 15);
INSERT INTO `PREFIXuser_est_de_type` VALUES (66, 7, 16);
INSERT INTO `PREFIXuser_est_de_type` VALUES (67, 7, 17);
INSERT INTO `PREFIXuser_est_de_type` VALUES (68, 7, 18);
INSERT INTO `PREFIXuser_est_de_type` VALUES (69, 7, 19);
INSERT INTO `PREFIXuser_est_de_type` VALUES (70, 7, 20);
INSERT INTO `PREFIXuser_est_de_type` VALUES (71, 7, 21);
INSERT INTO `PREFIXuser_est_de_type` VALUES (72, 7, 22);
INSERT INTO `PREFIXuser_est_de_type` VALUES (73, 7, 23);
INSERT INTO `PREFIXuser_est_de_type` VALUES (74, 7, 24);

-- --------------------------------------------------------

-- 
-- Structure de la table `PREFIXuser_type`
-- 

DROP TABLE IF EXISTS `PREFIXuser_type`;
CREATE TABLE `PREFIXuser_type` (
  `id_type` tinyint(3) unsigned NOT NULL auto_increment,
  `libelle` varchar(32) NOT NULL default '',
  PRIMARY KEY  (`id_type`)
) TYPE=MyISAM AUTO_INCREMENT=9 ;

-- 
-- Contenu de la table `PREFIXuser_type`
-- 

INSERT INTO `PREFIXuser_type` VALUES (5, 'Pr&eacute;sident de jury');
INSERT INTO `PREFIXuser_type` VALUES (1, 'Administrateur');
INSERT INTO `PREFIXuser_type` VALUES (2, 'Enseignant');
INSERT INTO `PREFIXuser_type` VALUES (3, 'Etudiant');
INSERT INTO `PREFIXuser_type` VALUES (4, 'Directeur d''&eacute;tudes');
INSERT INTO `PREFIXuser_type` VALUES (6, 'Directeur de \r\nd&eacute;partemen');
INSERT INTO `PREFIXuser_type` VALUES (7, 'Secr&eacute;taire');
INSERT INTO `PREFIXuser_type` VALUES (8, 'Superviseur');

-- --------------------------------------------------------

-- 
-- Structure de la table `PREFIXusers`
-- 

DROP TABLE IF EXISTS `PREFIXusers`;
CREATE TABLE `PREFIXusers` (
  `id_user` int(10) unsigned NOT NULL auto_increment,
  `login` varchar(32) NOT NULL default '',
  `password` varchar(32) NOT NULL default '',
  `actif` int(1) NOT NULL default '0',
  PRIMARY KEY  (`id_user`)
) TYPE=MyISAM AUTO_INCREMENT=75 ;

-- 
-- Contenu de la table `PREFIXusers`
-- 

INSERT INTO `PREFIXusers` VALUES (1, 'admin', 'f71dbe52628a3f83a77ab494817525c6', 0);
INSERT INTO `PREFIXusers` VALUES (2, 'eramat', 'f71dbe52628a3f83a77ab494817525c6', 0);
INSERT INTO `PREFIXusers` VALUES (3, 'tata', 'f71dbe52628a3f83a77ab494817525c6', 0);
INSERT INTO `PREFIXusers` VALUES (4, 'toto', 'f71dbe52628a3f83a77ab494817525c6', 1);
INSERT INTO `PREFIXusers` VALUES (35, 'mleblond', 'd41d8cd98f00b204e9800998ecf8427e', 0);
INSERT INTO `PREFIXusers` VALUES (36, 'ttiti', '5d933eef19aee7da192608de61b6c23d', 0);
INSERT INTO `PREFIXusers` VALUES (37, 'hbasson', 'f71dbe52628a3f83a77ab494817525c6', 1);
INSERT INTO `PREFIXusers` VALUES (38, 'gquesnel', 'd41d8cd98f00b204e9800998ecf8427e', 0);
INSERT INTO `PREFIXusers` VALUES (39, 'jsoulie', 'd41d8cd98f00b204e9800998ecf8427e', 0);
INSERT INTO `PREFIXusers` VALUES (40, 'msegond', 'd41d8cd98f00b204e9800998ecf8427e', 0);
INSERT INTO `PREFIXusers` VALUES (41, 'tdelemer', 'd41d8cd98f00b204e9800998ecf8427e', 0);
INSERT INTO `PREFIXusers` VALUES (42, 'gbourguin', 'f71dbe52628a3f83a77ab494817525c6', 1);
INSERT INTO `PREFIXusers` VALUES (43, 'mbouneffa', 'd41d8cd98f00b204e9800998ecf8427e', 0);
INSERT INTO `PREFIXusers` VALUES (44, 'pcollet', 'd41d8cd98f00b204e9800998ecf8427e', 0);
INSERT INTO `PREFIXusers` VALUES (45, 'drobilliard', 'd41d8cd98f00b204e9800998ecf8427e', 0);
INSERT INTO `PREFIXusers` VALUES (46, 'cfonlupt', 'd41d8cd98f00b204e9800998ecf8427e', 0);
INSERT INTO `PREFIXusers` VALUES (47, 'vmarion-poty', 'd41d8cd98f00b204e9800998ecf8427e', 0);
INSERT INTO `PREFIXusers` VALUES (48, 'dduvivier', 'd41d8cd98f00b204e9800998ecf8427e', 0);
INSERT INTO `PREFIXusers` VALUES (49, 'lderuelle', 'd41d8cd98f00b204e9800998ecf8427e', 0);
INSERT INTO `PREFIXusers` VALUES (50, 'lpoulet', 'd41d8cd98f00b204e9800998ecf8427e', 0);
INSERT INTO `PREFIXusers` VALUES (51, 'tonio', 'f71dbe52628a3f83a77ab494817525c6', 0);
INSERT INTO `PREFIXusers` VALUES (52, 'gzogzog', 'd41d8cd98f00b204e9800998ecf8427e', 0);
        
