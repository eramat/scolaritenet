<?php

//-----------------------------------------------------------------------------
// Entete de la page HTML
//
function affiche_header()
{
  echo '<html>
	<head>
	<title>Installation de ScolariteNet</title>
	<style type="text/css">
		#container { border: 2px solid gray; width: 400px; }
		#titre { margin-top: 20px; margin-bottom: 30px; font-size: 2em; font-weight: bold; }
		#info { width: 400px; margin-bottom: 5px; background-color: #dbeaef; line-height: 40px; border: 2px solid #8dabb6 }
		#erreur { margin: auto; width: 400px; border: 2px solid orange; background-color: #ffe59e; margin-bottom: 5px; font-weight: bold }
	</style>
	</head>
	<body background="../images/fond.gif" style="font-family: sans-serif; font-size: 0.8em;">
		<center>
		<div id="titre">Installation</div>
		<div id="info">Bienvenue dans le module d\'installation de ScolariteNet v1.0</div>';
}

//-----------------------------------------------------------------------------
// Formulaire d'installation
//
function affiche_formulaire()
{
  $types = array();
  $dir = opendir('./types/postgres/');
  while ($f = readdir($dir)) {
    if (preg_match('!.sql$!', $f)) {
      $f = str_replace('.sql', '', $f);
      $types.array_push($types, $f);
    }
  }
  closedir($dir);

  echo '<form method="post" action="'.$PHP_SELF.'" name="install_form">
	<div id="container">
	<table width="100%" cellpadding="5" bgcolor="#EEEEEE" style="font-family: sans-serif; font-size: 0.9em;">
	<tr>
		<td colspan="2" bgcolor="#CCCCCC"><b>Base de donn&eacute;es :</b></td>
	</tr>
	<tr>
		<td>Type de base</td>
		<td><select name="dbtype">
	           <option value="mysql">mysql</option>
		   <option value="postgres">postgres</option>
		</select></td>
	</tr>
	<tr>
		<td>Hôte</td>
		<td><input type="text" value="localhost" name="dbhost"></td>
	</tr>
	<tr>
		<td>Nom de la base</td>
		<td><input type="text" value="scolaritenet" name="dbname"></td>
	</tr>
	<tr>
		<td>Pr&eacute;fixe des tables</td>
		<td><input type="text" value="edt_" name="dbpref"></td>
	</tr>
	<tr>
		<td>Nom d\'utilisateur</td>
		<td><input type="text" value="root" name="dbuser"></td>
	</tr>
	<tr>
		<td>Mot de passe</td>
		<td><input type="text" value="" name="dbpass"></td>
	</tr>
	<tr>
		<td colspan="2" bgcolor="#CCCCCC"><b>Site :</b></td>
	</tr>
	<tr>
		<td>Type d\'installation</td>
		<td><select name="install_type">';

  foreach ($types as $t) {
    echo '<option value="'.$t.'">'.$t.'</option>';
  }
  echo '            </select></td>
	  </tr>
	  <tr>
		<td>Racine du site</td>
		<td><input type="text" value="http://127.0.0.1/" name="racine_site"></td>
	</tr>
	<tr>
		<td>Logo du site</td>
		<td><input type="text" value="images/ulco.gif" name="logo_site"></td>
	</tr>
	<tr>
		<td colspan="2"><center>
		<input type="hidden" value="<?php echo $version; ?>" name="version">
		<input type="submit" value="Valider">
		</center></td>
	</tr>
        </table>
	</div>
	</form>';
}

//-----------------------------------------------------------------------------
// Ajout d'une erreur relative au formulaire
function ajout_erreur($i)
{
  $_SESSION['erreur'].array_push($_SESSION['erreur'], $i);
}

//-----------------------------------------------------------------------------
// Erreur critique
function error($i)
{
  die ('<br><b>'.$i.'</b><br><a href="javascript:window.history.go(-1)"><br>retour</a>');
}

//-----------------------------------------------------------------------------
// Fonction qui affiche les erreurs du formulaire
function affiche_erreur()
{
  echo '<div id="erreur">Erreurs :<br>';
  foreach ($_SESSION['erreur'] as $e) {
    echo $e.'<br>';
  }
  echo '</div>';
  unset ($_SESSION['erreur']);
}

//-----------------------------------------------------------------------------
// Fonction qui vérifie le formulaire
function verif_formulaire()
{
  if (empty($_POST['dbtype'])) {
    ajout_erreur('Veuillez sp&eacute;cifier le type de la base.');
  }
  if (empty($_POST['dbhost'])) {
    ajout_erreur('Veuillez indiquer l\'hôte de la base.');
  }
  if (empty($_POST['dbname'])) {
    ajout_erreur('Veuillez indiquer le nom de la base.');
  }
  if (empty($_POST['dbuser'])) {
    ajout_erreur('Veuillez indiquer un nom d\'utilisateur pour se connecter à la base.');
  }
  if (empty($_POST['racine_site'])) {
    ajout_erreur('Veuillez indiquer le r&eacute;pertoire racine de scolaritenet.');
  }
  if (!empty($_SESSION['erreur'])) {
    return false;
  }

  $GLOBALS['$db_type'] = $_POST['dbtype'];
  $GLOBALS['$db_host'] = $_POST['dbhost'];
  $GLOBALS['$db_name'] = $_POST['dbname'];
  $GLOBALS['$db_pref'] = $_POST['dbpref'];
  $GLOBALS['$db_user'] = $_POST['dbuser'];
  $GLOBALS['$db_pass'] = $_POST['dbpass'];
  $GLOBALS['$version'] = $_POST['version'];
  $GLOBALS['$install_type'] = $_POST['install_type'];
  $GLOBALS['$racine_site'] = $_POST['racine_site'];
  $GLOBALS['$logo_site'] = $_POST['logo_site'];
  return true;
}

