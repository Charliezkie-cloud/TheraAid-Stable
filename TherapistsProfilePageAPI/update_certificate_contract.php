<?php

include "../database.php";

session_start();

$userID = $_SESSION["sess_id"];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (
        isset($_FILES["certificate"]) &&
        isset($_POST["contract"])
    ) {
        $certificate = $_FILES["certificate"];
        $contract = $_POST["contract"];

        $certificatesDir = "../UserFiles/Certificates";

        $certificateTmpName = $certificate["tmp_name"];

        $newCertificateName = uniqid() . ".pdf";

        if (move_uploaded_file($certificateTmpName, "$certificatesDir/$newCertificateName")) {
            $sql = "UPDATE tbl_therapists
                    SET
                    	certificate = '$newCertificateName',
                    	contract = '$contract'
                    WHERE user_id = $userID";
            $result = $var_conn->query($sql);

            if ($result) {
                http_response_code(200);
                echo "Your certificate and contract has been updated!";
            } else {
                http_response_code(500);
                echo "Something went wrong while uploading your updates, please try again.";
            }
        } else {
            http_response_code(500);
            echo "Something went wrong while uploading your files, please try again.";
        }
    } else {
        http_response_code(400);
        echo "Missing variable, please try again.";
    }
} else {
    header("location ..");
}