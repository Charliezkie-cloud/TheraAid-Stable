<?php

include "./database.php";

session_start();

$var_APID = $_SESSION["sess_ApntmntId"];
$var_profid = $_SESSION["sess_PID"];
$var_qry = "SELECT u.User_id,
                u.Fname,
                u.Lname,
                u.Mname,
                u.Bday,
                u.ContactNum,
                u.Email,
                u.profilePic,
                p.P_case,
                p.case_desc,
                p.City,
                p.barangay,
                p.assement_photo,
                p.mid_hisotry_photo,
                appointment.*
            FROM tbl_user u JOIN tbl_patient p ON u.User_id = p.user_id
            JOIN tbl_appointment appointment ON appointment.patient_id = p.patient_id
            WHERE p.patient_id  = $var_profid";
$var_chk = mysqli_query($var_conn, $var_qry);
$var_Fname = "";
$var_Lname = "";
$var_Mname = "";
$var_MI = "";
$var_Age = "";
$var_CntctNum = "";
$var_Email = "";
$var_Case = "";
$var_CaseDesc = "";
$var_City = "";
$var_Stret = "";
$var_Medpic = "";
$var_Assesment = "";
$var_UID = "";
$profilePic = "";

if (mysqli_num_rows($var_chk) > 0) {
    $var_get = mysqli_fetch_array($var_chk);
    $var_Case = $var_get["P_case"];
    $var_CaseDesc = $var_get["case_desc"];
    $var_City = $var_get["City"];
    $var_Medpic = $var_get["mid_hisotry_photo"];
    $var_Assesment = $var_get["assement_photo"];
    $var_Street = $var_get["barangay"];
    $var_Fname = $var_get["Fname"];
    $var_Lname = $var_get["Lname"];
    $var_Mname = $var_get["Mname"];
    $var_Date = $var_get["Bday"];
    $var_CntctNum = $var_get["ContactNum"];
    $var_Email = $var_get["Email"];
    $var_year = date("Y");
    $var_MI = substr($var_Mname, 0, 1);
    $var_byear = substr($var_Date, 0, 4);
    $var_UID = $var_get["User_id"];
    $var_Age = $var_year - $var_byear;
    $profilePic = $var_get["profilePic"];
    $status = $var_get["status"];
} else {
    echo "No records found";
}

$var_Rate = "";

if (isset($_POST["BTNsubmit"])) {
    $var_Rate = intval($_POST["TxtRate"]);
    $var_status = "Responded";
    $var_update = "UPDATE tbl_appointment SET rate=$var_Rate, status='$var_status' WHERE appointment_id=" . $_SESSION["sess_ApntmntId"];
    $var_upqry = mysqli_query($var_conn, $var_update);

    if ($var_upqry) {
        $var_type = "Therapists Have Responded to your request";
        $var_notif = "INSERT INTO tbl_notifications(user_id,appointment_id,type) VALUES($var_UID,$var_APID,'$var_type')";
        
        mysqli_query($var_conn, $var_notif);
        header("location: TherapistsAppointment.php");
    } else {
        echo "error";
    }
}

if (isset($_POST["BTNDecline"])) {
    $var_status = "declined";

    $var_update = "UPDATE tbl_appointment SET
                         status='$var_status' 
                         WHERE appointment_id=".$_SESSION["sess_ApntmntId"];
    $var_upqry = mysqli_query($var_conn, $var_update);

    if ($var_upqry) {
        $var_type="Therapists Have Declined to your request";
        $var_notif ="INSERT INTO tbl_notifications(user_id,appointment_id,type)
        VALUES($var_UID,$var_APID,'$var_type')";
        mysqli_query($var_conn,$var_notif);
        
        $sql = "SELECT schedle_id FROM tbl_appointment WHERE appointment_id = " . $_SESSION["sess_ApntmntId"];
        $result = $var_conn->query($sql)->fetch_assoc();

        $schedle_id = $result["schedle_id"];

        $sql = "UPDATE tbl_sched SET status = 'Available' WHERE shed_id = $schedle_id";
        $var_conn->query($sql);

        header("location: TherapistsAppointment.php");
    } else {
        echo "Error: Could not decline the appointment.";
    }
}

