<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />

  <!-- custom title-->
  <title>Ανακοινώσεις</title>

  <!-- include Bootstrap css-->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous" />

  <!-- include custom css-->
  <link rel="stylesheet" href="style.css" />
</head>

<!-- header start -->
<?php
$selected = "announcements";
include "./includes/header.inc.php";
include 'dbh.php';
?>
<!-- header end -->

<?php
session_start();
?>

<body>
  <?php
  // if user is admin, then add/delete buttons are shown
  if ($_SESSION["viewAs"] == "Admin") : ?>

    <!--admin buttons start-->
    <div>
      <button onclick="showModalAdd()"> Νέα ανακοίνωση</button>

      <script>
        function showModalAdd() {
          modalAdd.style.display = "block";
        }
      </script>
    </div>
    <!--admin buttons end-->

    <!--modal add start-->
    <div>
      <!--force reload css-->
      <link rel="stylesheet" href="style.css?v=<?php echo time(); ?>">
      <div id="modalAdd" class="modal themodal">
        <div class="modal-content themodal-content container">
          <form name="newAnnounce" method="post" action="" target="_self">
            <!--add title-->
            <div class="container">
              <label for="announceTitle" class="col-2">Τίτλος:</label>
              <input class="col-8" type="text" id="announceTitle" name="announceTitle" placeholder="Τίτλος Ανακοίνωσης" required />
            </div>
            <!--add text-->
            <div class="container">
              <label for="announceText" class="col-2">Κείμενο:</label>
              <textarea name="announceText" class="col-8" id="announceText" placeholder="Κείμενο Ανακοίνωσης" rows="10" required></textarea>
            </div>
            <!--buttons-->
            <div>
              <input type="submit" name="addbutton" id="addbutton" value="Ανάρτηση Ανακοίνωσης" />
              <button id="cancelButton1">Ακύρωση</button>
            </div>
          </form>
        </div>
        <!-- clicking on the cancel button closes the modal -->
        <script>
          const modalAdd = document.getElementById("modalAdd");
          const cancelbutton = document.getElementById("cancelButton1");
          cancelbutton.onclick = function() {
            modalAdd.style.display = "none";
          }
        </script>
      </div>
    </div>
    <!--modal add end-->
  <?php endif; ?>

  <?php
  // assigns functions to input buttons
  if (array_key_exists('addbutton', $_POST)) {
    addAnnouncement();
  } else if (array_key_exists('removebutton', $_POST)) {
    deleteAnnouncement();
  }

  // adds announcement to db
  function addAnnouncement()
  {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
      if (isset($_POST['addbutton']) && ($_POST["announceTitle"] != null) && ($_POST["announceText"] != null)) {
        // get the post values
        $title = $_POST["announceTitle"];
        $textbody = $_POST["announceText"];
        $conn = OpenCon();

        // inserts values in the database
        $sql = "insert into announce (announce_title,announce_body,announce_date) values ('$title', '$textbody', NOW());";
        mysqli_query($conn, $sql);
        // empties fields
        $_POST["announceTitle"] = null;
        $_POST["announceText"] = null;

        //closes connection
        CloseCon($conn);
        //refreshes page
        echo "<meta http-equiv='refresh' content='0'>";
      }
    }
  }

  // deletes selected announcements from db
  function deleteAnnouncement()
  {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
      if (isset($_POST['removebutton'])) {
        if (!empty($_POST["selection"])) {
          foreach ($_POST["selection"] as $sel) {
            $conn = OpenCon();
            $sql = "delete FROM announce where announce_id='$sel'";
            $result = mysqli_query($conn, $sql);
            // Free result set
            mysqli_free_result($result);
            CloseCon($conn);
          }
          echo "Οι επιλεγμένες ανακοινώσεις διαγράφηκαν";
        } else {
          echo "Δεν έχουν επιλεγεί εγγραφές προς διαγραφή";
        }
      }
      // refreshes the page so that the announcements can get the correct ids
      echo "<meta http-equiv='refresh' content='0'>";
    }
  }
  ?>

  <div class="container m-2 p-2">
    <p class="h1">Ανακοινώσεις</p>

    <!-- announcements start-->
    <div class="container container-s rounded border border-5" style="overflow-y: scroll; height: 70vh">

      <!-- Creates a div for every announcement in the db-->
      <?php
      $conn = OpenCon();
      $sql = "SELECT *  FROM announce order by announce_date desc;";
      $result = mysqli_query($conn, $sql);
      CloseCon($conn);

      // index number for giving element unique ids
      $i = 0;

      // admins can see delete button
      if ($_SESSION["viewAs"] == "Admin") : ?>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" target="_self">
          <input type="submit" name="removebutton" id="removebutton" value="Διαγραφή ανακοίνωσης" />
        <?php
      endif;

      // for every row returned by the query
      while ($row = mysqli_fetch_row($result)) :
        $announce_element_id = "announce_id_" . $i;
        $announce_db_id = $row[0];
        // displays the date in normal format: l is weekday, H is hour in 24h format
        $announce_date = date("l d/m/y, H:i:s", strtotime($row[1]));
        $announce_title = $row[2];
        $announce_text = $row[3];
        ?>

          <!-- creates a div and assigns the column data of that row-->
          <div class="card-body container mb-4" id="<?php echo $announce_element_id; ?>">

            <!-- admin-only checkbox start -->
            <?php
            if ($_SESSION["viewAs"] == "Admin") :
            ?>
              <input type="checkbox" name="selection[]" value="<?php echo $announce_db_id ?>" /> Επιλογή
            <?php
            endif;
            ?>
            <!-- admin-only checkbox end -->

            <h3 class="card-title"><?php echo $announce_title; ?></h3>
            <p class="h2 card-subtitle"><?php echo $announce_date; ?></p>
            <p class="card-text border border-5"> <?php echo $announce_text; ?> </p>
          </div>

        <?php
        // increases index number
        $i++;
      endwhile;
      // clears result set
      mysqli_free_result($result);

      // closing tag for the form
      if ($_SESSION["viewAs"] == "Admin") : ?>
        </form>
      <?php endif; ?>
    </div>
    <!-- announcements end-->
  </div>

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