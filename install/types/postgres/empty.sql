-- --------------------------------------------------------
-- Structure de la table "PREFIXannee";

DROP TABLE IF EXISTS "PREFIXannee";
DROP SEQUENCE IF EXISTS "PREFIXannee_id_seq";
CREATE SEQUENCE "PREFIXannee_id_seq";
CREATE TABLE "PREFIXannee" (
  "id" INT NOT NULL UNIQUE,
  "numero" INT NOT NULL,
  "id_niveau" INT NOT NULL,
  PRIMARY KEY("id")
);
ALTER TABLE "PREFIXannee"
    ALTER COLUMN "id"
        SET DEFAULT NEXTVAL('PREFIXannee_id_seq');

-- --------------------------------------------------------
-- Structure de la table "PREFIXcalendrier";

DROP TABLE IF EXISTS "PREFIXcalendrier";
DROP SEQUENCE IF EXISTS "PREFIXcalendrier_id_seq";
CREATE SEQUENCE "PREFIXcalendrier_id_seq";
CREATE TABLE "PREFIXcalendrier" (
  "id" INT NOT NULL UNIQUE,
  "libelle" VARCHAR(50) NOT NULL default '',
  PRIMARY KEY("id")
);
ALTER TABLE "PREFIXcalendrier"
    ALTER COLUMN "id"
        SET DEFAULT NEXTVAL('PREFIXcalendrier_id_seq');

-- --------------------------------------------------------
-- Structure de la table "PREFIXcalendrier_ferie";

DROP TABLE IF EXISTS "PREFIXcalendrier_ferie";
CREATE TABLE "PREFIXcalendrier_ferie" (
  "id_calendrier" INT NOT NULL default 0,
  "id_periode" INT NOT NULL default 0
);

-- --------------------------------------------------------
-- Structure de la table "PREFIXcalendrier_travail";

DROP TABLE IF EXISTS "PREFIXcalendrier_travail";
CREATE TABLE "PREFIXcalendrier_travail" (
  "id_calendrier" INT NOT NULL default 0,
  "id_periode" INT NOT NULL default 0
);

-- --------------------------------------------------------
-- Structure de la table "PREFIXdepartement";

DROP TABLE IF EXISTS "PREFIXdepartement";
DROP SEQUENCE IF EXISTS "PREFIXdepartement_id_seq";
CREATE SEQUENCE "PREFIXdepartement_id_seq";
CREATE TABLE "PREFIXdepartement" (
  "id" INT NOT NULL UNIQUE,
  "libelle" VARCHAR(50) NOT NULL default '',
  PRIMARY KEY("id")
);
ALTER TABLE "PREFIXdepartement"
    ALTER COLUMN "id"
        SET DEFAULT NEXTVAL('PREFIXdepartement_id_seq');

-- --------------------------------------------------------
-- Structure de la table "PREFIXdepartement_directeur";

DROP TABLE IF EXISTS "PREFIXdepartement_directeur";
CREATE TABLE "PREFIXdepartement_directeur" (
  "id_departement" INT NOT NULL default 0,
  "id_enseignant" INT NOT NULL default 0
);

-- --------------------------------------------------------
-- Structure de la table "PREFIXdiplome";

DROP TABLE IF EXISTS "PREFIXdiplome";
DROP SEQUENCE IF EXISTS "PREFIXdiplome_id_seq";
CREATE SEQUENCE "PREFIXdiplome_id_seq";
CREATE TABLE "PREFIXdiplome" (
  "id_diplome" INT NOT NULL UNIQUE,
  "annee" SMALLINT NOT NULL default 0,
  "sigle" VARCHAR(10) NOT NULL default '',
  "sigle_complet" VARCHAR(255) NOT NULL default '',
  "id_president_jury" INT NOT NULL default 0,
  "id_directeur_etudes" INT NOT NULL default 0,
  "id_niveau" INT NOT NULL default 0,
  "id_domaine" INT NOT NULL default 0,
  "id_mention" INT NOT NULL default 0,
  "id_specialite" INT NOT NULL default 0,
  "intitule_parcours" VARCHAR(32) NOT NULL default '',
  "id_pole" INT NOT NULL default 0,
  "id_calendrier" INT NOT NULL default 0,
  "id_departement" INT NOT NULL default 0,
  PRIMARY KEY("id_diplome")
);
ALTER TABLE "PREFIXdiplome"
    ALTER COLUMN "id_diplome"
        SET DEFAULT NEXTVAL('PREFIXdiplome_id_seq');

-- --------------------------------------------------------
-- Structure de la table "PREFIXdomaine";

DROP TABLE IF EXISTS "PREFIXdomaine";
DROP SEQUENCE IF EXISTS "PREFIXdomaine_id_seq";
CREATE SEQUENCE "PREFIXdomaine_id_seq";
CREATE TABLE "PREFIXdomaine" (
  "id" INT NOT NULL UNIQUE,
  "libelle" VARCHAR(50) NOT NULL default '',
  PRIMARY KEY("id")
);
ALTER TABLE "PREFIXdomaine"
    ALTER COLUMN "id"
        SET DEFAULT NEXTVAL('PREFIXdomaine_id_seq');

-- --------------------------------------------------------
-- Structure de la table "PREFIXenseignant";

DROP TABLE IF EXISTS "PREFIXenseignant";
DROP SEQUENCE IF EXISTS "PREFIXenseignant_id_seq";
CREATE SEQUENCE "PREFIXenseignant_id_seq";
CREATE TABLE "PREFIXenseignant" (
  "id_enseignant" INT NOT NULL UNIQUE,
  "nom" VARCHAR(32) NOT NULL default '',
  "prenom" VARCHAR(32) NOT NULL default '',
  "initiales" VARCHAR(5) NOT NULL default '',
  "id_grade" INT NOT NULL default 0,
  "id_departement" INT NOT NULL default 0,
  "cnu" VARCHAR(32) NOT NULL default '',
  "titulaire" BOOLEAN default FALSE,
  "pes" BOOLEAN default FALSE,
  "id_pole" INT NOT NULL default 0,
  "adresse" VARCHAR(80) NOT NULL default '',
  "code_postal" VARCHAR(5) NOT NULL default '',
  "ville" VARCHAR(32) NOT NULL default '',
  "email" VARCHAR(80) NOT NULL default '',
  "telephone" VARCHAR(20) NOT NULL default '',
  PRIMARY KEY("id_enseignant")
);
ALTER TABLE "PREFIXenseignant"
    ALTER COLUMN "id_enseignant"
        SET DEFAULT NEXTVAL('PREFIXenseignant_id_seq');

-- --------------------------------------------------------
-- Structure de la table "PREFIXetudiant";

