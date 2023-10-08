<?php
// Include config file
require_once "config.php";
require_once('PHPMailer/PHPMailerAutoload.php');

 
// Define variables and initialize with empty values
$firstname = $lastname = $email = $username = $password = $input_password = $conpassword = $address = $contact = $latitude = $longitude = $geo_url = $picture = "";
$firstname_err = $lastname_err = $email_err = $username_err = $password_err = $conpassword_err = $address_err = $contact_err = $lat_err = $long_err = $picture_err= $code_err = "";
$salt = "b2a6ec273b45b1d0ab06130cb4e2e62d";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
    
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

      //Validate Email
    $input_email = trim($_POST["email"]);
    $result = mysqli_query($link, "SELECT * FROM residents1 WHERE email='$input_email'");
    
    if (mysqli_num_rows($result) > 0){
      $row = mysqli_fetch_array($result);
      if($input_email==isset($row['email'])){
        $email_err = "Email already exists.";
      }
    } elseif(empty($input_email)){
        $email_err = "Please enter an email.";
      } elseif(!filter_var($input_email, FILTER_VALIDATE_EMAIL)){
          $email_err = "Please enter a valid email.";
      } else{
          $email = $input_email;
    }
  
    //Validate Username
    $input_username = trim($_POST["username"]);
    $result = mysqli_query($link, "SELECT * FROM residents1 WHERE username='$input_username'");
    
    if (mysqli_num_rows($result) > 0){
      $row = mysqli_fetch_array($result);
      if($input_username==isset($row['username'])){
        $username_err = "Username already exists.";
      }
    } elseif(empty($input_username)){
        $username_err = "Please enter a username.";
      } elseif(!filter_var($input_username, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z0-9\_\S]+$/")))){
        $username_err = "Please enter a valid username.";
      } else{
        $username = $input_username;
    }

    //Validate Password
    $input_password = trim($_POST["password"]);
    if(empty($input_password)){
        $password_err = "Please enter valid password.";
    } elseif(strlen($input_password) < 8){
        $password_err = "Password must have a minimum of 8 characters";
    } elseif(!preg_match("/(?=.*\d)/", $input_password)){
        $password_err = "Password must contain at least one digit";
    } elseif(!filter_var($input_password, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^(?=.*\d)[a-zA-Z0-9\S]+$/")))){
        $password_err = "Please enter valid password.";
    } else {
      $password = $input_password;
    } 
    
    //Validate Confirm Password
    $input_conpassword = trim($_POST["conPass"]);
    if(empty($input_conpassword)){
        $conpassword_err = "Please confirm password.";
    } elseif($input_conpassword !== $input_password){
        $conpassword_err = "Passwords do not match!";
    }else{
        $conpassword = $input_conpassword;
    } 

    // Validate address
    $input_address = trim($_POST["address"]);
    if(empty($input_address)){
        $address_err = "Please enter an address.";     
    } elseif(!filter_var($input_address, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z0-9\s\,\.\#]+$/")))){
        $address_err = "Re-enter address";
    }else{
        $address = $input_address;
    }
    
    // Validate contact
    $input_contact = trim($_POST["contact"]);
    if(empty($input_contact)){
        $contact_err = "Please enter the contact amount.";     
    } elseif(!filter_var($input_contact, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[0][9][0-9]{9}$/")))){
        $contact_err = "Please enter valid Contact no. (09XXXXXXXXX)";
    } else{
        $contact = $input_contact;
    }  

     // Validate latitude
     $input_latitude = trim($_POST["hidden_lat"]);
     if(empty($input_latitude)){
         $lat_err = "Please pin your location";     
     }else{
         $latitude = $input_latitude;
     }
 
     // Validate longitude
     $input_longitude = trim($_POST["hidden_long"]);
     if(empty($input_longitude)){
         ?> 
         <script>
             alert("Please pin your location");
         </script>
         <?php
         $long_err = "Please pin your location";     
     } else{
         $longitude = $input_longitude;
     }

      // Validate GEO URL
      $input_geourl= trim($_POST["hidden_url"]);
      if(empty($input_geourl)){
          $lat_err = "Please pin your location";     
      }else{
          $geo_url = $input_geourl;
      }
    

    // Check input errors before inserting in database
    if (empty($firstname_err) && empty($lastname_err) && empty($email_err) && empty($username_err) && empty($password_err) && empty($conpassword_err) && empty($address_err) && empty($contact_err) && empty($lat_err) && empty($long_err)) {
      $code = rand(999999, 111111);
      $verification_status = "not verified";
      $hash_passw = md5($salt . $input_password);
      $password = $hash_passw;
  
      // Prepare an insert statement
      $sql = "INSERT INTO residents1 (firstname, lastname, email, username, password, address, contact, latitude, longitude, picture, code, verification_status, geo_url) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
  
      if ($stmt = mysqli_prepare($link, $sql)) {
          // Bind variables to the prepared statement as parameters
          mysqli_stmt_bind_param($stmt, "sssssssssssss", $param_firstname, $param_lastname, $param_email, $param_username, $param_password, $param_address, $param_contact, $param_lat, $param_long, $param_picture, $param_code, $param_verification_status, $param_geourl);
  
          // Set parameters
          $param_firstname = $firstname;
          $param_lastname = $lastname;
          $param_email = $email;
          $param_username = $username;
          $param_password = $password;
          $param_address = $address;
          $param_contact = $contact;
          $param_lat = $latitude;
          $param_long = $longitude;
          $param_picture = $picture;
          $param_code = $code;
          $param_verification_status = $verification_status;
          $param_geourl = $geo_url;

          function logs($link, $username, $details)
          {
            date_default_timezone_set('Asia/Manila');
            $date = date('F d, Y h:i:sA');
          
              $format = $date . "\t" . $username . "\t" . $details . "\n";
          file_put_contents("sample.log", $format, FILE_APPEND);
          }
  
          // Attempt to execute the prepared statement
          if (mysqli_stmt_execute($stmt)) {
              // Close statement
              mysqli_stmt_close($stmt);

              $log_details = "Signed up";
              logs($link, $username, $log_details);

              function hideEmailAddress($email)
        {
            $em   = explode("@",$email);
            $name = implode(array_slice($em, 0, count($em)-1));
            $len  = floor(strlen($name)/2);
            return substr($name,0, $len) . str_repeat('*', $len) . "@" . end($em);   
        }
  
              $mail = new PHPMailer;
              $mail->isSMTP();
              $mail->SMTPAuth = true;
              $mail->Host = 'smtp.gmail.com';
              $mail->Port = 587;
              $mail->SMTPSecure = 'tls';
              $mail->isHTML();
              $mail->Username = 'safehome42023@gmail.com';
              $mail->Password = 'tlitclmjwfqvvgpd';
              $mail->SetFrom('safehome42023@gmail.com', 'SafeHome');
              $mail->Subject = "Email Verification";
              $mail->Body = '<p>Your email verification code is: <b style="font-size:30px;">' . $code . '</b></p>';
              $mail->addAddress($email);
  
              if ($mail->Send()) {
                  $_SESSION['code_sent'] = true;
                  $info = "We've sent an email verification code to your email - " .hideEmailAddress($email);
                  $_SESSION['info'] = $info;
                  $_SESSION['email'] = $email;
  
                  header("location: user-signup-code.php");
                  exit();
              } else {
                  echo "Oops! Something went wrong while sending the email.";
              }
          } else {
              // Error handling if the registration fails
              echo "Oops! Something went wrong. Please try again later.";
          }
      }
  
      // Close statement
      mysqli_stmt_close($stmt);
  }
  
  // Close connection
  mysqli_close($link);
}
?>
 
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- for password eye -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <!-- end of password eye -->
    <link rel="stylesheet" href="assets/css/signup.css">
    <title>Sign Up</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
 
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css"
     integrity="sha256-kLaT2GOSpHechhsozzB+flnD+zUyjE2LlfWPgU04xyI="
     crossorigin=""/>

     <script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"
     integrity="sha256-WBkoXOwTeyKclOHuWtc+i2uENFpDZ9YPdf5Hf+D7ewM="
     crossorigin=""></script>

     <script src="https://code.jquery.com/jquery-3.6.4.min.js" 
     integrity="sha256-oP6HI9z1XaZNBrJURtCoUT5SUnxFr8s3BzRl+cbzUq8=" 
     crossorigin="anonymous"></script>

     <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.9.4/umd/popper.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0-alpha3/js/bootstrap.min.js"></script>

     
    <style>
    #map{
        width: 100%;
        height: 35vh;
    }

    #is-invalid {
        border: 2px solid red;
    }

    #geoDiv{
        width: 95%;
        height: 45vh; 
        padding:1px; 
        margin-top:40px;
    }

    #passwordConditions{
        position: absolute;
        margin-top: -20px;
        text-align: left;
        padding-left: 15px;
    }

    .valid-condition {
        color: green;
        font-size: 12px;
    }
    
    .invalid-condition {
        color: gray;
        font-size: 12px;
    }

    .row {
        margin-top: 3px;
    }

    #fieldDivs {
        margin-top: -20px;
        padding-left: 15px;
        position: absolute;
    }

    #fieldErrs {
        color: red;
        font-size: 12px;
    }
    
    .modal-content {
     background: #f2f6fc;
    }

    </style>

