<html>

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Document</title>
  <!--opens all links in new windows-->
  <base target="_blank" />

  <!-- include Bootstrap css-->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous" />

  <!-- include bootstrap icon-->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" />

  <!-- include custom css-->
  <link rel="stylesheet" href="style.css" />
</head>

<?php
session_start();
?>

<body>
  <div class="row align-items-center justify-content-center rounded border border-5 position-relative top-0" style="background-color: darkgrey; height: 15vh; max-height: 12vh; overflow:hidden;">
    <!-- one empty column is added for extra padding-->
    <div class="col"></div>

    <!-- link to index page -->
    <!-- with target="_top" the link is loaded in the parent window-->
    <div class="col">
      <a href="index.php" target="_top" id="logoLink"><i class="bi bi-fuel-pump-fill h1 headerFooterHlink"></i></a>
    </div>

    <!-- link to index page -->
    <div class="col">
      <a href="index.php" target="_top" id="indexPageLink" class="headerFooterHlink" style="
      <?php // link is bold and black if chosen page is index
      if ($selected == "index") {
        echo "font-weight: bold; color:black;";
      } ?>">Αρχική</a>
    </div>

    <!-- link to search page -->
    <div class="col">
      <a href="search.php" target="_top" id="searchPageLink" class="headerFooterHlink" style="
      <?php // link is bold and black if chosen page is search
      if ($selected == "search") {
        echo "font-weight: bold; color:black;";
      } ?>
          ">Αναζήτηση</a>
    </div>

    <!-- link to record page -->
    <div class="col">
      <a href="record.php" target="_top" id="recordPageLink" class="headerFooterHlink" style="
      <?php // link is bold and black if chosen page is record
      if ($selected == "record") {
        echo "font-weight: bold; color:black;";
      } 
      ?>
          ">Καταχώρηση</a>
    </div>

    <!-- link to announcements page -->
    <div class="col">
      <a href="announcements.php" target="_top" id="announcePageLink" class="headerFooterHlink" style="
      <?php // link is bold and black if chosen page is announcements
      if ($selected == "announcements") {
        echo "font-weight: bold; color:black;";
      } ?>
          ">Ανακοινώσεις</a>
    </div>

    <!-- link to login page -->
    <div class="col">
      <a href="login.php" target="_top" id="loginPageLink" class="headerFooterHlink">
        <button class="btn btn-secondary rounded-pill">Login</button></a>
    </div>

    <div class="col">
      <div style="font-size:x-small">Συνδεθήκατε ως:</div>
      <div style="font-size:small">
        <?php
        
        if ($_SESSION["viewAs"] == "business") : echo $_SESSION["userName"];
        else : echo $_SESSION["viewAs"];
        endif;
        ?>

      </div>
    </div>
  </div>
</body>
</html>