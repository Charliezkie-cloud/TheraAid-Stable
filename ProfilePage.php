<?php

include "./database.php";

session_start();

if (!isset($_SESSION["sess_id"])) {
    header("location: landingPage.php");
    exit();
}

$var_profid = intval($_SESSION["sess_id"]);
$var_qry = "SELECT u.User_id,
                    u.UserName,
                    u.Fname, 
                    u.Lname, 
                    u.Mname,
                    u.E_wallet,
                    u.Bday,
                    u.ContactNum, 
                    u.Email,
                    u.profilePic,
                    p.P_case,
                    p.case_desc,
                    p.City, 
                    p.barangay,
                    CONCAT(p.barangay, ',', p.city) AS fullAddress,
                    p.assement_photo, 
                    p.mid_hisotry_photo 
                    FROM tbl_user u JOIN tbl_patient p ON u.User_id = p.user_id
                    WHERE 
                    p.User_id ='$var_profid';";
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
$var_Medpic = null;
$var_Assesment = null;

if (mysqli_num_rows($var_chk) > 0) {
    $var_get = mysqli_fetch_array($var_chk);

    $var_Case = implode(",", array_map(function ($value) {
        return " " . $value;
    }, explode(",", $var_get["P_case"])));

    $username = $var_get["UserName"];
    $var_CaseDesc = $var_get["case_desc"];
    $var_City = $var_get["City"];
    $var_Medpic = explode(",", $var_get["mid_hisotry_photo"]);
    $var_Assesment = explode(",", $var_get["assement_photo"]);
    $var_Street = $var_get["barangay"];
    $fullAddress = $var_get["fullAddress"];
    $var_Fname = $var_get["Fname"];
    $var_Lname = $var_get["Lname"];
    $var_Mname = $var_get["Mname"];
    $var_Date = $var_get["Bday"];
    $var_CntctNum = $var_get["ContactNum"];
    $var_Email = $var_get["Email"];
    $profilePicture = $var_get["profilePic"];
    $var_year = date("Y");
    $var_MI = substr($var_Mname, 0, 1);
    $var_byear = substr($var_Date, 0, 4);
    $var_Age = $var_year - $var_byear;
    $var_Balance = $var_get["E_wallet"];

} else {
    echo "No records found";
}

