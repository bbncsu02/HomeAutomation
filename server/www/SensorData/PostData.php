<?php
// $_POST
$delta=0;
$minutes = date("i");
$seconds = date("s");
$now = strtotime("now");
$then = strtotime("+" . (5 - ($minutes % 5)) . " minutes");
$delta = $then - $now - $seconds;
// write the time to next execution to the first line
echo "0|" . ($delta + 3) * 1000;

$uuid = $_GET['uuid'];
$datetime = date("Y-m-d H:i:s");
$temp = $_GET['temp'];
$humidity = $_GET['humidity'];

echo "<br/><br/>\n\nDateTime:\t" , $datetime;
echo "<br/>\nUUID:\t\t", $uuid;
echo "<br/>\nTemp:\t\t" , $temp;
echo "ºF<br/>\nHumidity:\t" , $humidity;
echo "%\n<br/>\n<br/>";

$servername = "localhost";
$username = "haWeb";
$password = "haWebPassword123";
$dbname = "HomeAutomation";

// Create connection
echo "\n<br/>Create SQL connection";
$conn = mysqli_connect($servername, $username, $password, $dbname);
// Check connection
if (!$conn) {
    die("\n<br/>Connection failed: " . mysqli_connect_error());
}

// Insert the new record
//$sql = "INSERT INTO TemperatureLog (uuid, logDateTime, temperature, humidity) VALUES ('" 
//	. $uuid ."', '". $datetime ."', ". $temp .", ". $humidity . ")";

$sql = "CALL custom_AddTempLog('" . $uuid ."', " . $temp . ", " . $humidity .");";

//echo "<br/>\n\nInsert the new record: <br/>\n\t" . $sql . "<br/>\n";

if (mysqli_query($conn, $sql)) {
    echo "\n<br/>New record created successfully";
} else {
    echo "\n<br/>Error: " . $sql . "<br/>" . mysqli_error($conn);
}

mysqli_close($conn);
?>
