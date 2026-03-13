<!DOCTYPE html>
<html lang="gr">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <!-- custom title-->
  <title>Καταχώρηση</title>
  <!-- include Bootstrap css-->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous" />
  <!-- include custom css-->
  <link rel="stylesheet" href="style.css" />
</head>

<!-- header start -->
<?php
$selected = "record";
include "./includes/header.inc.php";
include 'dbh.php';
// TODO: GE4
// include "export_xml.php";
?>
<!-- header end -->

<?php // checks if the user is logged in
session_start();
?>

<body>

  <?php // if user is not logged in, then he is prompted to do so
  if ($_SESSION["viewAs"] == "Guest") : ?>

    <div style=" height:75vh; display: flex; align-items:center; justify-content: center;">
      <div>
        Για να κάνετε καταχώρηση προσφοράς πρέπει πρώτα να συνδεθείτε.
      </div>
    </div>

  <?php
  // if user is logged in, display this page
  else : ?>

    <?php
    // TODO: GE4 export button start
    if ($_SESSION["viewAs"] == "business") :
    ?>
      <form action="export_xml.php" method="post" target="_blank">
        <div class="text-left">
          <input name="exportButton" type="submit" class="btn btn-secondary rounded-pill" value="Εξαγωγή στοιχείων" ; />
        </div>
      </form>
    <?php
    // TODO: GE4 export button end
    endif;
    ?>

    <!--main container-->
    <div class="container mx-10 p-2 rounded">
      <!-- opening form tag for php-->
      <form name="offers" method="post" target="_self" action="" onsubmit="return validateOffer()">
        <p class="h1">Καταχώρηση Προσφοράς</p>

        <!--fields start-->
        <div class="col-9">
          <!-- name start -->
          <div class="row">
            <label for="bname" class="col">Επωνυμία Επιχείρησης:</label>
            <input class="col" type="text" id="bname" name="bname" placeholder="
            <?php if (isset($_SESSION["bizName"])) {
              echo $_SESSION["bizName"];
            } else {
              echo "Επωνυμία Επιχείρησης";
            }
            ?>" readonly required />
          </div>
          <!-- name end -->

          <!-- afm start -->
          <div class="row">
            <!-- type= tel wste se mobile na emfanizetai numpad-->
            <!-- to afm de mporei na 3ekinaei me 0-->
            <label for="afm" class="col">ΑΦΜ:</label>
            <input class="col" id="afm" name="afm" placeholder="
            <?php if (isset($_SESSION["bizAfm"])) {
              echo $_SESSION["bizAfm"];
            } else {
              echo "123456789";
            }
            ?>" required type="tel" pattern="[1-9][0-9]{8}" readonly required />
          </div>
          <!-- afm end -->

          <!-- address start -->
          <div class="row">
            <label for="address" class="col">Διεύθυνση:</label>
            <input class="col" type="text" id="address" name="address" placeholder="
            <?php if (isset($_SESSION["bizAddress"])) {
              echo $_SESSION["bizAddress"];
            } else {
              echo "Διεύθυνση";
            }
            ?>" readonly required />
          </div>
          <!-- address end -->

          <!-- county start -->
          <div class="row">
            <label for="nomos" class="col">Νομός:</label>
            <input class="col" type="text" id="nomos" name="nomos" placeholder="
            <?php if (isset($_SESSION["bizCounty"])) {
              echo $_SESSION["bizCounty"];
            } else {
              echo "Νομός";
            }
            ?>" readonly required />
          </div>
          <!-- county end -->

          <!-- municipality start -->
          <div class="row">
            <label for="dimos" class="col">Δήμος:</label>
            <input class="col" type="text" id="dimos" name="dimos" placeholder="
            <?php if (isset($_SESSION["bizMunicipal"])) {
              echo $_SESSION["bizMunicipal"];
            } else {
              echo "Δήμος";
            }
            ?>" readonly required />
          </div>
          <!-- municipality end -->

          <!-- fuel start -->
          <div class="row">
            <label for="fuelType" class="col">Είδος Καυσίμου:</label>
            <select id="fuelType" name="fuelType" class="col" required>
              <option disabled selected value>-- Επιλέξτε Καύσιμο --</option>
              <?php

              // list of fuel types from database
              $conn = OpenCon();
              $sql = "SELECT fuel_name FROM fuel;";
              $result = mysqli_query($conn, $sql);
              CloseCon($conn);
              while ($row = mysqli_fetch_array($result)) :
                $fuelname = $row[0];
              ?>
                <option value="<?php echo $fuelname; ?>"><?php echo $fuelname; ?></option>
              <?php
              endwhile;
              // Free result set
              mysqli_free_result($result);
              ?>
            </select>
          </div>
          <!-- fuel end -->

          <!-- price start -->
          <div class="row">
            <!-- 2 pshfia prin thn ypodiastolh kai 2 meta-->
            <label for="price" class="col">Τιμή:</label>
            <input class="col" id="price" name="price" placeholder="τιμή με 2 δεκαδικά ψηφία πχ 1.40" required type="number" step=".01" />
          </div>
          <!-- price end -->

          <!-- date select start -->
          <div class="row">
            <label for="endDate" class="col">Ημ/νια λήξης Προσφοράς:</label>
            <input class="col" type="date" id="endDate" name="endDate" placeholder="dd-mm-yyyy" min="<?php echo date("Y-m-d"); ?>" required />
          </div>
          <!-- date select end -->
        </div>
        <!-- fields end-->

        <!-- submit button start -->
        <div class="text-center">
          <br />
          <input name="offerbutton" type="submit" class="btn btn-secondary rounded-pill" value="Καταχώρηση" <?php echo ($_SESSION["viewAs"] == "Admin") ? "disabled" : ""; ?> />
        </div>
        <?php echo ($_SESSION["viewAs"] == "Admin") ? "<p> Μόνο οι επιχειρήσεις μπορούν να καταχωρούν προσφορές <p>" : ""; ?>
        <!-- submit button end -->

        <!-- closing form tag for php-->
      </form>

    </div>
  <?php endif; ?>

  <?php
  if (isset($_POST['offerbutton'])) {

    $fueltype = $_POST['fuelType'];
    $price = $_POST['price'];
    $expdate = $_POST['endDate'];
    $userid = $_SESSION["userID"];

    $conn = OpenCon();

    // gets the fuelid
    $sql = "SELECT fuel_id FROM fuel WHERE fuel_name='$fueltype';";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_row($result);
    $fuelid = $row[0];
    mysqli_free_result($result);
//TODO: GE4 -- added 'AND exp_date>=NOW(), 
    $sql = "SELECT * FROM offers WHERE offered_by='$userid' AND offered_fuel= '$fuelid'
    AND exp_date>=NOW();";
    $result = mysqli_query($conn, $sql);

    // if offer is not in the database, create it
    if (mysqli_num_rows($result) <= 0) {
      $sql = "INSERT INTO offers (offered_by,offered_fuel,exp_date,price) 
  VALUES ('$userid','$fuelid','$expdate','$price');";
      mysqli_query($conn, $sql);
      echo "Η προσφορά καταχωρήθηκε στη βάση δεδομένων";
    } else {
      // if offer for this fuel type exists, update it
      $sql = "UPDATE offers SET exp_date= '$expdate', price='$price'
  WHERE offered_by ='$userid' AND offered_fuel= '$fuelid';";
      mysqli_query($conn, $sql);
      echo "Η προσφορά επικαιροποιήθηκε επιτυχώς";
    }
    mysqli_free_result($result);
    CloseCon($conn);
  }
  ?>
  <!-- empties window history cache -->
  <script>
    if (window.history.replaceState) {
      window.history.replaceState(null, null, window.location.href);
    }
  </script>
  <script src="./js/validate_offer.js"></script>
</body>
<!--footer start-->
<?php include "./includes/footer.inc.php"; ?>
<!--footer end-->

</html>