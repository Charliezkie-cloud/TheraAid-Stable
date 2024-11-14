<?php

include "./database.php";

session_start();

if (!isset($_SESSION["sess_id"])) {
    header("location: landingPage.php");
    exit();
}

$var_profid = intval($_SESSION["sess_id"]);
$var_qry = "SELECT
                *,
                CONCAT(t.barangay, ',', t.city) AS full_address
            FROM tbl_user u JOIN tbl_therapists t ON u.User_id = t.user_id
            WHERE t.User_id ='$var_profid';";
$var_chk = mysqli_query($var_conn, $var_qry);
$var_Fname = "";
$var_Lname = "";
$var_Mname = "";
$var_MI = "";
$var_Age = "";
$var_CntctNum = "";
$var_Email = "";
$var_CaseHndld = "";
$var_City = "";
$var_Radius = "";
$var_LicenseIMG = "";

$profilePic = "";

if (mysqli_num_rows($var_chk) > 0) {
    $var_get = mysqli_fetch_array($var_chk);

    $var_CaseHndld = implode(", ", explode(",", $var_get["case_handled"]));

    $username = $var_get["UserName"];
    $var_City = $var_get["city"];
    $var_Radius = $var_get["Radius"];
    $var_LicenseIMG = $var_get["license_img"];
    $var_Fname = $var_get["Fname"];
    $var_Lname = $var_get["Lname"];
    $var_Mname = $var_get["Mname"];
    $var_Date = $var_get["Bday"];
    $var_CntctNum = $var_get["ContactNum"];
    $var_Email = $var_get["Email"];
    $profilePic = $var_get["profilePic"];
    $balance = $var_get["E_wallet"];
    $var_year = date("Y");
    $var_MI = substr($var_Mname, 0, 1);
    $var_byear = substr($var_Date, 0, 4);
    $var_Age = $var_year - $var_byear;

    $certificate = $var_get["certificate"];
    $contract = $var_get["contract"];

    $full_address = $var_get["full_address"];

} else {
    echo "No records found";
}

$var_UId = $var_get['User_id'];

if (isset($_POST["BtnSubmit"])) {
    $balance += floatval($_POST["TxtMoney"]);
    $var_updt = "UPDATE tbl_user SET E_wallet= $balance WHERE User_id =  $var_UId ";
    $var_upqry = mysqli_query($var_conn, $var_updt);
    header("location: ./TherapistsProfilePage.php");
}

