<?php

 // Création d'une  connexion à une base de données
$DB = NewADOConnection($db_type);
$DB->debug = false; // Utilisation du débogage
$DB->Connect($db_host, $db_user, $db_password, $db_name); // Connexion
$DB->SetFetchMode(ADODB_FETCH_NUM);

?>