//-----------------------------------------------------------------------------
// Remarque : Fonction provenant de PHPBB 2
// split_sql_file will split an uploaded sql file into single sql statements.
// Note: expects trim() to have already been run on $sql.
function split_sql_file($sql, $delimiter)
{
  $tokens = explode($delimiter, $sql);
  $sql = "";
  $output = array();
  $matches = array();
  $token_count = count($tokens);

  for ($i = 0; $i < $token_count; $i++) {
    if (($i != ($token_count - 1)) || (strlen($tokens[$i] > 0))) {
      $total_quotes = preg_match_all("/'/", $tokens[$i], $matches);
      $escaped_quotes = preg_match_all("/(?<!\\\\)(\\\\\\\\)*\\\\'/",
				       $tokens[$i], $matches);
      $unescaped_quotes = $total_quotes - $escaped_quotes;
      if (($unescaped_quotes % 2) == 0) {
	$output[] = $tokens[$i];
	$tokens[$i] = "";
      } else {
	$temp = $tokens[$i] . $delimiter;
	$tokens[$i] = "";
	$complete_stmt = false;

	for ($j = $i + 1; (!$complete_stmt && ($j < $token_count)); $j++) {
	  $total_quotes = preg_match_all("/'/", $tokens[$j], $matches);
	  $escaped_quotes = preg_match_all("/(?<!\\\\)(\\\\\\\\)*\\\\'/",
					   $tokens[$j], $matches);
	  $unescaped_quotes = $total_quotes - $escaped_quotes;

	  if (($unescaped_quotes % 2) == 1) {
	    $output[] = $temp . $tokens[$j];
	    $tokens[$j] = "";
	    $temp = "";
	    $complete_stmt = true;
	    $i = $j;
	  } else {
	    $temp .= $tokens[$j] . $delimiter;
	    $tokens[$j] = "";
	  }
	}
      }
    }
  }
  return $output;
}

//-----------------------------------------------------------------------------
// Installation : création des tables et du fichier de configuration
function installer()
{
  require "/usr/share/php/adodb/adodb.inc.php";

  // Connection à la base de donnée
  $DB = NewADOConnection($GLOBALS['$db_type']);
  if (!@$DB->Connect($GLOBALS['$db_host'],
		     $GLOBALS['$db_user'],
		     $GLOBALS['$db_pass'],
		     $GLOBALS['$db_name'])) {
    error('Impossible de se connecter à la base, veuillez v&eacute;rifier'.
	  ' que les param&egrave;tres sont corrects.');
  }

  // Installation des tables
  if (!$req = @file_get_contents('./types/'.$GLOBALS['$db_type'].'/'.
				 $GLOBALS['$install_type'].'.sql')) {
    error('Impossible de lire le fichier sql');
  }

  // Remplacement des préfices des tables
  $req = str_replace('PREFIX', $GLOBALS['$db_pref'], $req);
  // Chaque requete séparée par ; est mise dans le tableau req_list
  $req_list = split_sql_file($req, ';');

  foreach ($req_list as $requete) {
    $requete = trim($requete, " \n");
    if ($requete != "" && $requete[0] != "-" && $requete[1] != "-") {
      if (!@$DB->Execute($requete)) {
	error('Une erreur est survenue lors de l\'execution de la requete : ' +
	      '<pre style="border: 1px solid black; width: 60%;">'.$requete.
	      '</pre>');
      }
    }
  }

  // Création du fichier de configuration
  $conf = '<?php
// ScolariteNet installé?
$is_installed = true;

// Base de données
$db_type = "'.$GLOBALS['$db_type'].'"; // Type de base de données

$src_img_delete = "images/btn_delete.img";
$src_img_edit = "images/btn_edit.img";
$src_img_arrow_up = "images/arrow_up.img";
$src_img_arrow_left = "images/arrow_left.img";
$src_img_arrow_right = "images/arrow_right.img";
$src_img_arrow_down = "images/arrow_down.img";

$db_host = "'.$GLOBALS['$db_host'].'"; // Hôte
$db_name = "'.$GLOBALS['$db_name'].'"; // Nom de la base de données
$db_user = "'.$GLOBALS['$db_user'].'"; // Utilisateur
$db_password = "'.$GLOBALS['$db_pass'].'"; // Mot de passe
$prefix_tables = "'.$GLOBALS['$db_pref'].'";

$racine_site = "'.$GLOBALS['$racine_site'].'";
$logo_site = "'.$GLOBALS['$logo_site'].'";

$version = "1.0";

$head_color = "#6F8DB9";
$line_color = "#E9E9D1";
?>';

  $file = fopen('../includes/config.php', 'w');
  if (!$file) {
    error('Impossible de cr&eacute;er le fichier ../includes/config.php');
  }
  if (!@fwrite($file, $conf)) {
    error('Impossible d\'&eacute;crire dans le fichier ../includes/config.php');
  }
  fclose($file);

  echo '<div id="info"><b>Installation termin&eacute;e avec succ&eacute;s.</b><br>
		  Login administrateur : <i>admin</i><br>
		  (Pas de mot de passe requis pour la première connection)<br>
		  <a href="../index.php">Se connecter</a></div>';

  // Création d'un fichier htaccess pour protéger le répertoire d'installation
  $file = @fopen('.htaccess', 'w');
  @fwrite($file, "order deny,allow\ndeny from all\nallow from 127.0.0.1");
  fclose($file);
}

?>