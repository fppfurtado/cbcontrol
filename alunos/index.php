
<?php

include "../modelo/AlunoDAO.php";

$configDb = include "../db/config.php";
$db = new PDO($configDb["db"], $configDb["usuario"], $configDb["senha"]);
$db->exec("set names utf8mb4");

$alunos = new AlunoDAO($db);

$resultado = array();

switch($_SERVER["REQUEST_METHOD"]) {

    case "GET":
        $resultado = $alunos->getAll(
            array(
                "classe_id" => isset($_GET["classe_id"]) ? $_GET["classe_id"] : null,
             )
        );
        break;
    
}

header("Content-Type: application/json");
echo json_encode($resultado);