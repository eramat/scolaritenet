INSERT INTO "edt_domaine" (libelle) VALUES ('Sciences et Technologies');
INSERT INTO "edt_domaine" (libelle) VALUES ('Droit');
INSERT INTO "edt_domaine" (libelle) VALUES ('Lettres et langues');
INSERT INTO "edt_domaine" (libelle) VALUES ('Sciences Humaines et Sociales');
INSERT INTO "edt_domaine" (libelle) VALUES ('Sciences &eacute;co et gestion');

INSERT INTO "edt_niveau" (libelle,nombre_annees) VALUES ('Licence', 3);
INSERT INTO "edt_niveau" (libelle,nombre_annees) VALUES ('Master', 2);
INSERT INTO "edt_niveau" (libelle,nombre_annees) VALUES ('Doctorat', 3);

INSERT INTO edt_annee (numero,id_niveau) VALUES (1,1);
INSERT INTO edt_annee (numero,id_niveau) VALUES (2,1);
INSERT INTO edt_annee (numero,id_niveau) VALUES (3,1);
INSERT INTO edt_annee (numero,id_niveau) VALUES (1,2);
INSERT INTO edt_annee (numero,id_niveau) VALUES (2,2);
INSERT INTO edt_annee (numero,id_niveau) VALUES (1,3);
INSERT INTO edt_annee (numero,id_niveau) VALUES (2,3);
INSERT INTO edt_annee (numero,id_niveau) VALUES (3,3);

INSERT INTO "edt_mention" (libelle) VALUES ('Informatique');
INSERT INTO "edt_mention" (libelle) VALUES ('Math&eacute;matiques');
INSERT INTO "edt_mention" (libelle) VALUES ('MSPI');
INSERT INTO "edt_mention" (libelle) VALUES ('Biologie');
INSERT INTO "edt_mention" (libelle) VALUES ('Chimie - Physique');
INSERT INTO "edt_mention" (libelle) VALUES ('EEA');
INSERT INTO "edt_mention" (libelle) VALUES ('GSI - CMA');
INSERT INTO "edt_mention" (libelle) VALUES ('STAPS');
INSERT INTO "edt_mention" (libelle) VALUES ('Economie appliqu&eacute;e');
INSERT INTO "edt_mention" (libelle) VALUES ('Gestion');
INSERT INTO "edt_mention" (libelle) VALUES ('Sciences economiques et sociales appliquees');
INSERT INTO "edt_mention" (libelle) VALUES ('Administration publique');
INSERT INTO "edt_mention" (libelle) VALUES ('Droit');
INSERT INTO "edt_mention" (libelle) VALUES ('LCE - Allemand');
INSERT INTO "edt_mention" (libelle) VALUES ('LCE - Espagnol');
INSERT INTO "edt_mention" (libelle) VALUES ('LCE - Anglais');
INSERT INTO "edt_mention" (libelle) VALUES ('LEA');
INSERT INTO "edt_mention" (libelle) VALUES ('Lettres modernes');
INSERT INTO "edt_mention" (libelle) VALUES ('Culture et m&eacute;dias');
INSERT INTO "edt_mention" (libelle) VALUES ('G&eacute;ographie');
INSERT INTO "edt_mention" (libelle) VALUES ('Histoire');

INSERT INTO "edt_specialite" (libelle) VALUES ('Informatique');
INSERT INTO "edt_specialite" (libelle) VALUES ('Math&eacute;matiques');
INSERT INTO "edt_specialite" (libelle) VALUES ('Physique');

INSERT INTO "edt_pole" (libelle) VALUES ('CGU de Calais');

INSERT INTO "edt_departement" (libelle) VALUES ('Informatique');
INSERT INTO "edt_departement" (libelle) VALUES ('Langues');
INSERT INTO "edt_departement" (libelle) VALUES ('Math&eacute;matiques');
INSERT INTO "edt_departement" (libelle) VALUES ('Physique');
INSERT INTO "edt_departement" (libelle) VALUES ('Droit');
INSERT INTO "edt_departement" (libelle) VALUES ('Eco-gestion');
INSERT INTO "edt_departement" (libelle) VALUES ('EEA');
INSERT INTO "edt_departement" (libelle) VALUES ('Biologie');
INSERT INTO "edt_departement" (libelle) VALUES ('STAPS');
INSERT INTO "edt_departement" (libelle) VALUES ('G&eacute;ographie');
INSERT INTO "edt_departement" (libelle) VALUES ('Histoire');

