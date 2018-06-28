<?php

require_once "iDAO.php";

Class PessoaDonsDAO implements iDAO {

    private $db;

    public function __construct(PDO $db) 
    {
        $this->db = $db;        
    }

    private function read($row) {

        $result = [
            "pessoa_id" => $row["pessoa_id"],
            "dom_id" => $row["dom_id"],
            "dom_nome" => $row["nome"]
        ];

        return $result;

    }

    public function getById($id) {
        
        $sql = "SELECT * FROM marco_pessoa_dom pd
        INNER JOIN marco_dom_espiritual de ON pd.dom_id = de.id
        WHERE pd.dom_id = :did";

        $q = $this->db->prepare($sql);
        $q->bindParam(":did", $id, PDO::PARAM_INT);

        if(!$q->execute()) {
            print_r($q->errorInfo());
            return;
        }

        $rows = $q->fetchAll();
        
        return $this->read($rows[sizeof($rows)-1]);
    }

    public function getAll($filter) {

        $pessoa_id = $filter["pessoa_id"];
        
        $sql = "SELECT pessoa_id, dom_id, de.nome FROM marco_pessoa_dom pd
        INNER JOIN marco_dom_espiritual de ON pd.dom_id = de.id";
        
        if(!empty($pessoa_id)) {
           $sql = $sql . " WHERE pessoa_id = :pid";
        }

        $sql = $sql . " ORDER BY nome";
        
        $q = $this->db->prepare($sql);       
        
        if(!empty($pessoa_id)) {
            $q->bindParam(":pid", $pessoa_id, PDO::PARAM_INT);
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
        
        $sql = "INSERT INTO marco_pessoa_dom 
        (pessoa_id, dom_id) 
        VALUES 
        (:pid, :did)";

        $q = $this->db->prepare($sql);
        $q->bindParam(":pid", $data["pessoa_id"], PDO::PARAM_INT);
        $q->bindParam(":did", $data["dom_id"], PDO::PARAM_INT);
        
        if(!$q->execute()) {
            print_r($q->errorInfo());
            return;
        }
        
        return $this->getById($data["dom_id"]);

    }

    public function update($data)
    {
        return;
    }

    public function remove($id) {
        $sql = "DELETE FROM marco_pessoa_dom 
        WHERE dom_id = :did";
        $q = $this->db->prepare($sql);
        $q->bindParam(":did", $id, PDO::PARAM_INT);
        
        if(!$q->execute()) {
            print_r($q->errorInfo());
            return;
        }

    }
    
}