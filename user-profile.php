<?php 
include "config.php";
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("location: login.php");
    exit();
}

// Retrieve the username and password from the session
$username = $_SESSION['username'];
$password = $_SESSION['password'];

//Logs
/*
date_default_timezone_set('Asia/Manila');
$date = date('m-d-Y h:i:sA');
$username = $_SESSION['username'];
$details = "Login";   
logs($date, $username, $details);
function logs($date, $username, $details){
    $format = $date . "\t" . $username . "\t\t" . $details . "\n";
    file_put_contents("sample.log", $format, FILE_APPEND);
}
*/

if (isset($_POST['submit'])) {
    // Upload picture
    if ($_FILES['picture']['error'] == UPLOAD_ERR_OK) {
        $picture = $_FILES['picture']['name'];
        $picture_size = $_FILES['picture']['size'];
        $picture_tmp_name = $_FILES['picture']['tmp_name'];
        $picture_folder = 'assets/uploaded-img/';

        $image_file_type = strtolower(pathinfo($picture, PATHINFO_EXTENSION));
        $allowed_extensions = array('jpg', 'jpeg', 'png');
        if (!in_array($image_file_type, $allowed_extensions)) {
            ?>
            <script>
                alert("Invalid image format. Only JPG, JPEG, and PNG files are allowed.");
            </script>
            <?php
        } elseif ($picture_size > 2000000) { // Check file size (2MB limit)
            ?>
            <script>
                alert("Image size is too large. Maximum file size allowed is 2MB.");
            </script>
            <?php
        } else { 
            // Generate a unique filename to avoid conflicts
            $unique_filename = uniqid() . '.' . $image_file_type;
            $picture_folder = 'assets/uploaded-img/' . $unique_filename;

            // Move the uploaded file to the desired location
            if (move_uploaded_file($picture_tmp_name, $picture_folder)) {
                // Update the picture in the database
                $picture = $unique_filename; // Save only the filename in the $picture variable
                $updateQuery = "UPDATE residents1 SET picture='$picture' WHERE username='$username' AND password='$password'";
                mysqli_query($link, $updateQuery);
                
                // Redirect to the profile page
                header("Location: user-profile.php");
                exit();
            } else {
                echo "Failed to upload the picture.";
            }
        }
    }
}

if (isset($_POST['remove'])) {
    // Remove picture
    $updateQuery = "UPDATE residents1 SET picture='' WHERE username='$username' AND password='$password'";
    mysqli_query($link, $updateQuery);
    
    // Redirect to the profile page
    header("Location: user-profile.php");
    exit();
}

