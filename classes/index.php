
<?php

include "../modelo/ClasseDAO.php";

$configDb = include "../db/config.php";
$db = new PDO($configDb['db'], $configDb['usuario'], $configDb['senha']);
$db->exec("set names utf8mb4");

$classes = new ClasseDAO($db);

$resultado = array();

switch($_SERVER['REQUEST_METHOD']) {

    case "GET":
        $resultado = $classes->getAll(
            array(
                "nome" => isset($_GET['nome']) ? $_GET['nome'] : null
            )
        );
        break;

    case "POST":
        $resultado = $classes->insert(
            array(
                "nome" => isset($_POST['nome']) ? $_GET['nome'] : null
            )
        );
        break;

    case "PUT":
        parse_str(file_get_contents("php://input"), $_PUT);

        $resultado = $classes->update(
            array(
                "id" => isset($_PUT['id']) ? $_PUT['id'] : null,
                "nome" => isset($_PUT['nome']) ? $_GET['nome'] : null
            )
        );
        break;

    case "DELETE":
        parse_str(file_get_contents("php://input"), $_DELETE);
        
        $resultado = $classes->remove(intval($_DELETE['id']));
        break;         

}

header('Content-Type: application/json');
echo json_encode($resultado);