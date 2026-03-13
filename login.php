<?php
session_start();
require_once('konekcija.php');
header('Content-Type: application/json');
if(isset($_POST['login'])){
    $email = trim($_POST['email']);
    $lozinka = trim($_POST['lozinka']);
    $upit = mysqli_prepare($dbc, "SELECT * FROM osobe WHERE email = ? AND status = 'aktivan'");
    mysqli_stmt_bind_param($upit, "s", $email);
    mysqli_stmt_execute($upit);
    $rez = mysqli_stmt_get_result($upit);
    if(mysqli_num_rows($rez) === 1){
        $osoba = mysqli_fetch_assoc($rez);
        if(password_verify($lozinka, $osoba['lozinka'])){
            $_SESSION['id'] = $osoba['id'];
            $_SESSION['ime'] = $osoba['ime'];
            $_SESSION['uloga'] = $osoba['uloga'];
            setcookie($osoba['uloga'],$osoba['ime'], time() + 86400, "/");
            if($_SESSION['uloga']=='administrator'){
                echo json_encode(["status" => "uspesno",
                            "message" => "Uspešna prijava!",
                            "redirect" => "admin.php"]);
            }
            else{
                echo json_encode(["status" => "uspesno",
                            "message" => "Uspešna prijava!",
                            "redirect" => "index.php"]);
            }
            mysqli_stmt_close($upit);
            exit();
        }
    }
    echo json_encode([
        "status" => "error",
        "message" => "Pogrešan email ili lozinka!"
    ]);
    mysqli_stmt_close($upit);
    exit();
}
?>