DROP TABLE IF EXISTS "PREFIXetudiant";
DROP SEQUENCE IF EXISTS "PREFIXetudiant_id_seq";
CREATE SEQUENCE "PREFIXetudiant_id_seq";
CREATE TABLE "PREFIXetudiant" (
  "id_etudiant" INT NOT NULL UNIQUE,
  "ine" VARCHAR(15) NOT NULL default '',
  "nom" VARCHAR(32) NOT NULL default '',
  "prenom" VARCHAR(32) NOT NULL default '',
  "adresse" VARCHAR(80) NOT NULL default '',
  "code_postal" VARCHAR(5) NOT NULL default '',
  "ville" VARCHAR(32) NOT NULL default '',
  "email" VARCHAR(80) NOT NULL default '',
  "tel" VARCHAR(20) NOT NULL default '',
  "photo" BOOLEAN default FALSE,
  PRIMARY KEY("id_etudiant")
);
ALTER TABLE "PREFIXetudiant"
    ALTER COLUMN "id_etudiant"
        SET DEFAULT NEXTVAL('PREFIXetudiant_id_seq');

-- --------------------------------------------------------
-- Structure de la table "PREFIXetudiant_appartient_groupe";

DROP TABLE IF EXISTS "PREFIXetudiant_appartient_groupe";
CREATE TABLE "PREFIXetudiant_appartient_groupe" (
  "id_etudiant" INT NOT NULL default 0,
  "id_groupe" INT NOT NULL default 0,
  PRIMARY KEY("id_etudiant","id_groupe")
);

-- --------------------------------------------------------
-- Structure de la table "PREFIXetudiant_appartient_option";

DROP TABLE IF EXISTS "PREFIXetudiant_appartient_option";
CREATE TABLE "PREFIXetudiant_appartient_option" (
  "id_etudiant" INT NOT NULL default 0,
  "id_option" INT NOT NULL default 0,
  PRIMARY KEY("id_etudiant","id_option")
);

-- --------------------------------------------------------
-- Structure de la table "PREFIXgrade";

DROP TABLE IF EXISTS "PREFIXgrade";
DROP SEQUENCE IF EXISTS "PREFIXgrade_id_seq";
CREATE SEQUENCE "PREFIXgrade_id_seq";
CREATE TABLE "PREFIXgrade" (
  "id" INT NOT NULL UNIQUE,
  "libelle" VARCHAR(50) NOT NULL default '',
  "nombre_heures" INT NOT NULL default 0,
  PRIMARY KEY("id")
);
ALTER TABLE "PREFIXgrade"
    ALTER COLUMN "id"
        SET DEFAULT NEXTVAL('PREFIXgrade_id_seq');

-- --------------------------------------------------------
-- Structure de la table "PREFIXgroupe";

DROP TABLE IF EXISTS "PREFIXgroupe";
DROP SEQUENCE IF EXISTS "PREFIXgroupe_id_seq";
CREATE SEQUENCE "PREFIXgroupe_id_seq";
CREATE TABLE "PREFIXgroupe" (
  "id" INT NOT NULL UNIQUE,
  "nom" VARCHAR(50) NOT NULL default '',
  "id_diplome" INT default 0,
  "id_option" INT default 0,
  PRIMARY KEY("id")
);
ALTER TABLE "PREFIXgroupe"
    ALTER COLUMN "id"
        SET DEFAULT NEXTVAL('PREFIXgroupe_id_seq');

-- --------------------------------------------------------
-- Structure de la table "PREFIXgroupe_type";

DROP TABLE IF EXISTS "PREFIXgroupe_type";
CREATE TABLE "PREFIXgroupe_type" (
  "id_groupe" INT NOT NULL default 0,
  "id_type" INT NOT NULL default 0,
  PRIMARY KEY("id_groupe","id_type")
);

-- --------------------------------------------------------
-- Structure de la table "PREFIXgroupe_virtuel";

DROP TABLE IF EXISTS "PREFIXgroupe_virtuel";
DROP SEQUENCE IF EXISTS "PREFIXgroupe_virtuel_id_seq";
CREATE SEQUENCE "PREFIXgroupe_virtuel_id_seq";
CREATE TABLE "PREFIXgroupe_virtuel" (
  "id" INT NOT NULL UNIQUE,
  "nom" VARCHAR(50) NOT NULL default '',
  PRIMARY KEY("id")
);
ALTER TABLE "PREFIXgroupe_virtuel"
    ALTER COLUMN "id"
        SET DEFAULT NEXTVAL('PREFIXgroupe_virtuel_id_seq');

-- --------------------------------------------------------
-- Structure de la table "PREFIXgroupe_virtuel_compose_diplome";

DROP TABLE IF EXISTS "PREFIXgroupe_virtuel_compose_diplome";
CREATE TABLE "PREFIXgroupe_virtuel_compose_diplome" (
  "id_groupe_virtuel" INT NOT NULL default 0,
  "id_diplome" INT NOT NULL default 0,
  PRIMARY KEY("id_groupe_virtuel","id_diplome")
);

-- --------------------------------------------------------
-- Structure de la table "PREFIXgroupe_virtuel_compose_etudiant";

DROP TABLE IF EXISTS "PREFIXgroupe_virtuel_compose_etudiant";
CREATE TABLE "PREFIXgroupe_virtuel_compose_etudiant" (
  "id_groupe_virtuel" INT NOT NULL default 0,
  "id_etudiant" INT NOT NULL default 0,
  PRIMARY KEY("id_groupe_virtuel","id_etudiant")
);

-- --------------------------------------------------------
-- Structure de la table "PREFIXgroupe_virtuel_compose_groupe";

DROP TABLE IF EXISTS "PREFIXgroupe_virtuel_compose_groupe";
CREATE TABLE "PREFIXgroupe_virtuel_compose_groupe" (
  "id_groupe_virtuel" INT NOT NULL default 0,
  "id_groupe" INT NOT NULL default 0,
  PRIMARY KEY("id_groupe_virtuel","id_groupe")
);

-- --------------------------------------------------------
-- Structure de la table "PREFIXgroupe_virtuel_compose_option";

DROP TABLE IF EXISTS "PREFIXgroupe_virtuel_compose_option";
CREATE TABLE "PREFIXgroupe_virtuel_compose_option" (
  "id_groupe_virtuel" INT NOT NULL default 0,
  "id_option" INT NOT NULL default 0,
  PRIMARY KEY("id_groupe_virtuel","id_option")
);

-- --------------------------------------------------------
-- Structure de la table "PREFIXinscrit_diplome";

DROP TABLE IF EXISTS "PREFIXinscrit_diplome";
CREATE TABLE "PREFIXinscrit_diplome" (
  "id_diplome" INT NOT NULL default 0,
  "id_etudiant" INT NOT NULL default 0,
  "principal" BOOLEAN default FALSE
);

-- --------------------------------------------------------
-- Structure de la table "PREFIXmention";

