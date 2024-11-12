<?php

include "./database.php";

session_start();

$var_UserId = $_SESSION["sess_id"];

// $var_crrntDate = date("Y-m-d");
$var_crrntDate = "2024-11-13";
$var_validate = "";

$var_Rmndr = "SELECT RM.reminder_date,
                    RM.reminder_messsage,
                    RM.reminder_status,
                    AP.num_of_session,
                    AP.payment_type,
                    AP.start_date,
                    AP.start_date,
                    AP.appointment_id,
                    SC.start_time,
                    SC.day,
                    SC.end_time,
                    SC.note,
                    PT.validate,
                    CONCAT(U.Fname,' ',U.Mname,' ',U.Lname) AS Pat_fllname,
                    U.profilePic,
                    P.patient_id,
                    PT.case_handled
                    FROM tbl_reminder RM 
					JOIN tbl_appointment AP ON AP.appointment_id = RM.appointment_id
					JOIN tbl_patient P ON AP.patient_id = P.patient_id
					JOIN tbl_therapists PT ON AP.therapists_id = PT.therapist_id
					JOIN tbl_user U ON U.User_id = P.user_id
					JOIN tbl_sched SC ON AP.schedle_id = SC.shed_id
					WHERE  PT.user_id = $var_UserId";
$var_rmndrqry = mysqli_query($var_conn, $var_Rmndr);
$var_data = "";
// $var_validate = $var_rmndrqry->fetch_assoc()["validate"];
// $_SESSION["sess_PATID"] = "";

if (isset($_POST["BtnSession"])) {
    $_SESSION["appointment_id"] = $_POST["BtnSession"];

    header("location: TherapistCreateSession.php");
}

?>
<!DOCTYPE html>
<html data-bs-theme="light">
<head>
    <meta charset='utf-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <title>TheraAid | Therapist Reminder</title>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <link rel='stylesheet' type='text/css' media='screen' href='./assets/css/TherapistHomePage.css'>
