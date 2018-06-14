
<?php

include "../../modelo/PessoaDonsDAO.php";

$configDb = include "../../db/config.php";
$db = new PDO($configDb["db"], $configDb["usuario"], $configDb["senha"]);
$db->exec("set names utf8mb4");

$pessoa_dons = new PessoaDonsDAO($db);

$resultado = array();

switch($_SERVER["REQUEST_METHOD"]) {

    case "GET":
        $resultado = $pessoa_dons->getAll(
            array(
                "pessoa_id" => isset($_GET["pessoa_id"]) ? $_GET["pessoa_id"] : null,
                "dom_id" => isset($_GET["dom_id"]) ? $_GET["dom_id"] : null                
            )
        );
        break;

    case "POST":
        $resultado = $pessoa_dons->insert(
            array(
                "pessoa_id" => isset($_POST["pessoa_id"]) ? $_POST["pessoa_id"] : null,
                "dom_id" => isset($_POST["dom_id"]) ? $_POST["dom_id"] : null
            )
        );
        break;

    case "DELETE":
        parse_str(file_get_contents("php://input"), $_DELETE);
        
        $resultado = $pessoa_dons->remove(intval($_DELETE["dom_id"]));
        break;         

}

header("Content-Type: application/json");
echo json_encode($resultado);