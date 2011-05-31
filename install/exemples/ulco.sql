INSERT INTO "edt_domaine" VALUES (1, 'Sciences et Technologies');
INSERT INTO "edt_domaine" VALUES (2, 'Droit');
INSERT INTO "edt_domaine" VALUES (3, 'Lettres et langues');
INSERT INTO "edt_domaine" VALUES (4, 'Sciences Humaines et Sociales');
INSERT INTO "edt_domaine" VALUES (5, 'Sciences &eacute;co et gestion');

INSERT INTO "edt_niveau" VALUES (1, 'Licence', 3);
INSERT INTO "edt_niveau" VALUES (3, 'Doctorat', 3);
INSERT INTO "edt_niveau" VALUES (2, 'Master', 2);

INSERT INTO edt_annee (numero,id_niveau) VALUES (1,1);
INSERT INTO edt_annee (numero,id_niveau) VALUES (2,1);
INSERT INTO edt_annee (numero,id_niveau) VALUES (3,1);
INSERT INTO edt_annee (numero,id_niveau) VALUES (1,2);
INSERT INTO edt_annee (numero,id_niveau) VALUES (2,2);
INSERT INTO edt_annee (numero,id_niveau) VALUES (1,3);
INSERT INTO edt_annee (numero,id_niveau) VALUES (2,3);
INSERT INTO edt_annee (numero,id_niveau) VALUES (3,3);

INSERT INTO "edt_mention" VALUES (1, 'Informatique');
INSERT INTO "edt_mention" VALUES (2, 'Math&eacute;matiques');
INSERT INTO "edt_mention" VALUES (3, 'MSPI');
INSERT INTO "edt_mention" VALUES (4, 'Biologie');
INSERT INTO "edt_mention" VALUES (5, 'Chimie - Physique');
INSERT INTO "edt_mention" VALUES (6, 'EEA');
INSERT INTO "edt_mention" VALUES (7, 'GSI - CMA');
INSERT INTO "edt_mention" VALUES (8, 'STAPS');
INSERT INTO "edt_mention" VALUES (9, 'Economie appliqu&eacute;e');
INSERT INTO "edt_mention" VALUES (10, 'Gestion');
INSERT INTO "edt_mention" VALUES (11, 'Sciences economiques et sociales appliquees');
INSERT INTO "edt_mention" VALUES (13, 'Administration publique');
INSERT INTO "edt_mention" VALUES (14, 'Droit');
INSERT INTO "edt_mention" VALUES (15, 'LCE - Allemand');
INSERT INTO "edt_mention" VALUES (16, 'LCE - Espagnol');
INSERT INTO "edt_mention" VALUES (17, 'LCE - Anglais');
INSERT INTO "edt_mention" VALUES (18, 'LEA');
INSERT INTO "edt_mention" VALUES (19, 'Lettres modernes');
INSERT INTO "edt_mention" VALUES (20, 'Culture et m&eacute;dias');
INSERT INTO "edt_mention" VALUES (21, 'G&eacute;ographie');
INSERT INTO "edt_mention" VALUES (22, 'Histoire');

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

INSERT INTO "edt_grade" VALUES (1, 'ATER', 192);
INSERT INTO "edt_grade" VALUES (2, 'Etudiant', 96);
INSERT INTO "edt_grade" VALUES (3, 'IATOS', 20);
INSERT INTO "edt_grade" VALUES (4, 'Ma&icirc;tre de conf&eacute;rence', 192);
INSERT INTO "edt_grade" VALUES (5, 'Ma&icirc;tre de conf&eacute;rence hors classe', 192);
INSERT INTO "edt_grade" VALUES (6, 'Moniteur CIES', 64);
INSERT INTO "edt_grade" VALUES (7, 'PRAG', 384);
INSERT INTO "edt_grade" VALUES (8, 'Professeur 1&egrave;re classe', 192);
INSERT INTO "edt_grade" VALUES (9, 'Professeur 2&egrave;me classe', 192);
INSERT INTO "edt_grade" VALUES (10, 'Professeur hors classe', 192);
INSERT INTO "edt_grade" VALUES (11, 'Vacataire', 160);
INSERT INTO "edt_grade" VALUES (12, '1/2 ATER', 96);
INSERT INTO "edt_grade" VALUES (13, 'PRCE', 384);

INSERT INTO "edt_type_seance" VALUES (1, 'CM');
INSERT INTO "edt_type_seance" VALUES (2, 'TD');
INSERT INTO "edt_type_seance" VALUES (3, 'TP');
INSERT INTO "edt_type_seance" VALUES (4, 'Examen');

INSERT INTO "edt_type_salle" VALUES (1, 'Amphi');
INSERT INTO "edt_type_salle" VALUES (2, 'TD');