</head>

<body class="body">
    <section id="hero" class="d-flex justify-content-center align-items-center">
        <div class="container text-center text-md-left" style="width: 900px; height: 1200px; margin:auto; margin-top:50px;">
          <div class="mb-container text-center text-md-left">
            <div class="card">
                <div class="logo">
                    <img src="logo.png" alt="">
                </div>
            <div class="text-center mt-3 name">
            CREATE AN ACCOUNT
            </div>
        
        <form class="p-3 mt-3" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?> " method="post" autocomplete="">

            <div class="row">
                <div class="col-sm-4">
                    <!-- First Name Field -->
                    <div class="form-field d-flex align-items-center">
                        <input type="text" name="firstname" id="<?php echo (!empty($firstname_err)) ? 'is-invalid' : 'fName'; ?>" value="<?php echo $firstname; ?>" type="text" placeholder="First Name" required>
                    </div>
                    <div id="fieldDivs"><span id="fieldErrs"><?php echo $firstname_err;?></span></div>
                </div>
                  
                <div class="col-sm-4">
                    <!-- Last Name Field -->
                    <div class="form-field d-flex align-items-center">
                      <input type="text" name="lastname" id="<?php echo (!empty($lastname_err)) ? 'is-invalid' : 'lastname'; ?>" value="<?php echo $lastname; ?>" type="text" placeholder="Last Name" required>
                    </div>
                    <div id="fieldDivs"><span id="fieldErrs"><?php echo $lastname_err;?></span></div>
                </div>
                  
                <div class="col-sm-4">
                    <!-- Username Field -->
                    <div class="form-field d-flex align-items-center">
                      <input type="text" name="username" id="<?php echo (!empty($username_err)) ? 'is-invalid' : 'username'; ?>" value="<?php echo $username; ?>" type="text" placeholder="Username" required>
                  </div>
                  <div id="fieldDivs"><span id="fieldErrs"><?php echo $username_err;?></span></div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-6">
                    <!-- Email Address Field -->
                    <div class="form-field d-flex align-items-center">
                        <input type="email" name="email" id="<?php echo (!empty($email_err)) ? 'is-invalid' : 'email'; ?>" value="<?php echo $email; ?>" type="email" placeholder="Email Address" required>                      
                    </div>
                    <div id="fieldDivs"><span id="fieldErrs"><?php echo $email_err;?></span></div>
                </div>

                <div class="col-sm-6">
                  <!-- Contact Number Field -->
                  <div class="form-field d-flex align-items-center">
                        <input type="number" name="contact" id="<?php echo (!empty($contact_err)) ? 'is-invalid' : 'phone'; ?>" value="<?php echo $contact; ?>" type="tel" placeholder="Contact Number" required>
                  </div>
                  <div id="fieldDivs"><span id="fieldErrs"><?php echo $contact_err;?></span></div>
                </div>
            </div>

            <div class="row">
              <!-- Password Field -->
                <div class="col-sm-6">
                <div class="form-field d-flex align-items-center">
                    <input type="password" name="password" autocomplete="current-password" id="userPass<?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $password; ?>" type="password" placeholder="Password" required>
                    <i class="fas fa-eye" id="togglePassword" style="margin-left: -30px; cursor: pointer;"></i>                       
                    </div>
                    <!-- Passowrd Conditions -->
                    <div id="passwordConditions"><span id="fieldErrs"><?php echo $password_err;?></span></div>               
                 </div>

              <!-- Confirm Password Field -->
              <div class="col-sm-6">
                <div class="form-field d-flex align-items-center">
                    <input type="password" name="conPass" autocomplete="confirm-current-password" id="conPass<?php echo (!empty($conpassword_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $conpassword; ?>" type="password" placeholder="Confirm Password" required>
                    <i class="fas fa-eye" id="toggleConfirmPassword" style="margin-left: -30px; cursor: pointer;"></i>                 
