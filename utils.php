<?php


function is_alpha($input)
{
    return preg_match('/^[a-z\s]*$/i', $input);
}

function get_numero_domicilio($domicilio) {
    $ini_pos = strpos($domicilio, '#');
    $num = "";
    if ($ini_pos !== FALSE) {
        $num = substr($domicilio, $ini_pos + 1);
    }

    return trim($num);
}

function get_calle($domicilio) {
    $fin_pos = strpos($domicilio, '#');
    $dom = "";

    if ($fin_pos !== FALSE) {
        $dom = substr($domicilio, 0, $fin_pos - 1);
    }

    return trim($dom);
}

function get_grupo_sangre($sangre) {
    $grupo = "";

    $fin_pos = strpos($sangre, ' ');

    if ($fin_pos !== FALSE) {
        $grupo = substr($sangre, 0, $fin_pos);
    }

    return trim($grupo);
}

function get_rh($sangre) {
    $rh = "";

    $start_pos = strpos($sangre, ' ');

    if ($start_pos !== FALSE) {
        $rh = substr($sangre, $start_pos + 1);
    }

    if (strcmp(strtoupper($rh), "POSITIVO") == 0) {
        $rh = "+";
    } else {
        $rh = "-";
    }

    return trim($rh);
}
