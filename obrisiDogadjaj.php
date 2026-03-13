<?php
session_start();
require_once "konekcija.php";
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $naziv = trim($_POST['naziv']);
    $datum = $_POST['datum'];
    $vreme = $_POST['vreme'];
    $datum_vreme=$datum." ".$vreme;
    $upit = mysqli_prepare($dbc, "UPDATE dogadjaji 
                                    SET status = 'otkazan'
                                    WHERE naziv = ? 
                                    AND datum_vreme = ?");
    mysqli_stmt_bind_param($upit, "ss", $naziv, $datum_vreme);
    mysqli_stmt_execute($upit);
    if(mysqli_stmt_affected_rows($upit) > 0){
        header("Location:admin.php");
        exit();
    } else {
        $_SESSION['error'] ="<p class='text-danger mt-2'>Greška prilikom brisanja događaja!</p>";
        header("Location: admin.php");
        exit();
    }
    mysqli_stmt_close($upit);
}
?>