<!--------------------------------------------------- Hidden LatLong Fields----------------------------------------------------------- -->
                  <input type="hidden" id="hidden_lat" name="hidden_lat">
                  <input type="hidden" id="hidden_long" name="hidden_long"> 
                  <input type="hidden" id="hidden_url" name="hidden_url">  
                </div>
              <div id="passwordConditions"> <span id="fieldErrs"><?php echo $conpassword_err;?></span> </div>
            </div>

<!----------------------------------------------------------GeoLoc-------------------------------------------------------------------- -->
            <div class="card mb-3" id="geoDiv">
                <div class="card-header">Pin Location</div>           
                    <div id="map"></div>             
                </div>
            </div>

            <div class="col-sm-12" style="margin:auto;"> 
                    <div class="form-field d-flex align-items-center">
                        <input type="text" name="address" id="fullAdd<?php echo (!empty($address_err)) ? 'is-invalid' : ''; ?>" value ="<?php echo $address; ?>" type="text" placeholder="Full Address" required>
                    </div>
                </div>
<!--------------------------------------------------------End of GeoLoc--------------------------------------------------------------- -->

<!---------------------------------------------------- HIDDEN UPLOAD PROFILE --------------------------------------------------------- -->
    <div class="form-field d-flex align-items-center">
        <input type="file" name="image" hidden="hidden" accept="image/jpg, image/jpeg, image/png" id="picture">
    </div>
