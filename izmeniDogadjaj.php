<?php
session_start();
require_once "konekcija.php";
if(isset($_POST['sacuvaj'])){
    $id = $_POST['id'];
    $naziv = $_POST['naziv'];
    $datum_vreme = $_POST['datum_vreme'];
    $cena = $_POST['cena'];
    $opis = $_POST['opis'];
    $upit = mysqli_prepare($dbc,"UPDATE dogadjaji 
                                SET naziv=?, datum_vreme=?, cena=?, opis=?
                                WHERE id=?");
    mysqli_stmt_bind_param($upit, "ssdsi",$naziv, $datum_vreme, $cena, $opis, $id);
    mysqli_stmt_execute($upit);
    if(mysqli_stmt_affected_rows($upit) > 0){
        mysqli_stmt_close($upit);
        header("Location:admin.php");
        exit();
    } else {
        mysqli_stmt_close($upit);
        $_SESSION['error'] ="<p class='text-danger mt-2'>Greška prilikom izmene događaja!</p>";
        header("Location: admin.php");
        exit();
    }
}
?>