?>
<!DOCTYPE html>
<html data-bs-theme="light">
<head>
    <meta charset='utf-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <title>TheraAid | Therapist Profile</title>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <link rel='stylesheet' type='text/css' media='screen' href='./assets/css/TherapistHomePage.css'>
    <link rel='stylesheet' type='text/css' media='screen' href='./node_modules/leaflet/dist/leaflet.css'>
    <link rel='stylesheet' type='text/css' media='screen' href='./node_modules/easymde/dist/easymde.min.css'>
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
                                <a class="nav-link fw-semibold text-center active" aria-current="page" href="./TherapistsProfilePage.php">
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
    
    <div class="modal fade" tabindex="-1" id="map-modal">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Choose your location</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <small class="mb-2 fw-semibold">Click the map to choose a location</small>
                    <div id="map" style="width: 100%; height: 500px;" class="mb-2"></div>
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
                    <button type="button" class="btn btn-secondary px-5 rounded-5" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary px-5 rounded-5" data-bs-dismiss="modal" data-bs-target="#updateTherapistInformationModal" data-bs-toggle="modal" id="save-location-btn">Save</button>
                </div>
            </div>
        </div>
    </div>

    <div class="toast-container position-fixed bottom-0 end-0 p-3">
        <div id="toast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header">
                <strong class="me-auto">Notification</strong>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body"></div>
        </div>
    </div>

    <div class="modal fade" id="topUpModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5">Top Up Modal</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="TherapistsProfilePage.php" id="topUpForm" class="needs-validation" novalidate>
                        <h3 class="fw-semibold">Balance: <span class="balance"><?php echo $balance; ?></span></h3>
                        <div class="mb-3">
                            <label class="mb-1">Top up</label>
                            <input type="text" name="TxtMoney" class="form-control" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" form="topUpForm" name="BtnSubmit" class="btn btn-primary">Top-up</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal modal-xl fade" id="changeProfilePictureModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5">Update Profile Picture</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="TherapistsProfilePage.php" id="changeProfilePictureForm" class="needs-validation" novalidate>

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
                                <img src="./UserFiles/ProfilePictures/<?php echo $profilePic; ?>" id="profilePicturePreview" class="img-thumbnail" style="height: 250px; width: auto; object-fit: cover;">
                            </div>
                        </div>

                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" form="changeProfilePictureForm" name="BtnSubmit" class="btn btn-primary">Save</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal modal-xl fade" id="changeLicenseImageModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5">Update License Image</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="TherapistsProfilePage.php" id="changeLicenseImageForm" class="needs-validation" novalidate>

                        <div class="mb-3">
                            <label for="licenseImage" class="mb-1">License Image <span class="text-danger">*</span></label>
                            <input type="file" name="licenseImage" id="licenseImage" class="form-control" accept="image/*" required>
                            <div class="invalid-feedback">
                                Please choose a valid License Image.
                            </div>
                        </div>
                        <div class="d-flex justify-content-center align-items-center flex-column d-block mb-3">
                            <small class="fw-semibold mb-2">License Image Preview</small>
                            <img src="./UserFiles/LicensePictures/<?php echo $var_LicenseIMG; ?>" id="licensePreview" class="img-thumbnail" style="height: 250px; width: auto; object-fit: cover;">
                        </div>

                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" form="changeLicenseImageForm" name="BtnSubmit" class="btn btn-primary">Save</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal modal-xl fade" id="updateUserInformationModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5">Update User Information</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="TherapistsProfilePage.php" id="updateUserInformationForm" class="needs-validation" novalidate>

                        <div class="mb-3">
                            <label for="username" class="mb-1">Username <span class="text-danger">*</span> <small class="fw-semibold">(Max Length: 30)</small></label>
                            <input type="text" name="username" value="<?php echo $username; ?>" id="username" class="form-control" required>
                            <div class="invalid-feedback">
                                Please choose a Username.
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="firstName" class="mb-1">First Name <span class="text-danger">*</span> <small class="fw-semibold">(Max Length: 50)</small></label>
                            <input type="text" name="firstName" value="<?php echo $var_Fname; ?>" id="firstName" class="form-control" readonly required>
                            <div class="invalid-feedback">
                                Please choose a First Name.
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="middleName" class="mb-1">Middle Name <span class="text-danger">*</span> <small class="fw-semibold">(Max Length: 30)</small></label>
                            <input type="text" name="middleName" value="<?php echo $var_MI; ?>" id="middleName" class="form-control" readonly required>
                            <div class="invalid-feedback">
                                Please choose a Middle Name.
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="lastName" class="mb-1">Last Name <span class="text-danger">*</span> <small class="fw-semibold">(Max Length: 50)</small></label>
                            <input type="text" name="lastName" value="<?php echo $var_Lname; ?>" id="lastName" class="form-control" reaonly required>
                            <div class="invalid-feedback">
                                Please choose a Last Name.
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="password" class="mb-1">Password <span class="text-danger">*</span></label>
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
                            <label for="passwordConfirmation" class="mb-1">Confirm Password <span class="text-danger">*</span></label>
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
                            <input type="email" name="email" value="<?php echo $var_Email; ?>" id="email" class="form-control" required>
                            <div class="invalid-feedback">
                                Please enter a valid Email.
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="mobileNumber" class="mb-1">Mobile Number <span class="text-danger">*</span> <small class="fw-semibold">(Max Length: 11)</small></label>
                            <input type="text" name="mobileNumber" value="<?php echo $var_CntctNum; ?>" id="mobileNumber" class="form-control" required>
                            <div class="invalid-feedback">
                                Please enter a valid Mobile Number.
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="birthDate" class="mb-1">Birthday <span class="text-danger">*</span></label>
                            <input type="date" name="birthDate" value="<?php echo $var_Date; ?>" id="birthDate" class="form-control" required>
                            <div class="invalid-feedback">
                                Please enter a valid Birthday.
                            </div>
                        </div>

                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" form="updateUserInformationForm" name="BtnSubmit" class="btn btn-primary">Save</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal modal-xl fade" id="updateTherapistInformationModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5">Update Therapist Information</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="TherapistsProfilePage.php" id="updateTherapistInformationForm" class="needs-validation" novalidate>

                        <div class="mb-3">
                            <label for="caseHandled" class="mb-1">Case Handled <span class="text-danger">*</span> <small class="fw-semibold">(Use comma to separate your case handled, E.g.: Back Pain, Spine Injury,...)</small></label>
                            <textarea style="height: 17px; max-height: 250px;" id="caseHandled" name="caseHandled" class="form-control" data-bs-toggle="dropdown" placeholder="Case 1, Case 2, Case 3,..." required><?php echo $var_CaseHndld; ?></textarea>

                            <ul class="dropdown-menu" id="caseHandledList">
                                <?php

                                $sql = "SELECT P_case FROM tbl_patient;";
                                $results = mysqli_query($var_conn, $sql);

                                $cases = array();

                                foreach ($results as $result) {
                                    $caseArray = explode(",", $result["P_case"]);

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
                        <div class="mb-2">
                            <label for="location" class="mb-1">Address <span class="text-danger">*</span> <small class="fw-semibold">(Use comma to seperate your Barangay and City, E.g: Lahug, Cebu City or press the Choose Location button to choose locate your place)</small></label>
                            <input type="text" name="location" id="location" class="form-control mb-3" placeholder="Barangay, City" value="<?php echo $full_address; ?>" required>
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
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" form="updateTherapistInformationForm" name="BtnSubmit" class="btn btn-primary">Save</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal modal-xl fade" id="viewCertificateModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5">Your Certificate</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <?php

                    if ($certificate !== null) {
                        echo "<embed src='./UserFiles/Certificates/$certificate' class='border-0 w-100' style='height: 100vh;'/>";
                    } else {
                        echo "<div class='d-flex justify-content-center align-items-center flex-column gap-3 py-2'>
                            <h3>Oops! Looks like you haven't uploaded a file yet</h3>
                            <button type='submit'class='btn btn-outline-primary px-5 rounded-5 shadow' data-bs-target='#updateContractCertificateModal' data-bs-toggle='modal' data-bs-dismiss='modal'>Upload your Certificate Now</button>
                        </div>";
                    }

                    ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" data-bs-target='#updateContractCertificateModal' data-bs-toggle='modal' data-bs-dismiss='modal'>Update</button>
                    <button type="button" class="btn btn-danger" data-bs-target='#deleteCertificateOptionModal' data-bs-toggle='modal' data-bs-dismiss='modal'>Delete</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="deleteCertificateOptionModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5">Delete Certificate</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Do you wish to delete your certificate? Please be aware that this action is irreversible. Ensure that you save a copy of your certificate before proceeding with deletion.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" data-bs-target='#viewCertificateModal' data-bs-toggle='modal' data-bs-dismiss="modal" id="deleteCertificateButton">Yes</button>
                    <button type="button" class="btn btn-danger" data-bs-target='#viewCertificateModal' data-bs-toggle='modal' data-bs-dismiss="modal">No</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal modal-xl fade" id="viewContractModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5">Your Contract</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <?php

                    if ($contract !== null) {
                        echo "<div id='contractPreviewContainer'></div>";
                    } else {
                        echo "<div class='d-flex justify-content-center align-items-center flex-column gap-3 py-2'>
                            <h3>Oops! Looks like you haven't uploaded a file yet</h3>
                            <button type='submit'class='btn btn-outline-primary px-5 rounded-5 shadow' data-bs-target='#updateContractCertificateModal' data-bs-toggle='modal' data-bs-dismiss='modal'>Upload your Contract Now</button>
                        </div>";
                    }

                    ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" data-bs-target='#updateContractCertificateModal' data-bs-toggle='modal' data-bs-dismiss='modal'>Update</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal modal-xl fade" id="updateContractCertificateModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5">Update Contract & Certificate</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form class="needs-validation" id="updateContractCertificateForm" novalidate>
                        <div class="mb-3">
                            <label class="mb-1">Certificate: <span class="fw-semibold">(Kindly refrain from uploading files in any format other than PDF.)</span></label><br>
                            <input type="file" name="certificate" accept="application/pdf" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="mb-1">Contract: <span class="fw-semibold">(Minimum words: 10)</span></label><br>
                            <textarea class="form-control" name="contract" id="contractTextbox" required></textarea>
                        </div>

                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" form="updateContractCertificateForm">Save Changes</button>
                </div>
            </div>
        </div>
    </div>

    <main class="py-0 py-sm-3">

        <section class="main-section bg-secondary-subtle py-3 py-sm-5 px-3 px-sm-5 shadow container">

            <div class="row">

                <div class="col-md">
                    <h3 class="fw-semibold">User Information</h3>
                    <hr>

                    <div class="mb-3 d-flex justify-content-center align-items-center">
                        <img id="ProfPic" class="img-fluid rounded-5 shadow" style="height: 250px; width: auto;" src="./UserFiles/ProfilePictures/<?php echo $profilePic; ?>" alt="<?php echo $profilePic; ?>">
                    </div>

                    <div class="d-flex justify-content-center align-items-center">
                        <button type="button" class="btn btn-primary px-5 rounded-5 shadow btn-sm" data-bs-target="#changeProfilePictureModal" data-bs-toggle="modal">
                            <i class="bi bi-pencil-square"></i>
                        </button>
                    </div>

                    <hr>

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
                            <b>Mobile Number:</b>
                            <span><?php echo $var_CntctNum; ?></span>
                        </label><br>
                        <label class="mb-1">
                            <b>Email:</b>
                            <span><?php echo $var_Email; ?></span>
                        </label><br>
                        <label class="mb-1 d-flex justify-content-start align-items-center flex-row gap-1">
                            <b>Balance:</b>
                            <span class="balance"><?php echo $balance; ?></span>
                            <button type="button" class="btn btn-outline-primary btn-sm rounded-5" data-bs-toggle="modal" data-bs-target="#topUpModal">Top Up</button>
                        </label>
                        <label>
                            <b>User ID:</b>
                            <span><?php echo $var_get["User_id"]; ?></span>
                        </label><br>
                    </div>

                    <div class="d-flex justify-content-start align-items-center">
                        <button type="button" class="btn btn-primary px-5 rounded-5 shadow btn-sm" data-bs-target="#updateUserInformationModal" data-bs-toggle="modal">
                            <i class="bi bi-pencil-square"></i>
                        </button>
                    </div>

                </div>

                <div class="col-md">
                    <h3 class="fw-semibold">Therapist Information</h3>
                    <hr>

                    <div class="mb-3">
                        <label class="mb-1">
                            <b>Case Handled:</b>
                            <span><?php echo $var_CaseHndld; ?></span>
                        </label><br>
                        <label class="mb-1">
                            <b>City:</b>
                            <span><?php echo $var_City; ?></span>
                        </label><br>
                        <label class="mb-1">
                            <b>Barangay:</b>
                            <span><?php echo $var_get["barangay"]; ?></span>
                        </label>
                    </div>

                    <div class="d-flex justify-content-start align-items-center">
                        <button type="button" class="btn btn-primary px-5 rounded-5 shadow btn-sm" data-bs-target="#updateTherapistInformationModal" data-bs-toggle="modal">
                            <i class="bi bi-pencil-square"></i>
                        </button>
                    </div>

                    <hr>

                    <label class="mb-3 fw-semibold">Contract and Certificate:</label>

                    <div class="mb-3">
                        <button type="button" class="btn btn-primary px-5 rounded-5 shadow" data-bs-target="#viewCertificateModal" data-bs-toggle="modal">View Certificate</button>
                        <button type="button" class="btn btn-primary px-5 rounded-5 shadow" data-bs-target="#viewContractModal" data-bs-toggle="modal">View Contract</button>
                    </div>

                    <div class="d-flex justify-content-start align-items-center">
                        <button type="button" class="btn btn-primary px-5 rounded-5 shadow btn-sm" data-bs-target="#updateContractCertificateModal" data-bs-toggle="modal">
                            <i class="bi bi-pencil-square"></i>
                        </button>
                    </div>

                    <hr>

                    <div class="d-flex justify-content-end align-items-center">
                        <button type="button" class="btn btn-primary px-5 rounded-5 shadow btn-sm" data-bs-target="#changeLicenseImageModal" data-bs-toggle="modal">
                            <i class="bi bi-pencil-square"></i>
                        </button>
                    </div>

                    <div class="d-flex justify-content-center align-items-start flex-column">
                        <label class="mb-2 fw-semibold">License Image:</label>
                        <img class="img-fluid licenseImage rounded shadow" style="object-fit: cover; cursor: pointer;" src="./UserFiles/LicensePictures/<?php echo $var_LicenseIMG; ?>" alt="<?php echo $var_LicenseIMG; ?>">
                    </div>
                </l>

            </div>

        </section>

    </main>

    <script src="./node_modules/bootstrap/dist/js/bootstrap.bundle.js"></script>
    <script src='./node_modules/leaflet/dist/leaflet.js'></script>
    <script src='./node_modules/easymde/dist/easymde.min.js'></script>
    <script src='./node_modules/markdown-it/dist/markdown-it.min.js'></script>
    <script>
        const markdown = window.markdownit();

        let contractTextbox;

        document.addEventListener("DOMContentLoaded", () => {
            const deleteCertificateButton = document.getElementById("deleteCertificateButton");

            deleteCertificateButton.addEventListener("click", async () => {
                const formData = new FormData();
                formData.append("userID", "<?php echo $var_profid ?>");

                const response = await fetch("./TherapistsProfilePageAPI/delete_certificate.php", {
                    method: "POST",
                    body: formData
                });

                const responseText = await response.text();

                showToast(responseText);

                setTimeout(() => {
                    window.location.reload();
                }, 1000);
            });

            (async () => {
                const response = await fetch("./TherapistsProfilePageAPI/get_contract.php?userID=<?php echo $var_profid; ?>");

                const responseText = await response.text();

                if (document.getElementById("contractPreviewContainer")) {
                    const contractPreviewContainer = document.getElementById("contractPreviewContainer");

                    contractPreviewContainer.innerHTML = markdown.render(responseText);
                }
            })();

            contractTextbox = new EasyMDE({element: document.getElementById('contractTextbox')});

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
            });

            Array.from(document.getElementsByClassName("balance")).forEach((element) => {
                element.innerHTML = formatCurrency(element.innerHTML);
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
                if (!changeProfilePictureForm.checkValidity()) {
                    return;
                }

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

            const licenseImage = document.getElementById("licenseImage");
            const licensePreview = document.getElementById("licensePreview");

            licenseImage.addEventListener("change", () => {
                const file = licenseImage.files[0];

                if (file) {
                    licensePreview.parentElement.classList.replace("d-none", "d-block");
                    licensePreview.src = URL.createObjectURL(file);
                } else {
                    licensePreview.parentElement.classList.replace("d-block", "d-none");
                    licensePreview.src = "#"
                }
            });

            const changeLicenseImageForm = document.getElementById("changeLicenseImageForm");

            changeLicenseImageForm.addEventListener("submit", async (e) => {
                if (!changeLicenseImageForm.checkValidity()) {
                    return;
                }

                e.preventDefault();
                e.stopPropagation();

                const file = changeLicenseImageForm.licenseImage.files[0];

                const formData = new FormData();
                formData.append("licenseImage[]", file);

                const response = await fetch("./TherapistsProfilePageAPI/change_license_image.php", {
                    method: "POST",
                    body: formData
                });

                const responseText = await response.text();

                showToast(responseText);

                setTimeout(() => {
                    window.location.reload();
                }, 1000);
            });

            const updateUserInformationForm = document.getElementById("updateUserInformationForm");

            updateUserInformationForm.addEventListener("submit", async (e) => {
                if (!updateUserInformationForm.checkValidity()) {
                    return;
                }

                e.preventDefault();
                e.stopPropagation();

                const username = updateUserInformationForm.username.value;
                const firstName = updateUserInformationForm.firstName.value;
                const middleName = updateUserInformationForm.middleName.value;
                const lastName = updateUserInformationForm.lastName.value;
                const birthDate = updateUserInformationForm.birthDate.value;

                const password = updateUserInformationForm.password.value;
                const passwordConfirmation = updateUserInformationForm.passwordConfirmation.value;
                
                const email = updateUserInformationForm.email.value;
                const mobileNumber = updateUserInformationForm.mobileNumber.value;

                if (
                    username.length >= 30 ||
                    firstName.length >= 50 ||
                    middleName.length >= 30 ||
                    lastName.length >= 50 ||
                    password.length >= 30 ||
                    email.length >= 30 ||
                    mobileNumber.length > 11
                ) {
                    return showToast("<span class='text-danger'>Please follow the given max length of each inputs.</span>");
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

            const caseHandled = document.getElementById("caseHandled");
            const caseHandledList = document.getElementById("caseHandledList");

            Array.from(caseHandledList.getElementsByClassName("case")).forEach((element) => {
                element.addEventListener("click", () => {
                    caseHandled.value += element.innerHTML;
                });
            });

            caseHandled.oninput = () => {
                const value = caseHandled.value.split(",").map((word) => {
                    const characterArray = word.split("");

                    if (characterArray[0] === " ") {
                        characterArray.shift();
                        return characterArray.join("");
                    } else {
                        return characterArray.join("");
                    }
                }).at(-1);

                const caseHandledListElement = caseHandledList.getElementsByClassName("case");
                const cases = [...caseHandledListElement].map((item) => { return item.innerHTML });

                for (element of caseHandledListElement) {
                    if (element.innerHTML.includes(value)) {
                        element.classList.replace("d-none", "d-block");
                    } else {
                        element.classList.replace("d-block", "d-none");
                    }
                }
            }
            
            const updateTherapistInformationForm = document.getElementById("updateTherapistInformationForm");

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

                        apiUrl = `https://geocode.maps.co/reverse?lat=${latitude}&lon=${longitude}&api_key=66ffa02e9542d122573242pqy09573f`;

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
                updateTherapistInformationForm.location.value = fullAddressInput.value;
            });

            updateTherapistInformationForm.addEventListener("submit", async (e) => {
                if (!updateTherapistInformationForm.checkValidity()) {
                    return;
                }

                e.preventDefault();
                e.stopPropagation();

                const caseHandled = updateTherapistInformationForm.caseHandled.value;
                const location = updateTherapistInformationForm.location.value;

                const city = location.split(",")[1];
                const barangay = location.split(",")[0];

                const formData = new FormData();
                formData.append("caseHandled", caseHandled);
                formData.append("barangay", barangay);
                formData.append("city", city);

                const response = await fetch("./TherapistsProfilePageAPI/update_therapist_information.php", {
                    method: "POST",
                    body: formData
                });

                const responseText = await response.text();

                showToast(responseText);

                setTimeout(() => {
                    window.location.reload();
                }, 1000);
            });

            const updateContractCertificateForm = document.getElementById("updateContractCertificateForm");

            updateContractCertificateForm.addEventListener("submit", async (e) => {
                if (!updateContractCertificateForm.checkValidity()) {
                    return;
                }

                e.preventDefault();
                e.stopPropagation();

                const certificate = updateContractCertificateForm.certificate.files[0];
                const contract = contractTextbox.value();

                const formData = new FormData();
                formData.append("certificate", certificate, certificate.name);
                formData.append("contract", contract);

                if (contract.split(" ").length < 10 || certificate.name.split(".").indexOf("pdf") < 0) {
                    return showToast("Kindly adhere to the provided instructions for each input.");
                }

                const response = await fetch("./TherapistsProfilePageAPI/update_certificate_contract.php", {
                    method: "POST",
                    body: formData
                });

                const responseText = await response.text();

                showToast(responseText);

                if (response.status === 200) {
                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                }
            });
        });

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