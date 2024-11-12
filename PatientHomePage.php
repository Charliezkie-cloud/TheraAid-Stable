<?php

include "./database.php";

session_start();

$var_rrminder;
if(!isset($_SESSION["sess_id"])){
    header("Location: Login.php");
    exit();
}
$var_remind = "SELECT TB.reminder_date, 
        TB.reminder_messsage,
        TB.reminder_status,
        P.patient_id,
        SC.start_time,
        SC.end_time
    FROM tbl_reminder TB 
    JOIN tbl_appointment AP ON AP.appointment_id = TB.appointment_id 
    JOIN tbl_patient P ON P.patient_id = AP.patient_id 
    JOIN tbl_sched SC ON SC.shed_id = AP.schedle_id
    JOIN tbl_user U ON U.User_id = P.user_id WHERE P.user_id=" . $_SESSION["sess_id"];

$var_Rqry = mysqli_query($var_conn, $var_remind);
$var_message = "";
$var_sampleDate = "2024-11-13"; // EXAMPLE
$var_currentDate = date($var_sampleDate); //date('Y-m-d');

if (mysqli_num_rows($var_Rqry) > 0) {
    $var_Rrec = mysqli_fetch_array($var_Rqry);
    $var_Date = explode(",", $var_Rrec["reminder_date"]);
  

    if (in_array($var_currentDate, $var_Date)) {
        $var_message = $var_Rrec["reminder_messsage"];
    } else {
        $var_message = "No Session for today";
    }
} else {
    $var_message = "";
}

?>