<style>

/* --------------------------Terms and COndition--------------------------- */
  /* Custom styles for the disabled button */
  .btn-red[disabled] {
    background-color: #C04C43 !important;
    color: white !important;
    pointer-events: none;
    opacity: 0.65;
  }
</style>

<div class="form-check d-flex justify-content-center mb-5">
  <input class="form-check-input me-2" type="checkbox" value="" id="checkbox" style="margin-top:-1px; border-color:#69707a;" disabled required/>
  <p class="h3">I agree to all the statements in <a href="#!" data-bs-toggle="modal" data-bs-target="#exampleModal" class="h4"><u>Terms of Service</u></a></p>
</div>

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false"> 
<!-- data-bs-backdrop="static" -->
  <div class="modal-dialog modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <strong class="modal-title fs-5" id="exampleModalLabel">Terms & Conditions</strong>
      </div>
      <div class="modal-body" id="modalBody" style="text-align: justify; font-size:14px;">
        <!-- Modal content -->
        <strong style="font-size: 16px;">SafeHome Terms and Conditions</strong><br>

Last Updated: September 2023<br>
<br>
Please read these Terms and Conditions carefully before using SafeHome, a web-based home automation system offered by SafeHome Team. By accessing or using the Service, you agree to be bound by these Terms. If you do not agree to these Terms, please do not use the Service.
<br><br>
1. Acceptance of Terms
<br>
   By using the Service, you agree to comply with and be legally bound by these Terms, whether you are a registered user or not. If you do not agree with these Terms, please refrain from using the Service.
<br><br>
2. Use of the Service
<br>
   a. You must be at least 18 years old to use the Service. By using the Service, you affirm that you are at least 18 years old.
   <br>
   b. You agree to use the Service solely for its intended purpose, which is to provide home automation capabilities for detecting and monitoring emergency situations involving smoke, gas, and water.
   <br>
   c. You are responsible for maintaining the confidentiality of your account information and for all activities that occur under your account. You agree to notify us immediately of any unauthorized use of your account.
<br><br>
3. User Content
<br>
   a. You may submit, upload, or post content, including but not limited to data, text, images, videos, or other materials, to the Service.
   <br>
   b. By submitting User Content to the Service, you grant us a worldwide, non-exclusive, royalty-free, and transferable license to use, reproduce, modify, distribute, and display your User Content for the purpose of providing and improving the Service.
   <br>
   c. You are solely responsible for the accuracy, legality, and appropriateness of your User Content. You may not submit, upload, or post any User Content that violates applicable laws or infringes upon the rights of others.
   <br><br>
4. Privacy Policy
<br>
   Your use of the Service is also governed by our Privacy Policy. By using the Service, you consent to the collection and use of your information as described in the Privacy Policy.
   <br><br>