DROP TABLE IF EXISTS "PREFIXmention";
DROP SEQUENCE IF EXISTS "PREFIXmention_id_seq";
CREATE SEQUENCE "PREFIXmention_id_seq";
CREATE TABLE "PREFIXmention" (
  "id" INT NOT NULL UNIQUE,
  "libelle" VARCHAR(50) NOT NULL default '',
  PRIMARY KEY("id")
);
ALTER TABLE "PREFIXmention"
    ALTER COLUMN "id"
        SET DEFAULT NEXTVAL('PREFIXmention_id_seq');

-- --------------------------------------------------------
-- Structure de la table "PREFIXmenu_cat";

DROP TABLE IF EXISTS "PREFIXmenu_cat";
DROP SEQUENCE IF EXISTS "PREFIXmenu_cat_id_seq";
CREATE SEQUENCE "PREFIXmenu_cat_id_seq";
CREATE TABLE "PREFIXmenu_cat" (
  "id" INT NOT NULL UNIQUE,
  "libelle" VARCHAR(128) NOT NULL default '',
  "id_type_user" INT NOT NULL default 0,
  "ordre" INT NOT NULL default 0,
  PRIMARY KEY("id")
);
ALTER TABLE "PREFIXmenu_cat"
    ALTER COLUMN "id"
        SET DEFAULT NEXTVAL('PREFIXmenu_cat_id_seq');

-- --------------------------------------------------------
-- Structure de la table "PREFIXmenu_data";

DROP TABLE IF EXISTS "PREFIXmenu_data";
DROP SEQUENCE IF EXISTS "PREFIXmenu_data_id_seq";
CREATE SEQUENCE "PREFIXmenu_data_id_seq";
CREATE TABLE "PREFIXmenu_data" (
  "id" INT NOT NULL UNIQUE,
  "id_categorie" INT NOT NULL default 0,
  "id_type_user" INT NOT NULL default 0,
  "libelle" VARCHAR(128) NOT NULL default '',
  "param" VARCHAR(128) NOT NULL default '',
  "id_lien" INT NOT NULL default 0,
  "ordre" INT NOT NULL default 0,
  PRIMARY KEY("id","id_categorie","id_type_user")
);
ALTER TABLE "PREFIXmenu_data"
    ALTER COLUMN "id"
        SET DEFAULT NEXTVAL('PREFIXmenu_data_id_seq');

-- --------------------------------------------------------
-- Structure de la table "PREFIXmenu_lien";

DROP TABLE IF EXISTS "PREFIXmenu_lien";
DROP SEQUENCE IF EXISTS "PREFIXmenu_lien_id_seq";
CREATE SEQUENCE "PREFIXmenu_lien_id_seq";
CREATE TABLE "PREFIXmenu_lien" (
  "id" INT NOT NULL UNIQUE,
  "libelle" VARCHAR(128) NOT NULL default '',
  "suppressible" BOOLEAN default FALSE,
  PRIMARY KEY("id")
);
ALTER TABLE "PREFIXmenu_lien"
    ALTER COLUMN "id"
        SET DEFAULT NEXTVAL('PREFIXmenu_lien_id_seq');

-- --------------------------------------------------------
-- Structure de la table "PREFIXmodule";

DROP TABLE IF EXISTS "PREFIXmodule";
DROP SEQUENCE IF EXISTS "PREFIXmodule_id_seq";
CREATE SEQUENCE "PREFIXmodule_id_seq";
CREATE TABLE "PREFIXmodule" (
  "id" INT NOT NULL UNIQUE,
  "nom" VARCHAR(64) NOT NULL default '',
  "sigle" VARCHAR(10) NOT NULL default '',
  "credits" INT NOT NULL default 0,
  "num_periode" SMALLINT NOT NULL default 0,
  "id_departement" INT NOT NULL default 0,
  "descriptif" VARCHAR(256) NOT NULL default '',
  PRIMARY KEY("id")
);
ALTER TABLE "PREFIXmodule"
    ALTER COLUMN "id"
        SET DEFAULT NEXTVAL('PREFIXmodule_id_seq');

-- --------------------------------------------------------
-- Structure de la table "PREFIXmodule_assure";

DROP TABLE IF EXISTS "PREFIXmodule_assure";
CREATE TABLE "PREFIXmodule_assure" (
  "id_enseignant" INT NOT NULL default 0,
  "id_module" INT NOT NULL default 0,
  "id_groupe" INT default NULL,
  "id_type_seance" INT NOT NULL default 0,
  "nombre_heures" float NOT NULL default 0
);

-- --------------------------------------------------------
-- Structure de la table "PREFIXmodule_divise";

DROP TABLE IF EXISTS "PREFIXmodule_divise";
CREATE TABLE "PREFIXmodule_divise" (
  "id_module" INT NOT NULL default 0,
  "id_type_seance" INT NOT NULL default 0,
  "nombre_heures" FLOAT NOT NULL default 0,
  PRIMARY KEY("id_module","id_type_seance")
);

-- --------------------------------------------------------
-- Structure de la table "PREFIXmodule_planifie";

DROP TABLE IF EXISTS "PREFIXmodule_planifie";
DROP SEQUENCE IF EXISTS "PREFIXmodule_planifie_id_seq";
CREATE SEQUENCE "PREFIXmodule_planifie_id_seq";
CREATE TABLE "PREFIXmodule_planifie" (
  "id_planifie" INT NOT NULL UNIQUE,
  "id_module" INT NOT NULL default 0,
  "id_type_seance" INT NOT NULL default 0,
  "id_enseignant" INT NOT NULL default 0,
  "id_salle" INT NOT NULL default 0,
  "semaine" SMALLINT NOT NULL default 0,
  "jour_semaine" SMALLINT NOT NULL default 0,
  "heure_debut" TIME NOT NULL default '00:00:00',
  "heure_fin" TIME NOT NULL default '00:00:00',
  "valid_enseignant" BOOLEAN default FALSE,
  "valid_de" BOOLEAN default FALSE,
  PRIMARY KEY("id_planifie")
);
ALTER TABLE "PREFIXmodule_planifie"
    ALTER COLUMN "id_planifie"
        SET DEFAULT NEXTVAL('PREFIXmodule_planifie_id_seq');

-- --------------------------------------------------------
-- Structure de la table "PREFIXmodule_planifie_diplome";

DROP TABLE IF EXISTS "PREFIXmodule_planifie_diplome";
CREATE TABLE "PREFIXmodule_planifie_diplome" (
  "id_planifie" INT NOT NULL default 0,
  "id_diplome" INT NOT NULL default 0
);

-- --------------------------------------------------------
-- Structure de la table "PREFIXmodule_planifie_groupe";

DROP TABLE IF EXISTS "PREFIXmodule_planifie_groupe";
CREATE TABLE "PREFIXmodule_planifie_groupe" (
  "id_planifie" INT NOT NULL default 0,
  "id_groupe" INT NOT NULL default 0
);

