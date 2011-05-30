<?php
// Pour bloquer l'accès direct à cette page
if (!defined("acces_ok"))
    exit;

// Affichage de l'entête
entete("&Eacute;tudiant");

function affiche_menu() {
    echo "<center>\n";
    echo "<p><a href=\"index.php?page=etudiant&mode=liste\">Afficher la liste des &eacute;tudiants</a></p>\n";
    echo "<p><a href=\"index.php?page=etudiant&mode=nouveau\">Nouvel &eacute;tudiant</a></p>\n";
    echo "</center>\n";
}

function charge_donnees_etudiant($id) {
    global $prefix_tables, $DB;
    $row = $DB->GetRow("SELECT ine, nom, prenom, adresse, code_postal, ville, email, tel
                        FROM ".$prefix_tables."etudiant WHERE id_etudiant=".$id);
    $donnees = array("numero_ine" => $row[0], "nom" => $row[1], "prenom" => $row[2],
                     "adresse" => $row[3], "code_postal" => $row[4], "ville" => $row[5],
                     "email" => $row[6], "telephone" => $row[7]);
    return $donnees;
}

function charge_diplomes_etudiant_formulaire($id) {
    global $prefix_tables, $DB;
    $req = "SELECT d.sigle_complet, i.principal
            FROM ".$prefix_tables."diplome d, ".$prefix_tables."inscrit_diplome i
            WHERE d.id_diplome=i.id_diplome AND i.id_etudiant=".$id."
            ORDER BY d.sigle_complet ASC";
    $res = $DB->Execute($req);
    echo "<p align='center'>";
    if ($res->RecordCount()) {
        while ($row = $res->FetchRow()) {
            echo $row[0];
            if ($row[1])
                echo " (inscription principale)";
            echo "<br>";
        }
    } else {
        echo "<i>Aucune inscription enregistr&eacute;e</i>";
    }
    echo "</p>\n";
    $res->Close();
}

function charge_diplomes_etudiant_modification($id) {
    global $prefix_tables, $DB;
    $req = "SELECT d.sigle_complet, i.principal, d.id_diplome
            FROM ".$prefix_tables."diplome d, ".$prefix_tables."inscrit_diplome i
            WHERE d.id_diplome=i.id_diplome AND i.id_etudiant = ".$id."
            ORDER BY d.sigle_complet ASC";
    $res = $DB->Execute($req);
    echo "<table align='center'>";
    if ($res->RecordCount() == 1) {
        $row = $res->FetchRow();
        echo "<tr><td>", $row[0],"</td><td><i><a href=\"index.php?page=etudiant&mode=desinscrire&id=",$id,"&d=",
                $row[2],"\" onClick=\"return confirm('&Ecirc;tes-vous s&ucirc;r ?')\">D&eacute;sinscrire</a></i></td>";
    } elseif ($res->RecordCount()) {
        echo "<form method=\"post\" action=\"index.php?page=etudiant&mode=changer&id=",$id,"\">";
        echo "<tr align='center'><td>Dipl&ocirc;me</td><td>Inscription<br>principale</td></tr>\n";

        while ($row = $res->FetchRow()) {
            echo "<tr><td>", $row[0], "</td><td align='center'><input type='radio' name='inscription_principale' value='",$row[2],"'";
            if ($row[1])
                echo " checked></td><td></td>";
            else
                echo "></td><td><a href=\"index.php?page=etudiant&mode=desinscrire&id=",$id,"&d=",$row[2],"\" onClick=\"return confirm('&Ecirc;tes-vous s&ucirc;r ?')\">D&eacute;sinscrire</a></td>";
            echo "</tr>\n";
        }
        echo "<tr><td colspan='2' align='center'><input type='submit' value='Valider le changement'></td><td></td></tr>\n";
        echo "</form>\n";
    } else {
        echo "<tr><td><i>Aucune inscription enregistr&eacute;e</i></td></tr>";
    }
    echo "</table>\n";
    $res->Close();
}

function affiche_formulaire($id) {
    if ($id > 0) {
        $donnees = charge_donnees_etudiant($id);
    } elseif ($id < 0) {
        $id = -$id;
        $donnees = $_POST;
    } elseif (isset($_POST["nom"])) {
        $donnees = $_POST;
    } else {
        $donnees = array("numero_ine" => "", "nom" => "", "prenom" => "",
                "adresse" => "", "code_postal" => "", "ville" => "", "email" => "", "telephone" => "");
    }

    echo "<form method=\"post\" action=\"index.php?page=etudiant&mode=verifie\">\n";
    echo "<table align='center'>\n";
    // Id de l'étudiant
    echo "<input type=\"hidden\" name=\"id\" value=\"",$id,"\">\n";
    // Numéro INE
    echo "<tr><td>Num&eacute;ro INE</td><td><input type=\"text\" name=\"numero_ine\" size=\"20\" maxlength=\"15\" value=\"",$donnees["numero_ine"],"\"></td></tr>\n";
    // Nom
    echo "<tr><td>Nom</td><td><input type=\"text\" name=\"nom\" size=\"20\" maxlength=\"30\" value=\"",$donnees["nom"],"\"></td></tr>\n";
    // Prénom
    echo "<tr><td>Pr&eacute;nom</td><td><input type=\"text\" name=\"prenom\" size=\"20\" maxlength=\"30\" value=\"",$donnees["prenom"],"\"></td></tr>\n";
    // Adresse
    echo "<tr><td>Adresse</td><td><input type=\"text\" name=\"adresse\" size=\"35\" maxlength=\"75\" value=\"",$donnees["adresse"],"\"></td></tr>\n";
    // Code postal
    echo "<tr><td>Code postal</td><td><input type=\"text\" name=\"code_postal\" size=\"5\" maxlength=\"5\" value=\"",$donnees["code_postal"],"\"></td></tr>\n";
    // Ville
    echo "<tr><td>Ville</td><td><input type=\"text\" name=\"ville\" size=\"20\" maxlength=\"30\" value=\"",$donnees["ville"],"\"></td></tr>\n";
    // Email
    echo "<tr><td>Email</td><td><input type=\"text\" name=\"email\" size=\"20\" maxlength=\"75\" value=\"",$donnees["email"],"\"></td></tr>\n";
    // Téléphone
    echo "<tr><td>T&eacute;l&eacute;phone</td><td><input type=\"text\" name=\"telephone\" size=\"20\" maxlength=\"20\" value=\"",$donnees["telephone"],"\"></td></tr>\n";
    // Affichage du bouton de validation du formulaire
    if ($id)
        echo "<tr><td></td><td><input type=\"submit\" value=\"Mettre &agrave; jour\"></td></tr>\n";
    else
        echo "<tr><td></td><td><input type=\"submit\" value=\"Ajouter\"></td></tr>\n";
    echo "</table\n>";
    echo "</form>\n";
}

// Création du pseudo de l'étudiant
function creer_pseudo($nom, $prenom, $id) {
    global $prefix_tables, $DB;
    $id_type = 3; // id du type étudiant
    $pseudo = substr(strtolower($prenom), 0, 1).strtolower($nom);
    
    // On teste d'abord si le pseudo existe déjà
    $exist = $DB->GetOne("SELECT COUNT(*) FROM ".$prefix_tables."users WHERE login='".$pseudo."'");
    if ($exist) {
        $i = 0;
        do {
            $i++;
            $exist = $DB->GetOne("SELECT COUNT(*) FROM ".$prefix_tables."users WHERE login='".$pseudo.$i."'");
        } while ($exist);
        $pseudo .= $i;
    }
    
    // Ajout des données
    $DB->Execute("INSERT INTO ".$prefix_tables."users (login, password, actif) VALUES (?, ?, 0)",
                  array($pseudo, md5("")));
    $id_user = $DB->Insert_ID();
    $DB->Execute("INSERT INTO ".$prefix_tables."user_est_de_type (id_user, id_type, id) VALUES (?, ?, ?)",
                  array($id_user, $id_type, $id));
}

// Ajout d'un nouvel étudiant dans la base de données
function ajoute_donnees() {
    global $prefix_tables, $DB;
    $requete = "INSERT INTO ".$prefix_tables."etudiant
                (ine, nom, prenom, adresse, code_postal, ville, email, tel) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $requete_data = array($_POST["numero_ine"], strtoupper($_POST["nom"]), ucfirst($_POST["prenom"]),
                          $_POST["adresse"], $_POST["code_postal"],
                          $_POST["ville"], $_POST["email"], $_POST["telephone"]);
    $DB->Execute($requete, $requete_data);
    $id = $DB->Insert_ID();
    creer_pseudo(trim($_POST["nom"]), trim($_POST["prenom"]), $id);
}

function mise_a_jour_donnees() {
    global $prefix_tables, $DB;
    $DB->Execute("UPDATE ".$prefix_tables."etudiant
                  SET ine=?, nom=?, prenom=?, adresse=?, code_postal=?, ville=?, email=?, tel=?
                  WHERE id_etudiant=?",
                 array($_POST["numero_ine"], strtoupper($_POST["nom"]), ucfirst($_POST["prenom"]),
                       $_POST["adresse"], $_POST["code_postal"], $_POST["ville"], $_POST["email"],
                       $_POST["telephone"], $_POST["id"]));
}

function verifie_formulaire() {
    $msg_erreur = "";
    if (!strcmp($_POST["numero_ine"], "")) $msg_erreur .= "Le <i>Num&eacute;ro INE</i> doit &ecirc;tre renseign&eacute;.<br>";
    if (!strcmp($_POST["nom"], "")) $msg_erreur .= "Le champ <i>Nom</i> doit &ecirc;tre renseign&eacute;.<br>";
    if (!strcmp($_POST["prenom"], "")) $msg_erreur .= "Le champ <i>Pr&eacute;nom</i> doit &ecirc;tre renseign&eacute;.<br>";
    if (!strcmp($_POST["adresse"], "")) $msg_erreur .= "Le champ <i>Adresse</i> doit &ecirc;tre renseign&eacute;.<br>";
    if (!strcmp($_POST["code_postal"], "")) $msg_erreur .= "Le champ <i>Code postal</i> doit &ecirc;tre renseign&eacute;.<br>";
    if (!strcmp($_POST["ville"], "")) $msg_erreur .= "Le champ <i>Ville</i> doit &ecirc;tre renseign&eacute;.<br>";
    if (!strcmp($_POST["email"], "")) $msg_erreur .= "Le champ <i>Email</i> doit &ecirc;tre renseign&eacute;.<br>";
    if (!strcmp($_POST["telephone"], "")) $msg_erreur .= "Le champ <i>T&eacute;l&eacute;phone</i> doit &ecirc;tre renseign&eacute;.<br>";
    
    if (strcmp($msg_erreur, "")) {
        erreur($msg_erreur);
        affiche_formulaire(-$_POST["id"]);
    } elseif ($_POST["id"]) {
        mise_a_jour_donnees();
        echo "<p align='center'>Donn&eacute;es mises &agrave; jour</p>\n";
    } else {
        ajoute_donnees();
        echo "<p align='center'>&Eacute;tudiant ajout&eacute;</p>\n";
    }
}

// Affiche la liste des étudiants
function affiche_liste() {
    global $prefix_tables, $DB;
    
    echo "<center>\n";
    $res = $DB->Execute("SELECT id_etudiant, nom, prenom
                         FROM ".$prefix_tables."etudiant
                         ORDER BY nom ASC, prenom ASC");
    if ($res->RecordCount()) {
        echo "<p>";
        while ($row = $res->FetchRow()) {
            echo "<a href=\"index.php?page=etudiant&mode=voir&id=", $row[0], "\">", $row[1], " ", $row[2], "</a><br>";
        }
        echo "</p>\n";
    } else {
        echo "<i>Aucun &eacute;tudiant enregistr&eacute;</i>";
    }
    echo "</center>\n";
    $res->Close();
}

function verifie_existence_etudiant($id) {
    global $prefix_tables, $DB;
    return $DB->GetOne("SELECT COUNT(*) FROM ".$prefix_tables."etudiant
                        WHERE id_etudiant=".$id);
}

// Gestion des inscriptions dans les diplômes d'un étudiant
function inscrire_etudiant_diplome($id) {
    $fiche_etudiant = charge_donnees_etudiant($id);
    echo "<p align='center'>Liste des dipl&ocirc;mes dans lequel <b>",$fiche_etudiant["nom"]," ",$fiche_etudiant["prenom"],"</b> est inscrit</b></p>\n";
    charge_diplomes_etudiant_modification($id);
}

function ajoute_diplome_etudiant($id) {
    global $prefix_tables, $DB;
    
    // On regarde si l'étudiant est déjà inscrit dans un diplôme pour définir celui-là comme son inscription principale ou non
    $nb = $DB->GetOne("SELECT COUNT(*) FROM ".$prefix_tables."inscrit_diplome
                       WHERE id_etudiant=".$id);
    $diplome_principal = ($nb) ? 0 : 1;

    // Ajout de l'inscription
    $DB->Execute("INSERT INTO ".$prefix_tables."inscrit_diplome
                  (id_diplome, id_etudiant, principal) VALUES (?, ?, ?)",
                 array($_POST["choix"], $id, $diplome_principal));
    unset($_POST);
    inscrire_etudiant_diplome($id);
    liste_diplomes($id);
}

// Affiche dans un select les diplômes où l'étudiant peut s'inscrire (il n'y est pas inscrit)
function liste_diplomes($id_etudiant) {
    global $prefix_tables, $DB;

    $res = $DB->Execute("SELECT id_diplome
                         FROM ".$prefix_tables."inscrit_diplome
                         WHERE id_etudiant=".$id_etudiant);
    $tmp = "(0";
    while ($row = $res->FetchRow()) {
        $tmp .= ",".$row[0];
    }
    $tmp .= ")";
    $res->Close();
    $res = $DB->Execute("SELECT id_diplome, sigle_complet
                         FROM ".$prefix_tables."diplome
                         WHERE id_diplome NOT IN ".$tmp."
                         ORDER BY sigle_complet ASC");

    if ($res->RecordCount()) {
        echo "<table align='center'><tr><td>Inscrire cet &eacute;tudiant en <form method=\"post\" action=\"index.php?page=etudiant&mode=inscrire&id=",$id_etudiant,"\"><select name=\"choix\">\n";
        while ($row = $res->FetchRow()) {
            echo "<option value=\"", $row[0],"\">", $row[1], "</option>\n";
        }
        echo "</select> <input type=\"submit\" value=\"OK\"></form></td></tr></table>\n";
    } else {
        echo "<p align='center'><i>Aucun dipl&ocirc;me disponible.</i></p>";
    }
    $res->Close();
}

// Changement d'inscription principale pour un étudiant donné
function changer_diplome_principal($id_etudiant, $id_diplome) {
    global $prefix_tables, $DB;
    $DB->Execute("UPDATE ".$prefix_tables."inscrit_diplome
                  SET principal=0
                  WHERE id_etudiant=".$id_etudiant);
    $DB->Execute("UPDATE ".$prefix_tables."inscrit_diplome
                  SET principal=1
                  WHERE id_etudiant=".$id_etudiant." AND id_diplome=".$id_diplome);
}

// Désinscription d'un étudiant dans un diplôme
// Le diplôme principal ne peut être supprimer que s'il n'y a pas d'inscription secondaire
function desinscrire_etudiant_diplome($id_etudiant, $id_diplome) {
    global $prefix_tables, $DB;
    
    // nombre d'inscriptions de l'étudiant
    $nb = $DB->GetOne("SELECT COUNT(*) FROM ".$prefix_tables."inscrit_diplome
                        WHERE id_etudiant=".$id_etudiant);
    if ($nb > 1) {
        $DB->Execute("DELETE FROM ".$prefix_tables."inscrit_diplome
                      WHERE id_etudiant=".$id_etudiant." AND id_diplome=".$id_diplome." AND principal=0");
    } elseif ($nb = 1) {
        $DB->Execute("DELETE FROM ".$prefix_tables."inscrit_diplome
                      WHERE id_etudiant=".$id_etudiant." AND id_diplome=".$id_diplome);
    }
    inscrire_etudiant_diplome($id_etudiant);
    liste_diplomes($id_etudiant);
}

if (isset($_GET["mode"])) {
    switch($_GET["mode"]) {
        case "nouveau" : // Affichage du formulaire vierge pour l'ajout d'un nouvel étudiant
            echo "<p align='center'>Ajouter un nouvel &eacute;tudiant</p>\n";
            affiche_formulaire(0);
            echo "<p align='center'><a href=\"index.php?page=etudiant\">Retour &agrave; la page de gestion des &eacute;tudiants</a></p>\n";
            break;
        case "liste" : // Affichage de la liste des étudiants
            affiche_liste();
            echo "<p align='center'><a href=\"index.php?page=etudiant\">Retour &agrave; la page de gestion des &eacute;tudiants</a></p>\n";
            break;
        case "verifie" : // Validation du formulaire, vérification des données et traitement
            verifie_formulaire();
            break;
        case "voir" : // Visualisation de la fiche d'un étudiant
            if (isset($_GET["id"]) && !empty($_GET["id"])) {
                $id = (int)$_GET["id"];
                if (verifie_existence_etudiant($id)) {
                    echo "<p align='center'>Visualisation / modification d'un &eacute;tudiant</p>\n";
                    affiche_formulaire($id);
                    echo "<p align='center'>&Eacute;tudiant inscrit en :</p>\n";
                    charge_diplomes_etudiant_formulaire($id);
                    echo "<p align='center'><a href=\"index.php?page=etudiant&mode=inscrire&id=",$id,"\">Inscrire/d&eacute;sinscrire cet étudiant dans un dipl&ocirc;me</a></p>\n";
                    echo "<p align='center'><a href=\"index.php?page=etudiant&mode=liste\">Retour &agrave; la liste des &eacute;tudiants</a></p>\n";
                    echo "<p align='center'><a href=\"index.php?page=etudiant\">Retour &agrave; la page de gestion des &eacute;tudiants</a></p>\n";
                } else {
                    erreur("Cet &eacute;tudiant n'existe pas.");
                    affiche_menu();
                }
            } else {
                erreur("Param&egrave;tres incorrects.");
                affiche_menu();
            }
            break;
        case "inscrire" : // Ajout de l'étudiant dans un diplôme existant
            if (isset($_GET["id"]) && !empty($_GET["id"])) {
                $id = (int)$_GET["id"];
                if (verifie_existence_etudiant($id)) {
                    if (!isset($_POST["choix"])) {
                        inscrire_etudiant_diplome($id);
                        liste_diplomes($id);
                    } else {
                        ajoute_diplome_etudiant($id);
                    }
                    echo "<p align='center'><a href=\"index.php?page=etudiant&mode=voir&id=",$id,"\">Retour &agrave; la fiche de cet &eacute;tudiant</a></p>\n";
                    echo "<p align='center'><a href=\"index.php?page=etudiant&mode=liste\">Retour &agrave; la liste des &eacute;tudiants</a></p>\n";
                    echo "<p align='center'><a href=\"index.php?page=etudiant\">Retour &agrave; la page de gestion des &eacute;tudiants</a></p>\n";
                } else {
                    erreur("Cet &eacute;tudiant n'existe pas.");
                    affiche_menu();
                }
            } else {
                erreur("Param&egrave;tres incorrects.");
                affiche_menu();
            }
            break;
        case "desinscrire" : // Désinscription de l'étudiant dans un diplôme existant
            if (isset($_GET["id"]) && !empty($_GET["id"])) {
                $id = (int)$_GET["id"];
                if (verifie_existence_etudiant($id)) {
                    if (isset($_GET["d"])) {
                        desinscrire_etudiant_diplome($id, (int)$_GET["d"]);
                    }
                    echo "<p align='center'><a href=\"index.php?page=etudiant&mode=voir&id=",$id,"\">Retour &agrave; la fiche de cet &eacute;tudiant</a></p>\n";
                    echo "<p align='center'><a href=\"index.php?page=etudiant&mode=liste\">Retour &agrave; la liste des &eacute;tudiants</a></p>\n";
                    echo "<p align='center'><a href=\"index.php?page=etudiant\">Retour &agrave; la page de gestion des &eacute;tudiants</a></p>\n";
                } else {
                    erreur("Cet &eacute;tudiant n'existe pas.");
                    affiche_menu();
                }
            } else {
                erreur("Param&egrave;tres incorrects.");
                affiche_menu();
            }
            break;
        case "changer" : // L'étudiant change d'inscription principale
            if (isset($_GET["id"]) && !empty($_GET["id"])) {
                $id = (int)$_GET["id"];
                if (verifie_existence_etudiant($id)) {
                    changer_diplome_principal($id, $_POST["inscription_principale"]);
                    inscrire_etudiant_diplome($id);
                    echo "<p align='center'><a href=\"index.php?page=etudiant&mode=voir&id=",$id,"\">Retour &agrave; la fiche de cet &eacute;tudiant</a></p>\n";
                    echo "<p align='center'><a href=\"index.php?page=etudiant&mode=liste\">Retour &agrave; la liste des &eacute;tudiants</a></p>\n";
                    echo "<p align='center'><a href=\"index.php?page=etudiant\">Retour &agrave; la page de gestion des &eacute;tudiants</a></p>\n";
                } else {
                    erreur("Cet &eacute;tudiant n'existe pas.");
                    affiche_menu();
                }
            } else {
                erreur("Param&egrave;tres incorrects.");
                affiche_menu();
            }
            break;
        default :
            erreur("Param&egrave;tres incorrects.");
            affiche_menu();
    }
} else {
    affiche_menu();
}

?>