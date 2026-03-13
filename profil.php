<?php
session_start();
require_once('konekcija.php');

if (!isset($_SESSION['id'])) {
    header("Location: prijava.php");
    exit();
}
$id = (int)$_SESSION['id'];
if (isset($_POST['obrisi_nalog'])) {
    $stmt = mysqli_prepare($dbc, "UPDATE osobe SET status='deaktiviran' WHERE id=?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    header("Location: logout.php");
    exit();
}
if (isset($_POST['izmeni'])) {
    $ime = trim($_POST['ime']);
    $prezime = trim($_POST['prezime']);
    $telefon = trim($_POST['telefon']);
    $lozinka1 = $_POST['lozinka1'];
    $lozinka2 = $_POST['lozinka2'];
    if (empty($ime) || empty($prezime) || empty($telefon)) {
        $_SESSION['statusIzmene'] = "<p class='text-danger'>Sva polja osim lozinke su obavezna!</p>";
        header("Location: profil.php");
        exit();
    }
    if (!empty($lozinka1) || !empty($lozinka2)) {
        if ($lozinka1 !== $lozinka2) {
            $_SESSION['statusIzmene'] = "<p class='text-danger'>Lozinke se ne poklapaju!</p>";
            header("Location: profil.php");
            exit();
        }
        $hash = password_hash($lozinka1, PASSWORD_DEFAULT);
        $stmt = mysqli_prepare($dbc,"UPDATE osobe SET ime=?, prezime=?, telefon=?, lozinka=? WHERE id=?");
        mysqli_stmt_bind_param($stmt, "ssssi", $ime, $prezime, $telefon, $hash, $id);
    } else {
        $stmt = mysqli_prepare($dbc,"UPDATE osobe SET ime=?, prezime=?, telefon=? WHERE id=?");
        mysqli_stmt_bind_param($stmt, "sssi", $ime, $prezime, $telefon, $id);
    }
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    $_SESSION['ime'] = $ime;
    $_SESSION['statusIzmene'] = "<p class='text-success'>Podaci su uspešno izmenjeni!</p>";
    header("Location: profil.php");
    exit();
}
$stmt = mysqli_prepare($dbc,
"SELECT DISTINCT d.*,s.naziv AS sala,m.red AS redMesta,m.broj AS brojMesta,a.grad AS grad,d.slika AS slika
 FROM dogadjaji d JOIN karte k ON k.idDogadjaja = d.id
                  JOIN stavkekupovine sk ON sk.idKarte = k.id
                  JOIN kupovine ku ON ku.id = sk.idKupovine
                  JOIN osobe o ON o.id = ku.idKorisnika
                  JOIN sale s ON s.id = d.idSale
                  JOIN adrese a ON a.id = s.idAdrese
                  JOIN mesta m ON m.id = k.idMesta
 WHERE o.id = ? AND d.status = 'aktivan' AND d.datum_vreme > NOW()
 ORDER BY d.datum_vreme ASC");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$dogadjaji = mysqli_stmt_get_result($stmt);
mysqli_stmt_close($stmt);
$stmt = mysqli_prepare($dbc,
"SELECT DISTINCT d.*, s.naziv as sala, m.red as redMesta, m.broj as brojMesta, a.grad as grad, d.slika as slika
 FROM dogadjaji d JOIN karte k ON k.idDogadjaja=d.id
                  JOIN stavkekupovine sk ON sk.idKupovine=k.id
                  JOIN kupovine ku ON ku.id=sk.idKupovine
                  JOIN osobe o ON o.id=ku.idKorisnika
                  JOIN sale s ON s.id=d.idSale
                  JOIN adrese a ON a.id=s.idAdrese
                  JOIN mesta m ON m.id=k.idMesta
 WHERE o.id=? AND d.status='otkazan' AND datum_vreme>NOW()
 ORDER BY d.datum_vreme ASC");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$otkazanidogadjaji = mysqli_stmt_get_result($stmt);
mysqli_stmt_close($stmt);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link rel="icon" type="image/png" href="images/logo.png">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="styles/styleLogin.css">
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
<div class="d-flex justify-content-center align-items-start py-5">
<div class="card p-4 shadow" style="width: 600px; background:#161515; color:white;">
  <?php
  echo"<h2 class='card-header mb-4'>{$_SESSION['ime']}</h2>";
  if (isset($_SESSION['statusIzmene'])) {
      echo $_SESSION['statusIzmene'];
      unset($_SESSION['statusIzmene']);
  }
  ?>
    <h3 class="card-header mb-4">Vaši predstojeći događaji</h3>
    <?php 
    if (mysqli_num_rows($dogadjaji) > 0) {
        while ($d = mysqli_fetch_assoc($dogadjaji)) {
            echo "<div class='event-card d-flex align-items-center'>";
             if (!empty($d['slika'])) {
                echo "<img src='images/" . $d['slika'] . "' class='event-img me-3' style='width: 180px; height:180px;object-fit: contain;' alt='Slika događaja'>";
            }
            echo "<div>";
            echo "<h5 class='text-warning'>" . $d['naziv'] . "</h5>";
            echo "<p>" . $d['organizator'] . "</p>";
            echo "<p>" . $d['sala'] . " - " . $d['grad'] . "</p>";
            echo "<p>" . date("d.m.Y H:i", strtotime($d['datum_vreme'])) . "</p>";
            echo "<p><strong>Sedišta: </strong><br>Red: " . $d['redMesta']."  Broj: " . $d['brojMesta'] . "</p>";
            echo "</div>";
            echo "</div>";
            }
        }
        else {
        echo "<p class='text-center text-muted'>Nemate kupljenih karata.</p>";
        }?>
        <br>
        <h3 class="card-header mb-4">Otkazani događaji</h3>
    <?php 
    if (mysqli_num_rows($otkazanidogadjaji) > 0) {
        while ($d = mysqli_fetch_assoc($otkazanidogadjaji)) {
            echo "<div class='event-card d-flex align-items-center'>";
             if (!empty($d['slika'])) {
                echo "<img src='images/" . $d['slika'] . "' class='event-img me-3' style='width: 180px; height:180px;object-fit: contain;' alt='Slika događaja'>";
            }
            echo "<div>";
            echo "<h5 class='text-warning'>" . $d['naziv'] . "</h5>";
            echo "<p>" . $d['organizator'] . "</p>";
            echo "<p>" . $d['sala'] . " - " . $d['grad'] . "</p>";
            echo "<p>" . date("d.m.Y H:i", strtotime($d['datum_vreme'])) . "</p>";
            echo "<p><strong>Sedišta: </strong><br>Red: " . $d['redMesta']."  Broj: " . $d['brojMesta'] . "</p>";
            echo "</div>";
            echo "</div>";
            }
        }
        else {
        echo "<p class='text-center text-muted'>Nemate kupljenih karata.</p>";
        }?>
    <hr class="my-4">
    <div class="text-center">
        <form method="POST">
            <button name="obrisi_nalog" class="btn btn-danger btn-profile w-100 mb-2">Obriši nalog</button>
        </form>
        <button class="btn btn-warning btn-profile w-100 mb-2" type="button" data-bs-toggle="collapse" data-bs-target="#izmena">Izmeni podatke</button>
        <div class="collapse mt-3" id="izmena">
            <form method="POST">
                <input type="text" name="ime" class="form-control mb-2" placeholder="Novo ime">
                <input type="text" name="prezime" class="form-control mb-2" placeholder="Novo prezime">
                <input type="text" name="telefon" class="form-control mb-2" placeholder="Novi telefon">
                <input type="password" name="lozinka1" class="form-control mb-2" placeholder="Nova lozinka">
                <input type="password" name="lozinka2" class="form-control mb-2" placeholder="Ponovite lozinku">
                <button type="submit" name="izmeni" class="btn btn-success w-100">Sačuvaj izmene</button>
            </form>
        </div>
        <form method="POST" action="logout.php" class="mt-2">
            <button class="btn btn-secondary btn-profile w-100">Odjavi se</button>
        </form>
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