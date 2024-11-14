<?php

include "../database.php";

if (isset($_GET["userID"])) {
    $userID = $_GET["userID"];

    $sql = "SELECT * FROM tbl_therapists WHERE user_id = $userID";
    $result = $var_conn->query($sql)->fetch_assoc();

    $contract = $result["contract"];

    if ($contract) {
        echo $contract;
    } else {
        echo 0;
    }
}