<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <!-- custom title-->
  <title>Αναζήτηση</title>

  <!-- include Bootstrap css-->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous" />

  <!-- include custom css-->
  <link rel="stylesheet" href="style.css" />
</head>

<!-- header start -->
<?php
$selected = "search";
include "./includes/header.inc.php";
include 'dbh.php';
?>
<!-- header end -->

<body>
  <!--main container-->
  <div class="container mx-10 p-2">
    <!-- opening form tag for php-->
    <form name="filters" method="post" target="_self" action="">
      <!--filters start-->
      <div class="container">
        <p class="h1">Φίλτρα</p>
        <div class="row" style="overflow-x: hidden">
          <!-- municipality start-->
          <div class="col-5" style="display: inline-flex">
            <label for="nomos">Νομός:</label>
            <select class="form-select form-select-sm" name="nomos" id="nomos" required>
              <option disabled selected value>-- Επιλέξτε Νομό --</option>
              <?php
              // list of fuel types from database
              $conn = OpenCon();
              $sql = "SELECT county_name FROM county;";
              $result = mysqli_query($conn, $sql);
              CloseCon($conn);
              while ($row = mysqli_fetch_array($result)) :
                $countyname = $row[0];
              ?>
                <option value="<?php echo $countyname; ?>"><?php echo $countyname; ?></option>
              <?php
              endwhile;
              // Free result set
              mysqli_free_result($result);
              ?>
            </select>
          </div>
          <!-- municipality end-->

          <!-- fuel start-->
          <div class="col-5" style="display: inline-flex">
            <label for="kaysimo">Είδος Καυσίμου:</label>
            <select class="form-select w-50 h-auto" name="kaysimo" id="kaysimo" required>
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
          <!-- fuel end-->

          <!-- submit button-->
          <div class="col-2">
            <input type="submit" name="submitbutton" class="btn btn-secondary rounded-pill" value="Αναζήτηση" />
          </div>
        </div>
      </div>
      <!--filters end-->
      <!--closing form tag for php-->
    </form>
    <!-- results table start -->
    <div class="container ">
      <p class="h1">Αποτελέσματα
        <?php
        // on clicking search, display what kind of fuel is searched for, and where
        if (isset($_POST['submitbutton'])) {
          $countyname = $_POST['nomos'];
          $fuelname = $_POST['kaysimo'];
          echo "για " . $fuelname . " στο νομό " . $countyname;
        }
        ?>
      </p>
      <!-- table goes within scrollable div in case many elements are added-->
      <div class="table-responsive" style="max-height:55vh;">
        <table class="table table-striped table-bordered ">
          <thead>
            <tr>
              <th scope="col">α/α</th>
              <th scope="col">Επωνυμία</th>
              <th scope="col">Διεύθυνση</th>
              <th scope="col">Δήμος</th>
              <th scope="col">Τιμή</th>
            </tr>
          </thead>
          <tbody>
            <?php
            // on clicking the search button
            if (isset($_POST['submitbutton'])) :

              // run specific query for offers that have not yet expired
              $conn = OpenCon();
              $sql = "SELECT business_name,user_address,municipal_name,price FROM fuel as f
          INNER JOIN offers as o
          ON f.fuel_id = o.offered_fuel
          INNER JOIN users as u
          ON o.offered_by = u.user_id
          INNER JOIN municipality as m
          ON u.lives_in = m.municipal_id
          INNER JOIN county as c
          ON m.belongs_to = c.county_id
          WHERE fuel_name='$fuelname'
          AND  county_name='$countyname'
          AND exp_date >= NOW()
          ORDER BY price ASC
          ;";
              $result = mysqli_query($conn, $sql);

              // get the average
              $sql = "SELECT AVG(price) FROM offers AS o
              INNER JOIN fuel AS f
              ON o.offered_fuel=f.fuel_id
              WHERE f.fuel_name='$fuelname'
              AND o.exp_date >= NOW();
              ";
              $avgresult = mysqli_query($conn, $sql);
              $row = mysqli_fetch_row($avgresult);
              $selectedaverage = $row[0];
              mysqli_free_result($avgresult);

              // closes off the db
              CloseCon($conn);

              // we find the index of the row that must be highlighted
              $indexWithMinDifference = getMinDifference($result, $selectedaverage);

              // result index number
              $index = 1;
              // for every result row, fill-up a line
              while ($row = mysqli_fetch_row($result)) :
                $bizname = $row[0];
                $bizaddress = $row[1];
                $bizmunicipal = $row[2];
                $offeredprice = $row[3];
            ?>
                <tr style="<?php 
                // highlight the line with the minimum difference from the average price
                if ($index==$indexWithMinDifference){
                  echo "background-color:green";
                }
                ?>">
                  <th scope="row"><?php echo $index; ?></th>
                  <td height="35"><?php echo $bizname; ?></td>
                  <td>
                    <a href="
                      <?php echo "https://www.google.com/maps/search/?api=1&query=" .
                        $bizaddress . "," . $bizmunicipal; ?>" target="_blank">
                      <p><?php echo $bizaddress; ?></p>
                    </a>
                  </td>
                  <td>
                    <p><?php echo $bizmunicipal; ?></p>
                  </td>
                  <td>
                    <p><?php echo $offeredprice; ?></p>
                  </td>
                </tr>
              <?php
                $index++;
              endwhile;
              // empties the result set
              mysqli_free_result($result);
              // if less than 7 records, show extra empty lines
              while ($index < 8) : ?>
                <tr>
                  <th scope="row"></th>
                  <td height="40"></td>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>
              <?php
                $index++;
              endwhile;
            // if search button is not pushed, just show an empty table
            else :
              for ($i = 0; $i < 7; $i++) : ?>
                <tr>
                  <th scope="row"></th>
                  <td height="40"></td>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>
            <?php
              endfor;
            endif;
            // reset selection variables
            $countyname = null;
            $fuelname = null;
            ?>
          </tbody>
        </table>
      </div>
    </div>
    <!-- results table end-->
  </div>

  <?php
  function getMinDifference($resultarray, $avgfuelprice)
  {
    // current index iterates through the result set
    $currentindex = 1;
    // init chosen index with smallest difference with the average
    $ind = 0;
    // we begin with max difference
    $difference = PHP_INT_MAX; 
    while ($row = mysqli_fetch_row($resultarray)) {
      // we keep the index of the smallest price difference with the average
     if (abs($row[3] - $avgfuelprice) < $difference) {
        $difference = abs($row[3] - $avgfuelprice);
        $ind = $currentindex;
      }
      $currentindex++;
    }
    //back to the start of the result set
    mysqli_data_seek($resultarray,0);
    // returns index number
    return $ind;
  }
  ?>

  <!-- empties window history cache so as to avoid duplicates-->
  <script>
    if (window.history.replaceState) {
      window.history.replaceState(null, null, window.location.href);
    }
  </script>
</body>
<!--footer start-->
<?php include "./includes/footer.inc.php"; ?>
<!--footer end-->

</html>