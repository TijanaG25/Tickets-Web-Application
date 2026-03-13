<?php
session_start();
require_once('konekcija.php');
if(!isset($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$idDogadjaja = (int)$_GET['id'];
$upit = "SELECT d.*, s.naziv AS nazivSale, a.grad, a.ulica, a.broj
         FROM dogadjaji d JOIN sale s ON d.idSale = s.id
                          JOIN adrese a ON s.idAdrese = a.id
         WHERE d.id = $idDogadjaja AND d.status='aktivan'";
$rez = mysqli_query($dbc, $upit);
if(!$rez || mysqli_num_rows($rez) == 0){
    die("<p>Događaj nije pronađen ili je otkazan.</p>");
}
$dogadjaj = mysqli_fetch_assoc($rez);
$upitMesta = "SELECT m.id, m.red, m.broj
              FROM mesta m
              JOIN sale s ON m.idSale = s.id
              JOIN dogadjaji d ON s.id = d.idSale
              LEFT JOIN karte k ON m.id = k.idMesta AND k.idDogadjaja = $idDogadjaja
              WHERE k.id IS NULL AND d.id = $idDogadjaja";
$rezMesta = mysqli_query($dbc, $upitMesta);
$slobodnaMesta = [];
if($rezMesta){
    while($m = mysqli_fetch_assoc($rezMesta)){
        $slobodnaMesta[] = $m;
    }
}
if(isset($_POST['korpa'])) {
    if(isset($_SESSION['id'])) {
        if(!empty($_POST['mesta'])) {
            if(!isset($_SESSION['korpa'])) {
                $_SESSION['korpa'] = [];
            }
            foreach($_POST['mesta'] as $idMesta) {
                $_SESSION['korpa'][] = ['idDogadjaja' => $idDogadjaja,'idMesta' => $idMesta];
            }
        }
        header("Location: korpa.php");
        exit();
    } else {
        header("Location: prijava.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Karta</title>
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
    <div class="row">
        <div class="col-md-6">
            <img src="images/<?= $dogadjaj['slika']?>" class="img-fluid rounded" alt="<?=$dogadjaj['naziv']?>">
        </div>
        <div class="col-md-6">
            <h2 class="text-warning"><?= $dogadjaj['naziv'] ?></h2>
            <p><strong><?=$dogadjaj['organizator']?></strong></p>
            <p><strong>Lokacija:</strong> <?=$dogadjaj['grad'] . ', ' . $dogadjaj['ulica'] . ' ' . $dogadjaj['broj']?> (<?=$dogadjaj['nazivSale']?>)</p>
            <p><strong>Datum:</strong> <?= date('d.m.Y', strtotime($dogadjaj['datum_vreme'])) ?></p>
            <p><strong>Vreme:</strong> <?= date('H:i', strtotime($dogadjaj['datum_vreme'])) ?></p>
            <p><i><?= nl2br(htmlspecialchars($dogadjaj['opis'])) ?></i></p>
            <p><strong>Slobodna mesta:</strong> <?= count($slobodnaMesta) ?></p>
            <?php
            if(count($slobodnaMesta) > 0) {
                $mestaPoRedu = [];
                $maxRed = 0;
                foreach($slobodnaMesta as $mesto) {
                    $mestaPoRedu[$mesto['red']][] = $mesto;
                    if($mesto['red'] > $maxRed) $maxRed = $mesto['red'];
                }
                echo '<form method="POST">';
                foreach($mestaPoRedu as $red => $mesta) {
                    $cenaPoRedu = $dogadjaj['cena'] + (($maxRed - $red) * 1000);
                    echo '<div class="row mb-3 p-2 border rounded bg-dark text-light">';
                    echo '<div class="col-12 mb-2"><strong>Red ' . $red . ' — Cena: ' . number_format($cenaPoRedu,0) . ' RSD</strong></div>';
                    foreach($mesta as $m) {
                        echo '<div class="col-auto">';
                        echo '<div class="form-check">';
                        echo '<input class="form-check-input" type="checkbox" name="mesta[]" value="' . $m['id'] . '" id="mesto' . $m['id'] . '">';
                        echo '<label class="form-check-label" for="mesto' . $m['id'] . '">Broj ' . $m['broj'] . '</label>';
                        echo '</div></div>';
                    }
                    echo '</div>';
                }
                echo '<button type="submit" name="korpa" class="btn btn-warning">Dodaj u korpu</button>';
                echo '</form>';
            } else {
                echo '<p class="text-danger"><strong>Nažalost, nema slobodnih mesta za ovaj događaj.</strong></p>';
            }
            ?>
        </div>
    </div>
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