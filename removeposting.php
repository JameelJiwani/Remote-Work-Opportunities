<?php
  // Include config file
  require_once "logindb.php";

  // Define variables and initialize with empty values
  $postingId = htmlspecialchars($_GET["postingId"]);

  // Check input errors before inserting in database
  if(!empty($postingId)){

    try {
      $conn = new mysqli($hn, $un, $pw, $db);
    } catch(Exception $e) {
      echo 'Connection Error.';
    }

    // Prepare a delete statement
    $query = "DELETE FROM `postings` WHERE postingId = '".$postingId."'";

    // Attempt to execute the prepared statement
    try {
      $result = $conn->query($query);
      if (!$result) echo "DELETE failed: $query<br>" .
          $conn->error . "<br><br>";
      header("location: mypostings.php");
    } catch(Exception $e) {
      echo "Something went wrong. Please try again later.";
    }

    // Close statement
    $result->close();

    // Close connection
    $conn->close();

  } else {
    header("location: mypostings.php");
  }
?>
