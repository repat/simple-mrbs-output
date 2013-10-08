<?php
/**
\file: simple-mrbs-output.php
\author: repat<repat@repat.de>
\date: September 2013
\brief: Connects to MRBS database and SELECTs name, description, start-time and end-time
of all booked rooms on current date, then prints an h1-caption and all the SELECTed data.
*/

function printRoom($roomNr, $qry) {

	echo "<h2>Room: X.". $roomNr . "</h2>";
	if (mysql_num_rows($qry) == 0 ) {
		echo "<em><font size=\"+2\"> No reservations in Room X". $roomNr. ".</font></em>";
	} else {
		// Starting table
		echo "<table border=1>";
		echo "<colgroup>";
		echo "<col width=\"200\">";
		echo "<col width=\"300\">";
		echo "<col width=\"500\">";
		echo "</colgroup>";
		echo "<tr>";
		echo "<th align=\"center\" valign=\"middle\"><font size=\"+2\"> Time </font></th>";
		echo "<th align=\"center\" valign=\"middle\"><font size=\"+2\"> Name </font></th>";
		echo "<th align=\"center\" valign=\"middle\"><font size=\"+2\"> Desc </font></th>";
		echo "</tr>";

		// Actual data
		// Cut off the date with substr() because it's always 10 characters in front of time->(YYYY-MM-DD)
		while($row=mysql_fetch_array($qry)) {
			echo "<tr>";
			echo "<td align=\"center\" valign=\"middle\"><font size=\"+2\"> " . substr($row[2],11,5) . " - " . substr($row[3],11,5) . " </font></td>";
			echo "<td align=\"center\" valign=\"middle\"><font size=\"+2\"> " . $row['name'] . " </font></td>";
			echo "<td align=\"center\" valign=\"middle\"><font size=\"+2\"> " . $row['description'] . " </font></td>";
			echo "</tr>";
		}

		// Ending table
		echo "</table>";
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
echo "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\" \n \"http://www.w3.org/TR/html4/loose.dtd\">\n";
echo "<html>\n";
echo "<head>\n";
echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\">\n";
echo "<title>MRBS Room Booking</title>\n";
echo "</head>\n";
echo "<body style=\"text-align:center;\">\n";

// Caption
echo "<h1>MRBS Room Booking</h1>\n";

printRoom(1,$qry01);
printRoom(2,$qry02);
printRoom(3,$qry03);

echo "\n<p>--<em>last update: </em>" . date("d. M Y") . " at " . date("G:i") . "</p>";

echo "</body>\n";
echo "</html>";
?>

