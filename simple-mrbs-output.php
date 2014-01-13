<?php
/**
\file: simple-mrbs-output.php
\author: repat<repat@repat.de>
\date: September 2013
\brief: Connects to MRBS database and SELECTs name, description, start-time and end-time
of all booked rooms on current date, then prints an h1-caption and all the SELECTed data.
*/

function printRoom($roomNr, $qry) {

	echo "<h2>Room: X.". $roomNr . "</h2>\n";
	if (mysql_num_rows($qry) == 0 ) {
		echo "<div class=\"bigfont\"> No reservations in Room X". $roomNr. ".</div>";
	} else {
		// Starting table
		echo "<table border=1>\n";
		echo "<colgroup>\n";
		echo "<col class=\"first\">\n";
		echo "<col class=\"second\">\n";
		echo "<col class=\"third\">\n";
		echo "</colgroup>\n";
		echo "<tr>\n";
		echo "<th>Time</th>\n";
		echo "<th>Name</th>\n";
		echo "<th>Desc</th>\n";
		echo "</tr>\n";

		// Actual data
		// Cut off the date with substr() because it's always 10 characters in front of time->(YYYY-MM-DD)
		while($row=mysql_fetch_array($qry)) {
			echo "<tr>";
			echo "<td> " . substr($row[2],11,5) . " - " . substr($row[3],11,5) . "</td>";
			echo "<td> " . $row['name'] . "</td>";
			echo "<td>" . $row['description'] . "</td>";
			echo "</tr>";
		}

		// Ending table
		echo "\n</table>";
	}
	return 0;
}

// --- MySQL Part

// MySQL configuration
$mysqlhost="localhost";
$mysqluser="user";
$mysqlpwd="password";
$mysqldb="database";

// Set date to today
$date = date("Y-m-d");

// Connect to MySQL-Server
$connection=mysql_connect($mysqlhost, $mysqluser, $mysqlpwd) or die ("Could not connect to database");

// Select database
mysql_select_db($mysqldb, $connection) or die("Could not select database.");

// This could be more elegant
// Build SQL-queries with name, description and start/end-time, room_id doesn't match actual room number in the building(!)
$sql01 = "SELECT name,description,from_unixtime(start_time),from_unixtime(end_time) FROM mrbs_entry WHERE from_unixtime(start_time) LIKE '%" . $date . "%' AND room_id = 1;" ;
$sql02 = "SELECT name,description,from_unixtime(start_time),from_unixtime(end_time) FROM mrbs_entry WHERE from_unixtime(start_time) LIKE '%" . $date . "%' AND room_id = 2;" ;
$sql03 = "SELECT name,description,from_unixtime(start_time),from_unixtime(end_time) FROM mrbs_entry WHERE from_unixtime(start_time) LIKE '%" . $date . "%' AND room_id = 3;" ;

// Query the database
$qry01 = mysql_query($sql01) or die("Query01 not successfull");
$qry02 = mysql_query($sql02) or die("Query02 not successfull");
$qry03 = mysql_query($sql03) or die("Query03 not successfull");

// --- HTML Output

// HTML stuff
echo "<!DOCTYPE html>\n";
echo "<html>\n";
echo "<head>\n";
echo "<meta charset=\"UTF-8\">\n";
echo "<meta http-equiv=\"refresh\" content=\"60\" >\n";
echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"style.css\" />\n";
echo "<meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">\n";
echo "<title>MRBS Room Booking</title>\n";
echo "</head>\n";
echo "<body>\n";

// Caption
echo "<h1>MRBS Room Booking</h1>\n";

printRoom(1,$qry01);
printRoom(2,$qry02);
printRoom(3,$qry03);

echo "\n<p><div class=\"smallfont\">--last update: " . date("d. M Y") . " um " . date("G:i") . "</div></p>\n";

echo "</body>\n";
echo "</html>";
?>

