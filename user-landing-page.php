<?php
include_once 'config.php';


?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>SafeHome</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
</head>

<body>
  <!-- Favicons -->
  <link href="assets/img/favicon.png" rel="icon">
  <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css?family=League+Spartan:300,300i,400,400i,600,600i,700,700i|Raleway:300,300i,400,400i,500,500i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
  <link href="assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">

  <!-- Template Main CSS File -->
  <link href="assets/css/user-landing-page.css" rel="stylesheet">
</head>

<body>

  <!-- ======= Header ======= -->
  <header id="header" class="fixed-top d-flex align-items-center">
    <div class="container d-flex align-items-center">

      <div class="logo me-auto">
        <a href="user-landing-page.php"><img src="assets/img/logo1.png" alt="" class="img-fluid"></a></h1>
      </div>

      <nav id="navbar" class="navbar order-last order-lg-0">
        <ul>
          <li><a class="nav-link scrollto active" href="#hero">Home</a></li>
          <li><a class="nav-link scrollto" href="#about">About</a></li>
          <li><a class="nav-link scrollto" href="#services">Services</a></li>
          <li><a class="nav-link scrollto" href="#contact">Contact</a></li>
        </ul>
        <i class="bi bi-list mobile-nav-toggle"></i>
      </nav> <!-- .navbar -->
    </header> <!-- End Header -->

  <!-- ======= Hero Section ======= -->
  <section id="hero" class="d-flex flex-column justify-content-center align-items-center">
    <div class="container text-center text-md-left" data-aos="fade-up">
      <h1>Let's be safe together!</h1>
      <h2>SafeHome, your best home buddy.</h2>
      <a href="login.php" class="btn-get-started scrollto">Get Started  <i class="bi bi-arrow-right-circle-fill"></i></a>
    </div>
  </section><!-- End Hero -->

  <main id="main">

    <!-- ======= What We Provide Section ======= -->
    <section id="what-we-do" class="what-we-do mx-auto">
      <div class="container mx-auto">

        <div class="section-title">
          <h2>What We Provide?</h2>
          <p>Our mission is to make your life easier. We offer a significantly helpful information towards a safe community.</p>
        </div>

        <div class="row mx-auto">
          <div class="col-lg-4 mx-auto">
            <div class="icon-box">
              <div class="icon"><img src = "assets/img/fire-icon.png" alt="" style="height:55px"></i></div>
              <h4><a href="#">Fire Emergency</a></h4>
              <p>We provide precautionaries regarding fire emergencies.</p>
            </div>
          </div>

          <div class="col-lg-4 mx-auto">
            <div class="icon-box">
              <div class="icon"><i><img src = "assets/img/gas-icon.png" alt="" style="height:55px"></i></div>
              <h4><a href="#">Gas Leakage Emergency</a></h4>
              <p>We provide precautionaries regarding gas leakage emergencies.</p>
            </div>
          </div>

          <div class="col-lg-4 mx-auto">
            <div class="icon-box">
              <div class="icon"><i><img src = "assets/img/flood-icon.png" alt="" style="height:55px"></i></div>
              <h4><a href="#">Flood Emergency</a></h4>
              <p>We provide precautionaries regarding flood emergencies.</p>
            </div>
          </div>

        </div>

      </div>
    </section><!-- End What We Provide Section -->

    <!-- ======= About Section ======= -->
    <section id="about" class="about">
      <div class="container">

        <div class="row">
          <div class="col-lg-6">
            <img src="assets/img/logo-about.png" class="img-fluid" alt="">
          </div>
          <div class="col-lg-6 pt-4 pt-lg-0">
            <h3>About Us</h3>
            <p>
            Safehome enriches the purpose of having a safe household with its reliable tracking emergency system. Safehome is a device for homeowners of areas known for being:
            </p>
            <ul>
              <li><i class="bx bx-check-double"></i> Flood prone</li>
              <li><i class="bx bx-check-double"></i> Narrow houses suspected with fast escalation of fire and gas leakage emergencies</li>
            </ul>
            <div class="row icon-boxes">
              <div class="col-md-6">
                <i class="bx bx-receipt"></i>
                <h4>Mission</h4>
                <p>We are dedicated as we see the world where technology enhances human lives. It directs us onto this integration that everyone can benefit from.</p>
              </div>
              <div class="col-md-6 mt-4 mt-md-0">
                <i class="bx bx-cube-alt"></i>
                <h4>Vision</h4>
                <p>We vision a goal that bring meaningful changes on how people give importance in each others' safety. We're rooted in providing the best services and offer satisfying results.</p>
              </div>
            </div>
          </div>
        </div>

      </div>
    </section><!-- End About Section -->

    <!-- ======= Services Section ======= -->
    <section id="services" class="services section-bg">
      <div class="container">

        <div class="section-title">
          <h2>Services</h2>
          <p>Safehome is your companion for emergency situations.<br>Making it possible to deliver the best services and ensure the quality of safeness inside the household.</p>
        </div>

        <div class="row">
          <div class="col-md-6">
            <div class="icon-box">
            <i><img src = "assets/img/safety.png" alt="" style="height:55px"></i>
              <h4>Safety</h4>
              <p>Safety is our top priority. Emergencies can possibly happen inside our houses in any various form. May it be accidentally or natural phenomenon.</p>
            </div>
          </div>
          <div class="col-md-6 mt-4 mt-lg-0">
            <div class="icon-box">
              <i><img src = "assets/img/security.png" alt="" style="height:55px"></i>
              <h4>Security</h4>
              <p>User's security are safe with our hands. Reports and logs are kept by trusted admins capable in inceasing the assurance of securing data.</p><br>
            </div>
          </div>
          <div class="col-md-6 mt-4">
            <div class="icon-box">
              <i><img src = "assets/img/wifi-connect.png" alt="" style="height:53px"x></i>
              <h4>Internet Connectivity</h4>
              <p>Safehome delivers a convenient way connecting to your devices, making it easier for easy tracking of suspected emergency.</p><br>
            </div>
          </div>
          <div class="col-md-6 mt-4">
            <div class="icon-box">
              <i><img src = "assets/img/emer-sensors.png" alt="" style="height:55px"></i>
              <h4>Emergency Sensors</h4>
              <p>Essential sensors are integrated to fully maximize the effort of detecting emergencies. Sensors elevate the purpose of Safehome as it initializes the emergency levels.</p>
            </div>
          </div>
          <div class="col-md-6 mt-4">
            <div class="icon-box">
              <i><img src = "assets/img/alert-notif.png" alt="" style="height:53px"></i>
              <h4>Alert Notification</h4>
              <p>Notifying homeowners, LGUs and BFP based on emergency level the Safehome detected. Significantly enhancing the overall capability of the device.</p>
            </div>
          </div>
        </div>

      </div>
    </section><!-- End Services Section -->

    <!-- ======= Contact Section ======= -->
    <section id="contact" class="contact section-bg">
      <div class="container">

        <div cloass="section-title">
          <h2>Contact</h2>
          <p>For more details and information, you can contact us for further questions.</p>
        </div>

        <div class="row mt-5 justify-content-center">

          <div class="col-lg-10">

            <div class="info-wrap">
              <div class="row">
                <div class="col-lg-4 info">
                  <i class="bi bi-geo-alt"></i>
                  <h4>Location:</h4>
                  <p>Aduas Sur, Cabanatuan City<br>Nueva Ecija, 3100</p>
                </div>

                <div class="col-lg-4 info mt-4 mt-lg-0">
                  <i class="bi bi-envelope"></i>
                  <h4>Email:</h4>
                  <p>safehome42023@gmail.com</p>
                </div>

                <div class="col-lg-4 info mt-4 mt-lg-0">
                  <i class="bi bi-phone"></i>
                  <h4>Call:</h4>
                  <p>+63 928 838 2725</p>
                </div>
              </div>
            </div>

          </div>

        </div>

      </div>
    </section><!-- End Contact Section -->

  </main><!-- End #main -->

  <!-- ======= Footer ======= -->
  <footer id="footer">

    <div class="container d-md-flex py-4">

      <div class="me-md-auto text-center text-md-start">
        <div class="copyright">
          &copy; Copyright <strong><span>SafeHome</span></strong>. All Rights Reserved
        </div>
      </div>
  </footer><!-- End Footer -->

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>


</body>

</html>