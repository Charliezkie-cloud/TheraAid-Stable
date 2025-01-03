<?php
include("../database.php");
session_start();
$var_AdminID = $_SESSION["Sess_AdminID"];

$var_updatesuccess = "";
// Modify the SQL query based on the filter type

    $var_all = "SELECT u.User_id, 
                CONCAT(u.Fname,' ',u.Mname,' ',u.Lname) AS 'Fullname',
                u.Email,
                u.ContactNum,
                t.license_img,
                t.therapist_id
                FROM tbl_user u
                LEFT JOIN tbl_therapists t ON u.User_id = t.user_id
                LEFT JOIN tbl_appointment a ON t.therapist_id = a.therapists_id
                WHERE t.validate = 0
                ORDER BY u.User_id ASC";

$var_allqry = mysqli_query($var_conn, $var_all);

if(isset($_POST["Btnvalidate"])){
    $var_valiD = $_POST["Btnvalidate"];

   $var_update = "UPDATE tbl_therapists 
                SET
                 validate =1
                WHERE therapist_id = $var_valiD";
    $var_updateqry = mysqli_query($var_conn,$var_update);
    if($var_updateqry){
        $var_updatesuccess = true;
    }
}
?>

<!DOCTYPE html>
<html data-bs-theme="light">

<head>
    <meta charset='utf-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <title>TheraAid | Validate</title>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <link rel='stylesheet' type='text/css' media='screen' href='../assets/css/TherapistHomePage.css'>
</head>

<body>

<header>
        <nav class="navbar navbar-expand-lg bg-primary-subtle">
            <div class="container">
                <a class="navbar-brand" href="#">
                    <img src="../assets/img/Logo.jpg" class="rounded-pill shadow" alt="Logo.jpg" width="64" height="64">
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
                                <a class="nav-link fw-semibold text-center active" aria-current="page" href="./AdminHomePage.php">
                                    <i class="bi bi-house fs-3"></i><br>
                                    <small>Home</small>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link fw-semibold text-center" aria-current="page" href="./AdminManageUsers.php">
                                    <i class="bi bi-hospital fs-3"></i><br>
                                    <small>Manage users</small>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link fw-semibold text-center" aria-current="page" href="./AdminValidate.php">
                                    <i class="bi bi-calendar-check fs-3"></i><br>
                                    <small>Manage Therapists</small>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link fw-semibold text-center" aria-current="page" href="./Reports.php">
                                    <i class="bi bi-calendar-check fs-3"></i><br>
                                    <small>Reports</small>
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

    <main>
        <section class="main-section container py-5">
            <div class="bg-primary-subtle p-5 rounded-5">
                <h1 class="text-center">Users</h1>

                <form method="POST" action="AdminValidate.php">
                <table class="table table-primary table-striped rounded-5" style="text-align:center;">
                    <tr>
                        <th>ID</th>
                        <th>Fullname</th>
                        <th>Email</th>
                        <th>Contact #</th>
                        <th>License</th>
                        <th>Action</th>
                       
                        <th></th>
                    </tr>
                    <?php
                    if (mysqli_num_rows($var_allqry) > 0) {
                        while ($var_allRec = mysqli_fetch_array($var_allqry)) {
                    ?>
                            <tr>
                                <td><?php echo $var_allRec["therapist_id"]; ?></td>
                                <td><?php echo $var_allRec["Fullname"]; ?></td>
                                <td><?php echo $var_allRec["Email"]; ?></td>
                                <td><?php echo $var_allRec["ContactNum"]; ?></td>
                                <td><img style="width:50px; height:50px;"src='../UserFiles/LicensePictures/<?php echo $var_allRec["license_img"]; ?>'> </td>
                                <td><button type="submit" name="Btnvalidate" value="<?php echo $var_allRec["therapist_id"];?>">Accept</button></td>
                               
                                
                               
                            </tr>
                    <?php
                        }
                    }
                    ?>
                </table>
            </div>
            </form>
        </section>
    </main>
    <script src="../node_modules/bootstrap/dist/js/bootstrap.bundle.js"></script>
    <script>
        let check = <?php echo $var_updatesuccess;?>

        if(check === 1){
            alert("Therapist has been validated!");
        }
    </script>

    
</body>

</html>