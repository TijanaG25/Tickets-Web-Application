<?php
session_start();
require_once "konekcija.php";
if($_SERVER["REQUEST_METHOD"] === "POST"){
    $ime = trim($_POST['ime']);
    $prezime = trim($_POST['prezime']);
    $email = trim($_POST['email']);
    $upit = mysqli_prepare($dbc,"UPDATE osobe 
                                SET uloga='administrator' 
                                WHERE ime=? AND prezime=? AND email=?");
    mysqli_stmt_bind_param($upit, "sss", $ime, $prezime, $email);
    mysqli_stmt_execute($upit);
    if(mysqli_stmt_affected_rows($upit) > 0){
        header("Location: admin.php");
        exit();
    } 
    else {
        $_SESSION['error'] ="<p class='text-danger mt-2'>Greška prilikom postavljanja admina!</p>";
        header("Location: admin.php");
        exit();
    }
    mysqli_stmt_close($upit);
}
?>