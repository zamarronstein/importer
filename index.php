<?php

require_once("simplexlsx/simplexlsx.class.php");

$rows_to_omit = 2;

function is_valid_row($r) {
    $is_valid = FALSE;

    $name = trim(strtoupper($r[2]));

    $position = stripos($name, "grupo");

    if ($position === FALSE) return TRUE;

    return $is_valid;
}

function clean_name($name) {

    $name = trim($name);

    if (count($name) > 0) {
        $s_numeric = substr($name, 0, 1);
        if (is_numeric($s_numeric)) {
            $space_pos = strpos($name, " ");
            $name = substr($name, $space_pos + 1);
            /*
            $car = substr($name, 0, 1);
            echo ord($car) . "<br>";
            */
            $name = str_replace("" . chr(194), "", $name);
            $name = str_replace("" . chr(160), "", $name);

        }
    }

    return trim($name);
}


if ($xls = SimpleXLSX::parse("files/students.xlsx")) {

    echo "<table>";
    $ommited_rows = 0;
    foreach($xls->rows() as $r) {
        if (($ommited_rows > $rows_to_omit) && is_valid_row($r)) {
            $r[2] = clean_name($r[2]); //r[2] is the first name
            echo '<tr><td>'.implode('</td><td>', $r ).'</td></tr>';        
        }
        $ommited_rows++;
    }
    echo "</table>";
} else {
    echo SimpleXLSX::parse_error();
}

