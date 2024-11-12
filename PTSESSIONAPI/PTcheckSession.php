<?php

include "../database.php";

session_start();

$JSONDATA = file_get_contents(filename: "php://input");

$DcodeJSON = json_decode($JSONDATA, true);
date_default_timezone_set('Asia/Manila');
$var_crrntTime = date("h:i:sa");
$var_currntDate = date("Y-m-d");
$var_currntDate = "2024-11-15";
$var_status = "ongoing";

if (isset($DcodeJSON["appId"])) {
    $appointment_id = $DcodeJSON["appId"];
    
    $sql = "SELECT * 
            FROM tbl_reminder
            WHERE appointment_id = $appointment_id";
    $results = $var_conn->query($sql);

    if ($results->num_rows < 0) {
        http_response_code(500);
        echo "No item found";
        exit;
    }

    foreach ($results as $result) {
        $reminder_date = explode(",", $result["reminder_date"]);

        if (!(in_array($var_currntDate, $reminder_date))) {
            http_response_code(400);
            echo "You don't have a session today";
            exit;
        }

        $sql = "SELECT 
                	*,
                	DATE_FORMAT(Date_creadted, '%Y-%m-%d') AS formatted_date_created
                FROM tbl_session
                WHERE appointment_id = $appointment_id";
        $results = $var_conn->query($sql);

        if ($results->num_rows < 0) {
            http_response_code(500);
            echo "No item found";
            exit;
        }

        foreach ($results as $result) {
            $formatted_date_created = $result["formatted_date_created"];

            if ($formatted_date_created == $var_currntDate) {
                http_response_code(400);
                echo "You have already started a session";
                exit;
            }
        }
    }

    echo "New session have been added";
}
