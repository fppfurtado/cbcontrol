
<?php

include "../modelo/PresencaDAO.php";

$configDb = include "../db/config.php";
$db = new PDO($configDb["db"], $configDb["usuario"], $configDb["senha"]);
$presencas = new PresencaDAO($db);

$resultado = array();

switch($_SERVER["REQUEST_METHOD"]) {

    case "GET":
        $resultado = $presencas->getAll(
            array(
                "aula_id" => isset($_GET["aula_id"]) ? $_GET["aula_id"] : null,
                "pessoa_id" => isset($_GET["pessoa_id"]) ? $_GET["pessoa_id"] : null
                
            )
        );
        break;

    case "POST":
        $resultado = $presencas->insert(
            array(
                "aula_id" => isset($_POST["aula_id"]) ? $_POST["aula_id"] : null,
                "pessoa_id" => isset($_POST["pessoa_id"]) ? $_POST["pessoa_id"] : null                
            )
        );
        break;

    case "DELETE":
        parse_str(file_get_contents("php://input"), $_DELETE);
        
        $resultado = $presencas->remove(intval($_DELETE["pessoa_id"]));
        break;         

}

header("Content-Type: application/json");
echo json_encode($resultado);