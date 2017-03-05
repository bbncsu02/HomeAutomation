<!DOCTYPE html>
<html>
<head>
	<title>Temperature Table</title>
</head>
<body>
<h1>Temperature display table</h1>
<?php

$d = strtotime("-3 days");
$filterdatetime = date("Y-m-d H:i:s", $d);

echo "<p>Log entries received since: " .$filterdatetime."</p>";

$servername = "localhost";
$username = "haWeb";
$password = "haWebPassword123";
$dbname = "HomeAutomation";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);
// Check connection
if (!$conn) {
    die("<p>Connection failed: " . mysqli_connect_error() ."</p>");
}

// Insert the new record
$sql = "SELECT id, uuid, logDateTime, temperature, humidity FROM TemperatureLog"
   . " WHERE logDateTime > '" .$filterdatetime. "'";

$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    echo "<table><tr><th>ID</th><th>UUID</th><th>Date Time</th>"
    ."<th>Temp &deg;F</th><th>Humidity</th></tr>";
    // output data of each row
    while($row = mysqli_fetch_assoc($result)) {
        echo "<tr><td>" .$row["id"]."</td><td>". $row["uuid"]. "</td><td>"
	. $row["logDateTime"]."</td><td>"
	. $row["temperature"] ."</td><td>". $row["humidity"]. "</td></tr>";
    }
    echo "</table>";
} else {
    echo "<p>0 results</p>";
}

mysqli_close($conn);
?>
</body>
</html>
