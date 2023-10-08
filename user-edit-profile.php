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


if (isset($_POST['remove'])) {
    // Remove picture
    $updateQuery = "UPDATE residents1 SET picture='' WHERE username='$username' AND password='$password'";
    mysqli_query($link, $updateQuery);

    function logs($link, $username, $details)
     {
       date_default_timezone_set('Asia/Manila');
       $date = date('F d, Y h:i:sA');
     
         $format = $date . "\t" . $username . "\t" . $details . "\n";
     file_put_contents("sample.log", $format, FILE_APPEND);
     }
          
     $log_details = "Removed Profile Picture";
     logs($link, $username, $log_details);
    
    // Redirect to the profile page
    header("Location: user-edit-profile.php");
    exit();
}

$firstname = $lastname = 
// $contact =
$address = $picture = $oldPass = $newPass = $confirmPass = $inputNewPass = '';
$firstname_err = $lastname_err =
// $contact_err = 
$address_err = $picture_err = '';

if (isset($_POST['update_account_details'])) {
    // Retrieve the updated information from the form
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $address = $_POST['address'];
    $contact = $_POST['contact'];

     // Validate Firstname
     $input_firstname = trim($_POST["firstname"]);
     if(empty($input_firstname)){
         $firstname_err = "Please enter a firstname.";
     } elseif(!filter_var($input_firstname, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/")))){
         $firstname_err = "Please enter a valid firstname.";
     } else{
         $firstname = ucwords($input_firstname);
     }

     //Validate Lastname
    $input_lastname = trim($_POST["lastname"]);
    if(empty($input_lastname)){
        $lastname_err = "Please enter a lastname.";
    } elseif(!filter_var($input_lastname, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/")))){
        $lastname_err = "Please enter a valid lastname.";
    } else{
        $lastname = ucwords($input_lastname);
    }

    // // Validate contact
    // $input_contact = trim($_POST["contact"]);
    // if(empty($input_contact)){
    //     $contact_err = "Please enter the contact amount.";     
    // } elseif(!filter_var($input_contact, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[0][9][0-9]{9}$/")))){
    //     $contact_err = "Please enter valid Contact no. (09XXXXXXXXX)";
    // } else{
    //     $contact = $input_contact;
    // }  

    //Validate Email
    // $input_email = trim($_POST["email"]);
    // if(empty($input_email)){
    //     $email_err = "Please enter an email.";
    // } elseif(!filter_var($input_email, FILTER_VALIDATE_EMAIL)){
    //     $email_err = "Please enter a valid email.";
    // }else{
    //     $email = $input_email;
    // } 

     // Validate address
     $input_address = trim($_POST["address"]);
     if(empty($input_address)){
         $address_err = "Please enter an address.";     
     } elseif(!filter_var($input_address, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z0-9\s\,\.\#]+$/")))){
         $address_err = "Re-enter address";
     }else{
         $address = $input_address;
     }


     if(empty($firstname_err) && empty($lastname_err)  
     //  && empty($contact_err)  
      && empty($address_err)){
        // Update the profile information in the database
     $updateQuery = "UPDATE residents1 SET firstname='$firstname', lastname='$lastname', address='$address', contact='$contact' WHERE username='$username' AND password='$password'";
     
     function logs($link, $username, $details)
     {
       date_default_timezone_set('Asia/Manila');
       $date = date('F d, Y h:i:sA');
     
         $format = $date . "\t" . $username . "\t" . $details . "\n";
     file_put_contents("sample.log", $format, FILE_APPEND);
     }
     if($statement = mysqli_query ($link, $updateQuery)){
          
       $log_details = "Edited Account Details";
       logs($link, $username, $log_details);
       // Redirect to the profile page
         header("Location: user-edit-profile.php");
         exit();
      } else{
       echo "Oops! Something went wrong. Please try again later.";
      }
     mysqli_stmt_close($stmt);
    
 }
 mysqli_close($link);
 }

if (isset($_POST['submit'])) {
 // Check if a picture file is uploaded
 if (isset($_FILES["picture"]["name"])) {
    $id = $_POST["id"];

    // Validate the uploaded image file
    $picture = $_FILES['picture']['name'];
    $picture_size = $_FILES['picture']['size'];
    $picture_tmp_name = $_FILES['picture']['tmp_name'];
    $picture_folder = 'assets/uploaded-img/';

    $image_file_type = strtolower(pathinfo($picture, PATHINFO_EXTENSION));
    $allowed_extensions = array('jpg', 'jpeg', 'png');

    if (!in_array($image_file_type, $allowed_extensions)) {
        $picture_err = "Invalid image format. Only JPG, JPEG, and PNG files are allowed.";
        // header("Location: user-edit-profile.php"); // Redirect back to the profile page
        // exit();
    } elseif ($picture_size > 2000000) { // Check file size (2MB limit)
        $picture_err = "Image size is too large. Maximum file size allowed is 2MB.";
        // header("Location: user-edit-profile.php"); // Redirect back to the profile page
        // exit();
    } else {
        // Generate a unique filename to avoid conflicts
        $unique_filename = uniqid() . '.' . $image_file_type;
        $picture_folder = 'assets/uploaded-img/' . $unique_filename;

        function logs($link, $username, $details)
     {
       date_default_timezone_set('Asia/Manila');
       $date = date('F d, Y h:i:sA');
     
         $format = $date . "\t" . $username . "\t" . $details . "\n";
     file_put_contents("sample.log", $format, FILE_APPEND);
     }
        // Move the uploaded file to the desired location
        if (move_uploaded_file($picture_tmp_name, $picture_folder)) {
            // Update the picture in the database
            $picture = $unique_filename; // Save only the filename in the $picture variable
            $updateQuery = "UPDATE residents1 SET picture='$picture' WHERE username='$username' AND password='$password'";

            $log_details = "Uploaded A Profile Picture";
            logs($link, $username, $log_details);
            mysqli_query($link, $updateQuery);

        } else {
            $picture_err =  "Failed to upload the picture.";
        }
    }
}
}

// for change password
if (isset($_POST['update_password'])) {
  
  //Validate New Password
  $newPass = trim($_POST["newPass"]);
  if (empty($newPass)) {
      $newPass_err = "Please enter valid password.";
  } elseif (strlen($newPass) < 8) {
      $newPass_err = "Password must have a minimum of 8 characters";
  } elseif (!preg_match("/(?=.*\d)/", $newPass)) {
      $newPass_err = "Password must contain at least one digit";
  } elseif (!filter_var($newPass, FILTER_VALIDATE_REGEXP, array("options" => array("regexp" => "/^(?=.*\d)[a-zA-Z0-9\S]+$/")))) {
      $newPass_err = "Please enter valid password.";
  }

  //Validate Confirm Password
  $confirmPass = trim($_POST["confirmPass"]);
  if (empty($confirmPass)) {
      $confirmPass_err = "Please confirm password.";
  } elseif ($confirmPass !== $newPass) {
      $confirmPass_err = "Passwords do not match!";
  }

  // Check Old Password
  $oldPass = trim($_POST["oldPass"]);
  $salt = "b2a6ec273b45b1d0ab06130cb4e2e62d"; //sunog
  $hashedOldPass = md5($salt . $oldPass);
  if (empty($oldPass)) {
      $oldPass_err = "Please enter current password.";
  } elseif ($hashedOldPass !== $password) {
      $oldPass_err = "Passwords do not match!";
  }

  if (empty($oldPass_err) && empty($newPass_err) && empty($confirmPass_err)) {
      // Retrieve the username from the session
      $salt = "b2a6ec273b45b1d0ab06130cb4e2e62d"; //sunog

      // Validate the old password
      $hashedOldPass = md5($salt . $oldPass);
      $result = mysqli_query($link, "SELECT password FROM residents1 WHERE username='$username' and password ='$hashedOldPass'");

      function logs($link, $username, $user_details)
      {
        date_default_timezone_set('Asia/Manila');
        $date = date('F d, Y h:i:sA');
      
          $format = $date . "\t" . $username . "\t" . $user_details . "\n";
      file_put_contents("sample.log", $format, FILE_APPEND);
      }

      if (mysqli_num_rows($result) == 1) {
          $row = mysqli_fetch_array($result);
          $storedPassword = $row['password'];
          if ($hashedOldPass == $storedPassword) {
            $hashedNewPass = md5($salt . $newPass);
            session_start();
            unset($_SESSION['username']);
            unset($_SESSION['password']);
            header("location: login.php");
            
          // make a script confirming change pass and will automatically logout //

          // Update the password in the database
          $updatePass = "UPDATE residents1 SET password='$hashedNewPass' WHERE username='$username'";
          mysqli_query($link, $updatePass);

          $log_details = "Changed Password";
          logs($link, $username, $log_details);

          }
      } else {
          // Passwords don't maclass="col-lg-12"tch
          header("Location: user-edit-profile.php"); 
      } 
  }  // Close connection
  mysqli_close($link);
}
?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Profile Settings</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css?family=League+Spartan:300,300i,400,400i,600,600i,700,700i|Raleway:300,300i,400,400i,500,500i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">  

  <!-- Template Main CSS File -->
  <link href="assets/css/user-edit-profile.css" rel="stylesheet" type="text/css">

  <!-- for image preview -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <!-- end of prev -->

  <!-- for password eye -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
  <!-- end of password eye -->

   
  <!-- for map -->
  <script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"
     integrity="sha256-WBkoXOwTeyKclOHuWtc+i2uENFpDZ9YPdf5Hf+D7ewM="
     crossorigin=""></script>
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css"
     integrity="sha256-kLaT2GOSpHechhsozzB+flnD+zUyjE2LlfWPgU04xyI="
     crossorigin=""/>

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/5.5.3/css/ionicons.min.css">
 
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

.upload{
      width: 120px;
      position: relative;
      margin: auto;
      text-align: center;
    }
    .upload img{
      border-radius: 50%;
      border: 3px solid gray;
      height: 120px;
      width:120px;
    }
    .upload .rightRound{
      position: absolute;
      bottom: 0;
      right: 0;
      background: #0294DB;
      width: 32px;
      height: 32px;
      line-height: 33px;
      text-align: center;
      border-radius: 50%;
      overflow: hidden;
      cursor: pointer;
    }
    .upload .leftRound{
      position: absolute;
      bottom: 0;
      left: 0;
      background: #dc3545;
      width: 32px;
      height: 32px;
      line-height: 33px;
      text-align: center;
      border-radius: 50%;
      overflow: hidden;
      cursor: pointer;
    }
    .upload .fa{
      color: white;
    }
    .upload input{
      position: absolute;
      transform: scale(2);
      opacity: 0;
    }
    .upload input::-webkit-file-upload-button, .upload input[type=submit]{
      cursor: pointer;
      }

      #is-invalid {
        border: 2px solid red;
    }

    #fieldDivs-picture {
        margin-top: -5px;
        position: absolute;
        padding-left: 16%;
    }
   
    #fieldDivs {
        margin-top: -3px;
        position: absolute;
    }

    #fieldErrs {
        color: red;
        font-size: 12px;
    }

    .modal-content {
     background: #f2f6fc;
    }

    .wrapper {
      width: 900px; 
      height: 1120px; 
      margin:auto; 
      margin-top:100px;
    }

    @media(max-width: 600px) {

      .wrapper {
      max-width: 550px;
      height: 1430px; 
  }

  #fieldDivs-picture{
    padding-left: 10%;
  }
  

    }

    </style>
  
