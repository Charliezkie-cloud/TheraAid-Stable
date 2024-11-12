<?php

include "./database.php";

session_start();

$var_therapistsId = $_SESSION["sess_PTID"];
// echo $var_therapistsId;
date_default_timezone_set('Asia/Manila');

$var_crrntTime = date("h:i:sa");
$var_currntDate = date("Y-m-d");
$var_currntDate = "2024-11-13";

$var_filter = "";
$var_days = array();
$var_sessionList = "SELECT *, 
						CONCAT(U.Fname,' ',U.Mname,' ',U.Lname)AS 'FULLNAME',
                        GROUP_CONCAT(DISTINCT AP.status)
                        FROM tbl_session SS JOIN tbl_appointment AP ON AP.appointment_id = SS.appointment_id
                        JOIN tbl_therapists PT ON PT.therapist_id = AP.therapists_id 
                        JOIN tbl_patient PAT ON AP.patient_id =  PAT.patient_id 
                        JOIN tbl_user U ON PAT.user_id = U.User_id 
                        JOIN tbl_sched SC ON SC.shed_id = AP.schedle_id
                        WHERE PT.therapist_id = $var_therapistsId
                        AND AP.status = 'On-Going'";
$var_Slist = mysqli_query($var_conn, $var_sessionList);

if (isset($_POST["BtnFilter"]) && isset($_POST["RadDay"])) {
    $var_filter = $_POST["RadDay"];
}

?>
<!DOCTYPE html>
<html data-bs-theme="light">

<head>
    <meta charset='utf-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <title>TheraAid | Therapist Home Page</title>
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
                                <a class="nav-link fw-semibold text-center active" aria-current="page" href="./PTSession.php">
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
                                <a class="nav-link fw-semibold text-center" aria-current="page" href="./TherapistsReminder.php">
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

                <div class="modal fade" id="Session" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5">Session</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <button type="button" id="StartSession" class="btn btn-primary">Start Session</button>

                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>

                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal fade" id="Editsess" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5">Session</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <label>Note:</label><br>
                                <textarea style="height:100px; width: 100%;" name="TxtDuration"></textarea>
                                <input type="file" name="SessPhotos" multiple>
                            </div>
                            <div class="modal-footer">
                                <button type="button" id="StartSession" class="btn btn-primary">Save</button>
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>

                            </div>
                        </div>
                    </div>
                </div>
                <form method="POST" action="PTsession.php">
                <div class="d-flex justify-content-start align-items-center flex-row gap-2 mb-3">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="RadDay" id="All">
                        <label class="form-check-label" for="All">
                            All
                        </label>
                    </div>

                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="RadDay" id="Mon">
                        <label class="form-check-label" for="Mon">
                            Monday
                        </label>
                    </div>

                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="RadDay" id="Tue">
                        <label class="form-check-label" for="Tue">
                            Tuesday
                        </label>
                    </div>

                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="RadDay" id="Wed">
                        <label class="form-check-label" for="Wed">
                            Wednesday
                        </label>
                    </div>

                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="RadDay" id="Thu">
                        <label class="form-check-label" for="Thu">
                            Thursday
                        </label>
                    </div>

                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="RadDay" id="Fri">
                        <label class="form-check-label" for="Fri">
                            Friday
                        </label>
                    </div>

                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="RadDay" id="Sat">
                        <label class="form-check-label" for="Sat">
                            Saturday
                        </label>
                    </div>

                    <button type="submit" name="BtnFilter" class="btn btn-primary px-4 rounded-5 shadow btn-sm">Filter</button>
                </div>
                </form>

                <div>
                    <h3 class="text-center">Sessions</h3>
                    <table class="table table-striped shadow">
                    <?php
                        while ($var_SSRec = mysqli_fetch_array($var_Slist)) {
                            $profilePic = $var_SSRec["profilePic"];
                            $fullname = $var_SSRec["FULLNAME"];
                            $P_case = $var_SSRec["P_case"];
                            $day = $var_SSRec["day"];
                            $Date_creadted = $var_SSRec["Date_creadted"];
                            $appointment_id = $var_SSRec["appointment_id"];
                        
                            echo "
                                <tr>
                                    <td>
                                        <div class='row'>
                                            <div class='col-3 p-4'>
                                                <div class='col-sm d-flex justify-content-center align-items-center flex-column gap-3'>
                                                    <img src='./UserFiles/ProfilePictures/$profilePic' alt='$profilePic' class='rounded-pill shadow' style='object-fit: cover; object-fit-position: top; height: 250px; width: 250px;'>
                                                </div>
                                            </div>
                                            <div class='col border-start d-flex justify-content-center align-items-start flex-column gap-2'>
                                                <label><b>Patient Full Name: </b>$fullname</label>
                                                <label><b>Patient Case: </b>$P_case</label>
                                                <label><b>Days: </b>$day</label>
                                                <label><b>Date: </b>$Date_creadted</label>
                                                <a class='btn btn-outline-primary btn-sm px-5 rounded-5 shadow' href='./TherapistCreateSession.php?record=$appointment_id'>Check Session</a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            ";
                        }
                    ?>
                    </table>
                </div>

            </form>

        </section>

    </main>

    <script src="./node_modules/bootstrap/dist/js/bootstrap.bundle.js"></script>
</body>

</html>