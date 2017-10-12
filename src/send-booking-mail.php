<?php
session_start();

include_once 'includes/dbh.inc.php';


$bandName = str_replace(' ', '_', $_POST['bandName']);
$date = $_POST['date'];
$time = $_POST['time'];
$length = $_POST['length'];
$scene = $_POST['scene'];
$price = $_POST['price'];
$email = $_POST['email'];
$val = rand();

$festival = $_POST['festival'];
$genre = $_POST['genre'];

$sql3 = "SELECT * FROM Scene WHERE SceneName = '$scene'";
$result = mysqli_query($conn, $sql3);
$scene_result = $result->fetch_all();

$scene = $scene_result[0][0];

$sql2 = "SELECT * FROM Festival WHERE FestivalName = '$festival'";
$result2 = mysqli_query($conn, $sql2);
$festival_result = $result2->fetch_all();

$festival = $festival_result[0][0];

$startTime = $date . ' ' . $time;

$endTimeTime = strtotime($startTime);
$endTime =  $endTimeTime+ $length*60;
$endTime = date('Y-m-d H:i:s', $endTime);

$sql = "INSERT INTO Booking_Offers (Validation, BandName, ConcertTimeStart, ConcertTimeEnd, SceneID, ContactEmail, Genre, Price, FestivalID, Sent, Accepted)

VALUES ($val, '$bandName','$startTime', '$endTime', $scene, '$email', '$genre', $price, 
	$festival, 0, 0)";

if ($conn->query($sql) === TRUE) {
    mail($email, utf8_decode("Booking offer for ") . utf8_decode($bandName),
	"Your band " . $bandName . " has received an offer to play at festiv4len on " . $date . ", " . $time . " on Stage " . $scene .
	". Your set will last " . $length . " minutes\n\n" .
	"For the concert, you will be paid " . $price . "kr \n\nClick the following link to review your offer. \n\n\n"
	. "http://org.ntnu.no/festiv4len/Prosjekt1-1901/src/booking-reply.php?band=" . $bandName . "&val=" . $val);

	$_SESSION['sent'] = true;
	$_SESSION['mail'] = $email;
	$_SESSION['band'] = $bandName;

  header("Location: booking-offer.php");

} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}



?>
