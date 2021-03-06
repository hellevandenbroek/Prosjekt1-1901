<?php
session_start();
//Checking if user is logged in
if(!(isset($_SESSION['u_id']))){
    header("Location: index.php");
}
include_once 'includes/dbh.inc.php';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Billettsalg</title>
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
        <div style="width:90%; height: 80vh;" class="flexWrapper">
        <p class="insideMenuHeader">Billettsalg</p>
        <div class="flexWrapperInside">
            <table>
                <tr>
                    <th colspan="3">Konsert-info</th>
                    <th colspan="2">Billettsalg</th>
                    <th>Tekniske oppgaver</th>
                </tr>
                <tr>
                    <th>Dato</th>
                    <th>Band / Artist</th>
                    <th>Scene</th>
                    <th>Billettsalg</th>
                    <th>Billettinntekter</th>
                    <th>Antall fullført</th>
                </tr>

                <?php

                //Looping through concerts
                $sql = "SELECT * FROM Concert";
                $result = mysqli_query($conn, $sql);
                while($row = mysqli_fetch_assoc($result)){
                    $concertTime = date('d-m-y h:m', strtotime($row['ConcertTimeStart']));
                    $sceneID = $row['SceneID'];
                    $bandID = $row['BandID'];
                    $ticketsSold = $row['TicketsSold'];
                    $ticketPrice = $row['TicketPrice'];
                    $ticketIncome = $ticketsSold*$ticketPrice;

                    //Formating numbers to pretty
                    $ticketsSold = number_format($ticketsSold, 0, ',', ' ');
                    $ticketPrice = number_format($ticketPrice, 0, ',', ' ');
                    $ticketIncome = number_format($ticketIncome, 0, ',', ' ');

                    //Getting band name
                    $sqlBand = "SELECT * FROM Band WHERE BandID = '$bandID'";
                    $resultBand = mysqli_query($conn, $sqlBand);
                    $rowBand = mysqli_fetch_assoc($resultBand);
                    $bandName = $rowBand['BandName'];

                    //Getting scene name
                    $sqlScene = "SELECT * FROM Scene WHERE SceneID = '$sceneID'";
                    $resultScene = mysqli_query($conn, $sqlScene);
                    $rowScene = mysqli_fetch_assoc($resultScene);
                    $sceneName = $rowScene['SceneName'];
                    $sceneCapacity = $rowScene['Capacity'];

                    //Gettings number of tasks
                    $taskNum = $row['TechTasks'];
                    $taskComp = $row['TasksCompleted'];

                    echo "<tr><td>" . $concertTime ."</td><td>" . $bandName . "</td><td>" . $sceneName . "</td>
                              <td>" . $ticketsSold ." / ". $sceneCapacity ."</td><td>" . $ticketIncome . ",-</td>
                              <td>". $taskComp ." / ".  $taskNum. "</td></tr>";
                }
                ?>
            </table>

        </div>

    </div>

</div>
</div>
</body>
</html>