<?php
/**
\file: return-JSON-MRBS.php
\author: repat<repat@repat.de>
\date: October 2013
\version: 1.0
\brief: Connects to MRBS database and SELECTs name, description, start-time and end-time
of all lectures on current date from given room at GET-parameter, then returns it as JSON 
*/

// -- HTTP Part
// Parameter via GET
$room_id = $_GET["room"];

// --- MySQL Part
// MySQL configuration
$mysqlhost="localhost";
$mysqluser="user";
$mysqlpwd="password";
$mysqldb="database";

// Set date to today
$date = date("Y-m-d");

// Connect to MySQL-server
$connection=mysql_connect($mysqlhost, $mysqluser, $mysqlpwd) or die ("Could not connect to database");

// Select database
mysql_select_db($mysqldb, $connection) or die("Could not select database.");

// Build SQL-queries with name, description, start/end-time and room_id(from GET, doesn't have to match the actual room number in the building!)
$sql = "SELECT name,description,from_unixtime(start_time),from_unixtime(end_time) FROM mrbs_entry WHERE from_unixtime(start_time) LIKE '%" . $date . "%' AND room_id = " . $room_id;

// Query the database
$qry = mysql_query($sql) or die("Query not successfull");
// identifier
$i = 0;

while($row=mysql_fetch_array($qry)) {
	// array has a identifier($i) because it's easier to parse on the other side
	$jsonarray = array('begin' . $i => substr($row[2],11, 5), 'end' . $i => substr($row[3],11,5), 'name' . $i => $row['name'], 'description' . $i =>  $row['description']);
	// the comma could eventually end up in a parsing error on the other side
	echo json_encode($jsonarray), ",";
	$i++;
}
?>