</head>

<?php
  require_once 'header.php';
?> 
    
<body>

<section id="hero" class="d-flex justify-content-center align-items-center mx-auto">

    <div class="wrapper text-center text-md-left">
        <?php 
        $username = $_SESSION['username'];
        $password = $_SESSION['password'];
        $selectUser = "SELECT * FROM residents1 where username='$username' and password='$password'";
        $query = mysqli_query($link, $selectUser);
        while($info = mysqli_fetch_assoc($query)){
            $id = $info['id'];
            $firstname = $info['firstname'];
            $lastname = $info['lastname'];
            $address = $info['address'];
            $contact = $info['contact'];
            $picture = $info['picture'];
        ?>
      <div class="row-12">
      <div class="col-1">
      <a href="user-view-profile.php"><i class="bi bi-arrow-left" style="color:#69707a; font-size:30px;"></i></a>
      </div>
    <form class="form" id = "form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" enctype="multipart/form-data" method="post" style="margin-top:100px;">
        <input type="hidden" name="id" value="<?php echo $user['id']; ?>">
      <div class="upload" style="margin:auto; margin-top:-130px; background-color: transparent;">
      <?php 
        if ($picture == '') {
            echo '<img id="image" class="img-account-profile" style= "object-fit: cover; object-position: 25% 25%;" src="assets/img/default-avatar.png">';
        } else {
            echo '<img id="image" class="img-account-profile" style="object-fit: cover; object-position: 25% 25%;" src="assets/uploaded-img/' . $picture . '">';
        }
        ?>

        <div class="rightRound" id = "upload">
          <input type="file" name="picture" id = "picture">
          <i class = "fa fa-pen"></i>
        </div>

        <div class="leftRound" id = "cancel" style = "display: none;">
          <i class = "fa fa-times"></i>
        </div>
        <div class="rightRound" id = "confirm" style = "display: none;">
          <input type="submit" name="submit">
          <i class = "fa fa-check"></i>
        </div>
      </div>
        <div id="fieldDivs-picture"><span id="fieldErrs"><?php echo $picture_err;?></span></div>
      <button type="submit" class="remove" name="remove" value="Remove" style="margin-top:20px; font-size:13.5px;">Remove Profile</button>
    </form>
    </div>
    <div class="row">
    <div class="col-9" style="margin:auto; text-align:left;"><br>
        <div class="card-header" style="  font-weight: 450; text-align: left;
        color:#69707a; border-bottom: 1px solid rgba(33, 40, 50, 0.125);">Account Details</div><br>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post"> 
                        <!-- Form Row -->
                        <div class="row gx-3 mb-3">
                            <!-- Form Group (first name) -->
                            <div class="col-md-6">
                              <div>
                                <label class="small mb-1" style="color:#69707a;">First name</label> 
                                <input class="form-control" id="<?php echo (!empty($firstname_err)) ? 'is-invalid' : 'fName'; ?>" type="text" name="firstname" value="<?php echo ucwords($firstname); ?>" placeholder="Enter your first name" required>
                                </div>
                                <div id="fieldDivs"><span id="fieldErrs"><?php echo $firstname_err;?></span></div>
                            </div>

                            <div class="col-md-6">
                              <div>
                                <label class="small mb-1" style="color:#69707a;">Last name</label>
                                <input class="form-control" id="<?php echo (!empty($lastname_err)) ? 'is-invalid' : 'lastname'; ?>" type="text" name="lastname"  value="<?php echo ucwords($lastname); ?>" placeholder="Enter your last name" required>
                            </div>
                            <div id="fieldDivs"><span id="fieldErrs"><?php echo $lastname_err;?></span></div>
                            </div>
                        </div>
                        <div class="row gx-3 mb-3">
                        <div class="col-md-6">
                                <label class="small mb-1" style="color:#69707a;" for="inputOrgName">Username</label>
                                <input class="form-control" id="inputOrgName" type="text" name="username" value="<?php echo ($username); ?>" placeholder="Enter your username" disabled>
                            </div>
                            <!-- Form Group (phone number)-->
                             <div class="col-md-6">
                              <div>
                                <label class="small mb-1" style="color:#69707a;">Contact Number</label>
                                <input class="form-control" id="<?php 
                                // echo (!empty($contact_err)) ? 'is-invalid' : 'phone'; 
                                ?>" type="number" name="contact" value="<?php echo ($contact); ?>" placeholder="Enter your phone number">
                            </div>
                              <!-- <div id="fieldDivs"><span id="fieldErrs"><?php echo $contact_err;?></span></div> -->
                            </div>
                        </div>

                        <div class="mb-3">
                          <div>
                                <label class="small mb-1" style="color:#69707a;">Email Address</label>
                                <input class="form-control" type="email" name="email" value="<?php echo ($email); ?>" placeholder="Enter your email address" disabled>
                            </div>
                            </div>
                            <div class="mb-3">
                              <div>
                            <label class="small mb-1" style="color:#69707a;">Full Address</label>
                            <input class="form-control" id="<?php echo (!empty($address_err)) ? 'is-invalid' : ''; ?>" value ="<?php echo $address; ?>" type="text" name="address" value="<?php echo ucwords($address); ?>" placeholder="Enter your location">
                        </div>
                        <div id="fieldDivs"><span id="fieldErrs"><?php echo $address_err;?></span></div>
                        </div>
                        <div class="row gx-3 mb-3">
                        <button class="btn btn-save mx-auto" style = "background-color: #0294DB; width: 200px;
                                float: right; margin-top: 5px; color: white;" type="button" data-bs-toggle="modal" data-bs-target="#saveModal1">Save Changes</button>
                                <!-- Modal -->
                                <div class="modal fade" id="saveModal1" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                  <div class="modal-dialog modal-dialog-centered">
                                      <div class="modal-content">
                                          <div class="modal-body" style=" text-align:center;">
                                          <i class="bi bi-question-circle fa-4x animated rotateIn mb-4" style="color: #C04C43;"></i>
                                          <p class="text-center p">Do you want to save changes?</p>
                                          </div>
                                          <div class="modal-footer mx-auto">
                                            <div class="row">
                                              <div class="col-6">
                                              <button type="button" class="btn btn-danger waves-effect mx-auto rounded " style="width: 100px; color: white; height: 40px;" data-bs-dismiss="modal">Cancel</button>
                                              </div>
                                              <div class="col-6">
                                              <button type="submit" class="btn btn-danger mx-auto rounded" name="update_account_details" style="width: 100px; color: white; height: 40px;">Save</button>
                                            </div>
                                            </div>
                                            </div>
                                      </div>
                                  </div>
                              </div>
                        </div> 
                            </form>
    </div>
    </div>
        <div class="row-12">    
        <div class="col-9" style="margin:auto; text-align:left;"><br>
        <div class="card-header" style="  font-weight: 500; text-align: left;
        color:#69707a; border-bottom: 1px solid rgba(33, 40, 50, 0.125);">Pin Address</div><br>
        </div>
        <div class="col-lg-12" id="geoDiv" style="margin:auto; width: 85%; height: 50vh; padding: 5px 10px 10px 10px;">
        <div id="map"></div> 
    </div>
    </div>
        <?php
        }
        ?>
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

<!-- for image preview -->
<script type="text/javascript">
      document.getElementById("picture").onchange = function(){
        document.getElementById("image").src = URL.createObjectURL(picture.files[0]); // Preview new image

        document.getElementById("cancel").style.display = "block";
        document.getElementById("confirm").style.display = "block";

        document.getElementById("upload").style.display = "none";
      }

      var userImage = document.getElementById('image').src;
      document.getElementById("cancel").onclick = function(){
        document.getElementById("image").src = userImage; // Back to previous image

        document.getElementById("cancel").style.display = "none";
        document.getElementById("confirm").style.display = "none";

        document.getElementById("upload").style.display = "block";
      }
    </script>
<!-- end -->  


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


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe"
        crossorigin="anonymous"></script>
</body>
</html>
