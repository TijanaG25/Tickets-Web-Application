<?php
session_start();
require_once('konekcija.php');
if(!isset($_SESSION['id'])){
    header("Location: prijava.php");
    exit();
}

if(!isset($_SESSION['korpa']) || !is_array($_SESSION['korpa']) || count($_SESSION['korpa']) == 0){
    $prazna = true;
} else {
    $prazna = false;
}
if(isset($_POST['plati']) && !$prazna) {
    $korisnikId = (int)$_SESSION['id'];
    $korpa = $_SESSION['korpa'];
    mysqli_begin_transaction($dbc);
    try {
        $totalCena = 0;
        $karteId = [];
        $txt = "Prime Tickets - Vaše karte\n";
        $txt .= "Datum kupovine: " . date('d.m.Y H:i') . "\n";
        $txt .= "-------------------------------------\n";
        foreach($korpa as $stavka){
            if(!is_array($stavka) || !isset($stavka['idDogadjaja'], $stavka['idMesta'])) continue;
            $idDog = (int)$stavka['idDogadjaja'];
            $idMesta = (int)$stavka['idMesta'];
            $rezMesto = mysqli_query($dbc, "SELECT red, broj, idSale 
                                            FROM mesta 
                                            WHERE id = $idMesta");
            $mesto = mysqli_fetch_assoc($rezMesto);
            $red = $mesto['red'];
            $broj = $mesto['broj'];
            $idSale = $mesto['idSale'];
            $rezMaxRed = mysqli_query($dbc, "SELECT MAX(red) AS maxRed 
                                            FROM mesta 
                                            WHERE idSale = $idSale");
            $maxRed = mysqli_fetch_assoc($rezMaxRed)['maxRed'];
            $rezDog = mysqli_query($dbc, "SELECT naziv, cena 
                                            FROM dogadjaji 
                                            WHERE id = $idDog");
            $dogadjaj = mysqli_fetch_assoc($rezDog);
            $cena = $dogadjaj['cena'] + (($maxRed - $red) * 1000);
            $totalCena += $cena;
            $stmtKarta = mysqli_prepare($dbc, "INSERT INTO karte (cena, idMesta, idDogadjaja) 
                                                VALUES (?, ?, ?)");
            mysqli_stmt_bind_param($stmtKarta, 'dii', $cena, $idMesta, $idDog);
            mysqli_stmt_execute($stmtKarta);
            $karteId[] = mysqli_insert_id($dbc);
            $txt .= "Događaj: " . $dogadjaj['naziv'] . "\n";
            $txt .= "Red: $red | Broj: $broj\n";
            $txt .= "Cena: " . number_format($cena,0) . " RSD\n";
            $txt .= "-------------------------------------\n";
        }
        $stmtKupovina = mysqli_prepare($dbc, "INSERT INTO kupovine (datum_kupovine, ukupnaCena, idKorisnika) 
                                            VALUES (NOW(), ?, ?)");
        mysqli_stmt_bind_param($stmtKupovina, 'di', $totalCena, $korisnikId);
        mysqli_stmt_execute($stmtKupovina);
        $idKupovine = mysqli_insert_id($dbc);
        foreach($karteId as $idKarte){
            $stmtStavka = mysqli_prepare($dbc, "INSERT INTO stavkekupovine (idKupovine, idKarte) 
                                                VALUES (?, ?)");
            mysqli_stmt_bind_param($stmtStavka, 'ii', $idKupovine, $idKarte);
            mysqli_stmt_execute($stmtStavka);
        }
        mysqli_commit($dbc);
        unset($_SESSION['korpa']);
        $txt .= "Ukupno: " . number_format($totalCena,0) . " RSD\n";
        $txt .= "Hvala što ste kupili karte preko Prime Tickets!\n";
        $imeFajla = "karte_" . time() . ".txt";
        header('Content-Type: text/plain');
        header('Content-Disposition: attachment; filename="'.$imeFajla.'"');
        header('Content-Length: ' . strlen($txt));
        echo $txt;
        exit();
    } 
    catch(Exception $e){
        mysqli_rollback($dbc);
        echo "<p class='text-danger'>Došlo je do greške prilikom plaćanja.</p>";
    }
}
if(isset($_GET['ukloni'])) {
    $ukloniIndeks = (int)$_GET['ukloni'];
    if(isset($_SESSION['korpa'][$ukloniIndeks])) {
        unset($_SESSION['korpa'][$ukloniIndeks]);
        $_SESSION['korpa'] = array_values($_SESSION['korpa']);
    }
    header("Location: korpa.php");
    exit();
}
$korpaDetalji = [];
$totalCena = 0;
if(!$prazna){
    foreach($_SESSION['korpa'] as $indeks => $stavka){
        if(!is_array($stavka) || !isset($stavka['idDogadjaja']) || !isset($stavka['idMesta'])) continue;
        $idDog = (int)$stavka['idDogadjaja'];
        $idMesta = (int)$stavka['idMesta'];
        $upitDog = "SELECT d.naziv, d.cena, s.naziv AS sala, a.grad, a.ulica, a.broj
                    FROM dogadjaji d
                    JOIN sale s ON d.idSale = s.id
                    JOIN adrese a ON s.idAdrese = a.id
                    WHERE d.id = $idDog";
        $rezDog = mysqli_query($dbc, $upitDog);
        if($rezDog && mysqli_num_rows($rezDog) > 0){
            $dogadjaj = mysqli_fetch_assoc($rezDog);
            $upitMesto = "SELECT red, broj, idSale 
                        FROM mesta 
                        WHERE id = $idMesta";
            $rezMesto = mysqli_query($dbc, $upitMesto);
            if($rezMesto && mysqli_num_rows($rezMesto) > 0){
                $mesto = mysqli_fetch_assoc($rezMesto);
                $red = $mesto['red'];
                $broj = $mesto['broj'];
                $upitMaxRed = "SELECT MAX(red) AS maxRed 
                                FROM mesta 
                                WHERE idSale = {$mesto['idSale']}";
                $rezMaxRed = mysqli_query($dbc, $upitMaxRed);
                $maxRed = mysqli_fetch_assoc($rezMaxRed)['maxRed'];
                $cenaPoMestu = $dogadjaj['cena'] + (($maxRed - $red) * 1000);
                $totalCena += $cenaPoMestu;
                $korpaDetalji[] = ['indeks' => $indeks,'idDogadjaja' => $idDog,'nazivDogadjaja' => $dogadjaj['naziv'],'sala' => $dogadjaj['sala'],'lokacija' => $dogadjaj['grad'] . ', ' . $dogadjaj['ulica'] . ' ' . $mesto['broj'],'red' => $red,'broj' => $broj,'cena' => $cenaPoMestu];
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Korpa</title>
    <link rel="icon" type="image/png" href="images/logo.png">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="styles/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
</head>
<body>
<!--NAVBAR-->
<nav class="navbar navbar-expand-lg navbar-dark px-4">
  <a class="navbar-brand" href="index.php">
    <img src="images/logo.png" alt="Prime Tickets" class="d-inline-block align-text-top" height="50">
  </a>
  <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
    <ul class="navbar-nav">
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">KONCERTI</a>
        <ul class="dropdown-menu">
          <?php 
          $koncerti=['Pop','Rock','Dj','Hard and Heavy','Džez i bluz','Klasična muzika','Opera','Elektro','Narodna muzika','Hip hop','Svi događaji'];
          foreach($koncerti as $k) {
              echo "<li><a class='dropdown-item' href='koncerti.php?vrsta=".urlencode($k)."'>$k</a></li>";
          }
          ?>
        </ul>
      </li>
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">POZORIŠTA</a>
        <ul class="dropdown-menu">
          <?php 
          $pozorista=['Balet','Dečije predstave','Komedije','Mjuzikli','One man show','Stand up','Predstava','Svi događaji'];
          foreach($pozorista as $p) {
              echo "<li><a class='dropdown-item' href='pozorista.php?vrsta=".urlencode($p)."'>$p</a></li>";
          }
          ?>
        </ul>
      </li>
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">SPORT</a>
        <ul class="dropdown-menu">
          <?php 
          $sport=['Košarka','Vaterpolo','Svi događaji'];
          foreach($sport as $s) {
              echo "<li><a class='dropdown-item' href='sport.php?vrsta=".urlencode($s)."'>$s</a></li>";
          }
          ?>
        </ul>
      </li>
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">FESTIVALI</a>
        <ul class="dropdown-menu">
          <?php 
          $festivali=['Muzika','Film','Svi događaji'];
          foreach($festivali as $f) {
              echo "<li><a class='dropdown-item' href='festivali.php?vrsta=".urlencode($f)."'>$f</a></li>";
          }
          ?>
        </ul>
      </li>
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">MUZEJI</a>
        <ul class="dropdown-menu">
          <?php 
          $muzeji=['Istorija','Tura sa vodičem','Svi događaji'];
          foreach($muzeji as $m) {
              echo "<li><a class='dropdown-item' href='muzeji.php?vrsta=".urlencode($m)."'>$m</a></li>";
          }
          ?>
        </ul>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="vesti.php">VESTI</a>
      </li>
      <li class="nav-item search-item position-relative">
          <a class="nav-link" href="#" id="searchToggle"><i class="bi bi-search"></i></a>
          <input type="text" id="searchInput" class="form-control position-absolute" placeholder="Pretraži..." />
      </li>
      <li class="nav-item dropdown">
        <?php
            if(isset($_SESSION['uloga'])) {
             if($_SESSION['uloga'] === 'administrator') {
                  echo '<a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"><i class="bi bi-person-circle"></i>Admin</a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="admin.php">Admin panel</a></li>
                            <li><a class="dropdown-item" href="profil.php">Moj profil</a></li></ul>';
                } 
            else {
                echo '<a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"><i class="bi bi-person-circle"></i>Profil</a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="profil.php">Moj profil</a></li>
                    </ul>';
            }
            } 
            else {
                echo '<a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"><i class="bi bi-person-circle"></i></a>
                <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="prijava.php">Prijava</a></li>
                        <li><a class="dropdown-item" href="registracija.php">Registracija</a></li>
                </ul>';
            }
        ?>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="korpa.php"><i class="bi bi-cart-fill"></i></a>
      </li>
    </ul>
  </div>
</nav>

<div class="container my-5">
    <h2 class="mb-4">Vaša korpa</h2>
<?php
    if ($prazna) {
        echo '<p class="text-danger">Vaša korpa je prazna.</p>';
        } 
        else {
?>
    <table class="table table-bordered">
        <thead class="table-dark">
            <tr>
                <th>Događaj</th>
                <th>Lokacija</th>
                <th>Sala</th>
                <th>Red</th>
                <th>Broj</th>
                <th>Cena</th>
                <th>Ukloni</th>
            </tr>
        </thead>
        <tbody>

<?php
    foreach ($korpaDetalji as $stavka) {
?>
            <tr>
                <td><?php echo $stavka['nazivDogadjaja']; ?></td>
                <td><?php echo $stavka['lokacija']; ?></td>
                <td><?php echo $stavka['sala']; ?></td>
                <td><?php echo $stavka['red']; ?></td>
                <td><?php echo $stavka['broj']; ?></td>
                <td><?php echo number_format($stavka['cena'], 0); ?> RSD</td>
                <td>
                    <a href="korpa.php?ukloni=<?php echo $stavka['indeks']; ?>"class="btn btn-sm btn-danger">X</a>
                </td>
            </tr>
            <?php }?>
            <tr class="table-warning">
                <td colspan="5" class="text-end"><strong>Ukupno:</strong></td>
                <td colspan="2">
                    <strong><?php echo number_format($totalCena, 0); ?> RSD</strong>
                </td>
            </tr>
        </tbody>
    </table>
    <form method="POST">
        <button type="submit" name="plati" class="btn btn-success">Plati</button>
    </form>
    <?php } ?>
</div>

<!--FOOTER-->
<footer class="footer mt-5">
 <div class="footer-sponsors">
  <div class="sponsor-track">
    <a href="https://www.bancaintesa.rs/"><img src="images/intesa.jpg" alt="Sponsor 1"></a>
    <a href="https://mts.rs/"><img src="images/mts.png" alt="Sponsor 2"></a>
    <a href="https://www.posted.co.rs/"><img src="images/postanska.png" alt="Sponsor 3"></a>
    <a href="https://www.raiffeisenbank.rs/sr/stanovnistvo.html"><img src="images/raiffeisen.png" alt="Sponsor 4"></a>
    <a href="https://tickets.rs"><img src="images/tickets.png" alt="Sponsor 5"></a>
    <a href="https://www.yettel.rs/"><img src="images/yettel.png" alt="Sponsor 6"></a>
    <a href="https://www.bancaintesa.rs/"><img src="images/intesa.jpg" alt="Sponsor 1"></a>
    <a href="https://mts.rs/"><img src="images/mts.png" alt="Sponsor 2"></a>
    <a href="https://www.posted.co.rs/"><img src="images/postanska.png" alt="Sponsor 3"></a>
    <a href="https://www.raiffeisenbank.rs/sr/stanovnistvo.html"><img src="images/raiffeisen.png" alt="Sponsor 4"></a>
    <a href="https://tickets.rs"><img src="images/tickets.png" alt="Sponsor 5"></a>
    <a href="https://www.yettel.rs/"><img src="images/yettel.png" alt="Sponsor 6"></a>
    <a href="https://www.bancaintesa.rs/"><img src="images/intesa.jpg" alt="Sponsor 1"></a>
    <a href="https://mts.rs/"><img src="images/mts.png" alt="Sponsor 2"></a>
    <a href="https://www.posted.co.rs/"><img src="images/postanska.png" alt="Sponsor 3"></a>
    <a href="https://www.raiffeisenbank.rs/sr/stanovnistvo.html"><img src="images/raiffeisen.png" alt="Sponsor 4"></a>
    <a href="https://tickets.rs"><img src="images/tickets.png" alt="Sponsor 5"></a>
    <a href="https://www.yettel.rs/"><img src="images/yettel.png" alt="Sponsor 6"></a>
  </div>
</div>
  <div class="footer-content container py-4 text-light">
    <div class="row">
      <div class="col-md-6 mb-3">
        <h5>Linkovi</h5>
        <ul class="list-unstyled">
          <li><a href="oNama.php" class="footer-link" style="color: #f1d66c !important;text-decoration: none !important;"><strong>O nama</strong></a></li>
          <li><a href="faq.php" class="footer-link" style="color: #f1d66c !important;text-decoration: none !important;"><strong>FAQ</strong></a></li>
        </ul>
      </div>
      <div class="col-md-6 mb-3">
        <h5>Pratite nas</h5>
        <div class="social-icons mt-2">
          <a href="https://www.instagram.com/viserbgd/"><i class="bi bi-instagram"></i></a>
          <a href="https://www.youtube.com/@avt_viser_atuss"><i class="bi bi-youtube"></i></a>
          <a href="https://x.com/VISERBgd"><i class="bi bi-twitter"></i></a>
        </div>
      </div>
    </div>
  </div>
  <div class="footer-bottom text-center py-2 text-muted">
    &copy; 2026 Prime Tickets. Sva prava zadržana.
  </div>
</footer>
<script>
    const searchToggle = document.getElementById('searchToggle');
    const searchItem = document.querySelector('.search-item');
    const searchInput = document.getElementById('searchInput');
    searchToggle.addEventListener('click', function(e) {
        e.preventDefault();
        searchItem.classList.toggle('open');
        if(searchItem.classList.contains('open')) {
            searchInput.focus();
        } else {
            searchInput.value = '';
        }
    });
    document.addEventListener('click', function(e){
        if(!searchItem.contains(e.target)) {
            searchItem.classList.remove('open');
            searchInput.value = '';
        }
    });
</script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>