<?php

include "../modelo/PessoaDAO.php";
include "../utilidades/funcoes.php";

$configDb = include "../db/config.php";
$db = new PDO($configDb['db'], $configDb['usuario'], $configDb['senha']);
$pessoas = new PessoaDAO($db);

$resultado = array();

switch($_SERVER['REQUEST_METHOD']) {

    case "GET":
        $resultado = $pessoas->getAll(
            array(
                "primeiro_nome" => $_GET['primeiro_nome'],
                "ultimo_nome" => $_GET['ultimo_nome']
            )
        );

        foreach($resultado as $pessoa) {
            $pessoa->data_nascimento = formatarData($pessoa->data_nascimento);
            $pessoa->data_batismo = formatarData($pessoa->data_batismo);
        }

        break;

    case "POST":
        $resultado = $pessoas->insert(
            array(
                "primeiro_nome" => $_POST['primeiro_nome'],
                "ultimo_nome" => $_POST['ultimo_nome'],
                "data_nascimento" => $_POST['data_nascimento'],
                "data_batismo" => $_POST['data_batismo'],
                "telefone" => $_POST['telefone'],
                "email" => $_POST['email'],
                "e_professor" => $_POST['e_professor'] === "true" ? 1 : 0,
                "discipulador" => !empty($_POST['discipulador']) ? intval($_POST['discipulador']) : 'NULL'
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
                "data_nascimento" => $_PUT['data_nascimento'],
                "data_batismo" => $_PUT['data_batismo'],
                "telefone" => $_PUT['telefone'],
                "email" => $_PUT['email'],
                "e_professor" => $_PUT['e_professor'] === "true" ? 1 : 0,
                "discipulador" => intval($_PUT['discipulador'])
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