INSERT INTO "edt_grade" (libelle, nombre_heures) VALUES ('ATER', 192);
INSERT INTO "edt_grade" (libelle, nombre_heures) VALUES ('Etudiant', 96);
INSERT INTO "edt_grade" (libelle, nombre_heures) VALUES ('IATOS', 20);
INSERT INTO "edt_grade" (libelle, nombre_heures) VALUES ('Ma&icirc;tre de conf&eacute;rence', 192);
INSERT INTO "edt_grade" (libelle, nombre_heures) VALUES ('Ma&icirc;tre de conf&eacute;rence hors classe', 192);
INSERT INTO "edt_grade" (libelle, nombre_heures) VALUES ('Moniteur CIES', 64);
INSERT INTO "edt_grade" (libelle, nombre_heures) VALUES ('PRAG', 384);
INSERT INTO "edt_grade" (libelle, nombre_heures) VALUES ('Professeur 1&egrave;re classe', 192);
INSERT INTO "edt_grade" (libelle, nombre_heures) VALUES ('Professeur 2&egrave;me classe', 192);
INSERT INTO "edt_grade" (libelle, nombre_heures) VALUES ('Professeur hors classe', 192);
INSERT INTO "edt_grade" (libelle, nombre_heures) VALUES ('Vacataire', 160);
INSERT INTO "edt_grade" (libelle, nombre_heures) VALUES ('1/2 ATER', 96);
INSERT INTO "edt_grade" (libelle, nombre_heures) VALUES ('PRCE', 384);

INSERT INTO "edt_type_seance" (libelle) VALUES ('CM');
INSERT INTO "edt_type_seance" (libelle) VALUES ('TD');
INSERT INTO "edt_type_seance" (libelle) VALUES ('TP');
INSERT INTO "edt_type_seance" (libelle) VALUES ('Examen');

INSERT INTO "edt_type_salle" VALUES (1, 'Amphi');
INSERT INTO "edt_type_salle" VALUES (2, 'TD');

-- Master I2L --

INSERT INTO edt_calendrier (libelle) VALUES ('Master2');

INSERT INTO edt_periode_travail (numero, date_debut, date_fin) VALUES (1, 1, '2011-09-12', '2011-12-16');
INSERT INTO edt_periode_travail (numero, date_debut, date_fin) VALUES (2, 2, '2012-01-02', '2012-03-30');

INSERT INTO edt_calendrier_travail VALUES (1, 1);
INSERT INTO edt_calendrier_travail VALUES (1, 2);

INSERT INTO edt_enseignant (nom, prenom, initiales, id_grade, id_departement, cnu, titulaire, pes, id_pole, adresse, code_postal, ville, email, telephone) VALUES ('RAMAT', 'Eric', 'ER', 9, 1, '27', true, false, 1, 'xxxxxxxxxxxxxx', 'xxxxxx', 'xxxxxxx', 'ramat@lisic.univ-littoral.fr', 'xxxxxxxxxx');

INSERT INTO edt_diplome (annee, sigle, sigle_complet, id_president_jury, id_directeur_etudes, id_niveau, id_domaine, id_mention, id_specialite, intitule_parcours, id_pole, id_calendrier, id_departement) VALUES (2, 'I2L', 'Master2 I2L', 1, 1, 2, 1, 3, 4, 'Ingenierie du Logiciel Libre', 1, 1, 1);

INSERT INTO edt_module (nom, sigle, credits, num_periode, id_departement, descriptif) VALUES ('Systemes, Securite et Reseaux', 'SRS', 7, 1, 1, '');
INSERT INTO edt_module (nom, sigle, credits, num_periode, id_departement, descriptif) VALUES ('Developpement d applications Web', 'DAW', 7, 1, 1, '');
INSERT INTO edt_module (nom, sigle, credits, num_periode, id_departement, descriptif) VALUES ('Environnement de developpement libres', 'EDL', 6, 1, 1, '');
INSERT INTO edt_module (nom, sigle, credits, num_periode, id_departement, descriptif) VALUES ('Langue vivante I - Anglais', 'LV1', 4, 1, 1, '');
INSERT INTO edt_module (nom, sigle, credits, num_periode, id_departement, descriptif) VALUES ('Latex', 'Latex', 2, 1, 1, '');
INSERT INTO edt_module (nom, sigle, credits, num_periode, id_departement, descriptif) VALUES ('Economie innovante', 'Eco', 2, 1, 6, '');
INSERT INTO edt_module (nom, sigle, credits, num_periode, id_departement, descriptif) VALUES ('Conferences', 'Conf', 2, 1, 1, '');
INSERT INTO edt_module (nom, sigle, credits, num_periode, id_departement, descriptif) VALUES ('L environnement du libre', 'EnvLibre', 3, 2, 1, '');
INSERT INTO edt_module (nom, sigle, credits, num_periode, id_departement, descriptif) VALUES ('Management du libre', 'Manag', 3, 2, 1, '');
INSERT INTO edt_module (nom, sigle, credits, num_periode, id_departement, descriptif) VALUES ('Migration vers le libre et integration du Libre', 'MI', 6, 2, 1, '');
INSERT INTO edt_module (nom, sigle, credits, num_periode, id_departement, descriptif) VALUES ('Projet', 'Projet', 6, 2, 1, '');
INSERT INTO edt_module (nom, sigle, credits, num_periode, id_departement, descriptif) VALUES ('Stage', 'Stage', 12, 2, 1, '');

