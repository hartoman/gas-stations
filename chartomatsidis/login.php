<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <!-- custom title-->
  <title>Είσοδος</title>
  <!-- include Bootstrap css-->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous" />
  <!-- include custom css-->
  <link rel="stylesheet" href="style.css" />
</head>

<!-- header start -->
<?php include "./includes/header.inc.php";
include 'dbh.php'; ?>
<!-- header end -->

<?php // checks if the user is logged in
session_start();
?>

<body>
  <!--main container-->
  <div class="container mx-10 p-2 rounded">
    <!-- opening form tag for php-->
    <form name="filters" method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" target="_self">
      <p class="h1">Είσοδος Χρήστη</p>

      <!-- fields start-->
      <div class="container m-5 p-2 col-7">
        <!-- name start -->
        <div class="row">
          <label for="name" class="col">Όνομα Χρήστη:</label>
          <input name="name" id="name" type="text" class="col" required />
        </div>
        <!-- name end -->

        <!-- password start -->
        <div class="row">
          <label for="password" class="col">Κωδικός:</label>
          <input name="password" id="password" type="password" class="col" required />
        </div>
        <!-- password end -->
      </div>
      <!-- fields end-->

      <!-- submit button start -->
      <div class="text-center">
        <br />
        <?php
        if ($_SESSION["viewAs"] == "Guest") : ?>
          <input type="submit" class="btn btn-secondary rounded-pill" value="Είσοδος" name="loginbutton" />
        <?php else : ?>
          <input type="submit" class="btn btn-secondary rounded-pill" value="Αποσύνδεση" name="logoutbutton" />
        <?php endif ?>
        <br />

        <?php
        if ($_SESSION["viewAs"] != "Guest") :
          echo "Έχετε συνδεθεί σαν ".$_SESSION["userName"]."<br>";
          echo "Για να κάνετε εγγραφή νέας επιχείρησης πρέπει πρώτα να αποσυνδεθείτε";
        else :
        ?>
          <a href="register.php" target="_self">Εγγραφή νέας Επιχείρησης</a>
        <?php endif; ?>

      </div>
      <!-- submit button end -->

      <!-- closing form tag for php-->
    </form>
  </div>

  <?php
  // assigns functions to login-logout buttons
  if (array_key_exists('loginbutton', $_POST)) {
    loginfunction();
  } else if (array_key_exists('logoutbutton', $_POST)) {
    logoutfunction();
  }

  // logs in and assigns roles
  function loginfunction()
  {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
      // get input text
      $inputName = $_POST['name'];
      $inputPass = $_POST['password'];

      // search the database for the username-password combination
      $conn = OpenCon();
      $sql = "select * from users where username = '$inputName' and user_password = '$inputPass';";
      $result = mysqli_query($conn, $sql);
      

      // no combination found
      if (mysqli_num_rows($result) == 0) {
        echo "Λάθος όνομα χρήστη ή password.";
      } else {

        // refreshes the page
        echo "<meta http-equiv='refresh' content='0'>";

        // checks the role of the user
        $row = mysqli_fetch_row($result);

        // assign role and current username
        if ($row[3] == 0) {
          $_SESSION["viewAs"] = "Admin";
          $_SESSION["userName"] = "ADMINISTRATOR";
          //   echo "Συνδεθήκατε ως Admin";
        } else {
          $_SESSION["viewAs"] = "business";
          $_SESSION["userName"] = $inputName;
          //   echo "Συνδεθήκατε ως business με username: " . $inputName;
          $_SESSION["userID"] = $row[0];
          $_SESSION["bizName"] = $row[4];
          $_SESSION["bizAfm"] = $row[5];
          $_SESSION["bizAddress"] = $row[6];

          // gets the name of the municipality
          $municipalId = $row[8];
          mysqli_free_result($result);
          $sql = "SELECT * from municipality where municipal_id = '$municipalId';";
          $result = mysqli_query($conn, $sql);
          $row = mysqli_fetch_row($result);
          $_SESSION["bizMunicipalID"] =$municipalId;
          $_SESSION["bizMunicipal"] =$row[1];

          // gets the name of the county
          $bizCountyId=$row[2];
          mysqli_free_result($result);
          $sql = "SELECT * from county where county_id = '$bizCountyId';";
          $result = mysqli_query($conn, $sql);
          $row = mysqli_fetch_row($result);
          $_SESSION["bizCountyID"] =$bizCountyId;
          $_SESSION["bizCounty"] =$row[1];
        }
      }
      // empties post fields
      $_POST = array();
      CloseCon($conn);
      // Free result set 
      mysqli_free_result($result);
    }
  }

  // logs out and assigns the role of guest
  function logoutfunction()
  {
        // refreshes the page
        echo "<meta http-equiv='refresh' content='0'>";

    $_SESSION["viewAs"] = "Guest";
    $_SESSION["userName"] = "000000000";
    $_SESSION["userID"] = null;
    $_SESSION["bizName"] = null;
    $_SESSION["bizAfm"] = null;
    $_SESSION["bizAddress"] = null;
    $_SESSION["bizMunicipalID"] =null;
    $_SESSION["bizMunicipal"] =null;
    $_SESSION["bizCountyID"] =null;
    $_SESSION["bizCounty"] =null;

  }
  ?>

</body>
<!--footer start-->
<?php include "./includes/footer.inc.php"; ?>
<!--footer end-->

</html>