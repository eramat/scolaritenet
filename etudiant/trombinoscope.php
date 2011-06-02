<?php
// Pour bloquer l'accès direct à cette page
if (!defined("acces_ok"))
	exit;
    
// Affichage de l'entête
entete("Trombinoscope");

global $prefix_tables;

if (isset($_POST["id_diplome"]))
	$id_diplome = $_POST["id_diplome"];
if (isset($_POST["id_groupe"]))
	$id_groupe = $_POST["id_groupe"];
if (isset($_POST["id_etudiant"]))
	$id_etudiant = $_POST["id_etudiant"];
if (isset($_POST["nom"]))
	$nom = $_POST["nom"];
if (isset($_POST["prenom"]))
	$prenom = $_POST["prenom"];
if (isset($_POST["userfile"]))
	$userfile = $_POST["userfile"];
if (isset($_POST["bouton"]))
	$bouton = $_POST["bouton"];
	
/****************************************************************
      si c'est un gestionnaire qui est connecté,
      il faut vérifier s'il peut agir sur la promotion 
      ie : s'il peut consulter les fiches ou ajouter des photos.
*****************************************************************/

if (!isset($id_diplome) OR $id_diplome <= 0)
{
	$id_diplome = -1;
	if (est_enseignant($_SESSION["usertype"])) {
		$request = "SELECT DISTINCT(d.id_diplome), sigle_complet
					FROM ".$prefix_tables."diplome d, ".$prefix_tables."module_assure a, ".$prefix_tables."module_suivi_diplome s
					WHERE a.id_enseignant = ?
					AND a.id_module = s.id_module
					AND s.id_diplome = d.id_diplome";
		$param_array = array($_SESSION["id"]);	
	} elseif (est_directeur_etude($_SESSION["usertype"])) {
		$request = "SELECT id_diplome, sigle_complet
					FROM ".$prefix_tables."diplome
					WHERE id_directeur_etudes = ?";
		$param_array = array($_SESSION["id"]);	
	} elseif (est_president_du_jury($_SESSION["usertype"])) {
		$request = "SELECT id_diplome, sigle_complet
                    FROM ".$prefix_tables."diplome
                    WHERE id_president_jury = ?";
		$param_array = array($_SESSION["id"]);		
	} elseif (est_secretaire($_SESSION["usertype"])) {
		$request = "SELECT d.id_diplome, sigle_complet
                    FROM ".$prefix_tables."diplome d, ".$prefix_tables."secretaire_occupe_diplome o
                    WHERE o.id_secretaire = ? 
                    AND o.id_diplome = d.id_diplome";
		$param_array = array($_SESSION["id"]);   
	}
	$result = $DB->Execute($request, $param_array);
	if ($result->RecordCount() > 1) // Il y a plusieurs diplômes -> il faut choisir !
    {
		echo "<center>";
		echo "<form name='form1' method='post' action='index.php?page=trombinoscope'>\n";
		while ($a_record = $result->FetchRow()) {
			echo "<INPUT TYPE=RADIO NAME=\"id_diplome\" value=\"".$a_record[0]."\" OnClick=\"submit();\">";
			print($a_record[1]."<br>");
		}
		echo "</form>";
		echo "</center>";
    }
    else
    {
		$a_record = $result->FetchRow();
		$id_diplome = $a_record[0];
		$titre = $a_record[1];
    }
}
else
{
	$request = "SELECT sigle_complet
                FROM ".$prefix_tables."diplome
                WHERE id_diplome = ?";
    $titre = $DB->GetOne($request, array($id_diplome)); 
}

