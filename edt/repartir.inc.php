<?php

function recherche_groupe($id_diplome,$id_option,$id_groupe) {
    global $prefix_tables, $DB;

    $type_groupe = $DB->GetOne("SELECT id_type 
                                FROM ".$prefix_tables."groupe_type
                                WHERE id_groupe = ?",
                                array($id_groupe));
  
    if ($id_diplome > 0) {// c'est un diplome
        $request = "SELECT g.id
                    FROM ".$prefix_tables."groupe g, ".$prefix_tables."groupe_type gt
                    WHERE g.id_diplome = ? AND gt.id_groupe = g.id AND gt.id_type = ?";
        $request_data = array($id_diplome, $type_groupe);
    } else { // c'est une option
        $request = "SELECT g.id 
                    FROM ".$prefix_tables."groupe g, ".$prefix_tables."groupe_type gt
                    WHERE g.id_option = ? AND gt.id_groupe = g.id AND gt.id_type = ?";
        $request_data = array($id_option, $type_groupe);
    }
    return $DB->Execute($request, $request_data);
}

function update_duree($id_module, $semaine, $id_type_seance, $id_diplome, $id_option, $id_groupe, $delta, $idem) {
    global $prefix_tables, $DB;

    $reparti = $DB->GetRow("SELECT nombre_heures, id
                            FROM ".$prefix_tables."module_reparti
                            WHERE id_module=? AND semaine=? AND id_type_seance=? AND id_groupe=?",
                            array($id_module, $semaine, $id_type_seance, $id_groupe));
    $nb_heures = $reparti[0];
    if ($delta > 0 or ($delta < 0 and $nb_heures + $delta > 0)) {
        if ($id_groupe > 0 and $idem) {
            // recherche de l'ensemble des groupes de même type      
            $result = recherche_groupe($id_diplome,$id_option,$id_groupe);
            while ($groupe = $result->FetchRow()) {
                $reparti = $DB->GetRow("SELECT id
                                        FROM ".$prefix_tables."module_reparti
                                        WHERE id_module=? AND semaine=? AND id_type_seance=? 
                                              AND id_groupe=?",
                                        array($id_module, $semaine, $id_type_seance, $groupe[0]));
                $DB->Execute("UPDATE ".$prefix_tables."module_reparti
                              SET nombre_heures=?
                              WHERE id=?",
                              array($nb_heures+$delta, $reparti[0]));	
            }
        } else {
            $DB->Execute("UPDATE ".$prefix_tables."module_reparti
                          SET nombre_heures=".($nb_heures+$delta)."
                          WHERE id=?",
                          array($reparti[1]));
        }
    } elseif ($id_groupe > 0 and $idem) {
        $result = recherche_groupe($id_diplome,$id_option,$id_groupe);
        while ($groupe = $result->FetchRow()) {
            $reparti = $DB->GetRow("SELECT id
                                    FROM ".$prefix_tables."module_reparti
                                    WHERE id_module=? AND semaine=? AND id_type_seance=? AND id_groupe=?",
                                    array($id_module, $semaine, $id_type_seance, $groupe[0]));
            $DB->Execute("DELETE FROM ".$prefix_tables."module_reparti 
                          WHERE id=?",
                          array($reparti[0]));
        }
    } else {
        $DB->Execute("DELETE FROM ".$prefix_tables."module_reparti 
                      WHERE id=?",
                      array($reparti[1]));
    }
}

function exist_duree($id_module, $semaine, $id_type_seance, $id_groupe) {
    global $prefix_tables, $DB;
    return $DB->GetOne("SELECT COUNT(*)
                        FROM ".$prefix_tables."module_reparti
                        WHERE id_module=? AND semaine=? AND id_type_seance=? AND id_groupe=?",
                        array($id_module, $semaine, $id_type_seance, $id_groupe));
}

