<?php
include "./database.php";
session_start();

date_default_timezone_set('Asia/Manila');

$var_crrntTime = date("h:i:sa");
$var_currntDate = date("Y-m-d");
// $var_currntDate = "2024-11-09";

$var_appid = $_GET["record"];

if (isset($_POST["session_id"])) {
    $session_id = $_POST["session_id"];

    $sql = "SELECT * FROM `tbl_session` WHERE session_id = $session_id";

    $result = $var_conn->query($sql)->fetch_assoc();

    echo $result["photo"];
    exit;
}

$var_sessionList = "SELECT 
                    	*,
                    	DATE_FORMAT(sessions.Date_creadted, '%M %D, %Y') AS formatted_date_created
                    FROM tbl_session sessions
                    JOIN tbl_appointment appointment
                    ON sessions.appointment_id = appointment.appointment_id 
                    WHERE sessions.appointment_id = $var_appid";
$var_Slist = mysqli_query($var_conn, $var_sessionList);
$var_note = "";
$var_Photo = "";
$var_sessID = "";

?>
<!DOCTYPE html>
<html data-bs-theme="light">

<head>
    <meta charset='utf-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <title>TheraAid | Therapist Create Session</title>
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
                                <a class="nav-link fw-semibold text-center active" aria-current="page"
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

    <div class="toast-container position-fixed bottom-0 end-0 p-3">
        <div id="toast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header">
                <strong class="me-auto">Notification</strong>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body"></div>
        </div>
    </div>

    <div class="modal modal-xl fade" id="SessionModal" tabindex="-1" role="dialog" aria-labelledby="SessionModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="SessionModalLabel">Session</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="TherapistCreateSession.php" id="editSessionForm" class="needs-validation" novalidate>
                        <input name="sessionID" value="" required hidden>
                        <div class="mb-3">
                            <label class="mb-1">Note:</label><br>
                            <textarea style="height: 150px;" class="form-control w-100" name="TxtNote" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="mb-1">Images:</label><br>
                            <input type="file" class="form-control" name="FilePoto" multiple accept="image/*" required>
                        </div>
                        <div class="d-flex justify-content-center align-items-center flex-column d-block mb-3" id="photoContainer">
                            <small class="fw-semibold mb-2">Session Pictures</small>
                            <!-- <img src="./UserFiles/SessionPictures/" id="sessionPicturesPreview" class="img-thumbnail" style="height: 250px; width: auto; object-fit: cover;"> -->
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary px-5 rounded-5 shadow" data-bs-dismiss="modal"
                        id="closeSessionModalBtn">Close</button>
                    <button type="submit" class="btn btn-primary px-5 rounded-5 shadow" form="editSessionForm">Save
                        Changes</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="Session" tabindex="-1" aria-labelledby="Session" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="Session">Session</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <button type="button" id="StartSession"
                        class="btn btn-outline-primary px-5 rounded-5 shadow w-100">Start Session</button>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary px-5 rounded-5 shadow"
                        data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="viewCertificateModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5">Your Certificate!</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <?php

                    $sql = "SELECT *
                            FROM tbl_appointment appointment
                            JOIN tbl_therapists therapist ON therapist.therapist_id = appointment.therapists_id
                            WHERE appointment.appointment_id = $var_appid";
                    $result = $var_conn->query($sql)->fetch_assoc();

                    $certificate = $result["certificate"];

                    if ($certificate !== null) {
                        echo "<embed src='./UserFiles/Certificates/$certificate' class='border-0 w-100' style='height: 100vh;'/>";
                    } else {
                        echo "<b>This therapist has not yet uploaded a certificate. :(</b>";
                    }

                    ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary px-5 rounded-5 shadow"
                        data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal modal-xl fade" id="healthPlanModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5">Health Plan</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <?php
                    
                    $sql = "SELECT * FROM tbl_appointment WHERE appointment_id = $var_appid";
                    $result = mysqli_query($var_conn, $sql)->fetch_assoc();

                    $healthPlan = $result["health_plan"];

                    ?>
                    <?php if ($healthPlan !== null): ?>
                        <div class="d-flex justify-content-center align-items-center flex-column d-block mb-3">
                            <small class="fw-semibold mb-2">Health Plan Preview</small>
                            <embed src='./UserFiles/SessionHealthPlan/<?= $healthPlan ?>' id="healthPlanPreview" class='border-0 w-100' style='height: 100vh;'/>";
                        </div>
                    <?php else: ?>
                        <div class='d-flex justify-content-center align-items-center flex-column gap-3 py-2'>
                            <h3>Oops! Looks like your therapist haven't uploaded a health plan yet</h3>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary px-5 rounded-5 shadow" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <main class="py-0 py-sm-3">
        <section class="main-section bg-secondary-subtle py-3 py-sm-5 px-3 px-sm-5 shadow container">

            <div class="row gap-3 gap-lg-0">

                <div class="col-lg">

                    <div class="mb-3 d-flex justify-content-center align-items-center">
                        <img id="ProfPic" class="img-fluid rounded-5 shadow" style="height: 250px; width: auto;" src="#"
                            alt="#">
                    </div>

                    <hr>

                    <div class="mb-3">
                        <label class="mb-1">
                            <b>Full Name:</b>
                            <span id="fllname"></span>
                        </label><br>
                        <label class="mb-1">
                            <b>Case:</b>
                            <span id="case"></span>
                        </label><br>
                        <label class="mb-1">
                            <b>City:</b>
                            <span id="City"></span>
                        </label><br>
                        <label class="mb-1">
                            <b>Sessions:</b>
                            <span id="sess"></span>
                        </label>
                    </div>

                    <hr>

                    <div class="d-flex justify-content-center align-items-center gap-2">
                        <button type="button" class="btn btn-outline-primary rounded-5 w-100 shadow" id="genCertificateBtn" data-bs-target="#viewCertificateModal" data-bs-toggle="modal" disabled>Generate Certificate</button>
                        <button type="button" class="btn btn-outline-primary rounded-5 w-100 shadow" id="feedbackBtn">Feedback</button>
                        <button type="button" class="btn btn-outline-primary rounded-5 w-100 shadow" data-bs-target="#healthPlanModal" data-bs-toggle="modal">Health Plan</button>
                    </div>

                    <hr class="d-block d-lg-none">

                </div>

                <div class="col-lg">



                    <hr>

                    <h3 class="text-center mb-3">Sessions</h3>
                    <label class="fw-semibold mb-3">Click a session to edit</label>

                    <div class="d-flex justify-content-center align-items-center flex-column gap-2">

                        <?php

                        $index = 0;
                        $isDisabled = false;
                        $num_of_session = null;

                        foreach ($var_Slist->fetch_all(MYSQLI_ASSOC) as $var_Sesget) {
                            $index++;

                            $var_note = $var_Sesget["note"];
                            $var_sessID = $var_Sesget["session_id"];
                            $num_of_session = intval($var_Sesget["num_of_session"]);
                            $formatted_date_created = $var_Sesget["formatted_date_created"];
                            $status = $var_Sesget["status"];

                            if ($var_note == "") {
                                $var_note = "No Note";
                            }

                            echo "<input name='TxtsessId' value='$var_sessID' hidden>";
                            echo "<button type='button' class='btn btn-outline-primary p-3 w-100 shadow text-start' data-bs-target='#SessionModal' data-bs-toggle='modal' onclick='openSessionModal(`$var_sessID`, `$var_note`)'>
                                    <label><b>Date: </b>$formatted_date_created</label><br>
                                    <label><b>Note: </b>$var_note</label><br>
                                    <label><b>Status: </b>$status</label><br>
                                </button>";
                        }

                        if ($index >= $num_of_session) {
                            $isDisabled = true;
                        }

                        ?>

                    </div>

                </div>

            </div>

        </section>
    </main>

    <script src="./node_modules/bootstrap/dist/js/bootstrap.bundle.js"></script>
    <script>
        window.addEventListener("DOMContentLoaded", () => {

            const feedbackBtn = document.getElementById("feedbackBtn");

            feedbackBtn.addEventListener("click", () => {
                window.location.href = "./PatientView.php?feedback=<?php echo $var_appid; ?>";
            });

            const genCertificateBtn = document.getElementById('genCertificateBtn');

            <?php
            if ($isDisabled) {
                echo "genCertificateBtn.disabled = true;";
            }
            ?>

            const forms = document.getElementsByClassName("needs-validation");

            Array.from(forms).forEach((form) => {
                form.addEventListener("submit", (e) => {
                    e.preventDefault();
                    e.stopPropagation();

                    form.classList.add("was-validated");
                });
            });

            const editSessionForm = document.getElementById("editSessionForm");

            editSessionForm.addEventListener("submit", async (e) => {
                if (!editSessionForm.checkValidity()) {
                    return;
                }

                const sessionID = editSessionForm.sessionID.value;
                const TxtNote = editSessionForm.TxtNote.value;
                const FilePoto = editSessionForm.FilePoto.files;

                if (FilePoto.length > 3) {
                    return showToast("Please follow the maximum file input.");
                }

                const formData = new FormData();
                formData.append("sessionID", sessionID);
                formData.append("TxtNote", TxtNote);

                for (let i = 0; i < FilePoto.length; i++) {
                    const file = FilePoto[i];
                    const filename = FilePoto[i].name;

                    formData.append("FilePoto[]", file, filename);
                }

                const response = await fetch("./PTSESSIONAPI/edit_session.php", {
                    method: "POST",
                    body: formData
                });

                const responseText = await response.text();

                showToast(responseText);

                const closeSessionModalBtn = document.getElementById("closeSessionModalBtn");

                closeSessionModalBtn.click();
            });

            let city;
            let caseDesc;
            let PtntID;
            let AppntmntId;

            async function GETTherapists() {
                try {
                    const response = await fetch("./PTSESSIONAPI/PATtherpists.php", {
                        method: "POST",
                        body: JSON.stringify({
                            "PTID": "<?php echo $var_appid; ?>"
                        })
                    });
                    const data = await response.json();
                    const fullname = `${data.fname} ${data.mname.charAt(0)}. ${data.lname}`;
                    document.getElementById("fllname").innerText = fullname;
                    document.getElementById("ProfPic").src = `./UserFiles/ProfilePictures/${data.profPic}`;
                    document.getElementById("case").innerText = data.case;
                    document.getElementById("City").innerText = data.city;
                    document.getElementById("sess").innerText = data.Session;
                    AppntmntId = data.APID;
                    city = data.city;
                    caseDesc = data.case;
                    PtntID = data.PtntID;


                } catch (error) {
                    console.error('Error:', error);
                }
            }
            GETTherapists()
            document.getElementById("StartSession").addEventListener("click", () => {
                checkSession(AppntmntId);
            });

            async function checkSession(AppntmntId) {
                try {
                    const response = await fetch("./PTSESSIONAPI/PTcheckSession.php", {
                        method: "POST",
                        body: JSON.stringify({
                            "appId": AppntmntId
                        })
                    });

                    const responseText = await response.text();

                    showToast(responseText);

                    if (response.status === 200) {
                        const formData = new FormData();
                        formData.append("appointment_id", AppntmntId);

                        fetch("./PTSESSIONAPI/create_session.php", {
                            method: "POST",
                            body: formData
                        });
                    }
                } catch (err) {
                    console.log(err);
                }
            }
        });

        async function openSessionModal(sessionId, note) {
            editSessionForm.sessionID.value = sessionId;
            editSessionForm.TxtNote.value = note;

            const formData = new FormData();
            formData.append("session_id", sessionId);

            const response = await fetch("./PatientSessionView.php?record=<?php echo $var_appid; ?>", {
                method: "POST",
                body: formData
            });

            const photos = await response.text();
            const photosArray = photos.split(",");
            const photoContainer = document.getElementById("photoContainer");

            for (let photo of photosArray) {
                photoContainer.innerHTML += `<img src='./UserFiles/SessionPictures/${photo}' class='img-thumbnail' style='height: 250px; width: auto; object-fit: cover; margin-right: 10px; cursor: pointer;'>`;
            }

            Array.from(photoContainer.getElementsByTagName("img")).forEach((img) => {
                img.addEventListener("click", (e) => {
                    window.open(img.src);
                });
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