INSERT INTO edt_module_suivi_diplome VALUES (1, 1);
INSERT INTO edt_module_suivi_diplome VALUES (2, 1);
INSERT INTO edt_module_suivi_diplome VALUES (3, 1);
INSERT INTO edt_module_suivi_diplome VALUES (4, 1);
INSERT INTO edt_module_suivi_diplome VALUES (5, 1);
INSERT INTO edt_module_suivi_diplome VALUES (6, 1);
INSERT INTO edt_module_suivi_diplome VALUES (7, 1);
INSERT INTO edt_module_suivi_diplome VALUES (8, 1);
INSERT INTO edt_module_suivi_diplome VALUES (9, 1);
INSERT INTO edt_module_suivi_diplome VALUES (10, 1);
INSERT INTO edt_module_suivi_diplome VALUES (11, 1);
INSERT INTO edt_module_suivi_diplome VALUES (12, 1);

INSERT INTO edt_module_divise VALUES (1, 1, 12);
INSERT INTO edt_module_divise VALUES (1, 2, 21);
INSERT INTO edt_module_divise VALUES (1, 3, 54);
INSERT INTO edt_module_divise VALUES (1, 4, 4);
INSERT INTO edt_module_divise VALUES (2, 1, 29);
INSERT INTO edt_module_divise VALUES (2, 2, 12);
INSERT INTO edt_module_divise VALUES (2, 3, 57);
INSERT INTO edt_module_divise VALUES (2, 4, 4);
INSERT INTO edt_module_divise VALUES (3, 1, 22);
INSERT INTO edt_module_divise VALUES (3, 3, 54);
INSERT INTO edt_module_divise VALUES (4, 4, 2);
INSERT INTO edt_module_divise VALUES (4, 2, 30);
INSERT INTO edt_module_divise VALUES (4, 4, 2);
INSERT INTO edt_module_divise VALUES (5, 2, 20);
INSERT INTO edt_module_divise VALUES (5, 4, 2);
INSERT INTO edt_module_divise VALUES (6, 1, 12);
INSERT INTO edt_module_divise VALUES (6, 2, 20);
INSERT INTO edt_module_divise VALUES (6, 4, 2);
INSERT INTO edt_module_divise VALUES (7, 1, 30);
INSERT INTO edt_module_divise VALUES (7, 4, 2);
INSERT INTO edt_module_divise VALUES (8, 1, 18);
INSERT INTO edt_module_divise VALUES (8, 2, 3);
INSERT INTO edt_module_divise VALUES (8, 4, 2);
INSERT INTO edt_module_divise VALUES (9, 1, 11);
INSERT INTO edt_module_divise VALUES (9, 2, 8);
INSERT INTO edt_module_divise VALUES (9, 4, 2);
INSERT INTO edt_module_divise VALUES (10, 1, 15);
INSERT INTO edt_module_divise VALUES (10, 2, 12);
INSERT INTO edt_module_divise VALUES (10, 3, 48);
INSERT INTO edt_module_divise VALUES (10, 4, 2);
INSERT INTO edt_module_divise VALUES (11, 4, 2);
INSERT INTO edt_module_divise VALUES (12, 4, 2);

INSERT INTO edt_secretaire (nom, prenom, tel, fax, email, id_pole) VALUES ('CLAQUE', 'Olivia', '0321xxxxx', '0321xxxxx', 'olivia.claque@univ-littoral.fr', 1);

INSERT INTO edt_secretaire_occupe_diplome VALUES (1, 1, 0);

INSERT INTO edt_superviseur VALUES (1, 'RAMAT', 'Eric', '032146xxxx', '', 'ramat@lisic.univ-littoral.fr', 1);

INSERT INTO edt_user VALUES (2, 'eramat', '5d933eef19aee7da192608de61b6c23d', true);
INSERT INTO edt_user VALUES (3, 'oclaque', 'f71dbe52628a3f83a77ab494817525c6', true);

INSERT INTO edt_user_est_de_type VALUES (2, 2, 1);
INSERT INTO edt_user_est_de_type VALUES (2, 8, 1);
INSERT INTO edt_user_est_de_type VALUES (2, 4, 1);
INSERT INTO edt_user_est_de_type VALUES (2, 5, 1);
INSERT INTO edt_user_est_de_type VALUES (3, 7, 1);
