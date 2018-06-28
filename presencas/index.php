
<?php

include "../modelo/PresencaDAO.php";

$configDb = include "../db/config.php";
$db = new PDO($configDb["db"], $configDb["usuario"], $configDb["senha"]);
$db->exec("set names utf8mb4");

$presencas = new PresencaDAO($db);

$resultado = array();

switch($_SERVER["REQUEST_METHOD"]) {

    case "GET":
        $resultado = $presencas->getAll(
            array(
                "aula_id" => isset($_GET["aula"]["id"]) ? $_GET["aula"]["id"] : null,
                "matricula_id" => isset($_GET["matricula"]["id"]) ? $_GET["matricula"]["id"] : null                
            )
        );
        break;

    case "POST":
        $resultado = $presencas->insert(
            array(
                "aula_id" => isset($_POST["aula"]["id"]) ? $_POST["aula"]["id"] : null,
                "matricula_id" => isset($_POST["matricula"]["id"]) ? $_POST["matricula"]["id"] : null                
            )
        );
        break;

    case "DELETE":
        parse_str(file_get_contents("php://input"), $_DELETE);
        
        $resultado = $presencas->remove(intval($_DELETE["matricula"]["id"]));
        break;         

}

header("Content-Type: application/json");
echo json_encode($resultado);