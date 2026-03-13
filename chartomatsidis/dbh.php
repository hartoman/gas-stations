<?php

// opens connection to database
function OpenCon()
 {
 $dbhost = "localhost";
 $dbuser = "admin";
 $dbpass = "password";
 $db = "chartomatsidis";
 //echo "Connected Successfully"."<br>";

 $conn = new mysqli($dbhost, $dbuser, $dbpass,$db) or die("Connect failed: %s\n". $conn -> error);
 return $conn;
 }
 
// closes connection to database
function CloseCon($conn)
 {
 $conn -> close();
 }
   
// function to test if results were found
function printQuery($result){

    if (mysqli_num_rows($result) > 0) {

        echo "Returned rows are: " . $result -> num_rows."<br>";

        while($row = mysqli_fetch_assoc($result)) {
            foreach($row as $r){
                echo $r." ";
            }
            echo "<br>";
        }
        // Free result set
        mysqli_free_result($result);
     }
     else {
        echo "0 results";
     }
}
?>