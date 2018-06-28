<?php

require_once "Dom.php";
require_once "iDAO.php";

Class DomDAO implements iDAO {

    private $db;

    public function __construct(PDO $db) 
    {
        $this->db = $db;
    }

    private function read($row) {

        $result = new Dom();
        
        $result->id = $row["id"];
        $result->nome = $row["nome"];
        
        return $result;

    }

    public function getById($id) {
        
        $sql = "SELECT * FROM marco_dom_espiritual WHERE id = :id";

        $q = $this->db->prepare($sql);
        $q->bindParam(":id", $id, PDO::PARAM_INT);

        if(!$q->execute()) {
            print_r($q->errorInfo());
            return;
        }

        $rows = $q->fetchAll();

        return $this->read($rows[0]);
    }

    public function getAll($filter) {

        $nome = "%" . $filter["nome"] . "%";        
        $sql = "SELECT * FROM marco_dom_espiritual WHERE nome LIKE :nome";

        $q = $this->db->prepare($sql);        
        $q->bindParam(":nome", $nome);
        
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
        
        $sql = "INSERT INTO marco_dom_espiritual (nome) VALUES (:nome)";

        $q = $this->db->prepare($sql);
        $q->bindParam(":nome", $data["nome"]);
        
        if(!$q->execute()) {
            print_r($q->errorInfo());
            return;
        }

        return $this->getById($this->db->lastInsertId());

    }

    public function update($data) {

        $sql = "UPDATE marco_dom_espiritual SET nome = :nome WHERE id = :id";

        $q = $this->db->prepare($sql);
        $q->bindParam(":nome", $data["nome"]);
        
        if(!$q->execute()) {
            print_r($q->errorInfo());
            return;
        }

    }

    public function remove($id) {
        $sql = "DELETE FROM marco_dom_espiritual 
        WHERE id = :id";
        $q = $this->db->prepare($sql);
        $q->bindParam(":id", $id, PDO::PARAM_INT);
        
        if(!$q->execute()) {
            print_r($q->errorInfo());
            return;
        }

    }
    
}