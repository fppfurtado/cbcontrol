
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
                "aula_id" => isset($_GET["aula_id"]) ? $_GET["aula_id"] : null,
                "matricula_id" => isset($_GET["matricula_id"]) ? $_GET["matricula_id"] : null                
            )
        );
        break;

    case "POST":
        $resultado = $presencas->insert(
            array(
                "aula_id" => isset($_POST["aula_id"]) ? $_POST["aula_id"] : null,
                "matricula_id" => isset($_POST["matricula_id"]) ? $_POST["matricula_id"] : null                
            )
        );
        break;

    case "DELETE":
        parse_str(file_get_contents("php://input"), $_DELETE);
        
        $resultado = $presencas->remove(intval($_DELETE["matricula_id"]));
        break;         

}

header("Content-Type: application/json");
echo json_encode($resultado);