5. Intellectual Property Rights
<br>
   a. The Service and its original content, features, and functionality are owned by Provider and are protected by international copyright, trademark, patent, trade secret, and other intellectual property or proprietary rights laws.
   <br>
   b. You may not reproduce, distribute, modify, create derivative works of, publicly display, publicly perform, republish, download, or transmit any portion of the Service without the prior written consent of Provider.
   <br><br>
6. Disclaimers and Limitation of Liability
<br>
   a. The Service is provided "as is" and "as available" without any warranties of any kind, either express or implied, including but not limited to the implied warranties of merchantability, fitness for a particular purpose, or non-infringement.
   <br>
   b. Provider does not warrant that the Service will be uninterrupted or error-free, that defects will be corrected, or that the Service is free from viruses or other harmful components.
   <br>
   c. In no event shall Provider be liable for any direct, indirect, incidental, special, consequential, or punitive damages arising out of or in connection with your use of the Service.
   <br><br>
7. Termination
<br>
   Provider may terminate or suspend your access to the Service immediately, without prior notice or liability, for any reason whatsoever, including without limitation if you breach these Terms.
   <br><br>
8. Governing Law
<br>
   These Terms shall be governed and construed in accordance with the laws of our Jurisdication, without regard to its conflict of law provisions.
   <br><br>
9. Changes to Terms
<br>
   Provider reserves the right to modify or replace these Terms at any time. Your continued use of the Service after any such changes constitute your acceptance of the new Terms.
   <br><br>
10. Contact Us
<br>
   If you have any questions or concerns about these Terms, please contact us at safehome42023@gmail.com.
   <br><br><br><br>
By using the Service, you acknowledge that you have read, understood, and agreed to these Terms and Conditions.

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary btn-red" id="closeButton" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<div class="d-flex justify-content-center mt-1">
  <button class="btn btn-red" type="button" id="signupButton" data-bs-toggle="modal" data-bs-target="#signupModal"  disabled><a href="signup.php" class="h5" style="pointer-events: none; text-decoration: none; color: white;">Sign up</a></button>
   <!-- Modal -->
   <div class="modal fade" id="signupModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body" style="text-align:center;">
            <i class="bi bi-envelope-check fa-4x animated rotateIn mb-4" style="color: #C04C43;"></i>
            <p class="text-center p" style="font-size: 18px;">Signed Up Successfully!</p>
            <p class="text-center p" style="font-size: 14px;">We've sent a confirmation code to your email to omplete the registration.</p>
            <button class="btn btn-red" type="submit" onclick="redirectToLogin()" id="signupButton" name="signup" style="width:110px;">Continue</button>
          </div>
        </div>
      </div>
      </div>
</div>
<p class="p">Already have an account? 
  <a href="login.php" class="p"><u>Login here</u></a>
</p>


</section>
<!---------------------------------------------------- GEOLOC JS--------------------------------------------------------- -->
<script>

var popup = L.popup();
let map = new L.map('map');
map.setView([15.49455, 120.96634], 16);

let layer = new L.TileLayer('http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png');
map.addLayer(layer);

let iconOpt = {
    draggable:true
}

// FUNCTION FOR LATLONG

function onMapClick(e) {
       popup
        .setLatLng(e.latlng)
        .setContent(e.latlng.toString())
        .openOn(map);
    lat = e.latlng.lat;
    long = e.latlng.lng;

    
    $('#hidden_long').val(long);
    $('#hidden_lat').val(lat);

    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'getdata.php');
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.send('latlong=' + encodeURIComponent(latlong));

 
    console.log(lat + ", " + long);
}

// FUNCTION FOR AUTO ADDRESS FIELD

function addrClick(e) {
  var latlng = e.latlng;

  // Use reverse geocoding to get the address
  var url = 'https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=' + latlng.lat + '&lon=' + latlng.lng;

  fetch(url)
    .then(function(response) {
      return response.json();
    })
    .then(function(data) {
      var address = data.display_name;

      //var geo_url = 'https://www.openstreetmap.org/#map=17/' + latlng.lat + '/' + latlng.lng + ''; // openstreetmap
      var geo_url = 'http://maps.google.com/maps?f=q&q=' + latlng.lat + ',%20' + latlng.lng + ''; // google maps
      $('#hidden_url').val(geo_url);

      // Place the address in the HTML textbox
      document.getElementById('fullAdd').value = address;
    })
    .catch(function(error) {
      console.log(error);
    });
}

