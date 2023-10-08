<?php
include "config.php";
require_once('PHPMailer/PHPMailerAutoload.php');

session_start();

date_default_timezone_set('Asia/Manila');
$date = date('F d, Y h:i:sA');

function logs($date, $username, $details)
{
    $format = $date . "\t" . $username . "\t" . $details . "\n";
    file_put_contents("sample.log", $format, FILE_APPEND);
}

if (isset($_SESSION["locked"])) {
    $difference = time() - $_SESSION["locked"];
    if ($difference > 20) {
        unset($_SESSION["locked"]);
        unset($_SESSION["login_attempts"]);
    }
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    $salt = "b2a6ec273b45b1d0ab06130cb4e2e62d"; //sunog
    $hashed = md5($salt . $password);
    $status = "verified";

    $link = mysqli_connect("localhost", "root", "", "demo1");
    if ($link === false) {
        die("ERROR: Could not connect. " . mysqli_connect_error());
    }

    $result = mysqli_query($link, "SELECT * FROM residents1 WHERE username = '$username'");

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $storedPassword = $row['password'];

        if ($hashed == $storedPassword) {
            $_SESSION["username"] = $row['username'];
            $_SESSION["password"] = $row['password'];
            $storedStatus = $row['verification_status'];
            
            if ($storedStatus == $status) { // Check if user status is verified
                $_SESSION['id'] = $row['id'];
                $_SESSION['firstname'] = $row['firstname'];
                $_SESSION['lastname'] = $row['lastname'];
                $_SESSION['username'] = $row['username'];
                $_SESSION['email'] = $row['email'];
                $_SESSION['password'] = $row['password'];
                $_SESSION['address'] = $row['address'];
                $_SESSION['contact'] = $row['contact'];
                $_SESSION['picture'] = $row['picture'];
                $_SESSION['longitude'] = $row['longitude'];
                $_SESSION['latitude'] = $row['latitude'];

                $username = $_SESSION['username'];
                $details = "Login";
                logs($date, $username, $details);
                
                unset($_SESSION["login_attempts"]);

                
                // unset($_SESSION['code_sent']);
                header("Location: user-home.php");
                exit;
            } else {
                // $_SESSION['error'] = "It looks like you haven't yet verified your email.";
                header('Location: user-signup-code.php');
                exit;
            }
        } else {
            $_SESSION["login_attempts"] = isset($_SESSION["login_attempts"]) ? $_SESSION["login_attempts"] + 1 : 1;
            $_SESSION["error"] = "Password does not match";
            if ($_SESSION["login_attempts"] >= 3) {
                $_SESSION["locked"] = time();
            }
        }
    } else {
        $_SESSION["login_attempts"] = isset($_SESSION["login_attempts"]) ? $_SESSION["login_attempts"] + 1 : 1;
        $_SESSION["error"] = "Username not found";
        if ($_SESSION["login_attempts"] >= 3) {
            $_SESSION["locked"] = time();
        }
    }
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="assets/css/login.css">
    <!-- for password eye -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <!-- end of password eye -->
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
          <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="body">
<section id="hero" class="d-flex flex-column justify-content-center align-items-center">
    <div class="container text-center text-md-left">
        <div class="mb-container text-center text-md-left">
            <div class="wrapper mx-auto">
                <div class="logo">
                    <img src="logo.png" alt="">
                </div>
                <br>
                <?php if (isset($_SESSION["error"])) { ?>
                    <p style="color:red;"><?= $_SESSION["error"]; ?></p>
                    <?php unset($_SESSION["error"]);
                } ?>


                <form class="p-3 mt-3" method="post">
                    <div class="d-flex align-items-center ">
                        <input type="text" name="username" placeholder="Username" required style="padding-left: 10px; margin-bottom: 20px; border-radius: 10px; width: 100%; padding: 10px 10px 10px 10px; border-color: #3d343468; ">
                    </div>
                    <div class="d-flex align-items-center">
                        <input type="password" name="password" placeholder="Password" autocomplete="current-password" id="userPass" required style="padding-left: 10px; margin-bottom: 20px; border-radius: 10px; width:100%; padding: 10px 10px 10px 10px;border-color: #3d343468;">
                        <i class="fas fa-eye" id="togglePassword" style="cursor: pointer; margin-left: -35px; color:#69707a; margin-bottom: 20px;"></i>
                    </div>
                    <div class="text-center fs-6">
                        <a href="user-forgot-pass.php">Forgot password?</a>
                    </div>
                    <div class="text-center fs-6">
                        <a href="signup.php" class="p" style= color:black;>Don't have an account?
                            <u style=color:#03A9F4;>Signup here</u></a>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <?php if (isset($_SESSION["locked"])) { ?>
                                <div id="countdownWrapper"><br>
                                    <p>Please wait for <span id="countdown">20</span> seconds...</p>
                                </div>
                                <div id="loginBtnWrapper" style="display: none;">
                                    <button type="submit" class="btn mt-3">Login</button>
                                </div>
                                <script>
                                    function startCountdown() {
                                        var seconds = 20;
                                        var countdownElement = document.getElementById("countdown");
                                        countdownElement.innerText = seconds;

                                        var countdownInterval = setInterval(function () {
                                            seconds--;
                                            countdownElement.innerText = seconds;

                                            if (seconds <= 0) {
                                                clearInterval(countdownInterval);
                                                document.getElementById("countdownWrapper").style.display = "none";
                                                document.getElementById("loginBtnWrapper").style.display = "block";
                                            }
                                        }, 1000);
                                    }

                                    setTimeout(function () {
                                        startCountdown();
                                    }, 1000);
                                </script>
                            <?php } elseif (!isset($_SESSION["login_attempts"]) || $_SESSION["login_attempts"] < 3) { ?>
                                <div id="loginBtnWrapper">
                                    <button type="submit" class="btn mt-3">Login</button>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </form>
                <i class="mobile-view"></i>
            </div>
        </div>
    </div>
</section>

<!-- password eye -->
<script>
    const togglePassword = document.querySelector('#togglePassword');
    const password = document.querySelector('#userPass');

    togglePassword.addEventListener('click', function (e) {
        const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
        password.setAttribute('type', type);
        this.classList.toggle('fa-eye-slash');
    

     setTimeout(function () {
            password.setAttribute('type', 'password');
            togglePassword.classList.toggle('fa-eye-slash', false);
        }, 500);
    });
</script>
<!-- end of password eyes -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe"
        crossorigin="anonymous"></script>
</body>
</html>
