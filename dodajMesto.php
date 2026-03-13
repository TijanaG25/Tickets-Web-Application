<?php
session_start();
require_once "konekcija.php";
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $nazivSale = trim($_POST['naziv']);
    $grad = trim($_POST['grad']);
    $ulica = trim($_POST['ulica']);
    $broj1 = trim($_POST['brojSale']);
    $redovi = $_POST['red']; 
    $brojevi = $_POST['broj'];
    $adresa = mysqli_prepare($dbc, "INSERT INTO adrese (grad, ulica, broj) VALUES (?, ?, ?)");
    mysqli_stmt_bind_param($adresa, "sss", $grad, $ulica, $broj1);
    mysqli_stmt_execute($adresa);
    $idAdrese = mysqli_insert_id($dbc);
    mysqli_stmt_close($adresa);
    $sala = mysqli_prepare($dbc, "INSERT INTO sale (naziv, idAdrese) VALUES (?, ?)");
    mysqli_stmt_bind_param($sala, "si", $nazivSale, $idAdrese);
    mysqli_stmt_execute($sala);
    $idSale = mysqli_insert_id($dbc);
    mysqli_stmt_close($sala);
    for($i = 0; $i < count($redovi); $i++){
        $r = intval($redovi[$i]);      
        $b = intval($brojevi[$i]);
        for($j = 1; $j <= $b; $j++){ 
            $provera = mysqli_prepare($dbc,"SELECT id 
                                            FROM mesta 
                                            WHERE red=? AND broj=? AND idSale=?");
            mysqli_stmt_bind_param($provera, "iii", $r, $j, $idSale);
            mysqli_stmt_execute($provera);
            mysqli_stmt_store_result($provera);
            if(mysqli_stmt_num_rows($provera) === 0){
                $mesto = mysqli_prepare($dbc,"INSERT INTO mesta (red, broj, idSale) 
                                                VALUES (?, ?, ?)");
                mysqli_stmt_bind_param($mesto, "iii", $r, $j, $idSale);
                mysqli_stmt_execute($mesto);
                mysqli_stmt_close($mesto);
        }
        mysqli_stmt_close($provera);
    }
}
header("Location: admin.php");
exit();
}
?>