<?php

include "./database.php";

session_start();

$sess_PTID = $_SESSION["sess_PTID"];

$isFeedback = false;

if (isset($_GET["feedback"])) {
    $appointment_id = $_GET["feedback"];

    $sql = "SELECT * FROM `tbl_appointment` WHERE appointment_id = $appointment_id";
    $result = $var_conn->query($sql)->fetch_assoc();

    $sess_PTID = $result["therapists_id"];

    $isFeedback = true;
}

?>

<!DOCTYPE html>
<html data-bs-theme="light">

<head>
    <meta charset='utf-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <title>TheraAid | Patient View</title>
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
                            <img src="./assets/img/Logo.jpg" class="rounded-pill shadow" alt="Logo.jpg" width="64"
                                height="64">
                        </h5>
                        <button type="button" class="btn-close shadow" data-bs-dismiss="offcanvas"
                            aria-label="Close"></button>
                    </div>

                    <div class="offcanvas-body">
                        <ul class="navbar-nav justify-content-end flex-grow-1 pe-0 gap-0 gap-lg-4">
                            <li class="nav-item">
                                <a class="nav-link fw-semibold text-center" aria-current="page"
                                    href="./PatientHomePage.php">
                                    <i class="bi bi-house fs-3"></i><br>
                                    <small>Home</small>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link fw-semibold text-center" aria-current="page"
                                    href="./PATAppointmentList.php">
                                    <i class="bi bi-calendar-check fs-3"></i><br>
                                    <small>Appointment</small>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link fw-semibold text-center" aria-current="page"
                                    href="PatientHistory.php">
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
                                <a class="nav-link fw-semibold text-center" aria-current="page"
                                    href="./PatientChat.php">
                                    <i class="bi bi-chat-dots fs-3 chat-badge"></i><br>
                                    <small>Chat</small>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link fw-semibold text-center" aria-current="page"
                                    href="./ProfilePage.php">
                                    <i class="bi bi-person fs-3"></i><br>
                                    <small>Profile</small>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link fw-semibold text-center" aria-current="page"
                                    href="./PatientHomePage.php">
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

    <div class="toast-container position-fixed bottom-0 end-0 p-3">
        <div id="toast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header">
                <strong class="me-auto">Notification</strong>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body"></div>
        </div>
    </div>

    <div class="modal modal-xl fade" id="feedbackModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5">Feedback</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form class="needs-validation" id="feedbackForm" novalidate>
                        <div class="mb-3">
                            <label class="mb-1"><b>Comment</b></label>
                            <textarea class="form-control" name="comment" style="height: 150px;" required></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary px-5 rounded-5 shadow" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary px-5 rounded-5 shadow" form="feedbackForm">Send</button>
                </div>
            </div>
        </div>
    </div>

    <main class="py-0 py-sm-3">

        <section class="patient-view-section bg-secondary-subtle py-3 py-sm-5 px-3 px-sm-5 shadow container-fluid container-sm">

            <div class="row">

                <div class="col-lg-3">

                    <div class="mb-3 d-flex justify-content-center align-items-center">
                        <img id="ProfPic" class="img-fluid rounded-5 shadow" style="height: 250px; width: auto;" src="#"
                            alt="#">
                    </div>

                    <hr>

                    <div class="mb-1">
                        <label class="mb-1">
                            <b>Full Name:</b>
                            <span id="fllname" class="text-capitalize"></span>
                        </label><br>
                        <label class="mb-1">
                            <b>Case Handled:</b>
                            <span id="case_handled" class="text-capitalize"></span>
                        </label><br>
                        <label class="mb-1">
                            <b>City:</b>
                            <span id="City" class="text-capitalize"></span>
                        </label><br>
                        <label class="mb-1">
                            <b>Barangay:</b>
                            <span id="barangay" class="text-capitalize"></span>
                        </label><br>
                        <label class="mb-1">
                            <b>Therapist ID:</b>
                            <span id="ID"></span>
                        </label>
                    </div>

                    <div class="row gap-1">
                        <div class="col-lg p-0">
                            <button type="button" id="sendMessageButton" class="btn btn-primary rounded-5 w-100 mb-2 mb-lg-0 btn-sm">Send Message</button>
                        </div>
                        <div class="col-lg p-0">
                            <button type="button" class="btn btn-primary rounded-5 w-100 mb-3 mb-lg-0 btn-sm" data-bs-target="#feedbackModal" data-bs-toggle="modal" id="sendFeedbackBtn">Send Feedback</button>
                        </div>
                    </div>
                </div>

                <div class="col-lg">

                    <div class="mb-3">
                        <div id="TimeBTN" class="TimeBTN mb-3">
                            <button id="BtnAM" class="btn btn-primary px-5 rounded-5">AM</button>
                            <button id="BtnPM" class="btn btn-primary px-5 rounded-5">PM</button>
                        </div>

                        <div class="AM shadow p-3 rounded" id="AM-schedule">
                            <div class="SchedButton">
                                <div id="AM" class="d-flex justify-content-center justify-content-sm-start align-items-start gap-2 flex-wrap"></div>
                            </div>
                        </div>
                        <div class="PM shadow p-3 rounded" id="PM-schedule" style="display: none;">
                            <div class="SchedButton" id="PM" class="d-flex justify-content-center justify-content-sm-start align-items-start gap-2 flex-wrap"></div>
                        </div>
                    </div>

                    <div class="p-3">
                        <h1>Feedbacks</h1>

                        <hr>

                        <div id="feedbackContainer">

                        </div>

                        <!-- <div class="shadow bg-body-secondary rounded-5 p-3">

                            <div class="mb-3">
                                <img src="./UserFiles/ProfilePictures/67192e2117b9d.jpg" alt="67192e2117b9d.jpg" class="img-fluid rounded-pill shadow" style="height: 48px; width: 48px; object-fit: cover;">
                                <label class="ms-2"><b>Charles Henry Tinoy</b></label>
                            </div>

                            <div class="mb-3">
                                <div class="d-flex justify-content-start align-items-start flex-row gap-2 fs-4">
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-half"></i>
                                </div>
                            </div>

                            <div>
                                <label class="mb-1"><b>Comment:</b></label>
                                <textarea class="form-control" style="height: 150px;" readonly>Comment here...</textarea>
                            </div>

                        </div> -->

                    </div>

                </div>

            </div>

        </section>

    </main>

    <script src="./node_modules/bootstrap/dist/js/bootstrap.bundle.js"></script>
    <script>
        var TherapID;

        (() => {
            <?php if ($isFeedback): ?>
                document.getElementById('sendFeedbackBtn').click()
            <?php endif ?>

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

            const btnAM = document.getElementById('BtnAM');
            const btnPM = document.getElementById('BtnPM');
            const amSchedule = document.getElementById('AM-schedule');
            const pmSchedule = document.getElementById('PM-schedule');

            if (btnAM && btnPM && amSchedule && pmSchedule) {
                btnAM.addEventListener('click', function () {
                    amSchedule.style.display = 'block';
                    pmSchedule.style.display = 'none';
                });

                btnPM.addEventListener('click', function () {
                    amSchedule.style.display = 'none';
                    pmSchedule.style.display = 'block';
                });
            }

            PTProf();

            const feedbackForm = document.getElementById("feedbackForm");

            feedbackForm.addEventListener("submit", async (e) => {
                e.preventDefault();
                e.stopPropagation();
            
                const comment = feedbackForm.comment.value;
            
                if (comment.split(" ").length < 3) {
                    return showToast("The comment must more than 3 words");
                }
            
                const formData = new FormData();
                formData.append("message", comment);
                formData.append("targetID", TherapID);
            
                const response = await fetch("./FeedbackAPI/post_feedback.php", {
                    method: "POST",
                    body: formData
                });
            
                const responseText = await response.text();
                const responseStatus = response.status;
            
                console.log(responseStatus);
            
                showToast(responseText);
            });
        })();

        async function PTProf() {
            try {
                const response = await fetch("./PatientViewAPI/TherapistsProfAPI.php", {
                    method: "POST",
                    body: JSON.stringify({
                        'ID': "<?php echo $sess_PTID; ?>"
                    })
                });

                const data = await response.json();
                const fullname = `${data.fname} ${data.mname.charAt(0)}. ${data.lname}`;

                document.getElementById("fllname").innerText = fullname;
                document.getElementById("ProfPic").src = `./UserFiles/ProfilePictures/${data.ProfPic}`;
                document.getElementById("case_handled").innerText = data.case.split(",").map((word) => {
                    return " " + word;
                }).toString();
                document.getElementById("City").innerText = data.city;
                document.getElementById("barangay").innerText = data.barangay;
                document.getElementById("ID").innerText = data.therapitst_id;

                document.getElementById("sendMessageButton").addEventListener("click", () => {
                    window.location.href = `./PatientChat.php?newChat=${data.userID}`;
                });

                TherapID = data.therapitst_id;
                GetSched(TherapID);
                getFeedback(TherapID);
            } catch (error) {
                console.error('Error:', error);
            }
        }

        async function getFeedback(ID) {
            const formData = new FormData();
            formData.append("therapist_id", ID);

            const response = await fetch("./FeedbackAPI/get_feedback.php", {
                method: "POST",
                body: formData
            });

            const responseText = await response.text();

            const feedbackContainer = document.getElementById("feedbackContainer");

            feedbackContainer.innerHTML = responseText;
        }

        async function GetSched(TherapID) {
            function convertTo12HourFormat(time24) {
                const [hours, minutes] = time24.split(':');
                let hour12 = hours % 12 || 12;
                const period = hours >= 12 ? 'PM' : 'AM';

                hour12 = String(hour12).padStart(2, '0');

                return `${hour12}:${minutes} ${period}`;
            }

            function createScheduleButton(SchedID, SchedDay, Stime, Etime, Note) {
                const button = document.createElement('button');
                button.className = 'schedule-btn btn btn-outline-primary d-flex justify-content-center align-items-start flex-column gap-1';
                button.value = SchedID;

                const dayLabel = document.createElement('label');
                dayLabel.innerHTML = `<b>Day<small>(s)</small>:</b> ${SchedDay}`

                const timeLabel = document.createElement('label');
                timeLabel.innerHTML = `<b>Time:</b> ${convertTo12HourFormat(Stime)} to ${convertTo12HourFormat(Etime)}`

                const noteLabel = document.createElement('label');
                noteLabel.innerHTML = `<b>Note:</b> ${Note}`

                button.appendChild(dayLabel);
                button.appendChild(timeLabel);
                button.appendChild(noteLabel);
                button.addEventListener("click", () => {
                    SessionSched(SchedID);
                })

                return button;
            }

            try {
                const SchedRes = await fetch("./HomePageAPI/TherapistsSchedAPI.php", {
                    method: "POST",
                    body: JSON.stringify({
                        "TID": TherapID
                    }),
                    headers: {
                        'Content-Type': 'application/json'
                    }
                });

                const scheddata = await SchedRes.json();
                const amElement = document.getElementById("AM");
                const pmElement = document.getElementById("PM");

                amElement.innerHTML = "";
                pmElement.innerHTML = "";

                if (scheddata.message) {

                    amElement.innerHTML = scheddata.message;
                    pmElement.innerHTML = scheddata.message;

                } else {
                    scheddata.forEach(schedule => {
                        const SchedID = schedule.Sched_id;

                        const SchedDay = schedule.Day.split(",").map((item) => {
                            return " " + item;
                        }).toString();

                        const Stime = schedule.Start_ime;
                        const Etime = schedule.End_Time;
                        const Note = schedule.Note;

                        if (convertTo12HourFormat(schedule.Start_ime).includes("AM")) {
                            const scheduleHTML = `
                                <button value="${SchedID}" class='schedule-btn btn btn-outline-primary d-flex justify-content-center align-items-start flex-column gap-1'>
                                    <label><b>Day<small>(s)</small>:</b> ${SchedDay}</label>
                                    <label><b>Time:</b> ${convertTo12HourFormat(Stime)} to ${convertTo12HourFormat(Etime)}</label>
                                    <label><b>Note:</b> ${Note}</label>
                                </button>`;

                            amElement.innerHTML += scheduleHTML;

                        } else if (convertTo12HourFormat(schedule.Start_ime).includes("PM")) {
                            const scheduleHTML = `
                            <button value="${SchedID}" class='schedule-btn btn btn-outline-primary d-flex justify-content-center align-items-start flex-column gap-1'>
                                <label><b>Day<small>(s)</small>:</b> ${SchedDay}</label>
                                <label><b>Time:</b> ${convertTo12HourFormat(Stime)} to ${convertTo12HourFormat(Etime)}</label>
                                <label><b>Note:</b> ${Note}</label>
                            </button>`;

                            pmElement.innerHTML += scheduleHTML;
                        }
                    });

                    Array.from(amElement.getElementsByClassName("schedule-btn")).forEach((button) => {
                        button.addEventListener("click", () => {
                            let SlctedID = button.getAttribute("value");
                            CheckChed("<?php echo $_SESSION["sess_PtntID"] ?>", SlctedID);
                        });
                    });

                    Array.from(pmElement.getElementsByClassName("schedule-btn")).forEach((button) => {
                        button.addEventListener("click", () => {
                            let SlctedID = button.getAttribute("value");
                            CheckChed("<?php echo $_SESSION["sess_PtntID"] ?>", SlctedID);
                        });
                    });

                }
            } catch (error) {
                console.error('Error:', error);
                document.getElementById("AM").innerHTML = "An error occurred while fetching schedules.";
                document.getElementById("PM").innerHTML = "An error occurred while fetching schedules.";
            }
        }

        async function CheckChed(PatID, SlctedID) {
            try {
                const schedRes = await fetch("./PatientViewAPI/CheckSchedAPI.php", {
                    method: "POST",
                    body: JSON.stringify({
                        "PATID": PatID
                    }),
                    headers: {
                        'Content-Type': 'application/json'
                    }
                });

                const resSched = await schedRes.text();

                if (resSched === "1") {
                    showToast("You have already booked an appointment and session");
                }
                if (resSched === "0") {
                    SessionSched(SlctedID);
                }
            } catch (error) {
                console.error('Error:', error);
            }
        }

        function SessionSched(SlctedID) {
            fetch("./PatientViewAPI/SelectedDateAPI.php", {
                method: "POST",
                body: JSON.stringify({
                    SchedID: SlctedID
                }),
                headers: {
                    'Content-Type': 'application/json'
                }
            })
                .then(() => {
                    window.location.href = "PatAppointment.php"; // Redirect to PatAppointment.php
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        }

        function showToast(message) {
            const toastElement = document.getElementById("toast");
            const toast = new bootstrap.Toast(toastElement);

            toastElement.getElementsByClassName("toast-body")[0].innerHTML = message;

            toast.show();
        }
    </script>

</body>

</html>