if (isset($_POST["BtnSubmit"])) {
    $var_Balance += floatval($_POST["TxtMoney"]);

    $var_updt = "UPDATE tbl_user SET E_wallet= $var_Balance WHERE User_id =  $var_profid ";
    $var_upqry = mysqli_query($var_conn, $var_updt);
    header("location: ./ProfilePage.php");
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
    <link rel='stylesheet' type='text/css' media='screen' href='./node_modules/leaflet/dist/leaflet.css'>
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
                                <a class="nav-link fw-semibold text-center" aria-current="page" href="PatientSession.php">
                                    <i class="bi bi-hospital fs-3"></i><br>
                                    <small>Session</small>
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
                                <a class="nav-link fw-semibold text-center active" aria-current="page" href="./ProfilePage.php">
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

    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">E Wallet Top-up</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="ProfilePage.php" class="needs-validation" id="topUpForm" novalidate>
                        <h3>Balance: <span class="balance">₱<?php echo $var_Balance ?></span></h3>
                        <hr>
                        <label class="mb-1" for="TxtMoney">Top up</label>
                        <input type="number" name="TxtMoney" placeholder="₱" class="form-control" id="TxtMoney" required>
                        <div class="invalid-feedback">Please type in a balance.</div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary px-5 rounded-5" data-bs-dismiss="modal">Close</button>
                    <button type="submit" name="BtnSubmit" form="topUpForm" class="btn btn-primary px-5 rounded-5">Top-up</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade modal-xl" id="changeProfilePictureModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5">Update Profile Picture</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="./ProfilePage.php" id="changeProfilePictureForm" class="needs-validation" novalidate>
                        <div class="mb-4">
                            <div class="mb-3">
                                <label for="profilePicture" class="mb-1">Profile Picture <span class="text-danger">*</span></label>
                                <input type="file" name="profilePicture" id="profilePicture" class="form-control" accept="image/*" required>
                                <div class="invalid-feedback">
                                    Please choose a valid Profile Picture Image.
                                </div>
                            </div>
                            <div class="d-flex justify-content-center align-items-center flex-column d-block mb-3">
                                <small class="fw-semibold mb-2">Profile Picture Preview</small>
                                <?php

                                echo "<img src='./UserFiles/ProfilePictures/$profilePicture' id='profilePicturePreview' class='img-thumbnail' style='height: 250px; width: auto; object-fit: cover;'>";

                                ?>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary px-5 rounded-5" data-bs-dismiss="modal">Close</button>
                    <button type="submit" form="changeProfilePictureForm" class="btn btn-primary px-5 rounded-5">Save</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade modal-xl" id="changeMedicalHistoryPicturesModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5">Update Medical History Pictures</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="./ProfilePage.php" id="changeMedicalHistoryPicturesForm" class="needs-validation" novalidate>

                        <div class="mb-3">
                            <label for="medicalHistoryImage" class="mb-1">Medical History Image <span class="text-danger">*</span> <small class="fw-semibold">(Max Image: 3)</small></label>
                            <input type="file" name="medicalHistoryImage" id="medicalHistoryImage" class="form-control" accept="image/*" multiple required>
                            <div class="invalid-feedback">
                                Please choose a valid Medical History Image.
                            </div>
                        </div>
                        <div id="medicalHistoryPreviewContainer" class="d-flex justify-content-center align-items-center flex-column d-block mb-3">
                            <small class="fw-semibold mb-2">Medical History Image Preview</small>
                            <div class="d-flex justify-content-center align-items-center flex-column flex-md-row gap-2">
                            <?php

                            foreach ($var_Medpic as $names) {
                                echo "<img src='./UserFiles/PatientMedicalHistoryPictures/$names' class='medicalHistoryPreview img-thumbnail' style='height: 250px; width: auto; object-fit: cover;'>";
                            }

                            ?>
                            </div>
                        </div>

                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary px-5 rounded-5" data-bs-dismiss="modal">Close</button>
                    <button type="submit" form="changeMedicalHistoryPicturesForm" class="btn btn-primary px-5 rounded-5">Save</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade modal-xl" id="changeMedicalAssessmentPicturesModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5">Update Medical Assessment Pictures</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="./ProfilePage.php" id="changeMedicalAssessmentPicturesForm" class="needs-validation" novalidate>

                        <div class="mb-3">
                            <label for="assesmentImage" class="mb-1">Assesment Image <span class="text-danger">*</span> <small class="fw-semibold">(Max Image: 2)</small></label>
                            <input type="file" name="assesmentImage" id="assesmentImage" class="form-control" accept="image/*" multiple required>
                            <div class="invalid-feedback">
                                Please choose a valid Assesment Image.
                            </div>
                        </div>
                        <div id="assesmentPreviewContainer" class="d-flex justify-content-center align-items-center flex-column d-block mb-3">
                            <small class="fw-semibold mb-2">Assesment Image Preview</small>
                            <div class="d-flex justify-content-center align-items-center flex-column flex-md-row gap-2">
                                <?php
                                
                                foreach ($var_Assesment as $names) {
                                    echo "<img src='./UserFiles/PatientAssementPictures/$names' class='assesmentPreview img-thumbnail' style='height: 250px; width: auto; object-fit: cover;'>";
                                }

                                ?>
                            </div>
                        </div>

                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary px-5 rounded-5" data-bs-dismiss="modal">Close</button>
                    <button type="submit" form="changeMedicalAssessmentPicturesForm" class="btn btn-primary px-5 rounded-5">Save</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade modal-xl" id="changeUserInformationModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5">Update User Information</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="./ProfilePage.php" id="changeUserInformationForm" class="needs-validation" novalidate>

                        <div class="mb-3">
                            <label for="username" class="mb-1">Username <span class="text-danger">*</span> <small class="fw-semibold">(Max Length: 30)</small></label>
                            <input type="text" name="username" id="username" value="<?php echo $username ?>" class="form-control" required>
                            <div class="invalid-feedback">
                                Please choose a Username.
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="firstName" class="mb-1">First Name <span class="text-danger">*</span> <small class="fw-semibold">(Max Length: 50)</small></label>
                            <input type="text" name="firstName" id="firstName" value="<?php echo $var_Fname ?>" class="form-control" readonly required>
                            <div class="invalid-feedback">
                                Please choose a First Name.
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="middleName" class="mb-1">Middle Name <span class="text-danger">*</span> <small class="fw-semibold">(Max Length: 30)</small></label>
                            <input type="text" name="middleName" id="middleName" value="<?php echo $var_Mname ?>" class="form-control" readonly required>
                            <div class="invalid-feedback">
                                Please choose a Middle Name.
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="lastName" class="mb-1">Last Name <span class="text-danger">*</span> <small class="fw-semibold">(Max Length: 50)</small></label>
                            <input type="text" name="lastName" id="lastName" value="<?php echo $var_Lname ?>" class="form-control" readonly required>
                            <div class="invalid-feedback">
                                Please choose a Last Name.
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="birthDate" class="mb-1">Birthday <span class="text-danger">*</span></label>
                            <input type="date" name="birthDate" id="birthDate" value="<?php echo $var_Date ?>" class="form-control" required>
                            <div class="invalid-feedback">
                                Please enter a valid Birthday.
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="password" class="mb-1">Password <span class="text-danger">*</span> </label>
                            <div class="input-group">
                                <input type="password" name="password" id="password" class="form-control" required>
                                <button type="button" class="btn btn-primary viewPasswordButton rounded-end">
                                    <i class="bi bi-eye-fill"></i>
                                </button>
                                <div class="invalid-feedback">
                                    Please choose a Password.
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="passwordConfirmation" class="mb-1">Confirm Password <span class="text-danger">*</span> </label>
                            <div class="input-group">
                                <input type="password" name="passwordConfirmation" id="passwordConfirmation" class="form-control" required>
                                <button type="button" class="btn btn-primary viewPasswordButton rounded-end">
                                    <i class="bi bi-eye-fill"></i>
                                </button>
                            </div>
                            <small class="text-danger d-none mt-1" id="password-confirmation-feedback">
                                Password do not match.
                            </small>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="mb-1">Email <span class="text-danger">*</span> <small class="fw-semibold">(Max Length: 30)</small></label>
                            <input type="email" name="email" id="email" value="<?php echo $var_Email ?>"  class="form-control" required>
                            <div class="invalid-feedback">
                                Please enter a valid Email.
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="mobileNumber" class="mb-1">Mobile Number <span class="text-danger">*</span> <small class="fw-semibold">(Max Length: 11)</small></label>
                            <input type="text" name="mobileNumber" id="mobileNumber" value="<?php echo $var_CntctNum ?>"  class="form-control" required>
                            <div class="invalid-feedback">
                                Please enter a valid Mobile Number.
                            </div>
                        </div>

                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary px-5 rounded-5" data-bs-dismiss="modal">Close</button>
                    <button type="submit" form="changeUserInformationForm" class="btn btn-primary px-5 rounded-5">Save</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" tabindex="-1" id="map-modal">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Choose your location</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <small class="mb-2 fw-semibold">Click the map to choose a location</small>
                    <div id="map" style="width: 100%; height: 500px;"></div>
                    <div class="row gap-2 gap-md-0">
                        <div class="mt-2 col-md">
                            <label for="chosen-latitude" class="mb-1 fw-semibold">Latitude</label>
                            <input type="text" id="chosen-latitude" class="form-control form-control-sm" readonly>
                        </div>
                        <div class="mt-2 col-md">
                            <label for="chosen-longitude" class="mb-1 fw-semibold">Longitude</label>
                            <input type="text" id="chosen-longitude" class="form-control form-control-sm" readonly>
                        </div>
                    </div>
                    <div class="mt-2">
                        <label for="fullAddress">Full Address</label>
                        <input type="text" id="fullAddress" class="form-control form-control-sm" readonly>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary px-5 rounded-5" data-bs-target="#changePatientInformationModal" data-bs-toggle="modal" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary px-5 rounded-5" data-bs-target="#changePatientInformationModal" data-bs-toggle="modal" data-bs-dismiss="modal" id="save-location-btn">Save</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade modal-xl" id="changePatientInformationModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5">Update User Information</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="./ProfilePage.php" id="changePatientInformationForm" class="needs-validation" novalidate>

                        <div class="mb-3">
                            <label for="patientCase" class="mb-1">Patient Case <span class="text-danger">*</span> <small class="fw-semibold">(Use comma to separate your case, E.g.: Back Pain, Spine Injury,...)</small></label>
                            <textarea id="patientCase" name="patientCase" class="form-control" data-bs-toggle="dropdown" placeholder="Case 1, Case 2, Case 3,..." style="height: 17px;" required><?php echo $var_Case; ?></textarea>

                            <ul class="dropdown-menu" id="patientCaseList">
                                <?php

                                $sql = "SELECT case_handled FROM tbl_therapists;";
                                $results = mysqli_query($var_conn, $sql);

                                $cases = array();

                                foreach ($results as $result) {
                                    $caseArray = explode(",", $result["case_handled"]);

                                    foreach ($caseArray as $case) {
                                        array_push($cases, $case);
                                    }
                                }

                                sort($cases);

                                foreach (array_unique($cases) as $case) {
                                    echo "<li>
                                        <button type='button' class='case dropdown-item d-block text-capitalize'>$case</button>
                                    </li>";
                                }

                                ?>
                            </ul>
                            
                            <div class="invalid-feedback">
                                Please enter a Case.
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="patientCaseDescription" class="mb-1">Patient Case Description <span class="text-danger">*</span> <small class="fw-semibold">(Max Length: 200)</small></label>
                            <textarea style="height: 250px; max-height: 250px;" id="patientCaseDescription" name="patientCaseDescription" class="form-control" required><?php echo $var_CaseDesc; ?></textarea>
                            <div class="invalid-feedback">
                                Please enter a Case Description.
                            </div>
                        </div>
                        <div class="mb-2">
                            <label for="location" class="mb-1">Address <span class="text-danger">*</span> <small class="fw-semibold">(Use comma to seperate your Barangay and City, E.g: Lahug, Cebu City or press the Choose Location button to choose locate your place)</small></label>
                            <input type="text" name="location" id="location" value="<?php echo $fullAddress; ?>" class="form-control mb-3" placeholder="Barangay, City" required>
                            <div class="invalid-feedback">
                                Please enter a Location.
                            </div>
                        </div>
                        <div class="mb-3">
                            <button type="button" class="btn btn-secondary px-5 rounded-5" id="choose-location-btn" data-bs-dismiss="modal">Choose Location</button>
                        </div>

                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary px-5 rounded-5" data-bs-dismiss="modal">Close</button>
                    <button type="submit" form="changePatientInformationForm" class="btn btn-primary px-5 rounded-5">Save</button>
                </div>
            </div>
        </div>
    </div>

    <main class="py-0 py-sm-3">

        <section class="patient-view-section bg-secondary-subtle py-3 py-sm-5 px-3 px-sm-5 shadow container-fluid container-sm">

            <div class="row">
                
                <div class="col-md">

                    <div class="mb-3 d-flex justify-content-center align-items-center">
                        <img class="img-fluid rounded-5 shadow" style="height: 250px; width: auto;" src="./UserFiles/ProfilePictures/<?php echo $profilePicture ?>" alt="#">
                    </div>

                    <div class="d-flex justify-content-center align-items-center">
                        <button type="button" class="btn btn-primary px-5 rounded-5 shadow btn-sm" data-bs-target="#changeProfilePictureModal" data-bs-toggle="modal">
                            <i class="bi bi-pencil-square"></i>
                        </button>
                    </div>

                    <hr>

                    <h3 class="fw-semibold">User Information</h3>

                    <div class="mb-3">
                        <label class="mb-1">
                            <b>Full Name:</b>
                            <span class="text-capitalize"><?php echo $var_Fname . " " . $var_MI . ". " . $var_Lname; ?></span>
                        </label><br>
                        <label class="mb-1">
                            <b>Age:</b>
                            <span class="text-capitalize"><?php echo $var_Age; ?></span>
                        </label><br>
                        <label class="mb-1">
                            <b>Contact Number:</b>
                            <span class="text-capitalize"><?php echo $var_CntctNum; ?></span>
                        </label><br>
                        <label class="mb-1">
                            <b>Email:</b>
                            <span class="text-capitalize"><?php echo $var_Email; ?></span>
                        </label><br>
                        <div class="d-flex justify-content-start align-items-center">
                            <label>
                                <b>Balance:</b>
                                <span class="balance text-capitalize">₱<?php echo $var_Balance; ?></span>
                            </label>
                            <button type="button" class="btn btn-sm btn-outline-primary px-3 rounded-5 ms-1" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                Top Up
                            </button>
                        </div>
                    </div>

                    <div class="d-flex justify-content-start align-items-center">
                        <button type="button" class="btn btn-primary px-5 rounded-5 shadow btn-sm" data-bs-target="#changeUserInformationModal" data-bs-toggle="modal">
                            <i class="bi bi-pencil-square"></i>
                        </button>
                    </div>

                    <hr>
                    <h3 class="fw-semibold">Patient Information</h3>

                    <div class="mb-3">
                        <label class="mb-1">
                            <b>Case:</b>
                            <span class="text-capitalize"><?php echo $var_Case; ?></span>
                        </label><br>
                        <label class="mb-1">
                            <b>Description:</b>
                            <span><?php echo $var_CaseDesc; ?></span>
                        </label><br>
                        <label class="mb-1">
                            <b>City:</b>
                            <span><?php echo $var_City; ?></span>
                        </label><br>
                        <label>
                            <b>Barangay:</b>
                            <span><?php echo $var_Street; ?></span>
                        </label>
                    </div>

                    <div class="d-flex justify-content-start align-items-center">
                        <button type="button" class="btn btn-primary px-5 rounded-5 shadow btn-sm" data-bs-target="#changePatientInformationModal" data-bs-toggle="modal">
                            <i class="bi bi-pencil-square"></i>
                        </button>
                    </div>

                </div>

                <div class="col-md">
                    <hr>

                    <div class="row gap-4 gap-lg-0">

                        <div class="col-lg">

                            <div class="d-flex justify-content-center align-items-center mb-3">
                                <button type="button" class="btn btn-primary px-5 rounded-5 shadow btn-sm" data-bs-target="#changeMedicalHistoryPicturesModal" data-bs-toggle="modal">
                                    <i class="bi bi-pencil-square"></i>
                                </button>
                            </div>

                            <label class="mb-2"><b>Medical History</b></label>
                            <div class="d-flex justify-content-center align-items-center flex-column gap-2">
                                <?php 

                                foreach ($var_Medpic as $names) {
                                    echo "<img class='img-fluid rounded-5 shadow medicalImages' style='object-fit: cover; cursor: pointer;' src='./UserFiles/PatientMedicalHistoryPictures/$names' alt='$names'>";
                                }

                                ?>
                            </div>

                        </div>

                        <div class="col-lg">

                            <div class="d-flex justify-content-center align-items-center mb-3">
                                <button type="button" class="btn btn-primary px-5 rounded-5 shadow btn-sm" data-bs-target="#changeMedicalAssessmentPicturesModal" data-bs-toggle="modal">
                                    <i class="bi bi-pencil-square"></i>
                                </button>
                            </div>

                            <label class="mb-2"><b>Medical Assesment</b></label>
                            <div class="d-flex justify-content-center align-items-center flex-column gap-2">
                                <?php
                                
                                foreach ($var_Assesment as $names) {
                                    echo "<img class='img-fluid rounded-5 shadow medicalImages' style='object-fit: cover; cursor: pointer;' src='./UserFiles/PatientAssementPictures/$names' alt='$names'>";
                                }

                                ?>
                            </div>

                        </div>


                    </div>

                </div>

            </div>

        </section>

    </main>

    <script src="./node_modules/bootstrap/dist/js/bootstrap.bundle.js"></script>
    <script src='./node_modules/leaflet/dist/leaflet.js'></script>
    <script>
        (() => {
            const viewPasswordButton = document.getElementsByClassName("viewPasswordButton");

            Array.from(viewPasswordButton).forEach((button) => {
                button.addEventListener("click", () => {
                    const input = button.parentElement.getElementsByTagName("input")[0];

                    if (input.type === "password") {
                        input.type = "text";
                        button.innerHTML = `<i class="bi bi-eye-slash-fill"></i>`;
                    } else { 
                        input.type = "password";
                        button.innerHTML = `<i class="bi bi-eye-fill"></i>`;
                    }
                });
            });

            Array.from(document.getElementsByClassName("needs-validation")).forEach((form) => {
                form.addEventListener("submit", (e) => {
                    if (!form.checkValidity()) {
                        e.preventDefault();
                        e.stopPropagation();
                    }

                    form.classList.add("was-validated");
                });

                Array.from(form.querySelectorAll('button[data-bs-dismiss="modal"]')).forEach((closeButton) => {
                    closeButton.addEventListener("click", () => {
                        if (form.classList.contains("was-validated")) {
                            form.classList.remove("was-validated");
                        }
                    });
                });
            });

            Array.from(document.getElementsByClassName("medicalImages")).forEach((element) => {
                element.addEventListener("click", () => {
                    window.open(element.src);
                });
            });

            const profilePicture = document.getElementById("profilePicture");
            const profilePicturePreview = document.getElementById("profilePicturePreview");

            profilePicture.addEventListener("change", () => {
                const file = profilePicture.files[0];

                if (file) {
                    profilePicturePreview.parentElement.classList.replace("d-none", "d-block");
                    profilePicturePreview.src = URL.createObjectURL(file);
                } else {
                    profilePicturePreview.parentElement.classList.replace("d-block", "d-none");
                    profilePicturePreview.src = "#"
                }
            });

            const changeProfilePictureForm = document.getElementById("changeProfilePictureForm");

            changeProfilePictureForm.addEventListener("submit", async (e) => {
                e.preventDefault();
                e.stopPropagation();

                const file = changeProfilePictureForm.profilePicture.files[0];

                const formData = new FormData();
                formData.append("profilePicture[]", file);

                const response = await fetch("./ProfilePageAPI/change_profile_picture.php", {
                    method: "POST",
                    body: formData
                });

                const responseText = await response.text();

                showToast(responseText);

                setTimeout(() => {
                    window.location.reload();
                }, 1000);
            });

            const assesmentImage = document.getElementById("assesmentImage");
            const assesmentPreviewContainer = document.getElementById("assesmentPreviewContainer");
            const assesmentPreview = document.getElementsByClassName("assesmentPreview");

            assesmentImage.addEventListener("change", () => {
                const files = assesmentImage.files;

                if (!(files.length <= 1) && !(files.length >= 3)) {
                    let index = -1;

                    Array.from(assesmentPreview).forEach((element) => {
                        index++;

                        assesmentPreviewContainer.classList.replace("d-none", "d-block");
                        element.src = URL.createObjectURL(files[index]);
                    });
                } else {
                    Array.from(assesmentPreview).forEach((element) => {
                        assesmentPreviewContainer.classList.replace("d-block", "d-none");
                    });

                    assesmentImage.value = null;
                    showToast("<label class='text-danger'>Assesment Image Input must have 2 required images.</label>");
                }
            });

            const medicalHistoryImage = document.getElementById("medicalHistoryImage");
            const medicalHistoryPreviewContainer = document.getElementById("medicalHistoryPreviewContainer");
            const medicalHistoryPreview = document.getElementsByClassName("medicalHistoryPreview");

            medicalHistoryImage.addEventListener("change", () => {
                const files = medicalHistoryImage.files;

                if (!(files.length <= 2) && !(files.length >= 4)) { 
                    let index = -1;

                    Array.from(medicalHistoryPreview).forEach((element) => {
                        index++;

                        medicalHistoryPreviewContainer.classList.replace("d-none", "d-block");
                        element.src = URL.createObjectURL(files[index]);
                    });
                } else {
                    Array.from(medicalHistoryPreview).forEach((element) => {
                        medicalHistoryPreviewContainer.classList.replace("d-block", "d-none");
                    });
                    
                    medicalHistoryImage.value = null;
                    showToast("<label class='text-danger'>Medical History Image Input must have 3 required images.</label>");
                }
            });

            const changeMedicalHistoryPicturesForm = document.getElementById("changeMedicalHistoryPicturesForm");

            changeMedicalHistoryPicturesForm.addEventListener("submit", async (e) => {
                if (!changeMedicalHistoryPicturesForm.checkValidity()) {
                    return;
                }

                e.preventDefault();
                e.stopPropagation();

                const medicalHistoryImages = changeMedicalHistoryPicturesForm.medicalHistoryImage.files;

                const formData = new FormData();

                for (let image of medicalHistoryImages) {
                    formData.append("medicalHistoryImage[]", image);
                }

                const response = await fetch("./ProfilePageAPI/change_medical_history_pictures.php", {
                    method: "POST",
                    body: formData
                });

                const responseText = await response.text();

                showToast(responseText);

                setTimeout(() => {
                    window.location.reload();
                }, 1000);
            });

            const changeMedicalAssessmentPicturesForm = document.getElementById("changeMedicalAssessmentPicturesForm");

            changeMedicalAssessmentPicturesForm.addEventListener("submit", async (e) => {
                if (!changeMedicalAssessmentPicturesForm.checkValidity()) {
                    return;
                }

                e.preventDefault();
                e.stopPropagation();

                const assesmentImage = changeMedicalAssessmentPicturesForm.assesmentImage.files;

                const formData = new FormData();

                for (let image of assesmentImage) {
                    formData.append("assesmentImage[]", image);
                }

                const response = await fetch("./ProfilePageAPI/change_medical_assessment_pictures.php", {
                    method: "POST",
                    body: formData
                });

                const responseText = await response.text();

                showToast(responseText);

                setTimeout(() => {
                    window.location.reload();
                }, 1000);
            });

            const changeUserInformationForm = document.getElementById("changeUserInformationForm");
            const passwordConfirmationFeedback = document.getElementById("password-confirmation-feedback");

            changeUserInformationForm.addEventListener("submit", async (e) => {
                if (!changeUserInformationForm.checkValidity()) {
                    return;
                }

                e.preventDefault();
                e.stopPropagation();

                const username = changeUserInformationForm.username.value;
                const firstName = changeUserInformationForm.firstName.value;
                const middleName = changeUserInformationForm.middleName.value;
                const lastName = changeUserInformationForm.lastName.value;

                const birthDate = changeUserInformationForm.birthDate.value;

                const password = changeUserInformationForm.password.value;
                const passwordConfirmation = changeUserInformationForm.passwordConfirmation.value;
                
                const email = changeUserInformationForm.email.value;
                const mobileNumber = changeUserInformationForm.mobileNumber.value;

                if (password !== passwordConfirmation) {
                    passwordConfirmationFeedback.classList.replace("d-none", "d-block");
                    showToast("<span class='text-danger'>Password do not match.</span>");
                    return;
                } else {
                    passwordConfirmationFeedback.classList.replace("d-block", "d-none");
                }

                if (
                    username.length >= 30 ||
                    firstName.length >= 50 ||
                    middleName.length >= 30 ||
                    lastName.length >= 50 ||
                    password.length >= 30 ||
                    email.length >= 30 ||
                    mobileNumber.length > 11
                ) {
                    showToast("<span class='text-danger'>Please follow the given max length of each inputs.</span>");
                    return;
                }

                const formData = new FormData();
                formData.append("username", username);
                formData.append("firstName", firstName);
                formData.append("middleName", middleName);
                formData.append("lastName", lastName);
                formData.append("birthDate", birthDate);
                formData.append("password", password);
                formData.append("passwordConfirmation", passwordConfirmation);
                formData.append("email", email);
                formData.append("mobileNumber", mobileNumber);

                const response = await fetch("./ProfilePageAPI/update_user_information.php", {
                    method: "POST",
                    body: formData
                });

                const responseText = await response.text();

                showToast(responseText);

                setTimeout(() => {
                    window.location.reload();
                }, 1000);
            });

            const patientCaseList = document.getElementById("patientCaseList");

            Array.from(patientCaseList.getElementsByClassName("case")).forEach((element) => {
                element.addEventListener("click", () => {
                    patientCase.value += element.innerHTML;
                });
            });

            patientCase.oninput = () => {
                const value = patientCase.value.split(",").map((word) => {
                    const characterArray = word.split("");

                    if (characterArray[0] === " ") {
                        characterArray.shift();
                        return characterArray.join("");
                    } else {
                        return characterArray.join("");
                    }
                }).at(-1);

                const patientCaseListElement = patientCaseList.getElementsByClassName("case");
                const cases = [...patientCaseListElement].map((item) => { return item.innerHTML });

                for (element of patientCaseListElement) {
                    if (element.innerHTML.includes(value)) {
                        element.classList.replace("d-none", "d-block");
                    } else {
                        element.classList.replace("d-block", "d-none");
                    }
                }
            }
            
            const changePatientInformationForm = document.getElementById("changePatientInformationForm");

            const chooseLocationBtn = document.getElementById("choose-location-btn");
            const mapModal = document.getElementById("map-modal");
            const chosenLatitude = document.getElementById("chosen-latitude");
            const chosenLongitude = document.getElementById("chosen-longitude");
            const saveLocationBtn = document.getElementById("save-location-btn");
            const fullAddressInput = document.getElementById("fullAddress");

            let map;

            chooseLocationBtn.addEventListener("click", () => {
                const modal = new bootstrap.Modal(mapModal);

                modal.show();
                
                setTimeout(() => {
                    window.dispatchEvent(new Event("resize"));
                }, 500);
            });

            if (!navigator.geolocation) {
                map.innerHTML = "<small>Geolocation is not supported by this browser.</small>";
            }

            navigator.geolocation.getCurrentPosition((position) => {
                latitude = position.coords.latitude;
                longitude = position.coords.longitude;

                chosenLatitude.value = latitude;
                chosenLongitude.value = longitude;

                const map = L.map("map", {
                    center: [
                        latitude, longitude
                    ],
                    zoom: 14
                });

                const marker = L.marker([latitude, longitude])
                    .addTo(map)
                    .bindPopup("Your current location.")
                    .openPopup();

                let chosenMarker;

                map.on("click", (e) => {
                    const latitudeLongitude = map.mouseEventToLatLng(e.originalEvent);
                    latitude = latitudeLongitude.lat;
                    longitude = latitudeLongitude.lng;

                    map.removeLayer(marker);

                    if (chosenMarker) {
                        map.removeLayer(chosenMarker);
                        chosenLatitude.value = latitude;
                        chosenLongitude.value = longitude;

                        const apiUrl = `https://geocode.maps.co/reverse?lat=${latitude}&lon=${longitude}&api_key=66ffa02e9542d122573242pqy09573f`;

                        fullAddressInput.placeholder = "Fetching location, please wait...";

                        setTimeout(async () => {
                            const response = await fetch(apiUrl, {
                                method: "GET"
                            });

                            const responseStatusCode = response.status;

                            if (responseStatusCode === 200) {
                                const json = await response.json();
                                const barangay = json.address.quarter;
                                const city = json.address.city;

                                fullAddressInput.value = `${barangay}, ${city}`;
                                fullAddressInput.placeholder = "";
                            }
                        }, 1000);
                    }

                    chosenMarker = L.marker([latitude, longitude]).addTo(map);
                });

                L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
                }).addTo(map);
            }, showError);

            saveLocationBtn.addEventListener("click", () => {
                changePatientInformationForm.location.value = fullAddressInput.value;
            });

            changePatientInformationForm.addEventListener("submit", async (e) => {
                if (!changePatientInformationForm.checkValidity()) {
                    return;
                }

                e.preventDefault();
                e.stopPropagation();

                const patientCase = changePatientInformationForm.patientCase.value;
                const patientCaseDescription = changePatientInformationForm.patientCaseDescription.value;
                const location = changePatientInformationForm.location.value;
                
                const city = location.split(",")[1];
                const barangay = location.split(",")[0];

                const formData = new FormData();
                formData.append("patientCase", patientCase);
                formData.append("patientCaseDescription", patientCaseDescription);
                formData.append("city", city);
                formData.append("barangay", barangay);

                const response = await fetch("./ProfilePageAPI/update_patient_information.php", {
                    method: "POST",
                    body: formData
                });

                const responseText = await response.text();

                showToast(responseText);

                setTimeout(() => {
                    window.location.reload();
                }, 1000);
            });
        })();

        window.onload = () => {
            Array.from(document.getElementsByClassName("balance")).forEach((balance) => {
                const newValue = formatCurrency(balance.innerHTML);
                balance.innerHTML = newValue;
            });
        }

        function formatCurrency(value) {
            value = value.replace(/[^\d.-]/g, '');
            return new Intl.NumberFormat('en-US', {
                style: 'currency',
                currency: 'PHP',
            }).format(value || 0);
        }

        function showToast(message) {
            const toastElement = document.getElementById("toast");
            const toast = new bootstrap.Toast(toastElement);

            toastElement.getElementsByClassName("toast-body")[0].innerHTML = message;

            toast.show();
        }

        function showError(error) {
            switch (error.code) {
                case error.PERMISSION_DENIED:
                    alert("User denied the request for Geolocation.");
                    break;
                case error.POSITION_UNAVAILABLE:
                    alert("Location information is unavailable.");
                    break;
                case error.TIMEOUT:
                    alert("The request to get user location timed out.");
                    break;
                case error.UNKNOWN_ERROR:
                    alert("An unknown error occurred.");
                    break;
            }
        }
    </script>
</body>

</html>