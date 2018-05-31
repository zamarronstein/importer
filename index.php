<?php

require_once "importer.php";
require_once "db_connection.php";

function is_alpha($input) {
    
    return preg_match('/^[a-z\s]*$/i', $input);
}

$a_data = get_data("files/students.xlsx");

if (!empty($a_data)) {

    $conn = get_connection();
    mysqli_set_charset($conn,"utf8");
    foreach ($a_data as $row) {
        // row[2] -> nombre del alumno
        // row[3] -> primer apellido
        // row[4] -> segundo apellido

        if (ord(substr($row[2], 0, 1)) == 0) continue;

        $sql = "INSERT IGNORE INTO persona (id_persona, tipo_persona, nombre, a_paterno, a_materno, nacionalidad, activo) VALUES ";
        $sql .= "(0, 'Alumno', '{$row[2]}', '{$row[3]}', '{$row[4]}', 'Mexicana', '1');";

        if ($conn->query($sql) === true) {
            //echo "Data imported correctly!!<br>";
        } else {
            echo "<br>Something was wrong: " . $conn->error;
        }
    }

    $conn->close();
}