<?php

require_once "Matricula.php";
require_once "iDAO.php";

Class AlunoDAO {

    private $db;

    public function __construct(PDO $db) 
    {
        $this->db = $db;
    }

    private function read($row) {

        $result = new Matricula();

        $result->id = $row["id"];
        $result->classe->id = $row["classe_id"];
        $result->classe->nome = $row["classe_nome"];
        $result->pessoa->id = $row["pessoa_id"];
        $result->pessoa->primeiro_nome = $row["pnome"];
        $result->pessoa->ultimo_nome = $row["unome"];
        $result->esta_cursando = $row["esta_cursando"] === "1" ? true : false;        
        
        return $result;

    }

    public function getById($id) {
        
        $sql = "SELECT * FROM marco_matricula WHERE id = :id";

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

        //$pessoa_id = $filter["pessoa_id"];
        $classe_id = $filter["classe_id"];
        //$esta_cursando = $filter["esta_cursando"];
       // $discipulador = "%" . $filter["discipulador"] . "%";

        $sql = "SELECT 
        mm.id,
        mm.pessoa_id, 
        mp.primeiro_nome as pnome, 
        mp.ultimo_nome as unome, 
        mc.id as classe_id,
        mc.nome as classe_nome, 
        mm.esta_cursando 
        FROM marco_matricula mm 
        INNER JOIN marco_pessoa mp ON mm.pessoa_id = mp.id
        INNER JOIN marco_classe mc ON mm.classe_id = mc.id
        WHERE mm.esta_cursando = TRUE";
        
        $condicoes = [];
        $contador = 0;
        
        if(!empty($classe_id)) {
            $condicoes[$contador++] = "classe_id = :cid";
        }

        if(sizeof($condicoes) > 0) {
            $sql = $sql . " WHERE ";
            foreach ($condicoes as $c) {
                $sql = $sql . "AND " . $c;
            }
        }

        $sql = $sql . " ORDER BY pnome";

        $q = $this->db->prepare($sql);       
        
        if(!empty($classe_id)) {
            $q->bindParam(":cid", $classe_id, PDO::PARAM_INT);
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
    
}