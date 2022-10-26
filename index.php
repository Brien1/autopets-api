<?php
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header('Access-Control-Allow-Methods: GET, POST');

header("Access-Control-Allow-Headers:  Content-Type");

// header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT");
// header("Access-Control-Allow-Headers: X-Token");
include("db.php");
$mysqli = connect();





if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $request_body = file_get_contents('php://input');
    $data = json_decode($request_body);
    $js_data = array();
    function remapJSobjectToPhpAssoc($data,$js_data) {
        foreach ($data as $k => $d) {
            $js_data[$k]=$d;
        }
        return $js_data;
    }
    $js_data = @remapJSobjectToPhpAssoc($data,$js_data);
    if (!empty($js_data)) {
        $_POST = $js_data;
    }

    if (isset($_POST["shoplevel"])) {
        $shoplevel = $_POST["shoplevel"];
    }
    if (isset($_POST["team"])) {
        $team  = $_POST["team"] ;
    }
    if (isset($_POST['id'])) {
        $team_id = $_POST['id'];
    }
    if (!isset($team_id)) {

        $all = "SELECT * FROM autopets$shoplevel LIMIT 1";
        $all_res = $mysqli->query($all);
        $does_it_exist = $mysqli->error;
        if ($does_it_exist) {
            // table doesn't exist, create then add user in
            $create_table = "CREATE TABLE autopets$shoplevel (teamid int NOT NULL AUTO_INCREMENT, team varchar(255), paired varchar(255), PRIMARY KEY (teamid));";
            $create_table_res = $mysqli->query($create_table);
            echo ($mysqli->error) ? $mysqli->error . $create_table : "";
            //add to table
            
        }

        $all = "SELECT * FROM autopets$shoplevel LIMIT 1";
        $all_res = $mysqli->query($all);

        if ($all_res->num_rows>0) {
            // table does exist
            
            $opp = $all_res->fetch_assoc();
            $opp_team = $opp['team'];
            $opp_id = $opp['teamid'];
            $add = "UPDATE autopets$shoplevel SET paired='$team' WHERE teamid = $opp_id";
            $add_res = $mysqli->query($add);
            echo ($mysqli->error) ? $mysqli->error . $add_res : "";
            
            echo json_encode(["opponent"=> $opp_team, "result"=> "paired"]);
            
        }
        if ($all_res->num_rows ==0) {

            $add = "INSERT INTO autopets$shoplevel (`team`, `paired`) VALUES ('$team',0)";
            $add_res = $mysqli->query($add);
            echo ($mysqli->error) ? $mysqli->error . $add : "";
            $id = $mysqli->insert_id;
            
            echo json_encode(["insert_id"=> $id, "result"=> "no pairing"]);
            


        }
    }
    if (isset($team_id)) {
        // check if you have had pairing
        $all = "SELECT * FROM autopets$shoplevel WHERE teamid =$team_id";
        $all_res = $mysqli->query($all);
        if ($all_res->num_rows!=0) {
            $data = $all_res->fetch_assoc();
            $opp_team = $data['paired'];
            if ($opp_team !== NULL) {

                $del = "DELETE FROM autopets$shoplevel  WHERE teamid = $team_id";
                $del_res = $mysqli->query($del);
                $data["message"] = "entry deleted";
            }
        
        }
        if ($all_res->num_rows==0) {
            $data = ["message"=>"team doesn't exist"];
        }
        echo json_encode($data);
    }


    

    
      

}


