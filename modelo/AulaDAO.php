<?php

include "Aula.php";
include "Pessoa.php";
include "Classe.php";
include "iDAO.php";

class AulaDAO implements iDAO
{

    private $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    private function read($row)
    {

        $result = new Aula();

        $result->id = $row["id"];
        $result->data = $row["data"];
        $result->classe_id = $row["classe_id"];
        $result->professor_id = $row["professor_id"];
        $result->num_licao = $row["num_licao"];
        $result->estudo_licao = $row["estudo_licao"];
        $result->pequeno_grupo = $row["pequeno_grupo"];
        $result->estudo_biblico = $row["estudo_biblico"];
        $result->ativ_missionarias = $row["ativ_missionarias"];

        return $result;

    }

    public function getById($id)
    {

        $sql = "SELECT * FROM marco_aula WHERE id = :id";

        $q = $this->db->prepare($sql);
        $q->bindParam(":id", $id, PDO::PARAM_INT);

        if (!$q->execute()) {
            print_r($q->errorInfo());
            return;
        }

        $rows = $q->fetchAll();

        return $this->read($rows[0]);
    }

    public function getAll($filter)
    {

        $data_from = $filter["data_from"];
        $data_to = $filter["data_to"];
        $professor_id = $filter["professor_id"];
        $classe_id = $filter["classe_id"];
        // $discipulador = "%" . $filter["discipulador"] . "%";

        $sql = "SELECT ma.id, ma.data, ma.professor_id as professor_id, ma.classe_id as classe_id,
        ma.num_licao as num_licao, estudo_licao, pequeno_grupo, estudo_biblico, ativ_missionarias
        FROM marco_aula ma 
        INNER JOIN marco_pessoa mp ON ma.professor_id = mp.id
        INNER JOIN marco_classe mc ON ma.classe_id = mc.id";

        $condicoes = [];
        $contador = 0;

        // Incluindo filtros de data_nascimento no SQL
        if (!empty($data_from) and !empty($data_to)) {
            $sql = $sql . " AND (data BETWEEN :df AND :dt)";
        } elseif (!empty($data_from)) {
            $sql = $sql . " AND data >= :df";
        } elseif (!empty($data_to)) {
            $sql = $sql . " AND data < :dt";
        }

        if (!empty($professor_id)) {
            $condicoes[$contador++] = "professor_id = :pid";
        }

        if (!empty($classe_id)) {
            $condicoes[$contador++] = "classe_id = :cid";
        }

        if (sizeof($condicoes) > 0) {
            $sql = $sql . " WHERE ";
            foreach ($condicoes as $c) {
                $sql = $sql . "AND " . $c;
            }
        }

        $sql = $sql . ' ORDER by data';

        $q = $this->db->prepare($sql);       

        // Substituindo parametros de data_nascimento no SQL
        if (!empty($data_from) and !empty($data_to)) {
            $q->bindParam(":df", $data_from);
            $q->bindParam(":dt", $data_to);
        } elseif (!empty($data_from)) {
            $q->bindParam(":df", $data_from);
        } elseif (!empty($data_to)) {
            $q->bindParam(":dt", $data_to);
        }

        if (!empty($professor_id)) {
            $q->bindParam(":pid", $professor_id, PDO::PARAM_INT);
        }

        if (!empty($classe_id)) {
            $q->bindParam(":cid", $classe_id, PDO::PARAM_INT);
        }

        if (!$q->execute()) {
            print_r($q->errorInfo());
            return;
        }

        $rows = $q->fetchAll();
        $result = array();

        foreach ($rows as $row) {
            array_push($result, $this->read($row));
        }
        return $result;
    }

    public function insert($data)
    {

        $sql = "INSERT INTO marco_aula 
        (data, professor_id, classe_id, num_licao, estudo_licao, pequeno_grupo, estudo_biblico, ativ_missionarias) 
        VALUES 
        (:data, :pid, :cid, :nl, :el, :pg, :eb, :am)";

        $q = $this->db->prepare($sql);
        $q->bindParam(":data", $data["data"]);
        $q->bindParam(":pid", $data["professor_id"], PDO::PARAM_INT);
        $q->bindParam(":cid", $data["classe_id"], PDO::PARAM_INT);
        $q->bindParam(":nl", $data["num_licao"], PDO::PARAM_INT);
        $q->bindParam(":el", $data["estudo_licao"], PDO::PARAM_INT);
        $q->bindParam(":pg", $data["pequeno_grupo"], PDO::PARAM_INT);
        $q->bindParam(":eb", $data["estudo_biblico"], PDO::PARAM_INT);
        $q->bindParam(":am", $data["ativ_missionarias"], PDO::PARAM_INT);

        if (!$q->execute()) {
            print_r($q->errorInfo());
            return;
        }

        return $this->getById($this->db->lastInsertId());

    }

    public function update($data)
    {

        $sql = "UPDATE marco_aula 
        SET 
        data = :data,
        professor_id = :pid, 
        classe_id = :cid,
        num_licao = :nl,
        estudo_licao = :el, 
        pequeno_grupo = :pg,
        estudo_biblico = :eb,
        ativ_missionarias = :am
        WHERE id = :id";

        $q = $this->db->prepare($sql);
        $q->bindParam(":data", $data["data"]);
        $q->bindParam(":pid", $data["professor_id"], PDO::PARAM_INT);
        $q->bindParam(":cid", $data["classe_id"], PDO::PARAM_INT);
        $q->bindParam(":nl", $data["num_licao"], PDO::PARAM_INT);
        $q->bindParam(":el", $data["estudo_licao"], PDO::PARAM_INT);
        $q->bindParam(":pg", $data["pequeno_grupo"], PDO::PARAM_INT);
        $q->bindParam(":eb", $data["estudo_biblico"], PDO::PARAM_INT);
        $q->bindParam(":am", $data["ativ_missionarias"], PDO::PARAM_INT);
        $q->bindParam(":id", $data["id"], PDO::PARAM_INT);

        if (!$q->execute()) {
            print_r($q->errorInfo());
            return;
        }

    }

    public function remove($id)
    {
        $sql = "DELETE FROM marco_aula 
        WHERE id = :id";
        $q = $this->db->prepare($sql);
        $q->bindParam(":id", $id, PDO::PARAM_INT);

        if (!$q->execute()) {
            print_r($q->errorInfo());
            return;
        }

    }

}