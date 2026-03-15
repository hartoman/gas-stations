<?php
include 'dbh.php';
session_start();

if (!isset($_SESSION["viewAs"])) {
  $_SESSION["viewAs"] = "Guest";
}
if (!isset($_SESSION["userName"])) {
  $_SESSION["userName"] = "000000000";
}
?>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <!-- custom title-->
  <title>Τα Καλύτερα Καύσιμα</title>
  <!-- include Bootstrap css-->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous" />
  <!-- include custom css-->
  <link rel="stylesheet" href="style.css" />
</head>

<?php 
// default value to display
$defaultvalue="XX";

$conn = OpenCon();

// benzine max
$sql = "SELECT MAX(price) FROM offers 
WHERE offered_fuel='1'
AND exp_date >= NOW();";
$result = mysqli_query($conn, $sql);
$row= mysqli_fetch_row($result);
mysqli_free_result($result);
$benzMax=$row[0];

// benzine min
$sql = "SELECT MIN(price) FROM offers 
WHERE offered_fuel='1'
AND exp_date >= NOW();";
$result = mysqli_query($conn, $sql);
$row= mysqli_fetch_row($result);
mysqli_free_result($result);
$benzMin=$row[0];

// benzine avg
$sql = "SELECT AVG(price) FROM offers 
WHERE offered_fuel='1'
AND exp_date >= NOW();";
$result = mysqli_query($conn, $sql);
$row= mysqli_fetch_row($result);
mysqli_free_result($result);
$benzAvg=number_format((float)$row[0],2,'.','');

// diesel car max
$sql = "SELECT MAX(price) FROM offers 
WHERE offered_fuel='2'
AND exp_date >= NOW();";
$result = mysqli_query($conn, $sql);
$row= mysqli_fetch_row($result);
mysqli_free_result($result);
$diesCarMax=$row[0];

// diesel car min
$sql = "SELECT MIN(price) FROM offers 
WHERE offered_fuel='2'
AND exp_date >= NOW();";
$result = mysqli_query($conn, $sql);
$row= mysqli_fetch_row($result);
mysqli_free_result($result);
$diesCarMin=$row[0];

// diesel car avg
$sql = "SELECT AVG(price) FROM offers 
WHERE offered_fuel='2'
AND exp_date >= NOW();";
$result = mysqli_query($conn, $sql);
$row= mysqli_fetch_row($result);
mysqli_free_result($result);
$diesCarAvg=number_format((float)$row[0],2,'.','');

// heating max
$sql = "SELECT MAX(price) FROM offers 
WHERE offered_fuel='3'
AND exp_date >= NOW();";
$result = mysqli_query($conn, $sql);
$row= mysqli_fetch_row($result);
mysqli_free_result($result);
$heatMax=$row[0];

// heating min
$sql = "SELECT MIN(price) FROM offers 
WHERE offered_fuel='3'
AND exp_date >= NOW();";
$result = mysqli_query($conn, $sql);
$row= mysqli_fetch_row($result);
mysqli_free_result($result);
$heatMin=$row[0];

// heating avg
$sql = "SELECT AVG(price) FROM offers 
WHERE offered_fuel='3'
AND exp_date >= NOW();";
$result = mysqli_query($conn, $sql);
$row= mysqli_fetch_row($result);
mysqli_free_result($result);
$heatAvg=number_format((float)$row[0],2,'.','');

// close db connection
CloseCon($conn);
?>

<!-- header start -->
<?php
$selected = "index";
include "./includes/header.inc.php";
?>
<!-- header end -->

<body>
  <div class="container mx-10 p-2 rounded">
    <!-- daily prices start-->
    <div class="container">
      <p class="h1">Ημερήσια Σύνοψη Τιμών</p>
      <p class="h3" id="currentDate"><?php echo date("l d-m-Y"); ?></p>
      <ul>
        <li>
          <p class="h2">Τιμή Αμόλυβδης Βενζίνης</p>

          <!-- benzine start-->
          <div style="display: inline-flex">
            <div style="display: inline-flex">
              Μέγιστη:
              <p id="maxPriceBenz"><?php echo $benzMax ?></p>
            </div>
            <div style="display: inline-flex">
              / Ελάχιστη:
              <p id="minPriceBenz"><?php echo $benzMin ?></p>
            </div>
            <div style="display: inline-flex">
              / Μέση:
              <p id="avgPriceBenz"><?php echo $benzAvg ?></p>
            </div>
          </div>
          <!-- benzine end-->
        </li>
        <li>
          <p class="h2">Τιμή Πετρελαίου Κίνησης</p>

          <!-- petrol car start-->
          <div style="display: inline-flex">
            <div style="display: inline-flex">
              Μέγιστη:
              <p id="maxPricePCar"><?php echo $diesCarMax ?></p>
            </div>
            <div style="display: inline-flex">
              / Ελάχιστη:
              <p id="minPricePCar"><?php echo $diesCarMin ?></p>
            </div>
            <div style="display: inline-flex">
              / Μέση:
              <p id="avgPricePCar"><?php echo $diesCarAvg ?></p>
            </div>
          </div>
          <!-- petrol car end-->
        </li>
        <li>
          <p class="h2">Τιμή Πετρελαίου Θέρμανσης</p>

          <!-- petrol heat start-->
          <div style="display: inline-flex">
            <div style="display: inline-flex">
              Μέγιστη:
              <p id="maxPricePHeat"><?php echo $heatMax ?></p>
            </div>
            <div style="display: inline-flex">
              / Ελάχιστη:
              <p id="minPricePHeat"><?php echo $heatMin ?></p>
            </div>
            <div style="display: inline-flex">
              / Μέση:
              <p id="avgPricePHeat"><?php echo $heatAvg ?></p>
            </div>
          </div>
          <!-- petrol heat end-->
        </li>
      </ul>
    </div>
    <!-- daily prices end-->

    <!-- latest announcements start-->
    <div class="container" id="latestAnnouncements">
      <p class="h1">Τελευταίες Ανακοινώσεις</p>
      <ul>
        <?php
        $conn = OpenCon();
        // we only need the last 3 results
        $sql = "SELECT * FROM announce ORDER BY announce_date DESC
        LIMIT 3;";
        $result = mysqli_query($conn, $sql);
        CloseCon($conn);

        // index number of element id, to link directly to relevant announcement
        $index = 0;

        // for every one of the results, we create a list item
        while ($row = mysqli_fetch_array($result)) :
        ?>
          <!--announcement start-->
          <li>
            <p class="h3"><?php echo $row[1]; ?></p>
            <a href="announcements.php#announce_id_<?php echo $index;
                                                    $index++; ?>">
              <p class="h2"><?php echo $row[2]; ?></p>
            </a>
          </li>
          <!--announcement end-->

        <?php
        endwhile;
        // Free result set
        mysqli_free_result($result);
        ?>
      </ul>
    </div>
    <!-- latest announcements end-->
  </div>
</body>

<!--footer start-->
<?php include "./includes/footer.inc.php"; ?>
<!--footer end-->

</html>