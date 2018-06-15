
<?php

include "../modelo/MatriculaDAO.php";

$configDb = include "../db/config.php";
$db = new PDO($configDb["db"], $configDb["usuario"], $configDb["senha"]);
$db->exec("set names utf8mb4");

$matriculas = new MatriculaDAO($db);

$resultado = array();

switch($_SERVER["REQUEST_METHOD"]) {

    case "GET":
        $resultado = $matriculas->getAll(
            array(
                "pessoa_id" => isset($_GET["pessoa_id"]) ? $_GET["pessoa_id"] : null,
                "classe_id" => isset($_GET["classe_id"]) ? $_GET["classe_id"] : null,
                "esta_cursando" => isset($_GET["esta_cursando"]) ? ($_GET["esta_cursando"] === "true" ? 1 : 0) : null,
                "data_entrada_from" => isset($_GET["data_entrada"]["from"]) ? $_GET["data_entrada"]["from"] : null,
                "data_entrada_to" => isset($_GET["data_entrada"]["to"]) ? $_GET["data_entrada"]["to"] : null,
                "data_saida_from" => isset($_GET["data_saida"]["from"]) ? $_GET["data_saida"]["from"] : null,
                "data_saida_to" => isset($_GET["data_saida"]["to"]) ? $_GET["data_saida"]["to"] : null,
                "frequencia" => isset($_GET["frequencia"]) ? $_GET["frequencia"] : null
            )
        );
        break;

    case "POST":
        $resultado = $matriculas->insert(
            array(
                "pessoa_id" => isset($_POST["pessoa_id"]) ? $_POST["pessoa_id"] : null,
                "classe_id" => isset($_POST["classe_id"]) ? $_POST["classe_id"] : null,
                "esta_cursando" => isset($_POST["esta_cursando"]) ? ($_POST["esta_cursando"] === "true" ? 1 : 0) : null,
                "data_entrada" => isset($_POST["data_entrada"]) ? $_POST["data_entrada"] : null,
                "data_saida" => isset($_POST["data_saida"]) ? $_POST["data_saida"] : null
            )
        );
        break;

    case "PUT":
        parse_str(file_get_contents("php://input"), $_PUT);

        $resultado = $matriculas->update(
            array(
                "id" => isset($_PUT["id"]) ? $_PUT["id"] : null,
                "pessoa_id" => isset($_PUT["pessoa_id"]) ? $_PUT["pessoa_id"] : null,
                "classe_id" => isset($_PUT["classe_id"]) ? $_PUT["classe_id"] : null,
                "esta_cursando" => isset($_PUT["esta_cursando"]) ? ($_PUT["esta_cursando"] === "true" ? 1 : 0) : null,
                "data_entrada" => isset($_PUT["data_entrada"]) ? $_PUT["data_entrada"] : null,
                "data_saida" => isset($_PUT["data_saida"]) ? $_PUT["data_saida"] : null
            )
        );
        break;

    case "DELETE":
        parse_str(file_get_contents("php://input"), $_DELETE);
        
        $resultado = $matriculas->remove(intval($_DELETE["id"]));
        break;         

}

header("Content-Type: application/json");
echo json_encode($resultado);