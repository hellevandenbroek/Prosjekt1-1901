<!-- For brukerhistorie 5, Manager skal legge til behov-->

<?php
session_start();

//Checking if user is logged in
if(!(isset($_SESSION['u_id']))){
    header("Location: index.html");
    exit();
}
else{
    if(!($_SESSION['u_role'] == "manager")){
        header("Location: " . $_SESSION['u_role'] . ".php");
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Band Demands</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body style="background-color: #3C6E71">
<div class="flexBody">
    <a href="<?php
    if(isset($_SESSION['u_id'])){
        echo $_SESSION['u_role'] . ".php";
    }
    else{
        echo "index.html";
    }
    ?>">Hjem</a>

    <div class="indexBody">
        <div class="indexWrapper">
            <form action="includes/insert-demands.inc.php" method="POST">
                <p class="indexHeader">Add demands for band/artist</p>
                <label>Name of band/artist: </label>
                <input type = text name="BandName">
                <label>Demands: </label><br>
                <textarea name="BandDemands" rows="10" cols="80"></textarea>
                <input type="submit" name = 'submit' value="Add demands"/>
            </form>
        </div>
    </div>

</div>

</body>
</html>