<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <!-- custom title-->
  <title>Εγγραφή</title>
  <!-- include Bootstrap css-->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous" />
  <!-- include custom css-->
  <link rel="stylesheet" href="style.css" />
</head>

<!-- header start -->
<?php include './includes/header.inc.php';
include 'dbh.php'; ?>
<!-- header end -->

<body>

  <!--main container-->
  <div class="container m-10 p-2">

    <!-- opening form tag for php-->
    <form name="businessData" method="post" onsubmit="return validateRegister()" action="" target="_self">
      <p class="h1">Εγγραφή Επιχείρησης</p>

      <!--fields start-->
      <div class="col-9">
        <!-- name start -->
        <div class="row">
          <label for="bname" class="col">Επωνυμία Επιχείρησης:</label><input class="col" type="text" id="bname" name="bname" placeholder="Επωνυμία Επιχείρησης" maxlength="120" required />
        </div>
        <!-- name end -->

        <!-- afm start -->
        <div class="row">
          <!-- type= tel wste se mobile na emfanizetai numpad-->
          <!-- to afm de mporei na 3ekinaei me 0-->
          <label for="afm" class="col">ΑΦΜ:</label><input class="col" id="afm" name="afm" placeholder="123456789" required type="number" pattern="[1-9][0-9]{8}" minlength="9" maxlength="9" />
        </div>
        <!-- afm end -->

        <!-- address start -->
        <div class="row">
          <label for="address" class="col">Διεύθυνση:</label>
          <input class="col" type="text" id="address" name="address" placeholder="Διεύθυνση" maxlength="120" required />
        </div>
        <!-- address end -->

        <!-- county start -->
        <div class="row">
          <label for="nomos" class="col">Νομός:</label>
          <!-- every time we select a different county, the page is reloaded and 
          the js variable 'selectedjs' is sent back to itself -->
          <select id="nomos" name="nomos" class="col" required onchange="countyChange(this.value)">
            <option disabled selected value>-- Επιλέξτε Νομό --</option>
            <?php
            $conn = OpenCon();
            $sql = "SELECT * FROM county";
            $result = mysqli_query($conn, $sql);
            CloseCon($conn);

            // initializes 'selectedcounty' as php variable from the 'selectedjs' that was sent back
            $selectedcounty = $_GET["selectedjs"];

            while ($row = mysqli_fetch_array($result)) :
              $countyname = $row[1];
            ?>
              <!-- if the selectedcounty variable equals the county name, then it stays selected on the
              refreshed page -->
              <option value="<?php echo $countyname ?>" <?php if ($selectedcounty == $countyname) {
                                                          echo 'selected';
                                                        } ?>>
                <?php echo $countyname ?></option>
            <?php
            endwhile;
            // Free result set
            mysqli_free_result($result);
            ?>
          </select>
        </div>
        <!-- county end -->

        <!-- municipality start -->
        <div class="row">
          <label for="dimos" class="col">Δήμος:</label>
          <select id="dimos" name="dimos" class="col" required>
            <option disabled selected value>-- Επιλέξτε Δήμο --</option>
            <?php

            // the list of municipalities is based on the selected county
            $conn = OpenCon();
            $sql = "SELECT municipal_name, county_name 
            from municipality inner join county
            on municipality.belongs_to=county.county_id
            where county.county_name='$selectedcounty'
            ";
            $result = mysqli_query($conn, $sql);
            CloseCon($conn);
            while ($row = mysqli_fetch_array($result)) :
              $municipalname = $row[0];
            ?>
              <option value="<?php echo $municipalname; ?>"><?php echo $municipalname; ?></option>
            <?php
            endwhile;
            // Free result set
            mysqli_free_result($result);
            ?>
          </select>

        </div>
        <!-- municipality end -->

        <!-- e-mail start -->
        <div class="row">
          <label for="email" class="col">e-mail:</label><input class="col" id="email" name="email" placeholder="venzinadiko@gmail.com" required type="email" />
        </div>
        <!-- e-mail end -->

        <!-- username start -->
        <div class="row">
          <label for="username" class="col">Username:</label><input class="col" type="text" id="username" name="username" placeholder="username" minlength="6" required />
        </div>
        <!-- username end -->

        <!-- password start -->
        <div class="row">
          <label for="password" class="col">Password:</label>
          <input name="password" id="password" type="password" class="col" required />
        </div>
        <!-- password end -->

        <!-- confirm password start -->
        <div class="row">
          <label for="confirm" class="col">Confirm Password:</label>
          <input name="confirm" id="confirm" type="password" class="col" required />
        </div>
        <!-- confirm password end -->
      </div>
      <!-- fields end-->

      <!-- submit button start -->
      <div class="text-center">
        <br />
        <input type="submit" name="submit" class="btn btn-secondary rounded-pill" value="Εγγραφή" />
      </div>
      <!-- submit button end -->

      <!-- closing form tag for php-->
    </form>

    <?php
    if (isset($_POST['submit'])) {
      // form input values
      $businessname_p = $_POST['bname'];
      $afm_p = $_POST['afm'];
      $adress_p = $_POST['address'];
      $county_p = $_POST['nomos'];
      $municipality_p = $_POST['dimos'];
      $email_p = $_POST['email'];
      $username_p = $_POST['username'];
      $pass_p = $_POST['password'];

      // flags to check duplicate entries
      $afmExists = false;
      $usernameExists = false;

      // search database for duplicates
      $conn = OpenCon();

      // check for duplicate afm
      $sql = "SELECT * FROM users where AFM='$afm_p'";
      $result = mysqli_query($conn, $sql);
      if (!(mysqli_num_rows($result) <= 0)) {
        $afmExists = true;
        mysqli_free_result($result);
        $sql = "SELECT user_email FROM users where user_role='0'";
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_row($result);
        echo " Το ΑΦΜ ήδη υπάρχει. Παρακαλούμε επικοινωνήστε με το διαχειριστή στη διεύθυνση: " . $row[0] . "<br>";
      }
      mysqli_free_result($result);

      // check for duplicate username
      $sql = "SELECT * FROM users where username='$username_p'";
      $result = mysqli_query($conn, $sql);
      if (!(mysqli_num_rows($result) <= 0)) {
        $usernameExists = true;
        echo " Το Username που επιλέξατε ήδη υπάρχει. Δοκιμάστε κάτι άλλο.";
      }
      mysqli_free_result($result);

      // if no duplicates are found
      if ((!$afmExists) && (!$usernameExists)) {

        // derived value for municipality id
        $sql = "SELECT municipal_id FROM municipality WHERE municipal_name='$municipality_p';";
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_row($result);
        $livesIn = $row[0];

        $sql = "INSERT INTO users (username, user_password, user_role, business_name, AFM, 
          user_address, user_email, lives_in) 
          VALUES ('$username_p','$pass_p',1,'$businessname_p','$afm_p','$adress_p',
          '$email_p','$livesIn');";
        mysqli_query($conn, $sql);
        CloseCon($conn);

        // refreshes the page
        //  echo "<meta http-equiv='refresh' content='0'>";

    ?>
        <div>
          Έχετε εγγραφεί επιτυχώς. Κάντε Login για να συνδεθείτε.
        </div>
    <?php
      }
      CloseCon($conn);
    }
    ?>

  </div>


  <script src="./js/validate_register.js"></script>
</body>
<!--footer start-->
<?php include './includes/footer.inc.php'; ?>
<!--footer end-->


</html>