<?php
$dbc=@mysqli_connect("localhost","root","","primetickets");
if(mysqli_connect_errno()){
    die("Greška prilikom konekcije sa bazom.");
}
?>