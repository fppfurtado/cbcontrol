<?php

require_once "Classe.php";
require_once "Pessoa.php";

Class Aula {

    public $id;
    public $data;
    public $classe;
    public $professor;
    public $num_licao;
    public $estudo_licao;
    public $pequeno_grupo;
    public $estudo_biblico;
    public $ativ_missionarias;

    function __construct()
    {
        $this->classe = new Classe();
        $this->professor = new Pessoa();
    }

}