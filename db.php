<?php
function connect() {
    
    $absolutehost = "http://" . $_SERVER['HTTP_HOST'] ;
   
    if ($absolutehost == "http://localhost:86") {
        $host = "localhost";
        $user = "root";
        $pw = "root";
        $db = "webdev";
    }
    if ($absolutehost == "http://bhall06.webhosting6.eeecs.qub.ac.uk") {
        $host = "bhall06.webhosting6.eeecs.qub.ac.uk";
        $user = "bhall06";
        $pw = "jC1LW2qNQFJnT9Ld";
        $db = "bhall06";
    }
    $mysqli = new mysqli($host, $user, $pw, $db);
    $mysqli->set_charset('utf8mb4');
    
    echo ($mysqli->error) ? $mysqli->error : "";
    return ($mysqli->error) ? "no connection" : $mysqli;
}