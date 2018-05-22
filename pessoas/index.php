<?php

include "../modelo/PessoaDAO.php";

$configDb = include "../db/config.php";
$db = new PDO($configDb['db'], $configDb['usuario'], $configDb['senha']);
$pessoas = new PessoaDAO($db);

$resultado = array();

switch($_SERVER['REQUEST_METHOD']) {

    case "GET":
        $resultado = $pessoas->getAll(
            array(
                "primeiro_nome" => $_GET['primeiro_nome'],
                "ultimo_nome" => $_GET['ultimo_nome'],
                "data_nascimento_from" => $_GET['data_nascimento']['from'],
                "data_nascimento_to" => $_GET['data_nascimento']['to'],
                "data_batismo_from" => $_GET['data_batismo']['from'],
                "data_batismo_to" => $_GET['data_batismo']['to'],
                "e_professor" => isset($_GET['e_professor']) ? $_GET['e_professor'] : null 
              //  "discipulador" => intval($_GET['discipulador'])
            )
        );
        break;

    case "POST":
        $resultado = $pessoas->insert(
            array(
                "primeiro_nome" => $_POST['primeiro_nome'],
                "ultimo_nome" => $_POST['ultimo_nome'],
                "data_nascimento" => empty($_POST['data_nascimento']) ? null : $_POST['data_nascimento'],
                "data_batismo" => empty($_POST['data_batismo']) ? null : $_POST['data_batismo'],
                "telefone" => empty($_POST['telefone']) ? null : $_POST['telefone'],
                "email" => empty($_POST['email']) ? null : $_POST['email'],
                "e_professor" => $_POST['e_professor'] === "true" ? 1 : 0,
                "discipulador" => empty($_POST['discipulador']) ? null : intval($_POST['discipulador'])
            )
        );
        break;

    case "PUT":
        parse_str(file_get_contents("php://input"), $_PUT);

        $resultado = $pessoas->update(
            array(
                "id" => $_PUT['id'],
                "primeiro_nome" => $_PUT['primeiro_nome'],
                "ultimo_nome" => $_PUT['ultimo_nome'],
                "data_nascimento" => empty($_PUT['data_nascimento']) ? null : $_PUT['data_nascimento'],
                "data_batismo" => empty($_PUT['data_batismo']) ? null : $_PUT['data_batismo'],
                "telefone" => empty($_PUT['telefone']) ? null : $_PUT['telefone'],
                "email" => empty($_PUT['email']) ? null : $_PUT['email'],
                "e_professor" => $_PUT['e_professor'] === "true" ? 1 : 0,
                "discipulador" => empty($_PUT['discipulador']) ? null : intval($_PUT['discipulador'])
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