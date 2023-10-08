<?php 
require "config.php";
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("location: login.php");
    exit();
}

// Retrieve the username and password from the session
$username = $_SESSION['username'];
$password = $_SESSION['password'];

?>

<!doctype html>
<html lang="en">
<head>

    
    <title>Profile</title>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="assets/css/user-view-profile.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css" crossorigin=""/>
    <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js" crossorigin=""></script>

<style>
        #map{
    width: 100%;
    height: 40vh;
    overflow: hidden;
    }

    img {
  max-width: 300px;
  height: auto;
  margin-top: 10px;
}

  .address-cell {
    overflow-x: auto;   
    max-width: 500px; 
  }

    /* Add styles to the "Change Password" container to keep it at the bottom */
    .change-password-button {
        align-self: flex-end;
        margin-top: 360px; /* Add spacing between content and link */
        font-size: 0.8rem;
        color:#039BE5;
    }

    .content {
  border-right: 1px solid rgba(33, 40, 50, 0.125);
}

#geoDiv{
  padding: 5px 10px 10px 50px;
}

@media(max-width: 578px) {

  .btn.btn-save i {
    display: none;
  }

  .btn.btn-save{
    float: left;
    margin-left: 5px;
    width: 38px;
    height: 60px;
  }

  .change-password-button {
    background-color: #039BE5;
    color: white; /* Set the desired font color here */
    width: 130px;
    padding: 10px;
    text-align: center;
    border-radius: 50px;
    cursor: pointer;
    margin-top: -5px;
    font-size: 0.8rem;
    float: right;
    margin-right: 10px;
    border-radius: 25px;
    box-shadow: 3px 3px 3px #b1b1b1,
            -3px -3px 3px #fff;
        letter-spacing: 0.5px;
  }

  #geoDiv{
  padding: 15px 10px 10px 35px;
  }

  .content {
    border-right: none; /* Remove the border in mobile view */
  }

  .wrapper {
    margin-top: -190px;
    height: 1000px; 
  }

  #hero {
    padding: 25px;
  }

  .table {
    margin-top: 30px;
  }

  .table td{
    width: 1px;
  }
/* 
  .address-cell {
    overflow-x: auto;   
    max-width: 500px; 
  } */

}



    </style>
    </head>

<body>
  <!-- Favicons -->
  <link href="assets/img/favicon.ico" rel="favicon">
  <link href="assets/img/favicon.ico" rel="favicon">

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css?family=League+Spartan:300,300i,400,400i,600,600i,700,700i|Raleway:300,300i,400,400i,500,500i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

<?php
  require_once 'header.php';
?> 
</body>

    
<body>
<section id="hero" class="d-flex flex-column justify-content-center align-items-center">
    <div class="wrapper text-center text-xl-center" style="width: 100%; max-width: 1100px; padding: 20px;">
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
            $address = $info['address'];
            $contact = $info['contact'];
            $picture = $info['picture'];
        ?>

  <main class="container mb-5">
      <div class="row">
  <div class="col-lg-3 content mx-auto">
    <!-- Profile image and details -->
    <?php 
    if ($picture == '') {
        echo '<img class="img-account-profile" style="border-radius:50%; width: 120px; height: 120px; object-fit: cover; object-position: 25% 25%;" src="assets/img/default-avatar.png">';
    } else {
        echo '<img class="img-account-profile" style="border-radius:50%; width: 120px; height: 120px; object-fit: cover; object-position: 25% 25%;" src="assets/uploaded-img/' . $picture . '">';
    }
    ?>
    <p style="margin-top:5px; font-size:18px;">
      <?php echo ucwords($firstname) . ' ' . ucwords($lastname); ?>
    </p>
    <a href="user-edit-profile.php"><button class="btn btn-save" style="background-color: #039BE5; width: 120px; color: white; margin-top:-5px;">
       <i class="bi bi-pencil-square"></i><a href="user-edit-profile.php"> Edit Profile</a></button></a>
       <div class="change-password-button" onclick="location.href='modal-page.php'">
  Change Password
</div>

  </div>
  <div class="col-lg-9">
    <div class="row">
      <div class="col-lg-12">
        <table class="table" style="text-align:left; color:#4c4d4f;">
            <th colspan="2">Account Information</th>
          <tbody>
            <tr>
              <td>Full Name</td>
              <td class="field-cell"><?php echo ucwords($firstname) . ' ' . ucwords($lastname); ?></td>
            </tr>
            <tr>
              <td>Username</td>
              <td><?php echo ($username); ?></td>
            </tr>
            <tr>
              <td>Email Address</td>
              <td class="field-cell"><?php echo ($email); ?></td>
            </tr>
            <tr>
              <td>Phone Number</td>
              <td><?php echo ($contact); ?></td>
            </tr>
            <tr>
              <td>Full Address</td>
              <td class="address-cell"><?php echo ucwords($address); ?></td>
            </tr>
          </tbody>
        </table>
  </div>
        <div class="row" >
      <div class="col-lg-12" id="geoDiv" style="margin:auto; width: 100%; height: 43vh;">
        <div id="map"></div> 
  </div>
     </div>
    </div>
  </div>
</div>
        <?php
        }
        ?>
        </main>
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