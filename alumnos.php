<?php

require_once "importer.php";
require_once "db_connection.php";

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

$a_data = get_data("files/students.xlsx");

if (!empty($a_data)) {

    $conn = DataBase::get_connection();
    mysqli_set_charset($conn, "utf8");

    echo "Starting importing data!";

    foreach ($a_data as $row) {
        // row[2] -> nombre del alumno
        // row[3] -> primer apellido
        // row[4] -> segundo apellido

        if (ord(substr($row[2], 0, 1)) == 0) {
            continue;
        }

        $grupo_sangre = "NULL";
        $sangre_rh = "NULL";

        if (trim($row[13])) {
            $grupo_sangre = get_grupo_sangre($row[13]);
            $sangre_rh = get_rh($row[13]);

            $grupo_sangre = "'{$grupo_sangre}'";
            $sangre_rh = "'{$sangre_rh}'";
        }

        $sql = "INSERT IGNORE INTO persona (id_persona, tipo_persona, nombre, a_paterno, a_materno, nacionalidad, sangre_grupo, sangre_rh, activo) VALUES ";
        $sql .= "(0, 'Alumno', '{$row[2]}', '{$row[3]}', '{$row[4]}', 'Mexicana', {$grupo_sangre}, {$sangre_rh}, '1');";

        if ($conn->query($sql) === true) {

            $id_persona = $conn->insert_id;
            $sql = "INSERT IGNORE INTO alumno (id_alumno, persona_id_persona, carrera_id_carrera, matricula, estatus, lengua_indigena, curp, becado, contacto_accidente, lugar_bachillerato) VALUES ";
            $sql .= "(0, {$id_persona}, 1, '', 'Inscrito', '0', '', '0', '{$row[6]} - {$row[7]}', 'pais');";

            if ($conn->query($sql) == true) {
                $sql = "INSERT IGNORE INTO telefono (id_telefono, persona_id_persona, numero, tipo, activo) VALUES";
                $sql.= "(0, {$id_persona}, '{$row[8]}', 'local', '1');";

                if ($conn->query($sql) === true) {

                    $calle = trim($row[9]);

                    if ($calle != "") {

                        $n_exterior = get_numero_domicilio($row[9]);
                        $calle = get_calle($row[9]);

                        $sql = "INSERT IGNORE INTO direccion (id_direccion, persona_id_persona, instituto_id_instituto, pais_id_pais, estado_id_estado, municipio_id_municipio, colonia, calle, n_exterior, activo) VALUES ";
                        $sql.= "(0, {$id_persona}, 1, 1, 32, 1, '{$row[10]}', '{$calle}', '{$n_exterior}', '1');";

                        if ($conn->query($sql) === true) {

                        }
                    }

                }
            }

        } else {
            echo "<br>Something was wrong: " . $conn->error;
        }
    }

    echo "\nData importation finished!";
    $conn->close();
}