function insert_duree($id_module, $semaine, $id_type_seance, $id_diplome, $id_option, $id_groupe, $idem) {
    global $prefix_tables, $DB;
    
    if (!exist_duree($id_module, $semaine, $id_type_seance, $id_groupe)) {
        // verifie si la semaine precedente ce cours a ete programme
        // si c'est le cas alors le nombre d'heures programmees sera egal
        // au nombre d'heures programmees la semaine precedente
        $z = 1;
        $rep = $DB->GetOne("SELECT nombre_heures
                                FROM ".$prefix_tables."module_reparti
                                WHERE id_module=? AND semaine=? AND id_type_seance=? AND id_groupe=?",
                                array($id_module, $semaine-1, $id_type_seance, $id_groupe));
        if ($rep) {
            $z = $rep;
        }

        // mise à jour de la table reparti
        if ($id_groupe > 0 and $idem) {
            if ($id_diplome > 0) {// c'est un diplome
                $request = "SELECT g.id
                            FROM ".$prefix_tables."groupe g, ".$prefix_tables."groupe_type gt
                            WHERE g.id_diplome=? AND gt.id_groupe=g.id AND gt.id_type=?";
                $request_data = array($id_diplome, $id_type_seance);
            } else {// c'est une option
                $request = "SELECT g.id
                            FROM ".$prefix_tables."groupe g, ".$prefix_tables."groupe_type gt
                            WHERE g.id_option=? AND gt.id_groupe=g.id AND gt.id_type=?";
                $request_data = array($id_option, $id_type_seance);
            }
            $result = $DB->Execute($request, $request_data);;
            while ($groupe = $result->FetchRow()) {
                if (!exist_duree($id_module, $semaine, $id_type_seance, $groupe[0])) {
                    $DB->Execute("INSERT INTO ".$prefix_tables."module_reparti
                                  (semaine, nombre_heures, id_type_seance, id_module, id_groupe)
                                  VALUES(?, ?, ?, ?, ?)",
                                  array($semaine, $z, $id_type_seance, $id_module, $groupe[0]));
                }
            }
        } else {
            $DB->Execute("INSERT INTO ".$prefix_tables."module_reparti
                          (semaine, nombre_heures, id_type_seance, id_module, id_groupe)
                          VALUES(?, ?, ?, ?, ?)",
                          array($semaine, $z, $id_type_seance, $id_module, $id_groupe));
        }
    }
}

function insert_duree2($id_module, $semaine, $id_type_seance, $id_groupe, $nb_heures) {
    global $prefix_tables, $DB;
    // mise à jour de la table reparti
    $DB->Execute("INSERT INTO ".$prefix_tables."module_reparti
                  (semaine, nombre_heures, id_type_seance, id_module, id_groupe)
                  VALUES(?, ?, ?, ?, ?)",
                  array($semaine, $nb_heures, $id_type_seance, $id_module, $id_groupe));
}

function delete_duree($id_module, $semaine, $id_type_seance, $id_diplome, $id_option, $id_groupe, $idem) {
    global $prefix_tables, $DB;
    
    $reparti = $DB->GetOne("SELECT id
                            FROM ".$prefix_tables."module_reparti
                            WHERE id_module=? AND semaine=? AND id_type_seance=? AND id_groupe=?",
                            array($id_module, $semaine, $id_type_seance, $id_groupe));
    
    if ($id_groupe > 0 and $idem) {
        if ($id_diplome > 0) { // c'est un diplome
            $request = "SELECT g.id
                        FROM ".$prefix_tables."groupe g, ".$prefix_tables."groupe_type gt
                        WHERE g.id_diplome=? AND gt.id_groupe=g.id AND gt.id_type=?";
            $request_data = array($id_diplome, $id_type_seance);
        } else {// c'est une option
            $request = "SELECT g.id
                        FROM ".$prefix_tables."groupe g, ".$prefix_tables."groupe_type gt
                        WHERE g.id_option=? AND gt.id_groupe=g.id AND gt.id_type=?";
            $request_data = array($id_option, $id_type_seance);
        }
        $result = $DB->Execute($request, $request_data);
        while ($groupe = $result->FetchRow()) {
            $reparti = $DB->GetRow("SELECT id
                                     FROM ".$prefix_tables."module_reparti
                                     WHERE id_module=? AND semaine=? AND id_type_seance=? AND id_groupe=?",
                                    array($id_module, $semaine, $id_type_seance, $groupe[0]));
            $DB->Execute("DELETE FROM ".$prefix_tables."module_reparti 
                          WHERE id=?",
                          array($reparti[0]));
        }
    } else {
        $DB->Execute("DELETE FROM ".$prefix_tables."module_reparti 
                      WHERE id=?",
                      array($reparti));
    }
}

?>