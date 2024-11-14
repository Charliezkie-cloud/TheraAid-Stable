<?php

include "./database.php";

session_start();

if (isset($_POST["PatientProf"])) {
    $_SESSION["sess_PID"] = $_POST["PatientProf"];
    $_SESSION["sess_ApntmntId"] = $_POST["appointment_id"];
    header("location: PTPatientview.php");
}
$var_filter = "P"; // Default filter is Pending

// Handle form submission for filtering
if (isset($_POST["BtnFilter"])) {
    $var_filter = $_POST["response"];
}

if (isset($_POST["cancelAppointment"])) {
    $id = $_POST["appointment_id"];

    mysqli_query($var_conn, "UPDATE `tbl_appointment` SET `status`='declined' WHERE `appointment_id` = '$id';");
}

// SQL query based on the selected filter
$var_sclt = "SELECT * FROM tbl_appointment
    WHERE therapists_id = " . $_SESSION["sess_PTID"] . " 
    AND status LIKE '" . $var_filter . "%'";

$var_qry = mysqli_query($var_conn, $var_sclt);
?>

<!DOCTYPE html>
<html data-bs-theme="light">

<head>
    <meta charset='utf-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <title>TheraAid | Therapist Appointment</title>
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
                                <a class="nav-link fw-semibold text-center active" aria-current="page" href="./TherapistsAppointment.php">
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
            <div class="overflow-x-auto">
                <form method="POST" action="TherapistsAppointment.php" class="mb-3">
                    <div class="d-flex justify-content-start align-items-center gap-3 mb-2 flex-wrap">

                        <div class="form-check">
                            <input class="form-check-input" type="radio" value="P" name="response" id="pending" <?php if ($var_filter == "P")
                                echo "checked"; ?>>
                            <label class="form-check-label" for="pending">
                                Pending
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" value="R" name="response" id="responded" <?php if ($var_filter == "R")
                                echo "checked"; ?>>
                            <label class="form-check-label" for="responded">
                                Responded
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" value="O" name="response" id="ongoing" <?php if ($var_filter == "O")
                                echo "checked"; ?>>
                            <label class="form-check-label" for="ongoing">
                                Ongoing
                            </label>
                        </div>
                        <input type="submit" name="BtnFilter" value="Filter"
                            class="btn btn-primary px-5 rounded-5 btn-sm">
                    </div>
                </form>

                <table class="table table-primary table-striped rounded-5">
                    <tr>
                        <th>Number Of Session</th>
                        <th>Payment Type</th>
                        <th>Start Date</th>
                        <th>Status</th>
                        <th>Date Created</th>
                        <th>Rate</th>
                        <th>Patient Profile</th>
                        <?php if ($var_filter == "R"): ?>
                            <th>Action</th>
                        <?php endif; ?>
                    </tr>

                    <?php if ($var_qry && mysqli_num_rows($var_qry) > 0): ?>
                        <?php while ($var_rec = mysqli_fetch_array($var_qry)): ?>
                            <tr>
                                <td><?php echo $var_rec["num_of_session"]; ?></td>
                                <td><?php echo $var_rec["payment_type"]; ?></td>
                                <td><?php echo $var_rec["start_date"]; ?></td>
                                <td><?php echo $var_rec["status"]; ?></td>
                                <td><?php echo $var_rec["Date_creadted"]; ?></td>
                                <td><?php echo $var_rec["rate"]; ?></td>
                                <?php if ($var_filter == "R"): ?>
                                    <td>
                                        <form method="POST" action="TherapistsAppointment.php">
                                            <input type="hidden" name="appointment_id" value="<?php echo $var_rec['appointment_id']; ?>">
                                            <button type="submit" name="PatientProf" class="btn btn-primary px-5 rounded-5 w-100 btn-sm" value="<?php echo $var_rec['patient_id']; ?>">View</button>
                                        </form>
                                    </td>
                                    <td>
                                        <form method="POST" action="TherapistsAppointment.php">
                                            <input type="hidden" name="appointment_id"
                                                value="<?php echo $var_rec['appointment_id']; ?>">
                                            <button type="submit" name="cancelAppointment"
                                                class="btn btn-primary px-5 rounded-5 w-100 btn-sm"
                                                value="<?php echo $_SESSION["sess_PTID"]; ?>">Cancel</button>
                                        </form>
                                    </td>
                                <?php endif; ?>
                                <?php if (($var_filter === "P") || $var_filter === "O"): ?>
                                    <td>
                                        <form method="POST" action="TherapistsAppointment.php">
                                            <input type="hidden" name="appointment_id" value="<?php echo $var_rec['appointment_id']; ?>">
                                            <button type="submit" name="PatientProf" class="btn btn-primary px-5 rounded-5 w-100 btn-sm" value="<?php echo $var_rec['patient_id']; ?>">View</button>
                                        </form>
                                    </td>
                                <?php endif; ?>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="100">No records found.</td>
                        </tr>
                    <?php endif; ?>
                </table>
            </div>
        </section>

    </main>

    <script src="./node_modules/bootstrap/dist/js/bootstrap.bundle.js"></script>
</body>

</html>