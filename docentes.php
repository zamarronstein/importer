<?php

require_once "importer.php";
require_once "db_connection.php";
require_once "utils.php";

$a_data = get_data("files/teachers.xlsx");

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

        $sql = "INSERT IGNORE INTO persona (id_persona, tipo_persona, nombre, a_paterno, a_materno, email_principal, nacionalidad, activo) VALUES ";
        $sql .= "(0, 'Docente', '{$row[1]}', '{$row[2]}', '{$row[3]}', '{$row[4]}', 'Mexicana', '1');";

        if ($conn->query($sql) === true) {

            $id_persona = $conn->insert_id;

            $sql = "INSERT IGNORE INTO telefono (id_telefono, persona_id_persona, numero, tipo, activo) VALUES ";
            $sql.= "(0, {$id_persona}, '{$row[5]}', 'Celular', '1');";

            if ($conn->query($sql) === TRUE) {

                $sql = "INSERT IGNORE INTO docente (id_docente, persona_id_persona) VALUES ";
                $sql.= "(0, {$id_persona})";

                if ($conn->query($sql) == TRUE) {

                }
            }

        } else {
            echo "<br>Something was wrong: " . $conn->error;
        }
    }

    echo "\nData importation finished!";
    $conn->close();
}