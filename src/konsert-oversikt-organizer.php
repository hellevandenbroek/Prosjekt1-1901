

<!DOCTYPE html>
<html>
    <head>
        <title></title>
        <link rel="stylesheet" type="text/css" href="style.css">
    </head>
    <body style="background-color: #3C6E71">





        <div class="indexBody">
            <div class="indexWrapper">


<?php

// For brukerhistorie 2, Arrangør skal få oversikt over alle konserter på alle scener

// Takes a constant query and returns 2d array as result
function sql_query_array_result($mysqli, $query, $associative=TRUE){
    $mysqli_result = $mysqli->query($query);
    
    if (!$mysqli_result) {
        echo "<p>Error in query " . $mysqli->error . "</p>";
    }
    
    if ($associative) {
        $mysqli_array_result = $mysqli_result->fetch_all(MYSQLI_ASSOC);
    } else {
        $mysqli_array_result = $mysqli_result->fetch_all();
    }
    
    return $mysqli_array_result;
}

function sql_prepared_query_1param_array_result($mysqli, $query, $param, $types, $associative=TRUE){
    $prepared_statement = $mysqli->prepare($query);
    $prepared_statement->bind_param($types, $param);
    $prepared_statement->execute();
    $scene_name_result = $prepared_statement->get_result();
    
    if ($associative) {
        $scene_name_array_result = $scene_name_result->fetch_all(MYSQLI_ASSOC);
    } else {
        $scene_name_array_result = $scene_name_result->fetch_all();
    }
    
    return $scene_name_array_result;
}


date_default_timezone_set("Europe/Oslo");

if ($_SESSION["u_role"] == "organizer") { // TODO: remove || TRUE
    
    $mysqli = mysqli_connect("mysql.stud.ntnu.no", "kimera_gruppe4", "festiv4l", "kimera_gruppe4");
    
    if($mysqli->connect_error){
        echo("failure to connect to database " . $conn->connect_error);
    }
    
    $festival_array_result = sql_query_array_result($mysqli, "SELECT * FROM Festival;");
    
    // Go through each festival
    foreach ($festival_array_result as $festival_row){
        
        echo "<p>" . $festival_row["FestivalName"] . "</p>";
        
        $festival_id = $festival_row["FestivalID"];
        
        $concert_stmt = $mysqli->prepare("SELECT * FROM Concert WHERE FestivalID=? ORDER BY ConcertTimeStart;");
        $concert_stmt->bind_param("i", $festival_id);
        $concert_stmt->execute();
        $concert_result = $concert_stmt->get_result();
        $array_concert_result = $concert_result->fetch_all(MYSQLI_ASSOC);
        
        echo "<table>\r\n"
                . "<tr>\r\n"
                . "<th>Band</th>"
                . "<th>Scene</th>"
                . "<th>Concert start</th>"
                . "<th>Concert end</th>\r\n"
                . "</tr>\r\n";
        
        
        // Go through each concert of the festival
        foreach ($array_concert_result as $row){
            
            $query_scene_name = "SELECT SceneName FROM Scene WHERE SceneID=? LIMIT 1;";
            $scene_id = $row["SceneID"];
            $scene_name_array_result 
                    = sql_prepared_query_1param_array_result($mysqli, $query_scene_name, $scene_id, "i", FALSE);
            $scene_name = $scene_name_array_result[0][0];
            
            $query_band_name = "SELECT BandName FROM Band WHERE BandID=? LIMIT 1;";
            $band_id = $row["BandID"];
            $band_name_array_result 
                    = sql_prepared_query_1param_array_result($mysqli, $query_band_name, $scene_id, "i", FALSE);
            $band_name = $band_name_array_result[0][0];
            
            $concert_time_start = $row["ConcertTimeStart"];
            $concert_time_end = $row["ConcertTimeEnd"];
            
            $unix_concert_time_start = strtotime($concert_time_start);
            $unix_concert_time_end = strtotime($concert_time_end);
            $unix_time_now = strtotime("now");
            $concert_has_started = FALSE;
            $concert_has_ended = FALSE;
            $concert_row_class = "notStarted";
            
            if ($unix_time_now > $unix_concert_time_start) {
                $concert_has_started = TRUE;
                $concert_row_class = "started";
                if ($unix_time_now > $unix_concert_time_end) {
                    $concert_has_ended = TRUE;
                    $concert_row_class = "finished";
                }
            }
            
            
            echo "<tr class='$concert_row_class'>\r\n"
                    . "<td>$band_name</td>"
                    . "<td>$scene_name</td>"
                    . "<td>$concert_time_start</td>"
                    . "<td>$concert_time_end</td>"
                    . "</tr>\r\n";
        }

        echo "</table>\r\n";

    }
    
}
else {
    echo "<p>Du har ikke lov til å se denne siden</p>";
}

?>

            </div>
        </div>
    </body>
</html>