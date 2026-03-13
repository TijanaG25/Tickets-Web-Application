<?php
session_start();
require_once('konekcija.php');
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['signin'])) {
    $ime = trim($_POST['ime']);
    $prezime = trim($_POST['prezime']);
    $email = trim($_POST['email']);
    $telefon = trim($_POST['telefon']);
    $lozinka1 = trim($_POST['lozinka1']);
    $lozinka2 = trim($_POST['lozinka2']);
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = "<p class='text-danger mt-2'>Neispravan format emaila!</p>";
        header("Location: registracija.php");
        exit();
    }
    if ($lozinka1 !== $lozinka2) {
        $_SESSION['error'] = "<p class='text-danger mt-2'>Unete lozinke nisu iste!</p>";
        header("Location: registracija.php");
        exit();
    }
    $provera = mysqli_prepare($dbc, "SELECT id FROM osobe WHERE email = ? and status='aktivan'");
    mysqli_stmt_bind_param($provera, "s", $email);
    mysqli_stmt_execute($provera);
    $rezProvere = mysqli_stmt_get_result($provera);
    if(mysqli_num_rows($rezProvere) > 0){
        $_SESSION['error'] = "<p class='text-danger mt-2'>Korisnik već postoji!</p>";
        header("Location: registracija.php");
        exit();
    }
    $hasLozinka = password_hash($lozinka1, PASSWORD_DEFAULT);
    $upit = mysqli_prepare($dbc, "INSERT INTO osobe(ime, prezime, email, telefon, lozinka) 
                                VALUES (?, ?, ?, ?, ?)");
    mysqli_stmt_bind_param($upit, "sssss",$ime,$prezime,$email,$telefon,$hasLozinka);
    mysqli_stmt_execute($upit);
    if(mysqli_stmt_affected_rows($upit) == 1){
        $_SESSION['id'] = mysqli_insert_id($dbc);
        $_SESSION['ime'] = $ime;
        $_SESSION['uloga'] = 'korisnik';
        setcookie($osoba['uloga'],$osoba['ime'], time() + 86400, "/");
        header("Location: index.php");
        exit();
    }
    else{
        $_SESSION['error'] = "<p class='text-danger mt-2'>Greška pri registraciji.</p>";
        header("Location: registracija.php");
        exit();
    }
    mysqli_stmt_close($upit);
}
?>