if (isset($_POST['update'])) {
    // Retrieve the updated information from the form
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $email = $_POST['email'];
    $address = $_POST['address'];
    $contact = $_POST['contact'];


    // Update the profile information in the database
    $updateQuery = "UPDATE residents1 SET firstname='$firstname', lastname='$lastname', email='$email', address='$address', contact='$contact' WHERE username='$username' AND password='$password'";
    mysqli_query($link, $updateQuery);

    // Redirect to the profile page
    header("Location: user-profile.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <!-- for password eye -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <!-- end of password eye -->

  <title>Profile Settings</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css"
     integrity="sha256-kLaT2GOSpHechhsozzB+flnD+zUyjE2LlfWPgU04xyI="
     crossorigin=""/>

     <script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"
     integrity="sha256-WBkoXOwTeyKclOHuWtc+i2uENFpDZ9YPdf5Hf+D7ewM="
     crossorigin=""></script>

     <style>
        #map{
    width: 100%;
    height: 40vh;
    }

    img {
  max-width: 300px;
  height: auto;
  margin-top: 10px;
}
    </style>
  
</head>

<body>
  <!-- Favicons -->
  <link href="assets/img/favicon.png" rel="icon">
  <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css?family=League+Spartan:300,300i,400,400i,600,600i,700,700i|Raleway:300,300i,400,400i,500,500i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

  <!-- Template Main CSS File -->
  <link href="assets/css/user-profile.css" rel="stylesheet">

<?php
  require_once 'header.php';
?> 

<body>

  <section id="hero" class="d-flex flex-column justify-content-center align-items-center mt-3">
            <?php 
                
                $username = $_SESSION['username'];
                $password = $_SESSION['password'];
                $selectUser = "SELECT * FROM residents1 where username='$username' and password='$password'";
                $query = mysqli_query($link, $selectUser);
                while($info = mysqli_fetch_assoc($query)){
                    $id = $info['id'];
                    $firstname = $info['firstname'];
                    $lastname = $info['lastname'];
                    $email = $info['email'];
                    $address     = $info['address'];
                    $contact = $info['contact'];
                    $picture = $info['picture']
                    ?>

<div class="container-xl wrapper">
    <!-- Account page navigation-->
     <div class="row">
        <div class="col-8">
            <!-- Account details card -->
            <div class="card" style="width:100%;">
                <div class="card-header">Account Details</div>
                <div class="card-body">
                    <form action="" method="post"> 
                        <!-- Form Row -->
                        <div class="row gx-3 mb-3">
                            <!-- Form Group (first name) -->
                            <div class="col-md-6">
                                <label class="small mb-1" for="inputFirstName">First name</label>
                                <input class="form-control" id="inputFirstName" type="text" name="firstname" value="<?php echo ucwords($firstname); ?>" placeholder="Enter your first name">
                            </div>

                            <div class="col-md-6">
                                <label class="small mb-1" for="inputLastName">Last name</label>
                                <input class="form-control" id="inputLastName" type="text" name="lastname"  value="<?php echo ucwords($lastname); ?>" placeholder="Enter your last name">
                            </div>
                        </div>
                        <div class="row gx-3 mb-3">
                        <div class="col-md-6">
                                <label class="small mb-1" for="inputOrgName">Username</label>
                                <input class="form-control" id="inputOrgName" type="text" name="username" value="<?php echo ($username); ?>" placeholder="Enter your username" disabled>
                            </div>
                            <!-- Form Group (location) -->
                            <div class="col-md-6">
                                <label class="small mb-1" for="inputLocation">Email Address</label>
                                <input class="form-control" id="inputEmailAddress" type="text" name="email" value="<?php echo ($email); ?>" placeholder="Enter your email address">
                            </div>
                        </div>
                        <!-- Form Group (email address) -->
                        <div class="mb-3">
                            <label class="small mb-1" for="inputEmailAddress">Full Address</label>
                            <input class="form-control" id="inputLocation" type="text" name="address" value="<?php echo ucwords($address); ?>" placeholder="Enter your location">
                        </div>
                        <!-- Form Row -->
                        <div class="row gx-3 mb-3">
                            <!-- Form Group (phone number)-->
                             <div class="col-md-6">
                                <label class="small mb-1" for="inputPhone">Phone number</label>
                                <input class="form-control" id="inputPhone" type="tel" name="contact" value="<?php echo ($contact); ?>" placeholder="Enter your phone number">
                            </div>
                            <div class="col-md-6">
                                <label class="small mb-1" for="curPass">Current Password</label>
                                <input class="form-control" autocomplete="current-password" id="curPass" type="text" placeholder="Enter your current password" >
                                <i class="far fa-eye" id="togglePassword" style="margin-left: -50px; cursor: pointer;"></i>
                            </div>
                        </div>
                        <!-- Change Pass -->
                        <div class="row gx-3 mb-3">
                             <div class="col-md-6">
                                <label class="small mb-1" for="newPass">New Password</label>
                                <input class="form-control" autocomplete="current-password" id="newPass"  type="password" placeholder="Enter your new password">
                                <i class="far fa-eye" id="togglePassword" style="margin-left: -30px; cursor: pointer;"></i>
                            </div>
                            <div class="col-md-6">
                                <label class="small mb-1" for="confirmPass">Confirm Password</label>
                                <input class="form-control" autocomplete="current-password" id="confirmPass" type="password" placeholder="Confirm your new password">
                                <i class="far fa-eye" id="togglePassword" style="margin-left: -30px; cursor: pointer;"></i>
                            </div>
                        </div>
                        <div class="row gx-3 mb-3">
                                <button class="btn btn-save mx-auto" style = "background-color: #039BE5; width: 200px;
                                float: right; margin-top: 5px; color: white;" type="submit" name="update">Save changes</button>
                        </div> 
                            </form>
                </div>
            </div>
        </div>
            <?php
                }

            ?>

<div class="col-4">
   <!-- RIGHT SIDE  -->
    <!-- Profile picture card-->
    <div class="card mb-4 mb-xl-0">
    <div class="card-header">Profile Picture</div>
    <div class="card-body text-center">
        <!-- Profile picture image --> 
        <?php 
        if ($picture == '') {
            echo '<img class="img-account-profile" style="border-radius:50%; width: 90px; height: 90px; object-fit: cover; object-position: 25% 25%;" src="assets/img/default-avatar.png">';
        } else {
            echo '<img class="img-account-profile" style="border-radius:50%; width: 90px; height: 90px; object-fit: cover; object-position: 25% 25%;" src="assets/uploaded-img/' . $picture . '">';
        }
        ?>
        <!-- Profile picture help block -->
        <div class="small font-italic text-muted mb-4">Upload your profile photo</div>

        <form action="" method="post" enctype="multipart/form-data">
            <input type="file" name="picture" hidden="hidden" id="picture" onchange="uploadPhoto()">
            <label for="picture" class="upload">Upload</label>
            <button type="submit" class="remove" name="remove" value="Remove">Remove</button>
            <button type="submit" name="submit" value="Submit" hidden="hidden" id="submitButton">Submit</button>
        </form>
    </div>
</div>
    <br>
    <div class="col-4"> 
        <!-- Profile picture card-->
         <div class="card mb-4 mb-xl-0">
            <div class="card-header">Pin Location</div>
           
            <div id="map"></div>             
            </div>
        </div>
    </div>

</div>
           
</section>
<?php
//////////////////////////////////////////////// PHP FOR RETRIEVING LONG&LAT FROM DATABASE //////////////////////////////////////////////////////
  // require_once 'footer.php';

  // Establish database connection
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'demo1');
 
