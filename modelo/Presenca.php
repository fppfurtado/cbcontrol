<?php

require_once "Aula.php";
require_once "Matricula.php";

Class Presenca {

    public $aula;
    public $matricula;

    function __construct()
    {
        $this->aula = new Aula();
        $this->matricula = new Matricula();
    }

}