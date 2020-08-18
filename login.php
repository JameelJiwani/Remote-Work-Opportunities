<?php
// Initialize the session
session_start();

// Check if the user is already logged in, if yes then redirect him to welcome page
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    header("location: index.php");
    exit;
}

// Include config file
require_once "logindb.php";

// Define variables and initialize with empty values
$email = $password = "";
$email_err = $password_err = "";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){

  // Check if email is empty
  if(empty(trim($_POST["email"]))){
    $email_err = "Please enter email.";
  } else {
    $email = trim($_POST["email"]);
  }

  // Check if password is empty
  if(empty(trim($_POST["password"]))){
    $password_err = "Please enter your password.";
  } else {
    $password = trim($_POST["password"]);
  }

  // Validate credentials
  if(empty($email_err) && empty($password_err)){

    try {
      $conn = new mysqli($hn, $un, $pw, $db);
    } catch(Exception $e) {
      echo 'Connection Error.';
    }

    // Set parameters
    $param_email = $email;

    // Prepare a select statement
    $query = "SELECT userId, email, password, user_code FROM users WHERE email = '$param_email'";

    // Attempt to execute the prepared statement
    try {
      $result = $conn->query($query);
    } catch(Exception $e) {
      echo 'Oops! Something went wrong. Please try again later.';
    }

    if($result){
      $rows = $result->num_rows;

      if($rows == 1) {
        // Bind result variables
        $row = $result->fetch_array(MYSQLI_NUM);
        $userId = $row[0];
        $email = $row[1];
        $hashed_password = $row[2];
        $user_code = $row[3];
        if(password_verify($password, $hashed_password)){
          // Password is correct, so start a new session
          session_start();

          // Store data in session variables
          $_SESSION["loggedin"] = true;
          $_SESSION["userId"] = $userId;
          $_SESSION["email"] = $email;
          $_SESSION["user_code"] = $user_code;

          // Redirect user to welcome page
          header("location: index.php");
        } else{
          // Display an error message if password is not valid
          $password_err = "The password you entered was not valid.";
        }
      } else {
        // Display an error message if email doesn't exist
        $email_err = "No account found with that email.";
      }
    }

    // Close statement
    $result->close();

    // Close connection
    $conn->close();
  }
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

  <title>SB Admin 2 - Login</title>

  <!-- Custom fonts for this template-->
  <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

  <!-- Custom styles for this template-->
  <link href="css/sb-admin-2.min.css" rel="stylesheet">

</head>

<body class="bg-gradient-primary">

  <div class="container">

    <!-- Outer Row -->
    <div class="row justify-content-center">

      <div class="col-xl-10 col-lg-12 col-md-9">

        <div class="card o-hidden border-0 shadow-lg my-5">
          <div class="card-body p-0">
            <!-- Nested Row within Card Body -->
            <div class="row">
              <div class="col-lg-6 d-none d-lg-block bg-login-image"></div>
              <div class="col-lg-6">
                <div class="p-5">
                  <div class="text-center">
                    <h1 class="h4 text-gray-900 mb-4">Welcome Back!</h1>
                  </div>
                  <form class="user" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                    <div class="form-group">
                      <input type="email" class="form-control form-control-user" id="exampleInputEmail" aria-describedby="emailHelp" placeholder="Enter Email Address..." name="email" value="<?php echo $email; ?>" required>
                      <span style="color: #3c763d;"><?php echo $email_err; ?></span>
                    </div>
                    <div class="form-group">
                      <input type="password" name="password" class="form-control form-control-user" id="exampleInputPassword" placeholder="Password">
                      <span style="color: #3c763d;"><?php echo $password_err; ?></span>
                    </div>
                    <div class="form-group">
                      <div class="custom-control custom-checkbox small">
                        <input type="checkbox" class="custom-control-input" id="customCheck">
                        <label class="custom-control-label" for="customCheck">Remember Me</label>
                      </div>
                    </div>
                    <button type="submit" class="btn btn-primary btn-user btn-block">
                      Login
                    </button>
                    <hr>
                    <a href="index.php" class="btn btn-google btn-user btn-block">
                      <i class="fab fa-google fa-fw"></i> Login with Google
                    </a>
                    <a href="index.php" class="btn btn-facebook btn-user btn-block">
                      <i class="fab fa-facebook-f fa-fw"></i> Login with Facebook
                    </a>
                  </form>
                  <hr>
                  <div class="text-center">
                    <a class="small" href="forgot-password.php">Forgot Password?</a>
                  </div>
                  <div class="text-center">
                    <a class="small" href="register.php">Create an Account!</a>
                  </div>
                </div>
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