-- --------------------------------------------------------
-- Structure de la table "PREFIXmodule_planifie_option";

DROP TABLE IF EXISTS "PREFIXmodule_planifie_option";
CREATE TABLE "PREFIXmodule_planifie_option" (
  "id_planifie" INT NOT NULL default 0,
  "id_option" INT NOT NULL default 0
);

-- --------------------------------------------------------
-- Structure de la table "PREFIXmodule_reparti";

DROP TABLE IF EXISTS "PREFIXmodule_reparti";
DROP SEQUENCE IF EXISTS "PREFIXmodule_reparti_id_seq";
CREATE SEQUENCE "PREFIXmodule_reparti_id_seq";
CREATE TABLE "PREFIXmodule_reparti" (
  "id" INT NOT NULL UNIQUE,
  "semaine" SMALLINT NOT NULL default 0,
  "nombre_heures" float NOT NULL default 0,
  "id_type_seance" INT NOT NULL default 0,
  "id_module" INT NOT NULL default 0,
  "id_groupe" INT default NULL,
  PRIMARY KEY("id")
);
ALTER TABLE "PREFIXmodule_reparti"
    ALTER COLUMN "id"
        SET DEFAULT NEXTVAL('PREFIXmodule_reparti_id_seq');

-- --------------------------------------------------------
-- Structure de la table "PREFIXmodule_suivi_diplome";

DROP TABLE IF EXISTS "PREFIXmodule_suivi_diplome";
CREATE TABLE "PREFIXmodule_suivi_diplome" (
  "id_module" INT NOT NULL default 0,
  "id_diplome" INT NOT NULL default 0,
  PRIMARY KEY("id_module","id_diplome")
);

-- --------------------------------------------------------
-- Structure de la table "PREFIXmodule_suivi_groupe_virtuel";

DROP TABLE IF EXISTS "PREFIXmodule_suivi_groupe_virtuel";
CREATE TABLE "PREFIXmodule_suivi_groupe_virtuel" (
  "id_module" INT NOT NULL default 0,
  "id_groupe_virtuel" INT NOT NULL default 0,
  PRIMARY KEY("id_module","id_groupe_virtuel")
);

-- --------------------------------------------------------
-- Structure de la table "PREFIXmodule_suivi_option";

DROP TABLE IF EXISTS "PREFIXmodule_suivi_option";
CREATE TABLE "PREFIXmodule_suivi_option" (
  "id_module" INT NOT NULL default 0,
  "id_option" INT NOT NULL default 0,
  PRIMARY KEY("id_module","id_option")
);

-- --------------------------------------------------------
-- Structure de la table "PREFIXniveau";

DROP TABLE IF EXISTS "PREFIXniveau";
DROP SEQUENCE IF EXISTS "PREFIXniveau_id_seq";
CREATE SEQUENCE "PREFIXniveau_id_seq";
CREATE TABLE "PREFIXniveau" (
  "id" INT NOT NULL UNIQUE,
  "libelle" VARCHAR(50) NOT NULL default '',
  "nombre_annees" SMALLINT NOT NULL default 0,
  PRIMARY KEY("id")
);
ALTER TABLE "PREFIXniveau"
    ALTER COLUMN "id"
        SET DEFAULT NEXTVAL('PREFIXniveau_id_seq');

-- --------------------------------------------------------
-- Structure de la table "PREFIXoption";

DROP TABLE IF EXISTS "PREFIXoption";
DROP SEQUENCE IF EXISTS "PREFIXoption_id_seq";
CREATE SEQUENCE "PREFIXoption_id_seq";
CREATE TABLE "PREFIXoption" (
  "id" INT NOT NULL UNIQUE,
  "nom" VARCHAR(50) NOT NULL default '',
  "id_diplome" INT NOT NULL default 0,
  PRIMARY KEY("id")
);
ALTER TABLE "PREFIXoption"
    ALTER COLUMN "id"
        SET DEFAULT NEXTVAL('PREFIXoption_id_seq');

-- --------------------------------------------------------
-- Structure de la table "PREFIXperiode_ferie";

DROP TABLE IF EXISTS "PREFIXperiode_ferie";
DROP SEQUENCE IF EXISTS "PREFIXperiode_ferie_id_seq";
CREATE SEQUENCE "PREFIXperiode_ferie_id_seq";
CREATE TABLE "PREFIXperiode_ferie" (
  "id_periode" INT NOT NULL UNIQUE,
  "nom" VARCHAR(50) default NULL,
  "date_debut" DATE NOT NULL default '1970-01-01',
  "date_fin" DATE NOT NULL default '1970-01-01',
  PRIMARY KEY("id_periode")
);
ALTER TABLE "PREFIXperiode_ferie"
    ALTER COLUMN "id_periode"
        SET DEFAULT NEXTVAL('PREFIXperiode_ferie_id_seq');

-- --------------------------------------------------------
-- Structure de la table "PREFIXperiode_travail";

DROP TABLE IF EXISTS "PREFIXperiode_travail";
DROP SEQUENCE IF EXISTS "PREFIXperiode_travail_id_seq";
CREATE SEQUENCE "PREFIXperiode_travail_id_seq";
CREATE TABLE "PREFIXperiode_travail" (
  "id_periode" INT NOT NULL UNIQUE,
  "numero" SMALLINT NOT NULL default 0,
  "date_debut" DATE NOT NULL default '1970-01-01',
  "date_fin" DATE NOT NULL default '1970-01-01',
  PRIMARY KEY("id_periode")
);
ALTER TABLE "PREFIXperiode_travail"
    ALTER COLUMN "id_periode"
        SET DEFAULT NEXTVAL('PREFIXperiode_travail_id_seq');

-- --------------------------------------------------------
-- Structure de la table "PREFIXpole";

DROP TABLE IF EXISTS "PREFIXpole";
DROP SEQUENCE IF EXISTS "PREFIXpole_id_seq";
CREATE SEQUENCE "PREFIXpole_id_seq";
CREATE TABLE "PREFIXpole" (
  "id" INT NOT NULL UNIQUE,
  "libelle" VARCHAR(50) NOT NULL default '',
  PRIMARY KEY("id")
);
ALTER TABLE "PREFIXpole"
    ALTER COLUMN "id"
        SET DEFAULT NEXTVAL('PREFIXpole_id_seq');

-- --------------------------------------------------------
-- Structure de la table "PREFIXpool";

DROP TABLE IF EXISTS "PREFIXpool";
DROP SEQUENCE IF EXISTS "PREFIXpool_id_seq";
CREATE SEQUENCE "PREFIXpool_id_seq";
CREATE TABLE "PREFIXpool" (
  "id" INT NOT NULL UNIQUE,
  "libelle" VARCHAR(50) NOT NULL default '',
  PRIMARY KEY("id")
);
ALTER TABLE "PREFIXpool"
    ALTER COLUMN "id"
        SET DEFAULT NEXTVAL('PREFIXpool_id_seq');