?>
<!DOCTYPE html>
<html data-bs-theme="light">
<head>
    <meta charset='utf-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <title>TheraAid | Therapist Patient Appointment</title>
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
                                <a class="nav-link fw-semibold text-center" aria-current="page" href="<?php echo $_SERVER["HTTP_REFERER"] ?>">
                                    <i class="bi bi-box-arrow-right fs-3"></i><br>
                                    <small>Go Back</small>
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

            <div class="container">
                <div class="row">

                    <div class="col-lg-4">
                        <h3 class="mb-3 d-block d-lg-none text-center">Patient Information</h3>

                        <div class="mb-3 d-flex justify-content-center align-items-center">
                            <img class="img-fluid rounded-5 shadow" style="height: 250px; width: auto; object-fit: cover;" src="./UserFiles/ProfilePictures/<?php echo $profilePic ?>" alt="<?php echo $profilePic ?>">
                        </div>

                        <div class="mb-3">
                            <label class="mb-1">
                                <b>Full Name:</b>
                                <span><?php echo $var_Fname . " " . $var_MI . ". " . $var_Lname; ?></span>
                            </label><br>
                            <label class="mb-1">
                                <b>Age:</b>
                                <span><?php echo $var_Age; ?></span>
                            </label><br>
                            <label class="mb-1">
                                <b>Contact Number:</b>
                                <span><?php echo $var_CntctNum; ?></span>
                            </label><br>
                            <label>
                                <b>Email:</b>
                                <span><?php echo $var_Email; ?></span>
                            </label>
                        </div>

                        <hr>

                        <?php if ($status === "Pending"): ?>
                            <form id="declineForm" method="post" action="./PTPatientview.php" hidden></form>

                            <div class="d-flex justify-content-center justify-content-lg-start align-items-center mt-3 flex-row gap-1 p-3 bg-secondary bg-opacity-50 shadow rounded">
                                <button type="submit" class="btn btn-secondary px-5 px-lg-3 rounded-5" name="BTNDecline" form="declineForm">Cancel</button>
                                <button type="button" class="btn btn-primary px-5 px-lg-3 rounded-5 fw-semibold" data-bs-toggle="modal" data-bs-target="#mainModal">Accept</button>
                            </div>
                        <?php endif; ?>

                    </div>

                    <div class="col-lg">
                        <h3 class="mb-3 d-none d-lg-block">Patient Information</h3>
                        <hr>

                        <div class="mb-3">
                            <label class="mb-1">
                                <b>Case:</b>
                                <span><?php echo $var_Case; ?></span>
                            </label><br>
                            <label class="mb-1">
                                <b>Description:</b>
                                <span><?php echo $var_CaseDesc; ?></span>
                            </label><br>
                            <label class="mb-1">
                                <b>City:</b>
                                <span><?php echo $var_City; ?></span>
                            </label><br>
                            <label class="mb-1">
                                <b>Barangay:</b>
                                <span><?php echo $var_Street; ?></span>
                            </label>
                        </div>
                        <hr>

                        <div class="row gap-3">
                            <div class="col-md">
                                <label class="mb-3"><b>Medical History</b></label>
                                <div class="d-flex justify-content-start align-items-start flex-column gap-2">
                                    <?php

                                    foreach (explode(",", $var_Medpic) as $filename) {
                                        echo "<img class='img-fluid rounded-5 shadow' style='height: auto; width: 100%; cursor: pointer;' src='./UserFiles/PatientMedicalHistoryPictures/$filename' alt='$filename'>";
                                    }

                                    ?>
                                </div>
                            </div>
                            <div class="col-md">
                                <label class="mb-3"><b>Medical Assesment</b></label>
                                <div class="d-flex justify-content-start align-items-start flex-column gap-2">
                                    <?php

                                    foreach (explode(",", $var_Assesment) as $filename) {
                                        echo "<img class='img-fluid rounded-5 shadow cursor' style='height: auto; width: 100%; cursor: pointer;' src='./UserFiles/PatientAssementPictures/$filename' alt='$filename'>";
                                    }

                                    ?>
                                </div>
                            </div>
                        </div>

                    </div>

                </div>
            </div>

        </section>

    </main>

    <form method="POST" action="PTPatientview.php" class="needs-validation" novalidate>
        <div class="modal fade" id="mainModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5">Respond Patient</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <?php

                        $var_appntmnt = "SELECT * FROM tbl_appointment WHERE appointment_id=" . $_SESSION["sess_ApntmntId"];
                        $var_Aqry = mysqli_query($var_conn, $var_appntmnt);
                        $var_get = mysqli_fetch_array($var_Aqry);
                        $var_condition = "";

                        if ($var_get["is_aggreed"] == 1) {
                            $var_condition = "Agreed";
                        } else {
                            $var_condition = "Disagreed";
                        }

                        ?>

                        <div class="mb-1">
                            <label class="mb-1">
                                <b>Number of Session:</b>
                                <span><?php echo $var_get["num_of_session"]; ?></span>
                            </label><br>
                            <label class="mb-1">
                                <b>Payment Type:</b>
                                <span><?php echo $var_get["payment_type"]; ?></span>
                            </label><br>
                            <label class="mb-1">
                                <b>Start Date:</b>
                                <span><?php echo $var_get["start_date"]; ?></span>
                            </label><br>
                            <label class="mb-1">
                                <b>Date Requested:</b>
                                <span><?php echo $var_get["Date_creadted"]; ?></span>
                            </label><br>
                            <label>
                                <b>Condition:</b>
                                <span><?php echo $var_condition; ?></span>
                            </label>
                        </div>

                        <hr>

                        <label for="TxtRate" class="mb-1 fw-bold">Rate:</label>
                        <input type="text" name="TxtRate" id="TxtRate" class="form-control" required>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary px-5 rounded-5" data-bs-dismiss="modal">Close</button>
                        <button type="submit" name="BTNsubmit" class="btn btn-primary px-5 rounded-5">Submit</button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <script src="./node_modules/bootstrap/dist/js/bootstrap.bundle.js"></script>
    <script>
        (() => {

            Array.from(document.querySelectorAll("img")).forEach((i) => {
                i.addEventListener("click", () => {
                    window.open(i.src);
                });
            });

            Array.from(document.querySelectorAll(".needs-validation")).forEach((i) => {
                i.addEventListener("submit", (e) => {
                    if (!i.checkValidity()) {
                        e.preventDefault();
                        e.stopPropagation();
                    }

                    i.classList.add("was-validated");
                });
            });

        })();
    </script>
</body>
</html>