// ONLICK CALL FOR BOTH FUNCTIONS

map.on('click', function(e) {

  addrClick(e); 
  onMapClick(e);

});

</script>

<!--------------------------------------------------------- PASSW CONDITION JS ----------------------------------------------------------->
<script>
var passwordInput = document.getElementById("userPass");
var passwordConditions = document.getElementById("passwordConditions");

passwordInput.addEventListener("input", updatePasswordConditions);

function updatePasswordConditions() {
  var password = passwordInput.value;
  var conditions = [];

  // Check for password conditions
  if (password.length < 8) {
    conditions.push("<span class='invalid-condition'>Password must be at least 8 characters</span>");
  } else {
    conditions.push("<span class='valid-condition'>Password must be at least 8 characters</span>");
  }

  if (!/\d/.test(password)) {
    conditions.push("<span class='invalid-condition'>Password must contain at least one digit</span>");
  } else {
    conditions.push("<span class='valid-condition'>Password must contain at least one digit</span>");
  }

  // Display the password conditions
  passwordConditions.innerHTML = conditions.join("<br>");
}
</script>

<!----------------------------------------------------- PASSWORD EYE -------------------------------------------------------------------->
<script>
    const togglePassword = document.querySelector('#togglePassword');
    const password = document.querySelector('#userPass');

    togglePassword.addEventListener('click', function (e) {
        const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
        password.setAttribute('type', type);
        this.classList.toggle('fa-eye-slash');
    });
</script>

<script>
    const toggleConfirmPassword = document.querySelector('#toggleConfirmPassword');
    const confirm_password = document.querySelector('#conPass');

    toggleConfirmPassword.addEventListener('click', function (e) {
        const type = confirm_password.getAttribute('type') === 'password' ? 'text' : 'password';
        confirm_password.setAttribute('type', type);
        this.classList.toggle('fa-eye-slash');
    });
</script>

<!----------------------------------------------------------- end of Password eye -------------------------------------------------------->
<!----------------------------------------------------------- Terms and Condition JS -------------------------------------------------------->
<script>
  document.addEventListener('DOMContentLoaded', function() {
    var modal = document.getElementById('exampleModal');
    var checkbox = document.getElementById('checkbox');
    var closeButton = modal.querySelector('#closeButton');
    var signupButton = document.getElementById('signupButton');
    var modalBody = document.getElementById('modalBody');
    var isScrollFinished = false;

    modal.addEventListener('shown.bs.modal', function() {
      // Enable the checkbox when the modal is shown
      checkbox.removeAttribute('disabled');
    });

    modalBody.addEventListener('scroll', function() {
      // Check if scroll position is at the end of the modal body
      var isScrollAtEnd = modalBody.scrollHeight - modalBody.scrollTop === modalBody.clientHeight;
      
      // Enable the close button after scrolling to the end of the content
      if (isScrollAtEnd && !isScrollFinished) {
        isScrollFinished = true;
        closeButton.removeAttribute('disabled');
      }
    });

    checkbox.addEventListener('change', function() {
      // Enable the close button and signup button when the checkbox is checked
      if (checkbox.checked) {
        closeButton.removeAttribute('disabled');
        signupButton.removeAttribute('disabled');
      } else {
        closeButton.setAttribute('disabled', 'disabled');
        signupButton.setAttribute('disabled', 'disabled');
      }
    });

    closeButton.addEventListener('click', function() {
      // Enable the checkbox when the close button is clicked
      checkbox.removeAttribute('disabled');
    });

    signupButton.addEventListener('click', function(event) {
      // Check if the checkbox is checked before allowing the signup action
      if (!checkbox.checked) {
        event.preventDefault();
        alert('Please agree to the Terms of Service before signing up.');
      }
    });
  });
</script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.7/dist/umd/popper.min.js" integrity="sha384-zYPOMqeu1DAVkHiLqWBUTcbYfZ8osu1Nd6Z89ify25QV9guujx43ITvfi12/QExE" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.min.js" integrity="sha384-Y4oOpwW3duJdCWv5ly8SCFYWqFDsfob/3GkgExXKV4idmbt98QcxXYs9UoXAB7BZ" crossorigin="anonymous"></script> -->

</body>
</html>