-- --------------------------------------------------------
-- Structure de la table "PREFIXsalle";

DROP TABLE IF EXISTS "PREFIXsalle";
DROP SEQUENCE IF EXISTS "PREFIXsalle_id_seq";
CREATE SEQUENCE "PREFIXsalle_id_seq";
CREATE TABLE "PREFIXsalle" (
  "id_salle" INT NOT NULL UNIQUE,
  "nom" VARCHAR(50) NOT NULL default '',
  "capacite" INT NOT NULL default 0,
  "id_type_salle" INT NOT NULL default 0,
  "id_pool" INT NOT NULL default 0,
  PRIMARY KEY("id_salle")
);
ALTER TABLE "PREFIXsalle"
    ALTER COLUMN "id_salle"
        SET DEFAULT NEXTVAL('PREFIXsalle_id_seq');

-- --------------------------------------------------------
-- Structure de la table "PREFIXsecretaire";

DROP TABLE IF EXISTS "PREFIXsecretaire";
DROP SEQUENCE IF EXISTS "PREFIXsecretaire_id_seq";
CREATE SEQUENCE "PREFIXsecretaire_id_seq";
CREATE TABLE "PREFIXsecretaire" (
  "id_secretaire" INT NOT NULL UNIQUE,
  "nom" VARCHAR(32) NOT NULL default '',
  "prenom" VARCHAR(32) NOT NULL default '',
  "tel" VARCHAR(16) NOT NULL default '',
  "fax" VARCHAR(16) NOT NULL default '',
  "email" VARCHAR(50) NOT NULL default '',
  "id_pole" INT NOT NULL default 0,
  PRIMARY KEY("id_secretaire")
);
ALTER TABLE "PREFIXsecretaire"
    ALTER COLUMN "id_secretaire"
        SET DEFAULT NEXTVAL('PREFIXsecretaire_id_seq');

-- --------------------------------------------------------
-- Structure de la table "PREFIXsecretaire_occupe_diplome";

DROP TABLE IF EXISTS "PREFIXsecretaire_occupe_diplome";
CREATE TABLE "PREFIXsecretaire_occupe_diplome" (
  "id_secretaire" INT NOT NULL default 0,
  "id_diplome" INT default NULL,
  "id_option" INT default NULL
);

-- --------------------------------------------------------
-- Structure de la table "PREFIXsecretaire_occupe_pool";

DROP TABLE IF EXISTS "PREFIXsecretaire_occupe_pool";
CREATE TABLE "PREFIXsecretaire_occupe_pool" (
  "id_secretaire" INT NOT NULL default 0,
  "id_pool" INT NOT NULL default 0,
  PRIMARY KEY("id_secretaire","id_pool")
);

-- --------------------------------------------------------
-- Structure de la table "PREFIXspecialite";

DROP TABLE IF EXISTS "PREFIXspecialite";
DROP SEQUENCE IF EXISTS "PREFIXspecialite_id_seq";
CREATE SEQUENCE "PREFIXspecialite_id_seq";
CREATE TABLE "PREFIXspecialite" (
  "id" INT NOT NULL UNIQUE,
  "libelle" VARCHAR(50) NOT NULL default '',
  PRIMARY KEY("id")
);
ALTER TABLE "PREFIXspecialite"
    ALTER COLUMN "id"
        SET DEFAULT NEXTVAL('PREFIXspecialite_id_seq');

-- --------------------------------------------------------
-- Structure de la table "PREFIXsuperviseur";

DROP TABLE IF EXISTS "PREFIXsuperviseur";
DROP SEQUENCE IF EXISTS "PREFIXsuperviseur_id_seq";
CREATE SEQUENCE "PREFIXsuperviseur_id_seq";
CREATE TABLE "PREFIXsuperviseur" (
  "id" INT NOT NULL UNIQUE,
  "nom" VARCHAR(64) NOT NULL default '',
  "prenom" VARCHAR(64) NOT NULL default '',
  "tel" VARCHAR(15) NOT NULL default '',
  "fax" VARCHAR(15) NOT NULL default '',
  "email" VARCHAR(64) NOT NULL default '',
  "id_enseignant" INT default NULL,
  PRIMARY KEY("id")
);
ALTER TABLE "PREFIXsuperviseur"
    ALTER COLUMN "id"
        SET DEFAULT NEXTVAL('PREFIXsuperviseur_id_seq');

-- --------------------------------------------------------
-- Structure de la table "PREFIXtype_salle";

DROP TABLE IF EXISTS "PREFIXtype_salle";
DROP SEQUENCE IF EXISTS "PREFIXtype_salle_id_seq";
CREATE SEQUENCE "PREFIXtype_salle_id_seq";
CREATE TABLE "PREFIXtype_salle" (
  "id" INT NOT NULL UNIQUE,
  "libelle" VARCHAR(50) NOT NULL default '',
  PRIMARY KEY("id")
);
ALTER TABLE "PREFIXtype_salle"
    ALTER COLUMN "id"
        SET DEFAULT NEXTVAL('PREFIXtype_salle_id_seq');

-- --------------------------------------------------------
-- Structure de la table "PREFIXtype_seance";

DROP TABLE IF EXISTS "PREFIXtype_seance";
DROP SEQUENCE IF EXISTS "PREFIXtype_seance_id_seq";
CREATE SEQUENCE "PREFIXtype_seance_id_seq";
CREATE TABLE "PREFIXtype_seance" (
  "id" INT NOT NULL UNIQUE,
  "libelle" VARCHAR(50) NOT NULL default '',
  PRIMARY KEY("id")
);
ALTER TABLE "PREFIXtype_seance"
    ALTER COLUMN "id"
        SET DEFAULT NEXTVAL('PREFIXtype_seance_id_seq');

-- --------------------------------------------------------
-- Structure de la table "PREFIXuser_est_de_type";

DROP TABLE IF EXISTS "PREFIXuser_est_de_type";
CREATE TABLE "PREFIXuser_est_de_type" (
  "id_user" INT NOT NULL default 0,
  "id_type" INT NOT NULL default 0,
  "id" INT NOT NULL default 0,
  PRIMARY KEY("id_user","id_type")
);

-- --------------------------------------------------------
-- Structure de la table "PREFIXuser_type";

DROP TABLE IF EXISTS "PREFIXuser_type";
DROP SEQUENCE IF EXISTS "PREFIXuser_type_id_seq";
CREATE SEQUENCE "PREFIXuser_type_id_seq";
CREATE TABLE "PREFIXuser_type" (
  "id_type" INT NOT NULL UNIQUE,
  "libelle" VARCHAR(32) NOT NULL default '',
  PRIMARY KEY("id_type")
);
ALTER TABLE "PREFIXuser_type"
    ALTER COLUMN "id_type"
        SET DEFAULT NEXTVAL('PREFIXuser_type_id_seq');

