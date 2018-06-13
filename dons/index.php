
<?php

include "../modelo/DomDAO.php";

$configDb = include "../db/config.php";
$db = new PDO($configDb['db'], $configDb['usuario'], $configDb['senha']);
$dons = new DomDAO($db);

$resultado = array();

switch($_SERVER['REQUEST_METHOD']) {

    case "GET":
        $resultado = $dons->getAll(
            array(
                "nome" => isset($_GET['nome']) ? $_GET['nome'] : null
            )
        );
        break;

    case "POST":
        $resultado = $dons->insert(
            array(
                "nome" => isset($_POST['nome']) ? $_GET['nome'] : null
            )
        );
        break;

    case "PUT":
        parse_str(file_get_contents("php://input"), $_PUT);

        $resultado = $dons->update(
            array(
                "id" => isset($_PUT['id']) ? $_PUT['id'] : null,
                "nome" => isset($_PUT['nome']) ? $_GET['nome'] : null
            )
        );
        break;

    case "DELETE":
        parse_str(file_get_contents("php://input"), $_DELETE);
        
        $resultado = $dons->remove(intval($_DELETE['id']));
        break;         

}

header('Content-Type: application/json');
echo json_encode($resultado);