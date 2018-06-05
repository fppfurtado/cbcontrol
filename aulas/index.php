
<?php

include "../modelo/AulaDAO.php";

$configDb = include "../db/config.php";
$db = new PDO($configDb["db"], $configDb["usuario"], $configDb["senha"]);
$aulas = new AulaDAO($db);

$resultado = array();

switch($_SERVER["REQUEST_METHOD"]) {

    case "GET":
        $resultado = $aulas->getAll(
            array(
                "data_from" => isset($_GET["data"]["from"]) ? $_GET["data"]["from"] : null,
                "data_to" => isset($_GET["data"]["to"]) ? $_GET["data"]["to"] : null,
                "classe_id" => isset($_GET["classe_id"]) ? $_GET["classe_id"] : null,
                "professor_id" => isset($_GET["professor_id"]) ? $_GET["professor_id"] : null,                
                "num_licao" => isset($_GET["num_licao"]) ? $_GET["num_licao"] : null,
                "estudo_licao" => isset($_GET["estudo_licao"]) ? $_GET["estudo_licao"] : null,
                "pequeno_grupo" => isset($_GET["pequeno_grupo"]) ? $_GET["pequeno_grupo"] : null,
                "estudo_biblico" => isset($_GET["estudo_biblico"]) ? $_GET["estudo_biblico"] : null,
                "ativ_missionarias" => isset($_GET["ativ_missionarias"]) ? $_GET["ativ_missionarias"] : null
            )
        );
        break;

    case "POST":
        $resultado = $aulas->insert(
            array(
                "data" => isset($_POST["data"]) ? $_POST["data"] : null,
                "classe_id" => isset($_POST["classe_id"]) ? $_POST["classe_id"] : null,
                "professor_id" => isset($_POST["professor_id"]) ? $_POST["professor_id"] : null,                
                "num_licao" => isset($_POST["num_licao"]) ? $_POST["num_licao"] : null,
                "estudo_licao" => isset($_POST["estudo_licao"]) ? $_POST["estudo_licao"] : null,
                "pequeno_grupo" => isset($_POST["pequeno_grupo"]) ? $_POST["pequeno_grupo"] : null,
                "estudo_biblico" => isset($_POST["estudo_biblico"]) ? $_POST["estudo_biblico"] : null,
                "ativ_missionarias" => isset($_POST["ativ_missionarias"]) ? $_POST["ativ_missionarias"] : null
            )
        );
        break;

    case "PUT":
        parse_str(file_get_contents("php://input"), $_PUT);

        $resultado = $aulas->update(
            array(
                "id" => isset($_PUT["id"]) ? $_PUT["id"] : null,
                "data" => isset($_PUT["data"]) ? $_PUT["data"] : null,
                "classe_id" => isset($_PUT["classe_id"]) ? $_PUT["classe_id"] : null,
                "professor_id" => isset($_PUT["professor_id"]) ? $_PUT["professor_id"] : null,                
                "num_licao" => isset($_PUT["num_licao"]) ? $_PUT["num_licao"] : null,
                "estudo_licao" => isset($_PUT["estudo_licao"]) ? $_PUT["estudo_licao"] : null,
                "pequeno_grupo" => isset($_PUT["pequeno_grupo"]) ? $_PUT["pequeno_grupo"] : null,
                "estudo_biblico" => isset($_PUT["estudo_biblico"]) ? $_PUT["estudo_biblico"] : null,
                "ativ_missionarias" => isset($_PUT["ativ_missionarias"]) ? $_PUT["ativ_missionarias"] : null
            )
        );
        break;

    case "DELETE":
        parse_str(file_get_contents("php://input"), $_DELETE);
        
        $resultado = $aulas->remove(intval($_DELETE["id"]));
        break;         

}

header("Content-Type: application/json");
echo json_encode($resultado);