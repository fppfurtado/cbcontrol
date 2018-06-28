<?php

require_once "Presenca.php";
require_once "iDAO.php";

Class PresencaDAO implements iDAO {

    private $db;

    public function __construct(PDO $db) 
    {
        $this->db = $db;
    }

    private function read($row) {

        $result = new Presenca();

        $result->aula->id = $row["aula_id"];
        $result->matricula->id = $row["matricula_id"];
        $result->matricula->classe->id = $row["classe_id"];
        
        return $result;

    }

    public function getById($id) {
        
        $sql = "SELECT
        mpr.aula_id,
        mpr.matricula_id,
        mm.classe_id
        FROM marco_presenca mpr
        INNER JOIN marco_matricula mm ON mpr.matricula_id = mm.id
        INNER JOIN marco_pessoa mpe ON mm.pessoa_id = mpe.id
        INNER JOIN marco_classe mc ON mm.classe_id = mc.id
        WHERE mpr.matricula_id = :mid";

        $q = $this->db->prepare($sql);
        $q->bindParam(":mid", $id, PDO::PARAM_INT);

        if(!$q->execute()) {
            print_r($q->errorInfo());
            return;
        }

        $rows = $q->fetchAll();

        return $this->read($rows[sizeof($rows)-1]);
    }

    public function getAll($filter) {

        $aula_id = $filter["aula_id"];
        
        $sql = "SELECT 
        mpr.aula_id,
        mpr.matricula_id, 
        mm.classe_id,
        CONCAT(primeiro_nome, ' ', ultimo_nome) as nome 
        FROM marco_presenca mpr
        INNER JOIN marco_matricula mm ON mpr.matricula_id = mm.id
        INNER JOIN marco_pessoa mpe ON mm.pessoa_id = mpe.id
        INNER JOIN marco_classe mc ON mm.classe_id = mc.id";
        
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
        (aula_id, matricula_id) 
        VALUES 
        (:aid, :mid)";

        $q = $this->db->prepare($sql);
        $q->bindParam(":aid", $data["aula_id"], PDO::PARAM_INT);
        $q->bindParam(":mid", $data["matricula_id"], PDO::PARAM_INT);
        
        if(!$q->execute()) {
            print_r($q->errorInfo());
            return;
        }
        
        return $this->getById($data["matricula_id"]);

    }

    public function update($data) {

        $sql = "UPDATE marco_presenca 
        SET 
        aula_id = :aid, 
        pessoa_id = :mid
        WHERE id = :id";

        $q = $this->db->prepare($sql);
        $q->bindParam(":aid", $data["aula_id"], PDO::PARAM_INT);
        $q->bindParam(":mid", $data["matricula_id"], PDO::PARAM_INT);
        
        if(!$q->execute()) {
            print_r($q->errorInfo());
            return;
        }

    }

    public function remove($id) {
        $sql = "DELETE FROM marco_presenca 
        WHERE matricula_id = :mid";
        $q = $this->db->prepare($sql);
        $q->bindParam(":mid", $id, PDO::PARAM_INT);
        
        if(!$q->execute()) {
            print_r($q->errorInfo());
            return;
        }

    }
    
}