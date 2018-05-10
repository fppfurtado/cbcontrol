<?php

include "Pessoa.php";
include "iDAO.php";

Class PessoaDAO implements iDAO {

    private $db;

    public function __construct(PDO $db) 
    {
        $this->db = $db;
    }

    private function read($row) {

        $result = new Pessoa();

        $result->id = $row["id"];
        $result->primeiro_nome = $row["primeiro_nome"];
        $result->ultimo_nome = $row["ultimo_nome"];
        $result->data_nascimento = $row["data_nascimento"];
        $result->data_batismo = $row["data_batismo"];
        $result->telefone = $row["telefone"];
        $result->email = $row["email"];
        $result->e_professor = $row["e_professor"] == 1 ? true : false;
        $result->discipulador = $row["discipulador"];

        return $result;

    }

    public function getById($id) {
        
        $sql = "SELECT * FROM marco_pessoa WHERE id = :id";

        $q = $this->db->prepare($sql);
        $q->bindParam(":id", $id, PDO::PARAM_INT);
        $q->execute();

        $rows = $q->fetchAll();

        return $this->read($rows[0]);
    }

    public function getAll($filter) {

        $pnome = "%" . $filter["primeiro_nome"] . "%";
        $unome = "%" . $filter["ultimo_nome"] . "%";
        //$eProf = "%" . $filter["e_professor"] . "%";
       // $discipulador = "%" . $filter["discipulador"] . "%";


        $sql = "SELECT * FROM marco_pessoa
        WHERE 
        primeiro_nome LIKE :pnome 
        AND ultimo_nome LIKE :unome ORDER BY primeiro_nome, ultimo_nome";
        //AND e_professor = :eProf 
        //AND discipulador = :discipulador;

        $q = $this->db->prepare($sql);
        $q->bindParam(":pnome", $pnome);
        $q->bindParam(":unome", $unome);
        //$q->bindParam(":eProf", $eProf);
        //$q->bindParam(":discipulador", $discipulador);
        $q->execute();
        $rows = $q->fetchAll();

        $result = array();
        foreach($rows as $row) {
            array_push($result, $this->read($row));
        }
        return $result;
    }

    public function insert($data) {

        $sql = "INSERT INTO marco_pessoa 
        (primeiro_nome, ultimo_nome, data_nascimento, data_batismo, telefone, email, e_professor, discipulador) 
        VALUES 
        (:pnome, :unome, :dtaNasc, :dtaBatismo, :tel, :email, :eProf, :discipulador)";

        $q = $this->db->prepare($sql);
        $q->bindParam(":pnome", $data["primeiro_nome"]);
        $q->bindParam(":unome", $data["ultimo_nome"]);
        $q->bindParam(":dtaNasc", $data["data_nascimento"], PDO::PARAM_STR);
        $q->bindParam(":dtaBatismo", $data["data_batismo"], PDO::PARAM_STR);
        $q->bindParam(":tel", $data["telefone"]);
        $q->bindParam(":email", $data["email"]);
        $q->bindParam(":eProf", $data["e_professor"], PDO::PARAM_INT);
        $q->bindParam(":discipulador", $data["discipulador"], PDO::PARAM_INT);
        echo $q->execute();

        return $this->getById($this->db->lastInsertId());

    }

    public function update($data) {

        $sql = "UPDATE marco_pessoa 
        SET 
        primeiro_nome = :pnome, 
        ultimo_nome = :unome, 
        data_nascimento = :dtaNasc, 
        data_batismo = :dtaBatismo, 
        telefone = :tel,
        email = :email,
        e_professor = :eProf,
        discipulador = :discipulador 
        WHERE id = :id";

        $q = $this->db->prepare($sql);
        $q->bindParam(":pnome", $data["primeiro_nome"]);
        $q->bindParam(":unome", $data["ultimo_nome"]);
        $q->bindParam(":dtaNasc", $data["data_nascimento"], PDO::PARAM_STR);
        $q->bindParam(":dtaBatismo", $data["data_batismo"], PDO::PARAM_STR);
        $q->bindParam(":tel", $data["telefone"]);
        $q->bindParam(":email", $data["email"]);
        $q->bindParam(":eProf", $data["e_professor"], PDO::PARAM_INT);
        $q->bindParam(":discipulador", $data["discipulador"], PDO::PARAM_INT);
        $q->bindParam(":id", $data["id"], PDO::PARAM_INT);
        $q->execute();
    }

    public function remove($id) {
        $sql = "DELETE FROM marco_pessoa 
        WHERE id = :id";
        $q = $this->db->prepare($sql);
        $q->bindParam(":id", $id, PDO::PARAM_INT);
        $q->execute();
    }
    
}