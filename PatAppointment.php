<?php

include "./database.php";

session_start();

$var_SchedID = $_SESSION["sess_SchedID"];

$var_PTID = "";
$var_PtntID = "";

$var_Session = "";
$var_Ptype = "";
$var_Date = "";
$var_radBtn = "";

$var_day = "";

$isSuccess = false;

$var_Apdata = "SELECT * FROM tbl_sched WHERE shed_id=" . $var_SchedID;
$var_schedqry = mysqli_query($var_conn, $var_Apdata);

if (mysqli_num_rows($var_schedqry) > 0) {
    $var_rec = mysqli_fetch_array($var_schedqry);
    $var_day = $var_rec["day"];
    $var_SchedID = $var_rec["shed_id"];
    $var_PTID = $var_rec["therapists_id"];
    $var_PtntID = $_SESSION["sess_PtntID"];
    $var_status = "Pending";
}

if (isset($_POST["BtnSubmit"])) {
    $var_Session = trim($_POST["TxtSession"]);
    $var_Ptype = $_POST["PaymentType"];
    $var_Date = $_POST["SDate"];
    $var_radBtn = $_POST["Radcntract"];
    $var_type = "Pending";
    $var_status = "Pending";
    // echo "session" . $var_Session . " Ptype" . $var_Ptype . " " . $var_Date . " " . $var_radBtn . " " . " " . $var_PTID . " " . $var_PtntID;

    $var_insrt = "INSERT INTO tbl_appointment (num_of_session,payment_type,
    start_date,is_aggreed,therapists_id,patient_id,status,schedle_id)
    VALUES($var_Session,'$var_Ptype','$var_Date','$var_radBtn',$var_PTID,$var_PtntID,'$var_status',$var_SchedID)";

    $var_PuserId = "SELECT user_id  FROM tbl_therapists WHERE therapist_id=" . $var_PTID;
    $var_uId = mysqli_query($var_conn, $var_PuserId);
    $var_get = mysqli_fetch_array($var_uId);
    $var_UID = $var_get['user_id'];
    $var_APqry = mysqli_query($var_conn, $var_insrt);
    if ($var_APqry) {
        $last_APid = $var_conn->insert_id;

        $var_notif = "INSERT INTO tbl_notifications(user_id,appointment_id,type)
        VALUES($var_UID,$last_APid,'$var_type')";
        mysqli_query($var_conn, $var_notif);

        $sql = "UPDATE `tbl_sched` SET status = '' WHERE shed_id = $var_SchedID";
        $result = $var_conn->query($sql);

        if ($result) {
            $_SESSION["statusTitle"] = "Success";
            $_SESSION["statusText"] = "Your appointment has been created.";
            $isSuccess = true;
        } else {
            $_SESSION["statusTitle"] = "Oops!";
            $_SESSION["statusText"] = "Something went wrong while creating an appointment, please try again.";
        }
    } else {
        $_SESSION["statusTitle"] = "Oops!";
        $_SESSION["statusText"] = "Something went wrong while creating an appointment, please try again.";
    }
}
?>

