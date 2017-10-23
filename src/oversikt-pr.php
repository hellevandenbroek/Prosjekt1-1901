<?php
session_start();
include_once 'includes\dbh.inc.php';

//Checking if user is logged in. If not sending back to proper site
if(!(isset($_SESSION['u_id']))){
    header("Location: index.php");
}
else{
    if(!($_SESSION['u_role'] == "pr")){
        header("Location: " . $_SESSION['u_role'] . ".php");
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Band Booket</title>
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
<div style="margin:0;height:100%;" class="flexBody">
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
        <div style="width:auto;height:70vh;" class="flexWrapper">
            <p class="insideMenuHeader" style="font-size: 20px; margin-bottom: 0">Du er logget inn som
                <?php
                $userLoggedIn = $_SESSION["u_username"];
                $sqlUsersTop = "SELECT * FROM Users WHERE UserUsername = '$userLoggedIn'";
                $resultUsersTop = mysqli_query($conn, $sqlUsersTop);
                $usersArrayTop = mysqli_fetch_assoc($resultUsersTop);
                $firstName = $usersArrayTop["UserFirstname"];

                echo $firstName;
                ?></p>

            <p class="insideMenuHeader">PR//Bandoversikt</p>
            <div class="flexWrapperInside">
                <table>
                    <tr>
                        <th>Band</th>
                        <th>Kontaktinformasjon</th>
                        <th>Salgstall</th>
                        <th>Link til presseomtale</th>
                    </tr>
                    <?php

                    if (mysqli_num_rows($resultConcert)){
                        while($row = mysqli_fetch_assoc($resultConcert)) {


                            $ConcertID = $row['ConcertID'];
                            $sqlConTech = "SELECT UserID FROM Concerts_UserTechnicians WHERE ConcertID = '$ConcertID'";
                            $resultConTech = mysqli_query($conn, $sqlConTech);
                            $conTechArray = mysqli_fetch_assoc($resultConTech);

                            //finner brukernavn slik at vi kan sjekke om den brukeren er innlogget og linja markeres grønt
                            $userIDConTech = $conTechArray['UserID'];
                            $sqlUsers = "SELECT * FROM Users WHERE UserID = '$userIDConTech'";
                            $resultUsers = mysqli_query($conn, $sqlUsers);
                            $usersArray= mysqli_fetch_assoc($resultUsers);
                            $userName = $usersArray['UserFirstname'];

                            //endrer bakgrunnsfarge dersom gjeldende bruker er pålogget
                            if($_SESSION["u_username"] == $usersArray['UserUsername']){
                                $style = 'background-color: #88cc88; border-radius:5px;';
                            }
                            else{
                                $style = 'background-color:#b2c2bf; border-radius:5px;';
                            }

                            //Finner demand
                            $sqlDemand = "SELECT * FROM Concert_Demands WHERE ConcertID = '$ConcertID'";
                            $resultDemand = mysqli_query($conn, $sqlDemand);

                            $outDemand = "";
                            while($rowDemand = mysqli_fetch_assoc($resultDemand)){
                                $outDemand = $outDemand. $rowDemand['Demand'] . ", ";
                            }

                            $outDemand = substr($outDemand, 0,-2);

                            echo "<tr> <td style='$style;'>" . date('d.M.Y H:s', strtotime($row['ConcertTimeStart'])) . " | " . "Scene " . $row['SceneID']  . "</td> <td style='$style'>" . $outDemand  . "</td>" . "</tr>";
                        }

                    }


                    ?>
                </table>
            </div>
        <a class="helleButton" style='$style;'href='rigge-oversikt.php'>Tilbake</a>

        </div>
    </div>
</body>
</html>