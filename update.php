<?php
// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values
$firstname = $lastname = $email = $username = $password = $address = $contact ="";
$firstname_err = $lastname_err = $email_err = $username_err = $password_err = $address_err = $contact_err ="";
 
// Processing form data when form is submitted
if(isset($_POST["id"]) && !empty($_POST["id"])){
    // Get hidden input value
    $id = $_POST["id"];
    
    // Validate name
    $input_firstname = trim($_POST["firstname"]);
    if(empty($input_firstname)){
        $firstname_err = "Please enter a firstname.";
    } elseif(!filter_var($input_firstname, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/")))){
        $firstname_err = "Please enter a valid firstname.";
    } else{
        $firstname = $input_firstname;
    }

    $input_lastname = trim($_POST["lastname"]);
    if(empty($input_lastname)){
        $lastname_err = "Please enter a lastname.";
    } elseif(!filter_var($input_lastname, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/")))){
        $lastname_err = "Please enter a valid lastname.";
    } else{
        $lastname = $input_lastname;
    }

    $input_email = trim($_POST["email"]);
    if(empty($input_email)){
        $email_err = "Please enter an email.";
    } else{
        $email = $input_email;
    }

    $input_username = trim($_POST["username"]);
    if(empty($input_username)){
        $username_err = "Please enter a username.";
    } else{
        $username = $input_username;
    }

    $input_password = trim($_POST["password"]);
    if(empty($input_password)){
        $password_err = "Please enter a password.";
    } elseif(!filter_var($input_password, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/")))){
        $password_err = "Please enter a password.";
    } else{
        $password = $input_password;
    }
    // Validate address
    $input_address = trim($_POST["address"]);
    if(empty($input_address)){
        $address_err = "Please enter an address.";     
    } else{
        $address = $input_address;
    }
    
    // Validate contact
    $input_contact = trim($_POST["contact"]);
    if(empty($input_contact)){
        $contact_err = "Please enter the contact amount.";     
    } elseif(!ctype_digit($input_contact)){
        $contact_err = "Please enter a valid contact no.";
    } else{
        $contact = $input_contact;
    }
    
    // Check input errors before inserting in database
    if(empty($firstname_err) && empty($lastname_err) && empty($email_err) && empty($username_err) && empty($password_err) && empty($address_err) && empty($contact_err)){
        // Prepare an update statement
        $sql = "UPDATE residents1 SET firstname=?, lastname=?, email=?, username=?, password=?, address=?, contact=? WHERE id=?";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ssssssss", $param_firstname,$param_lastname, $param_email,$param_username, $param_password, $param_address, $param_contact, $param_id);
            
            // Set parameters
            $param_firstname = $firstname;
            $param_lastname = $lastname;
            $param_email = $email;
            $param_username = $username;
            $param_password = $password;
            $param_address = $address;
            $param_contact = $contact;
            $param_id = $id;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Records updated successfully. Redirect to landing page
                header("location: index1.php");
                exit();
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
         
        // Close statement
        mysqli_stmt_close($stmt);
    }
    
    // Close connection
    mysqli_close($link);
} else{
    // Check existence of id parameter before processing further
    if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
        // Get URL parameter
        $id =  trim($_GET["id"]);
        
        // Prepare a select statement
        $sql = "SELECT * FROM residents1 WHERE id = ?";
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_id);
            
            // Set parameters
            $param_id=$id;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                $result = mysqli_stmt_get_result($stmt);
    
                if(mysqli_num_rows($result) == 1){
                    /* Fetch result row as an associative array. Since the result set
                    contains only one row, we don't need to use while loop */
                    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                    
                    // Retrieve individual field value
                    $firstname = $row["firstname"];
                    $lastname = $row["lastname"];
                    $username = $row["username"];
                    $email = $row["email"];
                    $password = $row["password"];
                    $address = $row["address"];
                    $contact = $row["contact"];
                } else{
                    // URL doesn't contain valid id. Redirect to error page
                    header("location: error.php");
                    exit();
                }
                
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
        
        // Close statement
        mysqli_stmt_close($stmt);
        
        // Close connection
        mysqli_close($link);
    }  else{
        // URL doesn't contain id parameter. Redirect to error page
        header("location: error.php");
        exit();
    }
}
?>
 

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="assets/css/signup.css">
    <title>Update</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
  </head>
  <body class="body">
    <section id="hero" class="d-flex flex-column justify-content-center align-items-center">
        <div class="container text-center text-md-left">
          <div class="mb-container text-center text-md-left">
    <div class="card mx-auto">
        <div class="logo">
            <img src="logo.png" alt="">
        </div>
        <div class="text-center mt-4 name">
            UPDATE AN ACCOUNT
        </div>
                <form class="p-3 mt-3"action="<?php echo htmlspecialchars($_SERVER["REQUEST_URI"]); ?>" method="post">
                  <div class="row">
                    <div class="col-sm-4">
                  <div class="form-field d-flex align-items-center">
                      <input type="text" name="firstname" id="fName<?php echo (!empty($firstname_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $firstname; ?>" type="text" placeholder="First Name" required>
                      <span class="far fa-user"><?php echo $firstname_err;?></span>
                  </div>
                  </div>
                  <div class="col-sm-4">
                    <div class="form-field d-flex align-items-center">
                      <input type="text" name="lastname" id="lastname<?php echo (!empty($lastname_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $lastname; ?>" type="text" placeholder="Last Name" required>
                      <span class="fas fa-key"><?php echo $lastname_err;?></span>
                  </div>
                  </div>
                  <div class="col-sm-4">
                    <div class="form-field d-flex align-items-center">
                      <input type="text" name="username" id="username<?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $username; ?>" type="text" placeholder="Username" required>
                      <span class="fas fa-key"><?php echo $username_err;?></span>
                  </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-sm-6">
                    <div class="form-field d-flex align-items-center">
                        <input type="email" name="email" id="email<?php echo (!empty($email_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $email; ?>" type="email" placeholder="Email" required>
                        <span class="far fa-user"><?php echo $email_err;?></span>
                    </div>
                  </div>
                <div class="col-sm-6">
                  <div class="form-field d-flex align-items-center">
                    <input type="number" name="contact" id="phone<?php echo (!empty($contact_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $contact; ?>" type="tel" placeholder="Contact Number" required>
                    <span class="fas fa-key"><?php echo $contact_err;?></span>
                </div>
                </div>
              </div>

              <div class="row">
                <div class="col-sm-12">
              <div class="form-field d-flex align-items-center">
                  <input type="text" name="address" id="fullAdd<?php echo (!empty($address_err)) ? 'is-invalid' : ''; ?>"<?php echo $address; ?> type="text" placeholder="Full Address" required>
                  <span class="far fa-user"><?php echo $address_err;?></span>
              </div>
              </div>
            </div>
            <div class="row">
              <div class="col-sm-6">
              <div class="form-field d-flex align-items-center">
                <input type="password" name="password" id="userPass<?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $password; ?>" type="password" placeholder="Password" required>
                <span class="far fa-user"><?php echo $password_err;?></span>
              </div>
              </div>
              <div class="col-sm-6">
                <div class="form-field d-flex align-items-center">
                  <span class="fas fa-key"></span>
                  <input type="password" name="conPass" id="conPass" type="password" placeholder="Confirm Password" required>
              </div>
              </div>
                <div class="form-check d-flex justify-content-center mb-5">
                  <input class="form-check-input me-2 " type="checkbox" value="" id="checkbox" />
                  <p class="h3">I agree all statements in <a href="#!"
                    class="h4"><u>Terms of service</u></a></p>
                </div>

                <div class="d-flex justify-content-center">
                  <button class="btn mt-3"><p href="index1.php"class="h5">Update</p></button>
                </div>
                

              </form>
        </div>
    </div>
    <!-- <div class="col"></div> -->
    <!-- </div> -->
</section>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.7/dist/umd/popper.min.js" integrity="sha384-zYPOMqeu1DAVkHiLqWBUTcbYfZ8osu1Nd6Z89ify25QV9guujx43ITvfi12/QExE" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.min.js" integrity="sha384-Y4oOpwW3duJdCWv5ly8SCFYWqFDsfob/3GkgExXKV4idmbt98QcxXYs9UoXAB7BZ" crossorigin="anonymous"></script>
</body>
</html>