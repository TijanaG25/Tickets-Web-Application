<?php
session_start(); 
require_once "konekcija.php";
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $naziv = trim($_POST['naziv']);
    $organizator = trim($_POST['organizator']);
    $datum = $_POST['datum'];
    $vreme = $_POST['vreme'];
    $datum_vreme = $datum . ' ' . $vreme;
    $opis = trim($_POST['opis']);
    $cena = floatval($_POST['cena']);
    $idSale = intval($_POST['idSale']);
    $idVrste = intval($_POST['idVrste']);
    if(isset($_FILES['slika']) && $_FILES['slika']['error'] === 0){
        $targetDir = "images/";
        $fileName = basename($_FILES['slika']['name']);
        $targetFile = $targetDir . $fileName;
        if(!move_uploaded_file($_FILES['slika']['tmp_name'], $targetFile)){
            die("Greška prilikom čuvanja slike.");
        }
    } 
    else {
        $fileName = null;
    }
    $upit = mysqli_prepare($dbc, "INSERT INTO dogadjaji(naziv,organizator, datum_vreme, slika, opis, idSale, idVrste, cena) 
        VALUES (?,?, ?, ?, ?, ?, ?, ?)");
    mysqli_stmt_bind_param($upit, "sssssiid", $naziv,$organizator, $datum_vreme, $fileName, $opis, $idSale, $idVrste, $cena);
    if(mysqli_stmt_execute($upit)){
        header("Location: admin.php");
        exit();
    } else {
        $_SESSION['error'] ="<p class='text-danger mt-2'>Greška prilikom dodavanja događaja!</p>";
        header("Location: admin.php");
        exit();
    }
    mysqli_stmt_close($upit);
}
?>