-- --------------------------------------------------------
-- Structure de la table "PREFIXuser";

DROP TABLE IF EXISTS "PREFIXuser";
DROP SEQUENCE IF EXISTS "PREFIXuser_id_seq";
CREATE SEQUENCE "PREFIXuser_id_seq" START WITH 2;
CREATE TABLE "PREFIXuser" (
  "id_user" INT NOT NULL UNIQUE,
  "login" VARCHAR(32) NOT NULL default '',
  "password" VARCHAR(32) NOT NULL default '',
  "actif" BOOLEAN default FALSE,
  PRIMARY KEY("id_user")
);
ALTER TABLE "PREFIXuser"
    ALTER COLUMN "id_user"
        SET DEFAULT NEXTVAL('PREFIXuser_id_seq');

-- --------------------------------------------------------
-- Menu;

INSERT INTO "PREFIXmenu_cat" VALUES (1, 'G&eacute;n&eacute;ral', 1, 1);
INSERT INTO "PREFIXmenu_cat" VALUES (2, 'Promotion', 1, 2);
INSERT INTO "PREFIXmenu_cat" VALUES (3, 'Emploi du temps', 2, 2);
INSERT INTO "PREFIXmenu_cat" VALUES (4, 'Module', 1, 3);
INSERT INTO "PREFIXmenu_cat" VALUES (5, 'G&eacute;n&eacute;ral', 2, 1);
INSERT INTO "PREFIXmenu_cat" VALUES (16, 'Secr&eacute;taire', 1, 4);
INSERT INTO "PREFIXmenu_cat" VALUES (17, 'Superviseur', 1, 5);
INSERT INTO "PREFIXmenu_cat" VALUES (18, 'Bilans', 1, 6);
INSERT INTO "PREFIXmenu_cat" VALUES (19, 'Promotion', 2, 3);
INSERT INTO "PREFIXmenu_cat" VALUES (20, 'Bilans', 2, 4);
INSERT INTO "PREFIXmenu_cat" VALUES (21, 'G&eacute;n&eacute;ral', 5, 1);
INSERT INTO "PREFIXmenu_cat" VALUES (22, 'Promotion', 5, 2);
INSERT INTO "PREFIXmenu_cat" VALUES (23, 'G&eacute;n&eacute;ral', 3, 1);
INSERT INTO "PREFIXmenu_cat" VALUES (24, 'Cursus', 3, 2);
INSERT INTO "PREFIXmenu_cat" VALUES (25, 'Emploi du temps', 3, 3);
INSERT INTO "PREFIXmenu_cat" VALUES (26, 'G&eacute;n&eacute;ral', 4, 1);
INSERT INTO "PREFIXmenu_cat" VALUES (27, 'Promotion', 4, 2);
INSERT INTO "PREFIXmenu_cat" VALUES (28, 'Emploi du temps', 4, 3);
INSERT INTO "PREFIXmenu_cat" VALUES (30, 'Administration', 1, 7);
INSERT INTO "PREFIXmenu_cat" VALUES (31, 'G&eacute;n&eacute;ral', 6, 1);
INSERT INTO "PREFIXmenu_cat" VALUES (32, 'Dipl&ocirc;me', 6, 2);
INSERT INTO "PREFIXmenu_cat" VALUES (33, 'Module', 6, 3);
INSERT INTO "PREFIXmenu_cat" VALUES (34, 'Bilans', 6, 4);
INSERT INTO "PREFIXmenu_cat" VALUES (35, 'G&eacute;n&eacute;ral', 7, 1);
INSERT INTO "PREFIXmenu_cat" VALUES (36, 'Emploi du temps', 7, 2);
INSERT INTO "PREFIXmenu_cat" VALUES (37, 'Promotion', 7, 3);
INSERT INTO "PREFIXmenu_cat" VALUES (38, 'G&eacute;n&eacute;ral', 8, 1);
INSERT INTO "PREFIXmenu_cat" VALUES (39, 'Enseignant', 8, 2);
INSERT INTO "PREFIXmenu_cat" VALUES (40, 'Bilans', 8, 3);

-- --------------------------------------------------------
-- Données Menu;

