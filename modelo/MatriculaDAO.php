<?php

include "Matricula.php";
include "iDAO.php";

Class MatriculaDAO implements iDAO {

    private $db;

    public function __construct(PDO $db) 
    {
        $this->db = $db;
    }

    private function read($row) {

        $result = new Matricula();
        
        $result->id = $row["id"];
        $result->pessoa_id = $row["pessoa_id"];
        $result->classe_id = $row["classe_id"];
        $result->esta_cursando = $row["esta_cursando"] === "1" ? true : false;
        $result->data_entrada = $row["data_entrada"];
        $result->data_saida = $row["data_saida"];
        $result->frequencia = $row["frequencia"];
        
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

        $pessoa_id = $filter["pessoa_id"];
        $classe_id = $filter["classe_id"];
        $esta_cursando = $filter["esta_cursando"];
       // $discipulador = "%" . $filter["discipulador"] . "%";

        $sql = "SELECT mm.id, mm.classe_id, mm.pessoa_id, mm.esta_cursando, mm.data_entrada, mm.data_saida, ROUND(COUNT(ma.id)/13*100,0) as frequencia, CONCAT(primeiro_nome, ' ', ultimo_nome) as nome
        FROM marco_presenca mpr
        INNER JOIN marco_aula ma ON mpr.aula_id = ma.id
        INNER JOIN marco_matricula mm ON mpr.matricula_id = mm.id
        INNER JOIN marco_classe mc ON ma.classe_id = mc.id
        INNER JOIN marco_pessoa mpe ON mm.pessoa_id = mpe.id";

        $condicoes = [];
        $contador = 0;
        
        if(!empty($pessoa_id)) {
            //$sql = $sql . " WHERE pessoa_id = :pid";
            $condicoes[$contador++] = "mm.pessoa_id = :pid";
        }

        if(!empty($classe_id)) {
            $condicoes[$contador++] = "mm.classe_id = :cid";
        }

        if(!empty($esta_cursando)) {
            $condicoes[$contador++] = "mm.esta_cursando = :ec";
        }

        if(sizeof($condicoes) > 0) {
            $sql = $sql . " WHERE ";
            foreach ($condicoes as $c) {
                $sql = $sql . "AND " . $c;
            }
        }

        $sql = $sql . " GROUP BY mm.pessoa_id ORDER BY nome";

        $q = $this->db->prepare($sql);       
        
        if(!empty($pessoa_id)) {
            $q->bindParam(":pid", $pessoa_id, PDO::PARAM_INT);
        }

        if(!empty($classe_id)) {
            $q->bindParam(":cid", $classe_id, PDO::PARAM_INT);
        }

        if(!empty($esta_cursando)) {
            $q->bindParam(":ec", $esta_cursando, PDO::PARAM_BOOL);
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
        
        $sql = "INSERT INTO marco_matricula 
        (pessoa_id, classe_id, esta_cursando, data_entrada, data_saida) 
        VALUES 
        (:pid, :cid, :ec, :dte, :dts)";

        $q = $this->db->prepare($sql);
        $q->bindParam(":pid", $data["pessoa_id"], PDO::PARAM_INT);
        $q->bindParam(":cid", $data["classe_id"], PDO::PARAM_INT);
        $q->bindParam(":ec", $data["esta_cursando"], PDO::PARAM_BOOL);
        $q->bindParam(":dte", $data["data_entrada"]);
        $q->bindParam(":dts", $data["data_saida"]);
        
        if(!$q->execute()) {
            print_r($q->errorInfo());
            return;
        }

        return $this->getById($this->db->lastInsertId());

    }

    public function update($data) {

        $sql = "UPDATE marco_matricula 
        SET 
        pessoa_id = :pid, 
        classe_id = :cid, 
        esta_cursando = :ec, 
        data_entrada = :dte, 
        data_saida = :dts
        WHERE id = :id";

        $q = $this->db->prepare($sql);
        $q->bindParam(":pid", $data["pessoa_id"], PDO::PARAM_INT);
        $q->bindParam(":cid", $data["classe_id"], PDO::PARAM_INT);
        $q->bindParam(":ec", $data["esta_cursando"], PDO::PARAM_BOOL);
        $q->bindParam(":dte", $data["data_entrada"]);
        $q->bindParam(":dts", $data["data_saida"]);
        $q->bindParam(":id", $data["id"], PDO::PARAM_INT);

        if(!$q->execute()) {
            print_r($q->errorInfo());
            return;
        }

    }

    public function remove($id) {
        $sql = "DELETE FROM marco_matricula 
        WHERE id = :id";
        $q = $this->db->prepare($sql);
        $q->bindParam(":id", $id, PDO::PARAM_INT);
        
        if(!$q->execute()) {
            print_r($q->errorInfo());
            return;
        }

    }
    
}