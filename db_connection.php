<?php

$connection = NULL;

function get_connection()
{

    if ($connection == NULL) {

        $connection = new mysqli("172.17.0.2", "root", "1q2w3e", "d_izeu");
    }

    if ($connection->connect_error) {
        echo $connection->connect_error;
        exit();
    }

    return $connection;
}
