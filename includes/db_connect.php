<?php

 // Cr�ation d'une  connexion � une base de donn�es
$DB = NewADOConnection($db_type);
$DB->debug = false; // Utilisation du d�bogage
$DB->Connect($db_host, $db_user, $db_password, $db_name); // Connexion
$DB->SetFetchMode(ADODB_FETCH_NUM);

?>