INSERT INTO "PREFIXmenu_data" VALUES (23, 1, 1, 'D&eacute;connexion', 'login', 36, 6);
INSERT INTO "PREFIXmenu_data" VALUES (2, 2, 1, 'Dipl&ocirc;me', 'diplome', 7, 1);
INSERT INTO "PREFIXmenu_data" VALUES (3, 1, 1, 'Libell&eacute;s', 'libelles', 1, 1);
INSERT INTO "PREFIXmenu_data" VALUES (22, 1, 1, 'Changer son mot de passe', 'motdepasse', 5, 5);
INSERT INTO "PREFIXmenu_data" VALUES (6, 2, 1, 'Option', 'option', 8, 2);
INSERT INTO "PREFIXmenu_data" VALUES (8, 1, 1, 'Calendrier', 'calendrier', 2, 2);
INSERT INTO "PREFIXmenu_data" VALUES (20, 1, 1, 'Enseignant', 'enseignant', 3, 3);
INSERT INTO "PREFIXmenu_data" VALUES (14, 4, 1, 'Module', 'module', 15, 1);
INSERT INTO "PREFIXmenu_data" VALUES (17, 2, 1, 'Groupe', 'groupe', 9, 3);
INSERT INTO "PREFIXmenu_data" VALUES (21, 1, 1, 'Gestion des utilisateurs', 'userlist', 4, 4);
INSERT INTO "PREFIXmenu_data" VALUES (24, 2, 1, 'Constitution des groupes', 'constitution', 10, 4);
INSERT INTO "PREFIXmenu_data" VALUES (25, 2, 1, 'Groupes virtuels', 'groupe_virtuel', 14, 5);
INSERT INTO "PREFIXmenu_data" VALUES (26, 2, 1, '&Eacute;tudiant', 'etudiant', 11, 6);
INSERT INTO "PREFIXmenu_data" VALUES (27, 4, 1, 'Volume horaire des modules', 'diviser', 16, 2);
INSERT INTO "PREFIXmenu_data" VALUES (28, 4, 1, 'R&eacute;partition des modules', 'assurer', 17, 3);
INSERT INTO "PREFIXmenu_data" VALUES (29, 16, 1, 'Secr&eacute;taire', 'secretaire', 25, 1);
INSERT INTO "PREFIXmenu_data" VALUES (30, 16, 1, 'Dipl&ocirc;mes g&eacute;r&eacute;s', 'secretaire_gere', 26, 2);
INSERT INTO "PREFIXmenu_data" VALUES (31, 17, 1, 'Superviseur', 'superviseur', 27, 1);
INSERT INTO "PREFIXmenu_data" VALUES (32, 18, 1, 'Charges', 'charges', 28, 1);
INSERT INTO "PREFIXmenu_data" VALUES (33, 5, 2, 'Changer son mot de passe', 'motdepasse', 5, 1);
INSERT INTO "PREFIXmenu_data" VALUES (34, 5, 2, 'D&eacute;connexion', 'login', 36, 2);
INSERT INTO "PREFIXmenu_data" VALUES (35, 3, 2, 'Mes modules', 'mes_modules', 22, 1);
INSERT INTO "PREFIXmenu_data" VALUES (36, 3, 2, 'Mon emplois du temps', 'PREFIXdiplome', 18, 2);
INSERT INTO "PREFIXmenu_data" VALUES (37, 19, 2, 'Trombinoscope', 'trombinoscope', 12, 1);
INSERT INTO "PREFIXmenu_data" VALUES (38, 20, 2, 'Suivi', 'suivi', 31, 1);
INSERT INTO "PREFIXmenu_data" VALUES (39, 20, 2, 'Mon service', 'mon_service', 29, 2);
INSERT INTO "PREFIXmenu_data" VALUES (40, 21, 5, 'Changer son mot de passe', 'motdepasse', 5, 1);
INSERT INTO "PREFIXmenu_data" VALUES (41, 21, 5, 'D&eacute;connexion', 'login', 36, 2);
INSERT INTO "PREFIXmenu_data" VALUES (42, 22, 5, 'Liste des modules', 'liste_module', 23, 1);
INSERT INTO "PREFIXmenu_data" VALUES (43, 22, 5, 'Trombinoscope', 'trombinoscope', 12, 2);
INSERT INTO "PREFIXmenu_data" VALUES (44, 23, 3, 'Mes informations', 'vue_etudiant', 6, 1);
INSERT INTO "PREFIXmenu_data" VALUES (45, 23, 3, 'Changer son mot de passe', 'motdepasse', 5, 2);
INSERT INTO "PREFIXmenu_data" VALUES (46, 24, 3, 'Mon cursus', 'liste_module', 23, 1);
INSERT INTO "PREFIXmenu_data" VALUES (47, 25, 3, 'Mon emplois du temps', 'PREFIXetudiant', 19, 1);
INSERT INTO "PREFIXmenu_data" VALUES (48, 26, 4, 'Changer son mot de passe', 'motdepasse', 5, 1);
INSERT INTO "PREFIXmenu_data" VALUES (49, 26, 4, 'D&eacute;connexion', 'login', 36, 2);
INSERT INTO "PREFIXmenu_data" VALUES (50, 27, 4, 'Constitution des groupes', 'constitution', 10, 1);
INSERT INTO "PREFIXmenu_data" VALUES (51, 27, 4, 'Trombinoscope', 'trombinoscope', 12, 2);
INSERT INTO "PREFIXmenu_data" VALUES (52, 28, 4, 'Liste des modules', 'liste_module', 23, 1);
INSERT INTO "PREFIXmenu_data" VALUES (53, 28, 4, 'Planification hebdomadaire', 'repartir', 20, 2);
INSERT INTO "PREFIXmenu_data" VALUES (54, 28, 4, 'Planification journali&egravere', 'planifier', 21, 3);
INSERT INTO "PREFIXmenu_data" VALUES (55, 28, 4, 'Validation', 'PREFIXdiplome', 18, 4);
INSERT INTO "PREFIXmenu_data" VALUES (57, 30, 1, 'Gestion Menu', 'gestion_menu', 37, 1);
INSERT INTO "PREFIXmenu_data" VALUES (59, 23, 3, 'D&eacute;connexion', 'login', 36, 3);
INSERT INTO "PREFIXmenu_data" VALUES (60, 0, 1, '', 'menu', 39, 1);
INSERT INTO "PREFIXmenu_data" VALUES (61, 31, 6, 'Changer son mot de passe', 'motdepasse', 5, 1);
INSERT INTO "PREFIXmenu_data" VALUES (62, 31, 6, 'D&eacute;connexion', 'login', 36, 2);
INSERT INTO "PREFIXmenu_data" VALUES (63, 32, 6, 'Mes dipl&ocirc;me', 'mes_diplomes', 32, 1);
INSERT INTO "PREFIXmenu_data" VALUES (64, 32, 6, 'Charges par dipl&ocirc;me', 'liste_module', 23, 2);
INSERT INTO "PREFIXmenu_data" VALUES (65, 33, 6, 'Module', 'module', 15, 1);
INSERT INTO "PREFIXmenu_data" VALUES (66, 33, 6, 'Volume horaire des modules', 'diviser', 16, 2);
INSERT INTO "PREFIXmenu_data" VALUES (67, 33, 6, 'R&eacute;partition des modules', 'assurer', 17, 3);
INSERT INTO "PREFIXmenu_data" VALUES (68, 34, 6, 'Charges', 'charges', 28, 1);
INSERT INTO "PREFIXmenu_data" VALUES (69, 35, 7, 'Changer son mot de passe', 'motdepasse', 5, 1);
INSERT INTO "PREFIXmenu_data" VALUES (70, 35, 7, 'D&eacute;connexion', 'login', 36, 2);
INSERT INTO "PREFIXmenu_data" VALUES (71, 36, 7, 'Listes des modules', 'liste_module', 23, 1);
INSERT INTO "PREFIXmenu_data" VALUES (72, 36, 7, 'Planification journali&egrave;re', 'planifier', 21, 2);
INSERT INTO "PREFIXmenu_data" VALUES (73, 36, 7, 'Affectation des salles', 'affecter', 24, 3);
INSERT INTO "PREFIXmenu_data" VALUES (74, 37, 7, '&Eacute;tudiant', 'etudiant', 11, 1);
INSERT INTO "PREFIXmenu_data" VALUES (75, 37, 7, 'Trombinoscope', 'trombinoscope', 12, 2);
INSERT INTO "PREFIXmenu_data" VALUES (76, 38, 8, 'Changer son mot de passe', 'motdepasse', 5, 1);
INSERT INTO "PREFIXmenu_data" VALUES (77, 38, 8, 'D&eacute;connexion', 'login', 36, 2);
INSERT INTO "PREFIXmenu_data" VALUES (78, 39, 8, 'Suivis enseignant', 'suivi', 31, 1);
INSERT INTO "PREFIXmenu_data" VALUES (79, 39, 8, 'Services enseignant', 'mon_service', 29, 2);
INSERT INTO "PREFIXmenu_data" VALUES (80, 39, 8, 'Tous les services enseignant', 'tous_les_services', 30, 3);
INSERT INTO "PREFIXmenu_data" VALUES (81, 40, 8, 'Charges par dipl&ocirc;me', 'liste_module', 23, 1);
INSERT INTO "PREFIXmenu_data" VALUES (82, 40, 8, 'Charges par d&eacute;partement', 'charges', 28, 2);
INSERT INTO "PREFIXmenu_data" VALUES (88, 0, 2, '', 'menu', 39, 1);
INSERT INTO "PREFIXmenu_data" VALUES (87, 0, 5, '', 'menu', 39, 1);
INSERT INTO "PREFIXmenu_data" VALUES (89, 0, 3, '', 'menu', 39, 1);
INSERT INTO "PREFIXmenu_data" VALUES (90, 0, 4, '', 'menu', 39, 1);
INSERT INTO "PREFIXmenu_data" VALUES (91, 0, 6, '', 'menu', 39, 1);
INSERT INTO "PREFIXmenu_data" VALUES (92, 0, 7, '', 'menu', 39, 1);
INSERT INTO "PREFIXmenu_data" VALUES (93, 0, 8, '', 'menu', 39, 1);
INSERT INTO "PREFIXmenu_data" VALUES (96, 0, 1, '', 'PREFIXdiplome', 18, 2);
INSERT INTO "PREFIXmenu_data" VALUES (97, 0, 3, '', 'PREFIXdiplome', 18, 2);
INSERT INTO "PREFIXmenu_data" VALUES (98, 0, 2, '', 'etudiant_visu', 13, 2);
INSERT INTO "PREFIXmenu_data" VALUES (99, 0, 4, '', 'etudiant_visu', 13, 2);
INSERT INTO "PREFIXmenu_data" VALUES (100, 0, 5, '', 'etudiant_visu', 13, 2);
INSERT INTO "PREFIXmenu_data" VALUES (101, 0, 7, '', 'etudiant_visu', 13, 2);
INSERT INTO "PREFIXmenu_data" VALUES (102, 0, 7, '', 'PREFIXsalle', 33, 3);