<!DOCTYPE html>
<html data-bs-theme="light">
<head>
    <meta charset='utf-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <title>TheraAid | Patient Home Page</title>
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
                                <a class="nav-link fw-semibold text-center active" aria-current="page" href="./PatientHomePage.php">
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

    <main class="py-0 py-lg-3">

        <section class="main-section bg-secondary-subtle py-3 py-lg-5 px-3 px-lg-5 shadow container-fluid container-lg">

            <div class="row">

                <div class="col-lg-4">

                    <div class="mb-3 d-flex justify-content-center align-items-center">
                        <img id="ProfPic" class="img-fluid rounded-5 shadow" style="height: 250px; width: auto;" src="#" alt="#">
                    </div>
                    <hr>
                    <div class="mb-3">
                        <label class="mb-1">
                            <b>Full Name:</b>
                            <span id="fllname" class="text-capitalize"></span>
                        </label><br>
                        <label class="mb-1">
                            <b>Case:</b>
                            <span id="case" class="text-capitalize"></span>
                        </label><br>
                        <label class="mb-1">
                            <b>Address:</b>
                            <span id="City" class="text-capitalize"></span>
                        </label>
                    </div>
                    <hr>
                    <h3 class="text-lg-center">Reminder</h3>

                    <div class="reminder d-flex justify-content-start align-items-center flex-column shadow rounded p-3 gap-2" style="height: 250px; overflow-y: auto;">
                        <?php if ($var_message) : ?>
                            <div class="w-100 text-center">
                                <p><?= $var_message . "<br>" . $var_Rrec["start_time"] . "-" . $var_Rrec["end_time"]; ?><hr></p>
                            </div>
                        <?php else : ?>
                            <div class="bg-secondary text-center">
                                <p>No Session for today</p>
                            </div>
                        <?php endif; ?>
                    </div>

                </div>

                <div class="col-lg mt-3 mt-lg-0">
                    <div id="Therapists">

                        <h3>Near me Therapist</h3>
                        <hr>

                        <div id="PT" class="d-flex justify-content-center justify-content-lg-start align-items-start flex-wrap p-3 gap-3 rounded shadow" style="height: 625px; overflow-y: auto;">

                        </div>

                    </div>

                </div>
                
            </div>

        </section>
    </main>

    <script src="./node_modules/bootstrap/dist/js/bootstrap.bundle.js"></script>
    
    <script>
        let city;
        let caseDesc;
        let PtntID;

        (() => {
            
            GETPatient();

        })();

        async function GETPatient() {
            try {
                const response = await fetch("./PatientHomePageAPI/PatientInfoAPI.php", {
                    method: "POST",
                    body: JSON.stringify({
                        "PID": "<?php echo $_SESSION["sess_id"]; ?>"
                    })
                });

                const data = await response.json();
                const fullname = `${data.fname} ${data.mname} ${data.lname}`;

                document.getElementById("fllname").innerText = fullname;
                document.getElementById("ProfPic").src = `./UserFiles/ProfilePictures/${data.profPic}`;

                document.getElementById("case").innerText = data.case.split(",").map((item) => {
                    return " " + item;
                });

                document.getElementById("City").innerText = `${data.city}, ${data.barangay}`;

                const city = data.city;
                const barangay = data.barangay;
                const caseDesc = data.case;
                const PtntID = data.PtntID;

                SessionPNTID(PtntID);
                GETtherapists(city, barangay, caseDesc);
            } catch (error) {
                console.error('Error:', error);
            }
        }

        async function GETtherapists(city, barangay, caseDesc) {
            try {
                const therapists = await fetch("./PatientHomePageAPI/GETtherapistsAPI.php", {
                    method: "POST",
                    body: JSON.stringify({
                        "city": city,
                        "barangay": barangay,
                        "case": caseDesc
                    })
                });

                const ThrpstData = await therapists.json();
                const PTElement = document.getElementById("PT");

                if (ThrpstData.message) {
                    PTElement.innerHTML = ThrpstData.message;
                } else {
                    ThrpstData.forEach(therapist => {
                        const fullname = `${therapist.fname} ${therapist.mname} ${therapist.lname}`;
                        const profilePic = therapist.profilePic;
                        
                        const caseHandled = therapist.case.split(",").map((item) => {
                            return " " + item;
                        }).toString();

                        const cityBarangay = `${therapist.city}, ${therapist.barangay}`;

                        createTherapistCard(PTElement, profilePic, fullname, caseHandled, cityBarangay, therapist.TID);
                    });
                }
            } catch (error) {
                console.log('Error:', error);
            }
        }

        function SessionID(id) {
            fetch("./PatientHomePageAPI/SessionAPI.php", {
                method: "POST",
                body: JSON.stringify({
                    PTID: id,
                })
            }).then((res) => {
                window.location.href = "PatientView.php";
            });
        }

        function SessionPNTID(PtntID) {
            fetch("./PatientHomePageAPI/SessionPID.php", {
                method: "POST",
                body: JSON.stringify({
                    PNTID: PtntID,
                })
            });
        }

        function createTherapistCard(parentElement, profilePicture, fullName, caseHandled, cityBarangay, SlctedID) {
            const card = document.createElement('div');
            card.classList.add('card', 'shadow', 'rounded-5');

            const img = document.createElement('img');
            img.src = `./UserFiles/ProfilePictures/${profilePicture}`;
            img.classList.add('card-img-top');
            img.style.height = '250px';
            img.style.width = '100%';
            img.style.objectFit = 'cover';
            img.style.objectPosition = 'top';
            img.alt = profilePicture;

            card.appendChild(img);

            const cardBody = document.createElement('div');
            cardBody.classList.add('card-body');

            const title = document.createElement('h6');
            title.classList.add('card-title');
            title.classList.add('text-capitalize');
            title.textContent = fullName;

            const caseHandledLabel = document.createElement('small');
            caseHandledLabel.classList.add('mb-1');
            caseHandledLabel.innerHTML = `<b>Case handled: </b><span class='text-capitalize'>${caseHandled}</span>`;

            const cityBarangayLabel = document.createElement('small');
            cityBarangayLabel.classList.add('mb-1');
            cityBarangayLabel.innerHTML = `<b>City: </b><span class='text-capitalize'>${cityBarangay}</span>`;

            const viewButton = document.createElement('a');
            viewButton.href = '#';
            viewButton.classList.add('btn', 'btn-primary', 'btn-sm', 'px-5', 'rounded-5', 'w-100', 'fw-semibold', "mt-2");
            viewButton.textContent = 'View Therapist';

            viewButton.addEventListener("click", () => {
                SessionID(SlctedID);
            });

            cardBody.appendChild(title);
            cardBody.appendChild(caseHandledLabel);
            cardBody.appendChild(document.createElement('br'));
            cardBody.appendChild(cityBarangayLabel);
            cardBody.appendChild(document.createElement('br'));
            cardBody.appendChild(viewButton);

            card.appendChild(cardBody);

            parentElement.appendChild(card);
        }

    </script>
</body>

</html>