<!-- For brukerhistorie 12, Bookingsjef skal få oversikt over bookingtilbud som er sendt-->

<?php
session_start();

include 'includes/dbh.inc.php';

//Checking if user is logged in
if(!(isset($_SESSION['u_id']))){
    header("Location: index.php");
    exit();
}
else{
    if(!($_SESSION['u_role'] == "bookingsjef")){
        header("Location: " . $_SESSION['u_role'] . ".php");
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Bookingtilbud - oversikt</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body style="background-color: #3C6E71">
<div class="flexTop">
        <a class="hjemButton" href="<?php
                    if(isset($_SESSION['u_id'])){
                        echo $_SESSION['u_role'] . ".php";
                    }
                    else{
                        echo "index.php";
                    }
                    ?>">Hjem</a>
        <p class="superHeader">Festiv4len</p>
        <form action="includes\logout.inc.php" method="post">
            <button type="submit" name="submit">Logg ut</button>
        </form> 
    </div>
    <div style="margin: 0;height: 100%" class="flexBody">
        <div style="width:99%; height: 80vh;" class="flexWrapper">
        <p class="insideMenuHeader">Bookingtilbud - oversikt</p>
        <div class="flexWrapperInside">
            <table style="font-size: 15px;">
                <tr>
                    <th>BookingOfferID</th>
                    <th>BandName</th>
                    <th>Genre</th>
                    <th>SceneID</th>
                    <th>ConcertTime Start</th>
                    <th>ConcertTime End</th>
                    <th>Price</th>
                    <th>Contact Email</th>
                    <th>Accepted</th>
                    <th>Send Offer</th>
                </tr>

                <?php
                $sql = "SELECT * FROM Booking_Offers";
                $result = mysqli_query($conn, $sql);
                while($row = mysqli_fetch_assoc($result)){

                    if($row['Accepted'] == 1){
                        $accepted = "True";
                    }
                    else{
                        $accepted = "False";
                    }

                    echo "<tr><td>" . $row['BookingOfferID'] . "</td> 
                    <td>" . $row['BandName'] . "</td>
                    <td>" . $row['Genre'] . "</td>
                    <td>" . $row['SceneID'] . "</td>
                    <td>" . date('d.M.Y H:s', strtotime($row['ConcertTimeStart'])) . "</td>
                    <td>" . date('d.M.Y H:s', strtotime($row['ConcertTimeEnd'])) . "</td> 
                    <td>" . $row['Price'] . "</td> 
                    <td>" . $row['ContactEmail'] . "</td><td>" . $accepted  . "</td>";
                    if ($row['Sent'] == 1) {
                        echo '<td> Sent </td>';
                    }
                    else {
                        echo "<td style='background-color: green'> <a class='btn btn-primary btn-lg' onclick='return confirm(\"Are you sure you want to send the offer?\");' href='send.php?band=" . $row['BandName'] . "&val=" .   $row['Validation'] . "'>Send</a></td> </td>";
                    }
                }

                if (isset($_SESSION['sent']) && $_SESSION['sent']) {
                    $_SESSION['sent'] = False;
                    $_SESSION['failed'] = False;
                    $band = $_SESSION['band'];
                    $mail = $_SESSION['mail'];
                    $popupMessage = $_SESSION['message'];

                    echo "<script type='text/javascript'> window.alert('$popupMessage')</script>";
                    exit();
                }

                ?>


            </table>
        </div>
    </div>
</div>
</body>
</html>