-- --------------------------------------------------------
-- Liens Menu;

INSERT INTO "PREFIXmenu_lien" VALUES (1, 'general/libelles.php', TRUE);
INSERT INTO "PREFIXmenu_lien" VALUES (2, 'general/calendrier.php', TRUE);
INSERT INTO "PREFIXmenu_lien" VALUES (3, 'general/enseignant.php', TRUE);
INSERT INTO "PREFIXmenu_lien" VALUES (4, 'general/userlist.php', TRUE);
INSERT INTO "PREFIXmenu_lien" VALUES (5, 'motdepasse.php', TRUE);
INSERT INTO "PREFIXmenu_lien" VALUES (6, 'etudiant/vue_etudiant.php', TRUE);
INSERT INTO "PREFIXmenu_lien" VALUES (7, 'promotion/diplome.php', TRUE);
INSERT INTO "PREFIXmenu_lien" VALUES (8, 'promotion/option.php', TRUE);
INSERT INTO "PREFIXmenu_lien" VALUES (9, 'promotion/groupe.php', TRUE);
INSERT INTO "PREFIXmenu_lien" VALUES (10, 'promotion/constitution_groupe.php', TRUE);
INSERT INTO "PREFIXmenu_lien" VALUES (11, 'promotion/etudiant.php', TRUE);
INSERT INTO "PREFIXmenu_lien" VALUES (12, 'etudiant/trombinoscope.php', TRUE);
INSERT INTO "PREFIXmenu_lien" VALUES (13, 'etudiant/etudiant_visu.php', TRUE);
INSERT INTO "PREFIXmenu_lien" VALUES (14, 'promotion/groupe_virtuel.php', TRUE);
INSERT INTO "PREFIXmenu_lien" VALUES (15, 'module.php', TRUE);
INSERT INTO "PREFIXmenu_lien" VALUES (16, 'diviser.php', TRUE);
INSERT INTO "PREFIXmenu_lien" VALUES (17, 'assurer.php', TRUE);
INSERT INTO "PREFIXmenu_lien" VALUES (18, 'edt/diplome.php', TRUE);
INSERT INTO "PREFIXmenu_lien" VALUES (19, 'edt/etudiant.php', TRUE);
INSERT INTO "PREFIXmenu_lien" VALUES (20, 'repartir.php', TRUE);
INSERT INTO "PREFIXmenu_lien" VALUES (21, 'planifier.php', TRUE);
INSERT INTO "PREFIXmenu_lien" VALUES (22, 'enseignant/module.php', TRUE);
INSERT INTO "PREFIXmenu_lien" VALUES (23, 'bilan/liste_module.php', TRUE);
INSERT INTO "PREFIXmenu_lien" VALUES (24, 'salle/affecter.php', TRUE);
INSERT INTO "PREFIXmenu_lien" VALUES (25, 'secretaire/secretaire.php', TRUE);
INSERT INTO "PREFIXmenu_lien" VALUES (26, 'secretaire/gestion_diplomes.php', TRUE);
INSERT INTO "PREFIXmenu_lien" VALUES (27, 'superviseur/superviseur.php', TRUE);
INSERT INTO "PREFIXmenu_lien" VALUES (28, 'bilan/charges.php', TRUE);
INSERT INTO "PREFIXmenu_lien" VALUES (29, 'enseignant/service.php', TRUE);
INSERT INTO "PREFIXmenu_lien" VALUES (30, 'enseignant/services.php', TRUE);
INSERT INTO "PREFIXmenu_lien" VALUES (31, 'bilan/suivi.php', TRUE);
INSERT INTO "PREFIXmenu_lien" VALUES (32, 'directeur/mes_diplomes.php', TRUE);
INSERT INTO "PREFIXmenu_lien" VALUES (33, 'edt/salle.php', TRUE);
INSERT INTO "PREFIXmenu_lien" VALUES (36, 'login.php', FALSE);
INSERT INTO "PREFIXmenu_lien" VALUES (37, 'administration/gestion_menu.php', FALSE);
INSERT INTO "PREFIXmenu_lien" VALUES (39, 'menu.php', FALSE);

-- --------------------------------------------------------
-- Types utilisateurs;

INSERT INTO "PREFIXuser_type" VALUES (1, 'Administrateur');
INSERT INTO "PREFIXuser_type" VALUES (2, 'Enseignant');
INSERT INTO "PREFIXuser_type" VALUES (3, 'Etudiant');
INSERT INTO "PREFIXuser_type" VALUES (4, 'Directeur d''&eacute;tudes');
INSERT INTO "PREFIXuser_type" VALUES (5, 'Pr&eacute;sident de jury');
INSERT INTO "PREFIXuser_type" VALUES (6, 'Directeur de d&eacute;partement');
INSERT INTO "PREFIXuser_type" VALUES (7, 'Secr&eacute;taire');
INSERT INTO "PREFIXuser_type" VALUES (8, 'Superviseur');

-- --------------------------------------------------------
-- Création du compte administrateur;

INSERT INTO "PREFIXuser" VALUES (1, 'admin', '', FALSE);
INSERT INTO "PREFIXuser_est_de_type" VALUES (1, 1, 1);