<!DOCTYPE html>
<html data-bs-theme="light">
<head>
    <meta charset='utf-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <title>TheraAid | Patient Appointment</title>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <link rel='stylesheet' type='text/css' media='screen' href='./assets/css/PatientHomePage.css'>
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
                            <img src="./assets/img/Logo.jpg" class="rounded-pill shadow" alt="Logo.jpg" width="64" height="64">
                        </h5>
                        <button type="button" class="btn-close shadow" data-bs-dismiss="offcanvas"
                            aria-label="Close"></button>
                    </div>

                    <div class="offcanvas-body">
                        <ul class="navbar-nav justify-content-end flex-grow-1 pe-0 gap-0 gap-lg-4">
                            <li class="nav-item">
                                <a class="nav-link fw-semibold text-center" aria-current="page" href="./PatientHomePage.php">
                                    <i class="bi bi-house fs-3"></i><br>
                                    <small>Home</small>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link fw-semibold text-center" aria-current="page" href="./PATAppointmentList.php">
                                    <i class="bi bi-calendar-check fs-3"></i><br>
                                    <small>Appointment</small>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link fw-semibold text-center" aria-current="page" href="PatientHistory.php">
                                    <i class="bi bi-clock-history fs-3"></i><br>
                                    <small>History</small>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link fw-semibold text-center" aria-current="page" href="./PATnotif.php">
                                    <i class="bi bi-bell fs-3"></i><br>
                                    <small>Notification</small>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link fw-semibold text-center" aria-current="page" href="./PatientChat.php">
                                    <i class="bi bi-chat-dots fs-3 chat-badge"></i><br>
                                    <small>Chat</small>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link fw-semibold text-center" aria-current="page" href="./ProfilePage.php">
                                    <i class="bi bi-person fs-3"></i><br>
                                    <small>Profile</small>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link fw-semibold text-center" aria-current="page" href="./PatientView.php">
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

    <div class="modal fade" id="mainModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5"></h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body"></div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary px-5 rounded-5" data-bs-dismiss="modal">Okay</button>
                </div>
            </div>
        </div>
    </div>

    <main class="py-0 py-sm-3">

        <section class="patient-appointment-section bg-secondary-subtle py-3 py-sm-5 px-3 px-sm-5 shadow container-fluid container-sm">

            <h3>Appointment Information</h3>
            <hr>

            <div class="mb-3">
                <?php
                
                echo "<label class='mb-1'><b>Day Selected: </b>" . $var_rec["day"] . "</label><br>";
                echo "<label class='mb-1'><b>Start Time: </b>" . $var_rec["start_time"] . "</label><br>";
                echo "<label class='mb-1'><b>End Time: </b>" . $var_rec["end_time"] . "</label><br>";
                echo "<label class='mb-1'><b>Note: </b>" . $var_rec["note"] . "</label><br>";
                echo "<label class='mb-1'><b>Schedule ID: </b>" . $var_rec["shed_id"] . "</label><br>";
                echo "<label class='mb-1'><b>Therapist ID: </b>" . $var_rec["therapists_id"] . "</label><br>";

                ?>
            </div>

            <hr>

            <form method="POST" action="PatAppointment.php" class="needs-validation" novalidate>
                <div class="mb-3">
                    <label class="mb-1">Num Of Session:</label>
                    <input type="text" value="<?php echo $var_Session; ?>" name="TxtSession" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="mb-1">Payment Type:</label>
                    <select name="PaymentType" id="color" class="form-control" required>
                        <!-- <option value="">--- Choose a payment type ---</option> -->
                        <option value="PS">Per Session</option>
                        <option value="PC">Package</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="mb-1">Start Date:</label>
                    <select name="SDate" id="SDate" class="form-control" required>
                        <!-- <option value="">--- Select Start Date ---</option> -->
                        <?php
                        $selectedDaysString = $var_day; // Example input, this should come from your data
                        $selectedDaysArray = explode(',', $selectedDaysString);
                    
                        // Map days to numeric values for the DateTime::format('N') function
                        $daysMap = [
                            'Mon' => 1, // Monday
                            'Tue' => 2, // Tuesday
                            'Wed' => 3, // Wednesday
                            'Thu' => 4, // Thursday
                            'Fri' => 5, // Friday
                            'Sat' => 6, // Saturday
                        ];
                    
                        // Use the actual current date
                        $today = new DateTime();
                        $currentMonth = $today->format('m'); // Current month
                        $currentYear = $today->format('Y');  // Current year
                        $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $currentMonth, $currentYear);
                    
                        // Define how many days in total we want to render
                        $totalDaysToRender = 8;
                        $daysRendered = 0;
                    
                        // Function to generate options for a given month and year
                        function generateOptions($year, $month, $startDay, $selectedDaysArray, $daysMap, &$daysRendered, $totalDaysToRender, $today)
                        {
                            $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);
                            for ($day = $startDay; $day <= $daysInMonth; $day++) {
                                $dateString = sprintf('%04d-%02d-%02d', $year, $month, $day);
                                $date = DateTime::createFromFormat('Y-m-d', $dateString);
                            
                                if ($date && $date >= $today) {
                                    $dayOfWeek = $date->format('N'); // Day of the week as a number (1=Monday, 7=Sunday)
                                
                                    // Check if the day of the week matches the selected days
                                    foreach ($selectedDaysArray as $selectedDay) {
                                        if (isset($daysMap[$selectedDay]) && $dayOfWeek == $daysMap[$selectedDay]) {
                                            echo "<option value='" . $date->format('Y-m-d') . "'>" . $date->format('Y-m-d') . "</option>";
                                            $daysRendered++;
                                            break; // Move to the next date once a match is found
                                        }
                                    }
                                }
                            
                                // Stop if we have rendered enough days
                                if ($daysRendered >= $totalDaysToRender) {
                                    return;
                                }
                            }
                        }
                    
                        // Render dates for the current month
                        generateOptions($currentYear, $currentMonth, $today->format('d'), $selectedDaysArray, $daysMap, $daysRendered, $totalDaysToRender, $today);
                    
                        // If there are not enough days left in the current month, render days from the next month
                        if ($daysRendered < $totalDaysToRender) {
                            $nextMonth = $today->modify('first day of next month');
                            generateOptions($nextMonth->format('Y'), $nextMonth->format('m'), 1, $selectedDaysArray, $daysMap, $daysRendered, $totalDaysToRender, $today);
                        }
                        ?>
                    </select>
                </div>

                <hr>

                <h2 class="mb-3">Therapist Contract</h2>

                <div class="mb-3">
                <?php

                $sql = "SELECT
                        *
                    FROM tbl_sched sched
                    JOIN tbl_therapists therapist ON therapist.therapist_id = sched.therapists_id
                    JOIN tbl_user user ON user.User_id = therapist.user_id
                    WHERE sched.shed_id = $var_SchedID";
                $result = $var_conn->query($sql);

                if ($result->num_rows > 0 && $rows = $result->fetch_assoc()) {
                    $contract = $rows["contract"];

                    if ($contract !== null) {
                        echo "<div id='contractPreviewContainer'></div>";
                    } else {
                        echo "<b>This therapist has not yet uploaded a contract. :(</b>";
                    }
                }

                ?>
                </div>

                <div class="mb-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="1" name="Radcntract" id="agree" required>
                        <label class="form-check-label" for="agree">I Agree</label>
                        <div class="invalid-feedback">
                            Kindly review and accept the therapist contract.
                        </div>
                    </div>
                </div>

                <input type="submit" value="Submit" name="BtnSubmit" class="btn btn-primary px-5 rounded-5">
            </form>

        </section>

    </main>

    <script src="./node_modules/bootstrap/dist/js/bootstrap.bundle.js"></script>
    <script src='./node_modules/markdown-it/dist/markdown-it.min.js'></script>
    <script>
        const markdown = window.markdownit();

        (() => {
            if (document.getElementById("contractPreviewContainer")) {
                const contractPreviewContainer = document.getElementById("contractPreviewContainer");

                contractPreviewContainer.innerHTML = markdown.render(`<?php echo $contract; ?>`);
            }
            
            const forms = document.getElementsByClassName("needs-validation");

            Array.from(forms).forEach((form) => {
                form.addEventListener("submit", (e) => {
                    if (!form.checkValidity()) {
                        e.preventDefault();
                        e.stopPropagation();
                    }

                    form.classList.add("was-validated");
                });
            });

            <?php

            if (isset($_SESSION["statusTitle"]) && isset($_SESSION["statusText"])) {
                $title = $_SESSION["statusTitle"];
                $message = $_SESSION["statusText"];

                echo "showModal('$title','$message', $isSuccess)";

                unset($_SESSION["statusTitle"], $_SESSION["statusText"]);
            }

            ?>

        })();

        function showModal(title, message, isSuccess) {
            const element = document.getElementById("mainModal");
            const modal = new bootstrap.Modal(element);

            element.querySelector(".modal-title").innerHTML = title;
            element.querySelector(".modal-body").innerHTML = message;

            if (isSuccess) {
                element.querySelector(".modal-footer").querySelector("button").addEventListener("click", () => {
                    window.location.href = "./PatientView.php";
                });
            }

            modal.show();
        }
    </script>

</body>

</html>