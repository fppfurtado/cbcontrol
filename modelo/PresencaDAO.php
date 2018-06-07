<?php

include "Presenca.php";
include "iDAO.php";

Class PresencaDAO implements iDAO {

    private $db;

    public function __construct(PDO $db) 
    {
        $this->db = $db;
    }

    private function read($row) {

        $result = new Presenca();
        
        $result->aula_id = $row["aula_id"];
        $result->pessoa_id = $row["pessoa_id"];
        
        return $result;

    }

    public function getById($id) {
        
        $sql = "SELECT * FROM marco_presenca WHERE aula_id = :aid";

        $q = $this->db->prepare($sql);
        $q->bindParam(":aid", $id, PDO::PARAM_INT);

        if(!$q->execute()) {
            print_r($q->errorInfo());
            return;
        }

        $rows = $q->fetchAll();

        return $this->read($rows[0]);
    }

    public function getAll($filter) {

        $aula_id = $filter["aula_id"];
        
        $sql = "SELECT aula_id, pessoa_id, CONCAT(primeiro_nome, ' ', ultimo_nome) as nome FROM marco_presenca pr
        INNER JOIN marco_pessoa pe ON pr.pessoa_id = pe.id";
        
        if(!empty($aula_id)) {
           $sql = $sql . " WHERE aula_id = :aid";
        }

        $sql = $sql . " ORDER BY nome";
        
        $q = $this->db->prepare($sql);       
        
        if(!empty($aula_id)) {
            $q->bindParam(":aid", $aula_id, PDO::PARAM_INT);
        }
        
        if(!$q->execute()) {
            print_r($q->errorInfo());
            return;
        }

        $rows = $q->fetchAll();
        $result = array();

        foreach($rows as $row) {
            array_push($result, $this->read($row));
        }

        return $result;

    }

    public function insert($data) {
        
        $sql = "INSERT INTO marco_presenca 
        (aula_id, pessoa_id) 
        VALUES 
        (:aid, :pid)";

        $q = $this->db->prepare($sql);
        $q->bindParam(":aid", $data["aula_id"], PDO::PARAM_INT);
        $q->bindParam(":pid", $data["pessoa_id"], PDO::PARAM_INT);
        
        if(!$q->execute()) {
            print_r($q->errorInfo());
            return;
        }
        
        return $this->getById($data["aula_id"]);

    }

    public function update($data) {

        $sql = "UPDATE marco_presenca 
        SET 
        aula_id = :aid, 
        pessoa_id = :pid
        WHERE id = :id";

        $q = $this->db->prepare($sql);
        $q->bindParam(":aid", $data["aula_id"], PDO::PARAM_INT);
        $q->bindParam(":pid", $data["pessoa_id"], PDO::PARAM_INT);
        
        if(!$q->execute()) {
            print_r($q->errorInfo());
            return;
        }

    }

    public function remove($id) {
        $sql = "DELETE FROM marco_presenca 
        WHERE pessoa_id = :pid";
        $q = $this->db->prepare($sql);
        $q->bindParam(":pid", $id, PDO::PARAM_INT);
        
        if(!$q->execute()) {
            print_r($q->errorInfo());
            return;
        }

    }
    
}