// Attempt to connect to MySQL database 
$conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
 
// Check connection
if($conn === false){
    die("ERROR: Could not connect. " . mysqli_connect_error());
}

$myId = $id;

// Perform the SQL queries to fetch the long and lat from the database
$sql1 = "SELECT longitude FROM residents1 WHERE id='" . $myId . "'";
$sql2 = "SELECT latitude FROM residents1 WHERE id='" . $myId . "'";

$result1 = mysqli_query($conn, $sql1);
$result2 = mysqli_query($conn, $sql2);

// Fetch the data and store it in PHP variables
$data1 = mysqli_fetch_assoc($result1);
$data2 = mysqli_fetch_assoc($result2);


// Close the database connection
mysqli_close($conn);

//Fetch KEY VALUE from LongLat Assoc Array
$longi = $data1['longitude'];
$lati = $data2['latitude'];
?>

<!-- ----------------------------------------------------- JAVASCRIPT ----------------------------------------------------------------- -->

<script>
    function uploadPhoto() {
        const input = document.getElementById('picture');
        const file = input.files[0];

        if (file) {
            const reader = new FileReader();

            reader.onload = function(e) {
                const img = document.createElement("img");
                img.src = e.target.result;

                const container = document.getElementsByClassName("img-account-profile")[0];
                container.src = img.src;

                document.getElementById("submitButton").click(); // Automatically trigger form submission
            };

            reader.readAsDataURL(file);
        }
    }
</script>

<script> 

//Fetch LongLat Variable Values from PHP
var jsonData1 = <?php echo $longi; ?>;
var jsonData2 = <?php echo $lati; ?>;

//GeoLoc Map
var popup = L.popup();
let map = new L.map('map');
map.setView([jsonData2, jsonData1], 17);

let layer = new L.TileLayer('http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png');
map.addLayer(layer);

var marker = L.marker([jsonData2, jsonData1]).addTo(map);

</script>
  

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.7/dist/umd/popper.min.js" integrity="sha384-zYPOMqeu1DAVkHiLqWBUTcbYfZ8osu1Nd6Z89ify25QV9guujx43ITvfi12/QExE" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.min.js" integrity="sha384-Y4oOpwW3duJdCWv5ly8SCFYWqFDsfob/3GkgExXKV4idmbt98QcxXYs9UoXAB7BZ" crossorigin="anonymous"></script>

  
  <!-- Vendor JS Files -->
  <script src="assets/vendor/purecounter/purecounter_vanilla.js"></script>
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/glightbox/js/glightbox.min.js"></script>
  <script src="assets/vendor/isotope-layout/isotope.pkgd.min.js"></script>
  <script src="assets/vendor/swiper/swiper-bundle.min.js"></script>
  <script src="assets/vendor/waypoints/noframework.waypoints.js"></script>
  <script src="assets/vendor/php-email-form/validate.js"></script>

  <!-- Template Main JS File -->
  <script src="assets/js/main.js"></script>
</body>
</html>