if ($id_diplome != -1)
{
// Vérifier s'il existe des groupes et proposer de choisir si ce n'est pas déjà fait.
    $request = "SELECT id, nom
                FROM ".$prefix_tables."groupe
                WHERE id_diplome = ?
                ORDER BY nom";				
	$result = $DB->Execute($request, array($id_diplome));
	if ($result->RecordCount() > 0)
    {
		echo "<form name='group' method='post' action='index.php?page=trombinoscope'>\n";
		echo "<center>Groupe :";
		echo "<input type='hidden' name='id_diplome' value='$id_diplome'>\n";
		while ($a_record = $result->FetchRow())
		{
			echo "<INPUT TYPE=RADIO NAME=\"id_groupe\"";
			if (isset($id_groupe) AND $id_groupe == $a_record[0])
				echo " CHECKED ";
			echo "VALUE=\"".$a_record[0]."\" OnClick=\"submit();\">";
			echo $a_record[1];
		}
		echo "<INPUT TYPE=RADIO NAME=\"id_groupe\"";
		if (!isset($id_groupe) OR $id_groupe == -1)
			echo " CHECKED ";
		echo "VALUE=\"-1\" OnClick=\"submit();\">Non";
		echo "</center>";
		echo "</form>";
    }
    if (isset($id_groupe) AND $id_groupe != -1)
    {
		$request = "SELECT nom
				  FROM ".$prefix_tables."groupe
				  WHERE id = ?";
		$titre = $titre." - Groupe ".$DB->GetOne($request, array($id_groupe));
    }
  
// Construction du Trombinoscope
    //sélection des étudiants concernés dans la table etudiant
    if(!isset($id_groupe) || $id_groupe==-1) { // C'est un diplôme
		$query = "SELECT e.id_etudiant, e.nom, e.prenom, e.photo
                FROM ".$prefix_tables."etudiant e, ".$prefix_tables."inscrit_diplome i
                WHERE i.id_diplome = ? 
                AND e.id_etudiant = i.id_etudiant 
                ORDER by e.nom";
		$param_array = array($id_diplome);
    } else {
		$query = "SELECT e.id_etudiant, e.nom, e.prenom, e.photo
                FROM ".$prefix_tables."etudiant e, ".$prefix_tables."etudiant_appartient_groupe g
                WHERE g.id_groupe = ?
                AND e.id_etudiant = g.id_etudiant 
                ORDER by e.nom";
		$param_array = array($id_groupe);
	}
 
// Construction de l'Entete
	$result = $DB->Execute($query, $param_array);
	$nbEtudiant = $result->RecordCount();
    $nbCases = 4; // nombre de cases par ligne du trombinoscope
    $nb = $nbEtudiant % $nbCases; // reste de la division par $nbCases 
 
    if($nbEtudiant == 0)
		echo "<p align='center'>Aucun étudiant en ",$titre,"</p>\n";
    else
    { 
		echo "<script language='javascript'>\n";
		echo "function envoyer(id,nom,prenom){\n";
		echo "  document.form1.id_etudiant.value = id;\n";
		echo "  document.form1.nom.value = nom;\n";
		echo "  document.form1.prenom.value = prenom;\n";
		echo "  document.form1.submit();\n}\n";
		echo "</script>\n";

		echo "<form name='form1' method='post' action='index.php?page=trombinoscope'>\n";
		echo "<input type='hidden' name='id_diplome' value='",$id_diplome,"'>\n";
      
		if(isset($id_groupe))
			echo "<input type='hidden' name='id_groupe' value='",$id_groupe,"'>\n"; 

		if(!isset($id_etudiant))
		{
			echo "<input type='hidden' name='id_etudiant'>\n";
			echo "<input type='hidden' name='nom'>\n";
			echo "<input type='hidden' name='prenom'>\n";
		} else{
			echo "<input type='hidden' name='id_etudiant' value='",$id_etudiant,"'>\n";
			echo "<input type='hidden' name='nom' value='",$nom,"'>\n";
			echo "<input type='hidden' name='prenom' value='",$prenom,"'>\n";
		}
		echo "</form>";
       
		if(isset($bouton) && $bouton=='Valider')
		{
			if ($_FILES['userfile']['error']) { // gestion des erreurs
				switch ($_FILES['userfile']['error']){
					case 1: // UPLOAD_ERR_INI_SIZE
						$msg = "Le fichier dépasse la limite autorise par le serveur (fichier php.ini) !";
						break;
					case 2: // UPLOAD_ERR_FORM_SIZE
						$msg = "Le fichier dépasse la limite autorisée dans le formulaire HTML !";
						break;
                   case 3: // UPLOAD_ERR_PARTIAL
						$msg = "L'envoi du fichier a été interrompu pendant le transfert !";
						break;
                   case 4: // UPLOAD_ERR_NO_FILE
						$msg = "Aucun fichier séléctionné !";
						break;
				}
				echo "<script language=\"javascript\">\n";
				echo "alert('",$msg,"')";
				echo "</script>\n";
			} elseif (is_uploaded_file($_FILES['userfile']['tmp_name'])) {
				$userfile_type = $_FILES['userfile']['type'];
				if($userfile_type != 'image/png') 
				{ 
					echo "<script language='javascript'>\n";
					echo "alert(\"L'image téléchargée doit être au format PNG SVP.\");\n";
					echo "</script>\n";
				} else {
					$move_file_successful = move_uploaded_file($_FILES['userfile']['tmp_name'], "etudiant/photos/".$id_etudiant.".png");
					if ($move_file_successful) {
						$DB->Execute("UPDATE ".$prefix_tables."etudiant 
									SET photo = 1 WHERE id_etudiant = ?", array($id_etudiant));
			  
						// Prise en compte du changement de photo  
						if(!isset($id_groupe) OR $id_groupe == -1) {
							$query = "SELECT e.id_etudiant, e.nom, e.prenom, e.photo
									  FROM ".$prefix_tables."etudiant e, ".$prefix_tables."inscrit_diplome i
									  WHERE i.id_diplome = ? 
									  AND e.id_etudiant = i.id_etudiant 
									  ORDER by e.nom";
							$param_array = array($id_diplome);
						} else {
							$query = "SELECT e.id_etudiant, e.nom, e.prenom, e.photo
									FROM etudiant e,appartient a,inscrit i 
									WHERE e.id_etudiant=a.id_etudiant AND a.id_groupe=? 
										AND e.id_etudiant=i.id_etudiant AND i.id_promotion=? order by e.nom";
							$param_array = array($id_groupe, $id_promotion);
						}
						$result = $DB->Execute($query, $param_array);
					} else {
						echo "<script language='javascript'>\n";
						echo "alert(\"Une erreur est survenue suite à l'envoie du fichier\");\n";
						echo "</script>\n";
					}
				}
			} else {
				echo "<script language='javascript'>\n";
				echo "alert(\"Le fichier ".$userfile." n'est pas valide. Veuillez réessayer SVP \");\n";
				echo "</script>\n";
			}
		}
		echo "<table align='center' border='5' bordercolor='black' bgcolor='#c6dffb' width=600 cellpadding='5' cellspacing='10'>\n";
		echo "<col span=",$nbCases," align='center'>\n";
		echo "<tr>\n";
		echo "<th colspan=",$nbCases,">",$titre,"</th>\n";
		echo "</tr>\n";
		$j = 1;
		echo "<tr>\n";
		while ($row = $result->FetchRow())
		{
			$photo = array("photo" => $row[3], "id_etudiant" => $row[0],"nom" => $row[1], "prenom" => $row[2]);
			if($photo["photo"] == 0) $img = "";
			else $img = $photo["id_etudiant"];    
			if($j > $nbCases)
			{
				$j = 1;
				echo "</tr>\n<tr>\n";
			}
        
			if($img == "")
			{
				echo "<td align=center valign=middle height=180 width=150>";
				echo "<input type='image' src='etudiant/photos/",$img,".png' alt='T&eacute;l&eacute;charger la photo' width=50 onClick=\"javascript:envoyer('".$photo["id_etudiant"]."','".$photo["nom"]."','".$photo["prenom"]."');\"><br><br><br><br><a href='index.php?page=etudiant_visu&id=".$photo["id_etudiant"]."'>".$photo["nom"]."<br>".$photo["prenom"]."</a></td>\n";
			} else {
				echo "<td align=center valign=middle height=180 width=150>";
				echo "<a href=\"javascript:envoyer('".$photo["id_etudiant"]."','".$photo["nom"]."','".$photo["prenom"]."');\"><img src='etudiant/photos/",$img,".png' alt='Modifier photo' width=100></a><br><br><a href='index.php?page=etudiant_visu&id=".$photo["id_etudiant"]."'>".$photo["nom"]."<br>".$photo["prenom"]."</a></td>\n";
			}
			$j++;
		}
		if($nb != 0)
		{
			for($i = 0;$i < $nbCases - $nb;$i++)
				echo "<td  height=180 width=150>&nbsp;</td>\n";
			echo "</tr>\n</table>\n";
		} else
			echo "</tr>\n</table>\n";
		echo "<br>\n";
      		
		if((isset($id_etudiant)))
		{
			if(!isset($bouton)) {
				echo "<table border='0' cellpadding='10' bgcolor='#94AAD6' width=400 align='center'>\n";
				echo "<col span='1' align='center'>\n";
				echo "<tr><td>\n";
				echo "Donner le chemin de la photo de ",$nom," ",$prenom,"\n";
				echo "<FORM METHOD=\"POST\" ENCTYPE=\"multipart/form-data\" ACTION=\"index.php?page=trombinoscope\">\n";
				echo "<input type='hidden' name='id_diplome' value='",$id_diplome,"'>\n";
				echo "<input type='hidden' name='id_etudiant' value='",$id_etudiant,"'>\n";
				echo "<input type='hidden' name='nom' value='",$nom,"'>\n";
				echo "<input type='hidden' name='prenom' value='",$prenom,"'>\n";
				if(isset($id_groupe))
					echo "<input type='hidden' name='id_groupe' value='",$id_groupe,"'>\n"; 
				echo "<INPUT TYPE='hidden' name='MAX_FILE_SIZE' value='32768'>\n";
				echo "<input type='file' name='userfile'>\n";
				echo "</td></tr>\n";
				echo "<tr><td align=center>\n";
				echo "<input type='submit' name='bouton' value='Valider'>&nbsp;\n";
				echo "<input type='submit' name='bouton' value='Annuler'>\n";
				echo "</td></tr>\n";
				echo "</table><br>\n";
				echo "</form>\n";
			}
		}
    }
  }
 ?>