</head>
<body>
    <header>
        <nav class="navbar navbar-expand-lg bg-primary-subtle">
            <div class="container">
                <a class="navbar-brand" href="#">
                    <img src="./assets/img/Logo.jpg" class="rounded-pill shadow" alt="Logo.jpg" width="64" height="64">
                </a>
                <button class="navbar-toggler rounded-pill shadow" type="button" data-bs-toggle="offcanvas"
                    data-bs-target="#offcanvasNavbar">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="offcanvas offcanvas-start bg-primary-subtle" tabindex="-1" id="offcanvasNavbar"
                    aria-labelledby="offcanvasNavbarLabel">

                    <div class="offcanvas-header">
                        <h5 class="offcanvas-title" id="offcanvasNavbarLabel">
                            <img src="./assets/img/Logo.jpg" class="rounded-pill shadow" alt="Logo.jpg" width="64"
                                height="64">
                        </h5>
                        <button type="button" class="btn-close shadow" data-bs-dismiss="offcanvas"
                            aria-label="Close"></button>
                    </div>

                    <div class="offcanvas-body">
                        <ul class="navbar-nav justify-content-end flex-grow-1 pe-0 gap-0 gap-lg-4">
                            <li class="nav-item">
                                <a class="nav-link fw-semibold text-center" aria-current="page" href="./TherapistsHomePage.php">
                                    <i class="bi bi-house fs-3"></i><br>
                                    <small>Home</small>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link fw-semibold text-center" aria-current="page" href="./TherapistFeedback.php">
                                    <i class="bi bi-chat-left-text fs-3"></i><br>
                                    <small>Feedbacks</small>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link fw-semibold text-center" aria-current="page" href="./PTSession.php">
                                    <i class="bi bi-hospital fs-3"></i><br>
                                    <small>Session</small>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link fw-semibold text-center" aria-current="page" href="./TherapistsAppointment.php">
                                    <i class="bi bi-calendar-check fs-3"></i><br>
                                    <small>Appointment</small>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link fw-semibold text-center" aria-current="page" href="./TherapistsHistory.php">
                                    <i class="bi bi-clock-history fs-3"></i><br>
                                    <small>History</small>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link fw-semibold text-center" aria-current="page" href="./PTReports.php">
                                    <i class="bi bi-file-earmark-text fs-3"></i><br>
                                    <small>Reports</small>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link fw-semibold text-center active" aria-current="page" href="./TherapistsReminder.php">
                                    <i class="bi bi-card-checklist fs-3"></i><br>
                                    <small>Reminder</small>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link fw-semibold text-center" aria-current="page" href="./TherapistsNotification.php">
                                    <i class="bi bi-bell fs-3"></i><br>
                                    <small>Notification</small>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link fw-semibold text-center" aria-current="page" href="./TherapistChat.php">
                                    <i class="bi bi-chat-dots fs-3 chat-badge"></i><br>
                                    <small>Chat</small>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link fw-semibold text-center" aria-current="page" href="./TherapistsProfilePage.php">
                                    <i class="bi bi-person fs-3"></i><br>
                                    <small>Profile</small>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link fw-semibold text-center" aria-current="page" href="./Logout.php">
                                    <i class="bi bi-box-arrow-right fs-3"></i><br>
                                    <small>Logout</small>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </nav>
    </header>

    <main class="py-0 py-sm-3">

        <section class="main-section bg-secondary-subtle py-3 py-sm-5 px-3 px-sm-5 shadow container">

            <h1 class="text-center mb-3">Appointment for Today</h1>

            <hr>

            <form method="POST" action="TherapistsReminder.php">
                
                <div class="d-flex justify-content-center align-items-center flex-column gap-3">

                    <?php

                    if (mysqli_num_rows($var_rmndrqry) > 0) {
                        while ($var_Rget = mysqli_fetch_array($var_rmndrqry)) {
                            $var_listDate = explode(",", $var_Rget["reminder_date"]);
                            
                            if (in_array($var_crrntDate, $var_listDate)) {
                                $var_PatientID = $var_Rget["patient_id"];
                                $appointmentID = $var_Rget["appointment_id"];
                                $profilePic = $var_Rget["profilePic"];
                                $fullName = $var_Rget["Pat_fllname"];
                                $day = $var_Rget["day"];
                                $startTime = $var_Rget["start_time"];
                                $endTime = $var_Rget["end_time"];

                                echo "<div class='row w-100 bg-bg-secondary-subtle p-3 rounded-5 shadow border-5 gap-3 gap-sm-0'>
                                    <b class='mb-3'>Information:</b>
                                    <div class='col-sm d-flex justify-content-center align-items-center flex-column gap-3'>
                                        <img src='./UserFiles/ProfilePictures/$profilePic' alt='$profilePic' class='img-fluid rounded-5 shadow' style='object-fit: cover; height: 250px;'>
                                        <label class='text-center'><b>Full Name: </b>$fullName</label>
                                    </div>

                                    <div class='col-sm'>
                                        <label class='mb-1'><b>Day: </b>$day</label><br>
                                        <label class='mb-1'><b>Start Time: </b>$startTime</label><br>
                                        <label class='mb-1'><b>End Time: </b>$endTime</label><br>
                                        <hr>
                                        <button name='BtnSession' value='$appointmentID' class='btn btn-primary px-5 rounded-5 shadow'>View Appointment</button>
                                    </div>
                                </div>";
                            }
                        }
                    } else {
                        $var_validate = 0;
                    }
                    
                    ?>

                </div>
            </form>

        </section>

    </main>

    <script src="./node_modules/bootstrap/dist/js/bootstrap.bundle.js"></script>
    <script> 
        var validate = <?php echo $var_validate;?>;
        if(validate == 0){
            alert("You are not validated");
        }
    </script>
</body>

</html>
