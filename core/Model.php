<?php

class Model {

    public function __construct() {
        try {
            $this->db = Connect::getInstanceC();
        } catch (Exception $ex) {
            error_log($ex->getMessage());
        }
    }

    public function cleanData($data) {
        return htmlentities(htmlentities($data));
    }

    public function lire($champs = null) {

        if ($champs == null) {
            $champs = '*';
        }

        $req = "SELECT $champs FROM " . $this->table . " WHERE id=" . $this->id;

        $resultat = array();
        $execution = $this->db->query($req);

        if ($execution) {
            while ($res = $execution->fetch()) {
                $resultat[] = $res;
            }
        } else {
            error_log($req);
            error_log($this->db->errorInfo()[2]);
        }

        return $resultat;
    }

    public function recherche($donnees = array()) {
        $champs = '*';
        $conditions = '1=1';
        $order = 'id DESC';
        $limit = '25';

        if (!empty($donnees['champs'])) {
            $champs = $donnees['champs'];
        }
        if (!empty($donnees['conditions'])) {
            $conditions = $donnees['conditions'];
        }
        if (!empty($donnees['order'])) {
            $order = $donnees['order'];
        }
        if (!empty($donnees['limit'])) {
            $limit = $donnees['limit'];
        }

        $req = "SELECT $champs FROM " . $this->table . " WHERE $conditions ORDER BY $order LIMIT $limit";
        error_log($req);
        
        $resultat = array();
        $execution = $this->db->query($req);
        if ($execution) {
            while ($res = $execution->fetch()) {
                $resultat[] = $res;
            }
        } else {
            error_log($req);
            error_log($this->db->errorInfo()[2]);
        }
        return $resultat;
    }

    public function ajouter($champs = array(), $needID = FALSE) {
        if (!empty($champs)) {
            $sql = "INSERT INTO " . $this->table . "(";
            foreach ($champs as $attribut => $contenu):
                $sql .= $attribut . ",";
            endforeach;
            $sql = substr($sql, 0, -1);

            $sql .= ") VALUES (";
            foreach ($champs as $attribut => $contenu):
                $sql .= "'$contenu',";
            endforeach;
            $sql = substr($sql, 0, -1);
            $sql .= ")";

            if ($this->db->query($sql)) {
                return ($needID) ? $this->db->lastInsertId() : TRUE;
            } else {
                error_log($sql);
                error_log($this->db->errorInfo()[2]);
                return ($needID) ? 0 : FALSE;
            }
        }
    }

    // send flow
    public function modifier($champs = array()) {
        if (!empty($champs)) {
            $sql = "UPDATE " . $this->table . " SET ";
            foreach ($champs as $attribut => $contenu):
                if ($attribut != 'id') {
                    $sql .= "`" . $attribut . "`='$contenu', ";
                }
            endforeach;
            $sql = substr($sql, 0, -2);

            $sql .= " WHERE id=" . $champs['id'];
            if ($this->db->query($sql)) {
                return TRUE;
            } else {
                error_log($sql);
                error_log($this->db->errorInfo()[2]);
                return FALSE;
            }
        }
    }

    public function supprimer($id) {
        $sql = "DELETE FROM " . $this->table . " WHERE id = '$id'";
        if ($this->db->query($sql)) {
            return TRUE;
        } else {
            error_log($this->db->errorInfo()[2]);
            return FALSE;
        }
    }

    public function executer($sql) {
        if ($sql != "" && $this->db->query($sql)) {
            return TRUE;
        } else {
            error_log($this->db->errorInfo()[2]);
            return FALSE;
        }
    }

    public function executerReq($sql) {
        $resultat = array();
        $execution = $this->db->query($sql);
        if ($execution) {
            while ($res = $execution->fetch()) {
                $resultat[] = $res;
            }
        } else {
            error_log($sql);
            error_log($this->db->errorInfo()[2]);
        }
        return $resultat;
    }

}

