<?php

include "../database.php";

if (isset($_POST["userID"])) {
    $userID = $_POST["userID"];

    $sql = "UPDATE tbl_therapists 
            SET certificate = NULL
            WHERE user_id = $userID";
    $result = mysqli_query($var_conn, $sql);

    echo "Your certificate has been deleted!";
}