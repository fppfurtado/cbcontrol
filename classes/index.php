
<?php

include "../modelo/ClasseDAO.php";

$configDb = include "../db/config.php";
$db = new PDO($configDb['db'], $configDb['usuario'], $configDb['senha']);
$pessoas = new ClasseDAO($db);

$resultado = array();

switch($_SERVER['REQUEST_METHOD']) {

    case "GET":
        $resultado = $pessoas->getAll(
            array(
                "nome" => isset($_GET['nome']) ? $_GET['nome'] : null
            )
        );
        break;

    case "POST":
        $resultado = $pessoas->insert(
            array(
                "nome" => isset($_POST['nome']) ? $_GET['nome'] : null
            )
        );
        break;

    case "PUT":
        parse_str(file_get_contents("php://input"), $_PUT);

        $resultado = $pessoas->update(
            array(
                "id" => isset($_PUT['id']) ? $_PUT['id'] : null,
                "nome" => isset($_PUT['nome']) ? $_GET['nome'] : null
            )
        );
        break;

    case "DELETE":
        parse_str(file_get_contents("php://input"), $_DELETE);
        
        $resultado = $pessoas->remove(intval($_DELETE['id']));
        break;         

}

header('Content-Type: application/json');
echo json_encode($resultado);