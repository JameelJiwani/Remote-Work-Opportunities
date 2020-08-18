<?php
  // Include config file
  require_once "logindb.php";

  // Define variables and initialize with empty values
  $email = $password = $confirm_password = $lastname = $firstname = "";
  $email_err = $password_err = $confirm_password_err = $firstname_err = $lastname = "";

  // Processing form data when form is submitted
  if($_SERVER["REQUEST_METHOD"] == "POST"){

    try {
      $conn = new mysqli($hn, $un, $pw, $db);
    } catch(Exception $e) {
      echo 'Connection Error.';
    }

    // Validate email
    if(empty(trim($_POST["email"]))){
      $email_err = "Please enter an email.";
    } else {

      // Set parameters
      $param_email = trim($_POST["email"]);

      // Prepare a select statement
      $query  = "SELECT userId FROM users WHERE email = '$param_email'";

      // Attempt to execute the prepared statement
      try {
        $result = $conn->query($query);
      } catch(Exception $e) {
        echo 'Oops! Something went wrong. Please try again later.';
      }

      if($result){
        $rows = $result->num_rows;

        if($rows == 1) {
          $email_err = "This email is already in use.";
        } else {
          $email = trim($_POST["email"]);
        }
      }

      // Close statement
      $result->close();
    }

    // Validate firstname
    if(empty(trim($_POST["firstname"]))){
      $firstname_err = "Please enter a first name.";
    } else{
      $firstname = trim($_POST["firstname"]);
    }

    // Validate lastname
    if(empty(trim($_POST["lastname"]))){
      $lastname_err = "Please enter a last name.";
    } else{
      $lastname = trim($_POST["lastname"]);
    }

    // Validate password
    if(empty(trim($_POST["password"]))){
      $password_err = "Please enter a password.";
    } elseif(strlen(trim($_POST["password"])) < 6){
      $password_err = "Password must have atleast 6 characters.";
    } else{
      $password = trim($_POST["password"]);
    }

    // Validate confirm password
    if(empty(trim($_POST["confirm_password"]))){
      $confirm_password_err = "Please confirm password.";
    } else{
      $confirm_password = trim($_POST["confirm_password"]);
      if(empty($password_err) && ($password != $confirm_password)){
        $confirm_password_err = "Password did not match.";
      }
    }

    // Check input errors before inserting in database
    if(empty($email_err) && empty($password_err) && empty($confirm_password_err)){

      // Set parameters
      $param_email = $email;
      $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash

      // Prepare an insert statement
      $query = "INSERT INTO users (email, password, firstname, lastname, user_code) VALUES ('$param_email', '$param_password', '$firstname', '$lastname', '4')";
      // 4 is the user_code for candidates

      // Attempt to execute the prepared statement
      try {
        $result = $conn->query($query);
        if (!$result) echo "INSERT failed: $query<br>" .
            $conn->error . "<br><br>";
        header("location: login.php");
      } catch(Exception $e) {
        echo "Something went wrong. Please try again later.";
      }

      // Close statement
      $result->close();
    }

    // Close connection
    $conn->close();
  }
?>

 <!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>SB Admin 2 - Register</title>

  <!-- Custom fonts for this template-->
  <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

  <!-- Custom styles for this template-->
  <link href="css/sb-admin-2.min.css" rel="stylesheet">

</head>

<body class="bg-gradient-primary">

  <div class="container">

    <div class="card o-hidden border-0 shadow-lg my-5">
      <div class="card-body p-0">
        <!-- Nested Row within Card Body -->
        <div class="row">
          <div class="col-lg-5 d-none d-lg-block bg-register-image"></div>
          <div class="col-lg-7">
            <div class="p-5">
              <div class="text-center">
                <h1 class="h4 text-gray-900 mb-4">Create an Account!</h1>
              </div>
              <form class="user" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <div class="form-group row">
                  <div class="col-sm-6 mb-3 mb-sm-0">
                    <input type="text" class="form-control form-control-user" id="exampleFirstName" name="firstname" placeholder="First Name" required>
                  </div>
                  <div class="col-sm-6">
                    <input type="text" class="form-control form-control-user" id="exampleLastName" placeholder="Last Name" name="lastname" required>
                  </div>
                </div>
                <div class="form-group">
                  <input type="email" name="email" class="form-control form-control-user" id="exampleInputEmail" placeholder="Email Address" value="<?php echo $email; ?>" required>
                  <span style="color: #3c763d;"><?php echo $email_err; ?></span>
                </div>
                <div class="form-group row">
                  <div class="col-sm-6 mb-3 mb-sm-0">
                    <input type="password" name="password" class="form-control form-control-user" id="exampleInputPassword" placeholder="Password" value="<?php echo $password; ?>" required>
                    <span style="color: #3c763d;"><?php echo $password_err; ?></span>
                  </div>
                  <div class="col-sm-6">
                    <input type="password" name="confirm_password" class="form-control form-control-user" id="exampleRepeatPassword" placeholder="Repeat Password" value="<?php echo $confirm_password; ?>">
                    <span style="color: #3c763d;"><?php echo $confirm_password_err; ?></span>
                  </div>
                </div>
                <button type="submit" class="btn btn-primary btn-user btn-block">
                  Register Account
                </button>
                <hr>
                <a href="index.html" class="btn btn-google btn-user btn-block">
                  <i class="fab fa-google fa-fw"></i> Register with Google
                </a>
                <a href="index.html" class="btn btn-facebook btn-user btn-block">
                  <i class="fab fa-facebook-f fa-fw"></i> Register with Facebook
                </a>
              </form>
              <hr>
              <div class="text-center">
                <a class="small" href="forgot-password.html">Forgot Password?</a>
              </div>
              <div class="text-center">
                <a class="small" href="login.php">Already have an account? Login!</a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

  </div>

  <!-- Bootstrap core JavaScript-->
  <script src="vendor/jquery/jquery.min.js"></script>
  <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Core plugin JavaScript-->
  <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

  <!-- Custom scripts for all pages-->
  <script src="js/sb-admin-2.min.js"></script>

</body>

</html>
