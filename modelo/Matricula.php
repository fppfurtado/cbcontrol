<?php

require_once "Pessoa.php";
require_once "Classe.php";

Class Matricula {

    public $id;
    public $pessoa;
    public $classe;
    public $esta_cursando;
    public $data_entrada;
    public $data_saida;
    public $frequencia;

    function __construct(){
        $this->pessoa = new Pessoa();
        $this->classe = new Classe();
    }

}
