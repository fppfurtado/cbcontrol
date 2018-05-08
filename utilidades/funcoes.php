<?php

function formatarData($data) {

    //$data vem em formato aaaa-mm-dd

    //1º passo: quebrar a data pelo separador '-'
    $fdata = explode('-', $data);

    //2º passo: inverter posição do array
    $fdata = array_reverse($fdata);

    //3º passo: concatenar o array
    $fdata = implode('', $fdata);

    //retorna o valor
    return $fdata;

}