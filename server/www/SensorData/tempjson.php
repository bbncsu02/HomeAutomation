<?php

$d = strtotime("-3 days");
$filterdatetime = date("Y-m-d H:i:s", $d);

$servername = "localhost";
$username = "haWeb";
$password = "haWebPassword123";
$dbname = "HomeAutomation";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);
// Check connection
if (!$conn) {
    die("var tempdata={error:'Connection failed: " 
	. mysqli_connect_error() ."'};");
}

// Insert the new record
$sql = "SELECT id, uuid, logDateTime, temperature, humidity FROM TemperatureLog"
   . " WHERE logDateTime > '" .$filterdatetime. "'";

$result = mysqli_query($conn, $sql);

echo "var tempdata = {";
if (mysqli_num_rows($result) > 0) {
    // output data of each row
    while($row = mysqli_fetch_assoc($result)) {
        echo "{ id:" .$row["id"]."; uuid:'". $row["uuid"]. "';"
	. " dateTime:'" . $row["logDateTime"]."'; temperature:"
	. $row["temperature"] ."; humidity:". $row["humidity"]
	. ";},";
    }
}
echo "};";

mysqli_close($conn);
?>
