<?php

include "../database.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST["feedbackID"])) {
        $feedbackID = $_POST["feedbackID"];

        $sql = "DELETE FROM feedback WHERE feedback_id = $feedbackID";
        $result = $var_conn->query($sql);

        if ($result) {
            http_response_code(200);
            echo "Your feedback has been deleted successfully!";
        } else {
            http_response_code(500);
            echo "Something went wrong while deleting your feedback, please try again.";
        }
    } else {
        http_response_code(400);
        echo "Missing variable, please try again.";
    }
} else {
    header("location ..");
}
