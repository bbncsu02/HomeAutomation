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
    die("error:'Connection failed: "
	. mysqli_connect_error());
}

//echo "DateTime,Temp 1 (F),Humidity 1 %,Temp 2 (F),Humidity 2 %\n"; 
/*
$sql = "SELECT 'DateTime' as logDateTime, "
	."MAX(CASE WHEN sensorID = 1 THEN name ELSE NULL END) AS s1t, "
	."MAX(CASE WHEN sensorID = 1 THEN name ELSE NULL END) AS s1h, "
        ."MAX(CASE WHEN sensorID = 2 THEN name ELSE NULL END) AS s2t, "
	."MAX(CASE WHEN sensorID = 2 THEN name ELSE NULL END) AS s2h "
	."FROM Sensors  GROUP BY logDateTime ORDER BY sensorID;";
*/

$sql = "CALL custom_GetTempLogColumnHeaders();";

$result = mysqli_query($conn, $sql) or die("Query 1 failed: " . mysqli_error());

if (mysqli_num_rows($result) > 0) {
    // output data of each row
    while($row = mysqli_fetch_assoc($result)) {
	echo $row["logDateTime"] . ","
	. $row["s1t"] . " T (F)," . $row["s1h"] . " % H,"
	. $row["s2t"] . " T (F)," . $row["s2h"] . " % H\n";
    }
}
mysqli_close($conn);

/*
$sql = "SELECT "
	."myDateTime, "
	."MAX(CASE WHEN sensorID = 1 THEN temperature END) AS temp1, "
	."MAX(case when sensorID = 1 then humidity END) as humidity1, "
	."MAX(case when sensorID = 2 then temperature end) as temp2, "
	."MAX(case when sensorID = 2 then humidity end) as humidity2 "
	."FROM (SELECT DATE_SUB(logDateTime, INTERVAL EXTRACT(SECOND FROM "
	."logDateTime) SECOND)"
	."AS myDateTime, sensorID, temperature, humidity, NaN from TemperatureLog) as A "
	."WHERE myDateTime >= '" .$filterdatetime. "' "
	."GROUP BY myDateTime ORDER BY myDateTime";
*/

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);
// Check connection
if (!$conn) {
    die("error:'Connection failed: "
        . mysqli_connect_error());
}

$sql = "CALL custom_GetTempLog('" . $filterdatetime . "', NULL);";

$result2 = mysqli_query($conn, $sql) or die("Query 2 failed: " . mysqli_error());

if (mysqli_num_rows($result2) > 0) {
    // output data of each row
    while($row = mysqli_fetch_assoc($result2)) {
	echo $row["logDateTime"]. ","
	. $row["temp1"] .",". $row["humidity1"] . ","	
	. $row["temp2"] .",". $row["humidity2"] . "\n";
    }
}
mysqli_close($conn);
?>
